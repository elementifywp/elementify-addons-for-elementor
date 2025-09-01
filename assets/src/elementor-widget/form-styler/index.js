// Styles
import './index.scss';

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
                    }
                    else if (!shouldShowPlaceholders && originalPlaceholder) {
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
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/eae-form-styler.default',
            formStyler
        );
    });
})(jQuery);