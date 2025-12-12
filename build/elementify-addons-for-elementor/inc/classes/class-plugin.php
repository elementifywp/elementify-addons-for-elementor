<?php
/**
 * Plugin.
 *
 * @package elementify-addons-for-elementor
 * @since 1.0.0
 */

namespace Elementify_Addons_For_Elementor\Inc;

use Elementify_Addons_For_Elementor\Inc\Traits\Singleton;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Plugin Main Class
 *
 * @since 1.0.0
 */
final class Plugin {

	use Singleton;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {

		if ( ! $this->can_boot() ) {
			return;
		}

		// Load textdomain on 'init' — this is correct.
		add_action( 'init', array( $this, 'load_textdomain' ), 5 ); // Priority 5 to be safe.

		// Initialize components BEFORE other init hooks (so post types/taxonomies are registered early).
		add_action( 'init', array( $this, 'init_components' ), 8 );
	}

	/**
	 * Check if the environment meets plugin requirements.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	private function can_boot() {
		if ( ! $this->is_php_compatible() ) {
			add_action( 'admin_notices', array( $this, 'php_version_notice' ) );
			return false;
		}

		if ( ! $this->is_wp_compatible() ) {
			add_action( 'admin_notices', array( $this, 'wp_version_notice' ) );
			return false;
		}

		return true;
	}

	/**
	 * Check PHP version compatibility.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	private function is_php_compatible() {
		return version_compare( PHP_VERSION, $this->min_php_version, '>=' );
	}

	/**
	 * Check WordPress version compatibility.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	private function is_wp_compatible() {
		global $wp_version;
		return version_compare( $wp_version, $this->min_wp_version, '>=' );
	}

	/**
	 * Display PHP version incompatibility notice.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function php_version_notice() {
		?>
		<div class="notice notice-error">
			<p>
				<?php
				printf(
					/* translators: 1: Minimum required PHP version, 2: Current PHP version */
					esc_html__( 'Elementify Addons for Elementor requires PHP %1$s or higher. You are running PHP %2$s. Please upgrade your PHP version.', 'elementify-addons-for-elementor' ),
					'<strong>' . esc_html( $this->min_php_version ) . '</strong>',
					'<strong>' . esc_html( PHP_VERSION ) . '</strong>'
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Display WordPress version incompatibility notice.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function wp_version_notice() {
		global $wp_version;

		?>
		<div class="notice notice-error">
			<p>
				<?php
				printf(
					/* translators: 1: Minimum required WordPress version, 2: Current WordPress version */
					esc_html__(
						'Elementify Addons for Elementor requires WordPress %1$s or higher. You are running version %2$s. Please upgrade WordPress.',
						'elementify-addons-for-elementor'
					),
					'<strong>' . esc_html( $this->min_wp_version ) . '</strong>',
					'<strong>' . esc_html( $wp_version ) . '</strong>'
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Load plugin translations — now safely on init
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'elementify-addons-for-elementor',
			false,
			dirname( ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BASENAME ) . '/languages'
		);
	}

	/**
	 * Initialize components — now runs AFTER textdomain is loaded
	 */
	public function init_components() {

		Utils::get_instance();
		Assets::get_instance();
		Rest_Endpoint::get_instance();
		Integration::get_instance();
		if ( is_admin() ) {
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
	public function activate() {
		$current_version = get_option( 'elementify_addons_for_elementor_version', '0.0.0' );
		$new_version     = ELEMENTIFY_ADDONS_FOR_ELEMENTOR_VERSION; // Replace with your plugin version.

		if ( version_compare( $current_version, $new_version, '<' ) ) {
			// Flush rewrite rules on update.
			flush_rewrite_rules();
			update_option( 'elementify_addons_for_elementor_version', $new_version );
		}

		// Set activation redirect flag.
		add_option( 'elementify_addons_for_elementor_activation_redirect', true );
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
	public function deactivate() {
		// Clean up the redirect flag on deactivation.
		flush_rewrite_rules();
		delete_option( 'elementify_addons_for_elementor_activation_redirect' );
	}

	/**
	 * Prevent cloning of the plugin instance
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong(
			__FUNCTION__,
			esc_html__( 'Cloning is forbidden.', 'elementify-addons-for-elementor' ),
			esc_html( ELEMENTIFY_ADDONS_FOR_ELEMENTOR_VERSION )
		);
	}

	/**
	 * Prevent unserializing of the plugin instance
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong(
			__FUNCTION__,
			esc_html__( 'Unserializing instances of this class is forbidden.', 'elementify-addons-for-elementor' ),
			esc_html( ELEMENTIFY_ADDONS_FOR_ELEMENTOR_VERSION )
		);
	}
}
