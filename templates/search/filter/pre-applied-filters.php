<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @psalm-var array{
 *     preAppliedFilters: array{name: string, value: string}[],
 * } $data
 * @var array $data
 * @var Renderer $renderer
 */

[
    'preAppliedFilters' => $preAppliedFilters,
] = $data;

?>

<?php foreach ($preAppliedFilters as $filter) : ?>
    <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?= $renderer->render(
        'search/filter/hidden-item',
        $filter
    ) ?>
<?php endforeach;
