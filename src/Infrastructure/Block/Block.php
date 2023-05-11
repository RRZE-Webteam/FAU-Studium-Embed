<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Block;

interface Block
{
    public function name(): string;

    /**
     * @param array<string, mixed> $args
     */
    public function render(array $args = []): string;
}
