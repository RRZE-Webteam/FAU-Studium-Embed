/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/ts/accordion/constants.ts":
/*!*********************************************!*\
  !*** ./resources/ts/accordion/constants.ts ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ACCORDION_BUTTON_SELECTOR: () => (/* binding */ ACCORDION_BUTTON_SELECTOR),
/* harmony export */   ACCORDION_ITEM_SELECTOR: () => (/* binding */ ACCORDION_ITEM_SELECTOR),
/* harmony export */   CONTENT_HEIGHT_VAR: () => (/* binding */ CONTENT_HEIGHT_VAR)
/* harmony export */ });
var ACCORDION_ITEM_SELECTOR = '.c-accordion-item';
var ACCORDION_BUTTON_SELECTOR = '.c-accordion-item__button';
var CONTENT_HEIGHT_VAR = '--content-height';

/***/ }),

/***/ "./resources/ts/accordion/index.ts":
/*!*****************************************!*\
  !*** ./resources/ts/accordion/index.ts ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./constants */ "./resources/ts/accordion/constants.ts");

var open = function (button) {
  var _a;
  if (button.ariaExpanded === 'true') {
    return;
  }
  var content = document.getElementById((_a = button.getAttribute('aria-controls')) !== null && _a !== void 0 ? _a : '');
  if (!(content instanceof HTMLElement)) {
    return;
  }
  if (!content.style.getPropertyValue(_constants__WEBPACK_IMPORTED_MODULE_0__.CONTENT_HEIGHT_VAR)) {
    content.style.setProperty(_constants__WEBPACK_IMPORTED_MODULE_0__.CONTENT_HEIGHT_VAR, "".concat(content.scrollHeight, "px"));
  }
  button.setAttribute('aria-expanded', 'true');
  content.removeAttribute('hidden');
};
var close = function (button) {
  var _a;
  if (button.ariaExpanded === 'false') {
    return;
  }
  var content = document.getElementById((_a = button.getAttribute('aria-controls')) !== null && _a !== void 0 ? _a : '');
  if (!(content instanceof HTMLElement)) {
    return;
  }
  button.setAttribute('aria-expanded', 'false');
  content.setAttribute('hidden', 'hidden');
};
var onClickButton = function (button) {
  var accordionItem = button.closest(_constants__WEBPACK_IMPORTED_MODULE_0__.ACCORDION_ITEM_SELECTOR);
  if (!(accordionItem instanceof HTMLElement)) {
    return;
  }
  var accordion = accordionItem.parentElement;
  if (!(accordion instanceof HTMLElement)) {
    return;
  }
  if (button.getAttribute('aria-expanded') === 'true') {
    close(button);
    return;
  }
  open(button);
  accordion.querySelectorAll("".concat(_constants__WEBPACK_IMPORTED_MODULE_0__.ACCORDION_BUTTON_SELECTOR, ":not(#").concat(button.id, ")")).forEach(close);
};
var initAccordion = function () {
  document.querySelectorAll(_constants__WEBPACK_IMPORTED_MODULE_0__.ACCORDION_BUTTON_SELECTOR).forEach(function (button) {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      onClickButton(button);
    });
  });
};
initAccordion();

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*******************************!*\
  !*** ./resources/ts/embed.ts ***!
  \*******************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _accordion__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./accordion */ "./resources/ts/accordion/index.ts");

})();

/******/ })()
;
//# sourceMappingURL=embed.js.map