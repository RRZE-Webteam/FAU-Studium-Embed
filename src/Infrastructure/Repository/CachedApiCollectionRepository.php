<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;

/**
 * The WordPress database is used as a cache layer for REST API.
 * In fact, `WordPressDatabaseDegreeProgramCollectionRepository` always returns a collection,
 * but this behavior could be changed in the future.
 * Therefore, the service should be used everywhere when cached collection retrieval is assumed.
 */
final class CachedApiCollectionRepository implements DegreeProgramCollectionRepository
{
    public function __construct(
        private DegreeProgramCollectionRepository $apiRepository,
        private DegreeProgramCollectionRepository $databaseRepository,
    ) {
    }

    public function findRawCollection(CollectionCriteria $criteria): ?PaginationAwareCollection
    {
        return $this->databaseRepository->findRawCollection($criteria)
            ?? $this->apiRepository->findRawCollection($criteria);
    }

    public function findTranslatedCollection(CollectionCriteria $criteria, string $languageCode): ?PaginationAwareCollection
    {
        return $this->databaseRepository->findTranslatedCollection($criteria, $languageCode)
            ?? $this->apiRepository->findTranslatedCollection($criteria, $languageCode);
    }
}
