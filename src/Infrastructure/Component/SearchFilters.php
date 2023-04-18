<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramsSearch;

/**
 * @psalm-import-type OutputType from DegreeProgramsSearch
 * @psalm-type SearchFiltersAttributes = array{
 *     filters: array<string, array<int>>,
 *     output: OutputType,
 * }
 */
class SearchFilters implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'filters' => [],
        'output' => 'tiles',
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
                'output' => $attributes['output'],
                'outputModeUrls' => $this->outputModeUrls(),
            ]
        );
    }

    private function outputModeUrls(): array
    {
        return [
            DegreeProgramsSearch::OUTPUT_LIST => add_query_arg(
                [DegreeProgramsSearch::OUTPUT_MODE_QUERY_PARAM => DegreeProgramsSearch::OUTPUT_LIST],
            ),
            DegreeProgramsSearch::OUTPUT_TILES => add_query_arg(
                [DegreeProgramsSearch::OUTPUT_MODE_QUERY_PARAM => DegreeProgramsSearch::OUTPUT_TILES],
            ),
        ];
    }
}
