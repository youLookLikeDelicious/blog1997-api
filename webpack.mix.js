const mix = require('laravel-mix')
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

mix.options({
  hmr: true,
  hmrOptions: {
    host: 'www.blog1997.com',  // www.blog1997.com is my local domain used for testing
    port: 443
  }
})

mix.alias({
  '~': path.join(__dirname, 'resources/js')
})

mix.webpackConfig({
  output: {
    filename: './[name].js',
    hotUpdateMainFilename: '_vue_[id].[hash].hot-update.json',
    hotUpdateChunkFilename: '_vue_[id].[hash].hot-update.json'
  },
  module: {
    rules: [{
      test: /\.scss|\.css$/,
      use: [
        {
          loader: 'sass-loader',
          options: {
            additionalData: `
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
    hot: true,
    host: '0.0.0.0',
    port: 8081,
    historyApiFallback: true,
    disableHostCheck: true,
    inline: true,
    publicPath: 'http://0.0.0.0:8081/'
  }
})

if (mix.inProduction()) {
  mix.version()
}
mix.vue({
  version: 2,
  extractStyles: true,
  globalStyles: false
})

mix.sass('./resources/sass/app.scss', 'vue/css')
mix.js('./resources/js/auth.js', 'vue/auth')
  .js('./resources/js/main.js', 'vue')
  .extract(['vue', 'vue-router', 'vuex', 'axios', '@blog1997/vue-umeditor', '@blog1997/animate'])



mix.copyDirectory('resources/image', 'public/vue/image')
