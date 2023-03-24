<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

interface RenderableComponent
{
    /**
     * @psalm-param array<string, mixed> $attributes
     */
    public function render(array $attributes = []): string;
}
