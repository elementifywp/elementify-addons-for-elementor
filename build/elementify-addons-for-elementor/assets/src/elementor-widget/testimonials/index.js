// Styles
import './index.scss';

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
            console.log("🚀 ~ $container.each ~ settings:", settings)

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
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/eae-testimonials.default',
            testimonials
        );
    });
})(jQuery);