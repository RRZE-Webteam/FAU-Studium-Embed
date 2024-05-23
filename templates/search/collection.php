<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @psalm-var array{
 *     collection: PaginationAwareCollection<DegreeProgramViewTranslated>,
 *     output: 'tiles' | 'list',
 *     currentOrder: array<string, 'asc' | 'desc'>,
 *     orderByOptions: array<string, array{label_asc: string, label_desc: string}>,
 *     activeFilterNames: array<string>,
 * } $data
 * @var array $data
 * @var Renderer $renderer
 */

[
    'collection' => $collection,
    'output' => $output,
    'currentOrder' => $currentOrder,
    'orderByOptions' => $orderByOptions,
    'activeFilterNames' => $activeFilterNames,
] = $data;

$viewModeClass = $output === 'list' ? '-list' : '-tiles';

?>

<ul
    class="c-degree-programs-collection <?= esc_attr($viewModeClass) ?>"
    data-active-filters="<?= esc_attr(implode(',', $activeFilterNames)) ?>"
    role="region"
    aria-labelledby="degree-programs-search-title"
    aria-live="polite"
>
    <?php /** @var DegreeProgramViewTranslated $view */ ?>
    <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?= $renderer->render('search/collection-table-header', [
        'currentOrder' => $currentOrder,
        'orderByOptions' => $orderByOptions,
    ]) ?>
    <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>

    <?php foreach ($collection as $view) : ?>
        <?php /** @var DegreeProgramViewTranslated $view */ ?>
        <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?= $renderer->render('search/item-preview', [
            'degreeProgram' => $view,
        ]) ?>
        <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php endforeach; ?>
</ul>

<?php if ($collection->totalItems() === 0) : ?>
    <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?= $renderer->render('search/no-results') ?>
    <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php endif ?>
