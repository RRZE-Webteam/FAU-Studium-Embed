<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\StructuredData;

use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramsSearch;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use Fau\DegreeProgram\Output\Infrastructure\Shortcode\ShortcodeAttributesNormalizer;
use Fau\DegreeProgram\Output\Infrastructure\Shortcode\ShortcodeAttributesParser;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

final class StructuredDataModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            ScriptBuilder::class => static fn() => new ScriptBuilder(),
            ShortcodeAttributesParser::class => static fn() => new ShortcodeAttributesParser(),
            SingleViewStructuredDataFilter::class => static fn(ContainerInterface $container) => new SingleViewStructuredDataFilter(
                $container->get(DegreeProgramViewRepository::class),
                $container->get(CurrentRequest::class),
                $container->get(ScriptBuilder::class),
            ),
            OverviewStructuredDataFilter::class => static fn(ContainerInterface $container) => new OverviewStructuredDataFilter(
                $container->get(ShortcodeAttributesParser::class),
                $container->get(ShortcodeAttributesNormalizer::class),
                $container->get(DegreeProgramsSearch::class),
                $container->get(ScriptBuilder::class),
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_filter(
            'the_seo_framework_ldjson_scripts',
            [
                $container->get(SingleViewStructuredDataFilter::class),
                'outputStructuredData',
            ],
            10,
            2
        );

        add_filter(
            'the_seo_framework_ldjson_scripts',
            [
                $container->get(OverviewStructuredDataFilter::class),
                'outputStructuredData',
            ],
            10,
            2
        );

        return true;
    }
}
