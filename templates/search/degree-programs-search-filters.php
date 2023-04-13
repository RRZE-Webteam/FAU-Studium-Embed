<?php

declare(strict_types=1);

use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     filters: array<string, array<int>>,
 * } $data
 * @var array $data
 */

[
    'filters' => $filters,
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
            <button type="button" class="is-active" aria-selected="true">
                <span class="screen-reader-text">
                    <?= esc_html_x(
                        'Switch to grid view',
                        'frontoffice: degree programs search form',
                        'fau-degree-program-output'
                    ) ?>
                </span>
                <?= renderComponent(
                    new Component(
                        Icon::class,
                        ['name' => 'grid']
                    )
                ) ?>
            </button>

            <button type="button">
                <span class="screen-reader-text">
                    <?= esc_html_x(
                        'Switch to list view',
                        'frontoffice: degree programs search form',
                        'fau-degree-program-output'
                    ) ?>
                </span>
                <?= renderComponent(
                    new Component(
                        Icon::class,
                        ['name' => 'list']
                    )
                ) ?>
            </button>
        </div>
    </li>
</ul>
