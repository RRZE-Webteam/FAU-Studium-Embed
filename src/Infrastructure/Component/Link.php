<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @psalm-type LinkAttributes = array{
 *     url: string,
 *     text: string,
 *     type: 'dark' | 'light',
 *     icon: string,
 * }
 */
final class Link implements RenderableComponent
{
    public const DARK = 'dark';
    public const LIGHT = 'light';
    private const DEFAULT_ATTRIBUTES = [
        'url' => '',
        'text' => '',
        'type' => self::LIGHT,
        'icon' => '',
    ];

    public function __construct(
        private Renderer $renderer,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var LinkAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);

        if (empty($attributes['url']) || empty($attributes['text'])) {
            return '';
        }

        return $this->renderer->render(
            'common/link',
            $attributes
        );
    }
}
