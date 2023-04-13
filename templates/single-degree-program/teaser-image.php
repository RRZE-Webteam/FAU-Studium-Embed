<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data;

if (!$view->teaserImage()->rendered()) {
    return;
}

?>

<div class="c-single-degree-program__teaser-image">
    <?= wp_kses($view->teaserImage()->rendered(), [
        'img' => [
            'width' => true,
            'height' => true,
            'src' => true,
            'class' => true,
            'alt' => true,
            'decoding' => true,
            'loading' => true,
            'srcset' => true,
            'sizes' => true,
        ],
    ]) ?>
</div>
