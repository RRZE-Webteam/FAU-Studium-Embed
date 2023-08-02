<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Link;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data;

if (!$view->studentAdvice()->linkUrl() && !$view->subjectSpecificAdvice()->linkUrl()) {
    return '';
}

?>

<div class="c-single-degree-program__more-information l-container">
    <h2>
        <?= esc_html_x(
            'Do you need help or more information?',
            'frontoffice: single view',
            'fau-degree-program-output'
        ) ?>
    </h2>
    <p>
        <?= esc_html_x(
            'Our Student Advice and Career Service (IBZ) is the central point of contact for all questions about studying and starting a degree programme. Our Student Service Centres and subject advisors support you in planning your studies.',
            'frontoffice: single view',
            'fau-degree-program-output'
        ) ?>
    </p>
    <div class="l-information-links">
        <?= renderComponent(
            new Component(
                Link::class,
                [
                    'url' => $view->studentAdvice()->linkUrl(),
                    'text' => $view->studentAdvice()->linkText(),
                ]
            ),
            new Component(
                Link::class,
                [
                    'url' => $view->subjectSpecificAdvice()->linkUrl(),
                    'text' => _x(
                        'Specific Student Advice',
                        'frontoffice: single view',
                        'fau-degree-program-output'
                    ),
                ]
            ),
        ) ?>
    </div>
</div>
