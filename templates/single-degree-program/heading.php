<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data;

if (!$view->title() && !$view->subtitle()) {
    return;
}

?>

<div class="l-heading">
    <div class="l-container">
        <?php if ($view->title()) : ?>
            <h1 class="c-single-degree-program__title">
                <?= esc_html($view->title()) ?>
                (<?= esc_html($view->degree()->abbreviation()) ?>)
            </h1>
        <?php endif; ?>
        <?php if ($view->subtitle()) : ?>
            <div class="c-single-degree-program__subtitle">
                <?= esc_html($view->subtitle()) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
