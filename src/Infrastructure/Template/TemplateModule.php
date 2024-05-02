<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

use Fau\DegreeProgram\Output\Application\OriginalDegreeProgramViewRepository;
use Fau\DegreeProgram\Output\Infrastructure\Component\SingleDegreeProgram;
use Fau\DegreeProgram\Output\Infrastructure\Embed\PostDataFilter;
use Fau\DegreeProgram\Output\Infrastructure\Environment\EnvironmentDetector;
use Fau\DegreeProgram\Output\Infrastructure\Repository\CurrentViewRepository;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Inpsyde\WpContext;
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
            ),
            SeoFrameworkIntegration::class => static fn(ContainerInterface $container) => new SeoFrameworkIntegration(
                $container->get(CurrentViewRepository::class),
            ),
            PostDataFilter::class => static fn(ContainerInterface $container) => new PostDataFilter(
                $container->get(CurrentViewRepository::class),
            ),
            ImageSizeRegistrar::class => static fn() => new ImageSizeRegistrar(),
            TitleModifier::class => static fn(ContainerInterface $container) => new TitleModifier(
                $container->get(CurrentViewRepository::class),
            ),
            ExcludeDegreeProgramElements::class => static fn() => new ExcludeDegreeProgramElements(),
        ];
    }

    /**
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public function run(ContainerInterface $container): bool
    {
        self::registerImageSizes(
            $container->get(EnvironmentDetector::class),
            $container->get(ImageSizeRegistrar::class),
        );

        if (!WpContext::determine()->isFrontoffice()) {
            return false;
        }

        add_filter(
            'the_content',
            [
                $container->get(SingleDegreeProgramContentFilter::class),
                'filterContent',
            ]
        );

        add_filter(
            'single_post_title',
            [
                $container->get(TitleModifier::class),
                'modify',
            ],
            10,
            2
        );

        add_filter(
            'the_title',
            [
                $container->get(TitleModifier::class),
                'modify',
            ],
            10,
            2
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
        add_filter(
            'the_seo_framework_supported_post_type',
            [
                $seoFrameworkIntegration,
                'alwaysSupportDegreeProgramPostType',
            ],
            10,
            2
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

    private static function registerImageSizes(
        EnvironmentDetector $detector,
        ImageSizeRegistrar $imageSizeRegistrar
    ): void {

        if (!$detector->isProvidingWebsite()) {
            return;
        }

        add_action(
            'after_setup_theme',
            [
                $imageSizeRegistrar,
                'registerImageSizes',
            ]
        );

        add_filter(
            'fau.degree-program.image-size',
            [
                $imageSizeRegistrar,
                'filterImageSize',
            ],
            10,
            2
        );
    }
}
