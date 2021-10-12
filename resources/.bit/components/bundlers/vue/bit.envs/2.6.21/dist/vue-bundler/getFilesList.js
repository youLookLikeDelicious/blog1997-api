"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var getFilesList = function (files) {
    var list = {};
    files.forEach(function (file) {
        if (list[file.stem] && list[file.stem][0].endsWith('.js')) {
        }
        else {
            list[file.stem] = [file.path];
        }
    });
    return list;
};
exports.default = getFilesList;
