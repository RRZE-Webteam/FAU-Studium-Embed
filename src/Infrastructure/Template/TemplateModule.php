<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
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
            SeoFrameworkIntegration::class => static fn(ContainerInterface $container) => new SeoFrameworkIntegration(
                $container->get(DegreeProgramViewRepository::class),
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

        $seoFrameworkIntegration = $container->get(SeoFrameworkIntegration::class);
        add_filter(
            'the_seo_framework_title_from_generation',
            [
                $seoFrameworkIntegration,
                'filterGeneratedTitle',
            ]
        );
        add_filter(
            'the_seo_framework_generated_description',
            [
                $seoFrameworkIntegration,
                'filterGeneratedDescription',
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
