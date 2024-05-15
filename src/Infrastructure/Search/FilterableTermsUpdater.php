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

        foreach ($flatProperties as $flatProperty) {
            if (empty($flatProperty->id())) {
                continue;
            }

            // For "Degree" and "Admission requirements …" taxonomies, only first-level terms are used as filter options
            if ($flatProperty instanceof Degree || $flatProperty instanceof AdmissionRequirement) {
                $flatProperty = $this->retrieveFirstLevelTerm($flatProperty);
            }

            $termId = $this->idGenerator->termIdsList($flatProperty)[0] ?? null;
            if (!$termId) {
                $this->logger->error(
                    'Could not retrieve ID from structure.',
                    [
                        'structure' => $flatProperty,
                    ]
                );
                continue;
            }
            $name = $flatProperty instanceof MultilingualString ? $flatProperty : $flatProperty->name();

            $newTermId = self::arraySearchBy(
                $taxonomiesCache[$taxonomy],
                self::TAXONOMIES_CACHE_ORIGINAL_ID_PROPERTY,
                $termId
            );

            if ($newTermId) {
                // Term was persisted already
                $result[] = $newTermId;
                $this->updateTerm($taxonomy, $newTermId, $name);
                continue;
            }

            $newTermId = self::arraySearchBy(
                $taxonomiesCache[$taxonomy],
                self::TAXONOMIES_CACHE_NAME_PROPERTY,
                $name->inGerman()
            );

            if ($newTermId) {
                // Term with the name already exists
                $result[] = $newTermId;
                $taxonomiesCache[$taxonomy][$newTermId] = [
                    self::TAXONOMIES_CACHE_ORIGINAL_ID_PROPERTY => $termId,
                ];
                $this->updateTerm($taxonomy, $newTermId, $name, $termId);
                continue;
            }

            $newTermId = $this->createTerm($taxonomy, $termId, $name);
            if ($newTermId) {
                $result[] = $newTermId;
                $taxonomiesCache[$taxonomy][$newTermId] = [
                    self::TAXONOMIES_CACHE_NAME_PROPERTY => $name->inGerman(),
                    self::TAXONOMIES_CACHE_ORIGINAL_ID_PROPERTY => $termId,
                ];
            }
        }

        return $result;
    }

    private function retrieveFirstLevelTerm(Degree|AdmissionRequirement $structure): Degree|AdmissionRequirement
    {
        $parent = $structure->parent();
        while ($parent) {
            $structure = $parent;
            $parent = $structure->parent();
        }

        return $structure;
    }

    private function createTerm(
        string $taxonomy,
        int $remoteTermId,
        MultilingualString $name,
    ): ?int {
        // phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
        /** @var array{term_id: int, term_taxonomy_id: string|numeric-string} | WP_Error $dbResult */
        $dbResult = wp_insert_term(
            $name->inGerman(),
            $taxonomy,
        );

        if ($dbResult instanceof WP_Error) {
            $this->logger->error($dbResult->get_error_message());
            return null;
        }

        $termId = $dbResult['term_id'];

        $result = update_term_meta($termId, self::ORIGINAL_TERM_ID_KEY, $remoteTermId);
        if ($result instanceof WP_Error) {
            $this->logger->error($result->get_error_message());
            return null;
        }

        $result = update_term_meta(
            $termId,
            BilingualRepository::addEnglishSuffix('name'),
            $name->inEnglish()
        );
        if ($result instanceof WP_Error) {
            $this->logger->warning($result->get_error_message());
        }

        return $termId;
    }

    private function updateTerm(
        string $taxonomy,
        int $termId,
        MultilingualString $name,
        ?int $remoteTermId = null,
    ): void {
        /** @var array<int, int> $memo */
        static $memo = [];
        if (isset($memo[$termId])) {
            // It doesn't make sense to update the term several times during the single request
            return;
        }

        // phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
        /** @var array{term_id: int, term_taxonomy_id: string|numeric-string} | WP_Error $dbResult */
        $dbResult = wp_update_term(
            $termId,
            $taxonomy,
            [
                'name' => $name->inGerman(),
            ],
        );

        if ($dbResult instanceof WP_Error) {
            $this->logger->warning($dbResult->get_error_message());
        }

        $result = update_term_meta(
            $termId,
            BilingualRepository::addEnglishSuffix('name'),
            $name->inEnglish()
        );

        if ($result instanceof WP_Error) {
            $this->logger->warning($result->get_error_message());
        }

        $memo[$termId] = $termId;

        if (!$remoteTermId) {
            return;
        }

        $result = update_term_meta($termId, self::ORIGINAL_TERM_ID_KEY, $remoteTermId);
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
        $campoKeyType = CampoKeysRepository::CAMPO_KEYS_TOTAXONOMY_MAP[$taxonomy] ?? '';
        $campoKey = $campoKeys[$campoKeyType] ?? null;

        if (is_null($campoKey)) {
            return;
        }

        update_term_meta($termId, CampoKeysRepository::CAMPOKEY_TERM_META_KEY, $campoKey);
    }
}
