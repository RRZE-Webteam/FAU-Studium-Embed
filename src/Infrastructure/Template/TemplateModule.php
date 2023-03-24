<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

use Fau\DegreeProgram\Output\Application\OriginalDegreeProgramViewRepository;
use Fau\DegreeProgram\Output\Infrastructure\Environment\EnvironmentDetector;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

final class TemplateModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
          CanonicalUrlFilter::class => static fn(ContainerInterface $container) => new CanonicalUrlFilter(
              $container->get(OriginalDegreeProgramViewRepository::class),
          ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        if ($container->get(EnvironmentDetector::class)->isProvidingWebsite()) {
            return false;
        }

        add_filter(
            'get_canonical_url',
            [
                $container->get(CanonicalUrlFilter::class),
                'filterCanonicalUrl',
            ],
            10,
            2
        );

        return true;
    }
}
