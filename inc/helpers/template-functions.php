<?php
/**
 * Helper functions for template files.
 *
 * @package elementify-addons-for-elementor
 * @since 1.0.0
 */

/**
 * @return bool
 * @since 1.0.0
 */
function elementify_addons_for_elementor_has_pro() {
	return defined( 'ELEMENTIFY_ADDONS_FOR_ELEMENTOR_VERSION_PRO' );
}

/**
 * Get elementor instance
 *
 * @return \Elementor\Plugin
 */
function elementify_addons_for_elementor_elementor() {
	return \Elementor\Plugin::instance();
}
