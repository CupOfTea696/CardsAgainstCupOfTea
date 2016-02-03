var gulp = require('gulp');
var elixir = require('laravel-elixir');

var sass_options = {
    includePaths: [
        'node_modules/bootstrap-sass/assets/stylesheets',
        require('bourbon').includePaths,
        'node_modules/bourbon-neat/app/assets/stylesheets',
        'node_modules/hint.css/src',
    ]
};

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
    mix.sass([
        'app.scss'
    ], 'public/assets/css', sass_options);
    
    mix.scripts([
        'jquery/**/*.js',
        'lib/**/*.js',
        'app/Application.js',
        'app/**/*.js',
        'app.js'
    ], 'public/assets/js/app.js');
});
