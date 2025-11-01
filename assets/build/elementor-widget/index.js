/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/elementor-widget/animated-text/index.js":
/*!************************************************************!*\
  !*** ./assets/src/elementor-widget/animated-text/index.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.scss */ "./assets/src/elementor-widget/animated-text/index.scss");
// Styles


// Scripts
(function ($) {
  'use strict';

  const animatedText = ($scope, $) => {
    const widgetId = $scope.data('id');
    const widgetClass = `elementor-element-${widgetId}`;
    const $container = $(`.${widgetClass} .eae-animated-text`);
    if (!$container.length) {
      return; // Exit if no matching elements are found
    }
    $container.each((index, element) => {
      const $element = $(element);
      const settings = $element.data('settings');
      if (!settings || !settings.strings || !Array.isArray(settings.strings)) {
        console.warn(`Invalid or missing settings for animated text in widget ${widgetId}`);
        return; // Skip if settings are invalid
      }

      // Initialize Typed.js with safe configuration
      try {
        new Typed(`.${widgetClass} .eae-animated-text__typed`, {
          strings: settings.strings,
          typeSpeed: settings.typeSpeed || 30,
          // Fallback to 30 if not specified
          backSpeed: settings.backSpeed || 20,
          // Optional: Add backspace speed
          startDelay: settings.startDelay || 30,
          backDelay: settings.backDelay || 20,
          loop: settings.loop || false,
          // Optional: Enable looping
          showCursor: settings.showCursor !== false,
          // Optional: Show cursor unless explicitly disabled
          cursorChar: settings.cursorChar || '|',
          // Optional: Custom cursor character
          fadeOut: settings.fadeOut !== false // Optional: Enable fadeOut
        });
      } catch (error) {
        console.error(`Failed to initialize Typed.js for widget ${widgetId}:`, error);
      }
    });
  };

  // Initialize on Elementor frontend
  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction('frontend/element_ready/eae-animated-text.default', animatedText);
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/elementor-widget/animated-text/index.scss":
/*!**************************************************************!*\
  !*** ./assets/src/elementor-widget/animated-text/index.scss ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/src/elementor-widget/form-styler/index.js":
/*!**********************************************************!*\
  !*** ./assets/src/elementor-widget/form-styler/index.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.scss */ "./assets/src/elementor-widget/form-styler/index.scss");
// Styles

(function ($) {
  'use strict';

  const formStyler = ($scope, $) => {
    const widgetId = $scope.data('id');
    const widgetClass = `elementor-element-${widgetId}`;
    const $container = $(`.${widgetClass} .eae-form-styler-container`);
    if (!$container.length) {
      return; // Exit if no matching elements are found
    }
    $container.each((index, element) => {
      const $element = $(element);
      // Initialize functionality
      try {
        const $showPlaceholder = $element.find('.eae-show-placeholders');
        const shouldShowPlaceholders = $showPlaceholder.length > 0;
        $element.find('input,textarea').each(function () {
          const $field = $(this);
          const originalPlaceholder = $field.attr('placeholder');
          if (shouldShowPlaceholders && originalPlaceholder) {
            // Remove placeholder on focus
            $field.on('focus', function () {
              $(this).removeAttr('placeholder');
            });

            // Restore placeholder on blur if field is empty
            $field.on('blur', function () {
              if (!$(this).val()) {
                $(this).attr('placeholder', originalPlaceholder);
              }
            });
          } else if (!shouldShowPlaceholders && originalPlaceholder) {
            $(this).removeAttr('placeholder');
          }
        });

        // mc4wp-form
        const $mc4wpForm = $element.find('.mc4wp-form');
        if ($mc4wpForm.length > 0) {
          $mc4wpForm.find('input:not([type="submit"])').each(function () {
            const $field = $(this);
            $field.before('<br>');
          });
        }
      } catch (error) {
        console.error(`Failed to initialize form styler for widget ${widgetId}:`, error);
      }
    });
  };

  // Initialize on Elementor frontend
  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction('frontend/element_ready/eae-form-styler.default', formStyler);
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/elementor-widget/form-styler/index.scss":
/*!************************************************************!*\
  !*** ./assets/src/elementor-widget/form-styler/index.scss ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/src/elementor-widget/logos/index.js":
/*!****************************************************!*\
  !*** ./assets/src/elementor-widget/logos/index.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.scss */ "./assets/src/elementor-widget/logos/index.scss");
// Styles


// Scripts
(function ($) {
  'use strict';

  const logosScroller = ($scope, $) => {
    const widgetId = $scope.data('id');
    if (!widgetId) {
      console.warn('Logos widget: Missing widget ID');
      return;
    }
    const widgetClass = `elementor-element-${widgetId}`;
    const $scrollerWrappers = $(`.${widgetClass} .eae-logos`);
    if (!$scrollerWrappers.length) {
      console.warn(`Logos widget #${widgetId}: No scroller containers found`);
      return;
    }
    $scrollerWrappers.each((index, wrapper) => {
      const $scrollers = $(wrapper).find('.eae-logo-scroller');
      if (!$scrollers.length) {
        console.warn(`Logos widget #${widgetId}: No scroller elements found in container ${index}`);
        return;
      }
      $scrollers.each((i, scroller) => {
        try {
          if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
            console.log(`Logos widget #${widgetId}: Reduced motion preference detected - skipping animation`);
            return;
          }
          if (scroller.hasAttribute('data-animated')) {
            console.log(`Logos widget #${widgetId}: Scroller already animated`);
            return;
          }
          const scrollerInner = scroller.querySelector('.scroller__inner');
          if (!scrollerInner) {
            console.warn(`Logos widget #${widgetId}: Missing scroller inner element`);
            return;
          }

          // Mark as animated before processing
          scroller.setAttribute('data-animated', 'true');

          // Clone children for seamless looping
          const children = Array.from(scrollerInner.children);
          children.forEach(item => {
            const clone = item.cloneNode(true);
            clone.setAttribute('aria-hidden', 'true');
            scrollerInner.appendChild(clone);
          });
          console.log(`Logos widget #${widgetId}: Successfully initialized scroller ${i} in container ${index}`);
        } catch (error) {
          console.error(`Logos widget #${widgetId}: Error initializing scroller ${i} in container ${index}`, error);
        }
      });
    });
  };

  // Initialize on Elementor frontend
  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction('frontend/element_ready/eae-logos.default', logosScroller);
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/elementor-widget/logos/index.scss":
/*!******************************************************!*\
  !*** ./assets/src/elementor-widget/logos/index.scss ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/src/elementor-widget/portfolio/index.js":
/*!********************************************************!*\
  !*** ./assets/src/elementor-widget/portfolio/index.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.scss */ "./assets/src/elementor-widget/portfolio/index.scss");
// Styles


// Scripts
(function ($) {
  'use strict';

  const portfolioInit = $scope => {
    try {
      // Get widget ID
      const widgetId = $scope.data('id');
      if (!widgetId) {
        console.warn('Portfolio widget: Missing widget ID');
        return;
      }

      // Find portfolio container
      const $container = $scope.find('.eae-portfolio');
      if (!$container.length) {
        console.warn(`Portfolio widget #${widgetId}: No container elements found`);
        return;
      }

      // Get layout type
      const layout = $container.data('layout');

      // Initialize appropriate layout
      if (layout === 'grid') {
        initGridLayout($container);
      } else if (layout === 'masonry') {
        initIsotopeLayout($container);
      } else {
        console.warn(`Portfolio widget #${widgetId}: Invalid layout type "${layout}"`);
        return;
      }

      // Remove loader after layout initialization
      const $loader = $container.find('.eae-portfolio__loader');
      if ($loader.length) {
        $loader.fadeOut(300, () => {
          $loader.remove();
        });
      }
    } catch (error) {
      console.error(`Portfolio widget #${widgetId}: Initialization failed`, error);
    }
  };
  function initGridLayout($container) {
    try {
      // Find elements
      const $filterButtons = $container.find('.eae-portfolio-filter__button');
      const $gridItems = $container.find('.eae-portfolio__item');
      const transitionDelay = 200; // Delay for staggered animation

      // Set initial active button
      const $defaultButton = $filterButtons.filter('[data-filter="*"]');
      if ($defaultButton.length) {
        $defaultButton.addClass('is--active');
      }

      // Prevent multiple event bindings
      $filterButtons.off('click').on('click', function (e) {
        e.preventDefault();
        const $this = $(this);

        // Prevent multiple clicks during animation
        if ($this.prop('disabled')) return;

        // Disable buttons during transition
        $filterButtons.prop('disabled', true);

        // Update active button
        $filterButtons.removeClass('is--active');
        $this.addClass('is--active');

        // Hide all items immediately
        $gridItems.stop(true, true).hide();

        // Filter items
        const filterValue = $this.data('filter');
        const $targetItems = filterValue === '*' ? $gridItems : $container.find(filterValue);
        if ($targetItems.length === 0) {
          // If no items match, re-enable buttons and exit
          $filterButtons.prop('disabled', false);
          return;
        }

        // Track animations
        let totalAnimations = $targetItems.length;
        let completedAnimations = 0;

        // Show items with staggered animation
        $targetItems.each(function (index) {
          $(this).stop(true, true).delay(index * transitionDelay).fadeIn(300, function () {
            completedAnimations++;
            // Re-enable buttons when all animations are complete
            if (completedAnimations === totalAnimations) {
              $filterButtons.prop('disabled', false);
            }
          });
        });
      });
    } catch (error) {
      console.error(`Portfolio widget: Grid layout initialization failed`, error);
    }
  }
  function initIsotopeLayout($container) {
    $container.each((index, element) => {
      const $element = $(element);
      try {
        // Find the grid container
        const $gridEl = $element.find('.eae-portfolio__grid');

        // Get gutter sizes from data attributes or fallback to defaults
        const getGutterSize = () => {
          const windowWidth = $(window).width();
          if (windowWidth <= 768) {
            return parseInt($element.data('gutter-mobile') || 10); // Mobile gutter
          } else if (windowWidth <= 1024) {
            return parseInt($element.data('gutter-tablet') || 10); // Tablet gutter
          } else {
            return parseInt($element.data('gutter-desktop') || 10); // Desktop gutter
          }
        };

        // Initialize Isotope
        $gridEl.isotope({
          itemSelector: '.eae-portfolio__item',
          percentPosition: true,
          masonry: {
            columnWidth: '.eae-grid-sizer',
            gutter: getGutterSize()
          }
        });

        // Filter button functionality
        $element.find('.eae-portfolio-filter').on('click', '.eae-portfolio-filter__button', function () {
          const filterValue = $(this).attr('data-filter');
          $gridEl.isotope({
            filter: filterValue
          });
          $element.find('.eae-portfolio-filter__button').removeClass('is--active');
          $(this).addClass('is--active');
        });

        // Wait for images to load before layout
        $gridEl.imagesLoaded().progress(function () {
          $gridEl.isotope('layout');
        });

        // Handle window resize for dynamic gutter
        $(window).on('resize', () => {
          $gridEl.isotope('option', {
            masonry: {
              gutter: getGutterSize()
            }
          });
          $gridEl.isotope('layout');
        });

        // Handle Elementor editor changes
        if (window.elementor && elementor.channels && elementor.channels.editor) {
          elementor.channels.editor.on('change', () => {
            setTimeout(() => {
              $gridEl.isotope('layout');
            }, 300);
          });
        }

        // Initial layout after a short delay
        setTimeout(() => {
          $gridEl.isotope('layout');
        }, 500);
      } catch (error) {
        console.error(`Portfolio widget: Isotope initialization failed`, error);
        $element.find('.error-message').show();
      }
    });
  }
  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction('frontend/element_ready/eae-portfolio.default', portfolioInit);
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/elementor-widget/portfolio/index.scss":
/*!**********************************************************!*\
  !*** ./assets/src/elementor-widget/portfolio/index.scss ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/src/elementor-widget/tabs/index.js":
/*!***************************************************!*\
  !*** ./assets/src/elementor-widget/tabs/index.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.scss */ "./assets/src/elementor-widget/tabs/index.scss");
// Styles


// Scripts
(function ($) {
  'use strict';

  const tabs = ($scope, $) => {
    const widgetId = $scope.data('id');
    const widgetClass = `elementor-element-${widgetId}`;
    const $container = $(`.${widgetClass} .eae-tabs`);
    if (!$container.length) {
      return; // Exit if no matching elements are found
    }
    $container.each((index, element) => {
      const $element = $(element);

      // Initialize tabs functionality
      try {
        const tabs = $element.find(".eae-tab").get();
        function tabify(tab) {
          const $tab = $(tab);
          const $tabList = $tab.find(".eae-tab__list").first();
          if ($tabList.length) {
            const $tabItems = $tabList.children();
            const $tabContent = $tab.find(".eae-tab__content").first();
            const $tabContentItems = $tabContent.children();

            // Find active tab or default to first
            let tabIndex = $tabItems.filter('.is--active').index();
            if (tabIndex === -1) tabIndex = 0;
            function setTab(index) {
              // Validate index
              if (index < 0 || index >= $tabItems.length) return;
              $tabItems.removeClass("is--active");
              $tabContentItems.removeClass("is--active");
              $tabItems.eq(index).addClass("is--active");
              $tabContentItems.eq(index).addClass("is--active");
            }
            $tabItems.on("click", function () {
              setTab($(this).index());
            });
            setTab(tabIndex);

            // Handle nested tabs
            $tab.find(".eae-tab").each(function () {
              tabify(this);
            });
          }
        }
        tabs.forEach(tabify);
      } catch (error) {
        console.error(`Failed to initialize tabs for widget ${widgetId}:`, error);
      }
    });
  };

  // Initialize on Elementor frontend
  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction('frontend/element_ready/eae-tabs.default', tabs);
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/elementor-widget/tabs/index.scss":
/*!*****************************************************!*\
  !*** ./assets/src/elementor-widget/tabs/index.scss ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/src/elementor-widget/testimonials/index.js":
/*!***********************************************************!*\
  !*** ./assets/src/elementor-widget/testimonials/index.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.scss */ "./assets/src/elementor-widget/testimonials/index.scss");
// Styles


// Scripts
(function ($) {
  'use strict';

  const testimonials = ($scope, $) => {
    const widgetId = $scope.data('id');
    if (!widgetId) {
      console.warn('Testimonials widget: Missing widget ID');
      return;
    }
    const widgetClass = `elementor-element-${widgetId}`;
    const $container = $(`.${widgetClass} .eae-testimonials`);
    if (!$container.length) {
      console.warn(`Testimonials widget #${widgetId}: No container elements found`);
      return;
    }
    $container.each((index, element) => {
      const $element = $(element);
      const settings = $element.data('settings');
      console.log("🚀 ~ $container.each ~ settings:", settings);
      if (!settings) {
        console.warn(`Testimonials widget #${widgetId}: Missing or invalid settings`);
        return;
      }
      try {
        const swiperEl = $(`.${widgetClass} .eae-testimonials-swiper`);
        if (!swiperEl.length) {
          console.warn(`Testimonials widget #${widgetId}: Swiper container not found`);
          return;
        }

        // Default configuration
        const swiperOptions = {
          slidesPerView: settings.slidesPerView?.mobile || 1,
          spaceBetween: settings.spaceBetween?.mobile || 10,
          breakpoints: {
            // Tablet breakpoint
            768: {
              slidesPerView: settings.slidesPerView?.tablet || 2,
              spaceBetween: settings.spaceBetween?.tablet || 10
            },
            // Desktop breakpoint
            1024: {
              slidesPerView: settings.slidesPerView?.desktop || 2,
              spaceBetween: settings.spaceBetween?.desktop || 15
            }
          }
        };

        // Optional features
        if (settings.autoplay) {
          swiperOptions.autoplay = {
            delay: settings.delay || 5000,
            disableOnInteraction: true,
            pauseOnMouseEnter: true // Native pause on hover
          };
        }
        if (settings.loop) {
          swiperOptions.loop = true;
        }
        if (settings.centeredSlides) {
          swiperOptions.centeredSlides = true;
        }
        if (settings.navigation) {
          swiperOptions.navigation = {
            nextEl: `.${widgetClass} .swiper-button-next`,
            prevEl: `.${widgetClass} .swiper-button-prev`,
            disabledClass: 'swiper-button-disabled'
          };
        }
        if (settings.pagination) {
          swiperOptions.pagination = {
            el: `.${widgetClass} .swiper-pagination`,
            type: settings.paginationType || 'bullets',
            clickable: true,
            dynamicBullets: settings.dynamicBullets || false
          };
        }

        // Initialize Swiper
        const swiperInstance = new Swiper(swiperEl[0], swiperOptions);

        // Manual pause/play on hover (alternative approach)
        if (settings.autoplay && settings.pauseOnHover) {
          swiperEl.on('mouseenter', () => {
            if (swiperInstance.autoplay.running) {
              swiperInstance.autoplay.stop();
            }
          });
          swiperEl.on('mouseleave', () => {
            if (!swiperInstance.autoplay.running) {
              swiperInstance.autoplay.start();
            }
          });
        }
      } catch (error) {
        console.error(`Testimonials widget #${widgetId}: Swiper initialization failed`, error);
      }
    });
  };

  // Initialize on Elementor frontend
  $(window).on('elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction('frontend/element_ready/eae-testimonials.default', testimonials);
  });
})(jQuery);

/***/ }),

/***/ "./assets/src/elementor-widget/testimonials/index.scss":
/*!*************************************************************!*\
  !*** ./assets/src/elementor-widget/testimonials/index.scss ***!
  \*************************************************************/
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
  !*** ./assets/src/elementor-widget/index.js ***!
  \**********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _logos_index_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./logos/index.js */ "./assets/src/elementor-widget/logos/index.js");
/* harmony import */ var _animated_text_index_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./animated-text/index.js */ "./assets/src/elementor-widget/animated-text/index.js");
/* harmony import */ var _testimonials_index_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./testimonials/index.js */ "./assets/src/elementor-widget/testimonials/index.js");
/* harmony import */ var _tabs_index_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./tabs/index.js */ "./assets/src/elementor-widget/tabs/index.js");
/* harmony import */ var _portfolio_index_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./portfolio/index.js */ "./assets/src/elementor-widget/portfolio/index.js");
/* harmony import */ var _form_styler_index_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./form-styler/index.js */ "./assets/src/elementor-widget/form-styler/index.js");






})();

/******/ })()
;
//# sourceMappingURL=index.js.map