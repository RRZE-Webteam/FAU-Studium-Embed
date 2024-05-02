<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Application\Filter\FilterFactory;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\DirectoryLocator;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\TemplateRenderer;
use Fau\DegreeProgram\Output\Application\ArrayPropertiesAccessor;
use Fau\DegreeProgram\Output\Application\DegreeProgramViewPropertiesFilter;
use Fau\DegreeProgram\Output\Infrastructure\Filter\FilterViewFactory;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use Fau\DegreeProgram\Output\Infrastructure\Template\HiddenDegreeProgramElements;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Inpsyde\Modularity\Package;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class ComponentModule implements ServiceModule
{
    use ModuleClassNameIdTrait;

    /**
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public function services(): array
    {
        return [
            Renderer::class => static fn(ContainerInterface $container) => TemplateRenderer::new(
                DirectoryLocator::new(
                    $container->get(Package::PROPERTIES)->basePath() . '/templates',
                )
            ),
            ArrayPropertiesAccessor::class => static fn() => new ArrayPropertiesAccessor(),
            DegreeProgramViewPropertiesFilter::class => static fn(ContainerInterface $container) => new DegreeProgramViewPropertiesFilter(
                $container->get(ArrayPropertiesAccessor::class),
            ),
            DegreeProgramsSearch::class => static fn(ContainerInterface $container) => new DegreeProgramsSearch(
                $container->get(Renderer::class),
                $container->get(DegreeProgramCollectionRepository::class),
                $container->get(CurrentRequest::class),
                $container->get(FilterViewFactory::class),
                $container->get(FilterFactory::class),
                $container->get(HiddenDegreeProgramElements::class),
            ),
            SingleDegreeProgram::class => static fn(ContainerInterface $container) => new SingleDegreeProgram(
                $container->get(Renderer::class),
                $container->get(DegreeProgramViewRepository::class),
                $container->get(LoggerInterface::class),
                $container->get(DegreeProgramViewPropertiesFilter::class),
                $container->get(CurrentRequest::class),
            ),
            DegreeProgramCombinations::class => static fn(ContainerInterface $container) => new DegreeProgramCombinations(),
            ComponentFactory::class => static fn(ContainerInterface $container) => new ComponentFactory(
                $container->get(DegreeProgramsSearch::class),
                $container->get(SingleDegreeProgram::class),
                $container->get(DegreeProgramCombinations::class),
            ),
            Icon::class => static fn(ContainerInterface $container) => new Icon(
                $container->get(Renderer::class),
                (string) $container->get(Package::PROPERTIES)->baseUrl() . 'assets/sprite.svg',
            ),
            Link::class => static fn(ContainerInterface $container) => new Link(
                $container->get(Renderer::class),
            ),
            Accordion::class => static fn(ContainerInterface $container) => new Accordion(
                $container->get(Renderer::class),
            ),
            AccordionItem::class => static fn(ContainerInterface $container) => new AccordionItem(
                $container->get(Renderer::class),
            ),
            SearchForm::class => static fn(ContainerInterface $container) => new SearchForm(
                $container->get(Renderer::class),
                $container->get(CurrentRequest::class),
            ),
            SearchFilters::class => static fn(ContainerInterface $container) => new SearchFilters(
                $container->get(Renderer::class),
            ),
            DegreeProgramsCollection::class => static fn(ContainerInterface $container) => new DegreeProgramsCollection(
                $container->get(Renderer::class),
                $container->get(CurrentRequest::class),
            ),
            DegreeProgramDetail::class => static fn(ContainerInterface $container) => new DegreeProgramDetail(
                $container->get(Renderer::class),
            ),
            Combinations::class => static fn(ContainerInterface $container) => new Combinations(
                $container->get(Renderer::class),
            ),
            Links::class => static fn(ContainerInterface $container) => new Links(
                $container->get(Renderer::class),
            ),
            MultichoiceFilter::class => static fn(ContainerInterface $container) => new MultichoiceFilter(
                $container->get(Renderer::class),
            ),
            ActiveFilters::class => static fn(ContainerInterface $container) => new ActiveFilters(
                $container->get(Renderer::class),
                $container->get(CurrentRequest::class),
            ),
            AdvancedFilters::class => static fn(ContainerInterface $container) => new AdvancedFilters(
                $container->get(Renderer::class),
            ),
        ];
    }
}
