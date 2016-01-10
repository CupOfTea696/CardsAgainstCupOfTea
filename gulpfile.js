var elixir = require('laravel-elixir');

var bootstrap = 'node_modules/bootstrap-sass/assets/stylesheets/bootstrap',
    bourbon = require('bourbon').includePaths,
    neat = require('bourbon-neat').includePaths;

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass(null, null, {
        includePaths: [
            bootstrap,
            bourbon,
            neat
        ]
    });
    
    mix.sass([
        'app.scss'
    ], 'public/assets/css');
    
    mix.scripts([
        'app.js'
    ], 'public/assets/js/app.js');
});
