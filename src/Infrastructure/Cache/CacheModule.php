<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Cache;

use Fau\DegreeProgram\Common\Application\Cache\CacheInvalidator;
use Fau\DegreeProgram\Common\Application\Cache\CacheKeyGenerator;
use Fau\DegreeProgram\Common\Application\Cache\CacheWarmer;
use Fau\DegreeProgram\Common\Application\Event\CacheInvalidated;
use Fau\DegreeProgram\Common\Infrastructure\Cache\PostMetaDegreeProgramCache;
use Fau\DegreeProgram\Output\Infrastructure\Environment\EnvironmentDetector;
use Fau\DegreeProgram\Output\Infrastructure\Repository\RepositoryModule;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

final class CacheModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            CacheKeyGenerator::class => static fn() => new CacheKeyGenerator(),
            CacheInterface::class => static fn(ContainerInterface $container) => new PostDegreeProgramCache(
                new PostMetaDegreeProgramCache($container->get(CacheKeyGenerator::class)),
                $container->get(CacheKeyGenerator::class),
            ),
            CacheInvalidator::class => static fn(ContainerInterface $container) => new CacheInvalidator(
                $container->get(CacheKeyGenerator::class),
                $container->get(CacheInterface::class),
                $container->get(EventDispatcherInterface::class),
                $container->get(LoggerInterface::class),
            ),
            CacheWarmer::class => static fn(ContainerInterface $container) => new CacheWarmer(
                $container->get(CacheKeyGenerator::class),
                $container->get(CacheInterface::class),
                $container->get(RepositoryModule::COLLECTION_REPOSITORY_UNCACHED),
                $container->get(EventDispatcherInterface::class),
                $container->get(LoggerInterface::class),
            ),
            WhenCacheInvalidated::class => static fn() => new WhenCacheInvalidated(),
            WhenWarmingToBeStarted::class => static fn(ContainerInterface $container) => new WhenWarmingToBeStarted(
                $container->get(CacheWarmer::class),
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        if ($container->get(EnvironmentDetector::class)->isProvidingWebsite()) {
            return false;
        }

        add_action(
            CacheInvalidated::NAME,
            [
                $container->get(WhenCacheInvalidated::class),
                'scheduleWarming',
            ]
        );

        add_action(
            WhenWarmingToBeStarted::HOOK,
            [
                $container->get(WhenWarmingToBeStarted::class),
                'warm',
            ],
            10,
            2
        );

        return true;
    }
}
