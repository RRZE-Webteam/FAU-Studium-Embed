<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Output\Application\Filter\FilterView;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramsCollection;
use Fau\DegreeProgram\Output\Infrastructure\Component\SearchFilters;
use Fau\DegreeProgram\Output\Infrastructure\Component\SearchForm;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     collection: PaginationAwareCollection<DegreeProgramViewTranslated>,
 *     filters: array<FilterView>,
 *     output: 'list' | 'tiles',
 *     activeFilters: array<FilterView>,
 * } $data
 * @var array $data
 */

[
    'collection' => $collection,
    'filters' => $filters,
    'output' => $output,
    'activeFilters' => $activeFilters,
] = $data;

?>

<div class="c-degree-programs-search">
    <h1 class="c-degree-programs-search__title">
        <?= esc_html_x(
            'Degree programs',
            'frontoffice: degree programs search form',
            'fau-degree-program-output'
        ) ?>
    </h1>

    <?= renderComponent(
        new Component(
            SearchForm::class,
            []
        )
    ) ?>

    <?= renderComponent(
        new Component(
            SearchFilters::class,
            [
                'filters' => $filters,
                'output' => $output,
                'activeFilters' => $activeFilters,
            ]
        )
    ) ?>


    <?= renderComponent(
        new Component(
            DegreeProgramsCollection::class,
            [
                'collection' => $collection,
                'output' => $output,
            ]
        )
    ) ?>
</div>
