// Styles
import './index.scss';

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
                    typeSpeed: settings.typeSpeed || 30, // Fallback to 30 if not specified
                    backSpeed: settings.backSpeed || 20, // Optional: Add backspace speed
                    startDelay: settings.startDelay || 30,
                    backDelay: settings.backDelay || 20,
                    loop: settings.loop || false, // Optional: Enable looping
                    showCursor: settings.showCursor !== false, // Optional: Show cursor unless explicitly disabled
                    cursorChar: settings.cursorChar || '|', // Optional: Custom cursor character
                    fadeOut: settings.fadeOut !== false, // Optional: Enable fadeOut
                });
            } catch (error) {
                console.error(`Failed to initialize Typed.js for widget ${widgetId}:`, error);
            }
        });
    };

    // Initialize on Elementor frontend
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/eae-animated-text.default',
            animatedText
        );
    });
})(jQuery);