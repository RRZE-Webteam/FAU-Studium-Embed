<?php

declare(strict_types=1);

/**
 * @psalm-var array{
 *     name: string,
 *     value: string,
 * } $data
 * @var array $data
 */

[
    'name' => $name,
    'value' => $value,
] = $data;

?>

<input
    type="hidden"
    name="<?= esc_attr($name) ?>[]"
    value="<?= esc_attr($value) ?>"
/>
