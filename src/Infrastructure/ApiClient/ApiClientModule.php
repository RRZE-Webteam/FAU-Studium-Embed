<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\ApiClient;

use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class ApiClientModule implements ServiceModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            ApiClient::class => static fn (ContainerInterface $container) => new ApiClient(
                $container->get(LoggerInterface::class)
            ),
        ];
    }
}
