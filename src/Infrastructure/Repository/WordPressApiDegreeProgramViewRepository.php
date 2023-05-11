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

/**
 * @psalm-import-type DegreeProgramViewRawArrayType from DegreeProgramViewRaw
 * @psalm-import-type DegreeProgramViewTranslatedArrayType from DegreeProgramViewTranslated
 */
class WordPressApiDegreeProgramViewRepository implements
    DegreeProgramViewRepository,
    DegreeProgramCollectionRepository
{
    private const MAX_PER_PAGE = 100;

    public function __construct(private ApiClient $apiClient)
    {
    }

    public function findRaw(DegreeProgramId $degreeProgramId): ?DegreeProgramViewRaw
    {
        $response = $this->apiClient->get(
            sprintf(
                '/wp/v2/%s/%d',
                DegreeProgramPostType::REST_BASE,
                $degreeProgramId->asInt()
            )
        );

        if (!$response) {
            return null;
        }

        /** @var DegreeProgramViewRawArrayType $data */
        $data = $response->get('degree_program');

        return DegreeProgramViewRaw::fromArray($data);
    }

    public function findTranslated(DegreeProgramId $degreeProgramId, string $languageCode): ?DegreeProgramViewTranslated
    {
        $response = $this->apiClient->get(
            sprintf(
                '/fau/v1/degree-program/%d',
                $degreeProgramId->asInt()
            ),
            [
                'lang' => $languageCode,
            ]
        );

        if (!$response) {
            return null;
        }

        /** @var DegreeProgramViewTranslatedArrayType $data */
        $data = $response->data();

        return DegreeProgramViewTranslated::fromArray($data);
    }

    public function findRawCollection(CollectionCriteria $criteria): ?PaginationAwareCollection
    {
        /** @var null|bool $findAll */
        static $findAll = null;
        /** @var list<list<DegreeProgramViewRaw>> $paginatedItems */
        static $paginatedItems = [];

        if ($findAll === null) {
            $findAll = $criteria->perPage() === -1;
        }

        if ($findAll) {
            $criteria = $criteria->withPerPage(self::MAX_PER_PAGE);
        }

        /** @var array<DegreeProgramViewRaw> $items */
        $items = [];
        $currentPage = $criteria->page();

        $args = $criteria->args();
        $args['_fields'] = 'degree_program';

        $response = $this->apiClient->get(
            sprintf('/wp/v2/%s', DegreeProgramPostType::REST_BASE),
            $args,
        );

        if (!$response) {
            return null;
        }

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

        $paginatedItems[] = $items;

        $collection =  new WordPressApiPaginationAwareCollection(
            $totalPages,
            $totalItems,
            $currentPage,
            ...$items
        );

        if (!$findAll) {
            return $collection;
        }

        if (!$collection->nextPage()) {
            /** @var array<DegreeProgramViewRaw> $items */
            $items = array_merge([], ...$paginatedItems);
            return new WordPressApiPaginationAwareCollection(
                1,
                count($items),
                1,
                ...$items
            );
        }

        return $this->findRawCollection($criteria->toNextPage());
    }

    public function findTranslatedCollection(CollectionCriteria $criteria, string $languageCode): ?PaginationAwareCollection
    {
        /** @var null|bool $findAll */
        static $findAll = null;
        /** @var list<list<DegreeProgramViewTranslated>> $paginatedItems */
        static $paginatedItems = [];

        if ($findAll === null) {
            $findAll = $criteria->perPage() === -1;
        }

        if ($findAll) {
            $criteria = $criteria->withPerPage(self::MAX_PER_PAGE);
        }

        $items = [];
        $currentPage = $criteria->page();

        $query = $criteria->args();
        $query['lang'] = $languageCode;
        $response = $this->apiClient->get(
            '/fau/v1/degree-program',
            $query,
        );

        if (!$response) {
            return null;
        }

        $totalPages = (int) $response->header('x-wp-totalpages');
        $totalItems = (int) $response->header('x-wp-total');

        foreach ($response->data() as $degreeProgramData) {
            /** @var DegreeProgramViewTranslatedArrayType $degreeProgramData */
            $items[] = DegreeProgramViewTranslated::fromArray($degreeProgramData);
        }

        $paginatedItems[] = $items;

        $collection = new WordPressApiPaginationAwareCollection(
            $totalPages,
            $totalItems,
            $currentPage,
            ...$items
        );

        if (!$findAll) {
            return $collection;
        }

        if (!$collection->nextPage()) {
            /** @var array<DegreeProgramViewTranslated> $items */
            $items = array_merge([], ...$paginatedItems);
            return new WordPressApiPaginationAwareCollection(
                1,
                count($items),
                1,
                ...$items
            );
        }

        return $this->findTranslatedCollection($criteria->toNextPage(), $languageCode);
    }
}
