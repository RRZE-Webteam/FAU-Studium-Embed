<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Search;

use ArrayObject;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Domain\MultilingualLink;
use Fau\DegreeProgram\Common\Domain\MultilingualLinks;
use Fau\DegreeProgram\Common\Domain\MultilingualList;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\AreaOfStudyTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\AttributeTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\DegreeTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\FacultyTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\KeywordTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\SemesterTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\StudyLocationTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\SubjectGroupTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TeachingLanguageTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Repository\BilingualRepository;
use Fau\DegreeProgram\Common\Infrastructure\Repository\IdGenerator;
use LogicException;
use Psr\Log\LoggerInterface;
use WP_Error;
use WP_Post;

final class FilterableTermsUpdater
{
    public const ORIGINAL_TERM_ID_KEY = 'fau_original_term_id';

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
                $this->setObjectTerms($postId, $taxonomy, $property);
            }
        }
    }

    /**
     * phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
     * @return array<string, MultilingualString|MultilingualList|MultilingualLink|MultilingualLinks|Degree>
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
            KeywordTaxonomy::KEY =>
                $rawView->keywords(),
            AreaOfStudyTaxonomy::KEY =>
                $rawView->areaOfStudy(),
        ];
    }

    /**
     * @psalm-param array<int, MultilingualString> $terms
     */
    private function setObjectTerms(
        int $postId,
        string $taxonomy,
        MultilingualString|MultilingualList|MultilingualLink|MultilingualLinks|Degree $property
    ): void {

        $termIds = $this->maybeCreateTerms($taxonomy, $property);
        wp_set_object_terms(
            $postId,
            $termIds,
            $taxonomy
        );
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
        MultilingualString|MultilingualList|MultilingualLink|MultilingualLinks|Degree $property
    ): array {

        /**
         * @psalm-var array<string, array<int, int>> $taxonomiesCache
         *          array where keys are taxonomy slugs,
         *          nested array where keys are term IDs, values are remote term IDs
         */
        static $taxonomiesCache = [];
        if (!array_key_exists($taxonomy, $taxonomiesCache)) {
            $taxonomiesCache[$taxonomy] = $this->populateTaxonomiesCache($taxonomy);
        }

        /** @var array<int> $parents */
        static $parents = [];

        $result = [];

        /** @var array<MultilingualString|MultilingualLink|Degree> $flatProperties */
        $flatProperties = $property instanceof ArrayObject ? $property->getArrayCopy() : [$property];

        foreach ($flatProperties as $flatProperty) {
            if (empty($flatProperty->id())) {
                continue;
            }

            // Degree taxonomy is the only hierarchy taxonomy now.
            // The code could be generalized in the future.
            if ($flatProperty instanceof Degree && $flatProperty->parent()) {
                $parents[] = $this->idGenerator->termIdsList($flatProperty->parent())[0] ?? 0;
                $this->maybeCreateTerms($taxonomy, $flatProperty->parent());
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

            $parentId = 0;
            if (count($parents) > 0 && $termId !== $parents[count($parents) - 1]) {
                $parentId = array_pop($parents);
            }

            $validatedTermId = self::findKeyOrItemFromArray($termId, $taxonomiesCache[$taxonomy]);
            if ($validatedTermId && count($parents) === 0) {
                $result[] = $validatedTermId;
            }

            if ($validatedTermId) {
                // Term was persisted already
                continue;
            }

            $validatedParentId = self::findKeyOrItemFromArray($parentId, $taxonomiesCache[$taxonomy]);
            if ($parentId > 0 && $validatedParentId === null) {
                throw new LogicException('Parent term was not created?');
            }

            $name = $flatProperty instanceof MultilingualString ? $flatProperty : $flatProperty->name();
            $newTermId = $this->createTerm($taxonomy, $termId, $name, (int) $validatedParentId);
            if ($newTermId && count($parents) === 0) {
                $result[] = $newTermId;
            }

            if ($newTermId) {
                $taxonomiesCache[$taxonomy][$newTermId] = $termId;
            }
        }

        return $result;
    }

    /**
     * @param array<int, int> $array
     */
    private static function findKeyOrItemFromArray(int $keyOrItem, array $array): ?int
    {
        if (array_key_exists($keyOrItem, $array)) {
            return $keyOrItem;
        }

        $key = array_search($keyOrItem, $array, true);
        if (is_int($key)) {
            return $key;
        }

        return null;
    }

    private function createTerm(
        string $taxonomy,
        int $remoteTermId,
        MultilingualString $name,
        int $parentId
    ): ?int {
        // phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
        /** @var array{term_id: int, term_taxonomy_id: string|numeric-string} | WP_Error $dbResult */
        $dbResult = wp_insert_term(
            $name->inGerman(),
            $taxonomy,
            ['parent_id' => $parentId]
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

    /**
     * @psalm-return array<int, int> array where keys are term IDs, values are remote term IDs
     */
    private function populateTaxonomiesCache(string $taxonomy): array
    {
        /** @var array<int> $terms */
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'fields' => 'ids',
        ]);

        $result = [];
        foreach ($terms as $termId) {
            $result[$termId] = (int) get_term_meta($termId, self::ORIGINAL_TERM_ID_KEY, true);
        }

        return $result;
    }
}
