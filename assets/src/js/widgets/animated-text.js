(function ($) {
	'use strict';

	/**
	 * Animated Text Widget
	 *
	 * @package
	 * @since 1.0.0
	 *
	 * @param {Object} $scope The scope object that contains the widget data.
	 *
	 * @return {void} Undefined.
	 */
	const animatedText = function ($scope) {
		const widgetId = $scope.data('id');
		const widgetClass = `elementor-element-${widgetId}`;
		const $container = $(`.${widgetClass} .eae-animated-text`);

		if (!$container.length) {
			return; // Exit if no matching elements are found
		}

		$container.each((index, element) => {
			const $element = $(element);
			const settings = $element.data('settings');

			if (
				!settings ||
				!settings.strings ||
				!Array.isArray(settings.strings)
			) {
				return; // Skip if settings are invalid
			}

			// Initialize Typed.js with safe configuration
			try {
				if (typeof Typed !== 'undefined') {
					new Typed(`.${widgetClass} .eae-animated-text__typed`, {
						strings: settings.strings,
						typeSpeed: settings.typeSpeed || 30,
						backSpeed: settings.backSpeed || 20,
						startDelay: settings.startDelay || 30,
						backDelay: settings.backDelay || 20,
						loop: settings.loop || false,
						showCursor: settings.showCursor !== false,
						cursorChar: settings.cursorChar || '|',
						fadeOut: settings.fadeOut !== false,
					});
				}
			} catch (error) {
				// Optionally handle error
			}
		});
	};

	// Initialize on Elementor frontend
	jQuery(window).on('elementor/frontend/init', function () {
		if (typeof window.elementorFrontend !== 'undefined') {
			window.elementorFrontend.hooks.addAction(
				'frontend/element_ready/eae-animated-text.default',
				animatedText
			);
		}
	});
})(jQuery);
