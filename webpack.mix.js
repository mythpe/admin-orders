
const mix = require('laravel-mix');
require('vuetifyjs-mix-extension');
const config = require('./webpack.config');

mix.webpackConfig({
    output: {
        path: path.resolve(__dirname, process.env.MIX_BUILD_DIRECTORY),
        publicPath: `/${process.env.MANIFEST_DIRECTORY}/`,
        filename: `[name].js`,
        chunkFilename: `chunks/[name].js`
    }
});
mix.webpackConfig(config);

const assetPath = 'resources/dist';
const rootPath = process.env.MIX_BUILD_DIRECTORY;

[
    'images',
    'fonts',
    'pdf-style',
].forEach(folder => mix.copyDirectory(path.join(assetPath, folder), path.join(rootPath, folder)));

mix
.setResourceRoot(`/${process.env.MANIFEST_DIRECTORY}`)
.setPublicPath(`./${rootPath}`)
.copy('resources/dist/favicon.ico', `public`)
.js('resources/app/app.js', 'js')
.sass('resources/sass/app.scss', 'css')
.options({
    // processCssUrls: !1
})
.vuetify('vuetify-loader')
.disableNotifications();

if(mix.inProduction()) {
    mix.sourceMaps(process.env.NODE_ENV === 'production', 'source-map');
}

