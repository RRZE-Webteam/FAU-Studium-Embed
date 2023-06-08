<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data;

if (!$view->content()->about()->description()) {
    return;
}

?>

<div class="c-single-degree-program__about h-post-content l-container">
    <h2><?= esc_html($view->content()->about()->title()) ?></h2>
    <?= wp_kses_post($view->content()->about()->description()) ?>
</div>
