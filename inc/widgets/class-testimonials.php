<?php

/**
 * Testimonials Widget.
 *
 * @package elementify-addons-for-elementor
 * @since 1.0.0
 */

namespace Elementify_Addons_For_Elementor\Inc\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementify_Addons_For_Elementor\Inc\Utils as ElementifyUtils;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Testimonials Widget
 *
 * @since 1.0.0
 */
class Testimonials extends Widget_Base
{
    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name(): string
    {
        return 'eae-testimonials';
    }

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title(): string
    {
        return esc_html__('Testimonials', 'elementify-addons-for-elementor');
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon(): string
    {
        return 'eae-icon-testimonials';
    }

    /**
     * Get widget categories.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories(): array
    {
        return ['elementify-addons-for-elementor-category'];
    }

    /**
     * Get style dependencies.
     *
     * @since 1.0.0
     * @access public
     * @return array CSS style handles.
     */
    public function get_style_depends(): array
    {
        return ['swiper', 'elementify-addons-for-elementor-widget'];
    }

    /**
     * Get script dependencies.
     *
     * @since 1.0.0
     * @access public
     * @return array JS script handles.
     */
    public function get_script_depends(): array
    {
        return ['swiper', 'elementify-addons-for-elementor-widget'];
    }

    /**
     * Get widget keywords.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords(): array
    {
        return ['elementify', 'testimonial', 'review', 'quote', 'rating', 'recommendation'];
    }

    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls(): void
    {
        $this->register_content_controls();
        $this->register_slider_controls();
        $this->register_style_controls();
    }

    /**
     * Register content controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_content_controls(): void
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'author',
            [
                'label'       => esc_html__('Author', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'avatar',
            [
                'label'       => esc_html__('Avatar', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'headline',
            [
                'label'       => esc_html__('Headline', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'quote',
            [
                'label'       => esc_html__('Quote', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'rating',
            [
                'label'       => esc_html__('Rating', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 5,
                'min'         => 0,
                'max'         => 5,
                'step'        => 0.5,
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'   => esc_html__('Link', 'elementify-addons-for-elementor'),
                'type'    => Controls_Manager::URL,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'label'       => esc_html__('Testimonials', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::REPEATER,
                'default'     => [
                    [
                        'author' => esc_html__('John Doe', 'elementify-addons-for-elementor'),
                        'quote'  => esc_html__('Outstanding service and supportive team!', 'elementify-addons-for-elementor'),
                        'rating' => 5,
                    ],
                    [
                        'author' => esc_html__('Jane Smith', 'elementify-addons-for-elementor'),
                        'quote'  => esc_html__('Fantastic experience, highly recommended!', 'elementify-addons-for-elementor'),
                        'rating' => 4.5,
                    ],
                    [
                        'author' => esc_html__('Michael Brown', 'elementify-addons-for-elementor'),
                        'quote'  => esc_html__('Professional and efficient, exceeded expectations.', 'elementify-addons-for-elementor'),
                        'rating' => 4,
                    ],
                    [
                        'author' => esc_html__('Emily Davis', 'elementify-addons-for-elementor'),
                        'quote'  => esc_html__('Great attention to detail and support.', 'elementify-addons-for-elementor'),
                        'rating' => 4.5,
                    ],
                ],
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ author }}}',
            ]
        );

        $this->add_control(
            'headline_tag',
            [
                'label'   => esc_html__('Headline Tag', 'elementify-addons-for-elementor'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                ],
                'default'   => 'h3',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'author_tag',
            [
                'label'   => esc_html__('Author Tag', 'elementify-addons-for-elementor'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                ],
                'default'   => 'h4',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'author_link',
            [
                'label'     => esc_html__('Enable Author Link', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'pagination',
            [
                'label'     => esc_html__('Show Pagination', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label'     => esc_html__('Show Navigation', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label'   => esc_html__('Card Alignment', 'elementify-addons-for-elementor'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'center'     => [
                        'title' => esc_html__('Center', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__('Bottom', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials-swiper .swiper-wrapper' => 'align-items: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register slider controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_slider_controls(): void
    {
        $this->start_controls_section(
            'js_section',
            [
                'label' => esc_html__('Slider', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'slide_limit',
            [
                'label'           => esc_html__('Slides Per View', 'elementify-addons-for-elementor'),
                'type'            => Controls_Manager::SELECT,
                'options'         => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'],
                'desktop_default' => '2',
                'tablet_default'  => '2',
                'mobile_default'  => '1',
            ]
        );

        $this->add_responsive_control(
            'slide_gap',
            [
                'label'           => esc_html__('Slide Gap', 'elementify-addons-for-elementor'),
                'type'            => Controls_Manager::NUMBER,
                'min'             => 0,
                'max'             => 100,
                'desktop_default' => 15,
                'tablet_default'  => 10,
                'mobile_default'  => 10,
                'description'     => esc_html__('Gap between slides in pixels.', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'speed',
            [
                'label'       => esc_html__('Transition Speed', 'elementify-addons-for-elementor') . ' (ms)',
                'type'        => Controls_Manager::NUMBER,
                'default'     => 1200,
                'min'         => 100,
                'max'         => 5000,
                'description' => esc_html__('Duration of slide transition in milliseconds.', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'   => esc_html__('Autoplay', 'elementify-addons-for-elementor'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'delay',
            [
                'label'       => esc_html__('Autoplay Delay', 'elementify-addons-for-elementor') . ' (ms)',
                'type'        => Controls_Manager::NUMBER,
                'default'     => 5000,
                'min'         => 1000,
                'max'         => 10000,
                'description' => esc_html__('Delay between slide transitions in milliseconds.', 'elementify-addons-for-elementor'),
                'condition'   => ['autoplay' => 'yes'],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label'   => esc_html__('Infinite Loop', 'elementify-addons-for-elementor'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label'     => esc_html__('Pause on Hover', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'condition' => ['autoplay' => 'yes'],
            ]
        );

        $this->add_control(
            'center_slide',
            [
                'label'   => esc_html__('Center Slides', 'elementify-addons-for-elementor'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register style controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_style_controls(): void
    {
        $this->start_controls_section(
            'style_card_section',
            [
                'label' => esc_html__('Card', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_background',
                'types'    => ['classic', 'gradient'],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .eae-testimonials__item',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .eae-testimonials__item',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top'      => 15,
                    'right'    => 15,
                    'bottom'   => 15,
                    'left'     => 15,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top'      => 20,
                    'right'    => 20,
                    'bottom'   => 20,
                    'left'     => 20,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'headline_heading',
            [
                'label'     => esc_html__('Headline', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'headline_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'global'    => ['default' => Global_Colors::COLOR_SECONDARY],
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials__headline' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'headline_typo',
                'global'   => ['default' => Global_Typography::TYPOGRAPHY_PRIMARY],
                'selector' => '{{WRAPPER}} .eae-testimonials__headline',
            ]
        );

        $this->add_control(
            'quote_heading',
            [
                'label'     => esc_html__('Quote', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'global'    => ['default' => Global_Colors::COLOR_TEXT],
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials__entry-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'quote_typo',
                'global'   => ['default' => Global_Typography::TYPOGRAPHY_TEXT],
                'selector' => '{{WRAPPER}} .eae-testimonials__entry-content',
            ]
        );

        $this->add_control(
            'avatar_heading',
            [
                'label'     => esc_html__('Avatar', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'avatar_size',
            [
                'label'      => esc_html__('Size', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'range'      => [
                    'px' => ['min' => 20, 'max' => 100],
                    '%'  => ['min' => 0, 'max' => 100],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials__author-avatar-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'avatar_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top'      => 50,
                    'right'    => 50,
                    'bottom'   => 50,
                    'left'     => 50,
                    'unit'     => '%',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials__author-avatar-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'avatar_shadow',
                'selector' => '{{WRAPPER}} .eae-testimonials__author-avatar-wrap',
            ]
        );

        $this->add_control(
            'author_heading',
            [
                'label'     => esc_html__('Author', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'author_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'global'    => ['default' => Global_Colors::COLOR_SECONDARY],
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials__author-name' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'author_typo',
                'global'   => ['default' => Global_Typography::TYPOGRAPHY_PRIMARY],
                'selector' => '{{WRAPPER}} .eae-testimonials__author-name',
            ]
        );

        $this->add_control(
            'link_heading',
            [
                'label'     => esc_html__('Link', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials__author-link' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'link_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top'      => 50,
                    'right'    => 50,
                    'bottom'   => 50,
                    'left'     => 50,
                    'unit'     => '%',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials__author-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_pagination_section',
            [
                'label' => esc_html__('Pagination', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'pagination_normal_color',
            [
                'label'     => esc_html__('Normal Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials-swiper .swiper-pagination-bullet' => '--swiper-pagination-bullet-inactive-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_color',
            [
                'label'     => esc_html__('Active Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials-swiper .swiper-pagination-bullet-active' => '--swiper-pagination-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_navigation_section',
            [
                'label' => esc_html__('Navigation', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'navigation_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials-swiper .swiper-button-next::after, {{WRAPPER}} .eae-testimonials-swiper .swiper-button-prev::after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'navigation_bg_color',
            [
                'label'     => esc_html__('Background Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials-swiper .swiper-button-next, {{WRAPPER}} .eae-testimonials-swiper .swiper-button-prev' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top'      => 5,
                    'right'    => 5,
                    'bottom'   => 5,
                    'left'     => 5,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-testimonials-swiper .swiper-button-next' => 'border-radius: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
                    '{{WRAPPER}} .eae-testimonials-swiper .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Sanitize text input.
     *
     * @since 1.0.0
     * @access protected
     * @param string $text Input text to sanitize.
     * @return string Sanitized text.
     */
    protected function sanitize_text(string $text): string
    {
        return sanitize_text_field(wp_strip_all_tags($text));
    }

    /**
     * Get author image HTML.
     *
     * @since 1.0.0
     * @access protected
     * @param array $testimonial Testimonial data.
     * @return string Image HTML.
     */
    protected function get_author_image(array $testimonial): string
    {
        if (empty($testimonial['avatar']['url'])) {
            return '';
        }

        $image_src = esc_url($testimonial['avatar']['url']);
        $image_id = attachment_url_to_postid($image_src);
        $settings['image_data'] = ElementifyUtils::get_image_data($image_id, $image_src, 'thumbnail');

        return Group_Control_Image_Size::get_attachment_image_html($settings, 'thumbnail', 'image_data');
    }

    /**
     * Render star rating.
     *
     * @since 1.0.0
     * @access protected
     * @param float $rating Rating value.
     * @return string Rating HTML.
     */
    protected function render_star_rating(float $rating): string
    {
        $percentage = ($rating / 5) * 100;
        return sprintf(
            '<div class="eae-testimonials__star-ratings">
                <div class="eae-testimonials__star-ratings-top" style="width: %1$s%%;">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                </div>
                <div class="eae-testimonials__star-ratings-bottom">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                </div>
            </div>',
            esc_attr($percentage)
        );
    }

    /**
     * Render widget output.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        if (empty($settings['testimonials'])) {
            return;
        }

        $headline_tag = Utils::validate_html_tag($settings['headline_tag']);
        $author_tag = Utils::validate_html_tag($settings['author_tag']);

        // Retrieve responsive slide limit settings
        $limits = [
            'desktop' => absint($this->get_settings_for_display('slide_limit')) ?: 3,
            'tablet'  => absint($this->get_settings_for_display('slide_limit_tablet')) ?: 2,
            'mobile'  => absint($this->get_settings_for_display('slide_limit_mobile')) ?: 1,
        ];
        $gaps = [
            'desktop' => absint($this->get_settings_for_display('slide_gap')) ?: 20,
            'tablet'  => absint($this->get_settings_for_display('slide_gap_tablet')) ?: 10,
            'mobile'  => absint($this->get_settings_for_display('slide_gap_mobile')) ?: 0,
        ];
        $attrs = array(
            'slidesPerView' => $limits,
            'spaceBetween'  => $gaps,
            'speed'         => $settings['speed'],
            'delay'         => $settings['delay'],
            'loop'          => (! empty($settings['loop'])) ? true : false,
            'autoplay'      => (! empty($settings['autoplay'])) ? true : false,
            'pauseOnHover'  => (! empty($settings['pause_on_hover'])) ? true : false,
            'pagination'    => (! empty($settings['pagination'])) ? true : false,
            'navigation'    => (! empty($settings['navigation'])) ? true : false,
            'centeredSlides' => (! empty($settings['center_slide'])) ? true : false,
        );

        $this->add_render_attribute('eae-wrapper', [
            'class'         => 'eae-testimonials',
            'data-settings' => wp_json_encode($attrs),
        ]);
?>
        <div <?php $this->print_render_attribute_string('eae-wrapper'); ?>>
            <?php if (!empty($settings['testimonials'])): ?>
                <div class="swiper eae-testimonials-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($settings['testimonials'] as $index => $item):
                            $this->add_render_attribute("link_{$index}", ['class' => 'eae-testimonials__author-link']);
                            if (!empty($item['link']['url'])) {
                                $this->add_link_attributes("link_{$index}", $item['link']);
                            }
                        ?>
                            <div class="swiper-slide eae-testimonials__item">
                                <div class="eae-testimonials__content">
                                    <?php if (!empty($item['headline'])): ?>
                                        <<?php echo esc_html($headline_tag); ?> class="eae-testimonials__headline">
                                            <?php echo esc_html($this->sanitize_text($item['headline'])); ?>
                                        </<?php echo esc_html($headline_tag); ?>>
                                    <?php endif; ?>

                                    <?php if (!empty($item['quote'])): ?>
                                        <div class="eae-testimonials__entry-content">
                                            <p><?php echo wp_kses_post($item['quote']); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="eae-testimonials__author-info">
                                    <div class="eae-testimonials__author-detail">
                                        <?php if (!empty($item['avatar']['url'])): ?>
                                            <figure class="eae-testimonials__author-avatar-wrap">
                                                <?php echo wp_kses_post($this->get_author_image($item)); ?>
                                            </figure>
                                        <?php endif; ?>

                                        <div class="eae-testimonials__author-review-name">
                                            <?php if (!empty($item['author'])): ?>
                                                <<?php echo esc_html($author_tag); ?> class="eae-testimonials__author-name">
                                                    <?php echo esc_html($this->sanitize_text($item['author'])); ?>
                                                </<?php echo esc_html($author_tag); ?>>
                                            <?php endif; ?>
                                            <?php if (!empty($item['rating'])): ?>
                                                <span class="eae-testimonials__author-review">
                                                    <?php echo wp_kses_post($this->render_star_rating(floatval($item['rating']))); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if (!empty($item['link']['url']) && $settings['author_link'] === 'yes'): ?>
                                        <a <?php $this->print_render_attribute_string("link_{$index}"); ?>>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-arrow-right">
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                                <polyline points="12 5 19 12 12 19"></polyline>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (!empty($settings['pagination'])): ?>
                        <div class="swiper-pagination"></div>
                    <?php endif; ?>
                    <?php if (!empty($settings['navigation'])): ?>
                        <div class="eae-testimonials__navigation">
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
<?php

    }
}
