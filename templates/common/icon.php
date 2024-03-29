<?php

declare(strict_types=1);

/**
 * @var array{className: string, iconUrl: string} $data
 */

[
    'className' => $className,
    'iconUrl' => $iconUrl,
] = $data;

?>

<svg class="<?= esc_attr(sprintf('fau-icon %s', $className)) ?>"
     aria-hidden="true"
     focusable="false"
     role="img">
    <use xlink:href="<?= esc_url($iconUrl) ?>"></use>
</svg>
