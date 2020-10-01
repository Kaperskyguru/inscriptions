const mix = require("laravel-mix");
const svgicon = require("laravel-mix-vue-svgicon");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/appv1.js", "public/js")
    .js("resources/js/appv2.js", "public/js")
    .copyDirectory("resources/fonts", "public/css/fonts")
    .sass("resources/sass/appv1.scss", "public/css")
    .sass("resources/sass/print.scss", "public/css")
    .svgicon("./resources/svg");

if (mix.inProduction()) {
    mix.version();
}
