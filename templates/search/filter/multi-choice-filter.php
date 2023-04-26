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

<div class="c-filter-control c-filter-control--multi-choice">
    <ul class="c-filter-control__options c-accordion" id="<?= esc_attr($filter->id()) ?>_content">
        <?php foreach ($options as $option) : ?>
            <li class="c-filter-control__option-item c-accordion-item">
                <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?= $renderer->render(
                    'search/filter/filter-choice',
                    [
                        'filterId' => $filter->id(),
                        'label' => $option->label(),
                        'value' => $option->value(),
                        'isSelected' => in_array(
                            $option->value(),
                            $selectedValues,
                            true
                        ),
                        'type' => 'checkbox',
                    ]
                ) ?>
                <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
