<?php

/**
 * Elementify Addons Integration Handler
 *
 * @package elementify-addons-for-elementor
 * @since 1.0.0
 */

namespace Elementify_Addons_For_Elementor\Inc;

use Elementify_Addons_For_Elementor\Inc\Traits\Singleton;
use Elementify_Addons_For_Elementor\Inc\Utils;
use Elementor\Elements_Manager;
use Elementor\Widgets_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Integration Handler
 *
 * Manages Elementor-specific integrations including widget categories and widgets registration.
 *
 * @since 1.0.0
 */
class Integration
{
    use Singleton;

    /**
     * Initializes the class and sets up hooks.
     *
     * @since 1.0.0
     */
    protected function __construct()
    {
        $this->setup_hooks();
    }

    /**
     * Sets up hooks for the class.
     *
     * @since 1.0.0
     * @return void
     */
    protected function setup_hooks(): void
    {
        add_action('elementor/elements/categories_registered', [$this, 'register_widgets_category'], 1);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
    }

    /**
     * Register Elementify widgets category.
     *
     * @since 1.0.0
     * @param Elements_Manager $elements_manager Elementor elements manager.
     * @return void
     */
    public function register_widgets_category(Elements_Manager $elements_manager): void
    {
        $elements_manager->add_category(
            'elementify-addons-for-elementor-category',
            [
                'title' => Utils::get_category(),
                'icon'  => 'fa fa-plug',
            ],
            1
        );
    }

    /**
     * Register Elementor widgets.
     *
     * Dynamically loads and registers all widget classes from the widgets directory.
     *
     * @since 1.0.0
     * @param Widgets_Manager $widgets_manager Elementor widgets manager.
     * @return void
     */
    public function register_widgets(Widgets_Manager $widgets_manager): void
    {
        $options = Utils::get_options();
        if (!is_array($options)) {
            return;
        }

        $widgets_dir = trailingslashit(ELEMENTIFY_ADDONS_FOR_ELEMENTOR_PATH) . 'inc/widgets/';

        if (!is_dir($widgets_dir) || !is_readable($widgets_dir)) {
            return;
        }

        $widget_files = glob($widgets_dir . 'class-*.php');
        if (empty($widget_files)) {
            return;
        }

        foreach ($widget_files as $file) {
            try {
                // Get class name from file
                $base = sanitize_file_name(basename($file, '.php'));
                $class_slug = str_replace('class-', '', $base);
                $class_name = str_replace('-', '_', $class_slug);
                $full_class = __NAMESPACE__ . '\\Widgets\\' . str_replace('-', '_', ucwords($class_slug, '-'));

                //Skip if widget is disabled in options
                if (!isset($options[$class_name]) || !$options[$class_name]) {
                    continue;
                }

                // Include and validate widget file
                if (is_readable($file)) {
                    require_once $file;
                } else {
                    throw new \Exception('Unable to read widget file: ' . $file);
                }

                // Register widget if class exists
                if (class_exists($full_class)) {
                    $widgets_manager->register(new $full_class());
                } else {
                    throw new \Exception('Widget class not found: ' . $full_class);
                }
            } catch (\Exception $e) {
                echo wp_kses_post($e->getMessage());
            }
        }
    }
}