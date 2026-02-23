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
use Elementor\Controls_Manager;
use Elementor\Element_Base;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Integration Handler
 *
 * Manages Elementor-specific integrations including widget categories and widgets registration.
 *
 * @since 1.0.0
 */
class Integration {


	use Singleton;

	/**
	 * Initializes the class and sets up hooks.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * Sets up hooks for the class.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	protected function setup_hooks(): void {
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_widgets_category' ), 1 );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

		// Add controls to sections, columns, and widgets in Advanced tab.
		add_action( 'elementor/element/column/section_advanced/after_section_end', array( $this, 'add_controls_section' ), 1 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', array( $this, 'add_controls_section' ), 1 );
		add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'add_controls_section' ), 1 );

		// Add render attributes before rendering.
		add_action( 'elementor/frontend/before_render', array( $this, 'before_render' ), 1 );
	}

	/**
	 * Register Elementify widgets category.
	 *
	 * @since 1.0.0
	 * @param Elements_Manager $elements_manager Elementor elements manager.
	 * @return void
	 */
	public function register_widgets_category( Elements_Manager $elements_manager ): void {
		$elements_manager->add_category(
			'elementify-addons-for-elementor-category',
			array(
				'title' => Utils::get_category(),
				'icon'  => 'fa fa-plug',
			),
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
	 * @throws \Exception When a widget file cannot be read or widget class is missing.
	 * @return void
	 */
	public function register_widgets( Widgets_Manager $widgets_manager ): void {
		$options = Utils::get_options();
		if ( ! is_array( $options ) ) {
			return;
		}

		$widgets_dir = trailingslashit( ELEMENTIFY_ADDONS_FOR_ELEMENTOR_PATH ) . 'inc/widgets/';

		if ( ! is_dir( $widgets_dir ) || ! is_readable( $widgets_dir ) ) {
			return;
		}

		$widget_files = glob( $widgets_dir . 'class-*.php' );
		if ( empty( $widget_files ) ) {
			return;
		}

		foreach ( $widget_files as $file ) {
			try {
				// Get class name from file.
				$base       = sanitize_file_name( basename( $file, '.php' ) );
				$class_slug = str_replace( 'class-', '', $base );
				$class_name = str_replace( '-', '_', $class_slug );
				$full_class = __NAMESPACE__ . '\\Widgets\\' . str_replace( '-', '_', ucwords( $class_slug, '-' ) );

				// Skip if widget is disabled in options.
				if ( ! isset( $options[ $class_name ] ) || ! $options[ $class_name ] ) {
					continue;
				}

				// Include and validate widget file.
				if ( is_readable( $file ) ) {
					require_once $file;
				} else {
					throw new \Exception( 'Unable to read widget file: ' . $file );
				}

				// Register widget if class exists.
				if ( class_exists( $full_class ) ) {
					$widgets_manager->register( new $full_class() );
				} else {
					throw new \Exception( 'Widget class not found: ' . $full_class );
				}
			} catch ( \Exception $e ) {
				// Log error instead of displaying it directly.
				echo wp_kses_post( $e->getMessage() );
				continue;
			}
		}
	}

	/**
	 * Add controls to sections, columns, and widgets in Advanced tab.
	 *
	 * @since 1.0.0
	 * @param Element_Base $element Element instance.
	 * @return void
	 */
	public function add_controls_section( $element ): void {
		$element->start_controls_section(
			'elementify_wrapper_link',
			array(
				'label' => esc_html__( 'Wrapper Link', 'elementify-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'elementify_wrapper_link_url',
			array(
				'label'       => esc_html__( 'Link', 'elementify-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => 'https://example.com',
				'description' => esc_html__( 'Add a link to make the entire element clickable.', 'elementify-addons-for-elementor' ),
			)
		);

		$element->add_control(
			'elementify_wrapper_link_cursor',
			array(
				'label'        => esc_html__( 'Pointer Cursor', 'elementify-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => esc_html__( 'Show pointer cursor when hovering over the element.', 'elementify-addons-for-elementor' ),
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Add wrapper link attributes before element render.
	 *
	 * @since 1.0.0
	 * @param Element_Base $element Element instance.
	 * @return void
	 */
	public function before_render( Element_Base $element ): void {
		$settings = $element->get_settings_for_display();

		if ( ! empty( $settings['elementify_wrapper_link_url']['url'] ) ) {
			$url = $settings['elementify_wrapper_link_url'];

			// Validate URL.
			if ( empty( $url['url'] ) ) {
				return;
			}

			// Add wrapper link attributes.
			$element->add_render_attribute(
				'_wrapper',
				array(
					'data-elementify-wrapper-link' => wp_json_encode( $url ),
				)
			);

			// Add cursor class if enabled.
			if ( ! empty( $settings['elementify_wrapper_link_cursor'] ) && 'yes' === $settings['elementify_wrapper_link_cursor'] ) {
				$element->add_render_attribute( '_wrapper', 'class', 'elementify-wrapper-link-cursor' );
			}
		}
	}
}
