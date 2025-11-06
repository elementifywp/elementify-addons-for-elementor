(function ($) {
	'use strict';

	const logosScroller = (scope) => {
		const widgetId = scope.data('id');
		if (!widgetId) {
			return;
		}

		const widgetClass = `elementor-element-${widgetId}`;
		const $scrollerWrappers = $(`.${widgetClass} .eae-logos`);

		if (!$scrollerWrappers.length) {
			return;
		}

		$scrollerWrappers.each((index, wrapper) => {
			const $scrollers = $(wrapper).find('.eae-logo-scroller');

			if (!$scrollers.length) {
				return;
			}

			$scrollers.each((i, scroller) => {
				try {
					if (
						window.matchMedia('(prefers-reduced-motion: reduce)')
							.matches
					) {
						return;
					}

					if (scroller.hasAttribute('data-animated')) {
						return;
					}

					const scrollerInner =
						scroller.querySelector('.scroller__inner');
					if (!scrollerInner) {
						return;
					}

					// Mark as animated before processing
					scroller.setAttribute('data-animated', 'true');

					// Clone children for seamless looping
					const children = Array.from(scrollerInner.children);
					children.forEach((item) => {
						const clone = item.cloneNode(true);
						clone.setAttribute('aria-hidden', 'true');
						scrollerInner.appendChild(clone);
					});
				} catch (error) {
					// Silently handle errors
				}
			});
		});
	};

	// Initialize on Elementor frontend
	$(window).on('elementor/frontend/init', function () {
		if (typeof window.elementorFrontend !== 'undefined') {
			window.elementorFrontend.hooks.addAction(
				'frontend/element_ready/eae-logos.default',
				logosScroller
			);
		}
	});
})(jQuery);
