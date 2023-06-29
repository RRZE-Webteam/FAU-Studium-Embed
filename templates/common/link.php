<?php

declare(strict_types=1);

/**
 * @var array{url: string, text: string, type: 'dark' | 'light'} $data
 */

[
    'url' => $url,
    'text' => $text,
    'type' => $type,
] = $data;

$classNames = ['c-link'];
$classNames[] = sprintf('c-link--%s', $type);

?>

<a href="<?= esc_url($url) ?>"
   class="<?= esc_attr(implode(' ', $classNames))?>">
    <?= esc_html($text) ?>
</a>
