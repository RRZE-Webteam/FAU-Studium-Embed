<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Infrastructure\Component\SingleDegreeProgram;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use WP_Post;

final class SingleDegreeProgramContentFilter
{
    public function __construct(
        private SingleDegreeProgram $singleDegreeProgram,
        private CurrentRequest $currentRequest,
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

        return $this->singleDegreeProgram->render([
            'id' => $post->ID,
            'lang' => $this->currentRequest->languageCode(),
        ]);
    }
}
