<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Domain\DegreeProgramSanitizer;

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
    <?= wp_kses(
        $view->content()->about()->description(),
        DegreeProgramSanitizer::ALLOWED_ENTITIES,
    ) ?>
</div>
