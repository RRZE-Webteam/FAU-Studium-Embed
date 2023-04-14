<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data;

if (!$view->videos()->count()) {
    return;
}

?>

<div class="c-single-degree-program__video">
    <?php foreach ($view->videos() as $url) : ?>
    <div class="c-video">
        <?= do_shortcode(
            sprintf('[fauvideo url="%s"]', esc_url($url))
        ) ?>
    </div>
    <?php endforeach ?>
</div>
