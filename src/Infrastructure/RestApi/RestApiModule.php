<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\RestApi;

use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Infrastructure\RestApi\TranslatedDegreeProgramController;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

class RestApiModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            TranslatedDegreeProgramController::class => static fn(ContainerInterface $container) => new TranslatedDegreeProgramController(
                $container->get(DegreeProgramViewRepository::class),
                $container->get(DegreeProgramCollectionRepository::class),
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        $translatedDegreeProgramController = $container->get(TranslatedDegreeProgramController::class);

        add_action(
            'rest_api_init',
            static function () use ($translatedDegreeProgramController) {
                $translatedDegreeProgramController->register_routes();
            }
        );

        return true;
    }
}
