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

<div class="c-single-degree-program__entry-text l-container">
    <?= wp_kses_post($view->entryText()) ?>
</div>
