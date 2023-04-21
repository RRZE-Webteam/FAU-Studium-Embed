<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

use Fau\DegreeProgram\Output\Infrastructure\Repository\PostsRepository;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;

class RewriteModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            ModifyRequestArgs::class => static fn(ContainerInterface $container) => new ModifyRequestArgs(
                $container->get(PostsRepository::class)
            ),
            InjectLanguageQueryVariable::class => static fn() => new InjectLanguageQueryVariable(),
            CurrentRequest::class => static fn() => new CurrentRequest(),
            ReferrerUrlHelper::class => static fn(ContainerInterface $container) => new ReferrerUrlHelper(
                $container->get(CurrentRequest::class),
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        add_filter(
            'query_vars',
            [$container->get(InjectLanguageQueryVariable::class), 'inject']
        );

        add_filter(
            'request',
            [$container->get(ModifyRequestArgs::class), 'modify']
        );

        return true;
    }
}
