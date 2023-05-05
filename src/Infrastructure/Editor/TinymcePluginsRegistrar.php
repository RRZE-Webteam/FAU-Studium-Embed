<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Editor;

use Inpsyde\Modularity\Properties\Properties;

class TinymcePluginsRegistrar
{
    public function __construct(private Properties $properties)
    {
    }

    /**
     * @wp-hook mce_external_plugins
     * @param array $plugins
     * @return array
     */
    public function register(array $plugins): array
    {
        $pluginScriptUrl = (string) $this->properties->baseUrl() . 'assets/ts/admin.js';
        $plugins['fau_degree_program_output_shortcodes_plugin'] = $pluginScriptUrl;

        return $plugins;
    }
}
