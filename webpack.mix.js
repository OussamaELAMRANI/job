let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix.js('themes/custom/pop_vue/src/js/app.js', 'themes/custom/pop_vue/js/')
  .sass('themes/custom/pop_vue/src/sass/app.scss', 'themes/custom/pop_vue/css/');

