<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @psalm-type PreAppliedFilters = array{
 *     preAppliedFilters: array<string, list<string|int>>,
 * }
 */
class PreAppliedFilters implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'preAppliedFilters' => [],
    ];

    public function __construct(
        private Renderer $renderer,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var PreAppliedFilters $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);
        $preAppliedFilters = [];

        foreach ($attributes['preAppliedFilters'] as $name => $values) {
            foreach ($values as $value) {
                $preAppliedFilters[] = [
                    'name' => $name,
                    'value' => (string) $value,
                ];
            }
        }

        return $this->renderer->render(
            'search/filter/pre-applied-filters',
            [
                'preAppliedFilters' => $preAppliedFilters,
            ]
        );
    }
}
