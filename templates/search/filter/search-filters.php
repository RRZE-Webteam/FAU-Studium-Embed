<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Application\Filter\FilterView;
use Fau\DegreeProgram\Output\Infrastructure\Component\AdvancedFilters;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramsSearch;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     filters: array<FilterView>,
 *     advancedFilters: array<FilterView>,
 *     output: 'tiles' | 'list',
 *     outputModeUrls: array<'tiles' | 'list', string>,
 * } $data
 * @var array $data
 * @var Renderer $renderer
 */

[
    'filters' => $filters,
    'advancedFilters' => $advancedFilters,
    'output' => $output,
    'outputModeUrls' => $outputModeUrls,
] = $data;

$buttonId = 'button_' . wp_generate_uuid4();
$contentId = 'content_' . wp_generate_uuid4();

?>

<div
    class="c-search-filters fau-dropdown"
    role="group"
    aria-labelledby="<?= esc_attr($buttonId) ?>"
    aria-expanded="false"
>
    <div class="c-search-filters__mobile-toggle fau-dropdown__toggle">
        <?= esc_html_x(
            'Show filter options',
            'frontoffice: degree programs search form',
            'fau-degree-program-output'
        ) ?>

        <?= renderComponent(
            new Component(
                Icon::class,
                ['name' => 'accordion-arrow']
            )
        ) ?>
    </div>

    <div
        class="c-search-filters__inner fau-dropdown__content"
        id="<?= esc_attr($contentId) ?>"
    >
        <?php foreach ($filters as $filter) : ?>
            <div class="search-filter">
                <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?= $renderer->render(
                    'search/filter/filter-dropdown',
                    [
                        'filter' => $filter,
                    ]
                ) ?>
                <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        <?php endforeach ?>

        <?= renderComponent(
            new Component(
                AdvancedFilters::class,
                [
                    'filters' => $advancedFilters,
                ],
            ),
        ) ?>

        <div class="search-filter search-filter--output">
            <div class="search-filter__label screen-reader-text" role="label">
                <?= esc_html_x(
                    'Layout',
                    'frontoffice: degree programs search form',
                    'fau-degree-program-output'
                ) ?>
            </div>
            <div class="search-filter__output_modes">
                <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?= $renderer->render('search/output-mode-toggle', [
                    'url' => $outputModeUrls[DegreeProgramsSearch::OUTPUT_TILES],
                    'selected' => $output === DegreeProgramsSearch::OUTPUT_TILES,
                    'mode' => DegreeProgramsSearch::OUTPUT_TILES,
                    'label' => esc_html_x(
                        'Switch to grid view',
                        'frontoffice: degree programs search form',
                        'fau-degree-program-output'
                    ),
                    'icon' => 'grid',
                ]) ?>
                <?= $renderer->render('search/output-mode-toggle', [
                    'url' => $outputModeUrls[DegreeProgramsSearch::OUTPUT_LIST],
                    'selected' => $output === DegreeProgramsSearch::OUTPUT_LIST,
                    'mode' => DegreeProgramsSearch::OUTPUT_LIST,
                    'label' => esc_html_x(
                        'Switch to list view',
                        'frontoffice: degree programs search form',
                        'fau-degree-program-output'
                    ),
                    'icon' => 'list',
                ]) ?>
                <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </div>
    </div>
</div>
