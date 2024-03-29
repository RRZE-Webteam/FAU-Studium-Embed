<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\ReferrerUrlHelper;

/**
 * @var array{
 *     degreeProgram: DegreeProgramViewTranslated,
 *     referrerUrlHelper: ReferrerUrlHelper,
 * } $data
 */

[
    'degreeProgram' => $degreeProgram,
    'referrerUrlHelper' => $referrerUrlHelper,
] = $data;

$link = $referrerUrlHelper->addReferrerArgs($degreeProgram->link());

?>

<li class="degree-program-preview">
    <a class="degree-program-preview__link"
        href="<?= esc_url($link) ?>">
        <img class="degree-program-preview__teaser-image"
                src="<?= esc_url($degreeProgram->teaserImage()->url()) ?>"
                alt="<?= esc_attr($degreeProgram->title()) ?>">
        <div class="degree-program-preview__title">
            <?= esc_html($degreeProgram->title()) ?>

            <div class="degree-program-preview__title-expanded">
                <?= esc_html($degreeProgram->subtitle()) ?>
            </div>
        </div>
    </a>
</li>
