<?php

/**
 * Plugin.
 *
 * @package elementify-addons-for-elementor
 * @since 1.0.0
 */

namespace Elementify_Addons_For_Elementor\Inc;

use Elementify_Addons_For_Elementor\Inc\Traits\Singleton;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Plugin Main Class
 *
 * @since 1.0.0
 */
final class Plugin
{
	use Singleton;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	protected function __construct()
	{

		// Load class.
		Assets::get_instance();
		Utils::get_instance();
		Integration::get_instance();
		Rest_Endpoint::get_instance();
		if (is_admin()) {
			Dashboard::get_instance();
		}
	}

	/**
	 * Method to execute tasks on plugin activation.
	 *
	 * This function is triggered when the plugin is activated.
	 * It can be used to set up default options, create necessary database tables,
	 * or perform any other initial setup required by the plugin.
	 *
	 * @since 1.0.0
	 * @return void
	 */

	public function activate()
	{
		$current_version 	= get_option('elementify_addons_for_elementor_version', '0.0.0');
		$new_version 		= ELEMENTIFY_ADDONS_FOR_ELEMENTOR_VERSION; // Replace with your plugin version

		if (version_compare($current_version, $new_version, '<')) {
			// Flush rewrite rules on update
			flush_rewrite_rules();
			update_option('elementify_addons_for_elementor_version', $new_version);
		}
	}

	/**
	 * Method to execute tasks on plugin deactivation.
	 *
	 * This function is triggered when the plugin is deactivated.
	 * It can be used to clean up any resources or data associated with the plugin.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function deactivate()
	{
		flush_rewrite_rules();
	}

	/**
	 * Prevent cloning of the plugin instance
	 *
	 * @since 1.0.0
	 */
	public function __clone()
	{
		_doing_it_wrong(
			__FUNCTION__,
			esc_html__('Cloning is forbidden.', 'elementify-addons-for-elementor'),
			esc_html(ELEMENTIFY_ADDONS_FOR_ELEMENTOR_VERSION)
		);
	}

	/**
	 * Prevent unserializing of the plugin instance
	 *
	 * @since 1.0.0
	 */
	public function __wakeup()
	{
		_doing_it_wrong(
			__FUNCTION__,
			esc_html__('Unserializing instances of this class is forbidden.', 'elementify-addons-for-elementor'),
			esc_html(ELEMENTIFY_ADDONS_FOR_ELEMENTOR_VERSION)
		);
	}
}