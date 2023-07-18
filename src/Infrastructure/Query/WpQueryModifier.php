<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Query;

use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use WP_Query;

final class WpQueryModifier
{
    /**
     * Mapped `orderby` keywords to meta keys
     */
    private const SUPPORTED_ORDER_BY = [
        DegreeProgram::TITLE . '_' . MultilingualString::EN,
        DegreeProgram::DEGREE . '_' . MultilingualString::EN,
        DegreeProgram::DEGREE . '_' . MultilingualString::DE,
        DegreeProgram::START . '_' . MultilingualString::EN,
        DegreeProgram::START . '_' . MultilingualString::DE,
        DegreeProgram::LOCATION . '_' . MultilingualString::EN,
        DegreeProgram::LOCATION . '_' . MultilingualString::DE,
        DegreeProgram::ADMISSION_REQUIREMENTS . '_' . MultilingualString::EN,
        DegreeProgram::ADMISSION_REQUIREMENTS . '_' . MultilingualString::DE,
    ];

    /**
     * @wp-hook pre_get_posts
     */
    public function sortBySupportedMetaKeys(WP_Query $query): void
    {
        if (!$this->isDegreeProgramQuery($query)) {
            return;
        }

        /** @var array<string, string>|string $orderBy */
        $orderBy = $query->get('orderby');
        if (!is_array($orderBy)) {
            return;
        }

        $updatedOrderBy = [];
        foreach ($orderBy as $property => $order) {
            $isSticky = $this->isStickyOrdering($property);
            $isSupported = $isSticky
                || in_array($property, self::SUPPORTED_ORDER_BY, true);

            if (!$isSupported) {
                $updatedOrderBy[$property] = $order;
                continue;
            }

            $key = $isSticky
                ? self::buildOrderByNotExistsKey($property)
                : self::buildOrderByKey($property);
            $updatedOrderBy[$key] = $order;
            $metaType = $isSticky ? 'UNSIGNED' : 'CHAR';
            self::updateMetaQuery($query, $property, $metaType);
        }

        $query->set('orderby', $updatedOrderBy);
    }

    private function isDegreeProgramQuery(WP_Query $query): bool
    {
        return in_array(
            DegreeProgramPostType::KEY,
            (array) $query->get('post_type'),
            true
        );
    }

    private function isStickyOrdering(string $orderBy): bool
    {
        return str_starts_with($orderBy, '_sticky');
    }

    private static function updateMetaQuery(
        WP_Query $query,
        string $orderBy,
        string $type = 'CHAR'
    ): void {

        $query->set(
            'meta_query',
            [
                'relation' => 'AND',
                [
                    'relation' => 'OR',
                    self::buildOrderByKey($orderBy) => [
                        'key' => $orderBy,
                        'type' => $type,
                    ],
                    self::buildOrderByNotExistsKey($orderBy) => [
                        'key' => $orderBy,
                        'compare' => 'NOT EXISTS',
                    ],
                ],
                // Preserve existing `meta_query` and append to ours via `AND` relation
                array_filter((array) $query->get('meta_query')),
            ],
        );
    }

    private static function buildOrderByKey(string $property): string
    {
        return 'order_by_' . $property;
    }

    private static function buildOrderByNotExistsKey(string $property): string
    {
        return 'order_by_not_exists_' . $property;
    }
}
