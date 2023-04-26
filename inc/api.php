<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output;

use Fau\DegreeProgram\Output\Infrastructure\Component\Component;

function renderComponent(?Component ...$components): string
{
    $result = '';

    foreach ($components as $component) {
        if (! $component instanceof Component) {
            continue;
        }

        $attributes = Component::generateAttributes($component);

        $result .= plugin()->container()->get($component->component())->render($attributes);
    }

    return $result;
}
