<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Assets;

use Inpsyde\Assets\Asset;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Script;
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
        $frontend = [
            new Style(
                'fau-frontend',
                (string) $this->pluginProperties->baseUrl() . 'assets/css/frontend.css'
            ),
            new Script(
                'fau-frontend',
                (string) $this->pluginProperties->baseUrl() . 'assets/ts/frontend.js'
            ),
            new Style(
                'fau-editor',
                (string) $this->pluginProperties->baseUrl() . 'assets/css/editor.css',
                Asset::BACKEND,
            ),
        ];

        $assetManager->register(...$frontend);
    }
}
