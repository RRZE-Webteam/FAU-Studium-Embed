<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Application\Filter\FilterFactory;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Application\Filter\FilterView;
use Fau\DegreeProgram\Output\Infrastructure\Filter\Option;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;

/**
 * @psalm-type ActiveFiltersAttributes = array{
 *     activeFilters: FilterView[],
 *     removeAllUrl: string,
 * }
 */
class ActiveFilters implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'activeFilters' => [],
        'removeAllUrl' => '',
    ];

    public function __construct(
        private Renderer $renderer,
        private CurrentRequest $currentRequest,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var ActiveFiltersAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);
        $activeFilterGroups = [];

        foreach ($attributes['activeFilters'] as $filterView) {
            if ($filterView->type() === FilterView::TEXT) {
                $activeFilterGroups[] = [
                    [
                        'label' => sprintf(
                            '%s: %s',
                            $filterView->label(),
                            (string) $filterView->value(),
                        ),
                        'url' => remove_query_arg($filterView->id()),
                    ],
                ];

                continue;
            }

            $activeFilterGroups[] = $this->activeFiltersFromMultiChoiceFilter($filterView);
        }

        return $this->renderer->render(
            'search/filter/active-filters',
            [
                'activeFilters' => array_merge([], ...$activeFilterGroups),
                'removeAllUrl' => $this->removeAllUrl(),
            ]
        );
    }

    private function activeFiltersFromMultiChoiceFilter(FilterView $filterView): array
    {
        /** @var array<int|string> $values */
        $values = $filterView->value();

        /** @var array<Option>|null $options */
        $options = $filterView->templateData()['options'] ?? null;

        if (!$options) {
            return [];
        }

        $result = [];
        foreach ($values as $value) {
            $selectedOption = array_values(
                array_filter(
                    $options,
                    static fn(Option $option) => $option->value() === $value,
                )
            )[0] ?? null;

            if (!$selectedOption) {
                continue;
            }

            $result[] = [
                'label' => sprintf(
                    '%s: %s',
                    $filterView->label(),
                    $selectedOption->label(),
                ),
                'url' => $this->removeUrl($filterView->id(), $value),
            ];
        }

        return $result;
    }

    private function removeUrl(string $key, int|string $value): string
    {
        $currentValue = $this->currentRequest->queryStrings()[$key] ?? null;

        if (!is_array($currentValue)) {
            return '';
        }

        $newValue = array_filter(
            $currentValue,
            static fn($item) => (string) $item !== (string) $value,
        );

        if (count($newValue) === 0) {
            return remove_query_arg($key);
        }

        return add_query_arg($key, $newValue);
    }

    private function removeAllUrl(): string
    {
        /** @var array<string> $keys */
        $keys = array_keys(FilterFactory::SUPPORTED_FILTERS);

        return remove_query_arg($keys);
    }
}
