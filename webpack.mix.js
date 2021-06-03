let mix = require('laravel-mix');

mix.sass('assets/src/scss/admin.scss', 'css')
    .setPublicPath('assets/dist');
