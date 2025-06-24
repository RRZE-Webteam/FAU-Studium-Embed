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
                    'content' => wp_kses_post(
                        do_shortcode($view->content()->structure()->description())
                    ),
                ]
            ),
            new Component(
                AccordionItem::class,
                [
                    'title' => $view->content()->specializations()->title(),
                    'content' => wp_kses_post(
                        do_shortcode($view->content()->specializations()->description())
                    ),
                ]
            ),
            new Component(
                AccordionItem::class,
                [
                    'title' => $view->content()->qualitiesAndSkills()->title(),
                    'content' => wp_kses_post(
                        do_shortcode($view->content()->qualitiesAndSkills()->description())
                    ),
                ]
            ),
            new Component(
                AccordionItem::class,
                [
                    'title' => $view->content()->whyShouldStudy()->title(),
                    'content' => wp_kses_post(
                        do_shortcode($view->content()->whyShouldStudy()->description())
                    ),
                ]
            ),
            new Component(
                AccordionItem::class,
                [
                    'title' => $view->content()->careerProspects()->title(),
                    'content' => wp_kses_post(
                        do_shortcode($view->content()->careerProspects()->description())
                    ),
                ]
            ),
            new Component(
                AccordionItem::class,
                [
                    'title' => $view->content()->specialFeatures()->title(),
                    'content' => wp_kses_post(
                        do_shortcode($view->content()->specialFeatures()->description())
                    ),
                ]
            ),
            new Component(
                AccordionItem::class,
                [
                    'title' => _x(
                        'Possible degree program combinations',
                        'frontoffice: single view',
                        'fau-degree-program-output'
                    ),
                ],
                [
                    new Component(
                        Combinations::class,
                        [
                            'title' => _x(
                                'Possible combinations without overlaps',
                                'frontoffice: single view',
                                'fau-degree-program-output'
                            ),
                            'list' => $view->combinations(),
                            'description' => _x(
                                'With these subject combinations, there are generally no overlaps in the timetable.',
                                'frontoffice: single view',
                                'fau-degree-program-output'
                            ),
                        ]
                    ),
                    new Component(
                        Combinations::class,
                        [
                            'title' => _x(
                                'Possible overlaps in the timetable',
                                'frontoffice: single view',
                                'fau-degree-program-output'
                            ),
                            'list' => $view->limitedCombinations(),
                            'description' => _x(
                                'If you combine these subjects, individual courses may overlap in your timetable. For this reason, you can only combine the following subjects with your chosen subject after a consultation. Students are responsible for ensuring that the combination can be studied and that the deadlines set out in Section 11 of the ABMStPOPhil are met. When enrolling, proof of a corresponding consultation with the Central Student Advisory Service or the Phil Study Service Center must be submitted.',
                                'frontoffice: single view',
                                'fau-degree-program-output'
                            ),
                        ]
                    ),
                ]
            ),
            new Component(
                AccordionItem::class,
                [
                    'title' => _x(
                        'Admission requirements, application, and enrollment',
                        'frontoffice: single view',
                        'fau-degree-program-output'
                    ),
                    'content' => $renderer->render(
                        'single-degree-program/admission-requirements',
                        ['view' => $view]
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
