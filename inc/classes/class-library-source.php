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

defined('ABSPATH') || exit;

/**
 * Elementify Addons Template Library Source
 */
class Library_Source extends Source_Base
{
	use Singleton;

	private const LIBRARY_CACHE_KEY       = 'eae_library_cache';
	private const API_TEMPLATES_INFO_URL  = 'https://templates.elementifywp.com/wp-json/elementifywp/v2/templates-info';
	private const API_TEMPLATE_DATA_URL   = 'https://templates.elementifywp.com/wp-json/elementifywp/v2/template/';
	private const CACHE_EXPIRATION        = WEEK_IN_SECONDS;     // ← changed to 1 week
	private const CACHE_ERROR_EXPIRATION  = 300;                 // 5 minutes

	/**
	 * Returns the ID of the source.
	 *
	 * @return string The ID of the source.
	 */
	public function get_id(): string
	{
		return 'elementify-library';
	}

	/**
	 * Returns the title of the source.
	 *
	 * @return string The title of the source.
	 */
	public function get_title(): string
	{
		return esc_html__('Elementify Addons Library', 'elementify-addons-for-elementor');
	}

	/**
	 * Saves a template item to the remote library.
	 *
	 * @param array $template_data The template item data to save.
	 *
	 * @return WP_Error The error object on failure.
	 */
	public function register_data(): void
	/**
	 * Saves a template item to the remote library.
	 *
	 * @param array $template_data The template item data to save.
	 *
	 * @return WP_Error The error object on failure.
	 */

	{
		// intentionally empty
	}

	/**
	 * Saves a template item to the remote library.
	 *
	 * @param array $template_data The template item data to save.
	 *
	 * @return WP_Error The error object on failure.
	 *
	 * @since 1.0.0
	 */
	public function save_item($template_data): WP_Error
	{
		return new WP_Error(
			'invalid_request',
			esc_html__('Cannot save to remote library', 'elementify-addons-for-elementor')
		);
	}

	/**
	 * Updates a template item in the remote library.
	 *
	 * @param array $new_data The updated template item data.
	 *
	 * @return WP_Error The error object on failure.
	 *
	 * @since 1.0.0
	 */
	public function update_item($new_data): WP_Error
	{
		return new WP_Error(
			'invalid_request',
			esc_html__('Cannot update remote library', 'elementify-addons-for-elementor')
		);
	}

	/**
	 * Deletes a template item from the remote library.
	 *
	 * @param int $template_id The template item ID to delete.
	 *
	 * @return WP_Error The error object on failure.
	 *
	 * @since 1.0.0
	 */
	public function delete_template($template_id): WP_Error
	{
		return new WP_Error(
			'invalid_request',
			esc_html__('Cannot delete from remote library', 'elementify-addons-for-elementor')
		);
	}

	/**
	 * Exports a template item from the remote library.
	 *
	 * @param int $template_id The template item ID to export.
	 *
	 * @return WP_Error The error object on failure.
	 *
	 * @since 1.0.0
	 */
	public function export_template($template_id): WP_Error
	{
		return new WP_Error(
			'invalid_request',
			esc_html__('Cannot export from remote library', 'elementify-addons-for-elementor')
		);
	}

	/**
	 * Retrieves a list of template items from the remote library.
	 *
	 * This function retrieves a list of template items from the remote library, filtered by the provided arguments.
	 *
	 * The arguments should contain the following keys:
	 * - 'type': The template type (section, page, etc). Empty for all types.
	 * - 'category': The template category. Empty for all categories.
	 * - 'search': The search query. Empty for no search.
	 *
	 * The function returns an array of template items, with each item containing the following keys:
	 * - 'template_id': The template item ID.
	 * - 'source': The source ID of the template item.
	 * - 'title': The template item title.
	 * - 'type': The template item type.
	 * - 'template_type': The template item type.
	 * - 'thumbnail': The template item thumbnail URL.
	 * - 'date': The template item creation date.
	 * - 'tags': The template item tags array.
	 * - 'url': The template item URL.
	 * - 'author': The template item author.
	 * - 'categories': The template item categories array.
	 *
	 * @param array $args The arguments to filter the template items.
	 * @return array The filtered template items array.
	 */
	public function get_items($args = []): array
	{
		$library_data = self::get_library_data();

		if (empty($library_data['templates']) || !is_array($library_data['templates'])) {
			return [];
		}

		return array_map(
			[$this, 'prepare_template'],
			$library_data['templates']
		);
	}

	/**
	 * Retrieves a list of tags from the remote library.
	 *
	 * The function returns an array of tags, with each tag containing the following keys:
	 * - 'id': The tag ID.
	 * - 'name': The tag name.
	 * - 'slug': The tag slug.
	 * - 'description': The tag description.
	 * - 'count': The number of templates associated with the tag.
	 *
	 * @return array The list of tags.
	 */
	public function get_tags(): array
	{
		return self::get_library_data()['tags'] ?? [];
	}

	/**
	 * Retrieves a list of type tags from the remote library.
	 *
	 * The function returns an array of type tags, with each type tag containing the following keys:
	 * - 'id': The type tag ID.
	 * - 'name': The type tag name.
	 * - 'slug': The type tag slug.
	 * - 'description': The type tag description.
	 * - 'count': The number of templates associated with the type tag.
	 *
	 * @return array The list of type tags.
	 */
	public function get_type_tags(): array
	{
		return self::get_library_data()['type_tags'] ?? [];
	}

	/**
	 * Prepares a template item data for use in the library.
	 *
	 * The function takes a template item data array and returns a prepared array containing the following keys:
	 * - 'template_id': The template item ID.
	 * - 'source': The source ID of the template item.
	 * - 'title': The template item title.
	 * - 'type': The template item type.
	 * - 'template_type': The template item type.
	 * - 'thumbnail': The template item thumbnail URL.
	 * - 'date': The template item creation date.
	 * - 'tags': The template item tags array.
	 * - 'isPro': Whether the template item is a pro template.
	 * - 'url': The template item URL.
	 * - 'author': The template item author.
	 * - 'categories': The template item categories array.
	 *
	 * @param array $template_data The template item data array.
	 * @return array The prepared template item data array.
	 */
	private function prepare_template(array $template_data): array
	{
		$type = $template_data['type'] ?? 'section';

		return [
			'template_id'   => (int)($template_data['id'] ?? 0),
			'source'        => $this->get_id(),
			'title'         => $template_data['title'] ?? esc_html__('Untitled', 'elementify-addons-for-elementor'),
			'type'          => $type,
			'template_type' => $type,
			'thumbnail'     => $template_data['thumbnail'] ?? '',
			'date'          => (int)($template_data['created_at'] ?? time()),
			'tags'          => (array)($template_data['tags'] ?? []),
			'isPro'         => !empty($template_data['is_pro']),
			'url'           => $template_data['url'] ?? '',
			'author'        => $template_data['author'] ?? 'Elementify Addons',
			'categories'    => (array)($template_data['categories'] ?? []),
		];
	}

	/**
	 * Requests the library data from the remote API.
	 *
	 * If the data is already cached and not expired, it will be returned from the cache.
	 * If the data is expired or not present in the cache, it will be requested from the remote API.
	 * The function will return null if the request fails or if the data is invalid.
	 *
	 * @param bool $force_update Whether to force update the cache.
	 * @return array|null The library data or null if the request fails.
	 */
	private static function request_library_data(bool $force_update = false): ?array
	{
		$cached = get_transient(self::LIBRARY_CACHE_KEY);

		if (
			!$force_update &&
			$cached !== false &&
			is_array($cached) &&
			!empty($cached['templates'])
		) {
			return $cached;
		}

		$response = wp_remote_get(
			self::API_TEMPLATES_INFO_URL,
			[
				'timeout'     => 20,
				'sslverify'   => true,
				'headers'     => ['Accept' => 'application/json'],
				'user-agent'  => 'ElementifyLibrary/1.0 (WordPress; ' . esc_url_raw(home_url()) . ')',
			]
		);

		if (is_wp_error($response)) {
			error_log('[Elementify Library] ' . $response->get_error_message());
			set_transient(self::LIBRARY_CACHE_KEY, [], self::CACHE_ERROR_EXPIRATION);
			return null;
		}

		$code = wp_remote_retrieve_response_code($response);
		$body = wp_remote_retrieve_body($response);

		if (200 !== $code || empty($body)) {
			error_log("[Elementify Library] HTTP Error: $code");
			set_transient(self::LIBRARY_CACHE_KEY, [], self::CACHE_ERROR_EXPIRATION);
			return null;
		}

		$data = json_decode($body, true);

		if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
			error_log('[Elementify Library] Invalid JSON response');
			set_transient(self::LIBRARY_CACHE_KEY, [], self::CACHE_ERROR_EXPIRATION);
			return null;
		}

		set_transient(self::LIBRARY_CACHE_KEY, $data, self::CACHE_EXPIRATION);

		return $data;
	}

	/**
	 * Retrieves the data for the templates library.
	 *
	 * This function retrieves the data for the templates library by making a request to the Elementify Addons API.
	 * If the data is already cached, it will be returned from the cache unless the $force_update parameter is set to true.
	 *
	 * @param bool $force_update Whether to force an update of the cache.
	 * @return array The data for the templates library.
	 */
	public static function get_library_data(bool $force_update = false): array
	{
		$data = self::request_library_data($force_update);
		return is_array($data) ? $data : [];
	}

	public function get_item($template_id): array
	{
		$template_id = (int) $template_id;

		foreach ($this->get_items() as $template) {
			if ((int) $template['template_id'] === $template_id) {
				return $template;
			}
		}

		return [];
	}

	public static function request_template_data(int $template_id): ?string
	{
		if ($template_id <= 0) {
			return null;
		}

		$url = self::API_TEMPLATE_DATA_URL . $template_id;

		$response = wp_remote_get(
			$url,
			[
				'timeout'    => 40,
				'sslverify'  => true,
				'user-agent' => 'ElementifyLibrary/1.0 (WordPress; ' . esc_url_raw(home_url()) . ')',
			]
		);

		if (is_wp_error($response)) {
			error_log('[Elementify Template] ' . $response->get_error_message());
			return null;
		}

		$code = wp_remote_retrieve_response_code($response);

		if (200 !== $code) {
			error_log("[Elementify Template] HTTP $code for template #$template_id");
			return null;
		}

		return wp_remote_retrieve_body($response);
	}

	/**
	 * Retrieves template data from the Elementor Addons API.
	 *
	 * This function retrieves the template data for the given template ID from the Elementor Addons API.
	 * If the template data is not available, it will throw an exception.
	 * The function will process the retrieved data by replacing the element IDs and processing the export/import content.
	 *
	 * @param array $args The function arguments.
	 * @param string $context The context of the function. Defaults to 'display'.
	 * @return array The processed template data.
	 * @throws Exception If the template data is not available.
	 */
	public function get_data(array $args, $context = 'display'): array
	{
		$template_id = (int)($args['template_id'] ?? 0);

		$raw_data = self::request_template_data($template_id);

		if (!$raw_data) {
			throw new Exception(
				__('Failed to retrieve template data from server.', 'elementify-addons-for-elementor')
			);
		}

		$data = json_decode($raw_data, true);

		if (!is_array($data) || empty($data['content'])) {
			throw new Exception(
				__('Template does not have any content', 'elementify-addons-for-elementor')
			);
		}

		$data['content'] = $this->replace_elements_ids($data['content']);
		$data['content'] = $this->process_export_import_content($data['content'], 'on_import');

		return $data;
	}

	/**
	 * Clears the cache for the template library.
	 *
	 * This function is used to clear the cache after the template library has been updated.
	 */
	public static function clear_cache(): void
	{
		delete_transient(self::LIBRARY_CACHE_KEY);
	}
}
