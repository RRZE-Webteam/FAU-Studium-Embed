<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Query;

use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use WP_Query;

final class WpQueryModifier
{
    /**
     * Mapped `orderby` keywords to meta keys
     */
    private const SUPPORTED_ORDERBY = [
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

        $orderBy = $query->get('orderby');
        if (!is_string($orderBy)) {
            return;
        }

        if ($this->isStickyOrdering($orderBy)) {
            self::updateMetaQuery($query, $orderBy, 'UNSIGNED');
            $query->set('orderby', [
                'orderby_' . $orderBy => 'DESC',
                CollectionCriteria::DEFAULT_ORDERBY[0] => CollectionCriteria::DEFAULT_ORDERBY[1],
            ]);

            return;
        }

        if (!in_array($orderBy, self::SUPPORTED_ORDERBY, true)) {
            return;
        }

        self::updateMetaQuery($query, $orderBy);

        $query->set('orderby', [
            'orderby_' . $orderBy => $query->get('order') ?: 'ASC',
        ]);
    }

    private function isDegreeProgramQuery(WP_Query $query): bool
    {
        return in_array(DegreeProgramPostType::KEY, (array) $query->get('post_type'), true);
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
                    'orderby_' . $orderBy => [
                        'key' => $orderBy,
                        'type' => $type,
                    ],
                    [
                        'key' => $orderBy,
                        'compare' => 'NOT EXISTS',
                    ],
                ],
                // Preserve existing `meta_query` and append to ours via `AND` relation
                array_filter((array) $query->get('meta_query')),
            ],
        );
    }
}
