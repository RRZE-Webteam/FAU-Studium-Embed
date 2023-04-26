<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Filter;

use Fau\DegreeProgram\Common\Application\Filter\FilterFactory;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TaxonomiesList;
use Fau\DegreeProgram\Output\Infrastructure\Repository\WordPressTermRepository;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

final class FilterModule implements ServiceModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            FilterFactory:: class => static fn () => new FilterFactory(),
            FilterViewFactory::class => static fn (ContainerInterface $container) => new FilterViewFactory(
                $container->get(TaxonomiesList::class),
                $container->get(WordPressTermRepository::class),
            ),
        ];
    }
}
