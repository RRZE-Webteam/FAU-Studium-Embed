<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-type OutputType = 'tiles' | 'list';
 * @psalm-type DegreeProgramsSearchAttributes = array{
 *     lang: LanguageCodes,
 *     filters: array<string, array<int>>,
 *     output: OutputType,
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
final class DegreeProgramsSearch implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'lang' => MultilingualString::DE,
        'filters' => [],
        'output' => 'tiles',
    ];

    public const OUTPUT_TILES = 'tiles';
    public const OUTPUT_LIST = 'list';
    public const OUTPUT_MODE_QUERY_PARAM = 'output';

    public function __construct(
        private Renderer $renderer,
        private DegreeProgramCollectionRepository $degreeProgramViewRepository,
        private CurrentRequest $currentRequest,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var DegreeProgramsSearchAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);

        $collection = $this->degreeProgramViewRepository->findTranslatedCollection(
            CollectionCriteria::new()
                ->withSearchKeyword($this->currentRequest->searchKeyword()), // Criteria should be updated with the current request
            $attributes['lang'],
        );

        return $this->renderer->render(
            'search/degree-programs-search',
            [
                'collection' => $collection,
                'filters' => $attributes['filters'],
                'output' => $this->sanitizedOutputMode($attributes['output']),
            ]
        );
    }

    /**
     * @param OutputType $mode
     * @return OutputType
     */
    private function sanitizedOutputMode(string $mode): string
    {
        $outputMode = (string) filter_input(
            INPUT_GET,
            self::OUTPUT_MODE_QUERY_PARAM,
            FILTER_SANITIZE_SPECIAL_CHARS
        ) ?: $mode;

        if (!in_array($outputMode, [self::OUTPUT_LIST, self::OUTPUT_TILES], true)) {
            return self::DEFAULT_ATTRIBUTES['output'];
        }

        return $outputMode;
    }
}
