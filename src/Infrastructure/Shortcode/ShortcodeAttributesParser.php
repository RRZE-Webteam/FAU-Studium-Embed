<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Shortcode;

final class ShortcodeAttributesParser
{
    /**
     * @var array<string, mixed>
     */
    private array $attributes = [];

    /**
     * Method allows extracting shortcode attributes from content.
     * To avoid performing complex regular expressions directly the following workaround is used.
     * Original callback for shortcode tag is temporarily replaced with spy function.
     * The function store parsed attributes in the class property.
     * After performing `do_shortcode` on the content we receive attributes
     * and restore the original callback.
     *
     * phpcs:disable Inpsyde.CodeQuality.VariablesName.SnakeCaseVar
     * @return array<string, mixed>
     */
    public function parseShortcodeAttributes(
        string $content,
        string $shortcodeTag
    ): array {

        global $shortcode_tags;

        $originalCallback = $shortcode_tags[$shortcodeTag] ?? null;
        if (!is_callable($originalCallback)) {
            return [];
        }

        add_shortcode($shortcodeTag, [$this, 'attributesCatcher']);
        do_shortcode($content);
        add_shortcode($shortcodeTag, $originalCallback);

        return $this->attributes;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function attributesCatcher(array $attributes): string
    {
        $this->attributes = $attributes;

        return '';
    }
}
