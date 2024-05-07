<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Shortcode;

use Fau\DegreeProgram\Common\Application\Filter\Filter;
use Fau\DegreeProgram\Common\Application\Filter\FilterFactory;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TaxonomiesList;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramCombinations;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramsSearch;
use Fau\DegreeProgram\Output\Infrastructure\Component\RenderableComponent;
use Fau\DegreeProgram\Output\Infrastructure\Component\SingleDegreeProgram;
use Fau\DegreeProgram\Output\Infrastructure\Repository\WordPressTermRepository;

final class ShortcodeAttributesNormalizer
{
    private const SINGLE_OUTPUT_FORMATS = ['short', 'full'];

    /**
     * @var array<string, callable(array<string, mixed>): array<string, mixed>>
     */
    private array $map;

    public function __construct(
        private WordPressTermRepository $termsRepository,
        private TaxonomiesList $taxonomiesList,
    ) {

        $this->map = [
            DegreeProgramsSearch::class => [$this, 'search'],
            SingleDegreeProgram::class => [$this, 'single'],
            DegreeProgramCombinations::class => [$this, 'combinations'],
        ];
    }

    /**
     * @param class-string<RenderableComponent> $componentId
     * @param array<string, mixed> $rawAttributes
     * @return array<string, mixed>
     */
    public function normalize(string $componentId, array $rawAttributes): array
    {
        $callable = $this->map[$componentId] ?? null;
        if (!$callable) {
            return $rawAttributes;
        }

        $normalizedAttributes = $callable($rawAttributes);

        return $this->common($normalizedAttributes);
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    private function common(array $attributes): array
    {
        $lang = (string) ($attributes['lang'] ?? MultilingualString::DE);

        if (!isset(MultilingualString::LANGUAGES[$lang])) {
            $attributes['lang'] = MultilingualString::DE;
        }

        return $attributes;
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    private function search(array $attributes): array
    {
        /** @var string[] */
        $listOfSupportedFilters = array_keys(FilterFactory::SUPPORTED_FILTERS);

        /** @var array<string> $availableFilters */
        $availableFilters = wp_parse_list((string) ($attributes['filters'] ?? ''));
        unset($attributes['filters']);
        /** @var array<Filter> $preAppliedFilters */
        $preAppliedFilters = [];

        $visibleFilters = ['search'];
        foreach ($availableFilters as $filter) {
            if (!in_array($filter, $listOfSupportedFilters, true)) {
                continue;
            }

            $preAppliedFilter = $this->preAppliedFilter(
                $filter,
                $attributes[$filter] ?? null,
            );

            if ($preAppliedFilter) {
                $preAppliedFilters[$filter] = $preAppliedFilter;
                continue;
            }

            $visibleFilters[] = $filter;
        }

        $attributes['visible_filters'] = $visibleFilters;
        $attributes['pre_applied_filters'] = $preAppliedFilters;
        $attributes['hidden_elements'] = wp_parse_list((string) ($attributes['hide'] ?? ''));
        unset($attributes['hide']);

        return $attributes;
    }

    /** @return array<int> */
    private function preAppliedFilter(string $filterName, mixed $filterValue): array
    {
        if (!is_string($filterValue)) {
            return [];
        }

        return array_filter(
            array_map(
                fn ($identifier) => $this->termsRepository->findTermId(
                    trim($identifier),
                    (string) $this->taxonomiesList->convertRestBaseToSlug(
                        $filterName
                    )
                ),
                explode(',', $filterValue)
            )
        );
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    private function single(array $attributes): array
    {
        $attributes['id'] = (int) ($attributes['id'] ?? 0);
        $attributes['include'] = wp_parse_list((string) ($attributes['include'] ?? ''));
        $attributes['exclude'] = wp_parse_list((string) ($attributes['exclude'] ?? ''));
        $attributes['className'] = 'is-shortcode';

        if (
            array_key_exists('format', $attributes)
            && !in_array($attributes['format'], self::SINGLE_OUTPUT_FORMATS, true)
        ) {
            $attributes['format'] = 'full';
        }

        return $attributes;
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    private function combinations(array $attributes): array
    {
        $attributes['faculty'] = wp_parse_id_list((string) ($attributes['faculty'] ?? ''));
        $attributes['degree'] = wp_parse_id_list((string) ($attributes['degree'] ?? ''));

        return $attributes;
    }
}
