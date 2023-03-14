<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Query;

use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use WP_Query;

final class WpQueryModifier
{
    /**
     * Mapped `orderby` keywords to meta keys
     */
    private const SUPPORTED_ORDERBY = [
        'degree_en' => 'degree_en',
        'degree_de' => 'degree_de',
    ];

    /**
     * @wp-hook pre_get_posts
     */
    public function sortyBySupportedMetaKeys(WP_Query $query): void
    {
        if (!$this->isDegreeProgramQuery($query)) {
            return;
        }

        $orderBy = $query->get('orderby');
        $metaQuery = array_filter((array) $query->get('meta_query'));

        if (!in_array($orderBy, self::SUPPORTED_ORDERBY, true)) {
            return;
        }

        $query->set(
            'meta_query',
            [
                'relation' => 'AND',
                [
                    'relation' => 'OR',
                    'orderby_' . $orderBy => [
                        'key' => $orderBy,
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
}
