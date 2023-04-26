<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Application\Filter;

use Fau\DegreeProgram\Common\Application\Filter\Filter;

/**
 * @psalm-type FilterViewType = 'multiselect' | 'text'
 */
final class FilterView
{
    public const MULTISELECT = 'multiselect';
    public const TEXT = 'text';

    /**
     * @psalm-param FilterViewType $type
     */
    public function __construct(
        private Filter $filter,
        private string $label,
        private string $type,
        private array $templateData = [],
    ) {
    }

    public function id(): string
    {
        return $this->filter->id();
    }

    public function label(): string
    {
        return $this->label;
    }

    public function templateData(): array
    {
        return $this->templateData;
    }

    public function value(): mixed
    {
        return $this->filter->value();
    }

    /**
     * @psalm-return FilterViewType
     */
    public function type(): string
    {
        return $this->type;
    }
}
