<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Embed;

use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use WP_Post;

final class EmbedHtmlFilter
{
    /**
     * @wp-hook embed_html
     */
    public function adjustIframeSize(
        string $output,
        WP_Post $post,
        int $width,
        int $height
    ): string {

        if ($post->post_type !== DegreeProgramPostType::KEY) {
            return $output;
        }

        return str_replace(
            [
                sprintf('width="%d" height="%d"', $width, $height),
                'if ( height > 1000 )',
            ],
            [
                // replace iframe tag width attribute
                sprintf('width="%s" height="%d"', '100%', $height),
                // prevent iframe height restriction
                'if ( false )',
            ],
            $output
        );
    }
}
