/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/sass/main.scss":
/*!***********************************!*\
  !*** ./assets/src/sass/main.scss ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!*******************************!*\
  !*** ./assets/src/js/main.js ***!
  \*******************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _sass_main_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../sass/main.scss */ "./assets/src/sass/main.scss");
// Styles

(function ($) {
  'use strict';

  // Section or column wrapper link
  $(window).on('load', function () {
    let wrapperLinkHandler = null;
    $('body').on('click.onWrapperLink', '[data-elementify-wrapper-link]', function (event) {
      // Prevent multiple rapid clicks
      if (wrapperLinkHandler) {
        clearTimeout(wrapperLinkHandler);
      }
      const $wrapper = $(this);
      const data = $wrapper.data('elementify-wrapper-link');
      if (!data?.url) {
        console.warn('Elementify: Missing URL data for wrapper link');
        return;
      }

      // Validate URL format
      try {
        new URL(data.url);
      } catch (e) {
        console.warn('Elementify: Invalid URL format', data.url);
        return;
      }
      wrapperLinkHandler = setTimeout(() => {
        const id = $wrapper.data('id') || `wrapper-${Date.now()}`;
        const anchor = document.createElement('a');
        anchor.href = data.url;
        anchor.target = data.is_external ? '_blank' : '_self';
        anchor.rel = data.nofollow ? 'nofollow noreferrer' : 'noopener';
        anchor.style.display = 'none';
        document.body.appendChild(anchor);
        anchor.click();
        setTimeout(() => {
          if (anchor.parentNode) {
            document.body.removeChild(anchor);
          }
        }, 100);
      }, 10); // Small delay to ensure event propagation is handled
    });
  });
})(jQuery);
})();

/******/ })()
;
//# sourceMappingURL=main.js.map