<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @psalm-type AccordionItemAttributes = array{
 *     title: string,
 *     content: string,
 *     innerComponents: array<Component>,
 * }
 *
 * The content must be escaped because it is outputted directly.
 */
class AccordionItem implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'title' => '',
        'content' => '',
    ];

    public function __construct(
        private Renderer $renderer,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var AccordionItemAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);
        $attributes['content'] = trim($attributes['content']);

        if (
            empty($attributes['title'])
            || (empty($attributes['content']) && empty($attributes['innerComponents']))
        ) {
            return '';
        }

        return $this->renderer->render(
            'common/accordion-item',
            $attributes
        );
    }
}
