<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Infrastructure\Repository\CurrentViewRepository;

final class SeoFrameworkIntegration
{
    public function __construct(
        private CurrentViewRepository $currentViewRepository,
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
        $view = $this->currentViewRepository->findTranslatedView();

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
        $view = $this->currentViewRepository->findTranslatedView();

        if (!$view instanceof DegreeProgramViewTranslated) {
            return $description;
        }

        return $view->metaDescription();
    }

    /**
     * @wp-hook the_seo_framework_supported_post_type
     */
    public function alwaysSupportDegreeProgramPostType(
        bool $isSupported,
        string $postType
    ): bool {

        return $postType === DegreeProgramPostType::KEY ?: $isSupported;
    }
}
