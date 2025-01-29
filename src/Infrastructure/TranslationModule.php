<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure;

use Inpsyde\Modularity\Module\ExecutableModule;
use Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use Psr\Container\ContainerInterface;

final class TranslationModule implements ExecutableModule
{
    use ModuleClassNameIdTrait;

    public function run(ContainerInterface $container): bool
    {
        add_action('init', static function (): void {
            load_plugin_textdomain(
                'fau-degree-program-output',
                false,
                plugin_basename(dirname(__FILE__, 3)) . '/languages'
            );

            // Bail if text domain is already loaded by the management plugin.
            if (is_textdomain_loaded('fau-degree-program-common')) {
                return;
            }

            load_plugin_textdomain(
                'fau-degree-program-common',
                false,
                sprintf(
                    '%s/vendor/rrze/fau-studium-common/languages',
                    plugin_basename(dirname(__FILE__, 3))
                )
            );
        });

        return true;
    }
}
