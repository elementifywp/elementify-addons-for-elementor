(function ($) {
	'use strict';

	const testimonials = ($scope) => {
		const widgetId = $scope.data('id');
		if (!widgetId) {
			return;
		}

		const widgetClass = `elementor-element-${widgetId}`;
		const $container = $(`.${widgetClass} .eae-testimonials`);

		if (!$container.length) {
			return;
		}

		$container.each((index, element) => {
			const $element = $(element);
			const settings = $element.data('settings');

			if (!settings) {
				return;
			}

			try {
				const swiperEl = $(`.${widgetClass} .eae-testimonials-swiper`);
				if (!swiperEl.length) {
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
							spaceBetween: settings.spaceBetween?.tablet || 10,
						},
						// Desktop breakpoint
						1024: {
							slidesPerView: settings.slidesPerView?.desktop || 2,
							spaceBetween: settings.spaceBetween?.desktop || 15,
						},
					},
				};

				// Optional features
				if (settings.autoplay) {
					swiperOptions.autoplay = {
						delay: settings.delay || 5000,
						disableOnInteraction: true,
						pauseOnMouseEnter: true, // Native pause on hover
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
						disabledClass: 'swiper-button-disabled',
					};
				}

				if (settings.pagination) {
					swiperOptions.pagination = {
						el: `.${widgetClass} .swiper-pagination`,
						type: settings.paginationType || 'bullets',
						clickable: true,
						dynamicBullets: settings.dynamicBullets || false,
					};
				}

				// Initialize Swiper
				// Only initialize if Swiper is available in the environment
				if (typeof window.Swiper !== 'undefined') {
					const swiperInstance = new window.Swiper(
						swiperEl[0],
						swiperOptions
					);

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
				} else {
					// Swiper not available — skip initialization
				}
			} catch (error) {
				// Silently handle swiper initialization errors
			}
		});
	};

	// Initialize on Elementor frontend
	$(window).on('elementor/frontend/init', function () {
		if (typeof window.elementorFrontend !== 'undefined') {
			window.elementorFrontend.hooks.addAction(
				'frontend/element_ready/eae-testimonials.default',
				testimonials
			);
		}
	});
})(jQuery);
