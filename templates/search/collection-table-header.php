<?php

declare(strict_types=1);

use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     currentOrder: array{0: string, 1: 'asc' | 'desc'},
 *     orderbyOptions: array<string, array{label_asc: string, label_desc: string}>,
 * } $data
 * @var array $data
 */

[
    'currentOrder' => $currentOrder,
    'orderbyOptions' => $orderbyOptions,
] = $data;

$orderby = $currentOrder[0];
$order = $currentOrder[1];

?>

<li class="c-degree-programs-collection__header -mobile">
    <div class="c-sort-selector">
        <select>
            <?php foreach ($orderbyOptions as $key => $labels) : ?>
                <?php foreach (['asc', 'desc'] as $orderValue) : ?>
                    <option
                        value="<?= esc_url(add_query_arg([
                            CurrentRequest::ORDERBY_QUERY_PARAM => $key,
                            CurrentRequest::ORDER_QUERY_PARAM => $orderValue,
                        ])) ?>"
                        <?php selected($key === $orderby && $order === $orderValue) ?>
                    >
                        <?= esc_html($labels['label_' . $orderValue]) ?>
                    </option>
                <?php endforeach ?>
            <?php endforeach ?>
        </select>

        <?= renderComponent(
            new Component(
                Icon::class,
                ['name' => 'sort']
            )
        ) ?>
    </div>
</li>

<li class="c-degree-programs-collection__header">
    <div class="c-degree-programs-collection__header-item -thumbnail">
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
                        'order' => $order === 'asc' ? 'desc' : 'asc',
                    ],
                )
            ) ?>"
            class="c-degree-programs-collection__sort-link"
        >
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'sort']
                )
            ) ?>
        </a>
    </div>
    <div class="c-degree-programs-collection__header-item -title">
    </div>
    <div class="c-degree-programs-collection__header-item">
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
                        'order' => $order === 'asc' ? 'desc' : 'asc',
                    ],
                )
            ) ?>"
            class="c-degree-programs-collection__sort-link"
        >
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'sort']
                )
            ) ?>
        </a>
    </div>
    <div class="c-degree-programs-collection__header-item">
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
                        'order' => $order === 'asc' ? 'desc' : 'asc',
                    ],
                )
            ) ?>"
            class="c-degree-programs-collection__sort-link"
        >
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'sort']
                )
            ) ?>
        </a>
    </div>
    <div class="c-degree-programs-collection__header-item">
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
                        'order' => $order === 'asc' ? 'desc' : 'asc',
                    ],
                )
            ) ?>"
            class="c-degree-programs-collection__sort-link"
        >
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'sort']
                )
            ) ?>
        </a>
    </div>
    <div class="c-degree-programs-collection__header-item">
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
                        'order' => $order === 'asc' ? 'desc' : 'asc',
                    ],
                )
            ) ?>"
            class="c-degree-programs-collection__sort-link"
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
