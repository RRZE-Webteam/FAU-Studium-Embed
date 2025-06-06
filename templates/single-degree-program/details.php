<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramDetail;
use Fau\DegreeProgram\Output\Infrastructure\Component\Link;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data;

$details = renderComponent(
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'degree',
            'term' => _x(
                'Degree',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => $view->degree()->name(),
        ]
    ),
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'standard-duration',
            'term' => _x(
                'Duration of studies in semester',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => $view->standardDuration(),
        ]
    ),
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'start',
            'term' => _x(
                'Start of degree program',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => implode(', ', $view->start()->getArrayCopy()),
        ]
    ),
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'location',
            'term' => _x(
                'Study location',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => implode(', ', $view->location()->getArrayCopy()),
        ]
    ),
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'number-of-students',
            'term' => _x(
                'Number of students',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => $view->numberOfStudents()->name(),
        ]
    ),
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'subject-groups',
            'term' => _x(
                'Subject group',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => implode(', ', $view->subjectGroups()->getArrayCopy()),
        ]
    ),
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'attributes',
            'term' => _x(
                'Special ways to study',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => implode(', ', $view->attributes()->getArrayCopy()),
        ]
    ),
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'teaching-language',
            'term' => _x(
                'Teaching language',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => $view->teachingLanguage(),
        ]
    ),
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'faculty',
            'term' => _x(
                'Admission Requirements',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => $view->admissionRequirementLink()?->name(),
        ]
    ),
    new Component(
        DegreeProgramDetail::class,
        [
            'icon' => 'keywords',
            'term' => _x(
                'Keywords',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'description' => implode(', ', $view->keywords()->getArrayCopy()),
        ]
    ),
);

$applicationLinks = renderComponent(
    new Component(
        Link::class,
        [
            'url' => $view->applyNowLink()->linkUrl(),
            'text' => $view->applyNowLink()->linkText(),
            'type' => Link::DARK,
        ]
    ),
    new Component(
        Link::class,
        [
            'url' => $view->admissionRequirementLink()?->linkUrl() ?? '',
            'text' => $view->admissionRequirementLink()?->linkText() ?? '',
        ]
    ),
    new Component(
        Link::class,
        [
            'url' => $view->notesForInternationalApplicants()->linkUrl(),
            'text' =>  $view->notesForInternationalApplicants()->linkText(),
        ]
    )
);

if (!$details && !$applicationLinks) {
    return;
}

$rootClassNames = ['l-details-wrapper'];
if ($details) {
    $rootClassNames[] = 'l-details-wrapper--has-details';
}

if ($applicationLinks) {
    $rootClassNames[] = 'l-details-wrapper--has-application-links';
}
?>

<div class="<?= esc_attr(implode(' ', $rootClassNames)) ?>">
    <?php if ($details) : ?>
        <div class="l-details">
            <dl class="c-details">
                <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?= $details ?>
            </dl>
        </div>
    <?php endif ?>
    <?php if ($applicationLinks) : ?>
        <div class="l-application-links">
            <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?= $applicationLinks ?>
        </div>
    <?php endif ?>
</div>
