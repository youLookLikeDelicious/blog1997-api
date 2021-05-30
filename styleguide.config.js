const vueLoader = require("vue-loader");
const path = require('path');

module.exports = {
    title: "Vue Style Guide Example",
    components: "resources/js/**/*.vue",
    webpackConfig: {
        resolve: {
            alias: {
                '~': path.join(__dirname, 'resources/js/'),
            }
        },
        module: {
            rules: [{
                    test: /\.vue$/,
                    loader: "vue-loader"
                },
                {
                    test: /\.jsx?$/,
                    exclude: /node_modules/,
                    loader: "babel-loader"
                },
                {
                    test: /\.s?[ac]?ss$/,
                    use: [
                        "style-loader",
                        {
                            loader: "css-loader",
                            options: {
                                url: false
                            }
                        },
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
                }
            ]
        },
        plugins: [new vueLoader.VueLoaderPlugin()]
    },
    defaultExample: false
};
