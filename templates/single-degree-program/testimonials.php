<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data;

if (!$view->content()->testimonials()->description()) {
    return;
}

?>

<div class="c-single-degree-program__testimonials l-container">
    <h2><?= esc_html($view->content()->testimonials()->title()) ?></h2>
    <?= wp_kses_post($view->content()->testimonials()->description()) ?>
</div>
