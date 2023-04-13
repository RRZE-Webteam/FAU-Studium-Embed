<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

use Fau\DegreeProgram\Output\Application\OriginalDegreeProgramViewRepository;
use Fau\DegreeProgram\Output\Infrastructure\Component\SingleDegreeProgram;
use Fau\DegreeProgram\Output\Infrastructure\Environment\EnvironmentDetector;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
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
                $container->get(CurrentRequest::class),
            ),
            SingleDegreeProgramContentFilter::class => static fn(ContainerInterface $container) => new SingleDegreeProgramContentFilter(
                $container->get(SingleDegreeProgram::class),
                $container->get(CurrentRequest::class),
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {

        add_filter(
            'the_content',
            [
                $container->get(SingleDegreeProgramContentFilter::class),
                'filterContent',
            ]
        );

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
