<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Assets;

use Inpsyde\Assets\AssetManager;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Inpsyde\Modularity\Package;
use Psr\Container\ContainerInterface;

class AssetsModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            AssetsLoader::class => static fn (ContainerInterface $container) => new AssetsLoader(
                $container->get(Package::PROPERTIES),
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action(
            AssetManager::ACTION_SETUP,
            [$container->get(AssetsLoader::class), 'load']
        );

        return true;
    }
}
