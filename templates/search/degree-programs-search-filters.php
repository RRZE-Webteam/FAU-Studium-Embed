<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramsSearch;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     filters: array<string, array<int>>,
 *     output: 'tiles' | 'list',
 *     outputModeUrls: array<'tiles' | 'list', string>,
 * } $data
 * @var array $data
 * @var Renderer $renderer
 */

[
    'filters' => $filters,
    'output' => $output,
    'outputModeUrls' => $outputModeUrls,
] = $data;

?>

<ul class="c-degree-programs-search__filters">
    <?php foreach ($filters as $filter => $preselectedValues) : ?>
        <li class="search-filter">
            <div class="search-filter__label">
                <?= esc_html($filter) ?>
            </div>
        </li>
    <?php endforeach; ?>

    <li class="search-filter search-filter--output">
        <div class="search-filter__label screen-reader-text" role="label">
            <?= esc_html_x(
                'Layout',
                'frontoffice: degree programs search form',
                'fau-degree-program-output'
            ) ?>
        </div>
        <div class="search-filter__options">
            <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?= $renderer->render('search/output-mode-toggle', [
                'url' => $outputModeUrls[DegreeProgramsSearch::OUTPUT_TILES],
                'selected' => $output === DegreeProgramsSearch::OUTPUT_TILES,
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
                'label' => esc_html_x(
                    'Switch to list view',
                    'frontoffice: degree programs search form',
                    'fau-degree-program-output'
                ),
                'icon' => 'list',
            ]) ?>
            <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    </li>
</ul>
