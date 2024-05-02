<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Filter\FilterFactory;
use Fau\DegreeProgram\Common\Application\Filter\SearchKeywordFilter;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Application\Filter\FilterView;
use Fau\DegreeProgram\Output\Infrastructure\Filter\FilterViewFactory;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\LocaleHelper;
use Fau\DegreeProgram\Output\Infrastructure\Template\HiddenDegreeProgramElements;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-type OutputType = 'tiles'|'list'
 * @psalm-type DegreeProgramsSearchAttributes = array{
 *     lang: LanguageCodes,
 *     pre_applied_filters: array<string, array<int>>,
 *     visible_filters: array<string>,
 *     output: OutputType,
 *     excluded_elements: array<string>,
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
    public const DEFAULT_ATTRIBUTES = [
        'lang' => MultilingualString::DE,
        'output' => 'tiles',
        'visible_filters' => [],
        'pre_applied_filters' => [],
        'excluded_elements' => [],
    ];

    public const OUTPUT_TILES = 'tiles';
    public const OUTPUT_LIST = 'list';

    public function __construct(
        private Renderer                          $renderer,
        private DegreeProgramCollectionRepository $degreeProgramViewRepository,
        private CurrentRequest                    $currentRequest,
        private FilterViewFactory                 $filterViewFactory,
        private FilterFactory                     $filterFactory,
        private HiddenDegreeProgramElements       $excludeDegreeProgramElements,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        $localeHelper = LocaleHelper::new();
        $attributes['lang'] = $attributes['lang'] ?? $this->currentRequest->languageCode();

        /** @var DegreeProgramsSearchAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);

        $collection = $this->findCollection($attributes);

        $localeHelper = $localeHelper->withLocale(
            $localeHelper->localeFromLanguageCode($attributes['lang'])
        );

        add_filter('locale', [$localeHelper, 'filterLocale']);

        $filterViews = $this->buildFilterViews($attributes);
        [$filters, $advancedFilters] = $this->splitFilterViews(...$filterViews);

        $html = $this->renderer->render(
            'search/search',
            [
                'collection' => $collection,
                'filters' => $filters,
                'advancedFilters' => $advancedFilters,
                'output' => self::sanitizeOutputMode(
                    $this->currentRequest->outputMode() ?: $attributes['output']
                ),
                'activeFilters' => array_filter(
                    $filterViews,
                    static fn(FilterView $filterView) => !empty($filterView->value()),
                ),
                'excludedElements' => $this->excludeDegreeProgramElements->excludeElements(
                    $attributes['excluded_elements']
                ),
            ],
        );
        remove_filter('locale', [$localeHelper, 'filterLocale']);

        return $html;
    }

    /**
     * @param DegreeProgramsSearchAttributes $attributes
     * @return array<FilterView>
     */
    private function buildFilterViews(array $attributes): array
    {
        return $this->filterViewFactory->create(
            ...$this->filterFactory->bulk(
                $this->currentRequest->getParams(
                    $attributes['visible_filters'] ?? []
                )
            )
        );
    }

    /**
     * @param FilterView ...$filterViews
     * @return array{0: array<FilterView>, 1: array<FilterView>}
     */
    private function splitFilterViews(FilterView ...$filterViews): array
    {
        $withoutSearchFilter = array_filter(
            $filterViews,
            static fn(FilterView $filterView) => $filterView->id() !== SearchKeywordFilter::KEY,
        );

        return [
            array_slice($withoutSearchFilter, 0, self::MAX_VISIBLE_FILTERS),
            array_slice(
                $withoutSearchFilter,
                self::MAX_VISIBLE_FILTERS,
            ),
        ];
    }

    /**
     * @psalm-param DegreeProgramsSearchAttributes $attributes
     * @psalm-return PaginationAwareCollection<DegreeProgramViewTranslated>
     */
    public function findCollection(array $attributes): ?PaginationAwareCollection
    {
        $filters = $this->filterFactory->bulk(
            array_merge(
                $attributes['pre_applied_filters'],
                $this->currentRequest->getParams(
                    $attributes['visible_filters']
                )
            )
        );

        return $this->degreeProgramViewRepository->findTranslatedCollection(
            CollectionCriteria::new()
                ->withPerPage(-1)
                ->addFilter(...$filters)
                ->withOrderBy($this->currentRequest->orderBy()),
            $attributes['lang'],
        );
    }

    /**
     * @param string $mode
     * @return OutputType
     */
    private static function sanitizeOutputMode(string $mode): string
    {
        return in_array($mode, [self::OUTPUT_LIST, self::OUTPUT_TILES], true)
            ? $mode
            : self::DEFAULT_ATTRIBUTES['output'];
    }
}
