<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @var array{
 *     degreeProgram: DegreeProgramViewTranslated,
 * } $data
 * @var Renderer $renderer
 */

[
    'degreeProgram' => $degreeProgram,
] = $data;

$titleId = sprintf('degree-program-title-%d', $degreeProgram->id());
?>

<li class="c-degree-program-preview">
    <div class="c-degree-program-preview__teaser-image">
        <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?= $renderer->render(
            'common/image',
            [
                'html' => $degreeProgram->teaserImage()->rendered(),
            ]
        ) ?: renderComponent(
            new Component(
                Icon::class,
                [
                    'name' => 'degree',
                ]
            )
        ) ?>
        <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>

    <div class="c-degree-program-preview__title">
        <a
            class="c-degree-program-preview__link"
            href="<?= esc_url($degreeProgram->link()) ?>"
            rel="bookmark"
            aria-labelledby="<?= esc_attr($titleId) ?>"
        ></a>

        <div id="<?= esc_attr($titleId) ?>">
            <?= esc_html($degreeProgram->title()) ?>
            (<abbr title="<?= esc_attr($degreeProgram->degree()->name()) ?>"><?=
                esc_html($degreeProgram->degree()->abbreviation())
            ?></abbr>)

            <div class="c-degree-program-preview__subtitle">
                <?= esc_html($degreeProgram->subtitle()) ?>
            </div>
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

    <div class="c-degree-program-preview__language-certificates">
        <span class="c-degree-program-preview__label">
            <?= esc_html_x(
                'German language skills for international students',
                'frontoffice: degree programs search result list',
                'fau-degree-program-output'
            ) ?>:
        </span>
        <?= esc_html($degreeProgram->germanLanguageSkillsForInternationalStudents()->linkText()) ?>
    </div>
</li>
