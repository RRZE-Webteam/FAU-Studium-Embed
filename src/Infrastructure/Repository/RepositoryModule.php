<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Repository;

use Fau\DegreeProgram\Output\Infrastructure\ApiClient\ApiClient;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

class RepositoryModule implements ServiceModule
{
    use ModuleClassNameIdTrait;

    public const REST_API_VIEW_REPOSITORY = 'remote_api_view_repository';

    public function services(): array
    {
        return [
            PostsRepository::class => static fn() => new PostsRepository(),
            self::REST_API_VIEW_REPOSITORY => static fn(ContainerInterface $container) => new WordpressApiDegreeProgramViewRepository(
                $container->get(ApiClient::class)
            ),
        ];
    }
}
