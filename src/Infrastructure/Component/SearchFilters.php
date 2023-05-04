<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Application\Filter\FilterView;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;

/**
 * @psalm-import-type OutputType from DegreeProgramsSearch
 * @psalm-type SearchFiltersAttributes = array{
 *     filters: array<FilterView>,
 *     output: OutputType,
 *     activeFilters: array<FilterView>,
 *     advancedFilters: array<FilterView>,
 * }
 */
class SearchFilters implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'filters' => [],
        'activeFilters' => [],
        'advancedFilters' => [],
        'output' => 'tiles',
    ];

    public function __construct(
        private Renderer $renderer,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @psalm-var SearchFiltersAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);

        return $this->renderer->render(
            'search/filter/search-filters',
            [
                'filters' => $attributes['filters'],
                'activeFilters' => $attributes['activeFilters'],
                'advancedFilters' => $attributes['advancedFilters'],
                'output' => $attributes['output'],
                'outputModeUrls' => $this->outputModeUrls(),
            ]
        );
    }

    private function outputModeUrls(): array
    {
        return [
            DegreeProgramsSearch::OUTPUT_LIST => add_query_arg(
                [CurrentRequest::OUTPUT_MODE_QUERY_PARAM => DegreeProgramsSearch::OUTPUT_LIST],
            ),
            DegreeProgramsSearch::OUTPUT_TILES => add_query_arg(
                [CurrentRequest::OUTPUT_MODE_QUERY_PARAM => DegreeProgramsSearch::OUTPUT_TILES],
            ),
        ];
    }
}
