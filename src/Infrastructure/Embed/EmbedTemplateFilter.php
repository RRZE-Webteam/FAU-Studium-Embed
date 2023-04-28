<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Embed;

use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;

final class EmbedTemplateFilter
{
    /**
     * @wp-hook embed_thumbnail_id
     */
    public function removeThumbnail(int|bool $thumbnailId): int|bool
    {
        if (!$this->isDegreeProgramEmbed()) {
            return $thumbnailId;
        }

        return false;
    }

    /**
     * @wp-hook the_excerpt_embed
     */
    public function removeExcerpt(string $output): string
    {
        if (!$this->isDegreeProgramEmbed()) {
            return $output;
        }

        return '';
    }

    /**
     * @wp-hook embed_site_title_html
     */
    public function removeEmbedSiteTitle(string $output): string
    {
        if (!$this->isDegreeProgramEmbed()) {
            return $output;
        }

        return '';
    }

    /**
     * @wp-hook embed_content
     */
    public function outputContent(): void
    {
        if (!$this->isDegreeProgramEmbed()) {
            return;
        }

        the_content();
    }

    private function isDegreeProgramEmbed(): bool
    {
        return get_post_type() === DegreeProgramPostType::KEY;
    }
}
