<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Filter\FilterFactory;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-type DegreeProgramsSearchAttributes = array{
 *     collection: PaginationAwareCollection<DegreeProgramViewTranslated>,
 *     output: 'tiles' | 'list',
 * }
 */
class DegreeProgramsCollection implements RenderableComponent
{
    public function __construct(
        private Renderer $renderer,
        private CurrentRequest $currentRequest,
    ) {
    }

    public function render(array $attributes = []): string
    {
        /**
         * @var DegreeProgramsSearchAttributes $attributes
         */
        if ($attributes['collection']->totalItems() === 0) {
            return $this->renderer->render('search/no-results');
        }

        return $this->renderer->render(
            'search/collection',
            [
                'collection' => $attributes['collection'],
                'currentOrder' => $this->currentRequest->orderBy(),
                'output' => $attributes['output'],
                'orderByOptions' => $this->orderByOptions(),
                'activeFilterNames' => $this->activeFilterNames(),
            ]
        );
    }

    /**
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    private function orderByOptions(): array
    {
        return [
            DegreeProgram::TITLE => [
                'label_asc' => _x(
                    'Sort by title',
                    'backoffice: Sort by options',
                    'fau-degree-program-output',
                ),
                'label_desc' => _x(
                    'Sort by title Z-A',
                    'backoffice: Sort by options',
                    'fau-degree-program-output',
                ),
            ],
            DegreeProgram::DEGREE => [
                'label_asc' => _x(
                    'Sort by degree',
                    'backoffice: Sort by options',
                    'fau-degree-program-output',
                ),
                'label_desc' => _x(
                    'Sort by degree Z-A',
                    'backoffice: Sort by options',
                    'fau-degree-program-output',
                ),
            ],
            DegreeProgram::START => [
                'label_asc' => _x(
                    'Sort by semester',
                    'backoffice: Sort by options',
                    'fau-degree-program-output',
                ),
                'label_desc' => _x(
                    'Sort by semester Z-A',
                    'backoffice: Sort by options',
                    'fau-degree-program-output',
                ),
            ],
            DegreeProgram::LOCATION => [
                'label_asc' => _x(
                    'Sort by study location',
                    'backoffice: Sort by options',
                    'fau-degree-program-output',
                ),
                'label_desc' => _x(
                    'Sort by study location Z-A',
                    'backoffice: Sort by options',
                    'fau-degree-program-output',
                ),
            ],
            DegreeProgram::ADMISSION_REQUIREMENTS => [
                'label_asc' => _x(
                    'Sort by admission requirement',
                    'backoffice: Sort by options',
                    'fau-degree-program-output',
                ),
                'label_desc' => _x(
                    'Sort by admission requirement Z-A',
                    'backoffice: Sort by options',
                    'fau-degree-program-output',
                ),
            ],
            DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS => [
                'label_asc' => _x(
                    'Sort by language certificates',
                    'backoffice: Sort by options',
                    'fau-degree-program-output',
                ),
                'label_desc' => _x(
                    'Sort by language certificates Z-A',
                    'backoffice: Sort by options',
                    'fau-degree-program-output',
                ),
            ],
        ];
    }

    /**
     * @return array<string>
     */
    private function activeFilterNames(): array
    {
        $activeFilters = array_filter(
            $this->currentRequest->getParams(array_keys(FilterFactory::SUPPORTED_FILTERS))
        );

        return array_keys($activeFilters);
    }
}
