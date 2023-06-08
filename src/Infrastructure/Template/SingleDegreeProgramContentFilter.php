<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Infrastructure\Component\SingleDegreeProgram;
use WP_Post;

final class SingleDegreeProgramContentFilter
{
    public function __construct(
        private SingleDegreeProgram $singleDegreeProgram,
    ) {
    }

    /**
     * @wp-hook the_content
     */
    public function filterContent(string $content): string
    {
        $post = get_post();
        if (!$post instanceof WP_Post) {
            return $content;
        }

        if ($post->post_type !== DegreeProgramPostType::KEY) {
            return $content;
        }

        // Removing filter to avoid endless loop
        // if translated view generation uses `the_content` filter under hood.
        remove_filter('the_content', [$this, 'filterContent']);
        $html = $this->singleDegreeProgram->render([
            'id' => $post->ID,
        ]);
        add_filter('the_content', [$this, 'filterContent']);

        return $html;
    }
}
