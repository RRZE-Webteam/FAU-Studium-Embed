<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Filter;

use Fau\DegreeProgram\Common\Application\Filter\AreaOfStudyFilter;
use Fau\DegreeProgram\Common\Application\Filter\AttributeFilter;
use Fau\DegreeProgram\Common\Application\Filter\DegreeFilter;
use Fau\DegreeProgram\Common\Application\Filter\FacultyFilter;
use Fau\DegreeProgram\Common\Application\Filter\Filter;
use Fau\DegreeProgram\Common\Application\Filter\SearchKeywordFilter;
use Fau\DegreeProgram\Common\Application\Filter\SemesterFilter;
use Fau\DegreeProgram\Common\Application\Filter\StudyLocationFilter;
use Fau\DegreeProgram\Common\Application\Filter\SubjectGroupFilter;
use Fau\DegreeProgram\Common\Application\Filter\TeachingLanguageFilter;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TaxonomiesList;
use Fau\DegreeProgram\Output\Application\Filter\FilterView;
use Fau\DegreeProgram\Output\Infrastructure\Repository\WordPressTermRepository;
use WP_Taxonomy;
use WP_Term;

final class FilterViewFactory
{
    public function __construct(
        private TaxonomiesList $taxonomiesList,
        private WordPressTermRepository $termsRepository,
    ) {
    }

    /**
     * @return array<FilterView>
     */
    public function create(
        Filter ...$filters
    ): array {

        return array_filter(
            array_map([$this, 'createFilterView'], $filters)
        );
    }

    private function createFilterView(Filter $filter): ?FilterView
    {
        return match ($filter->id()) {
            AreaOfStudyFilter::KEY,
            AttributeFilter::KEY,
            DegreeFilter::KEY,
            FacultyFilter::KEY,
            SemesterFilter::KEY,
            StudyLocationFilter::KEY,
            SubjectGroupFilter::KEY,
            TeachingLanguageFilter::KEY => new FilterView(
                $filter,
                $this->labelFromTaxonomyLabel($filter->id()),
                FilterView::MULTISELECT,
                [
                    'options' => $this->buildOptionsFromTerms(
                        $filter->id(),
                        $this->termsRepository->findTerms(
                            (string) $this->taxonomiesList->convertRestBaseToSlug($filter->id())
                        )
                    ),
                ]
            ),
            SearchKeywordFilter::KEY => new FilterView(
                $filter,
                _x(
                    'Keyword',
                    'backoffice: Filter label',
                    'fau-degree-program-output',
                ),
                FilterView::TEXT
            ),
            default => null,
        };
    }

    /**
     * @param array<WP_Term> $terms
     * @return array<Option>
     */
    private function buildOptionsFromTerms(string $id, array $terms): array
    {
        $result = [];
        foreach ($terms as $term) {
            if ($term->parent) {
                continue;
            }

            $result[] = new Option(
                $id,
                $term->name,
                $term->term_id,
            );
        }

        return $result;
    }

    private function labelFromTaxonomyLabel(string $filterId): string
    {
        $taxonomyName = $this->taxonomiesList->convertRestBaseToSlug($filterId);

        if (!$taxonomyName) {
            return '';
        }

        $taxonomyObject = get_taxonomy($taxonomyName);

        if (!$taxonomyObject instanceof WP_Taxonomy) {
            return '';
        }

        /** @var string $label */
        $label = $taxonomyObject->labels->singular_name;

        return $label;
    }
}
