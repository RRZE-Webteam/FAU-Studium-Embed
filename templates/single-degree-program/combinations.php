<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\RelatedDegreeProgram;

/**
 * @var array{
 *     title: string,
 *     type: string,
 *     list: array<RelatedDegreeProgram>,
 *     description: string
 * } $data
 */

[
    'title' => $title,
    'type' => $type,
    'list' => $list,
    'description' => $description,
] = $data;

$classNames = ['c-single-degree-program__combinations'];
if ($type) {
    $classNames[] = sprintf('c-single-degree-program__combinations--%s', $type);
}

?>

<div class="<?= esc_attr(implode(' ', $classNames)) ?>">
    <h3>
        <?= esc_html($title) ?>
    </h3>
    <ul>
        <?php foreach ($list as $relatedDegreeProgram) : ?>
            <li>
                <a href="<?= esc_url($relatedDegreeProgram->url()) ?>">
                    <?= esc_html($relatedDegreeProgram->title()) ?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
    <?php if ($description) : ?>
        <p><?= wp_kses_post(nl2br($description)) ?></p>
    <?php endif ?>
</div>
