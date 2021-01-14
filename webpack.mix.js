const mix = require('laravel-mix');
const { options } = require('marked');
const path = require('path')
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

// mix.options({
//     // extractVueStyles: '[name].css',
//     extractVueStyles: '[name].extracted.css',
//     publicPath: 'public',
//     processCssUrls: true,
//     extractStyles: true
// })


// mix.setPublicPath('public')
mix.alias({
    '~': path.join(__dirname, 'resources/js'),
})

mix.webpackConfig({
    module: {
        rules: [{
            test: /\.scss|\.css$/,
            use: [
                {
                    loader: "sass-loader",
                    options: {
                        data: `
                        @import "resources/sass/_variables.scss";
                        @import "resources/sass/_mixin.scss";
                        @import "resources/sass/_placeholder.scss";
                        `
                    }
                }
            ]
        }]
    },
    devServer: {
        overlay: true // 在dev环境中，直接将错误显示在页面中
    }
})
// mix.js('resources/js/login.js', 'public/js/login/index.js')
if (mix.inProduction()) {
    mix.version();
}

// mix.browserSync({
//     proxy: 'https://www.blog1997.com',
//     open: false
// });

mix.sass('./resources/sass/app.scss', 'vue/css')

mix.vue({
    version: 2,
    extractStyles: true,
    globalStyles: false,
})
mix.js('resources/js/auth.js', '/vue/auth/index.js')
    .js('resources/js/main.js', '/vue/')
    .extract(['vue', 'vue-router', 'vuex', 'axios', '@blog1997/vue-umeditor', '@blog1997/animate'])

mix.copyDirectory('resources/image', 'public/vue/image')
