<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data;

if (!$view->entryText()) {
    return;
}

?>

<div class="c-single-degree-program__entry-text h-post-content l-container">
    <?= wp_kses_post(do_shortcode($view->entryText())) ?>
</div>
