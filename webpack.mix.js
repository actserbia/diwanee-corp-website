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
     mix.js([
            'resources/assets/js/app.js'
         ], 'public/js/app.js')
        .sass('resources/assets/sass/app.scss', 'public/css')
        .combine([
             'node_modules/bootstrap/dist/css/bootstrap.min.css',
             'public/css/app.css'
         ], 'public/css/app.css')

        .combine([
            'node_modules/jquery/dist/jquery.min.js',
            'node_modules/moment/min/moment.min.js',
            'node_modules/bootstrap/dist/js/bootstrap.min.js',
            'node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
            'node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
            'node_modules/fastclick/lib/fastclick.js',
            'node_modules/icheck/icheck.min.js',
            'node_modules/datatables.net/js/jquery.dataTables.js',
            'node_modules/datatables.net-bs/js/dataTables.bootstrap.js',
            'node_modules/datatables.net-buttons/js/dataTables.buttons.min.js',
            'node_modules/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',
            'node_modules/datatables.net-buttons/js/buttons.flash.min.js',
            'node_modules/datatables.net-buttons/js/buttons.html5.min.js',
            'node_modules/datatables.net-buttons/js/buttons.print.min.js',
            'node_modules/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js',
            'node_modules/datatables.net-keytable/js/dataTables.keyTable.min.js',
            //'node_modules/datatables.net-responsive-bs/js/responsive.bootstrap.min.js',
            'resources/assets/_admin_/js/dataTables.responsive.min.js',
            'resources/assets/_admin_/js/responsive.bootstrap.js',
            'node_modules/datatables.net-scroller/js/dataTables.scroller.min.js',
            'node_modules/pdfmake/build/pdfmake.min.js',
            'node_modules/bootstrap-3-typeahead/bootstrap3-typeahead.min.js',
            //'node_modules/datatables.net-responsive-bs/js/responsive.bootstrap.js',
            'resources/assets/_admin_/js/vfs_fonts.js',
            'resources/assets/_admin_/js/custom.min.js',
            
            'resources/assets/js/admin/utils.js',
            'resources/assets/js/admin/form.js',
            'resources/assets/js/admin/model.js',
            'resources/assets/js/admin/relation-tags-parenting.js',
            'resources/assets/js/admin/relation.js',
            'resources/assets/js/admin/tags.js',
            'resources/assets/js/admin/nodes.js',
            'resources/assets/js/admin/lists.js',
            'resources/lang/*/js/datetimepicker.js',
            'resources/assets/js/admin/datatable.js',
            'resources/lang/*/js/datatable.js',
            'resources/lang/*/js/html5.js',
            'resources/lang/*/js/localization.js',
            'resources/assets/js/Html5Localization.js',
            'resources/assets/js/admin/search.js',
            'resources/assets/js/admin/statistics.js',
            'resources/assets/js/admin/admin.js'
        ], 'public/js/admin.js')

         .js('resources/assets/js/admin/sir-trevor-blocks.js', 'public/js/sir-trevor.js')
         .combine([
             'node_modules/sir-trevor/build/sir-trevor.js',
             'public/js/sir-trevor.js',
             'resources/lang/*/js/sir-trevor.js',
             'resources/assets/js/admin/sir-trevor.js'
         ], 'public/js/sir-trevor.js')
    
        .sass('resources/assets/sass/admin/admin.scss', 'public/css/admin.css')
        .combine([
            'node_modules/bootstrap/dist/css/bootstrap.min.css',
            'node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.js',
            'resources/assets/_admin_/css/font-awesome.min.css',
            'resources/assets/_admin_/css/green.css',
            'resources/assets/_admin_/css/custom.min.css',
            'node_modules/datatables.net-bs/css/dataTables.bootstrap.css',
            'node_modules/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',
            'node_modules/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css',
            'node_modules/datatables.net-keytable-bs/css/keyTable.bootstrap.min.css',
            'node_modules/datatables.net-responsive-bs/css/responsive.bootstrap.min.css',
            'node_modules/datatables.net-scroller-bs/css/scroller.bootstrap.min.css',

            'public/css/admin.css'
        ], 'public/css/admin.css')
        
        .copy('node_modules/bootstrap/dist/css/bootstrap.min.css.map', 'public/css/')

        .copy('resources/assets/_admin_/css/green.png', 'public/css/')
        .copy('resources/assets/_admin_/css/green@2x.png', 'public/css/')

        // .minify('public/css/bootstrap.scss')

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