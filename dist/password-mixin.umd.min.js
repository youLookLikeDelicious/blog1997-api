(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else if(typeof exports === 'object')
		exports["password-mixin"] = factory();
	else
		root["password-mixin"] = factory();
})((typeof self !== 'undefined' ? self : this), function() {
return /******/ (function(modules) { // webpackBootstrap
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

/***/ "./password.js":
/*!*********************!*\
  !*** ./password.js ***!
  \*********************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\r\n    data() {\r\n        return {\r\n            strength: 0\r\n        }\r\n    },\r\n    methods: {\r\n        /**\r\n         * 计算密码的强度\r\n         */\r\n        checkPasswordStrength() {\r\n            if (!this.model.password.length) {\r\n                return this.strength = 0\r\n            }\r\n            if (this.model.password.length < 8) {\r\n                return (this.strength = 1);\r\n            }\r\n\r\n            const type = this.calculateStringType(this.model.password);\r\n\r\n            this.strength = this.calculateLevel(type);\r\n        },\r\n        /**\r\n         * 计算字符串包含的特殊字符的种类\r\n         *\r\n         * @param {string} str\r\n         * @return {integer}\r\n         */\r\n        calculateStringType(str) {\r\n            let type = 0;\r\n            for (let i = 0, len = str.length; i < len; i++) {\r\n                const charCode = str.charCodeAt(i);\r\n\r\n                if (48 <= charCode && charCode <= 57) {\r\n                    type |= 1;\r\n                } else if (\r\n                    (65 <= charCode && charCode <= 90) ||\r\n                    (97 <= charCode && charCode <= 122)\r\n                ) {\r\n                    type |= 2;\r\n                } else {\r\n                    type |= 4;\r\n                }\r\n            }\r\n            return type;\r\n        },\r\n        /**\r\n         * 计算密码的强度\r\n         *\r\n         * @param {int} type\r\n         * @return {int}\r\n         */\r\n        calculateLevel(type) {\r\n            let level = 0;\r\n            do {\r\n                if (type & 1) {\r\n                    ++level;\r\n                }\r\n                type = type >>> 1;\r\n            } while (type);\r\n\r\n            return level;\r\n        },\r\n        /**\r\n         * 比较两次输入的密码\r\n         */\r\n        comparePassword() {\r\n            const model = this.model;\r\n            if (model.password && this.model.password_confirmation &&\r\n                model.password !== model.password_confirmation\r\n            ) {\r\n                this.passwordConfirmError = \"输入的密码不一致\";\r\n            } else if (this.passwordConfirmError) {\r\n                this.passwordConfirmError = \"\";\r\n            }\r\n        },\r\n    },\r\n    watch: {\r\n        \"model.password_confirmation\"() {\r\n            this.comparePassword();\r\n        },\r\n        \"model.password\"() {\r\n            this.comparePassword();\r\n            this.checkPasswordStrength()\r\n        },\r\n    },\r\n});\r\n\n\n//# sourceURL=webpack://password-mixin/./password.js?");

/***/ }),

/***/ "D:\\www\\api\\blog1997-api\\.git\\bit\\components\\compilers\\vue\\bit.envs\\0.1.13\\node_modules\\@vue\\cli-service\\lib\\commands\\build\\entry-lib.js":
/*!***********************************************************************************************************************************************!*\
  !*** D:/www/api/blog1997-api/.git/bit/components/compilers/vue/bit.envs/0.1.13/node_modules/@vue/cli-service/lib/commands/build/entry-lib.js ***!
  \***********************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _setPublicPath__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./setPublicPath */ \"D:\\\\www\\\\api\\\\blog1997-api\\\\.git\\\\bit\\\\components\\\\compilers\\\\vue\\\\bit.envs\\\\0.1.13\\\\node_modules\\\\@vue\\\\cli-service\\\\lib\\\\commands\\\\build\\\\setPublicPath.js\");\n/* harmony import */ var _entry__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ~entry */ \"./password.js\");\n/* empty/unused harmony star reexport */\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (_entry__WEBPACK_IMPORTED_MODULE_1__[\"default\"]);\n\n\n\n//# sourceURL=webpack://password-mixin/D:/www/api/blog1997-api/.git/bit/components/compilers/vue/bit.envs/0.1.13/node_modules/@vue/cli-service/lib/commands/build/entry-lib.js?");

/***/ }),

/***/ "D:\\www\\api\\blog1997-api\\.git\\bit\\components\\compilers\\vue\\bit.envs\\0.1.13\\node_modules\\@vue\\cli-service\\lib\\commands\\build\\setPublicPath.js":
/*!***************************************************************************************************************************************************!*\
  !*** D:/www/api/blog1997-api/.git/bit/components/compilers/vue/bit.envs/0.1.13/node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js ***!
  \***************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n// This file is imported into lib/wc client bundles.\n\nif (typeof window !== 'undefined') {\n  if (true) {\n    __webpack_require__(/*! current-script-polyfill */ \"D:\\\\www\\\\api\\\\blog1997-api\\\\.git\\\\bit\\\\components\\\\compilers\\\\vue\\\\bit.envs\\\\0.1.13\\\\node_modules\\\\current-script-polyfill\\\\currentScript.js\")\n  }\n\n  var i\n  if ((i = window.document.currentScript) && (i = i.src.match(/(.+\\/)[^/]+\\.js(\\?.*)?$/))) {\n    __webpack_require__.p = i[1] // eslint-disable-line\n  }\n}\n\n// Indicate to webpack that this file can be concatenated\n/* harmony default export */ __webpack_exports__[\"default\"] = (null);\n\n\n//# sourceURL=webpack://password-mixin/D:/www/api/blog1997-api/.git/bit/components/compilers/vue/bit.envs/0.1.13/node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js?");

/***/ }),

/***/ "D:\\www\\api\\blog1997-api\\.git\\bit\\components\\compilers\\vue\\bit.envs\\0.1.13\\node_modules\\current-script-polyfill\\currentScript.js":
/*!***************************************************************************************************************************************!*\
  !*** D:/www/api/blog1997-api/.git/bit/components/compilers/vue/bit.envs/0.1.13/node_modules/current-script-polyfill/currentScript.js ***!
  \***************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("// document.currentScript polyfill by Adam Miller\n\n// MIT license\n\n(function(document){\n  var currentScript = \"currentScript\",\n      scripts = document.getElementsByTagName('script'); // Live NodeList collection\n\n  // If browser needs currentScript polyfill, add get currentScript() to the document object\n  if (!(currentScript in document)) {\n    Object.defineProperty(document, currentScript, {\n      get: function(){\n\n        // IE 6-10 supports script readyState\n        // IE 10+ support stack trace\n        try { throw new Error(); }\n        catch (err) {\n\n          // Find the second match for the \"at\" string to get file src url from stack.\n          // Specifically works with the format of stack traces in IE.\n          var i, res = ((/.*at [^\\(]*\\((.*):.+:.+\\)$/ig).exec(err.stack) || [false])[1];\n\n          // For all scripts on the page, if src matches or if ready state is interactive, return the script tag\n          for(i in scripts){\n            if(scripts[i].src == res || scripts[i].readyState == \"interactive\"){\n              return scripts[i];\n            }\n          }\n\n          // If no match, return null\n          return null;\n        }\n      }\n    });\n  }\n})(document);\n\n\n//# sourceURL=webpack://password-mixin/D:/www/api/blog1997-api/.git/bit/components/compilers/vue/bit.envs/0.1.13/node_modules/current-script-polyfill/currentScript.js?");

/***/ })

/******/ });
});