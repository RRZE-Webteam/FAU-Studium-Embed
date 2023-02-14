<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure;

use Fau\DegreeProgram\Common\Infrastructure\EventDispatcher\WordPressHookEventDispatcher;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherModule implements ServiceModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            EventDispatcherInterface::class => static fn() => new WordPressHookEventDispatcher(),
        ];
    }
}
