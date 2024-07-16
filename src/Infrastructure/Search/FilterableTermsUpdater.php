<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Search;

use ArrayObject;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\AdmissionRequirement;
use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Domain\MultilingualLink;
use Fau\DegreeProgram\Common\Domain\MultilingualLinks;
use Fau\DegreeProgram\Common\Domain\MultilingualList;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\AreaOfStudyTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\AttributeTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\BachelorOrTeachingDegreeAdmissionRequirementTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\DegreeTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\FacultyTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\GermanLanguageSkillsForInternationalStudentsTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\MasterDegreeAdmissionRequirementTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\SemesterTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\StudyLocationTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\SubjectGroupTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TeachingDegreeHigherSemesterAdmissionRequirementTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TeachingLanguageTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Repository\BilingualRepository;
use Fau\DegreeProgram\Common\Infrastructure\Repository\CampoKeysRepository;
use Fau\DegreeProgram\Common\Infrastructure\Repository\IdGenerator;
use LogicException;
use Psr\Log\LoggerInterface;
use WP_Error;
use WP_Post;

final class FilterableTermsUpdater
{
    public const ORIGINAL_TERM_ID_KEY = 'fau_original_term_id';
    private const TAXONOMIES_CACHE_NAME_PROPERTY = 'name';
    private const TAXONOMIES_CACHE_ORIGINAL_ID_PROPERTY = 'original_id';

    public function __construct(
        private DegreeProgramCollectionRepository $degreeProgramCollectionRepository,
        private IdGenerator $idGenerator,
        private LoggerInterface $logger,
    ) {
    }

    public function updateFully(): void
    {
        $criteria = CollectionCriteria::new()
            ->withoutPagination();

        $this->update($criteria);

        $this->logger->info('Degree program filterable terms updated fully.');
    }

    /**
     * @param array<int> $ids
     */
    public function updatePartially(array $ids): void
    {
        $criteria = CollectionCriteria::new()
            ->withoutPagination()
            ->withInclude($ids);

        $this->update($criteria);

        $this->logger->info(
            sprintf(
                'Degree program filterable terms updated for IDs: %s.',
                implode(', ', $ids)
            )
        );
    }

    private function update(CollectionCriteria $criteria): void
    {
        $rawCollection = $this->degreeProgramCollectionRepository->findRawCollection($criteria);
        if (!$rawCollection instanceof PaginationAwareCollection) {
            return;
        }

        /** @var DegreeProgramViewRaw $rawView */
        foreach ($rawCollection as $rawView) {
            $postId = $rawView->id()->asInt();
            if (!$this->isValidPostId($postId)) {
                throw new LogicException(sprintf('Post %d is not a degree program.', $postId));
            }

            $taxonomyProperties = $this->retrieveFilterableProperties($rawView);
            foreach ($taxonomyProperties as $taxonomy => $property) {
                $this->setObjectTerms($rawView, $taxonomy, $property);
            }
        }
    }

    /**
     * phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
     * @return array<string, MultilingualString|MultilingualList|MultilingualLink|MultilingualLinks|Degree|AdmissionRequirement>
     */
    private function retrieveFilterableProperties(DegreeProgramViewRaw $rawView): array
    {
        return [
            SemesterTaxonomy::KEY =>
                $rawView->start(),
            TeachingLanguageTaxonomy::KEY =>
                $rawView->teachingLanguage(),
            AttributeTaxonomy::KEY =>
                $rawView->attributes(),
            DegreeTaxonomy::KEY =>
                $rawView->degree(),
            FacultyTaxonomy::KEY =>
                $rawView->faculty(),
            StudyLocationTaxonomy::KEY =>
                $rawView->location(),
            SubjectGroupTaxonomy::KEY =>
                $rawView->subjectGroups(),
            BachelorOrTeachingDegreeAdmissionRequirementTaxonomy::KEY =>
                $rawView->admissionRequirements()->bachelorOrTeachingDegree(),
            TeachingDegreeHigherSemesterAdmissionRequirementTaxonomy::KEY =>
                $rawView->admissionRequirements()->teachingDegreeHigherSemester(),
            MasterDegreeAdmissionRequirementTaxonomy::KEY =>
                $rawView->admissionRequirements()->master(),
            AreaOfStudyTaxonomy::KEY =>
                $rawView->areaOfStudy(),
            GermanLanguageSkillsForInternationalStudentsTaxonomy::KEY =>
                $rawView->germanLanguageSkillsForInternationalStudents(),
        ];
    }

    /**
     * @psalm-param array<int, MultilingualString> $terms
     */
    private function setObjectTerms(
        DegreeProgramViewRaw $rawView,
        string $taxonomy,
        MultilingualString|MultilingualList|MultilingualLink|MultilingualLinks|Degree|AdmissionRequirement $property
    ): void {

        $termIds = $this->maybeCreateTerms($taxonomy, $property);
        wp_set_object_terms(
            $rawView->id()->asInt(),
            $termIds,
            $taxonomy
        );

        if (! isset($termIds[0])) {
            return;
        }

        $this->maybeUpdateCampoKeys($rawView, $taxonomy, (int) $termIds[0]);
    }

    private function isValidPostId(int $postId): bool
    {
        if ($postId <= 0) {
            return false;
        }

        $post = get_post($postId);
        if (!$post instanceof WP_Post) {
            return false;
        }

        if ($post->post_type !== DegreeProgramPostType::KEY) {
            return false;
        }

        return $post->post_status === 'publish';
    }

    /**
     * phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     *
     * Refactoring could decrease performance.
     */
    private function maybeCreateTerms(
        string $taxonomy,
        MultilingualString|MultilingualList|MultilingualLink|MultilingualLinks|Degree|AdmissionRequirement $property
    ): array {

        /**
         * phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
         * @psalm-var array<string, array<int, array{name: string, original_id: int}>> $taxonomiesCache
         *          array where keys are taxonomy slugs,
         *          nested array where keys are term IDs, values are remote term data
         */
        static $taxonomiesCache = [];
        if (!array_key_exists($taxonomy, $taxonomiesCache)) {
            $taxonomiesCache[$taxonomy] = $this->populateTaxonomiesCache($taxonomy);
        }

        $result = [];

        // phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
        /** @var array<MultilingualString|MultilingualLink|Degree|AdmissionRequirement> $flatProperties */
        $flatProperties = $property instanceof ArrayObject ? $property->getArrayCopy() : [$property];

        // Identify parent terms and place them at the beginning of the term list to ensure they are created
        // before updating or creating a term.
        $flatProperties = $this->extendPropertiesWithParents($flatProperties);

        foreach ($flatProperties as $flatProperty) {
            if (empty($flatProperty->id())) {
                continue;
            }

            $termData = $this->generateTermData($flatProperty, $taxonomiesCache, $taxonomy);

            if (is_null($termData)) {
                $this->logger->error(
                    'Could not retrieve ID from structure.',
                    [
                        'structure' => $flatProperty,
                    ]
                );
                continue;
            }

            if ($termData->termId()) {
                // Term was persisted already
                $result[] = $termData->termId();
                $this->updateTerm($termData);
                continue;
            }

            $termId = $this->createTerm($termData);

            if (is_int($termId)) {
                $result[] = $termId;
                $taxonomiesCache[$taxonomy][$termId] = [
                    self::TAXONOMIES_CACHE_NAME_PROPERTY => $termData->name()->inGerman(),
                    self::TAXONOMIES_CACHE_ORIGINAL_ID_PROPERTY => $termData->remoteTermId(),
                ];
            }
        }

        return $result;
    }

    /**
     * phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
     * @psalm-param array<string, array<int, array{name: string, original_id: int}>> $taxonomiesCache
     */
    private function generateTermData(
        MultilingualString|MultilingualLink|AdmissionRequirement|Degree $flatProperty,
        array &$taxonomiesCache,
        string $taxonomy
    ): ?TermData {

        $termIdsData = $this->termIdsData($flatProperty, $taxonomiesCache[$taxonomy]);

        if (is_null($termIdsData)) {
            return null;
        }

        [$termId, $remoteTermId] = $termIdsData;

        return TermData::fromArray([
            'taxonomy' => $taxonomy,
            'name' => $flatProperty instanceof MultilingualString
                ? $flatProperty
                : $flatProperty->name(),
            'slug' => $this->retrieveTermSlug($flatProperty),
            'term_id' => $termId,
            'remote_term_id' => $remoteTermId,
            'parent_term_id' => $this->retrieveParentTermId(
                $flatProperty,
                $taxonomiesCache[$taxonomy]
            ),
        ]);
    }

    /**
     * @param AdmissionRequirement|Degree|MultilingualLink|MultilingualString $flatProperty
     * @param array $taxonomyCache
     * @psalm-param array<int, array{name?: string, original_id: int}> $taxonomyCache
     * @return array<int>|null
     */
    private function termIdsData(
        MultilingualString|MultilingualLink|AdmissionRequirement|Degree $flatProperty,
        array &$taxonomyCache
    ): ?array {

        $termId = $this->idGenerator->termIdsList($flatProperty)[0] ?? null;

        if (is_null($termId)) {
            return null;
        }

        $name = $flatProperty instanceof MultilingualString ? $flatProperty : $flatProperty->name();
        $newTermId = self::arraySearchBy(
            $taxonomyCache,
            self::TAXONOMIES_CACHE_ORIGINAL_ID_PROPERTY,
            $termId
        );

        if (is_int($newTermId)) {
            // Term was persisted already
            return [$newTermId, $termId];
        }

        $newTermId = self::arraySearchBy(
            $taxonomyCache,
            self::TAXONOMIES_CACHE_NAME_PROPERTY,
            $name->inGerman()
        );

        if (is_int($newTermId)) {
            // Term with the name already exists
            $taxonomyCache[$newTermId] = [
                self::TAXONOMIES_CACHE_ORIGINAL_ID_PROPERTY => $termId,
            ];

            return [$newTermId, $termId];
        }

        return [0, $termId];
    }

    /**
     * @param AdmissionRequirement|Degree|MultilingualLink|MultilingualString $flatProperty
     * @param array $taxonomyCache
     * @psalm-param array<int, array{name?: string, original_id: int}> $taxonomyCache
     * @return int
     */
    private function retrieveParentTermId(
        MultilingualString|MultilingualLink|AdmissionRequirement|Degree $flatProperty,
        array &$taxonomyCache
    ): int {

        if (!$this->isHierarchicalProperty($flatProperty)) {
            return 0;
        }

        /** @var AdmissionRequirement|Degree $flatProperty */
        $parentProperty = $flatProperty->parent();

        if (is_null($parentProperty)) {
            return 0;
        }

        $parentTermData = $this->termIdsData($parentProperty, $taxonomyCache);

        if (is_null($parentTermData)) {
            return 0;
        }

        [$parentTermId] = $parentTermData;

        return $parentTermId;
    }

    private function retrieveTermSlug(
        MultilingualString|MultilingualLink|AdmissionRequirement|Degree $flatProperty
    ): string {

        if (!$flatProperty instanceof AdmissionRequirement) {
            return '';
        }

        return $flatProperty->slug();
    }

    /**
     * @param array<MultilingualString|MultilingualLink|Degree|AdmissionRequirement> $flatProperties
     * @return array<MultilingualString|MultilingualLink|Degree|AdmissionRequirement>
     */
    private function extendPropertiesWithParents(array $flatProperties): array
    {
        $properties = [];

        foreach ($flatProperties as $flatProperty) {
            if (!$this->isHierarchicalProperty($flatProperty)) {
                $properties[] = $flatProperty;
                continue;
            }

            /** @var AdmissionRequirement|Degree $flatProperty */
            $sequence = [$flatProperty];
            $parent = $flatProperty->parent();

            while ($parent) {
                $sequence[] = $parent;
                $parent = $parent->parent();
            }

            $properties = array_merge($properties, array_reverse($sequence));
        }

        return $properties;
    }

    private function isHierarchicalProperty(
        MultilingualString|MultilingualLink|AdmissionRequirement|Degree $flatProperty
    ): bool {

        return $flatProperty instanceof Degree || $flatProperty instanceof AdmissionRequirement;
    }

    private function createTerm(TermData $termData): ?int
    {
        // phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
        /** @var array{term_id: int, term_taxonomy_id: string|numeric-string} | WP_Error $dbResult */
        $dbResult = wp_insert_term(
            $termData->name()->inGerman(),
            $termData->taxonomy(),
            [
                'parent' => $termData->parentTermId(),
                'slug' =>  $termData->slug(),
            ]
        );

        if ($dbResult instanceof WP_Error) {
            $this->logger->error($dbResult->get_error_message());
            return null;
        }

        $termId = $dbResult['term_id'];

        $result = update_term_meta($termId, self::ORIGINAL_TERM_ID_KEY, $termData->remoteTermId());

        if ($result instanceof WP_Error) {
            $this->logger->error($result->get_error_message());
            return null;
        }

        $result = update_term_meta(
            $termId,
            BilingualRepository::addEnglishSuffix('name'),
            $termData->name()->inEnglish()
        );

        if ($result instanceof WP_Error) {
            $this->logger->warning($result->get_error_message());
        }

        return $termId;
    }

    private function updateTerm(TermData $termData): void
    {
        /** @var array<int, int> $memo */
        static $memo = [];

        if (isset($memo[$termData->termId()])) {
            // It doesn't make sense to update the term several times during the single request
            return;
        }

        // phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
        /** @var array{term_id: int, term_taxonomy_id: string|numeric-string} | WP_Error $dbResult */
        $dbResult = wp_update_term(
            $termData->termId(),
            $termData->taxonomy(),
            [
                'name' => $termData->name()->inGerman(),
                'parent' => $termData->parentTermId(),
                'slug' => $termData->slug(),
            ],
        );

        if ($dbResult instanceof WP_Error) {
            $this->logger->warning($dbResult->get_error_message());
        }

        $result = update_term_meta(
            $termData->termId(),
            BilingualRepository::addEnglishSuffix('name'),
            $termData->name()->inEnglish()
        );

        if ($result instanceof WP_Error) {
            $this->logger->warning($result->get_error_message());
        }

        $memo[$termData->termId()] = $termData->termId();

        if (!$termData->remoteTermId()) {
            return;
        }

        $result = update_term_meta($termData->termId(), self::ORIGINAL_TERM_ID_KEY, $termData->remoteTermId());

        if ($result instanceof WP_Error) {
            $this->logger->warning($result->get_error_message());
        }
    }

    /**
     * @psalm-return array<int, array{name: string, original_id: int}> array where keys are term IDs
     */
    private function populateTaxonomiesCache(string $taxonomy): array
    {
        /** @var array<int, string> $terms */
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'fields' => 'id=>name',
        ]);

        $result = [];
        foreach ($terms as $termId => $termName) {
            $result[$termId] = [
                self::TAXONOMIES_CACHE_NAME_PROPERTY => $termName,
                self::TAXONOMIES_CACHE_ORIGINAL_ID_PROPERTY => (int) get_term_meta(
                    $termId,
                    self::ORIGINAL_TERM_ID_KEY,
                    true
                ),
            ];
        }

        return $result;
    }

    /**
     * @psalm-param array<int, array<string, mixed>> $array
     * @psalm-param string $property
     * @psalm-param mixed $value
     */
    private static function arraySearchBy(array $array, string $property, mixed $value): ?int
    {
        foreach ($array as $key => $item) {
            if (!isset($item[$property])) {
                continue;
            }

            if ($item[$property] === $value) {
                return $key;
            }
        }

        return null;
    }

    private function maybeUpdateCampoKeys(DegreeProgramViewRaw $rawView, string $taxonomy, int $termId): void
    {
        $campoKeys = $rawView->campoKeys()->asArray();
        $campoKeyType = CampoKeysRepository::TAXONOMY_TO_CAMPO_KEY_MAP[$taxonomy] ?? '';
        $campoKey = $campoKeys[$campoKeyType] ?? null;

        if (is_null($campoKey)) {
            return;
        }

        update_term_meta($termId, CampoKeysRepository::CAMPO_KEY_TERM_META_KEY, $campoKey);
    }
}
