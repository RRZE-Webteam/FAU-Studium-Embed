<?php

declare(strict_types=1);

/**
 * @var array{
 *     isFeaturedVideoDisabled: bool,
 * } $data
 */

[
    'isFeaturedVideoDisabled' => $isFeaturedVideoDisabled,
] = $data;

if ($isFeaturedVideoDisabled) {
    return;
}

?>

<div class="c-single-degree-program__featured-video">
    <?= do_shortcode(
        '[fauvideo url="https://www.fau.tv/webplayer/id/38001" aspectratio="2.40/1"]'
    ) ?>
</div>
