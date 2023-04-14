<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @psalm-type AccordionAttributes = array{
 *     innerComponents: array<Component>,
 * }
 *
 * The content must be escaped because it is outputted directly.
 */
class Accordion implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'innerComponents' => [],
    ];

    public function __construct(
        private Renderer $renderer,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var AccordionAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);

        $attributes['innerComponents'] = array_filter(
            $attributes['innerComponents'],
            static fn(Component $component) => $component->component() === AccordionItem::class
        );

        return $this->renderer->render(
            'common/accordion',
            $attributes
        );
    }
}
