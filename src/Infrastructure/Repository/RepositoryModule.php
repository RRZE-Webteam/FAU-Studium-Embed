<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Application\Cache\CacheKeyGenerator;
use Fau\DegreeProgram\Common\Application\Repository\CachedDegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TaxonomiesList;
use Fau\DegreeProgram\Common\Infrastructure\Repository\WordPressDatabaseDegreeProgramCollectionRepository;
use Fau\DegreeProgram\Output\Infrastructure\ApiClient\ApiClient;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

class RepositoryModule implements ServiceModule
{
    use ModuleClassNameIdTrait;

    public const VIEW_REPOSITORY_UNCACHED = 'view_repository_uncached';
    public const COLLECTION_REPOSITORY_UNCACHED = 'collection_repository_uncached';

    public function services(): array
    {
        return [
            PostsRepository::class => static fn() => new PostsRepository(),
            self::VIEW_REPOSITORY_UNCACHED => static fn(ContainerInterface $container) => new WordPressApiDegreeProgramViewRepository(
                $container->get(ApiClient::class)
            ),
            DegreeProgramViewRepository::class => static fn(ContainerInterface $container) => new CachedDegreeProgramViewRepository(
                $container->get(self::VIEW_REPOSITORY_UNCACHED),
                $container->get(CacheKeyGenerator::class),
                $container->get(CacheInterface::class),
            ),
            WordPressDatabaseDegreeProgramCollectionRepository::class => static fn(ContainerInterface $container) => new WordPressDatabaseDegreeProgramCollectionRepository(
                $container->get(DegreeProgramViewRepository::class),
                $container->get(TaxonomiesList::class),
            ),
            self::COLLECTION_REPOSITORY_UNCACHED => static fn(ContainerInterface $container) => new WordPressApiDegreeProgramViewRepository(
                $container->get(ApiClient::class)
            ),
            DegreeProgramCollectionRepository::class => static fn(ContainerInterface $container) => new CachedApiCollectionRepository(
                $container->get(self::COLLECTION_REPOSITORY_UNCACHED),
                $container->get(WordPressDatabaseDegreeProgramCollectionRepository::class),
            ),
        ];
    }
}
