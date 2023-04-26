<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Application\Filter\Filter;
use Fau\DegreeProgram\Common\Application\Filter\FilterFactory;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TaxonomiesList;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Application\Filter\FilterView;
use Fau\DegreeProgram\Output\Infrastructure\Filter\Option;
use Fau\DegreeProgram\Output\Infrastructure\Repository\WordPressTermRepository;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use WP_Term;

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
        private WordPressTermRepository $termRepository,
        private TaxonomiesList $taxonomiesList,
        private CurrentRequest $currentRequest,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var ActiveFiltersAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);
        $activeFilters = [];

        if (!count($attributes['activeFilters'])) {
            return '';
        }

        foreach ($attributes['activeFilters'] as $filterView) {
            $value = $filterView->value();

            if ($filterView->type() === FilterView::TEXT) {
                $activeFilters[] = [
                    'label' => sprintf(
                        '%s: %s',
                        $filterView->label(),
                        (string) $filterView->value(),
                    ),
                    'url' => remove_query_arg($filterView->id()),
                ];

                continue;
            }

            $activeFilters = array_merge(
                $activeFilters,
                // Each selected item in multi choice filter is considered separately
                $this->activeFiltersFromMultiChoiceFilter($filterView),
            );
        }

        return $this->renderer->render(
            'search/filter/active-filters',
            [
                'activeFilters' => $activeFilters,
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
                    static fn (Option $option) => $option->value() === $value,
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
                'url' => $this->removeUrl($filterView->id(), (string) $value),
            ];
        }

        return $result;
    }

    private function removeUrl(string $key, string $value): string
    {
        $currentValue = $this->currentRequest->queryStrings()[$key] ?? null;

        if (!is_string($currentValue)) {
            return '';
        }

        // Remove entirely if only one id is set
        if (!str_contains($currentValue, ',')) {
            return remove_query_arg($key);
        }

        $newValue = implode(
            ',',
            array_filter(
                explode(',', $currentValue),
                static fn ($item) => $item !== $value,
            ),
        );

        return add_query_arg($key, $newValue);
    }

    private function removeAllUrl(): string
    {
        /** @var array<string> $keys */
        $keys = array_keys(FilterFactory::SUPPORTED_FILTERS);

        return remove_query_arg($keys);
    }
}
