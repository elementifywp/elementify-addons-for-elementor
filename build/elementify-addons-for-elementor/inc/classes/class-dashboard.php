<?php

/**
 * Admin Dashboard Page.
 *
 * @package elementify-addons-for-elementor
 * @since 1.0.0
 */

namespace Elementify_Addons_For_Elementor\Inc;

use Elementify_Addons_For_Elementor\Inc\Traits\Singleton;
use Elementify_Addons_For_Elementor\Inc\Utils;

defined('ABSPATH') || exit; // Exit if accessed directly.

/**
 * Class Dashboard
 */
class Dashboard
{
    use Singleton;

    /**
     * Menu information.
     *
     * @since 1.0.0
     * @access private
     * @var array $menu_info Admin menu information.
     */
    private $menu_info;

    /**
     * Current screen base.
     *
     * @since 1.0.0
     * @access private
     * @var string|null
     */
    private $current_screen_base = null;

    /**
     * Construct method.
     *
     * Initializes the class and sets up necessary hooks.
     */
    protected function __construct()
    {
        $white_label       = Utils::white_label();
        $this->menu_info   = $white_label['admin_menu_page'] ?? [];
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
        add_action('admin_bar_menu', [$this, 'add_toolbar_items'], 500);
        add_action('admin_menu', [$this, 'add_admin_menu'], 99);
        add_filter('admin_body_class', [$this, 'add_has_sticky_header']);
        add_action('admin_enqueue_scripts', [$this, 'register_admin_assets']);

        // Register settings once, not twice
        add_action('admin_init', [$this, 'register_settings']);
    }

    /**
     * Adds Elementify Addons menu item to the WordPress admin bar.
     *
     * @since 1.0.0
     * @param \WP_Admin_Bar $admin_bar The admin bar object.
     * @return void
     */
    public function add_toolbar_items(\WP_Admin_Bar $admin_bar)
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        $admin_bar->add_menu([
            'id'    => 'elementify-addons-for-elementor',
            'title' => '<div class="eae-icon-addons"></div> ' . sanitize_text_field($this->menu_info['menu_title']),
            'href'  => Utils::get_dashboard_url(),
            'meta'  => [
                'title' => sanitize_text_field($this->menu_info['menu_title']),
            ]
        ]);
    }

    /**
     * Adds the admin menu page.
     *
     * @since 1.0.0
     * @return void
     */
    public function add_admin_menu(): void
    {
        if (empty($this->menu_info)) {
            return;
        }

        $menu_slug = $this->menu_info['menu_slug'] ?? ELEMENTIFY_ADDONS_FOR_ELEMENTOR_NAME;

        add_menu_page(
            sanitize_text_field($this->menu_info['page_title']),
            sanitize_text_field($this->menu_info['menu_title']),
            'manage_options',
            $menu_slug,
            [$this, 'render_page_callback'],
            'dashicons-admin-generic', // Fallback icon
            $this->menu_info['position'] ?? null
        );
    }

    /**
     * Gets the current screen base with caching.
     *
     * @since 1.0.0
     * @return string|null
     */
    private function get_current_screen_base(): ?string
    {
        if (null === $this->current_screen_base) {
            $screen = get_current_screen();
            $this->current_screen_base = $screen->base ?? null;
        }
        return $this->current_screen_base;
    }

    /**
     * Checks if the current screen is the plugin's menu page.
     *
     * @since 1.0.0
     * @return bool True if the current screen is the menu page, false otherwise.
     */
    public function is_menu_page(): bool
    {
        $screen_base = $this->get_current_screen_base();
        $menu_slug = $this->menu_info['menu_slug'] ?? ELEMENTIFY_ADDONS_FOR_ELEMENTOR_NAME;
        $expected_base = 'toplevel_page_' . $menu_slug;

        return $screen_base === $expected_base;
    }

    /**
     * Adds a sticky header class to the admin body.
     *
     * @since 1.0.0
     * @param string $classes Space-separated string of class names.
     * @return string Updated classes with sticky header class if applicable.
     */
    public function add_has_sticky_header(string $classes): string
    {
        if ($this->is_menu_page()) {
            $classes .= ' eae-dashboard-container';
        }

        return trim($classes);
    }

    /**
     * Renders the root div for the React application.
     *
     * @since 1.0.0
     * @return void
     */
    public function render_page_callback(): void
    {
        printf('<div id="%s"></div>', esc_attr(ELEMENTIFY_ADDONS_FOR_ELEMENTOR_NAME));
    }

    /**
     * Registers and enqueues admin assets.
     *
     * @since 1.0.0
     * @param string $hook_suffix The current admin page.
     * @return void
     */
    public function register_admin_assets(): void
    {
        $suffix = is_rtl() ? '-rtl' : '';
        // Enqueue icon font.
        wp_enqueue_style('elementify-addons-for-elementor-icons', ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . "font-icons/index{$suffix}.css", [], filemtime(ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH . "/font-icons/index{$suffix}.css"), 'all');

        $custom_css = "
        #toplevel_page_elementify-addons-for-elementor .wp-menu-image:before {
            content: '\\e001';
            font-family: 'ElementifyWP';
            font-size: 18px;
            line-height: 1;
            color: #fff;
        }
        #toplevel_page_elementify-addons-for-elementor .wp-menu-image img {
            width: 0 !important;
            height: 0 !important;
            display: none !important;
        }";
        wp_add_inline_style('elementify-addons-for-elementor-icons', $custom_css);

        // Only load assets on our plugin page
        if (!$this->is_menu_page()) {
            return;
        }
        // Register styles.
        wp_register_style('elementify-addons-for-elementor-admin', ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . "admin/index{$suffix}.css", ['wp-components'], filemtime(ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH . "/admin/index{$suffix}.css"), 'all');

        // Enqueue Styles.
        wp_enqueue_style('elementify-addons-for-elementor-admin');


        $asset_config_file = sprintf('%s/admin/index.asset.php', ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH);
        if (!file_exists($asset_config_file)) {
            return;
        }

        $editor_asset   = include_once $asset_config_file;
        $js_dependencies = (! empty($editor_asset['dependencies'])) ? $editor_asset['dependencies'] : [];
        $version         = (! empty($editor_asset['version'])) ? $editor_asset['version'] : filemtime($asset_config_file);

        // Register scripts.
        wp_register_script(
            'elementify-addons-for-elementor-admin',
            ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'admin/index.js',
            $js_dependencies,
            $version,
            true
        );

        // Enqueue scripts.
        wp_enqueue_script('elementify-addons-for-elementor-admin');

        /* Localize */
        $localize = apply_filters(
            'elementify_addons_for_elementor_admin_localize',
            [
                'version'     => $version,
                'root_id'     => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_NAME,
                'nonce'       => wp_create_nonce('wp_rest'),
                'store'       => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_NAME . '-store',
                'rest_url'    => esc_url_raw(get_rest_url()),
                'white_label' => Utils::white_label(),
            ]
        );
        wp_localize_script('elementify-addons-for-elementor-admin', 'ElementifyAddonsLocalize', $localize);
    }

    /**
     * Register settings.
     *
     * @since 1.0.0
     * @return void
     */
    public function register_settings(): void
    {
        register_setting(
            'elementify_addons_for_elementor_options_group',
            'elementify_addons_for_elementor_object_options',
            [
                'type'          => 'object',
                'default'       => Utils::get_default_options(),
                'show_in_rest'  => [
                    'schema'    => Utils::get_settings_schema(),
                ],
                'sanitize_callback' => [$this, 'sanitize_settings'],
            ]
        );
    }

    /**
     * Sanitizes and validates the settings input.
     *
     * @param mixed $input The raw, unsanitized settings data.
     * @param array $settings The current settings (before update).
     * @return array The sanitized settings to be saved to the database.
     */
    public function sanitize_settings($input, array $settings): array
    {
        // Initialize with current settings or defaults
        $output = is_array($settings) ? $settings : Utils::get_default_options();

        // If input is not array, return current settings
        if (!is_array($input)) {
            return $output;
        }

        // Define widget settings and their sanitization
        $widget_settings = [
            'animated_text' => 'bool',
            'form_styler'   => 'bool',
            'tabs'          => 'bool',
            'logos'         => 'bool',
            'testimonials'  => 'bool',
            'portfolio'     => 'bool',
        ];

        // Sanitize each widget setting
        foreach ($widget_settings as $key => $type) {
            if (isset($input[$key])) {
                $output[$key] = $this->sanitize_setting($input[$key], $type);
            }
        }

        return $output;
    }

    /**
     * Sanitize individual setting based on type.
     *
     * @param mixed $value The value to sanitize.
     * @param string $type The type of sanitization to apply.
     * @return mixed The sanitized value.
     */
    private function sanitize_setting($value, string $type)
    {
        switch ($type) {
            case 'bool':
                return (bool) $value;

            case 'int':
                return absint($value);

            case 'float':
                return floatval($value);

            case 'text':
                return sanitize_text_field($value);

            case 'textarea':
                return sanitize_textarea_field($value);

            case 'html':
                $allowed_html = wp_kses_allowed_html('post');
                return wp_kses($value, $allowed_html);

            case 'url':
                return esc_url_raw($value);

            case 'email':
                return sanitize_email($value);

            case 'key':
                return sanitize_key($value);

            default:
                return is_scalar($value) ? sanitize_text_field($value) : $value;
        }
    }
}
