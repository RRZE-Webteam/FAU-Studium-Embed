<?php

declare(strict_types=1);

use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @var array{url: string, text: string, type: 'dark' | 'light', icon: string} $data
 */

[
    'url' => $url,
    'text' => $text,
    'type' => $type,
    'icon' => $icon,
] = $data;

$classNames = ['c-link'];
$classNames[] = sprintf('c-link--%s', $type);

?>

<a href="<?= esc_url($url) ?>"
   class="<?= esc_attr(implode(' ', $classNames))?>">
    <?php if ($icon) : ?>
        <?= renderComponent(
            new Component(
                Icon::class,
                [
                'name' => $icon,
                ]
            )
        ) ?>
    <?php endif ?>
    <?= esc_html($text) ?>
</a>
