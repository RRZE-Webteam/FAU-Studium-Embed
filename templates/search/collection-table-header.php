<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     currentOrder: array<string, 'asc' | 'desc'>,
 *     orderByOptions: array<string, array{label_asc: string, label_desc: string}>,
 * } $data
 * @var array $data
 */

[
    'currentOrder' => $currentOrder,
    'orderByOptions' => $orderByOptions,
] = $data;

$orderBy = count($currentOrder) === 1 ? key($currentOrder) : null;
$order = count($currentOrder) === 1 ? current($currentOrder) : null;

?>

<li class="c-degree-programs-collection__header -mobile">
    <div class="c-sort-selector">
        <select>
            <?php foreach ($orderByOptions as $key => $labels) : ?>
                <?php foreach (['asc', 'desc'] as $orderValue) : ?>
                    <option
                        value="<?= esc_url(add_query_arg([
                            CurrentRequest::ORDER_BY_QUERY_PARAM => $key,
                            CurrentRequest::ORDER_QUERY_PARAM => $orderValue,
                        ])) ?>"
                        <?php selected($key === $orderBy && $order === $orderValue) ?>
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
    <a class="c-degree-programs-collection__header-item -thumbnail"
       role="button"
       href="<?= esc_url(
           add_query_arg(
               [
                   CurrentRequest::ORDER_BY_QUERY_PARAM => DegreeProgram::TITLE,
                   CurrentRequest::ORDER_QUERY_PARAM => $order === 'asc' ? 'desc' : 'asc',
               ],
           )
       ) ?>">
        <span class="screen-reader-text">
            <?= esc_html_x(
                'Sort by',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>
        </span>
        <?= esc_html_x(
            'Degree program',
            'frontoffice: degree programs search result list',
            'fau-degree-program-output'
        ) ?>
        <span class="c-degree-programs-collection__sort-icon">
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'sort']
                )
            ) ?>
        </span>
    </a>
    <div class="c-degree-programs-collection__header-item -title">
    </div>
    <a class="c-degree-programs-collection__header-item -degree"
       role="button"
       href="<?= esc_url(
           add_query_arg(
               [
                   CurrentRequest::ORDER_BY_QUERY_PARAM => DegreeProgram::DEGREE,
                   CurrentRequest::ORDER_QUERY_PARAM => $order === 'asc' ? 'desc' : 'asc',
               ],
           )
       ) ?>"
    >
        <span class="screen-reader-text">
            <?= esc_html_x(
                'Sort by',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>
        </span>
        <?= esc_html_x(
            'Degree type',
            'frontoffice: degree programs search result list',
            'fau-degree-program-output'
        ) ?>
        <span class="c-degree-programs-collection__sort-icon">
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'sort']
                )
            ) ?>
        </span>
    </a>
    <a class="c-degree-programs-collection__header-item -start"
       role="button"
       href="<?= esc_url(
           add_query_arg(
               [
                   CurrentRequest::ORDER_BY_QUERY_PARAM => DegreeProgram::START,
                   CurrentRequest::ORDER_QUERY_PARAM => $order === 'asc' ? 'desc' : 'asc',
               ],
           )
       ) ?>"
    >
        <span class="screen-reader-text">
            <?= esc_html_x(
                'Sort by',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>
        </span>
        <?= esc_html_x(
            'Start',
            'frontoffice: degree programs search result list',
            'fau-degree-program-output'
        ) ?>
        <span class="c-degree-programs-collection__sort-icon">
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'sort']
                )
            ) ?>
        </span>
    </a>
    <a class="c-degree-programs-collection__header-item -location"
       role="button"
       href="<?= esc_url(
           add_query_arg(
               [
                   CurrentRequest::ORDER_BY_QUERY_PARAM => DegreeProgram::LOCATION,
                   CurrentRequest::ORDER_QUERY_PARAM => $order === 'asc' ? 'desc' : 'asc',
               ],
           )
       ) ?>"
    >
        <span class="screen-reader-text">
            <?= esc_html_x(
                'Sort by',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>
        </span>
        <?= esc_html_x(
            'Location',
            'frontoffice: degree programs search result list',
            'fau-degree-program-output'
        ) ?>
        <span class="c-degree-programs-collection__sort-icon">
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'sort']
                )
            ) ?>
        </span>
    </a>
    <a class="c-degree-programs-collection__header-item -admission-requirement"
       role="button"
       href="<?= esc_url(
           add_query_arg(
               [
                   CurrentRequest::ORDER_BY_QUERY_PARAM => DegreeProgram::ADMISSION_REQUIREMENTS,
                   CurrentRequest::ORDER_QUERY_PARAM => $order === 'asc' ? 'desc' : 'asc',
               ],
           )
       ) ?>"
    >
        <span class="screen-reader-text">
            <?= esc_html_x(
                'Sort by',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>
        </span>
        <?= esc_html_x(
            'NC',
            'frontoffice: degree programs search result list',
            'fau-degree-program-output'
        ) ?>
        <span class="c-degree-programs-collection__sort-icon">
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'sort']
                )
            ) ?>
    </a>
</li>
