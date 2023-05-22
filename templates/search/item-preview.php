<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\ReferrerUrlHelper;

/**
 * @var array{
 *     degreeProgram: DegreeProgramViewTranslated,
 *     referrerUrlHelper: ReferrerUrlHelper,
 * } $data
 * @var Renderer $renderer
 */

[
    'degreeProgram' => $degreeProgram,
    'referrerUrlHelper' => $referrerUrlHelper,
] = $data;

$link = $referrerUrlHelper->addReferrerArgs($degreeProgram->link());
$titleId = sprintf('degree-program-title-%d', $degreeProgram->id());

?>

<li class="c-degree-program-preview">
    <div class="c-degree-program-preview__teaser-image">
        <a
            class="c-degree-program-preview__link"
            href="<?= esc_url($link) ?>"
            rel="bookmark"
            aria-labelledby="<?= esc_attr($titleId) ?>"
        >
        </a>
        <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?= $renderer->render(
            'common/image',
            [
                'html' => $degreeProgram->teaserImage()->rendered(),
            ]
        ) ?>
        <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>

    <div class="c-degree-program-preview__title"
         id="<?= esc_attr($titleId) ?>"
    >
        <?= esc_html($degreeProgram->title()) ?>
        (<abbr title="<?= esc_attr($degreeProgram->degree()->name()) ?>"><?=
            esc_html($degreeProgram->degree()->abbreviation())
        ?></abbr>)

        <div class="c-degree-program-preview__subtitle">
            <?= esc_html($degreeProgram->subtitle()) ?>
        </div>
    </div>

    <div class="c-degree-program-preview__degree">
        <span class="c-degree-program-preview__label">
            <?= esc_html_x(
                'Type',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>:
        </span>
        <?= esc_html($degreeProgram->degree()->name()) ?>
    </div>

    <div class="c-degree-program-preview__start">
        <span class="c-degree-program-preview__label">
            <?= esc_html_x(
                'Start',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>:
        </span>
        <?= esc_html(implode(', ', $degreeProgram->start()->getArrayCopy())) ?>
    </div>

    <div class="c-degree-program-preview__location">
        <span class="c-degree-program-preview__label">
            <?= esc_html_x(
                'Location',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>:
        </span>
        <?= esc_html(implode(', ', $degreeProgram->location()->getArrayCopy())) ?>
    </div>

    <div class="c-degree-program-preview__admission-requirement">
        <span class="c-degree-program-preview__label">
            <?= esc_html_x(
                'NC',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>:
        </span>
        <?= esc_html((string) $degreeProgram->admissionRequirementLink()?->name()) ?>
    </div>
</li>
