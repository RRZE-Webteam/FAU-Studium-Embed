<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Cache;

use Fau\DegreeProgram\Common\Application\Cache\CacheInvalidator;
use Fau\DegreeProgram\Common\Application\Cache\CacheKeyGenerator;
use Fau\DegreeProgram\Common\Application\Cache\CacheWarmer;
use Fau\DegreeProgram\Common\Application\Event\CacheInvalidated;
use Fau\DegreeProgram\Common\Application\Queue\MessageBus;
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

    public function __construct(private string $pluginFile)
    {
    }

    public function services(): array
    {
        return [
            CacheKeyGenerator::class => static fn() => new CacheKeyGenerator(),
            CachedDataTransformer::class => static fn() => new CachedDataTransformer(),
            CacheInterface::class => static fn(ContainerInterface $container) => new PostDegreeProgramCache(
                new PostMetaDegreeProgramCache($container->get(CacheKeyGenerator::class)),
                $container->get(CacheKeyGenerator::class),
                $container->get(CachedDataTransformer::class),
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
            WhenCacheInvalidated::class => static fn(ContainerInterface $container) => new WhenCacheInvalidated(
                $container->get(MessageBus::class),
            ),
            WarmCacheMessageHandler::class => static fn(ContainerInterface $container) => new WarmCacheMessageHandler(
                $container->get(CacheWarmer::class),
            ),
            DailyCacheInvalidationRunner::class => static fn(ContainerInterface $container) => new DailyCacheInvalidationRunner(
                $container->get(CacheInvalidator::class),
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

        $cacheInvalidationRunner = $container->get(DailyCacheInvalidationRunner::class);
        add_action(
            'admin_init',
            [
                $cacheInvalidationRunner,
                'scheduleDailyCacheInvalidation',
            ]
        );

        register_deactivation_hook(
            $this->pluginFile,
            [
                $cacheInvalidationRunner,
                'unscheduleDailyCacheInvalidation',
            ]
        );

        add_action(
            DailyCacheInvalidationRunner::DAILY_CACHE_INVALIDATION_HOOK,
            [
                $cacheInvalidationRunner,
                'runDailyCacheInvalidation',
            ]
        );

        return true;
    }
}
