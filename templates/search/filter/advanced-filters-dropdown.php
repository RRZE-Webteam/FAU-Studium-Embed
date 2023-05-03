<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Application\Filter\FilterView;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;
use Fau\DegreeProgram\Output\Infrastructure\Component\MultichoiceFilter;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     filters: array<FilterView>,
 *     selectedCount: int,
 * } $data
 * @var Renderer $renderer
 * @var array $data
 */

[
    'filters' => $filters,
    'selectedCount' => $selectedCount,
] = $data;

$labelId = 'label_' . wp_generate_uuid4();
$buttonId = 'button_' . wp_generate_uuid4();
$contentId = 'content_' . wp_generate_uuid4();

?>

<div
    class="c-filter-dropdown c-filter-dropdown--advanced-filters fau-dropdown"
    role="group"
    aria-labelledby="<?= esc_attr($labelId) ?>"
    aria-expanded="false"
>
    <div
        class="c-filter-dropdown__header fau-dropdown__toggle"
        role="button"
        id="<?= esc_attr($buttonId) ?>"
        aria-controls="<?= esc_attr($contentId) ?>"
    >
        <div class="c-filter-dropdown__label" id="<?= esc_attr($labelId) ?>">
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'settings']
                )
            ) ?>

            <?= esc_html_x(
                'Advanced filters',
                'frontoffice: degree programs search form',
                'fau-degree-program-output'
            ) ?>
        </div>

        <?php if ($selectedCount) : ?>
            <span class="c-filter-dropdown__count">
                <?= esc_html((string) $selectedCount) ?>
            </span>
        <?php endif ?>
    </div>

    <div
        class="c-filter-dropdown__content fau-dropdown__content"
        id="<?= esc_attr($contentId) ?>"
    >
        <div class="c-advanced-filters">
            <?php foreach ($filters as $filter) : ?>
                <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?= $renderer->render(
                        'search/filter/advanced-filter-item',
                        [
                            'filter' => $filter,
                        ]
                    ) ?>
                <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php endforeach ?>
        </div>
    </div>
</div>
