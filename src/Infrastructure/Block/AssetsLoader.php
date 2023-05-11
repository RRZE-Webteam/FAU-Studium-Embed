<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Block;

use Fau\DegreeProgram\Output\Infrastructure\ApiClient\ApiClient;
use Fau\DegreeProgram\Output\Infrastructure\Component\SingleDegreeProgram;
use Inpsyde\Assets\Asset;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Loader\ArrayLoader;
use Inpsyde\Assets\Script;
use Inpsyde\Assets\Style;
use Inpsyde\Modularity\Properties\PluginProperties;

final class AssetsLoader
{
    private const BLOCK_ASSET_HANDLE = 'ts/gutenberg';
    private const JS_OBJECT_NAME = 'DegreeProgramBlockServerData';

    public function __construct(
        private PluginProperties $pluginProperties,
    ) {
    }

    public function load(AssetManager $assetManager): void
    {
        /**
         * @var Asset[] $assets
         */
        $assets = (new ArrayLoader())->load([
            [
                'handle' => self::BLOCK_ASSET_HANDLE,
                'url' => (string) $this->pluginProperties->baseUrl()
                    . 'assets/ts/gutenberg.js',
                'location' => Asset::BLOCK_EDITOR_ASSETS,
                'type' => Script::class,
                'localize' => [
                    self::JS_OBJECT_NAME => [
                        'apiUrl' => ApiClient::apiHost(),
                        'supportedProperties' => SingleDegreeProgram::supportedProperties(),
                    ],
                ],
                'translation' => [
                    'domain' => 'fau-degree-program-output',
                    'path' => $this->pluginProperties->basePath() . 'languages',
                ],
            ],
            [
                'handle' => self::BLOCK_ASSET_HANDLE,
                'url' => (string) $this->pluginProperties->baseUrl()
                    . 'assets/ts/gutenberg.css',
                'location' => Asset::BLOCK_EDITOR_ASSETS,
                'type' => Style::class,
            ],
        ]);

        $assetManager->register(...$assets);
    }
}
