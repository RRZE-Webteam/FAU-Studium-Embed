<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramDetail;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data;

$admissionRequirements = renderComponent(
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'degree',
            'term' => _x(
                'Admission requirements (first semester)',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' =>
                $view->admissionRequirements()->bachelorOrTeachingDegree()?->asHtml(),
        ]
    ),
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'degree',
            'term' => _x(
                'Admission requirements (higher semester)',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' =>
                $view->admissionRequirements()->teachingDegreeHigherSemester()?->asHtml(),
        ]
    ),
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'degree',
            'term' => _x(
                'Admission requirements (first semester)',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => $view->admissionRequirements()->master()?->asHtml(),
        ]
    ),
);

$durations = renderComponent(
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'standard-duration',
            'term' => _x(
                'Application deadline winter semester',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => $view->applicationDeadlineWinterSemester(),
        ]
    ),
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'standard-duration',
            'term' => _x(
                'Application deadline summer semester',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => $view->applicationDeadlineSummerSemester(),
        ]
    ),
);

$contentRelatedAdmissionRequirements = renderComponent(
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'degree',
            'term' => _x(
                'Content-related admission requirements',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => wp_kses_post($view->contentRelatedMasterRequirements()),
        ]
    ),
);

$languageSkills = renderComponent(
    new Component(
        DegreeProgramDetail::class,
        [
            'term' => _x(
                'German language skills for international applicants',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => $view->germanLanguageSkillsForInternationalStudents()->asHtml(),
        ]
    ),
    new Component(
        DegreeProgramDetail::class,
        [
            'term' => _x(
                'General language skills',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => $view->languageSkills()
                . $view->languageSkillsHumanitiesFaculty(),
        ]
    ),
);

$detailsAndNotes = renderComponent(
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'attributes',
            'term' => _x(
                'Details and notes',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => $view->detailsAndNotes(),
        ]
    )
);

$degreeProgramFees = renderComponent(
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'area-of-study',
            'term' => _x(
                'Degree Program Fees',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => $view->degreeProgramFees(),
        ]
    )
);

?>

<div class="c-single-degree-program__admission-requirements">
    <?php if ($admissionRequirements) : ?>
        <dl>
            <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?= $admissionRequirements ?>
        </dl>
    <?php endif; ?>

    <?php if ($durations) : ?>
        <dl>
            <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?= $durations ?>
        </dl>
    <?php endif; ?>

    <?php if ($contentRelatedAdmissionRequirements) : ?>
        <dl>
            <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?= $contentRelatedAdmissionRequirements ?>
        </dl>
    <?php endif; ?>

    <?php if ($languageSkills) : ?>
        <div class="c-language-skills">
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'teaching-language']
                )
            ) ?>
            <h3 class="c-language-skills__header">
                <?= esc_html_x(
                    'Language skills',
                    'frontoffice: single view',
                    'fau-degree-program-output'
                ) ?>
            </h3>
            <dl class="c-language-skills__details">
                <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?= $languageSkills ?>
            </dl>
        </div>
    <?php endif; ?>

    <?php if ($detailsAndNotes) : ?>
        <dl>
            <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?= $detailsAndNotes ?>
        </dl>
    <?php endif; ?>

    <?php if ($degreeProgramFees) : ?>
        <dl>
            <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?= $degreeProgramFees ?>
        </dl>
    <?php endif; ?>
</div>
