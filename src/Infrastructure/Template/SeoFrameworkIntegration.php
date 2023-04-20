<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use WP_Post;

final class SeoFrameworkIntegration
{
    public function __construct(
        private DegreeProgramViewRepository $degreeProgramViewRepository,
        private CurrentRequest $currentRequest,
    ) {
    }

    /**
     * Degree program post type doesn't support title.
     * We provide generated title explicitly.
     *
     * @wp-hook the_seo_framework_title_from_generation
     */
    public function filterGeneratedTitle(string $title): string
    {
        $view = $this->currentView();

        if (!$view instanceof DegreeProgramViewTranslated) {
            return $title;
        }

        return $view->title();
    }

    /**
     * @wp-hook the_seo_framework_generated_description
     */
    public function filterGeneratedDescription(string $description): string
    {
        $view = $this->currentView();

        if (!$view instanceof DegreeProgramViewTranslated) {
            return $description;
        }

        return $view->metaDescription();
    }

    private function currentView(): ?DegreeProgramViewTranslated
    {
        $post = get_post();
        if (!$post instanceof WP_Post) {
            return null;
        }

        if ($post->post_type !== DegreeProgramPostType::KEY) {
            return null;
        }

        return $this->degreeProgramViewRepository->findTranslated(
            DegreeProgramId::fromInt($post->ID),
            $this->currentRequest->languageCode(),
        );
    }
}
