<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Editor;

use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Inpsyde\Modularity\Package;
use Psr\Container\ContainerInterface;

class EditorModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            TinymceButtonsModifier::class => static fn() => new TinymceButtonsModifier(),
            TinymcePluginsRegistrar::class => static fn(ContainerInterface $container) => new TinymcePluginsRegistrar(
                $container->get(Package::PROPERTIES),
            ),
            TinymceScriptDataInjector::class => static fn(ContainerInterface $container) => new TinymceScriptDataInjector(
                $container->get(DegreeProgramCollectionRepository::class)
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action('admin_print_footer_scripts', [
            $container->get(TinymceScriptDataInjector::class),
            'inject',
        ]);

        add_filter('mce_buttons', [$container->get(TinymceButtonsModifier::class), 'modify']);
        add_filter(
            'mce_external_plugins',
            [$container->get(TinymcePluginsRegistrar::class), 'register']
        );

        return true;
    }
}
