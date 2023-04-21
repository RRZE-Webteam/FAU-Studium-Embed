<?php

declare(strict_types=1);

/**
 * @psalm-var array{title: string, links: list<array{link_text: string, link_url: string}>} $data
 * @var array $data
 */

[
    'title' => $title,
    'links' => $links,
] = $data;

?>

<div class="c-links">
    <h3 class="c-links__title"><?= esc_html($title) ?></h3>
    <ul>
        <?php foreach ($links as ['link_text' => $linkText, 'link_url' => $linkUrl]) : ?>
            <li>
                <a href="<?= esc_url($linkUrl) ?>">
                    <?= esc_html($linkText) ?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</div>
