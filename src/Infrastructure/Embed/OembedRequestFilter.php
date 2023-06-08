<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Embed;

use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Infrastructure\Repository\PostsRepository;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\InjectLanguageQueryVariable;
use WP_Post;

final class OembedRequestFilter
{
    public function __construct(private PostsRepository $postsRepository)
    {
    }

    /**
     * @wp-hook oembed_request_post_id
     */
    public function filterOembedRequest(int $postId, string $url): int
    {
        if ($postId) {
            return $postId;
        }

        $url = trim(str_replace(home_url(), '', $url), '/');
        $regExp = sprintf(
            '#^%s/(?P<slug>[^/]+)/?$#',
            preg_quote(DegreeProgramPostType::KEY, '#')
        );

        if (!preg_match($regExp, $url, $matches)) {
            return $postId;
        }

        $degreeProgramPost = $this->postsRepository->findByEnglishSlug($matches['slug']);
        if (! $degreeProgramPost instanceof WP_Post) {
            return $postId;
        }

        return $degreeProgramPost->ID;
    }
}
