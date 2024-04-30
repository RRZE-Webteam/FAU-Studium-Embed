/** @type {import('webpack').defaultConfig} */
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const SpriteLoaderPlugin = require('svg-sprite-loader/plugin');
const path = require('path');
const glob = require('glob');
const { modifyRules } = require('./webpack.utils');

/** @type {import('webpack').Configuration} */
const config = {
    ...defaultConfig,
    plugins: [
        ...defaultConfig.plugins,
        new SpriteLoaderPlugin(),
    ],
    module: {
        ...defaultConfig.module,
        rules: [
            ...modifyRules(defaultConfig.module.rules),
            {
                test: /\.tsx?$/,
                use: 'ts-loader',
                exclude: /node_modules/,
            },
            {
                test: /\.svg$/,
                include: path.resolve(__dirname, 'resources/svg'),
                issuer: (path) => !path,
                use: [
                    {
                        loader: 'svg-sprite-loader',
                        options: {
                            extract: true,
                            spriteFilename: 'sprite.svg',
                            publicPath: '/',
                        },
                    },
                    {
                        loader: 'svgo-loader',
                        options: {
                            plugins: [
                                {
                                    name: 'removeAttrs',
                                    params: {
                                        attrs: '(fill|stroke|clip)',
                                    },
                                },
                            ],
                        },
                    },
                ],
            },
        ],
    },
};

module.exports = {
    ...config,
    entry: {
        sprite: glob.sync('./resources/svg/*.svg'),
        'css/editor': './resources/scss/editor.scss',
        'css/embed': './resources/scss/embed.scss',
        'css/frontend': './resources/scss/frontend.scss',
        'ts/admin': './resources/ts/admin.ts',
        'ts/embed': './resources/ts/embed.ts',
        'ts/frontend': './resources/ts/frontend.ts',
        'ts/gutenberg': './resources/ts/gutenberg.ts',
    },
    output: {
        publicPath: './',
        path: __dirname + '/assets',
        filename: '[name].js',
    },
};
