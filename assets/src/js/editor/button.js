(function ($) {
	$(document).ready(function () {
		// Modify the "Add Section" template to add your button
		const templateAddSection = $('#tmpl-elementor-add-section');
		if (templateAddSection.length > 0) {
			let html = templateAddSection.html();
			html = html.replace(
				'<div class="elementor-add-section-drag-title',
				'<div class="elementor-add-section-area-button elementor-add-eae-button"></div><div class="elementor-add-section-drag-title'
			);
			templateAddSection.html(html);
		}

		// Bind click after Elementor preview loads
		elementor.on('preview:loaded', function () {
			$(elementor.$previewContents[0].body).on(
				'click',
				'.elementor-add-eae-button',
				function (event) {
					event.preventDefault();
					event.stopPropagation();

					if (typeof window.etiOpenModal === 'function') {
						window.etiOpenModal();
					}
				}
			);
		});
	});
})(jQuery);
