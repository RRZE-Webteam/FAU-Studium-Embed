<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Block;

use Fau\DegreeProgram\Output\Infrastructure\Component\SingleDegreeProgram;

final class SingleDegreeProgramBlock implements Block
{
    public const NAME = 'fau/degree-program';

    public function __construct(
        private SingleDegreeProgram $component
    ) {
    }

    public function name(): string
    {
        return self::NAME;
    }

    public function render(array $args = []): string
    {
        $args['className'] = 'is-block';

        return $this->component->render($args);
    }
}
