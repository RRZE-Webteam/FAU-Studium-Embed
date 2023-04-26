<?php

declare(strict_types=1);

use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     filterId: string,
 *     label: string,
 *     value: int,
 *     isSelected: bool,
 *     type: 'checkbox' | 'radio',
 * } $data
 * @var array $data
 */

[
    'filterId' => $filterId,
    'label' => $label,
    'value' => $value,
    'isSelected' => $isSelected,
    'type' => $fieldType,
] = $data;

?>


<label class="c-filter-checkbox">
    <input
        type="<?= esc_attr($fieldType) ?>"
        name="<?= esc_attr($filterId) ?>"
        value="<?= esc_attr((string) $value) ?>"
        tabindex="-1"
        <?= checked($isSelected) ?>
    />

    <div class="c-filter-checkbox__inner">
        <span class="c-filter-checkbox__check">
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'check']
                )
            ) ?>
        </span>
        <span class="c-filter-checkbox__label">
            <?= esc_html($label) ?>
        </span>
    </div>
</label>
