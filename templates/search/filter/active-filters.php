<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
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

$class = !$activeFilters ? 'hidden' : '';

?>

<div id="fau_applied_filters" class="c-active-search-filters <?= esc_attr($class) ?>">
    <nav class="c-active-search-filters__list">
        <?php foreach ($activeFilters as $filter) : ?>
            <a
                class="c-active-search-filters__item"
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
        <?php endforeach; ?>
    </nav>

    <a
        href="<?= esc_url($removeAllUrl) ?>"
        class="c-active-search-filters__clear-all-button"
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
