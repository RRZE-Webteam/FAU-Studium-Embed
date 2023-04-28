<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Application\Filter\FilterView;

/**
 * @psalm-type AdvancedFiltersAttributes = array{
 *     filters: FilterView[],
 * }
 */
class AdvancedFilters implements RenderableComponent
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
        /** @var AdvancedFiltersAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);

        if (!count($attributes['filters'])) {
            return '';
        }

        return $this->renderer->render(
            'search/filter/advanced-filters-dropdown',
            [
                'filters' => $attributes['filters'],
                'selectedCount' => $this->selectedFilterItemsCount($attributes['filters']),
            ]
        );
    }

    /**
     * @param array<FilterView> $filters
     */
    private function selectedFilterItemsCount(array $filters): int
    {
        $count = 0;

        foreach ($filters as $filter) {
            $value = $filter->value();
            if (!is_array($value)) {
                continue;
            }

            $count += count($value);
        }

        return $count;
    }
}
