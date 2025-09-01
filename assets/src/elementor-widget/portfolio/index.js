// Styles
import './index.scss';

// Scripts
(function ($) {
    'use strict';

    const portfolioInit = ($scope) => {
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
                    },
                });

                // Filter button functionality
                $element.find('.eae-portfolio-filter').on('click', '.eae-portfolio-filter__button', function () {
                    const filterValue = $(this).attr('data-filter');
                    $gridEl.isotope({ filter: filterValue });
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
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/eae-portfolio.default',
            portfolioInit
        );
    });

})(jQuery);