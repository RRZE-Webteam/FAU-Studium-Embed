<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Application\RelatedDegreeProgram;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @psalm-type CombinationsAttributes = array{
 *     title: string,
 *     type: string,
 *     list: array<RelatedDegreeProgram>
 * }
 */
class Combinations implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'title' => '',
        'type' => '',
        'list' => '',
    ];

    public function __construct(
        private Renderer $renderer,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var CombinationsAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);

        if (empty($attributes['list'])) {
            return '';
        }

        return $this->renderer->render(
            'single-degree-program/combinations',
            $attributes
        );
    }
}
