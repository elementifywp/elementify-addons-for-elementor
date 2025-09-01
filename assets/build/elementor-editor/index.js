/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/elementor-editor/index.scss":
/*!************************************************!*\
  !*** ./assets/src/elementor-editor/index.scss ***!
  \************************************************/
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
/*!**********************************************!*\
  !*** ./assets/src/elementor-editor/index.js ***!
  \**********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.scss */ "./assets/src/elementor-editor/index.scss");
/*scss*/


// IMPORTANT: This needs a fix on the isotope file to work in the Elementor preview.
// See https://github.com/elementor/elementor/issues/6756
/* You need to comment the following lines in the isotope file:
// check that elem is an actual element
    if ( !( elem instanceof HTMLElement ) ) {
      return;
    }
*/

function startElemenfolio() {
  try {
    const iframe = jQuery('#elementor-preview-iframe');
    if (!iframe.length) return;
    const iframeContents = iframe.contents();
    const portfolioItems = iframeContents.find('.eae-portfolio[data-layout="masonry"] .eae-portfolio__content');
    if (!portfolioItems.length) return;
    portfolioItems.imagesLoaded(function () {
      // Get gutter sizes from data attributes or fallback to defaults
      const getGutterSize = () => {
        const windowWidth = jQuery(window).width();
        const $portfolio = portfolioItems.closest('.eae-portfolio');
        if (windowWidth <= 768) {
          return parseInt($portfolio.data('gutter-mobile') || 10); // Mobile gutter
        } else if (windowWidth <= 1024) {
          return parseInt($portfolio.data('gutter-tablet') || 10); // Tablet gutter
        } else {
          return parseInt($portfolio.data('gutter-desktop') || 10); // Desktop gutter
        }
      };

      // Initialize Masonry
      const $container = portfolioItems.isotope({
        layoutMode: 'masonry',
        itemSelector: '.eae-portfolio__item',
        resize: true,
        percentPosition: true,
        masonry: {
          columnWidth: '.eae-grid-sizer',
          gutter: getGutterSize()
        }
      });

      // Update layout and gutter on window resize
      const resizeHandler = function () {
        $container.isotope('option', {
          masonry: {
            gutter: getGutterSize()
          }
        });
        $container.isotope('layout');
      };
      jQuery(window).on('resize', resizeHandler);

      // Cleanup function
      return function () {
        jQuery(window).off('resize', resizeHandler);
        $container.isotope('destroy');
      };
    });
  } catch (error) {
    console.error('Error in startElemenfolio:', error);
  }
}
jQuery(document).ready(function ($) {
  // Initialize when Elementor widget is ready
  if (typeof elementorFrontend !== 'undefined') {
    elementorFrontend.hooks.addAction('frontend/element_ready/widget', startElemenfolio);
  }

  // Fallback interval for preview mode
  const previewCheckInterval = setInterval(function () {
    const iframe = $('#elementor-preview-iframe');
    if (iframe.length && iframe.contents().find('.eae-portfolio[data-layout="masonry"] .eae-portfolio__content').length) {
      startElemenfolio();
    }
  }, 1000);

  // Cleanup when leaving the page
  $(window).on('unload', function () {
    clearInterval(previewCheckInterval);
  });
});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map