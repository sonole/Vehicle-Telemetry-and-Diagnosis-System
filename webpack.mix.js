let mix = require('laravel-mix');

mix.js('node_modules/air-datepicker/air-datepicker.js', 'dist/air-datepicker');
mix.css('node_modules/air-datepicker/air-datepicker.css', 'dist/air-datepicker');

mix.js('resources/js/app.js', 'js');
mix.css('resources/css/app.css', 'css');

