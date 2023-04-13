<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @psalm-type SearchFiltersAttributes = array{
 *     filters: array<string, array<int>>,
 * }
 */
class SearchFilters implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'filters' => [],
    ];

    public function __construct(
        private Renderer $renderer,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var SearchFiltersAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);

        return $this->renderer->render(
            'search/degree-programs-search-filters',
            [
                'filters' => $attributes['filters'],
            ]
        );
    }
}
