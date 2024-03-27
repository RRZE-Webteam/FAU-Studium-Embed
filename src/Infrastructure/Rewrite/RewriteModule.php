<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

use Fau\DegreeProgram\Common\Application\Event\CacheWarmed;
use Fau\DegreeProgram\Common\Application\Queue\MessageBus;
use Fau\DegreeProgram\Output\Infrastructure\Environment\EnvironmentDetector;
use Fau\DegreeProgram\Output\Infrastructure\Repository\PostsRepository;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class RewriteModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            ModifyRequestArgs::class => static fn(ContainerInterface $container) => new ModifyRequestArgs(
                $container->get(PostsRepository::class)
            ),
            CurrentRequest::class => static fn() => new CurrentRequest(),
            FlushRewriteRulesMessageHandler::class => static fn(ContainerInterface $container) => new FlushRewriteRulesMessageHandler(
                $container->get(LoggerInterface::class),
            ),
            WhenCacheWarmed::class => static fn(ContainerInterface $container) => new WhenCacheWarmed(
                $container->get(MessageBus::class),
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_filter(
            'request',
            [$container->get(ModifyRequestArgs::class), 'modify']
        );

        if ($container->get(EnvironmentDetector::class)->isProvidingWebsite()) {
            return true;
        }

        add_action(
            CacheWarmed::NAME,
            [
                $container->get(WhenCacheWarmed::class),
                'scheduleFlushRewriting',
            ]
        );

        return true;
    }
}
