<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Filter;

use Fau\DegreeProgram\Common\Application\Filter\AdmissionRequirementTypeFilter;
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
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TaxonomiesList;
use Fau\DegreeProgram\Common\Infrastructure\Repository\BilingualRepository;
use Fau\DegreeProgram\Common\Infrastructure\Repository\IdGenerator;
use Fau\DegreeProgram\Output\Application\Filter\FilterView;
use Fau\DegreeProgram\Output\Infrastructure\Repository\WordPressTermRepository;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use WP_Term;

final class FilterViewFactory
{
    public function __construct(
        private TaxonomiesList $taxonomiesList,
        private WordPressTermRepository $termsRepository,
        private IdGenerator $idGenerator,
        private CurrentRequest $currentRequest,
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

    /**
     * phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    private function createFilterView(Filter $filter): ?FilterView
    {
        return match ($filter->id()) {
            AreaOfStudyFilter::KEY => new FilterView(
                $filter,
                _x(
                    'Area of study',
                    'frontoffice: Filter label',
                    'fau-degree-program-output',
                ),
                FilterView::MULTISELECT,
                [
                    'options' => $this->buildOptionsForTaxonomyBasedFilter($filter),
                ]
            ),
            AttributeFilter::KEY => new FilterView(
                $filter,
                _x(
                    'Special ways to study',
                    'frontoffice: Filter label',
                    'fau-degree-program-output',
                ),
                FilterView::MULTISELECT,
                [
                    'options' => $this->buildOptionsForTaxonomyBasedFilter($filter),
                ]
            ),
            DegreeFilter::KEY => new FilterView(
                $filter,
                _x(
                    'Degree',
                    'frontoffice: Filter label',
                    'fau-degree-program-output',
                ),
                FilterView::MULTISELECT,
                [
                    'options' => $this->buildOptionsForTaxonomyBasedFilter($filter),
                ]
            ),
            FacultyFilter::KEY => new FilterView(
                $filter,
                _x(
                    'Faculty',
                    'frontoffice: Filter label',
                    'fau-degree-program-output',
                ),
                FilterView::MULTISELECT,
                [
                    'options' => $this->buildOptionsForTaxonomyBasedFilter($filter),
                ]
            ),
            SemesterFilter::KEY => new FilterView(
                $filter,
                _x(
                    'Start of degree program',
                    'frontoffice: Filter label',
                    'fau-degree-program-output',
                ),
                FilterView::MULTISELECT,
                [
                    'options' => $this->buildOptionsForTaxonomyBasedFilter($filter),
                ]
            ),
            StudyLocationFilter::KEY => new FilterView(
                $filter,
                _x(
                    'Study location',
                    'frontoffice: Filter label',
                    'fau-degree-program-output',
                ),
                FilterView::MULTISELECT,
                [
                    'options' => $this->buildOptionsForTaxonomyBasedFilter($filter),
                ]
            ),
            SubjectGroupFilter::KEY => new FilterView(
                $filter,
                _x(
                    'Subject group',
                    'frontoffice: Filter label',
                    'fau-degree-program-output',
                ),
                FilterView::MULTISELECT,
                [
                    'options' => $this->buildOptionsForTaxonomyBasedFilter($filter),
                ]
            ),
            TeachingLanguageFilter::KEY => new FilterView(
                $filter,
                _x(
                    'Teaching language',
                    'frontoffice: Filter label',
                    'fau-degree-program-output',
                ),
                FilterView::MULTISELECT,
                [
                    'options' => $this->buildOptionsForTaxonomyBasedFilter($filter),
                ]
            ),
            SearchKeywordFilter::KEY => new FilterView(
                $filter,
                _x(
                    'Keyword',
                    'frontoffice: Filter label',
                    'fau-degree-program-output',
                ),
                FilterView::TEXT
            ),
            AdmissionRequirementTypeFilter::KEY => new FilterView(
                $filter,
                _x(
                    'Admission Requirement',
                    'frontoffice: Filter label',
                    'fau-degree-program-output',
                ),
                FilterView::MULTISELECT,
                [
                    'options' => $this->admissionRequirementTypeOptions(),
                ],
            ),
            default => null,
        };
    }

    /**
     * @return array<Option>
     */
    private function buildOptionsForTaxonomyBasedFilter(Filter $filter): array
    {
        $terms = $this->termsRepository->findTerms(
            (string) $this->taxonomiesList->convertRestBaseToSlug($filter->id())
        );

        $result = [];
        foreach ($terms as $term) {
            if ($term->parent) {
                continue;
            }

            $result[] = new Option(
                $filter->id(),
                $this->translatedTermName($term),
                $term->term_id,
            );
        }

        return $result;
    }

    /**
     * @return array<Option>
     */
    private function admissionRequirementTypeOptions(): array
    {
        return [
            new Option(
                AdmissionRequirementTypeFilter::KEY,
                _x(
                    'Restricted (NC)',
                    'backoffice: Filter label',
                    'fau-degree-program-output',
                ),
                AdmissionRequirementTypeFilter::RESTRICTED,
            ),
            new Option(
                AdmissionRequirementTypeFilter::KEY,
                _x(
                    'Admission free',
                    'backoffice: Filter label',
                    'fau-degree-program-output',
                ),
                AdmissionRequirementTypeFilter::FREE,
            ),
            new Option(
                AdmissionRequirementTypeFilter::KEY,
                _x(
                    'Admission free with restriction',
                    'backoffice: Filter label',
                    'fau-degree-program-output',
                ),
                AdmissionRequirementTypeFilter::FREE_WITH_RESTRICTION,
            ),
        ];
    }

    private function translatedTermName(WP_Term $term): string
    {
        $title = MultilingualString::fromTranslations(
            $this->idGenerator->generateTermMetaId($term, 'name'),
            $term->name,
            (string) get_term_meta(
                $term->term_id,
                BilingualRepository::addEnglishSuffix('name'),
                true
            ),
        );

        $languageCode = $this->currentRequest->languageCode();

        return $title->asString($languageCode);
    }
}
