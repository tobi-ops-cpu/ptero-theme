const mix = require('laravel-mix');

mix.js('resources/scripts/index.js', 'public/js/app.js')
   .vue({ version: 3 })
   .postCss('resources/css/theme.css', 'public/css/', [
       require('postcss-import'),
       require('tailwindcss'),
       require('autoprefixer'),
   ])
   .version();