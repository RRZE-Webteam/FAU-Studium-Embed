<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Domain\MultilingualString;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 *
 * @psalm-type DegreeProgramCombinationsAttributes = array{
 *     lang: LanguageCodes,
 *     faculty: array<int>,
 *     degree: array<int>,
 * }
 */
final class DegreeProgramCombinations implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'lang' => MultilingualString::DE,
        'faculty' => [],
        'degree' => [],
    ];

    public function render(array $attributes = []): string
    {
        /** @var DegreeProgramCombinationsAttributes $attributes */
        // TODO: Implement render() method.
        return '';
    }
}
