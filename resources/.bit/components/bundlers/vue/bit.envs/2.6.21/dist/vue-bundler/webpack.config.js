"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
var path_1 = __importDefault(require("path"));
var webpack_node_externals_1 = __importDefault(require("webpack-node-externals"));
var getFilesList_1 = __importDefault(require("./getFilesList"));
var preset_env_1 = __importDefault(require("@babel/preset-env"));
var plugin_1 = __importDefault(require("vue-loader/lib/plugin"));
var modulesPath = path_1.default.normalize("" + __dirname + path_1.default.sep + ".." + path_1.default.sep + "node_modules");
var getConfig = function (files, distPath) {
    return {
        entry: getFilesList_1.default(files),
        context: "" + __dirname + path_1.default.sep + "..",
        resolve: {
            modules: [modulesPath, 'node_modules'],
            extensions: ['*', '.js', '.vue', '.json']
        },
        resolveLoader: {
            modules: [modulesPath, 'node_modules']
        },
        module: {
            rules: [
                {
                    test: /\.vue$/,
                    loader: 'vue-loader',
                    options: {
                        transpileOptions: {
                            transforms: {
                                modules: false
                            }
                        }
                    }
                },
                {
                    test: /\.js$/,
                    loader: 'babel-loader',
                    options: {
                        babelrc: false,
                        presets: [preset_env_1.default]
                    }
                },
                {
                    test: /\.tsx?$/,
                    loader: 'ts-loader',
                    exclude: /node_modules/,
                    options: {
                        appendTsSuffixTo: [/\.vue$/]
                    }
                },
                {
                    test: /\.css$/,
                    use: ['vue-style-loader', 'css-loader']
                },
                {
                    test: /\.s[a|c]ss$/,
                    loader: ['vue-style-loader', 'css-loader', 'sass-loader']
                },
                {
                    test: /\.less$/,
                    loader: ['vue-style-loader', 'css-loader', 'less-loader']
                }
            ]
        },
        plugins: [
            new plugin_1.default()
        ],
        externals: [webpack_node_externals_1.default()],
        output: {
            path: path_1.default.resolve(distPath),
            filename: '[name].js',
            libraryTarget: 'umd'
        }
    };
};
exports.default = getConfig;
