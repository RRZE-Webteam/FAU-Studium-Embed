<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Application\Filter\FilterView;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     activeFilters: array{url: string, label: string}[],
 *     removeAllUrl: string,
 * } $data
 * @var array $data
 * @var Renderer $renderer
 */

[
    'activeFilters' => $activeFilters,
    'removeAllUrl' => $removeAllUrl,
] = $data;

?>

<div id="fau_applied_filters" class="c-degree-programs-search-filters-active-filters">
    <ul class="c-degree-programs-search-filters-active-filters__list">
        <?php foreach ($activeFilters as $filter) : ?>
        <li>
            <a
                class="c-degree-programs-search-filters-active-filters__item"
                href="<?= esc_url($filter['url']) ?>"
            >
                <?= renderComponent(
                    new Component(
                        Icon::class,
                        ['name' => 'close']
                    )
                ) ?>
                <?= esc_html($filter['label']) ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>

    <a
        href="<?= esc_url($removeAllUrl) ?>"
        class="c-degree-programs-search-filters-active-filters__clear-all-button"
    >
        <?= renderComponent(
            new Component(
                Icon::class,
                ['name' => 'close']
            )
        ) ?>
        <?= esc_html_x(
            'Clear all filters',
            'backoffice: Degree program search filters',
            'fau-degree-program-output',
        ) ?>
    </a>
</div>
