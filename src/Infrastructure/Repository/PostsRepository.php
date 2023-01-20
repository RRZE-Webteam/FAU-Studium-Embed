<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Common\Infrastructure\Repository\BilingualRepository;
use WP_Post;

class PostsRepository
{
    public function findByGermanSlug(string $slug): ?WP_Post
    {
        /**
         * @var WP_Post[] $posts
         */
        $posts = get_posts([
            'post_type' => DegreeProgramPostType::KEY,
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'name' => $slug,
            'suppress_filters' => false,
        ]);

        return $posts[0] ?? null;
    }

    public function findByEnglishSlug(string $slug): ?WP_Post
    {
        /**
         * @var WP_Post[] $posts
         */
        $posts = get_posts([
            'post_type' => DegreeProgramPostType::KEY,
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'suppress_filters' => false,
            'meta_query' => [
                [
                    'key' => BilingualRepository::addEnglishSuffix('post_name'),
                    'value' => $slug,
                ],
            ],
        ]);

        return $posts[0] ?? null;
    }
}
