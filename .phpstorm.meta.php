<?php

namespace PHPSTORM_META {
    override(\Psr\Container\ContainerInterface::get(0),
        map([
            \Inpsyde\Modularity\Package::PROPERTIES
                => \Inpsyde\Modularity\Properties\PluginProperties::class,
            \Fau\DegreeProgram\Common\Infrastructure\CliModule::CLI_LOGGER
                => \Psr\Log\LoggerInterface::class,
            \Fau\DegreeProgram\Common\Infrastructure\CliModule::CLI_CACHE_WARMER
                => \Fau\DegreeProgram\Common\Application\Cache\CacheWarmer::class,
            \Fau\DegreeProgram\Output\Infrastructure\Repository\RepositoryModule::VIEW_REPOSITORY_UNCACHED
                => \Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository::class,
            \Fau\DegreeProgram\Output\Infrastructure\Repository\RepositoryModule::COLLECTION_REPOSITORY_UNCACHED
                => \Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository::class,
            \Fau\DegreeProgram\Output\Infrastructure\CliModule::CLI_LOGGER
                => \Psr\Log\LoggerInterface::class,
            \Fau\DegreeProgram\Output\Infrastructure\CliModule::CLI_CACHE_WARMER
                => \Fau\DegreeProgram\Common\Application\Cache\CacheWarmer::class,
            '' => '@',
        ]));
}
