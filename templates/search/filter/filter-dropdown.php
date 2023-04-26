<?php

declare(strict_types=1);

use Fau\DegreeProgram\Output\Application\Filter\FilterView;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;
use Fau\DegreeProgram\Output\Infrastructure\Component\MultichoiceFilter;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     filter: FilterView,
 * } $data
 * @var array $data
 */

[
    'filter' => $filter,
] = $data;

$selectedCount = 0;
$selectedValues = $filter->value();

if ($selectedValues) {
    $selectedCount = is_array($selectedValues) ? count($selectedValues) : 1;
}

?>

<div
    class="c-filter-dropdown"
    role="group"
    aria-labelledby="<?= esc_attr($filter->id()) ?>_label"
    aria-expanded="false"
>
    <div
        class="c-filter-dropdown__header"
        role="button"
        id="<?= esc_attr($filter->id()) ?>_button"
        aria-controls="<?= esc_attr($filter->id()) ?>_content"
    >
        <div class="c-filter-dropdown__label" id="<?= esc_attr($filter->id()) ?>_label">
            <?= esc_html($filter->label()) ?>
        </div>

        <?php if ($selectedCount) : ?>
            <span class="c-filter-dropdown__count">
                <?= esc_html((string) $selectedCount) ?>
            </span>
        <?php endif ?>

        <span class="c-filter-dropdown__arrow">
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'accordion-arrow']
                )
            ) ?>
        </span>
    </div>

    <div class="c-filter-dropdown__items" id="<?= esc_attr($filter->id()) ?>_content">
        <?= renderComponent(
            new Component(
                MultichoiceFilter::class,
                [
                    'filter' => $filter,
                ]
            )
        ) ?>
    </div>
</div>
