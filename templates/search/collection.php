<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\ReferrerUrlHelper;

/**
 * @psalm-var array{
 *     collection: PaginationAwareCollection<DegreeProgramViewTranslated>,
 *     referrerUrlHelper: ReferrerUrlHelper,
 *     output: 'tiles' | 'list',
 *     currentOrder: array{0: string, 1: 'asc' | 'desc'},
 *     orderbyOptions: array<string, array{label_asc: string, label_desc: string}>,
 * } $data
 * @var array $data
 * @var Renderer $renderer
 */

[
    'collection' => $collection,
    'referrerUrlHelper' => $referrerUrlHelper,
    'output' => $output,
    'currentOrder' => $currentOrder,
    'orderbyOptions' => $orderbyOptions,
] = $data;

$viewModeClass = $output === 'list' ? '-list' : '-tiles';

?>

<ul class="c-degree-programs-collection <?= esc_attr($viewModeClass) ?>">
    <?php /** @var DegreeProgramViewTranslated $view */ ?>
    <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?= $renderer->render('search/collection-table-header', [
        'currentOrder' => $currentOrder,
        'orderbyOptions' => $orderbyOptions,
    ]) ?>
    <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>

    <?php foreach ($collection as $view) : ?>
        <?php /** @var DegreeProgramViewTranslated $view */ ?>
        <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?= $renderer->render('search/item-preview', [
            'degreeProgram' => $view,
            'referrerUrlHelper' => $referrerUrlHelper,
        ]) ?>
        <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php endforeach; ?>
</ul>
