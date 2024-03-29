<?php

declare(strict_types=1);

use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     url: string,
 *     selected: boolean,
 *     label: string,
 *     icon: string,
 *     mode: 'tiles' | 'list',
 * }
 * @var array $data
 */

[
    'url' => $url,
    'selected' => $selected,
    'label' => $label,
    'icon' => $icon,
    'mode' => $mode,
] = $data;

?>

<button
    type="button"
    class="search-filter__output-mode-option <?= esc_attr($selected ? '-active' : '') ?>"
    data-mode="<?= esc_attr($mode) ?>"
    role="menuitem"
>
    <span class="screen-reader-text">
        <?= esc_html($label) ?>
    </span>
    <?= renderComponent(
        new Component(
            Icon::class,
            ['name' => $icon]
        )
    ) ?>
</button>
