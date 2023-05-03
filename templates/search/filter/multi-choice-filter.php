<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Application\Filter\FilterView;
use Fau\DegreeProgram\Output\Infrastructure\Filter\Option;

/**
 * @psalm-var array{
 *     filter: FilterView,
 *     selectedValues: array<int>,
 *     options: array<Option>,
 * } $data
 * @var array $data
 * @var Renderer $renderer
 */

[
    'filter' => $filter,
    'selectedValues' => $selectedValues,
    'options' => $options,
] = $data;

?>

<ul class="c-filter-choices">
    <?php foreach ($options as $option) : ?>
        <li class="c-filter-choices__item">
            <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?= $renderer->render(
                'search/filter/checkbox-item',
                [
                    'filterId' => $filter->id(),
                    'label' => $option->label(),
                    'value' => $option->value(),
                    'isSelected' => in_array(
                        $option->value(),
                        $selectedValues,
                        true
                    ),
                ]
            ) ?>
            <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </li>
    <?php endforeach ?>
</ul>
