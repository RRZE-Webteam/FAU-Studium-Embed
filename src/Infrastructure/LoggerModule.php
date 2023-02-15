<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure;

use Fau\DegreeProgram\Common\Infrastructure\Logger\WordPressCliLogger;
use Fau\DegreeProgram\Common\Infrastructure\Logger\WordPressLogger;
use Inpsyde\Modularity\Module\FactoryModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class LoggerModule implements ServiceModule, FactoryModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            WordPressLogger::class => static fn() => WordPressLogger::new('fau.degree-program-output'),
            WordPressCliLogger::class => static fn(ContainerInterface $container) => new WordPressCliLogger(
                $container->get(WordPressLogger::class),
            ),
        ];
    }

    public function factories(): array
    {
        return [
            LoggerInterface::class => static fn(ContainerInterface $container) => defined('WP_CLI') && WP_CLI
                ? $container->get(WordPressCliLogger::class)
                : $container->get(WordPressLogger::class),
        ];
    }
}
