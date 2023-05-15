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

?>

<div class="c-single-degree-program__more-information l-container">
    <h2><?= esc_html_x(
        'Do you need help or more information?',
        'frontoffice: single view',
        'fau-degree-program-output'
    ) ?></h2>
    <p>Unsere Studienberatung ist die zentrale Anlaufstelle für
        alle Fragen rund ums Studium und den Studieneinstieg.
        Unsere Studien-Service-Center und Studienfachberaterinnen unterstützen
        Sie bei der Planung Ihres Studiums.</p>
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
