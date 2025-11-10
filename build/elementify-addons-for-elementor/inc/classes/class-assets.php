<?php
/**
 * Enqueue assets.
 *
 * @package elementify-addons-for-elementor
 * @since 1.0.0
 */

namespace Elementify_Addons_For_Elementor\Inc;

use Elementify_Addons_For_Elementor\Inc\Traits\Singleton;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Class Assets
 */
class Assets {

	use Singleton;

	/**
	 * Construct method.
	 *
	 * Initializes the class and sets up necessary hooks.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * Set up hooks for the class.
	 *
	 * @return void
	 */
	protected function setup_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ), 20 );
		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_elementor_frontend_assets' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'register_elementor_editor_assets' ) );
	}

	/**
	 * Register and enqueue assets.
	 *
	 * @return void
	 */
	public function register_assets() {
		$suffix = is_rtl() ? '-rtl' : '';
		// Enqueue styles.
		wp_register_style( 'elementify-addons-for-elementor-icons', ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . "css/icon{$suffix}.css", array(), filemtime( ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH . "/css/icon{$suffix}.css" ), 'all' );
		wp_register_style( 'elementify-addons-for-elementor', ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . "css/main{$suffix}.css", array( 'elementify-addons-for-elementor-icons', 'elementor-frontend' ), filemtime( ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH . "/css/main{$suffix}.css" ), 'all' );
		wp_enqueue_style( 'elementify-addons-for-elementor' );
	}

	/**
	 * Register frontend assets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_elementor_frontend_assets() {

		// Register styles.
		$suffix = is_rtl() ? '-rtl' : '';
		wp_register_style( 'elementify-addons-for-elementor-widget', ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . "css/widget{$suffix}.css", array( 'elementify-addons-for-elementor-icons' ), filemtime( ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH . "/css/widget{$suffix}.css" ), 'all' );

		// Register scripts.
		$asset_config_file = sprintf( '%s/js/widget.asset.php', ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH );

		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}

		$editor_asset    = include_once $asset_config_file;
		$js_dependencies = ( ! empty( $editor_asset['dependencies'] ) ) ? $editor_asset['dependencies'] : array();
		$version         = ( ! empty( $editor_asset['version'] ) ) ? $editor_asset['version'] : filemtime( $asset_config_file );

		wp_register_script(
			'typed',
			ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'library/typed/typed.umd.js',
			array(),
			'2.1.0',
			true
		);
		wp_register_script(
			'swiper',
			ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'library/swiper/swiper-bundle.min.js',
			array(),
			'11.2.10',
			true
		);
		wp_register_script(
			'isotope',
			ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'library/isotope/isotope.pkgd.min.js',
			array( 'jquery', 'imagesloaded' ),
			'3.0.6',
			true
		);
		wp_register_script(
			'packery',
			ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'library/isotope/packery-mode.pkgd.min.js',
			array( 'jquery', 'isotope', 'imagesloaded' ),
			'3.0.6',
			true
		);
		wp_register_script(
			'elementify-addons-for-elementor-widget',
			ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'js/widget.js',
			array_unique( array_merge( $js_dependencies, array( 'jquery' ) ) ),
			$version,
			true
		);
	}

	/**
	 * Register elementor editor assets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_elementor_editor_assets() {
		// Register styles.
		$suffix = is_rtl() ? '-rtl' : '';

		// Register styles.
		wp_register_style( 'elementify-addons-for-elementor-icons', ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . "css/icon{$suffix}.css", array(), filemtime( ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH . "/css/icon{$suffix}.css" ), 'all' );
		wp_register_style( 'elementify-addons-for-elementor-editor', ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . "css/editor{$suffix}.css", array( 'elementify-addons-for-elementor-icons' ), filemtime( ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH . "/css/editor{$suffix}.css" ), 'all' );
		wp_enqueue_style( 'elementify-addons-for-elementor-editor' );

		// Register scripts.
		$asset_config_file = sprintf( '%s/js/editor.asset.php', ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH );

		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}

		$editor_asset    = include_once $asset_config_file;
		$js_dependencies = ( ! empty( $editor_asset['dependencies'] ) ) ? $editor_asset['dependencies'] : array();
		$version         = ( ! empty( $editor_asset['version'] ) ) ? $editor_asset['version'] : filemtime( $asset_config_file );

		// Register scripts.
		wp_enqueue_script(
			'isotope',
			ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'library/isotope/isotope.pkgd.min.js',
			array( 'jquery', 'imagesloaded' ),
			'3.0.6',
			true
		);
		wp_enqueue_script(
			'packery',
			ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'library/isotope/packery-mode.pkgd.min.js',
			array( 'jquery', 'isotope', 'imagesloaded' ),
			'3.0.6',
			true
		);
		wp_enqueue_script(
			'elementify-addons-for-elementor-editor',
			ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'js/editor.js',
			array_unique( array_merge( $js_dependencies, array( 'jquery', 'elementor-editor', 'isotope', 'packery' ) ) ),
			$version,
			true
		);
	}
}
