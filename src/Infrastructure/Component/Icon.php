<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @psalm-type IconAttributes = array{
 *     className: string,
 *     name: string,
 * }
 */

class Icon implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'className' => '',
        'name' => '',
    ];

    public function __construct(
        private Renderer $renderer,
        private string $spriteUrl
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var IconAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);

        if (empty($attributes['name'])) {
            return '';
        }

        return $this->renderer->render(
            'common/icon',
            [
                'className' => $attributes['className'],
                'iconUrl' => $this->spriteUrl . '#' . $attributes['name'],
            ]
        );
    }
}
