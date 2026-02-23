<?php
/**
 * Elementify Addons Library Manager
 *
 * @package elementify-addons-for-elementor
 * @since 1.0.0
 */

namespace Elementify_Addons_For_Elementor\Inc;

use Elementify_Addons_For_Elementor\Inc\Traits\Singleton;
use Elementify_Addons_For_Elementor\Inc\Library_Source;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Elementify Addons Library Manager
 *
 * @since 1.0.0
 */
class Library_Manager {

	use Singleton;

	private const VERSION   = 'v1';
	private const NAMESPACE = ELEMENTIFY_ADDONS_FOR_ELEMENTOR_NAME;
	private $source;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->source = Library_Source::get_instance();
		$this->setup_hooks();
	}

	/**
	 * Sets up the hooks for the class.
	 *
	 * @since 1.0.0
	 */
	private function setup_hooks(): void {

		if ( is_user_logged_in() ) {
			add_action( 'elementor/editor/footer', array( $this, 'add_modal_container' ) );
			add_action( 'wp_ajax_eae_insert_template', array( $this, 'ajax_insert_template' ) );
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}
	}

	/**
	 * Prints the modal container to the page.
	 *
	 * This function is hooked onto the `elementor/editor/footer` action and is
	 * responsible for printing the HTML container for the modal.
	 *
	 * @since 1.0.0
	 */
	public function add_modal_container(): void {
		echo '<div id="eae-modal-root"></div>';
	}

	/**
	 * Registers REST API routes for templates library.
	 *
	 * @since 1.0.0
	 */
	public function register_routes(): void {
		$namespace = self::NAMESPACE . '/' . self::VERSION;

		register_rest_route(
			$namespace,
			'/templates',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_templates' ),
				'permission_callback' => array( $this, 'get_item_permissions_check' ),
				'args'                => array(
					'category'     => array(
						'description'       => 'Filter by category slug',
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
					'search'       => array(
						'description'       => 'Search term',
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
					'type'         => array(
						'description'       => 'Filter by template type',
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
					'force_update' => array(
						'description' => 'Force refresh library cache',
						'type'        => 'boolean',
						'default'     => false,
					),
				),
			)
		);

		register_rest_route(
			$namespace,
			'/template/(?P<id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_template' ),
				'permission_callback' => array( $this, 'get_item_permissions_check' ),
				'args'                => array(
					'id' => array(
						'required'          => true,
						'validate_callback' => static fn( $param ) => is_numeric( $param ) && (int) $param > 0,
						'sanitize_callback' => 'absint',
					),
				),
			)
		);
	}

	/**
	 * Retrieves a list of templates from the remote library.
	 *
	 * This endpoint retrieves a list of templates from the remote library, filtered by type, category, and search.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response The response object.
	 */
	public function get_templates( WP_REST_Request $request ): WP_REST_Response {
		try {
			$force_update = (bool) $request->get_param( 'force_update' );

			// Corrected: use static call to clear_cache().
			if ( $force_update ) {
				Library_Source::clear_cache();
			}

			$library_data = $this->source->get_library_data( $force_update );

			$templates = ! empty( $library_data['templates'] ) && is_array( $library_data['templates'] )
				? $library_data['templates']
				: array();

			$type_tags = $this->source->get_type_tags() ?? array();

			$type     = $request->get_param( 'type' );
			$category = $request->get_param( 'category' );
			$search   = $request->get_param( 'search' );

			// Filter by type.
			if ( $type && $type !== 'all' ) {
				$templates = array_filter( $templates, static fn( $t ) => ( $t['type'] ?? '' ) === $type );
			}

			// Filter by category.
			if ( $category && $category !== 'all' ) {
				$templates = array_filter(
					$templates,
					static fn( $t ) => ! empty( $t['categories'] ) && in_array( $category, (array) $t['categories'], true )
				);
			}

			// Search in title or tags.
			if ( $search ) {
				$search_lower = strtolower( $search );
				$templates    = array_filter(
					$templates,
					static function ( $t ) use ( $search_lower ) {
						$title = strtolower( $t['title'] ?? '' );
						$tags  = array_map( 'strtolower', (array) ( $t['tags'] ?? array() ) );
						return str_contains( $title, $search_lower ) || in_array( $search_lower, $tags, true );
					}
				);
			}

			return rest_ensure_response(
				array(
					'success'   => true,
					'data'      => array_values( $templates ),
					'type_tags' => $type_tags,
				)
			);
		} catch ( \Exception $e ) {
			return new WP_REST_Response(
				array(
					'success'   => false,
					'message'   => $e->getMessage(),
					'data'      => array(),
					'type_tags' => array(),
				),
				500
			);
		}
	}

	/**
	 * Retrieves a single template item from the remote library.
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response|WP_Error The template item, or an error response on failure.
	 */
	public function get_template( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$id       = $request->get_param( 'id' );
		$template = $this->source->request_template_data( $id );

		if ( empty( $template ) ) {
			return new WP_Error(
				'rest_not_found',
				__( 'Template not found.', 'elementify-addons-for-elementor' ),
				array( 'status' => 404 )
			);
		}

		return rest_ensure_response(
			array(
				'success' => true,
				'data'    => $template,
			)
		);
	}

	/**
	 * Checks if the current user has the necessary permissions to access the endpoint.
	 *
	 * @return bool|WP_Error True if the user has the necessary permissions, WP_Error otherwise.
	 */
	public function get_item_permissions_check(): bool|WP_Error {
		if ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) {
			return true;
		}

		return new WP_Error(
			'rest_forbidden',
			__( 'Permission denied.', 'elementify-addons-for-elementor' ),
			array( 'status' => 403 )
		);
	}

	/**
	 * AJAX endpoint to insert template data into an Elementor document.
	 *
	 * This endpoint is used to insert a template's content into an Elementor document.
	 *
	 * The data is passed via the 'options' parameter, which should include the following keys:
	 * - 'editor_post_id': The ID of the Elementor document's post.
	 * - 'data': The template data, which should include the 'content' key.
	 *
	 * The endpoint returns the inserted elements in the response, as well as the document's config.
	 *
	 * @since 1.0.0
	 */
	public function ajax_insert_template(): void {
		check_ajax_referer( 'eti_nonce', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'elementify-addons-for-elementor' ) ) );
		}

		$options = json_decode( wp_unslash( $_POST['options'] ?? '' ), true );

		if ( ! is_array( $options ) || empty( $options['editor_post_id'] ) ) {
			wp_send_json_error( array( 'message' => 'Invalid or missing data.' ) );
		}

		$post_id = absint( $options['editor_post_id'] );
		if ( ! get_post( $post_id ) ) {
			wp_send_json_error( array( 'message' => 'Post not found.' ) );
		}

		$elementor = elementify_addons_for_elementor_elementor();
		$elementor->db->switch_to_post( $post_id );

		$document = $elementor->documents->get( $post_id );
		if ( ! $document ) {
			wp_send_json_error( array( 'message' => 'Invalid Elementor document.' ) );
		}

		try {
			$template_data = $this->source->get_data( $options['data'] ?? array() );

			if ( empty( $template_data['content'] ) ) {
				wp_send_json_error( array( 'message' => 'Failed to fetch template data.' ) );
			}

			$content = $template_data['content'];
			if ( is_string( $content ) ) {
				$content = json_decode( $content, true );
			}

			if ( empty( $content ) ) {
				wp_send_json_error( array( 'message' => 'Template content is empty.' ) );
			}

			$elements_to_insert = $content['content'] ?? $content;

			if ( ! is_array( $elements_to_insert ) || empty( $elements_to_insert ) ) {
				wp_send_json_error( array( 'message' => 'Invalid template structure.' ) );
			}

			$elements_to_insert = $this->generate_element_ids( $elements_to_insert );

			$current_elements = $document->get_elements_data();
			$updated_elements = array_merge( $current_elements, $elements_to_insert );

			$document->save( array( 'elements' => $updated_elements ) );

			$raw_data = $document->get_elements_raw_data( null, true );
			$inserted = array_slice( $raw_data, -count( $elements_to_insert ) );

			wp_send_json_success(
				array(
					'message'  => __( 'Template imported successfully.', 'elementify-addons-for-elementor' ),
					'elements' => $inserted,
					'config'   => array(
						'document' => array(
							'type'     => $document->get_name(),
							'settings' => $document->get_settings(),
						),
					),
				)
			);
		} catch ( \Exception $e ) {
			wp_send_json_error( array( 'message' => $e->getMessage() ) );
		}
	}

	/**
	 * Generate unique IDs for all elements in the given array.
	 *
	 * This function will loop through all elements in the given array and
	 * assign a unique ID to each of them. If an element has child
	 * elements, this function will also assign unique IDs to those
	 * child elements.
	 *
	 * @param array $elements The array of elements to generate IDs for.
	 *
	 * @return array The array of elements with unique IDs assigned.
	 */
	private function generate_element_ids( array $elements ): array {
		foreach ( $elements as &$element ) {
			$element['id'] = \Elementor\Utils::generate_random_string();

			if ( ! empty( $element['elements'] ) && is_array( $element['elements'] ) ) {
				$element['elements'] = $this->generate_element_ids( $element['elements'] );
			}
		}
		unset( $element );

		return $elements;
	}
}
