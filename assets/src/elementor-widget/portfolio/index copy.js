// Styles
import './index.scss';

// Scripts
(function ($) {
    'use strict';

    const portfolioInit = ($scope, $) => {
        const widgetId = $scope.data('id');
        if (!widgetId) {
            console.warn('Portfolio widget: Missing widget ID');
            return;
        }

        const $container = $scope.find('.eae-portfolio-container');
        if (!$container.length) {
            console.warn(`Portfolio widget #${widgetId}: No container elements found`);
            return;
        }

        $container.each((index, element) => {
            const $element = $(element);
            try {
                const $gridEl = $element.find('.eae-portfolio__items').isotope({
                    itemSelector: '.eae-portfolio__item',
                    percentPosition: true,
                    masonry: {
                        columnWidth: '.eae-portfolio__item',
                        gutter: 20
                    }
                });

                $element.find('.eae-portfolio__filter').on('click', '.eae-portfolio__filter-item', function () {
                    const filterValue = $(this).attr('data-filter');
                    $gridEl.isotope({ filter: filterValue });
                    $element.find('.eae-portfolio__filter-item').removeClass('eae-active');
                    $(this).addClass('eae-active');
                });

                setTimeout(() => {
                    $gridEl.isotope('layout');
                }, 300);

                if (window.elementor && elementor.channels && elementor.channels.editor) {
                    elementor.channels.editor.on('change', () => {
                        setTimeout(() => {
                            $gridEl.isotope('layout');
                        }, 300);
                    });
                }
            } catch (error) {
                console.error(`Portfolio widget #${widgetId}: Isotope initialization failed`, error);
                $element.find('.error-message').show();
            }
        });
    };

    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ele-portfolio.default',
            portfolioInit
        );
    });
})(jQuery);