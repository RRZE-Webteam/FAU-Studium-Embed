<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

class Component
{
    /**
     * @var class-string
     */
    private string $component;
    private array $attributes;
    private array $innerComponents;

    /**
     * @param class-string $component
     * @param array $attributes
     * @param array $innerComponents
     */
    public function __construct(
        string $component,
        array $attributes = [],
        array $innerComponents = []
    ) {

        $this->component = $component;
        $this->attributes = $attributes;
        $this->innerComponents = $innerComponents;
    }

    /**
     * @return class-string
     */
    public function component(): string
    {
        return $this->component;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function innerComponents(): array
    {
        return $this->innerComponents;
    }

    private static function generateClassName(self $component): string
    {
        $attributes = $component->attributes();
        $classNames = explode(' ', (string) ($attributes['className'] ?? ''));
        if (!empty($attributes['style'])) {
            $classNames[] = "is-style-{$attributes['style']}";
        }

        if (!empty($attributes['align'])) {
            $classNames[] = "align-{$attributes['align']}";
        }

        return implode(' ', $classNames);
    }

    public static function generateAttributes(self $component): array
    {
        return array_merge(
            $component->attributes(),
            [
                'innerComponents' => $component->innerComponents(),
                'className' => self::generateClassName($component),
            ],
        );
    }
}
