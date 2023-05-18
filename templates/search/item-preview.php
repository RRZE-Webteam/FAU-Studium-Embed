<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\ReferrerUrlHelper;

/**
 * @var array{
 *     degreeProgram: DegreeProgramViewTranslated,
 *     referrerUrlHelper: ReferrerUrlHelper,
 * } $data
 */

[
    'degreeProgram' => $degreeProgram,
    'referrerUrlHelper' => $referrerUrlHelper,
] = $data;

$link = $referrerUrlHelper->addReferrerArgs($degreeProgram->link());

?>

<li class="c-degree-program-preview">
    <div class="c-degree-program-preview__teaser-image">
        <a
            class="c-degree-program-preview__link"
            href="<?= esc_url($link) ?>"
            rel="bookmark"
        >
            <span class="screen-reader-text">
                <?= esc_html(get_the_title($degreeProgram->id())) ?>
            </span>
        </a>
        <?= wp_kses($degreeProgram->teaserImage()->rendered(), [
            'img' => [
                'width' => true,
                'height' => true,
                'src' => true,
                'class' => true,
                'alt' => true,
                'decoding' => true,
                'loading' => true,
                'srcset' => true,
                'sizes' => true,
            ],
        ]) ?>
    </div>

    <div class="c-degree-program-preview__title">
        <?= esc_html($degreeProgram->title()) ?>
        (<?= esc_html($degreeProgram->degree()->abbreviation()) ?>)

        <div class="c-degree-program-preview__subtitle">
            <?= esc_html($degreeProgram->subtitle()) ?>
        </div>
    </div>

    <div
        class="c-degree-program-preview__degree"
        aria-label="<?= esc_attr_x(
            'Type',
            'frontoffice: degree programs search result list',
            'fau-degree-program-output'
        ) ?>"
    >
        <?= esc_html($degreeProgram->degree()->name()) ?>
    </div>

    <div
        class="c-degree-program-preview__start"
        aria-label="<?= esc_attr_x(
            'Start',
            'frontoffice: degree programs search result list',
            'fau-degree-program-output'
        ) ?>"
    >
        <?= esc_html(implode(',', $degreeProgram->start()->getArrayCopy())) ?>
    </div>

    <div
        class="c-degree-program-preview__location"
        aria-label="<?= esc_attr_x(
            'Location',
            'frontoffice: degree programs search result list',
            'fau-degree-program-output'
        ) ?>"
    >
        <?= esc_html(implode(', ', $degreeProgram->location()->getArrayCopy())) ?>
    </div>

    <div
        class="c-degree-program-preview__admission-requirement"
        aria-label="<?= esc_attr_x(
            'NC',
            'frontoffice: degree programs search result list',
            'fau-degree-program-output'
        ) ?>"
    >
        <?= esc_html((string) $degreeProgram->admissionRequirementLink()?->name()) ?>
    </div>
</li>
