<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

final class ExcludeDegreeProgramElements
{
    private const SEARCH_FORM_ELEMENT = 'search';
    private const TITLE_ELEMENT = 'heading';
    private array $excludedElements = [];

    public function excludeElements(array $excludedElements): ExcludeDegreeProgramElements
    {
        $this->excludedElements = $this->sanitizeExcludedElements($excludedElements);

        return $this;
    }

    public function isSearchFormAllowed(): bool
    {
        return !in_array(self::SEARCH_FORM_ELEMENT, $this->excludedElements, true);
    }

    public function isTitleAllowed(): bool
    {
        return !in_array(self::TITLE_ELEMENT, $this->excludedElements, true);
    }

    public function allowedElementsToBeExcluded(): array
    {
        return [
            self::SEARCH_FORM_ELEMENT,
            self::TITLE_ELEMENT,
        ];
    }

    private function sanitizeExcludedElements(array $excludedElements): array
    {
        $allowedElements = $this->allowedElementsToBeExcluded();

        return array_filter(
            $excludedElements,
            static fn (string $excludedElement): bool => in_array(
                $excludedElement,
                $allowedElements,
                true
            )
        );
    }
}
