<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramCard;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     collection: PaginationAwareCollection<DegreeProgramViewTranslated>,
 * } $data
 * @var array $data
 * @var Renderer $renderer
 */

[
    'collection' => $collection,
] = $data;

?>

<ul class="c-degree-programs-grid">
    <?php foreach ($collection as $view) : ?>
        <?php /** @var DegreeProgramViewTranslated $view */ ?>
        <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?= $renderer->render('search/degree-program-card', ['degreeProgram' => $view]) ?>
        <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php endforeach; ?>
</ul>
