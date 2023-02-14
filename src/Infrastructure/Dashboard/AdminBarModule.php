<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Dashboard;

use Closure;
use Fau\DegreeProgram\Common\Application\Cache\CacheInvalidator;
use Fau\DegreeProgram\Common\Infrastructure\Dashboard\AdminBar\AdminBarMenu;
use Fau\DegreeProgram\Common\Infrastructure\Dashboard\AdminBar\AdminPostAction;
use Fau\DegreeProgram\Common\Infrastructure\Dashboard\AdminBar\MenuItem;
use Fau\DegreeProgram\Output\Infrastructure\Environment\EnvironmentDetector;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Psr\Container\ContainerInterface;

final class AdminBarModule implements ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function run(ContainerInterface $container): bool
    {
        if ($container->get(EnvironmentDetector::class)->isProvidingWebsite()) {
            return false;
        }

        $cacheInvalidationAction = AdminPostAction::new(
            'invalidate_degree_program_cache',
            Closure::fromCallable(
                [$container->get(CacheInvalidator::class), 'invalidateFully']
            )
        )->withSuccessMessage(
            _x(
                'Degree program cache invalidated.',
                'backoffice: admin notice',
                'fau-degree-program-output'
            )
        )
        ->withErrorMessage(
            _x(
                'Could not invalidate degree program cache.',
                'backoffice: admin notice',
                'fau-degree-program-output'
            )
        );

        $cacheInvalidationAction->register();

        $adminBar = AdminBarMenu::new()
            ->withMenuItem(
                MenuItem::new(
                    'invalidate-cache',
                    _x(
                        'Invalidate cache',
                        'backoffice: admin bar menu item',
                        'fau-degree-program-output'
                    ),
                    $cacheInvalidationAction->buildUrl()
                )
            );

        add_action('admin_bar_menu', [$adminBar, 'render'], 100);

        return true;
    }
}
