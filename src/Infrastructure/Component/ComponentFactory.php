<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

final class ComponentFactory
{
    /**
     * @var array<class-string<RenderableComponent>, RenderableComponent>
     */
    private array $components = [];
    public function __construct(RenderableComponent ...$components)
    {
        foreach ($components as $component) {
            $this->components[$component::class] = $component;
        }
    }

    public function makeComponent(string $id): RenderableComponent|null
    {
        return $this->components[$id] ?? null;
    }
}
