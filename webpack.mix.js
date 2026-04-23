const mix = require('laravel-mix');

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

mix.styles('resources/assets/css/amsify.css', 'public/css/amsify.css');
mix.stylus('resources/assets/stylus/app.styl', 'public/css');
mix.stylus('resources/assets/stylus/dashboard.styl', 'public/css');


mix.scripts([
    'resources/assets/js/vue.js',
    'resources/assets/js/axios.js',
], 'public/js/app.js');


mix.scripts([
    'resources/assets/js/app.js',
], 'public/js/api.js');

// Scripts Vue de cotizaciones

mix.scripts([
    'resources/assets/js/quotation.js',
], 'public/js/quotation.js');

mix.scripts([
    'resources/assets/js/quotation_utils.js',
], 'public/js/quotation_utils.js');

mix.scripts([
    'resources/assets/js/prices_utils.js',
], 'public/js/prices_utils.js');

mix.scripts([
    'resources/assets/js/quotation_create.js',
], 'public/js/quotation_create.js');

mix.scripts([
    'resources/assets/js/quotation_edit.js',
], 'public/js/quotation_edit.js');


// Scripts Vue de negociaciones
mix.scripts([
    'resources/assets/js/negotiation_create.js',
], 'public/js/negotiation_create.js');

mix.scripts([
    'resources/assets/js/negotiation_edit.js',
], 'public/js/negotiation_edit.js');

mix.scripts([
    'resources/assets/js/negotiation_utils.js',
], 'public/js/negotiation_utils.js');


// Scripts de usuario y genericos


mix.scripts([
    'resources/assets/js/scales.js',
], 'public/js/scales.js');

mix.scripts([
    'resources/assets/js/user.js',
], 'public/js/user.js');

mix.scripts([
    'resources/assets/js/amsify.js',
], 'public/js/amsify.js');

mix.scripts([
    'resources/assets/js/generic.js',
], 'public/js/generic.js');

mix.scripts([
    'resources/assets/js/utilities.js',
], 'public/js/utilities.js');


//Scripts del repositorio

mix.scripts([
    'resources/assets/js/repository.js',
], 'public/js/repository.js');

mix.scripts([
    'resources/assets/js/reports.js',
], 'public/js/reports.js');

mix.js([
    'resources/assets/js/bootstrap.js',
], 'public/js/bootstrap.js');

mix.js([
    'resources/assets/js/app_components.js',
], 'public/js/app_components.js');


//Scripts del arp

mix.scripts([
    'resources/assets/js/arp.js',
], 'public/js/arp.js');
