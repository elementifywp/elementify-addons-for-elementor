<?php

/**
 * Plugin Utils.
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
 * Utils class.
 *
 * Handles utility functions for the plugin.
 *
 * @since 1.0.0
 */
class Utils
{
    use Singleton;

    /**
     * Get an array of posts.
     *
     * @since 1.0.0
     * @param array|string $args Arguments for WP_Query.
     * @return array Array of post IDs and titles, or empty array on failure.
     */
    public static function get_posts($args): array
    {
        // Normalize $args to an array
        if (is_string($args)) {
            $args = wp_parse_args($args, ['suppress_filters' => false]);
        } elseif (!is_array($args)) {
            return [];
        }

        // Set default query arguments
        $args = wp_parse_args($args, [
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'suppress_filters' => false,
        ]);

        $query = new \WP_Query($args);
        $items = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $items[get_the_ID()] = get_the_title() ?: __('(no title)', 'elementify-addons-for-elementor');
            }
        }

        wp_reset_postdata();
        return $items;
    }

    /**
     * Gets the widgets category name.
     *
     * @since 1.0.0
     * @return string Category name.
     */
    public static function get_category(): string
    {
        $category = esc_html__('Elementify Addons', 'elementify-addons-for-elementor');
        /**
         * Filter the widget category name.
         *
         * @since 1.0.0
         * @param string $category The default category name.
         */
        return apply_filters('elementify_addons_for_elementor_category', $category);
    }

    /**
     * Get Image Data
     *
     * Returns image data based on image id.
     *
     * @since 0.0.1
     * @access public
     *
     * @param int    $image_id Image ID.
     * @param string $image_url Image URL.
     * @param array  $image_size Image sizes array.
     *
     * @return array $data image data.
     */
    public static function get_image_data($image_id, $image_url, $image_size)
    {

        if (! $image_id && ! $image_url) {
            return false;
        }

        $data = array();

        $image_url = esc_url_raw($image_url);

        if (! empty($image_id)) { // Existing attachment.

            $attachment = get_post($image_id);

            if (is_object($attachment)) {
                $data['id']  = $image_id;
                $data['url'] = $image_url;

                $data['image']       = wp_get_attachment_image($attachment->ID, $image_size, true);
                $data['image_size']  = $image_size;
                $data['caption']     = $attachment->post_excerpt;
                $data['title']       = $attachment->post_title;
                $data['description'] = $attachment->post_content;
            }
        } else { // Placeholder image, most likely.

            if (empty($image_url)) {
                return;
            }

            $data['id']          = false;
            $data['url']         = $image_url;
            $data['image']       = '<img src="' . $image_url . '" alt="" title="" />';
            $data['image_size']  = $image_size;
            $data['caption']     = '';
            $data['title']       = '';
            $data['description'] = '';
        }

        return $data;
    }

    /**
     * Get Elementor templates with optional filtering
     *
     * @param string $type Template type (section, page, etc). Empty for all types.
     * @param bool $grouped Return as grouped array [type => [templates]]
     * @return array Template list as [id => title] or grouped array
     */
    public static function get_elementor_templates($type = '', $grouped = false)
    {
        $templates = [];

        try {
            // Method 1: Use Elementor's API if available (preferred)
            if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->templates_manager) {
                $source = \Elementor\Plugin::$instance->templates_manager->get_source('local');
                $items = $source->get_items(['type' => $type]);

                if (!empty($items)) {
                    if ($grouped) {
                        foreach ($items as $item) {
                            $templates[$item['type']][$item['template_id']] = $item['title'];
                        }
                    } else {
                        $templates = wp_list_pluck($items, 'title', 'template_id');
                    }
                    return $templates;
                }
            }

            // Fallback Method: Direct WP Query if Elementor API fails
            $args = [
                'post_type'      => 'elementor_library',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'       => 'title',
                'order'         => 'ASC',
                'no_found_rows' => true,
            ];

            if (!empty($type)) {
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'elementor_library_type',
                        'field'    => 'slug',
                        'terms'    => $type,
                    ]
                ];
            }

            $posts = get_posts($args);

            if (!empty($posts)) {
                foreach ($posts as $post) {
                    if ($grouped) {
                        $template_type = get_post_meta($post->ID, '_elementor_template_type', true);
                        $templates[$template_type][$post->ID] = esc_html($post->post_title);
                    } else {
                        $templates[$post->ID] = esc_html($post->post_title);
                    }
                }
            }
        } catch (Exception $e) {
            echo wp_kses_post($e->getMessage());
        }

        return $templates;
    }

    /**
     * Retrieve an array of image sizes with their respective width and height.
     *
     * @since 1.0.0
     * @return array Image sizes as [id => label] where label is a string
     *               containing the size name and dimensions in the format
     *               "Size Name (width x height)".
     */
    public static function get_image_sizes()
    {
        global $_wp_additional_image_sizes;

        $sizes  = get_intermediate_image_sizes();
        $result = array();

        foreach ($sizes as $size) {
            if (in_array($size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
                $result[$size] = ucwords(trim(str_replace(array('-', '_'), array(' ', ' '), $size)));
            } else {
                $result[$size] = sprintf(
                    '%1$s (%2$sx%3$s)',
                    ucwords(trim(str_replace(array('-', '_'), array(' ', ' '), $size))),
                    $_wp_additional_image_sizes[$size]['width'],
                    $_wp_additional_image_sizes[$size]['height']
                );
            }
        }

        return array_merge(array('full' => esc_html__('Full', 'elementify-addons-for-elementor'),), $result);
    }

    /**
     * Get default options.
     *
     * @since 1.0.0
     * @return array Default options.
     */
    public static function get_default_options(): array
    {
        $defaults = [
            'animated_text'     => true,
            'form_styler'       => true,
            'logos'             => true,
            'tabs'              => true,
            'testimonials'      => true,
            'portfolio'         => true
        ];

        return apply_filters('elementify_addons_for_elementor_default_options', $defaults);
    }

    /**
     * Get the plugin's saved options.
     *
     * @since 1.0.0
     * @param string $key Optional option key to retrieve a specific value.
     * @return mixed Array of all options or specific option value.
     */
    public static function get_options(string $key = '')
    {
        if (!defined('ELEMENTIFY_ADDONS_FOR_ELEMENTOR_NAME')) {
            return $key ? false : [];
        }

        $options = get_option(ELEMENTIFY_ADDONS_FOR_ELEMENTOR_NAME, []);
        $default_options = self::get_default_options();

        if (!is_array($options)) {
            $options = [];
        }

        if (!empty($key)) {
            return $options[$key] ?? ($default_options[$key] ?? false);
        }

        return array_merge($default_options, $options);
    }

    /**
     * Update the plugin options.
     *
     * @since 1.0.0
     * @param string|array $key_or_data Option key or array of options.
     * @param mixed       $val Value for the option key (if key is provided).
     * @return void
     */
    public static function update_options($key_or_data, $val = ''): void
    {
        if (!defined('ELEMENTIFY_ADDONS_FOR_ELEMENTOR_NAME')) {
            return;
        }

        $options = self::get_options();
        $schema = self::get_settings_schema()['properties'];

        if (is_string($key_or_data) && !empty($key_or_data)) {
            // Sanitize based on schema type
            if (isset($schema[$key_or_data]['type'])) {
                $val = self::sanitize_option($val, $schema[$key_or_data]);
            }
            $options[$key_or_data] = $val;
        } elseif (is_array($key_or_data)) {
            foreach ($key_or_data as $key => $value) {
                if (isset($schema[$key]['type'])) {
                    $key_or_data[$key] = self::sanitize_option($value, $schema[$key]);
                }
            }
            $options = array_merge($options, $key_or_data);
        }

        update_option(ELEMENTIFY_ADDONS_FOR_ELEMENTOR_NAME, $options);
    }

    /**
     * Get settings schema.
     *
     * @since 1.0.0
     * @return array Settings schema conforming to JSON Schema (draft-04).
     */
    public static function get_settings_schema(): array
    {
        $defaults = self::get_default_options();
        $setting_properties = apply_filters(
            'elementify_addons_for_elementor_options_schema',
            [
                'animated_text' => [
                    'type'        => 'boolean',
                    'description' => __('Enable animation text.', 'elementify-addons-for-elementor'),
                    'default'     => $defaults['animated_text'],
                ],
                'form_styler' => [
                    'type'        => 'boolean',
                    'description' => __('Enable from styler.', 'elementify-addons-for-elementor'),
                    'default'     => $defaults['form_styler'],
                ],
                'tabs' => [
                    'type'        => 'boolean',
                    'description' => __('Enable tabs.', 'elementify-addons-for-elementor'),
                    'default'     => $defaults['tabs'],
                ],
                'logos' => [
                    'type'        => 'boolean',
                    'description' => __('Enable logos.', 'elementify-addons-for-elementor'),
                    'default'     => $defaults['logos'],
                ],
                'testimonials' => [
                    'type'        => 'boolean',
                    'description' => __('Enable testimonials.', 'elementify-addons-for-elementor'),
                    'default'     => $defaults['testimonials'],
                ],
                'portfolio' => [
                    'type'        => 'boolean',
                    'description' => __('Enable Portfolio.', 'elementify-addons-for-elementor'),
                    'default'     => $defaults['portfolio'],
                ],
            ]
        );

        return [
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'type'       => 'object',
            'properties' => $setting_properties,
        ];
    }

    /**
     * Sanitize an option value based on its schema.
     *
     * @since 1.0.0
     * @param mixed $value Value to sanitize.
     * @param array $schema Schema for the option.
     * @return mixed Sanitized value.
     */
    private static function sanitize_option($value, array $schema)
    {
        switch ($schema['type']) {
            case 'string':
                $sanitize_callback = $schema['sanitize_callback'] ?? 'sanitize_text_field';
                $value = call_user_func($sanitize_callback, $value);
                if (isset($schema['enum']) && !in_array($value, $schema['enum'], true)) {
                    $value = $schema['default'] ?? '';
                }
                break;
            case 'boolean':
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $schema['default'] ?? false;
                break;
            default:
                $value = $schema['default'] ?? $value;
        }
        return $value;
    }

    /**
     * Get dashboard URL
     * @param $suffix
     */
    public static function get_dashboard_url($suffix = '#/')
    {
        return add_query_arg(['page' => 'elementify-addons-for-elementor' . $suffix], admin_url('admin.php'));
    }

    /**
     * Get white label options.
     *
     * @since 1.0.0
     * @param string $key Optional key to retrieve specific option.
     * @return mixed White label options or specific option value.
     */
    public static function white_label($key = '')
    {
        $plugin_name = apply_filters(
            'elementify_addons_for_elementor_white_label',
            esc_html__('Elementify Addons', 'elementify-addons-for-elementor')
        );

        $options = apply_filters(
            'elementify_addons_for_elementor_white_label_options',
            [
                'admin_menu_page'   => [
                    'page_title'    => esc_html__('Elementify Addons Page', 'elementify-addons-for-elementor'),
                    'menu_title'    => esc_html__('Elementify Addons', 'elementify-addons-for-elementor'),
                    'menu_slug'     => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_NAME,
                    'icon_url'      => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'images/logo.png',
                    'position'      => 58.9,
                ],
                'dashboard' => [
                    'title'         => $plugin_name,
                    'logo'          => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'images/logo.png',
                    'background'    => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'images/dashboard-banner.png',
                    'notice'        => sprintf(
                        /* translators: %s is the plugin name */
                        esc_html__('Thank you for selecting %s for Elementor – your new go-to tool for creating stunning, functional, and super-fast websites without any coding.', 'elementify-addons-for-elementor'),
                        $plugin_name
                    ),
                    'content' => [
                        [
                            'icon'      => '<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_64_322)">
<path d="M25.1169 12.8516C25.1169 6.16217 19.697 0.742249 13.0076 0.742249C6.31812 0.742249 0.898193 6.16217 0.898193 12.8516C0.898193 18.8956 5.32642 23.9053 11.1155 24.8145V16.3521H8.03931V12.8516H11.1155V10.1837C11.1155 7.14899 12.9221 5.47272 15.6892 5.47272C17.0144 5.47272 18.4001 5.70905 18.4001 5.70905V8.68756H16.8728C15.3689 8.68756 14.8997 9.62115 14.8997 10.5787V12.8516H18.2581L17.7209 16.3521H14.8997V24.8145C20.6887 23.9053 25.1169 18.8956 25.1169 12.8516Z" fill="black"/>
</g>
<defs>
<clipPath id="clip0_64_322">
<rect width="25" height="25" fill="white" transform="translate(0.507568 0.351624)"/>
</clipPath>
</defs>
</svg>',
                            'title'     => esc_html__('Join Our Facebook Community', 'elementify-addons-for-elementor'),
                            'text'      => esc_html__('Become part of a larger community!. Connect with fellow designers, showcase your work, gain inspiration, and keep up with exclusive tips and insights from our team and community members.', 'elementify-addons-for-elementor'),
                            'btn_text'  => esc_html__('Join Now', 'elementify-addons-for-elementor'),
                            'btn_link'  => 'https://www.facebook.com/profile.php?id=61576882395082'
                        ],
                        [
                            'icon'    => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M20.9999 12.4236V10.9996C20.9999 8.61269 20.0517 6.3235 18.3638 4.63567C16.676 2.94785 14.3868 1.99963 11.9999 1.99963C9.61294 1.99963 7.32376 2.94785 5.63593 4.63567C3.9481 6.3235 2.99989 8.61269 2.99989 10.9996V12.4236C1.95105 12.8855 1.09269 13.6936 0.568511 14.7127C0.0443322 15.7318 -0.113849 16.9001 0.120447 18.0219C0.354744 19.1437 0.967297 20.151 1.85556 20.8751C2.74382 21.5992 3.85388 21.9962 4.99989 21.9996C5.53032 21.9996 6.03903 21.7889 6.4141 21.4138C6.78918 21.0388 6.99989 20.5301 6.99989 19.9996V13.9996C6.99989 13.4692 6.78918 12.9605 6.4141 12.5854C6.03903 12.2103 5.53032 11.9996 4.99989 11.9996V10.9996C4.99989 9.14312 5.73739 7.36264 7.05014 6.04989C8.3629 4.73713 10.1434 3.99963 11.9999 3.99963C13.8564 3.99963 15.6369 4.73713 16.9496 6.04989C18.2624 7.36264 18.9999 9.14312 18.9999 10.9996V11.9996C18.4695 11.9996 17.9607 12.2103 17.5857 12.5854C17.2106 12.9605 16.9999 13.4692 16.9999 13.9996V19.9996H13.9999C13.7347 19.9996 13.4803 20.105 13.2928 20.2925C13.1052 20.4801 12.9999 20.7344 12.9999 20.9996C12.9999 21.2649 13.1052 21.5192 13.2928 21.7067C13.4803 21.8943 13.7347 21.9996 13.9999 21.9996H18.9999C20.1459 21.9962 21.256 21.5992 22.1442 20.8751C23.0325 20.151 23.645 19.1437 23.8793 18.0219C24.1136 16.9001 23.9554 15.7318 23.4313 14.7127C22.9071 13.6936 22.0487 12.8855 20.9999 12.4236ZM4.99989 19.9996C4.20424 19.9996 3.44118 19.6836 2.87857 19.121C2.31596 18.5583 1.99989 17.7953 1.99989 16.9996C1.99989 16.204 2.31596 15.4409 2.87857 14.8783C3.44118 14.3157 4.20424 13.9996 4.99989 13.9996V19.9996ZM18.9999 19.9996V13.9996C19.7955 13.9996 20.5586 14.3157 21.1212 14.8783C21.6838 15.4409 21.9999 16.204 21.9999 16.9996C21.9999 17.7953 21.6838 18.5583 21.1212 19.121C20.5586 19.6836 19.7955 19.9996 18.9999 19.9996Z" fill="#F8FAFB"/>
</svg>
',
                            'title'     => esc_html__('Need Assistance or Have Inquiries?', 'elementify-addons-for-elementor'),
                            'text'      => esc_html__('We’re here for you – anytime! Whether you’re facing a technical issue or exploring a feature, our dedicated support team is available 24/7 to assist you. Get live help or submit a ticket, and we’ll take care of it.', 'elementify-addons-for-elementor'),
                            'btn_text'  => esc_html__('Get Support', 'elementify-addons-for-elementor'),
                            'btn_link'  => 'https://elementifywp.com/account#!/login'
                        ],
                        [
                            'icon'    => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_403_2901)">
<path d="M12.0002 1.99997C13.3267 2.00432 14.6392 2.27171 15.8617 2.78666C17.0842 3.30161 18.1924 4.05389 19.1222 4.99997H16.0002C15.735 4.99997 15.4807 5.10533 15.2931 5.29286C15.1056 5.4804 15.0002 5.73476 15.0002 5.99997C15.0002 6.26519 15.1056 6.51954 15.2931 6.70708C15.4807 6.89461 15.735 6.99997 16.0002 6.99997H20.1432C20.6357 6.99971 21.1078 6.80397 21.456 6.45578C21.8042 6.10758 22 5.6354 22.0002 5.14297V0.999971C22.0002 0.734754 21.8949 0.4804 21.7073 0.292864C21.5198 0.105328 21.2654 -2.92115e-05 21.0002 -2.92115e-05C20.735 -2.92115e-05 20.4807 0.105328 20.2931 0.292864C20.1056 0.4804 20.0002 0.734754 20.0002 0.999971V3.07797C18.3474 1.58942 16.3128 0.590381 14.1243 0.19272C11.9358 -0.204941 9.67986 0.0144827 7.60902 0.826418C5.53818 1.63835 3.73425 3.01074 2.39924 4.7899C1.06424 6.56905 0.250875 8.68471 0.050233 10.9C0.0373164 11.0392 0.0535488 11.1797 0.097896 11.3123C0.142243 11.4449 0.213733 11.5669 0.307809 11.6704C0.401886 11.7738 0.516487 11.8566 0.644311 11.9133C0.772135 11.9701 0.910378 11.9996 1.05023 12C1.29482 12.0031 1.53177 11.9148 1.71469 11.7524C1.89762 11.59 2.01335 11.3652 2.03923 11.122C2.26185 8.63272 3.40721 6.31667 5.25031 4.62881C7.09342 2.94094 9.50105 2.00326 12.0002 1.99997Z" fill="#F8FAFB"/>
<path d="M22.9512 12.0002C22.7066 11.9971 22.4696 12.0854 22.2867 12.2478C22.1038 12.4102 21.988 12.635 21.9622 12.8782C21.7968 14.7814 21.0891 16.5973 19.9228 18.1104C18.7565 19.6235 17.1807 20.7703 15.3823 21.4148C13.5839 22.0592 11.6383 22.1743 9.7765 21.7463C7.91466 21.3183 6.21465 20.3653 4.87815 19.0002H8.00015C8.26537 19.0002 8.51972 18.8949 8.70726 18.7073C8.8948 18.5198 9.00015 18.2654 9.00015 18.0002C9.00015 17.735 8.8948 17.4806 8.70726 17.2931C8.51972 17.1056 8.26537 17.0002 8.00015 17.0002H3.85715C3.61325 17.0001 3.37172 17.048 3.14635 17.1413C2.92099 17.2346 2.71623 17.3714 2.54376 17.5438C2.3713 17.7163 2.23452 17.9211 2.14124 18.1464C2.04796 18.3718 2.00002 18.6133 2.00015 18.8572V23.0002C2.00015 23.2654 2.10551 23.5198 2.29305 23.7073C2.48058 23.8949 2.73494 24.0002 3.00015 24.0002C3.26537 24.0002 3.51972 23.8949 3.70726 23.7073C3.8948 23.5198 4.00015 23.2654 4.00015 23.0002V20.9222C5.65298 22.4108 7.68756 23.4098 9.87605 23.8075C12.0645 24.2051 14.3205 23.9857 16.3914 23.1738C18.4622 22.3618 20.2661 20.9895 21.6011 19.2103C22.9361 17.4311 23.7495 15.3155 23.9502 13.1002C23.9631 12.9609 23.9468 12.8205 23.9025 12.6879C23.8581 12.5552 23.7867 12.4333 23.6926 12.3298C23.5985 12.2263 23.4839 12.1436 23.3561 12.0868C23.2283 12.0301 23.09 12.0006 22.9502 12.0002H22.9512Z" fill="#F8FAFB"/>
</g>
<defs>
<clipPath id="clip0_403_2901">
<rect width="24" height="24" fill="white"/>
</clipPath>
</defs>
</svg>
',
                            'title'   => esc_html__('Stay Updated', 'elementify-addons-for-elementor'),
                            'text'    => esc_html__('Subscribe to Our Newsletter! Don’t miss out on new feature releases, tutorials, design inspiration, or special offers. Our newsletter is concise, engaging, and always a great read.', 'elementify-addons-for-elementor'),
                            'btn_text' => esc_html__('Subscribe Now', 'elementify-addons-for-elementor'),
                            'btn_link' => 'https://elementifywp.com/subscribe'
                        ],
                        [
                            'icon'      => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_2514_32)">
<path d="M19.9 3.09108C19.777 2.56273 19.5479 2.06491 19.2265 1.6279C18.9052 1.19088 18.4982 0.823806 18.0305 0.548987C17.5629 0.274168 17.0441 0.097351 16.506 0.0292897C15.9678 -0.0387716 15.4214 0.00334488 14.9 0.153078L13.176 0.646078C12.7393 0.771598 12.337 0.995297 12 1.30008C11.663 0.995297 11.2607 0.771598 10.824 0.646078L9.1 0.153078C8.57894 0.00412528 8.03304 -0.0373908 7.49544 0.0310496C6.95784 0.09949 6.43976 0.276459 5.97263 0.551216C5.50551 0.825973 5.09909 1.19279 4.77804 1.62939C4.45699 2.066 4.22801 2.56329 4.105 3.09108C2.9548 3.30034 1.91429 3.90603 1.16433 4.80288C0.414377 5.69972 0.00239598 6.831 0 8.00008L0 15.0001C0.00158786 16.3257 0.528882 17.5965 1.46622 18.5339C2.40356 19.4712 3.6744 19.9985 5 20.0001H11V22.0001H8C7.73478 22.0001 7.48043 22.1054 7.29289 22.293C7.10536 22.4805 7 22.7349 7 23.0001C7 23.2653 7.10536 23.5196 7.29289 23.7072C7.48043 23.8947 7.73478 24.0001 8 24.0001H16C16.2652 24.0001 16.5196 23.8947 16.7071 23.7072C16.8946 23.5196 17 23.2653 17 23.0001C17 22.7349 16.8946 22.4805 16.7071 22.293C16.5196 22.1054 16.2652 22.0001 16 22.0001H13V20.0001H19C20.3256 19.9985 21.5964 19.4712 22.5338 18.5339C23.4711 17.5965 23.9984 16.3257 24 15.0001V8.00008C23.9978 6.83171 23.5865 5.701 22.8376 4.80426C22.0886 3.90752 21.0493 3.30138 19.9 3.09108V3.09108ZM13 3.53108C13.0001 3.31389 13.0709 3.10264 13.2017 2.92926C13.3325 2.75589 13.5162 2.6298 13.725 2.57008L15.45 2.07708C15.7477 1.99193 16.0611 1.97706 16.3655 2.03364C16.6699 2.09022 16.957 2.2167 17.2042 2.40313C17.4514 2.58955 17.652 2.83082 17.7901 3.10794C17.9281 3.38506 18 3.69046 18 4.00008V7.93808C17.9986 8.37203 17.8565 8.79383 17.5951 9.1402C17.3337 9.48656 16.967 9.7388 16.55 9.85908L13 10.8731V3.53108ZM6.8 2.40008C7.04673 2.21433 7.33325 2.08847 7.63695 2.03241C7.94065 1.97636 8.25322 1.99165 8.55 2.07708L10.275 2.57008C10.4838 2.6298 10.6675 2.75589 10.7983 2.92926C10.9291 3.10264 10.9999 3.31389 11 3.53108V10.8731L7.45 9.85908C7.03305 9.7388 6.66634 9.48656 6.4049 9.1402C6.14346 8.79383 6.00139 8.37203 6 7.93808V4.00008C5.99898 3.68941 6.07083 3.38283 6.20976 3.10496C6.3487 2.82709 6.55086 2.58567 6.8 2.40008V2.40008ZM22 15.0001C22 15.7957 21.6839 16.5588 21.1213 17.1214C20.5587 17.684 19.7956 18.0001 19 18.0001H5C4.20435 18.0001 3.44129 17.684 2.87868 17.1214C2.31607 16.5588 2 15.7957 2 15.0001V8.00008C2.00256 7.38182 2.19608 6.77945 2.55409 6.27538C2.91209 5.77131 3.41709 5.39016 4 5.18408V7.93808C4.00245 8.80642 4.28642 9.65055 4.8093 10.3438C5.33218 11.0371 6.06577 11.5421 6.9 11.7831L10.351 12.7701C11.4295 13.0775 12.5725 13.0775 13.651 12.7701L17.102 11.7831C17.9359 11.5417 18.669 11.0366 19.1915 10.3433C19.714 9.6501 19.9977 8.80616 20 7.93808V5.18408C20.5829 5.39016 21.0879 5.77131 21.4459 6.27538C21.8039 6.77945 21.9974 7.38182 22 8.00008V15.0001Z" fill="white"/>
</g>
<defs>
<clipPath id="clip0_2514_32">
<rect width="24" height="24" fill="white"/>
</clipPath>
</defs>
</svg>
',
                            'title'   => __('Docs & Tutorials or Guides & Resources', 'elementify-addons-for-elementor'),
                            'text'    => esc_html__('Become proficient with ElementifyWP — Gradually. Our detailed documentation serves as your primary resource for all things ElementifyWP. Regardless of whether you`re a beginner or diving into more advanced features, you`ll discover all the solutions in our extensive knowledge base, filled with images, videos, and recommended practices.', 'elementify-addons-for-elementor'),
                            'links'   => [
                                [
                                    'title' => esc_html__('Documentation', 'elementify-addons-for-elementor'),
                                    'url'  => 'https://elementifywp.com/elementify-addons-docs'
                                ]
                            ]
                        ]
                    ],
                    'sidebar' => [
                        [
                            'title' => esc_html__('SEO', 'elementify-addons-for-elementor'),
                            'svg'   => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'images/seo.png',
                        ],
                        [
                            'title' => esc_html__('Advanced Animated Text', 'elementify-addons-for-elementor'),
                            'svg'   => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'images/screenshot-2.png',
                        ],
                        [
                            'title' => esc_html__('Form Styler', 'elementify-addons-for-elementor'),
                            'svg'   => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'images/screenshot-3.png',
                        ],
                        [
                            'title' => esc_html__('Logos Carousel', 'elementify-addons-for-elementor'),
                            'svg'   => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'images/screenshot-4.png',
                        ],
                        [
                            'title' => esc_html__('Portfolio Widget', 'elementify-addons-for-elementor'),
                            'svg'   => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'images/screenshot-5.png',
                        ],
                        [
                            'title' => esc_html__('Tabs Widget', 'elementify-addons-for-elementor'),
                            'svg'   => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'images/screenshot-6.png',
                        ],
                        [
                            'title' => esc_html__('Testimonials Carousel', 'elementify-addons-for-elementor'),
                            'svg'   => ELEMENTIFY_ADDONS_FOR_ELEMENTOR_BUILD_PATH_URL . 'images/screenshot-7.png',
                        ],
                    ]
                ],
            ],
        );

        return !empty($key) ? (isset($options[$key]) ? $options[$key] : []) : $options;
    }
}