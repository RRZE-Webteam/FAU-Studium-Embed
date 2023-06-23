<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Application\Cache\CacheKeyGenerator;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Infrastructure\Cache\PostMetaDegreeProgramCache;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use WP_Query;

/**
 * REST API doesn't support filtering. That's why we return nothing if cache has not been warmed.
 * For REST API interaction WordPressApiDegreeProgramViewRepository should be used directly.
 */
final class CachedApiCollectionRepository implements DegreeProgramCollectionRepository
{
    public function __construct(
        private DegreeProgramCollectionRepository $databaseRepository,
    ) {
    }

    public function findRawCollection(CollectionCriteria $criteria): ?PaginationAwareCollection
    {
        if (!$this->wasCacheWarmed()) {
            return null;
        }

        return $this->databaseRepository->findRawCollection($criteria);
    }

    public function findTranslatedCollection(
        CollectionCriteria $criteria,
        string $languageCode
    ): ?PaginationAwareCollection {

        if (!$this->wasCacheWarmed()) {
            return null;
        }

        return $this->databaseRepository->findTranslatedCollection($criteria, $languageCode);
    }

    private function wasCacheWarmed(): bool
    {
        static $result;
        if (is_bool($result)) {
            return $result;
        }

        $query = new WP_Query([
            'numberposts' => 1,
            'post_type' => DegreeProgramPostType::KEY,
            'post_status' => ['publish'],
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => PostMetaDegreeProgramCache::postMetaKey(
                        CacheKeyGenerator::RAW_TYPE
                    ),
                    'compare' => 'EXISTS',
                ],
                [
                    'key' => PostMetaDegreeProgramCache::postMetaKey(
                        CacheKeyGenerator::TRANSLATED_TYPE
                    ),
                    'compare' => 'EXISTS',
                ],
            ],
            'fields' => 'ids',
        ]);

        $result = $query->found_posts > 0;

        return $result;
    }
}
