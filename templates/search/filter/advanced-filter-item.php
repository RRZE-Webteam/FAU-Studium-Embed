<?php

declare(strict_types=1);

use Fau\DegreeProgram\Output\Application\Filter\FilterView;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;
use Fau\DegreeProgram\Output\Infrastructure\Component\MultichoiceFilter;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     filter: FilterView,
 * } $data
 * @var array $data
 */

[
    'filter' => $filter,
] = $data;

$labelId = 'label_' . wp_generate_uuid4();
$buttonId = 'button_' . wp_generate_uuid4();
$contentId = 'content_' . wp_generate_uuid4();

?>

<div class="c-advanced-filter-item c-accordion-item">
    <h3 class="c-advanced-filter-item__heading">
        <button class="c-accordion-item__button"
            aria-expanded="false"
            id="<?= esc_attr($buttonId) ?>"
            aria-controls="<?= esc_attr($contentId) ?>"
        >
            <span class="c-accordion-item__title">
                <?= esc_html($filter->label()) ?>
            </span>

            <?= renderComponent(
                new Component(
                    Icon::class,
                    [
                        'name' => 'accordion-arrow',
                        'className' => 'c-accordion-item__icon',
                    ]
                )
            ) ?>
        </button>
    </h3>

    <div
        class="c-accordion-item__content"
        role="region"
        id="<?= esc_attr($contentId) ?>"
        aria-labelledby="<?= esc_attr($buttonId) ?>"
        hidden="hidden"
    >
        <?= renderComponent(
            new Component(
                MultichoiceFilter::class,
                [
                    'filter' => $filter,
                ]
            )
        ) ?>
    </div>
</div>
