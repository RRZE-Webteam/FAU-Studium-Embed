<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Infrastructure\ApiClient\ApiClient;
use Throwable;
use WP_Http;

/**
 * @psalm-import-type DegreeProgramViewRawArrayType from DegreeProgramViewRaw
 */
class WordpressApiDegreeProgramViewRepository implements
    DegreeProgramViewRepository,
    DegreeProgramCollectionRepository
{
    public function __construct(private ApiClient $apiClient)
    {
    }

    public function findRaw(DegreeProgramId $degreeProgramId): ?DegreeProgramViewRaw
    {
        try {
            $response = $this->apiClient->get(
                sprintf(
                    '/%s/%d',
                    DegreeProgramPostType::REST_BASE,
                    $degreeProgramId->asInt()
                )
            );
            /** @var array{degree_program: DegreeProgramViewRawArrayType} $data */
            $data = $response->data();

            if ($response->statusCode() === WP_Http::OK) {
                return DegreeProgramViewRaw::fromArray(
                    $data['degree_program']
                );
            }
        } catch (Throwable) {
        }

        return null;
    }

    public function findTranslated(DegreeProgramId $degreeProgramId, string $languageCode): ?DegreeProgramViewTranslated
    {
        // TODO: Implement
        return null;
    }

    public function findRawCollection(CollectionCriteria $criteria): ?PaginationAwareCollection
    {
        /** @var array<DegreeProgramViewRaw> $items */
        $items = [];
        $currentPage = $criteria->page();

        try {
            $response = $this->apiClient->get(
                sprintf('/%s', DegreeProgramPostType::REST_BASE),
                $criteria->args(),
            );

            if ($response->statusCode() === WP_Http::OK) {
                $totalPages = (int) $response->header('x-wp-totalpages');
                $totalItems = (int) $response->header('x-wp-total');

                /**
                 * @psalm-var array{
                 *      degree_program: DegreeProgramViewRawArrayType
                 * } $degreeProgramData
                */
                foreach ($response->data() as $degreeProgramData) {
                    $items[] = DegreeProgramViewRaw::fromArray(
                        $degreeProgramData['degree_program']
                    );
                }

                return new WordpressApiPaginationAwareCollection(
                    $totalPages,
                    $totalItems,
                    $currentPage,
                    ...$items
                );
            }
        } catch (Throwable) {
        }

        return null;
    }

    public function findTranslatedCollection(CollectionCriteria $criteria, string $languageCode): ?PaginationAwareCollection
    {
        // TODO: Implement
        return null;
    }
}
