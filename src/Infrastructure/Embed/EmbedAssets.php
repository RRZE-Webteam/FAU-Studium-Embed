<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Embed;

use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Inpsyde\Modularity\Properties\PluginProperties;

final class EmbedAssets
{
    private const STYLE = 'style';
    private const SCRIPT = 'script';

    public function __construct(
        private PluginProperties $pluginProperties,
    ) {
    }

    /**
     * @wp-hook embed_head
     */
    public function printStyles(): void
    {
        if (!$this->isDegreeProgramEmbed()) {
            return;
        }

        remove_action('embed_head', 'print_embed_styles');

        $styles = [
            get_stylesheet_directory() . '/style.css',
            $this->pluginProperties->basePath() . 'assets/css/embed.css',
        ];

        foreach ($styles as $style) {
            $this->print($style, self::STYLE);
        }
    }

    /**
     * @wp-hook embed_footer
     */
    public function printScripts(): void
    {
        if (!$this->isDegreeProgramEmbed()) {
            return;
        }

        $scripts = [
            $this->pluginProperties->basePath() . 'assets/ts/embed.js',
        ];

        foreach ($scripts as $script) {
            $this->print($script, self::SCRIPT);
        }
    }

    /**
     * @psalm-param 'style' | 'script' $tag
     */
    private function print(string $path, string $tag): void
    {
        if (!file_exists($path)) {
            return;
        }

        printf(
            '<%1$s>%2$s</%1$s>',
            esc_attr($tag),
            (string) file_get_contents($path) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        );
    }

    private function isDegreeProgramEmbed(): bool
    {
        return get_post_type() === DegreeProgramPostType::KEY;
    }
}
