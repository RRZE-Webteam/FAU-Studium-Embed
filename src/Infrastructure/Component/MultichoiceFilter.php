<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Application\Filter\FilterView;

/**
 * @psalm-type FilterAttributes = array{
 *     filter: FilterView,
 * }
 */
class MultichoiceFilter implements RenderableComponent
{
    public function __construct(
        private Renderer $renderer,
    ) {
    }

    public function render(array $attributes = []): string
    {
        /** @var FilterAttributes $attributes */
        $filter = $attributes['filter'];

        /** @var array<int> $selectedValues **/
        $selectedValues = is_array($filter->value()) ? $filter->value() : [];

        return $this->renderer->render(
            'search/filter/multi-choice-filter',
            array_merge(
                [
                    'filter' => $attributes['filter'],
                    'selectedValues' => $selectedValues,
                ],
                $filter->templateData(),
            ),
        );
    }
}
