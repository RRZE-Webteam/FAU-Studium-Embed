<?php

declare(strict_types=1);

use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @var array{title: string, content: string, innerComponents: array<Component>} $data
 */

$innerContent = renderComponent(...$data['innerComponents']);

$buttonId = 'id' . wp_generate_uuid4();
$contentId = 'id' . wp_generate_uuid4();

if (empty($data['content']) && empty($innerContent)) {
    return;
}

?>

<li class="c-accordion-item">
    <h2 class="c-accordion-item__header">
        <button class="c-accordion-item__button"
                aria-expanded="false"
                id="<?= esc_attr($buttonId) ?>"
                aria-controls="<?= esc_attr($contentId) ?>"
        >
        <span class="c-accordion-item__title">
            <?= esc_html($data['title']) ?>
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
    </h2>
    <div class="c-accordion-item__content"
         role="region"
         id="<?= esc_attr($contentId) ?>"
         aria-labelledby="<?= esc_attr($buttonId) ?>"
         hidden="hidden"
    >
        <?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?= $data['content'] ?>
        <?= $innerContent ?>
        <?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>
</li>
