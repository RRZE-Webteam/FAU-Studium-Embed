<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Filter;

final class Option
{
    public function __construct(
        private string $id,
        private string $label,
        private string|int $value,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function value(): string|int
    {
        return $this->value;
    }
}
