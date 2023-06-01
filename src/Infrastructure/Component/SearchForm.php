<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;

class SearchForm implements RenderableComponent
{
    public function __construct(
        private Renderer $renderer,
        private CurrentRequest $currentRequest,
    ) {
    }

    public function render(array $attributes = []): string
    {
        return $this->renderer->render(
            'search/text-input-search',
            [
                'searchQuery' => $this->currentRequest->searchKeyword(),
                'queryStrings' => $this->currentRequest->flattenedQueryStrings(),
                'name' => CurrentRequest::SEARCH_QUERY_PARAM,
            ]
        );
    }
}
