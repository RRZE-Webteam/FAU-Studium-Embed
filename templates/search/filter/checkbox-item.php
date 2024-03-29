<?php

declare(strict_types=1);

/**
 * @psalm-var array{
 *     filterId: string,
 *     label: string,
 *     value: int,
 *     isSelected: bool,
 * } $data
 * @var array $data
 */

[
    'filterId' => $filterId,
    'label' => $label,
    'value' => $value,
    'isSelected' => $isSelected,
] = $data;

?>

<label class="c-filter-checkbox">
    <input
        type="checkbox"
        name="<?= esc_attr($filterId) ?>[]"
        value="<?= esc_attr((string) $value) ?>"
        <?= checked($isSelected) ?>
    />

    <span class="c-filter-checkbox__label">
        <?= esc_html($label) ?>
    </span>
</label>
