<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-type DegreeProgramsSearchFormAttributes = array{
 *     lang: LanguageCodes,
 *     filters: array<string, array<int>>,
 *     output: 'list' | 'table',
 * }
 *
 * `filters` keys are filterable taxonomy REST API bases,
 * and the values are an array of pre-selected term IDs.
 * The only edge case is `admission-requirements`,
 * which should be treated as a single taxonomy.
 * @see \Fau\DegreeProgram\Output\Infrastructure\Search\FilterableTermsUpdater::retrieveFilterableProperties
 * Example: array{
 *  teaching-language: array{0: 25, 1: 56},
 *  degree: array<empty, empty>,
 *  admission_requirements : array<empty, empty>
 * }
 */
final class DegreeProgramsSearchForm implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'lang' => MultilingualString::DE,
        'filters' => [],
        'output' => 'tiles',
    ];

    public function __construct(
        private Renderer $renderer,
        private DegreeProgramCollectionRepository $degreeProgramViewRepository,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var DegreeProgramsSearchFormAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);

        $collection = $this->degreeProgramViewRepository->findTranslatedCollection(
            CollectionCriteria::new(), // Criteria should be updated with the current request
            $attributes['lang'],
        );

        return $this->renderer->render(
            'degree-programs-search-form',
            [
                'collection' => $collection,
                'filters' => $attributes['filters'],
                'output' => $attributes['output'],
            ]
        );
    }
}
