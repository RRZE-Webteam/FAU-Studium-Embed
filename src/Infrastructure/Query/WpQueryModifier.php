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

        $orderBy = (string) $query->get('orderby');
        $metaQuery = array_filter((array) $query->get('meta_query'));
        $isTermMetaOrdering = $this->isTermMetaOrdering($orderBy);

        if (
            !$isTermMetaOrdering
            && !in_array($orderBy, self::SUPPORTED_ORDERBY, true)
        ) {
            return;
        }

        $type = $isTermMetaOrdering ? 'UNSIGNED' : 'CHAR';

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
                $metaQuery,
            ],
        );

        $query->set('orderby', [
            'orderby_' . $orderBy => $query->get('order') ?: 'ASC',
        ]);
    }

    private function isDegreeProgramQuery(WP_Query $query): bool
    {
        return in_array(DegreeProgramPostType::KEY, (array) $query->get('post_type'), true);
    }

    private function isTermMetaOrdering(string $orderBy): bool
    {
        return str_starts_with($orderBy, '_order_');
    }
}
