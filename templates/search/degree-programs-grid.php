<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\ReferrerUrlHelper;

/**
 * @var array{
 *     collection: PaginationAwareCollection<DegreeProgramViewTranslated>,
 *     referrerUrlHelper: ReferrerUrlHelper,
 * } $data
 * @var array $data
 * @var Renderer $renderer
 */

[
    'collection' => $collection,
    'referrerUrlHelper' => $referrerUrlHelper,
] = $data;

?>

<ul class="c-degree-programs-grid">
    <?php foreach ($collection as $view) : ?>
        <?php /** @var DegreeProgramViewTranslated $view */ ?>
        <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?= $renderer->render('search/degree-program-card', [
            'degreeProgram' => $view,
            'referrerUrlHelper' => $referrerUrlHelper,
        ]) ?>
        <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php endforeach; ?>
</ul>
