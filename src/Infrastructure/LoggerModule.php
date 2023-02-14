<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure;

use Fau\DegreeProgram\Common\Infrastructure\Logger\WordPressLogger;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Log\LoggerInterface;

final class LoggerModule implements ServiceModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            LoggerInterface::class => static fn() => WordPressLogger::new('fau.degree-program-output'),
        ];
    }
}
