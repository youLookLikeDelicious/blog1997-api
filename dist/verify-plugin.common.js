module.exports =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "D:\\www\\api\\blog1997-api\\.git\\bit\\components\\compilers\\vue\\bit.envs\\0.1.13\\node_modules\\@vue\\cli-service\\lib\\commands\\build\\entry-lib.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./index.js":
/*!******************!*\
  !*** ./index.js ***!
  \******************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\r\n    install(vue) {\r\n        vue.prototype.$verify = function (rule, target) {\r\n            switch (rule) {\r\n                case 'email':\r\n                    return /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\\.[a-zA-Z0-9_-]+)+$/.test(target)\r\n                default:\r\n                    return true\r\n            }\r\n        }\r\n    }\r\n});\r\n\n\n//# sourceURL=webpack://verify-plugin/./index.js?");

/***/ }),

/***/ "D:\\www\\api\\blog1997-api\\.git\\bit\\components\\compilers\\vue\\bit.envs\\0.1.13\\node_modules\\@vue\\cli-service\\lib\\commands\\build\\entry-lib.js":
/*!***********************************************************************************************************************************************!*\
  !*** D:/www/api/blog1997-api/.git/bit/components/compilers/vue/bit.envs/0.1.13/node_modules/@vue/cli-service/lib/commands/build/entry-lib.js ***!
  \***********************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _setPublicPath__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./setPublicPath */ \"D:\\\\www\\\\api\\\\blog1997-api\\\\.git\\\\bit\\\\components\\\\compilers\\\\vue\\\\bit.envs\\\\0.1.13\\\\node_modules\\\\@vue\\\\cli-service\\\\lib\\\\commands\\\\build\\\\setPublicPath.js\");\n/* harmony import */ var _entry__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ~entry */ \"./index.js\");\n/* empty/unused harmony star reexport */\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (_entry__WEBPACK_IMPORTED_MODULE_1__[\"default\"]);\n\n\n\n//# sourceURL=webpack://verify-plugin/D:/www/api/blog1997-api/.git/bit/components/compilers/vue/bit.envs/0.1.13/node_modules/@vue/cli-service/lib/commands/build/entry-lib.js?");

/***/ }),

/***/ "D:\\www\\api\\blog1997-api\\.git\\bit\\components\\compilers\\vue\\bit.envs\\0.1.13\\node_modules\\@vue\\cli-service\\lib\\commands\\build\\setPublicPath.js":
/*!***************************************************************************************************************************************************!*\
  !*** D:/www/api/blog1997-api/.git/bit/components/compilers/vue/bit.envs/0.1.13/node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js ***!
  \***************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n// This file is imported into lib/wc client bundles.\n\nif (typeof window !== 'undefined') {\n  if (true) {\n    __webpack_require__(/*! current-script-polyfill */ \"D:\\\\www\\\\api\\\\blog1997-api\\\\.git\\\\bit\\\\components\\\\compilers\\\\vue\\\\bit.envs\\\\0.1.13\\\\node_modules\\\\current-script-polyfill\\\\currentScript.js\")\n  }\n\n  var i\n  if ((i = window.document.currentScript) && (i = i.src.match(/(.+\\/)[^/]+\\.js(\\?.*)?$/))) {\n    __webpack_require__.p = i[1] // eslint-disable-line\n  }\n}\n\n// Indicate to webpack that this file can be concatenated\n/* harmony default export */ __webpack_exports__[\"default\"] = (null);\n\n\n//# sourceURL=webpack://verify-plugin/D:/www/api/blog1997-api/.git/bit/components/compilers/vue/bit.envs/0.1.13/node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js?");

/***/ }),

/***/ "D:\\www\\api\\blog1997-api\\.git\\bit\\components\\compilers\\vue\\bit.envs\\0.1.13\\node_modules\\current-script-polyfill\\currentScript.js":
/*!***************************************************************************************************************************************!*\
  !*** D:/www/api/blog1997-api/.git/bit/components/compilers/vue/bit.envs/0.1.13/node_modules/current-script-polyfill/currentScript.js ***!
  \***************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("// document.currentScript polyfill by Adam Miller\n\n// MIT license\n\n(function(document){\n  var currentScript = \"currentScript\",\n      scripts = document.getElementsByTagName('script'); // Live NodeList collection\n\n  // If browser needs currentScript polyfill, add get currentScript() to the document object\n  if (!(currentScript in document)) {\n    Object.defineProperty(document, currentScript, {\n      get: function(){\n\n        // IE 6-10 supports script readyState\n        // IE 10+ support stack trace\n        try { throw new Error(); }\n        catch (err) {\n\n          // Find the second match for the \"at\" string to get file src url from stack.\n          // Specifically works with the format of stack traces in IE.\n          var i, res = ((/.*at [^\\(]*\\((.*):.+:.+\\)$/ig).exec(err.stack) || [false])[1];\n\n          // For all scripts on the page, if src matches or if ready state is interactive, return the script tag\n          for(i in scripts){\n            if(scripts[i].src == res || scripts[i].readyState == \"interactive\"){\n              return scripts[i];\n            }\n          }\n\n          // If no match, return null\n          return null;\n        }\n      }\n    });\n  }\n})(document);\n\n\n//# sourceURL=webpack://verify-plugin/D:/www/api/blog1997-api/.git/bit/components/compilers/vue/bit.envs/0.1.13/node_modules/current-script-polyfill/currentScript.js?");

/***/ })

/******/ });