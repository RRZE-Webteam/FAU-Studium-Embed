<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Output\Infrastructure\Repository\CurrentViewRepository;
use WP_Post;

final class TitleModifier
{
    public function __construct(private CurrentViewRepository $currentViewRepository)
    {
    }

    /**
     * @wp-hook single_post_title
     * @wp-hook the_title
     */
    public function modify(string $title, int|WP_Post $post): string
    {
        $view = $this->currentViewRepository->findTranslatedView($post);

        if (!$view instanceof DegreeProgramViewTranslated) {
            return $title;
        }

        return $view->title();
    }
}
