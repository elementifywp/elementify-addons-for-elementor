// Styles
import './index.scss';

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
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/eae-logos.default',
            logosScroller
        );
    });
})(jQuery);