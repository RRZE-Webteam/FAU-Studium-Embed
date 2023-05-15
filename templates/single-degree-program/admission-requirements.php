<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramDetail;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data;

?>

<div class="c-single-degree-program__admission-requirements">
    <?= renderComponent(
        new Component(
            DegreeProgramDetail::class,
            [
                'icon' => 'degree',
                'term' => _x(
                    'Admission requirements Master',
                    'frontoffice: single view',
                    'fau-degree-program-output'
                ),
                'description' => $view->admissionRequirements()
                    ->master()
                    ?->asHtml(),
            ]
        ),
        new Component(
            DegreeProgramDetail::class,
            [
                'icon' => 'degree',
                'term' => _x(
                    'Admission requirements Bachelor/Teaching',
                    'frontoffice: single view',
                    'fau-degree-program-output'
                ),
                'description' => $view->admissionRequirements()
                    ->bachelorOrTeachingDegree()
                    ?->asHtml(),
            ]
        ),
        new Component(
            DegreeProgramDetail::class,
            [
                'icon' => 'degree',
                'term' => _x(
                    'Admission requirements for higher semester',
                    'frontoffice: single view',
                    'fau-degree-program-output'
                ),
                'description' => $view->admissionRequirements()
                    ->teachingDegreeHigherSemester()
                    ?->asHtml(),
            ]
        ),
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
        ),
        new Component(
            DegreeProgramDetail::class,
            [
                'icon' => 'teaching-language',
                'term' => _x(
                    'Language skills',
                    'frontoffice: single view',
                    'fau-degree-program-output'
                ),
                'description' => $view->languageSkills(),
            ]
        ),
        new Component(
            DegreeProgramDetail::class,
            [
                'icon' => 'teaching-language',
                'term' => _x(
                    'Language skills for Faculty of Humanities, Social Sciences, and Theology only',
                    'frontoffice: single view',
                    'fau-degree-program-output'
                ),
                'description' => $view->languageSkillsHumanitiesFaculty(),
            ]
        ),
        new Component(
            DegreeProgramDetail::class,
            [
                'icon' => 'teaching-language',
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
                'icon' => 'area-of-study',
                'term' => _x(
                    'Degree Program Fees',
                    'frontoffice: single view',
                    'fau-degree-program-output'
                ),
                'description' => $view->degreeProgramFees(),
            ]
        )
    ) ?>
</div>
