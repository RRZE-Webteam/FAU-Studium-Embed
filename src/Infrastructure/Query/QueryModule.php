<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Query;

use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

final class QueryModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            WpQueryModifier::class => fn () => new WpQueryModifier(),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_action(
            'pre_get_posts',
            [$container->get(WpQueryModifier::class), 'sortyBySupportedMetaKeys'],
        );

        return true;
    }
}
