<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Embed;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Infrastructure\Repository\CurrentViewRepository;
use WP_Post;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 */
final class PostDataFilter
{
    public function __construct(
        private CurrentViewRepository $currentViewRepository,
    ) {
    }

    /**
     * @wp-hook the_title
     */
    public function filterPostTitle(string $postTitle, int $postId): string
    {
        if (!$this->isDegreeProgramEmbed($postId)) {
            return $postTitle;
        }

        $view = $this->currentViewRepository->findTranslatedView($postId);

        if (!$view instanceof DegreeProgramViewTranslated) {
            return $postTitle;
        }

        return $view->title();
    }

    /**
     * @wp-hook post_type_link
     */
    public function filterPostLink(string $postLink, WP_Post $post): string
    {
        if (!$this->isDegreeProgramEmbed($post)) {
            return $postLink;
        }

        $view = $this->currentViewRepository->findTranslatedView($post);

        if (!$view instanceof DegreeProgramViewTranslated) {
            return $postLink;
        }

        return $view->link();
    }

    private function isDegreeProgramEmbed(int|WP_Post $post): bool
    {
        return did_filter('oembed_request_post_id')
            && get_post_type($post) === DegreeProgramPostType::KEY;
    }
}
