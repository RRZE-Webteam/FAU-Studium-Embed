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

$labelId = 'label_' . wp_generate_uuid4();
$buttonId = 'button_' . wp_generate_uuid4();
$contentId = 'content_' . wp_generate_uuid4();

?>

<div
    class="c-filter-dropdown fau-dropdown"
    role="group"
    aria-labelledby="<?= esc_attr($labelId) ?>"
    aria-expanded="false"
>
    <div
        class="c-filter-dropdown__header fau-dropdown__toggle"
        role="button"
        id="<?= esc_attr($buttonId) ?>"
        aria-controls="<?= esc_attr($contentId) ?>"
    >
        <div class="c-filter-dropdown__label" id="<?= esc_attr($labelId) ?>">
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

    <div class="c-filter-dropdown__content fau-dropdown__content" id="<?= esc_attr($contentId) ?>">
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
