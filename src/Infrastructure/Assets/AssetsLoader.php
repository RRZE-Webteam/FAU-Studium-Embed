<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Assets;

use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Style;
use Inpsyde\Modularity\Properties\PluginProperties;

class AssetsLoader
{
    public function __construct(
        private PluginProperties $pluginProperties,
    ) {
    }

    public function load(AssetManager $assetManager): void
    {
        $frontendStyles = (new Style(
            'fau_frontend',
            (string) $this->pluginProperties->baseUrl() . 'assets/css/frontend.css'
        ));

        $assetManager->register($frontendStyles);
    }
}
