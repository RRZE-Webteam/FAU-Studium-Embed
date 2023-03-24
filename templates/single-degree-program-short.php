<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data

?>

<div class="c-single-degree-program c-single-degree-program--short">
    <a href="<?= esc_url($view->url()) ?>">
        <?= esc_html($view->title()) ?>
    </a>
</div>
