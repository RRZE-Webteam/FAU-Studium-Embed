<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Assets;

use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;
use Inpsyde\Assets\Asset;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Script;
use Inpsyde\Assets\Style;
use Inpsyde\Modularity\Properties\PluginProperties;

use function Fau\DegreeProgram\Output\renderComponent;

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
            (new Script(
                'fau-frontend',
                (string) $this->pluginProperties->baseUrl() . 'assets/ts/frontend.js'
            ))->prependInlineScript(
                'var degreeProgramsOverviewSettings = '
                . $this->degreeProgramsOverviewSettings()
                . ';'
            )->withTranslation(
                'fau-degree-program-output',
                $this->pluginProperties->basePath() . 'languages'
            ),
            new Style(
                'fau-editor',
                (string) $this->pluginProperties->baseUrl() . 'assets/css/editor.css',
                Asset::BACKEND,
            ),
        ];

        $assetManager->register(...$frontend);
    }

    private function degreeProgramsOverviewSettings(): string
    {
        $settings = json_encode([
            'icon_degree' => renderComponent(
                new Component(
                    Icon::class,
                    [
                        'name' => 'degree',
                    ]
                )
            ),
            'icon_close' => renderComponent(
                new Component(
                    Icon::class,
                    [
                        'name' => 'close',
                    ]
                )
            ),
        ]);

        return  $settings !== false ? $settings : '';
    }
}
