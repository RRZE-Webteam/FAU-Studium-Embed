<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure;

use Fau\DegreeProgram\Common\Application\Cache\CacheKeyGenerator;
use Fau\DegreeProgram\Common\Application\Cache\CacheWarmer;
use Fau\DegreeProgram\Common\Infrastructure\Cli\DegreeProgramCacheCommand;
use Fau\DegreeProgram\Common\Infrastructure\Logger\WordPressCliLogger;
use Fau\DegreeProgram\Output\Infrastructure\Environment\EnvironmentDetector;
use Fau\DegreeProgram\Output\Infrastructure\Repository\RepositoryModule;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use WP_CLI;

final class CliModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public const CLI_CACHE_WARMER = 'cli_cache_warmer';
    public const CLI_LOGGER = 'cli_logger';

    public function services(): array
    {
        return [
            self::CLI_LOGGER => static fn(ContainerInterface $container) => new WordPressCliLogger(
                $container->get(LoggerInterface::class),
            ),
            self::CLI_CACHE_WARMER => static fn(ContainerInterface $container) => new CacheWarmer(
                $container->get(CacheKeyGenerator::class),
                $container->get(CacheInterface::class),
                $container->get(RepositoryModule::COLLECTION_REPOSITORY_UNCACHED),
                $container->get(EventDispatcherInterface::class),
                $container->get(self::CLI_LOGGER),
            ),
            DegreeProgramCacheCommand::class => static fn(ContainerInterface $container) => new DegreeProgramCacheCommand(
                $container->get(self::CLI_CACHE_WARMER),
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        if ($container->get(EnvironmentDetector::class)->isProvidingWebsite()) {
            return false;
        }

        add_action('cli_init', static function () use ($container): void {
            WP_CLI::add_command(
                'fau cache',
                $container->get(DegreeProgramCacheCommand::class)
            );
        });

        return true;
    }
}
