<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @psalm-type DetailAttributes = array{
 *     icon: string,
 *     term: string,
 *     description: string,
 * }
 */
final class DegreeProgramDetail implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'icon' => '',
        'term' => '',
        'description' => '',
    ];

    public function __construct(
        private Renderer $renderer,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var DetailAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);

        if (empty($attributes['term'])) {
            return '';
        }

        return $this->renderer->render(
            'single-degree-program/detail',
            $attributes
        );
    }
}
