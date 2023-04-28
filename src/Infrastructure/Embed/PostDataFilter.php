<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Embed;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use WP_Post;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 */
final class PostDataFilter
{
    public function __construct(
        private DegreeProgramViewRepository $degreeProgramViewRepository,
        private CurrentRequest $currentRequest,
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

        $view = $this->currentView(
            $postId,
            $this->currentRequest->languageCode()
        );

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

        $view = $this->currentView(
            $post->ID,
            $this->currentRequest->languageCode()
        );

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

    /**
     * @psalm-param LanguageCodes $languageCode
     */
    private function currentView(
        int $postId,
        string $languageCode
    ): ?DegreeProgramViewTranslated {
        /** @var array<string, null|DegreeProgramViewTranslated> $cache */
        static $cache = [];
        $cacheKey = sprintf('%d_%s', $postId, $languageCode);
        if (array_key_exists($cacheKey, $cache)) {
            return $cache[$cacheKey];
        }

        $cache[$cacheKey] = $this->degreeProgramViewRepository->findTranslated(
            DegreeProgramId::fromInt($postId),
            $languageCode,
        );

        return $cache[$cacheKey];
    }
}
