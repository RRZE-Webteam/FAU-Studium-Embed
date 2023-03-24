<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Shortcode;

use Fau\DegreeProgram\Output\Infrastructure\Component\ComponentFactory;
use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Inpsyde\Modularity\Module\ServiceModule;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class ShortcodeModule implements ServiceModule, ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function services(): array
    {
        return [
            ShortcodeAttributesNormalizer::class => static fn() => new ShortcodeAttributesNormalizer(),
            DegreeProgramShortcode::class => static fn(ContainerInterface $container) => new DegreeProgramShortcode(
                $container->get(ComponentFactory::class),
                $container->get(ShortcodeAttributesNormalizer::class),
                $container->get(LoggerInterface::class),
            ),
        ];
    }

    public function run(ContainerInterface $container): bool
    {
        $degreeProgramShortcode = $container->get(DegreeProgramShortcode::class);
        add_action('init', static function () use ($degreeProgramShortcode) {
            add_shortcode(
                DegreeProgramShortcode::KEY,
                [$degreeProgramShortcode, 'render']
            );
        });

        return true;
    }
}
