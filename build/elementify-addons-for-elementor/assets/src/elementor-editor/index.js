/*scss*/
import './index.scss';

// IMPORTANT: This needs a fix on the isotope file to work in the Elementor preview.
// See https://github.com/elementor/elementor/issues/6756
/* You need to comment the following lines in the isotope file:
// check that elem is an actual element
    if ( !( elem instanceof HTMLElement ) ) {
      return;
    }
*/

function startElemenfolio() {
    try {
        const iframe = jQuery('#elementor-preview-iframe');
        if (!iframe.length) return;

        const iframeContents = iframe.contents();
        const portfolioItems = iframeContents.find('.eae-portfolio[data-layout="masonry"] .eae-portfolio__content');
        if (!portfolioItems.length) return;

        portfolioItems.imagesLoaded(function () {
            // Get gutter sizes from data attributes or fallback to defaults
            const getGutterSize = () => {
                const windowWidth = jQuery(window).width();
                const $portfolio = portfolioItems.closest('.eae-portfolio');
                if (windowWidth <= 768) {
                    return parseInt($portfolio.data('gutter-mobile') || 10); // Mobile gutter
                } else if (windowWidth <= 1024) {
                    return parseInt($portfolio.data('gutter-tablet') || 10); // Tablet gutter
                } else {
                    return parseInt($portfolio.data('gutter-desktop') || 10); // Desktop gutter
                }
            };

            // Initialize Masonry
            const $container = portfolioItems.isotope({
                layoutMode: 'masonry',
                itemSelector: '.eae-portfolio__item',
                resize: true,
                percentPosition: true,
                masonry: {
                    columnWidth: '.eae-grid-sizer',
                    gutter: getGutterSize()
                },
            });

            // Update layout and gutter on window resize
            const resizeHandler = function () {
                $container.isotope('option', {
                    masonry: {
                        gutter: getGutterSize()
                    }
                });
                $container.isotope('layout');
            };

            jQuery(window).on('resize', resizeHandler);

            // Cleanup function
            return function () {
                jQuery(window).off('resize', resizeHandler);
                $container.isotope('destroy');
            };
        });
    } catch (error) {
        console.error('Error in startElemenfolio:', error);
    }
}

jQuery(document).ready(function ($) {
    // Initialize when Elementor widget is ready
    if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction('frontend/element_ready/widget', startElemenfolio);
    }

    // Fallback interval for preview mode
    const previewCheckInterval = setInterval(function () {
        const iframe = $('#elementor-preview-iframe');
        if (iframe.length && iframe.contents().find('.eae-portfolio[data-layout="masonry"] .eae-portfolio__content').length) {
            startElemenfolio();
        }
    }, 1000);

    // Cleanup when leaving the page
    $(window).on('unload', function () {
        clearInterval(previewCheckInterval);
    });
});
