<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

final class HiddenDegreeProgramElements
{
    private const SEARCH_FORM_ELEMENT = 'search';
    private const TITLE_ELEMENT = 'heading';
    private array $hiddenElements = [];

    public function hideElements(array $hiddenElements): HiddenDegreeProgramElements
    {
        $this->hiddenElements = $this->sanitizeHiddenElements($hiddenElements);

        return $this;
    }

    public function isSearchFormVisible(): bool
    {
        return !in_array(self::SEARCH_FORM_ELEMENT, $this->hiddenElements, true);
    }

    public function isTitleVisible(): bool
    {
        return !in_array(self::TITLE_ELEMENT, $this->hiddenElements, true);
    }

    public function allowedElementsToBeHidden(): array
    {
        return [
            self::SEARCH_FORM_ELEMENT,
            self::TITLE_ELEMENT,
        ];
    }

    private function sanitizeHiddenElements(array $hiddenElements): array
    {
        $allowedElements = $this->allowedElementsToBeHidden();

        return array_filter(
            $hiddenElements,
            static fn (string $hiddenElement): bool => in_array(
                $hiddenElement,
                $allowedElements,
                true
            )
        );
    }
}
