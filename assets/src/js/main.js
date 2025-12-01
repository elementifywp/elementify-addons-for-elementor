// Styles
import '../sass/main.scss';

(function ($) {
	'use strict';

	// Section or column wrapper link
	$(window).on('load', function () {
		let wrapperLinkHandler = null;

		$('body').on(
			'click.onWrapperLink',
			'[data-elementify-wrapper-link]',
			function (event) {
				// Prevent multiple rapid clicks
				if (wrapperLinkHandler) {
					clearTimeout(wrapperLinkHandler);
				}

				const $wrapper = $(this);
				const data = $wrapper.data('elementify-wrapper-link');

				if (!data?.url) {
					console.warn(
						'Elementify: Missing URL data for wrapper link'
					);
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
					anchor.rel = data.nofollow
						? 'nofollow noreferrer'
						: 'noopener';
					anchor.style.display = 'none';

					document.body.appendChild(anchor);
					anchor.click();

					setTimeout(() => {
						if (anchor.parentNode) {
							document.body.removeChild(anchor);
						}
					}, 100);
				}, 10); // Small delay to ensure event propagation is handled
			}
		);
	});
})(jQuery);
