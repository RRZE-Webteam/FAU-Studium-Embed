<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 * @var Renderer $renderer
 */

[
    'view' => $view,
] = $data

?>

<div class="c-single-degree-program">
    <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?= $renderer->render('single-degree-program/teaser-image', ['view' => $view]) ?>
    <?= $renderer->render('single-degree-program/heading', ['view' => $view]) ?>
    <?= $renderer->render('single-degree-program/entry-text', ['view' => $view]) ?>
    <?= $renderer->render('single-degree-program/details', ['view' => $view]) ?>
    <?= $renderer->render('single-degree-program/about', ['view' => $view]) ?>
    <?= $renderer->render('single-degree-program/video', ['view' => $view]) ?>
    <?= $renderer->render('single-degree-program/accordion', ['view' => $view]) ?>
    <?= $renderer->render('single-degree-program/special-features', ['view' => $view]) ?>
    <?= $renderer->render('single-degree-program/featured-video', ['view' => $view]) ?>
    <?= $renderer->render('single-degree-program/testimonials', ['view' => $view]) ?>
    <?= $renderer->render('single-degree-program/more-information', ['view' => $view]) ?>
    <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
