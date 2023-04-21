<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Infrastructure\Component\Accordion;
use Fau\DegreeProgram\Output\Infrastructure\Component\AccordionItem;
use Fau\DegreeProgram\Output\Infrastructure\Component\Combinations;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 * @var Renderer $renderer
 */

[
    'view' => $view,
] = $data;

$accordion = renderComponent(
    new Component(
        Accordion::class,
        [],
        [
            new Component(
                AccordionItem::class,
                [
                    'title' => $view->content()->structure()->title(),
                    'content' => wp_kses_post($view->content()->structure()->description()),
                ]
            ),
            new Component(
                AccordionItem::class,
                [
                    'title' => $view->content()->specializations()->title(),
                    'content' => wp_kses_post($view->content()->specializations()->description()),
                ]
            ),
            new Component(
                AccordionItem::class,
                [
                    'title' => $view->content()->qualitiesAndSkills()->title(),
                    'content' => wp_kses_post(
                        $view->content()->qualitiesAndSkills()->description()
                    ),
                ]
            ),
            new Component(
                AccordionItem::class,
                [
                    'title' => $view->content()->whyShouldStudy()->title(),
                    'content' => wp_kses_post($view->content()->whyShouldStudy()->description()),
                ]
            ),
            new Component(
                AccordionItem::class,
                [
                    'title' => $view->content()->careerProspects()->title(),
                    'content' => wp_kses_post($view->content()->careerProspects()->description()),
                ]
            ),
            new Component(
                AccordionItem::class,
                [
                    'title' => _x(
                        'Course combinations & limited course combinations',
                        'frontoffice: single view',
                        'fau-degree-program-output'
                    ),
                ],
                [
                    new Component(
                        Combinations::class,
                        [
                            'title' => _x(
                                'Course combinations',
                                'frontoffice: single view',
                                'fau-degree-program-output'
                            ),
                            'list' => $view->combinations(),
                            'description' => <<<'DESCRIPTION'
Das Lehrangebot dieser Kombination ist so aufeinander abgestimmt,
dass die Fächer in der Regel überschneidungsfrei miteinander kombiniert werden können.
DESCRIPTION,
                        ]
                    ),
                    new Component(
                        Combinations::class,
                        [
                            'title' => _x(
                                'Limited course combinations',
                                'frontoffice: single view',
                                'fau-degree-program-output'
                            ),
                            'list' => $view->limitedCombinations(),
                            'description' => <<<'DESCRIPTION'
Diese Kombination kann nur nach einer diesbezüglichen Studienberatung studiert werden.
Die Überschneidungsfreiheit kann jedoch nicht garantiert werden.
Die Studierenden tragen selbst die Verantwortung für die Studierbarkeit der Kombination
und die Einhaltung der Fristen des § 10.
Der Nachweis einer Studienberatung ist bei der Immatrikulation vorzulegen.
DESCRIPTION,
                        ]
                    ),
                ]
            ),
        ]
    )
);

if (!$accordion) {
    return;
}
?>

<div class="c-single-degree-program__accordion">
    <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?= $accordion ?>
</div>
