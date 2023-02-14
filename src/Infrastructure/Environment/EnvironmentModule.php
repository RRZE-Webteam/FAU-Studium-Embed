<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Environment;

use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;

final class EnvironmentModule implements ServiceModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            EnvironmentDetector::class => static fn() => new EnvironmentDetector(),
        ];
    }
}
