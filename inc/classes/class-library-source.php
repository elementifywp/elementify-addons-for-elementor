<?php
/**
 * Elementify Addons Template Library Source
 *
 * @package elementify-addons-for-elementor
 * @since 1.0.0
 */

namespace Elementify_Addons_For_Elementor\Inc;

use Elementify_Addons_For_Elementor\Inc\Traits\Singleton;
use Elementor\TemplateLibrary\Source_Base;
use WP_Error;
use Exception;

defined( 'ABSPATH' ) || exit;

/**
 * Elementify Addons Template Library Source
 *
 * @since 1.0.0
 */
class Library_Source extends Source_Base {

	use Singleton;

	private const LIBRARY_CACHE_KEY      = 'eae_library_cache';
	private const API_TEMPLATES_INFO_URL = 'https://demo.elementifywp.com/wp-json/elementifywp/v2/templates-info';
	private const API_TEMPLATE_DATA_URL  = 'https://demo.elementifywp.com/wp-json/elementifywp/v2/template/';
	private const CACHE_EXPIRATION       = 86400;    // 24 hours
	private const CACHE_ERROR_EXPIRATION = 300;      // 5 minutes on failure

	/**
	 * Returns the source ID.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_id(): string {
		return 'elementify-library';
	}

	/**
	 * Retrieve the source title.
	 *
	 * @since 1.0.0
	 * @return string Source title.
	 */
	public function get_title(): string {
		return esc_html__( 'Elementify Addons Library', 'elementify-addons-for-elementor' );
	}

	/**
	 * Intentionally empty. Register data method is required for source class, but there is no data to register from the remote library.
	 *
	 * @since 1.0.0
	 */
	public function register_data(): void {
		// Intentionally empty
	}

	/**
	 * Saves a template to the remote library.
	 *
	 * @param array $template_data Template data to save.
	 *
	 * @return WP_Error Error on failure.
	 */
	public function save_item( $template_data ): WP_Error {
		return new WP_Error( 'invalid_request', esc_html__( 'Cannot save to remote library', 'elementify-addons-for-elementor' ) );
	}

	/**
	 * Updates a template in the remote library.
	 *
	 * @param array $new_data Updated template data.
	 *
	 * @return WP_Error Error on failure.
	 *
	 * @since 1.0.0
	 */
	public function update_item( $new_data ): WP_Error {
		return new WP_Error( 'invalid_request', esc_html__( 'Cannot update remote library', 'elementify-addons-for-elementor' ) );
	}

	/**
	 * Deletes a template from the remote library.
	 *
	 * @param int $template_id The template ID to delete.
	 *
	 * @return WP_Error Error on failure.
	 *
	 * @since 1.0.0
	 */
	public function delete_template( $template_id ): WP_Error {
		return new WP_Error( 'invalid_request', esc_html__( 'Cannot delete from remote library', 'elementify-addons-for-elementor' ) );
	}

	/**
	 * Exports a template from the remote library.
	 *
	 * @param int $template_id The template ID to export.
	 *
	 * @return WP_Error Error on failure.
	 *
	 * @since 1.0.0
	 */
	public function export_template( $template_id ): WP_Error {
		return new WP_Error( 'invalid_request', esc_html__( 'Cannot export from remote library', 'elementify-addons-for-elementor' ) );
	}

	/**
	 * Retrieves an array of prepared template items from the remote library.
	 *
	 * The returned array contains the prepared template items, where each item is an array
	 * containing the template data.
	 *
	 * @param array $args {
	 *     Optional. An array of arguments to filter the results.
	 * }
	 *
	 * @return array An array of prepared template items.
	 *
	 * @since 1.0.0
	 */
	public function get_items( $args = array() ): array {
		$library_data = self::get_library_data();

		if ( empty( $library_data['templates'] ) || ! is_array( $library_data['templates'] ) ) {
			return array();
		}

		return array_map( array( $this, 'prepare_template' ), $library_data['templates'] );
	}

	/**
	 * Retrieves an array of all tags from the remote library.
	 *
	 * @return array An array of all tags from the remote library.
	 */
	public function get_tags(): array {
		return self::get_library_data()['tags'] ?? array();
	}

	/**
	 * Retrieves an array of all type tags from the remote library.
	 *
	 * @return array An array of all type tags from the remote library.
	 */
	public function get_type_tags(): array {
		return self::get_library_data()['type_tags'] ?? array();
	}

	/**
	 * Prepares a template item from the remote library.
	 *
	 * The returned array contains the prepared template item, where each key is a
	 * template data property and the value is the property value.
	 *
	 * @param array $template_data The template data to prepare.
	 *
	 * @return array The prepared template item.
	 */
	private function prepare_template( array $template_data ): array {
		return array(
			'template_id' => (int) ( $template_data['id'] ?? 0 ),
			'title'       => $template_data['title'] ?? esc_html__( 'Untitled', 'elementify-addons-for-elementor' ),
			'type'        => $template_data['type'] ?? 'section',
			'thumbnail'   => $template_data['thumbnail'] ?? '',
			'date'        => (int) ( $template_data['created_at'] ?? time() ),
			'tags'        => (array) ( $template_data['tags'] ?? array() ),
			'isPro'       => ! empty( $template_data['is_pro'] ),
			'url'         => $template_data['url'] ?? '',
			'author'      => $template_data['author'] ?? 'Elementify Addons',
			'categories'  => (array) ( $template_data['categories'] ?? array() ),
		);
	}

	/**
	 * Retrieves the library data from the remote server.
	 *
	 * @param bool $force_update Whether to force an update of the library data.
	 *
	 * @return array|null The library data from the remote server, or null on failure.
	 */
	private static function request_library_data( bool $force_update = false ): ?array {
		$cached = get_transient( self::LIBRARY_CACHE_KEY );

		// Return cached data only if it's valid and contains templates.
		if (
			! $force_update &&
			$cached !== false &&
			is_array( $cached ) &&
			! empty( $cached['templates'] ) &&
			is_array( $cached['templates'] )
		) {
			return $cached;
		}

		$response = wp_remote_get(
			self::API_TEMPLATES_INFO_URL,
			array(
				'timeout'    => $force_update ? 30 : 15,
				'sslverify'  => true,   // Change to false TEMPORARILY to test if SSL is the issue.
				'user-agent' => 'ElementifyLibrary/1.0 (WordPress; ' . home_url() . ') Mozilla/5.0 Compatible',
				'headers'    => array(
					'Accept' => 'application/json',
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_msg  = $response->get_error_message();
			$error_code = $response->get_error_code();
			error_log( "[Elementify Library] WP Error: $error_msg (Code: $error_code)" );
			set_transient( self::LIBRARY_CACHE_KEY, array(), self::CACHE_ERROR_EXPIRATION );
			return null;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );

		if ( $code !== 200 || empty( $body ) ) {
			error_log( "[Elementify Library] Failed - HTTP $code | Body length: " . strlen( $body ) );
			set_transient( self::LIBRARY_CACHE_KEY, array(), self::CACHE_ERROR_EXPIRATION );
			return null;
		}

		$data = json_decode( $body, true );

		if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $data ) ) {
			error_log( '[Elementify Library] Invalid JSON response - Error: ' . json_last_error_msg() );
			set_transient( self::LIBRARY_CACHE_KEY, array(), self::CACHE_ERROR_EXPIRATION );
			return null;
		}

		// Cache successful response.
		set_transient( self::LIBRARY_CACHE_KEY, $data, self::CACHE_EXPIRATION );

		return $data;
	}

	/**
	 * Retrieves the library data from the remote server.
	 *
	 * @param bool $force_update Whether to force an update of the library data.
	 *
	 * @return array The library data from the remote server, or an empty array on failure.
	 */
	public static function get_library_data( bool $force_update = false ): array {
		$data = self::request_library_data( $force_update );
		return is_array( $data ) ? $data : array();
	}

	/**
	 * Retrieves a single template item from the remote library.
	 *
	 * @param int $template_id The template ID to retrieve.
	 *
	 * @return array The template item, or an empty array on failure.
	 */
	public function get_item( $template_id ): array {
		$templates = $this->get_items();

		foreach ( $templates as $template ) {
			if ( (int) $template['template_id'] === (int) $template_id ) {
				return $template;
			}
		}

		return array();
	}

	/**
	 * Retrieves a single template item from the remote library.
	 *
	 * @param int $template_id The template ID to retrieve.
	 *
	 * @return string The template item, or null on failure.
	 */
	public static function request_template_data( int $template_id ): ?string {
		if ( $template_id <= 0 ) {
			return null;
		}

		$query = array(
			'home_url' => trailingslashit( home_url() ),
			'version'  => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_VERSION ?? '1.0.0',
		);

		if ( function_exists( 'elementify_addons_for_elementor_has_pro' ) && elementify_addons_for_elementor_has_pro() ) {
			$query['has_pro']     = 1;
			$query['pro_version'] = defined( 'ELEMENTIFY_ADDONS_FOR_ELEMENTOR_PRO_VERSION' )
				? ELEMENTIFY_ADDONS_FOR_ELEMENTOR_PRO_VERSION
				: '1.0.0';
		}

		$response = wp_remote_get(
			self::API_TEMPLATE_DATA_URL . $template_id,
			array(
				'timeout'    => 40,
				'sslverify'  => true,   // Change to false TEMPORARILY to test if SSL is the issue.
				'user-agent' => 'ElementifyLibrary/1.0 (WordPress; ' . home_url() . ') Mozilla/5.0 Compatible',
				'body'       => $query,
			)
		);

		if ( is_wp_error( $response ) ) {
			error_log( '[Elementify Single Template] WP Error: ' . $response->get_error_message() );
			return null;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		if ( $code !== 200 ) {
			error_log( "[Elementify Single Template] Failed - HTTP $code for ID $template_id" );
			return null;
		}

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Retrieves the template data from the remote server.
	 *
	 * @param array  $args {
	 *      'template_id' int The template ID to retrieve.
	 *      'editor_post_id' int The ID of the current document (optional).
	 *  }
	 * @param string $context The context of the request (optional). Accepts 'display'.
	 *
	 * @return array The template data from the remote server, or an empty array on failure.
	 *
	 * @throws Exception If the template data could not be retrieved from the server, or if the template does not have any content.
	 */
	public function get_data( array $args, $context = 'display' ): array {
		$template_id = (int) ( $args['template_id'] ?? 0 );

		$raw_data = self::request_template_data( $template_id );

		if ( ! $raw_data ) {
			throw new Exception( __( 'Failed to retrieve template data from server.', 'elementify-addons-for-elementor' ) );
		}

		$data = json_decode( $raw_data, true );

		if ( ! is_array( $data ) || empty( $data['content'] ) ) {
			throw new Exception( __( 'Template does not have any content', 'elementify-addons-for-elementor' ) );
		}

		// Process imported content.
		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		// Optional: compatibility with current document.
		$editor_post_id = (int) ( $args['editor_post_id'] ?? 0 );
		if ( $editor_post_id > 0 ) {
			$document = elementify_addons_for_elementor_elementor()->documents->get( $editor_post_id );
			if ( $document ) {
				$data['content'] = $document->get_elements_raw_data( $data['content'], true );
			}
		}

		return $data;
	}

	/**
	 * Clears the library cache.
	 *
	 * Deletes the transient cache entry for the library data, forcing the next request to re-fetch the data from the server.
	 */
	public static function clear_cache(): void {
		delete_transient( self::LIBRARY_CACHE_KEY );
	}
}
