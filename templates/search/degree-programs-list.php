<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramCard;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramListItem;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     collection: PaginationAwareCollection<DegreeProgramViewTranslated>,
 *     currentOrder: 'desc' | 'asc',
 * } $data
 * @var array $data
 * @var Renderer $renderer
 */

[
    'collection' => $collection,
    'currentOrder' => $currentOrder,
] = $data;

?>


<ul class="c-degree-programs-list c-degree-programs-list-table">
    <li class="c-degree-programs-list__header">
        <div class="c-sort-selector">
            <?= sprintf(
                // translators: %s is the orderby value
                esc_html_x(
                    'Sort by %s',
                    'frontoffice: degree programs search result list',
                    'fau-degree-program-output',
                ),
                'Title' // TODO: Replace with actual current orderby
            ) ?>
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'sort']
                )
            ) ?>

            <ul>
                <li>

                </li>
            </ul>
        </div>
    </li>

    <li class="c-degree-programs-list__header c-degree-programs-list-table__row c-degree-programs-list-table__row--header">
        <div class="c-degree-programs-list-table__column -thumbnail">
            <?= esc_html_x(
                'Degree program',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>
            <a
                href="<?= esc_url(
                    add_query_arg(
                        [
                            'orderby' => 'name',
                            'order' => $currentOrder === 'asc' ? 'desc' : 'asc',
                        ],
                    )
                ) ?>"
                class="c-degree-programs-list-table__sort-link"
            >
                <?= renderComponent(
                    new Component(
                        Icon::class,
                        ['name' => 'sort']
                    )
                ) ?>
            </a>
        </div>
        <div class="c-degree-programs-list-table__column">
        </div>
        <div class="c-degree-programs-list-table__column">
            <?= esc_html_x(
                'Degree type',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>
            <a
                href="<?= esc_url(
                    add_query_arg(
                        [
                            'orderby' => 'degree',
                            'order' => $currentOrder === 'asc' ? 'desc' : 'asc',
                        ],
                    )
                ) ?>"
                class="c-degree-programs-list-table__sort-link"
            >
                <?= renderComponent(
                    new Component(
                        Icon::class,
                        ['name' => 'sort']
                    )
                ) ?>
            </a>
        </div>
        <div class="c-degree-programs-list-table__column">
            <?= esc_html_x(
                'Start',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>
            <a
                href="<?= esc_url(
                    add_query_arg(
                        [
                            'orderby' => 'start',
                            'order' => $currentOrder === 'asc' ? 'desc' : 'asc',
                        ],
                    )
                ) ?>"
                class="c-degree-programs-list-table__sort-link"
            >
                <?= renderComponent(
                    new Component(
                        Icon::class,
                        ['name' => 'sort']
                    )
                ) ?>
            </a>
        </div>
        <div class="c-degree-programs-list-table__column">
            <?= esc_html_x(
                'Location',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>
            <a
                href="<?= esc_url(
                    add_query_arg(
                        [
                            'orderby' => 'location',
                            'order' => $currentOrder === 'asc' ? 'desc' : 'asc',
                        ],
                    )
                ) ?>"
                class="c-degree-programs-list-table__sort-link"
            >
                <?= renderComponent(
                    new Component(
                        Icon::class,
                        ['name' => 'sort']
                    )
                ) ?>
            </a>
        </div>
        <div class="c-degree-programs-list-table__column">
            <?= esc_html_x(
                'NC',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>
            <a
                href="<?= esc_url(
                    add_query_arg(
                        [
                            'orderby' => 'nc',
                            'order' => $currentOrder === 'asc' ? 'desc' : 'asc',
                        ],
                    )
                ) ?>"
                class="c-degree-programs-list-table__sort-link"
            >
                <?= renderComponent(
                    new Component(
                        Icon::class,
                        ['name' => 'sort']
                    )
                ) ?>
            </a>
        </div>
    </li>

    <?php foreach ($collection as $view) : ?>
        <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?= $renderer->render('search/degree-program-list-item', ['degreeProgram' => $view]) ?>
        <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php endforeach; ?>
</ul>
