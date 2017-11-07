let mix = require('laravel-mix');

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

//if(process.env.NODE_ENV === 'development') {
     mix.js('resources/assets/js/app.js', 'public/js')
        .sass('resources/assets/sass/app.scss', 'public/css')

        .combine([
            'resources/assets/_admin_/js/jquery.min.js',
            'resources/assets/_admin_/js/bootstrap.min.js',
            'resources/assets/_admin_/js/fastclick.js',
            'resources/assets/_admin_/js/nprogress.js',
            'resources/assets/_admin_/js/icheck.min.js',
            'resources/assets/_admin_/js/jquery.dataTables.min.js',
            'resources/assets/_admin_/js/dataTables.bootstrap.min.js',
            'resources/assets/_admin_/js/dataTables.buttons.min.js',
            'resources/assets/_admin_/js/buttons.bootstrap.min.js',
            'resources/assets/_admin_/js/buttons.flash.min.js',
            'resources/assets/_admin_/js/buttons.html5.min.js',
            'resources/assets/_admin_/js/buttons.print.min.js',
            'resources/assets/_admin_/js/dataTables.fixedHeader.min.js',
            'resources/assets/_admin_/js/dataTables.keyTable.min.js',
            'resources/assets/_admin_/js/dataTables.responsive.min.js',
            'resources/assets/_admin_/js/responsive.bootstrap.js',
            'resources/assets/_admin_/js/dataTables.scroller.min.js',
            'resources/assets/_admin_/js/jszip.min.js',
            'resources/assets/_admin_/js/pdfmake.min.js',
            'resources/assets/_admin_/js/vfs_fonts.js',
            'resources/assets/_admin_/js/custom.min.js',

            'resources/assets/js/admin/admin-custom.js',
            'resources/assets/js/admin/admin.js'
        ], 'public/js/admin.js')
        
        .combine([
            'node_modules/sir-trevor/build/sir-trevor.js',
            'resources/assets/js/admin/sir-trevor-blocks.js'
        ], 'public/js/sir-trevor.js')
    
        .sass('resources/assets/sass/admin/admin.scss', 'public/css/admin.css')
        .combine([
            'resources/assets/_admin_/css/bootstrap.min.css',
            'resources/assets/_admin_/css/font-awesome.min.css',
            'resources/assets/_admin_/css/nprogress.css',
            'resources/assets/_admin_/css/green.css',
            'resources/assets/_admin_/css/bootstrap-progressbar-3.3.4.min.css',
            'resources/assets/_admin_/css/jqvmap.min.css',
            'resources/assets/_admin_/css/custom.min.css',
            'resources/assets/_admin_/css/dataTables.bootstrap.min.css',
            'resources/assets/_admin_/css/buttons.bootstrap.min.css',
            'resources/assets/_admin_/css/fixedHeader.bootstrap.min.css',
            'resources/assets/_admin_/css/responsive.bootstrap.min.css',
            'resources/assets/_admin_/css/scroller.bootstrap.min.css',
            
            'public/css/admin.css'
        ], 'public/css/admin.css')
        .copy('resources/assets/_admin_/css/bootstrap.min.css.map', 'public/css/')

        .sass('resources/assets/sass/admin/sir-trevor.scss', 'public/css/sir-trevor.css')
        .combine([
            'node_modules/sir-trevor/build/sir-trevor.css',
            'public/css/sir-trevor.css'
        ], 'public/css/sir-trevor.css')

        .copy('resources/assets/_admin_/fonts/*', 'public/fonts/')
        .copy('resources/assets/_admin_/images/*', 'public/pictures/')
        .copy('node_modules/sir-trevor/build/sir-trevor-icons.svg', 'public/pictures/')

        .webpackConfig({ devtool: "inline-source-map" })
        .sourceMaps()

        .autoload({
            jquery: ['$', 'window.jQuery', 'jQuery', 'window.$', 'jquery', 'window.jquery']
        });
//}