<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     searchQuery: string,
 *     name: string,
 * } $data
 * @var array $data
 * @var Renderer $renderer
 */

[
    'searchQuery' => $searchQuery,
    'name' => $name,
] = $data;

?>

<div class="c-degree-programs-searchform">
    <div class="c-degree-programs-searchform__inner">
        <?= renderComponent(
            new Component(
                Icon::class,
                ['name' => 'search', 'className' => 'c-degree-programs-searchform__search-icon']
            )
        ) ?>

        <input
            type="search"
            name="<?= esc_attr($name) ?>"
            value="<?= esc_attr($searchQuery) ?>"
            class="c-degree-programs-searchform__input"
            placeholder="<?= esc_attr_x(
                'Please enter search term...',
                'frontoffice: degree programs search form',
                'fau-degree-program-output'
            ) ?>"
        />
        <button class="c-degree-programs-searchform__submit" type="submit">
            <span class="label">
                <?= esc_html_x(
                    'Search',
                    'frontoffice: degree programs search form',
                    'fau-degree-program-output'
                ) ?>
            </span>
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'search']
                )
            ) ?>
        </button>
    </div>
    <div class="c-degree-programs-searchform__options">
        <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?= $renderer->render(
            'search/filter/checkbox-item',
            [
                'filterId' => 'extended',
                'label' => esc_html_x(
                    'Also search in text',
                    'frontoffice: degree programs search form',
                    'fau-degree-program-output'
                ),
                'value' => 'enable',
                'isSelected' => false,
            ]
        ) ?>
        <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>
</div>
