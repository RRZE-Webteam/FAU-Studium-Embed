<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Search;

use Fau\DegreeProgram\Common\Application\Event\CacheWarmed;
use Fau\DegreeProgram\Common\Application\Queue\MessageBus;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Infrastructure\Repository\IdGenerator;
use Fau\DegreeProgram\Output\Infrastructure\Environment\EnvironmentDetector;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class SearchModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            SearchableContentUpdater::class => static fn(ContainerInterface $container) => new SearchableContentUpdater(
                $container->get(DegreeProgramCollectionRepository::class),
                $container->get(LoggerInterface::class),
            ),
            UpdateSearchableContentMessageHandler::class => static fn(ContainerInterface $container) => new UpdateSearchableContentMessageHandler(
                $container->get(SearchableContentUpdater::class),
            ),
            FilterableTermsUpdater::class => static fn(ContainerInterface $container) => new FilterableTermsUpdater(
                $container->get(DegreeProgramCollectionRepository::class),
                $container->get(IdGenerator::class),
                $container->get(LoggerInterface::class),
            ),
            FilterablePostsMetaUpdater::class => static fn(ContainerInterface $container) => new FilterablePostsMetaUpdater(
                $container->get(DegreeProgramCollectionRepository::class),
                $container->get(LoggerInterface::class),
            ),
            UpdateFilterableTermsMessageHandler::class => static fn(ContainerInterface $container) => new UpdateFilterableTermsMessageHandler(
                $container->get(FilterableTermsUpdater::class),
            ),
            UpdateFilterablePostsMetaMessageHandler::class => static fn(ContainerInterface $container) => new UpdateFilterablePostsMetaMessageHandler(
                $container->get(FilterablePostsMetaUpdater::class),
            ),
            WhenCacheWarmed::class => static fn(ContainerInterface $container) => new WhenCacheWarmed(
                $container->get(MessageBus::class),
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action(
            CacheWarmed::NAME,
            [
                $container->get(WhenCacheWarmed::class),
                'scheduleSearchableContentUpdating',
            ]
        );

        if ($container->get(EnvironmentDetector::class)->isProvidingWebsite()) {
            return true;
        }

        add_action(
            CacheWarmed::NAME,
            [
                $container->get(WhenCacheWarmed::class),
                'scheduleFilterableTermsUpdating',
            ]
        );

        return true;
    }
}
