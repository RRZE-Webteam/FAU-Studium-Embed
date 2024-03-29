<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Links;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data;

$organizational = renderComponent(
    new Component(
        Links::class,
        [
            'title' => _x(
                'Organizational',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'links' => [
                $view->startOfSemester()->asArray(),
                $view->semesterDates()->asArray(),
                $view->semesterFee()->asArray(),
                $view->serviceCenters()->asArray(),
                $view->abroadOpportunities()->asArray(),
            ],
        ]
    )
);

$downloads = renderComponent(
    new Component(
        Links::class,
        [
            'title' => _x(
                'Downloads',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'links' => [
                [
                    'link_text' => _x(
                        'Info brochure degree program',
                        'frontoffice: single view',
                        'fau-degree-program-output'
                    ),
                    'link_url' => $view->infoBrochure(),
                ],
                [
                    'link_text' => _x(
                        'Module handbook',
                        'frontoffice: single view',
                        'fau-degree-program-output'
                    ),
                    'link_url' => $view->moduleHandbook(),
                ],
                [
                    'link_text' => _x(
                        'Degree program and examination regulations',
                        'frontoffice: single view',
                        'fau-degree-program-output'
                    ),
                    'link_url' =>  $view->examinationRegulations(),
                ],
            ],
        ]
    )
);

$additionalInformation = renderComponent(
    new Component(
        Links::class,
        [
            'title' => _x(
                'Additional Information',
                'frontoffice: single view',
                'fau-degree-program-output'
            ),
            'links' => [
                [
                    'link_text' => _x(
                        'Website of the degree program',
                        'frontoffice: single view',
                        'fau-degree-program-output'
                    ),
                    'link_url' => $view->url(),
                ],
                $view->examinationsOffice()->asArray(),
                [
                    'link_text' => _x(
                        'Department/Institute website',
                        'frontoffice: single view',
                        'fau-degree-program-output'
                    ),
                    'link_url' =>  $view->department(),
                ],
                ...array_values($view->faculty()->asArray()),
                $view->studentInitiatives()->asArray(),
            ],
        ]
    )
);

if (
    !$organizational
    && !$downloads
    && !$additionalInformation
) {
    return;
}

?>

<div class="c-single-degree-program__links">
    <div class="l-container">
    <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?= $organizational ?>
    <?= $downloads ?>
    <?= $additionalInformation ?>
    <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>
</div>
