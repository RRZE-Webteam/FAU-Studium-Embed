<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data;

if (!$view->content()->specialFeatures()->description()) {
    return;
}

?>

<div class="c-single-degree-program__special-features l-container">
    <h2><?= esc_html($view->content()->specialFeatures()->title()) ?></h2>
    <?= wp_kses_post($view->content()->specialFeatures()->description()) ?>
</div>
