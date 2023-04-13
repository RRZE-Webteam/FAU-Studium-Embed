const utils = require('./webpack.utils');

const Encore = require('@symfony/webpack-encore');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const TSConfigPathsPlugin = require('tsconfig-paths-webpack-plugin');
const SpriteLoaderPlugin = require('svg-sprite-loader/plugin');
const path = require('path');

// variables
const sourceFolderRelPath = './resources';

// add .js, .ts, .jsx files here. Use glob patterns.
const sourceJsFiles = [
    `${sourceFolderRelPath}/js/[!.]*.js`,
    `${sourceFolderRelPath}/js/*.jsx`,
    `${sourceFolderRelPath}/ts/*.ts`,
    `${sourceFolderRelPath}/ts/*.tsx`,
    `${sourceFolderRelPath}/svg/*.svg`,
];

// add scss files here. Use glob patterns.
const sourceSassFiles = [`${sourceFolderRelPath}/scss/*.scss`];

// Move files from resources to assets to be able to regenerate the assets folder and to do it in a pipeline
// these are files that were not made by us, images and other stuff that has no treatment yet by us.
const filesToMove = [];

const jsFilesPaths = utils.getFilePaths(sourceJsFiles);
let jsFilesPathsObjs = utils.getFilesPathObjects(sourceFolderRelPath, jsFilesPaths, 'js');

const scssFilesPaths = utils.getFilePaths(sourceSassFiles);
const scssFilesPathsObjs = utils.getFilesPathObjects(sourceFolderRelPath, scssFilesPaths, 'css');

for (const asset of jsFilesPathsObjs) {
    utils.addEntriesToEncore(asset, Encore);
}

for (const asset of scssFilesPathsObjs) {
    utils.addEntriesToEncore(asset, Encore);
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('assets/')

    // public path used by the web server to access the output path
    .setPublicPath('../')
    .setManifestKeyPrefix('assets/')
    .configureManifestPlugin((options) => {
        options.filter = ((file) => /^(?!assets\/svg\/).*$/.test(file.name))
    })

    // uncomment if you use Sass/SCSS files
    .enableSassLoader()
    .configureCssLoader((options) => {
        options.modules = false;
    })
    .enablePostCssLoader()

    .enableReactPreset()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())
    .addPlugin(new DependencyExtractionWebpackPlugin())
    // uncomment if you use TypeScript
    .enableTypeScriptLoader()
    // .enableForkedTypeScriptTypesChecking()
    .cleanupOutputBeforeBuild()
    .copyFiles(filesToMove)
    .enableSourceMaps(!Encore.isProduction())
    .addPlugin(new SpriteLoaderPlugin({ plainSprite: true }))
    .disableSingleRuntimeChunk()
    .configureLoaderRule('images', rule => {
        rule.test = /\.(ico|svg|gif|png|jpg|jpeg|webp)(\?.*)?$/;
        rule.exclude = path.resolve(__dirname, 'resources/svg');
    })
    .addRule({
        test: /\.svg$/,
        include: path.resolve(__dirname, 'resources/svg'),
        use: [
          {
            loader: 'svg-sprite-loader',
            options: {
              extract: true,
              spriteFilename: 'sprite.svg',
              publicPath: '/',
            },
          },
          'svgo-loader',
        ],
    })

console.log('Encore is Production?: ', Encore.isProduction());
let config = Encore.getWebpackConfig();
config.resolve.plugins = [new TSConfigPathsPlugin()];
config.stats.errorDetails = true;
module.exports = config;
