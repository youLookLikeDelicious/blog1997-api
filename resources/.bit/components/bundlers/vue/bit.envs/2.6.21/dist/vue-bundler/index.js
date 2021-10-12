"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
var memory_fs_1 = __importDefault(require("memory-fs"));
var path_1 = __importDefault(require("path"));
var vinyl_1 = __importDefault(require("vinyl"));
var webpack_1 = __importDefault(require("webpack"));
var webpack_config_1 = __importDefault(require("./webpack.config"));
var runWebpack = function (files, distPath) {
    return new Promise(function (resolve, reject) {
        var fs = new memory_fs_1.default();
        var webPackConfig = webpack_config_1.default(files, distPath);
        var outputFiles = [];
        var compiler = webpack_1.default(webPackConfig);
        compiler.outputFileSystem = fs;
        compiler.run(function (err, stats) {
            if (err) {
                reject(err);
            }
            if (stats.hasErrors() && stats.compilation.errors && stats.compilation.errors.length > 0) {
                reject(stats.compilation.errors[0]);
            }
            fs.readdirSync(distPath).forEach(function (distFileName) {
                var fileContent = fs.readFileSync(path_1.default.join(distPath, distFileName));
                outputFiles.push(new vinyl_1.default({
                    contents: fileContent,
                    base: distPath,
                    path: path_1.default.join(distPath, distFileName),
                    basename: distFileName,
                    test: distFileName.indexOf('.spec.') >= 0
                }));
            });
            return resolve(outputFiles);
        });
    });
};
var compile = function (files, distPath) {
    if (files.length === 0) {
        return files;
    }
    return runWebpack(files, distPath).then(function (compiled) {
        return compiled;
    });
};
exports.default = {
    compile: compile
};
