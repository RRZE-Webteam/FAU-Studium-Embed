<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @psalm-type LinksAttributes = array{
 *     title: string,
 *     links: list<array{link_text: string, link_url: string}>,
 * }
 * Links is array with link text as key and link URL as value.
 */
final class Links implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'title' => '',
        'links' => [],
    ];

    public function __construct(
        private Renderer $renderer,
    ) {
    }

    public function render(array $attributes = []): string
    {
        /** @var LinksAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);

        $attributes['links'] = array_filter(
            $attributes['links'],
            static fn(array $link) => $link['link_text'] && $link['link_url']
        );

        if (empty($attributes['links'])) {
            return '';
        }

        return $this->renderer->render(
            'common/links',
            $attributes
        );
    }
}
