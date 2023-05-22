<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data;

if (!$view->featuredImage()->rendered()) {
    return;
}

?>

<div class="c-single-degree-program__featured-image">
    <?= str_replace( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        '<img',
        '<img alt=""',
        wp_kses(
            $view->featuredImage()->rendered(),
            [
                'img' => [
                    'width' => true,
                    'height' => true,
                    'src' => true,
                    'class' => true,
                    'decoding' => true,
                    'loading' => true,
                    'srcset' => true,
                    'sizes' => true,
                ],
            ]
        )
    ) ?>
</div>
