<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;

/**
 * @psalm-var array{
 *     degreeProgram: DegreeProgramViewTranslated,
 * } $data
 * @var array $data
 */

[
    'degreeProgram' => $degreeProgram,
] = $data;

?>

<li class="c-degree-programs-list-item c-degree-programs-list-table__row">
    <div class="c-degree-programs-list-table__column c-degree-programs-list-item__teaser-image">
        <a
            class="c-degree-programs-list-item__link"
            href="<?= esc_url((string) get_permalink($degreeProgram->id())) ?>"
            rel="bookmark"
        >
            <span class="screen-reader-text">
                <?= esc_html(get_the_title($degreeProgram->id())) ?>
            </span>
        </a>
        <img src="<?= esc_url($degreeProgram->teaserImage()->url()) ?>"
            alt="<?= esc_attr($degreeProgram->title()) ?>">
    </div>

    <div class="c-degree-programs-list-table__column c-degree-programs-list-item__title">
        <?= esc_html($degreeProgram->title()) ?>
    </div>

    <div
        class="c-degree-programs-list-table__column c-degree-programs-list-item__degree"
        aria-label="<?= esc_attr_x(
            'Type',
            'frontoffice: degree programs search result list',
            'fau-degree-program-output'
        ) ?>"
    >
        <?= esc_html($degreeProgram->degree()->abbreviation()) ?>
        (<?= esc_html($degreeProgram->degree()->name()) ?>)
    </div>

    <div
        class="c-degree-programs-list-table__column c-degree-programs-list-item__start"
        aria-label="<?= esc_attr_x(
            'Start',
            'frontoffice: degree programs search result list',
            'fau-degree-program-output'
        ) ?>"
    >
        <?= esc_html(implode(',', $degreeProgram->start()->getArrayCopy())) ?>
    </div>

    <div
        class="c-degree-programs-list-table__column c-degree-programs-list-item__location"
        aria-label="<?= esc_attr_x(
            'Location',
            'frontoffice: degree programs search result list',
            'fau-degree-program-output'
        ) ?>"
    >
        <?= esc_html(implode(', ', $degreeProgram->location()->getArrayCopy())) ?>
    </div>

    <div
        class="c-degree-programs-list-table__column c-degree-programs-list-item__semester-fee"
        aria-label="<?= esc_attr_x(
            'NC',
            'frontoffice: degree programs search result list',
            'fau-degree-program-output'
        ) ?>"
    >
        <?php // TODO: Add property to View and output it's value here ?>
    </div>
</li>
