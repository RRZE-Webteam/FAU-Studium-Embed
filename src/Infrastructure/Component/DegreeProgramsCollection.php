<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-type DegreeProgramsSearchAttributes = array{
 *     collection: PaginationAwareCollection<DegreeProgramViewTranslated>,
 *     output: 'tiles' | 'list',
 * }
 */
class DegreeProgramsCollection implements RenderableComponent
{
    public function __construct(
        private Renderer $renderer,
    ) {
    }

    public function render(array $attributes = []): string
    {
        /**
         * @var DegreeProgramsSearchAttributes $attributes
         */
        if ($attributes['collection']->totalItems() === 0) {
            return $this->renderer->render('search/no-results');
        }

        $templateName = $attributes['output'] === 'list'
            ? 'search/degree-programs-list'
            : 'search/degree-programs-grid';

        $currentOrder = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        return $this->renderer->render(
            $templateName,
            [
                'collection' => $attributes['collection'],
                'currentOrder' => $currentOrder,
            ]
        );
    }
}