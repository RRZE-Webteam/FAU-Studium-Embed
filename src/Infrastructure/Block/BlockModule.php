<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Block;

use Fau\DegreeProgram\Output\Infrastructure\Component\SingleDegreeProgram;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Inpsyde\Modularity\Package;
use Psr\Container\ContainerInterface;

final class BlockModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function __construct(
        private string $blockDirectory
    ) {
    }

    public function services(): array
    {
        return [
            BlockRegistry::class => fn() => new BlockRegistry(
                $this->blockDirectory,
            ),
            CategoryRegistry::class => static fn() => new CategoryRegistry(),
            SingleDegreeProgramBlock::class => static fn(ContainerInterface $container) => new SingleDegreeProgramBlock(
                $container->get(SingleDegreeProgram::class),
            ),
            AssetsLoader::class => static fn(ContainerInterface $container) => new AssetsLoader(
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

        add_filter(
            'block_categories_all',
            [
                $container->get(CategoryRegistry::class),
                'register',
            ]
        );

        add_action('init', static function () use ($container) {
            $container->get(BlockRegistry::class)->register(
                $container->get(SingleDegreeProgramBlock::class),
            );
        });

        return true;
    }
}
