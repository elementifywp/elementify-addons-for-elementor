<?php

/**
 * Plugin Name:       Elementify Addons for Elementor
 * Plugin URI:        https://elementifywp.com/elementify-addons
 * Description:       <a href="https://elementifywp.com/">Elementify Addons for Elementor</a> is a powerful and lightweight extension designed to supercharge your Elementor Page Builder. Packed with a collection of creative and fully customizable widgets, it helps you build faster and design smarter—just like a pro. Whether you're crafting landing pages, blogs, or business websites, Elementify Addons makes it easy to create stunning layouts with minimal effort and maximum flexibility.
 * Version:           1.0.1
 * Requires Plugins:  elementor
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Elementor tested up to: 3.31
 * Author:            elementifywp
 * Author URI:        https://elementifywp.com/
 * License:           GPLv3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain:       elementify-addons-for-elementor
 * Domain Path:       /languages
 *
 * @package           elementify-addons-for-elementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Define plugin constants.
 */
define('ELEMENTIFY_ADDONS_FOR_ELEMENTOR_VERSION', '1.0.1');
define('ELEMENTIFY_ADDONS_FOR_ELEMENTOR_NAME', 'elementify-addons-for-elementor');
define('ELEMENTIFY_ADDONS_FOR_ELEMENTOR_PATH', plugin_dir_path(__FILE__));
define('ELEMENTIFY_ADDONS_FOR_ELEMENTOR_URL', plugin_dir_url(__FILE__));
define('ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BASENAME', plugin_basename(__FILE__));
define('ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH', ELEMENTIFY_ADDONS_FOR_ELEMENTOR_PATH . 'assets/build/');
define('ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL', ELEMENTIFY_ADDONS_FOR_ELEMENTOR_URL . 'assets/build/');

/**
 * Bootstrap the plugin.
 */
require_once ELEMENTIFY_ADDONS_FOR_ELEMENTOR_PATH . 'inc/helpers/autoloader.php';

use Elementify_Addons_For_Elementor\Inc\Plugin;

// Check if the class exists and WordPress environment is valid
if (class_exists('Elementify_Addons_For_Elementor\Inc\Plugin')) {
    // Instantiate the plugin
    $the_plugin = Plugin::get_instance();

    // Register activation and deactivation hooks
    register_activation_hook(__FILE__, [$the_plugin, 'activate']);
    register_deactivation_hook(__FILE__, [$the_plugin, 'deactivate']);
}