<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Application\Filter\Filter;
use Fau\DegreeProgram\Common\Application\Filter\FilterFactory;
use Fau\DegreeProgram\Common\Application\Filter\SearchKeywordFilter;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use Fau\DegreeProgram\Output\Infrastructure\Filter\FilterViewFactory;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-type OutputType = 'tiles' | 'list';
 * @psalm-type DegreeProgramsSearchAttributes = array{
 *     lang: LanguageCodes,
 *     pre_applied_filters: array<string, array<int>>,
 *     visible_filters: array<string>,
 *     output: OutputType,
 * }
 *
 * `filters` keys are filterable taxonomy REST API bases,
 * and the values are an array of pre-selected term IDs.
 * The only edge case is `admission-requirements`,
 * which should be treated as a single taxonomy.
 * @see \Fau\DegreeProgram\Output\Infrastructure\Search\FilterableTermsUpdater::retrieveFilterableProperties
 * Example: array{
 *  teaching-language: array{0: 25, 1: 56},
 *  degree: array<empty, empty>,
 *  admission_requirements : array<empty, empty>
 * }
 */
final class DegreeProgramsSearch implements RenderableComponent
{
    private const MAX_VISIBLE_FILTERS = 3;
    private const DEFAULT_ATTRIBUTES = [
        'lang' => MultilingualString::DE,
        'filters' => [],
        'output' => 'tiles',
    ];

    public const OUTPUT_TILES = 'tiles';
    public const OUTPUT_LIST = 'list';
    public const OUTPUT_MODE_QUERY_PARAM = 'output';

    public function __construct(
        private Renderer $renderer,
        private DegreeProgramCollectionRepository $degreeProgramViewRepository,
        private CurrentRequest $currentRequest,
        private FilterViewFactory $filterViewFactory,
        private FilterFactory $filterFactory,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var DegreeProgramsSearchAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);

        $preAppliedFilters = $this->filterFactory->bulk($attributes['pre_applied_filters']);
        $visibleFilters = $this->filterFactory->bulk(
            $this->populateVisibleFiltersFromRequest($attributes['visible_filters'])
        );
        $searchFilter = new SearchKeywordFilter($this->currentRequest->searchKeyword());
        $userAppliedFilters = array_filter(
            array_merge($visibleFilters, [$searchFilter]),
            static fn (Filter $filter) => !empty($filter->value()),
        );

        $collection = $this->degreeProgramViewRepository->findTranslatedCollection(
            CollectionCriteria::new()
                ->withPerPage(-1)
                ->addFilter(
                    ...$preAppliedFilters,
                    ...$userAppliedFilters,
                )
                ->withOrderby(...$this->currentRequest->orderby()),
            $attributes['lang'],
        );

        $filterViews = $this->filterViewFactory->create(...$visibleFilters);

        return $this->renderer->render(
            'search/search',
            [
                'collection' => $collection,
                'filters' => array_slice($filterViews, 0, self::MAX_VISIBLE_FILTERS),
                'advancedFilters' => array_slice(
                    $filterViews,
                    self::MAX_VISIBLE_FILTERS,
                ),
                'output' => $this->sanitizedOutputMode($attributes['output']),
                'activeFilters' => $this->filterViewFactory->create(
                    ...$userAppliedFilters
                ),
            ],
        );
    }

    /**
     * @param array<string> $visibleFilterNames
     * @return array<string, mixed>
     */
    private function populateVisibleFiltersFromRequest(array $visibleFilterNames): array
    {
        $result = [];

        foreach ($visibleFilterNames as $filterId) {
            $result[$filterId] = $this->currentRequest->get($filterId, []);
        }

        return $result;
    }

    /**
     * @param OutputType $mode
     * @return OutputType
     */
    private function sanitizedOutputMode(string $mode): string
    {
        $outputMode = (string) filter_input(
            INPUT_GET,
            self::OUTPUT_MODE_QUERY_PARAM,
            FILTER_SANITIZE_SPECIAL_CHARS
        ) ?: $mode;

        if (!in_array($outputMode, [self::OUTPUT_LIST, self::OUTPUT_TILES], true)) {
            return self::DEFAULT_ATTRIBUTES['output'];
        }

        return $outputMode;
    }

    private function criteriaWithOrderby(CollectionCriteria $criteria): CollectionCriteria
    {
        $currentOrderBy = $this->currentRequest->orderby();

        return $criteria->withOrderby(
            $currentOrderBy[0],
            $currentOrderBy[1]
        );
    }
}
