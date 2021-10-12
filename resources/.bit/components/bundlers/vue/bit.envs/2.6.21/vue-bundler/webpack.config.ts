import fs from 'fs';
import path from 'path';
import webpack from 'webpack';
import nodeExternals from 'webpack-node-externals';
import getFilesList from './getFilesList';

// Import so it will be added as a dependency on Bit.
import presetEnv from '@babel/preset-env';
import babelLoader from 'babel-loader';
import cssLoader from 'css-loader';
import lessLoader from 'less-loader';
import sassLoader from 'sass-loader';
import tsLoader from 'ts-loader';
import ts from 'typescript';
import Vinyl from 'vinyl';
import vue from 'vue';
import vueLoader from 'vue-loader';
import VueLoaderPlugin from 'vue-loader/lib/plugin';
import vueStyleLoader from 'vue-style-loader';
import vueTemplateCompiler from 'vue-template-compiler';

type LibraryTarget = 'umd';

const modulesPath = path.normalize(`${__dirname}${path.sep}..${path.sep}node_modules`);

const getConfig = (files: Vinyl[], distPath: string) => {
  return {
    entry: getFilesList(files),
    context: `${__dirname}${path.sep}..`,
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
        // this will apply to both plain `.js` files
        // AND `<script>` blocks in `.vue` files
        {
          test: /\.js$/,
          loader: 'babel-loader',
          options: {
            babelrc: false,
            presets: [presetEnv]
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
        // this will apply to both plain `.css` files
        // AND `<style>` blocks in `.vue` files
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
      // make sure to include the plugin for the magic
      new VueLoaderPlugin()
    ],
    externals: [nodeExternals()],
    output: {
      path: path.resolve(distPath),
      filename: '[name].js',
      libraryTarget: 'umd' as LibraryTarget
    }
  };
};
export default getConfig;
