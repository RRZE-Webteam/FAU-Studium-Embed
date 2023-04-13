<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

class SearchForm implements RenderableComponent
{
    public function __construct(
        private Renderer $renderer,
    ) {
    }

    public function render(array $attributes = []): string
    {
        $searchQuery = get_search_query();

        return $this->renderer->render(
            'search/degree-programs-searchform',
            [
                'searchQuery' => $searchQuery,
            ]
        );
    }
}
