// Styles
import './index.scss';

// Scripts
(function ($) {
    'use strict';

    const tabs = ($scope, $) => {
        const widgetId = $scope.data('id');
        const widgetClass = `elementor-element-${widgetId}`;
        const $container = $(`.${widgetClass} .eae-tabs`);

        if (!$container.length) {
            return; // Exit if no matching elements are found
        }

        $container.each((index, element) => {
            const $element = $(element);

            // Initialize tabs functionality
            try {
                const tabs = $element.find(".eae-tab").get();

                function tabify(tab) {
                    const $tab = $(tab);
                    const $tabList = $tab.find(".eae-tab__list").first();

                    if ($tabList.length) {
                        const $tabItems = $tabList.children();
                        const $tabContent = $tab.find(".eae-tab__content").first();
                        const $tabContentItems = $tabContent.children();

                        // Find active tab or default to first
                        let tabIndex = $tabItems.filter('.is--active').index();
                        if (tabIndex === -1) tabIndex = 0;

                        function setTab(index) {
                            // Validate index
                            if (index < 0 || index >= $tabItems.length) return;

                            $tabItems.removeClass("is--active");
                            $tabContentItems.removeClass("is--active");

                            $tabItems.eq(index).addClass("is--active");
                            $tabContentItems.eq(index).addClass("is--active");
                        }

                        $tabItems.on("click", function () {
                            setTab($(this).index());
                        });

                        setTab(tabIndex);

                        // Handle nested tabs
                        $tab.find(".eae-tab").each(function () {
                            tabify(this);
                        });
                    }
                }

                tabs.forEach(tabify);
            } catch (error) {
                console.error(`Failed to initialize tabs for widget ${widgetId}:`, error);
            }
        });
    };

    // Initialize on Elementor frontend
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/eae-tabs.default',
            tabs
        );
    });
})(jQuery);