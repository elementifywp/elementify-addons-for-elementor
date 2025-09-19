<?php

/**
 * Portfolio Widget.
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
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementify_Addons_For_Elementor\Inc\Utils as ElementifyUtils;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Portfolio Widget
 *
 * @since 1.0.0
 */
class Portfolio extends Widget_Base
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
        return 'eae-portfolio';
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
        return esc_html__('Portfolio', 'elementify-addons-for-elementor');
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
        return 'eae-icon-portfolio';
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
        return ['elementify-addons-for-elementor-widget'];
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
        return ['isotope', 'packery', 'elementify-addons-for-elementor-widget'];
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
        return ['elementify', 'portfolio', 'grid', 'masonry'];
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
        $this->register_filter_controls();
        $this->register_card_controls();
        $this->register_filter_style_controls();
        $this->register_title_style_controls();
        $this->register_desc_style_controls();
        $this->register_label_style_controls();
        $this->register_skill_style_controls();
        $this->register_button_style_controls();
        $this->register_social_style_controls();
        $this->register_card_style_controls();
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

        // Parent repeater
        $repeater = new Repeater();

        // Child repeater inside parent repeater
        $child_cats = new Repeater();
        $child_cats->add_control(
            'cat_name',
            [
                'label'         => esc_html__('Category', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => ['active' => true],
                'label_block'   => true,
            ]
        );
        $child_cats->add_control(
            'cat_icon',
            [
                'label' => esc_html__('Icon', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::ICONS,
            ]
        );
        $repeater->add_control(
            'item_cats',
            [
                'label'         => esc_html__('Categories', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $child_cats->get_controls(),
                'default'     => [
                    [
                        'cat_name' => esc_html__('Blog', 'elementify-addons-for-elementor'),
                    ],
                ],
                'title_field' => '{{{ elementor.helpers.renderIcon( this, cat_icon, {}, "i", "panel" ) }}} {{{ cat_name }}}',
                'item_actions'  => [
                    'add'       => false,
                ],
            ]
        );

        $repeater->add_control(
            'item_primary_img',
            [
                'label'   => esc_html__('Feature Image', 'elementify-addons-for-elementor'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic'     => ['active' => true],
                'separator'   => 'before',
            ]
        );

        $repeater->add_control(
            'item_label',
            [
                'label'       => esc_html__('Label', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Free', 'elementify-addons-for-elementor'),
                'dynamic'     => ['active' => true],
                'label_block' => true,
                'separator'   => 'before',
            ]
        );

        $repeater->add_control(
            'item_title',
            [
                'label'       => esc_html__('Title', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Title', 'elementify-addons-for-elementor'),
                'dynamic'     => ['active' => true],
                'label_block' => true,
                'separator'   => 'before',
            ]
        );

        $repeater->add_control(
            'item_desc',
            [
                'label'       => esc_html__('Description', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Description', 'elementify-addons-for-elementor'),
                'dynamic'     => ['active' => true],
                'label_block' => true,
                'separator'   => 'before',
            ]
        );

        $repeater->add_control(
            'item_button_text',
            [
                'label'       => esc_html__('Button Text', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('View', 'elementify-addons-for-elementor'),
                'dynamic'     => ['active' => true],
                'label_block' => true,
                'separator'   => 'before',
            ]
        );
        $repeater->add_control(
            'item_button_url',
            [
                'label'       => esc_html__('Button Url', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'http://your-link.com',
                'default' => [
                    'url' => '#',
                ],
                'dynamic' => ['active' => true],
                'separator'   => 'before',
            ]
        );

        // Child repeater inside parent repeater
        $child_logos = new Repeater();
        $child_logos->add_control(
            'logo_icon',
            [
                'label' => esc_html__('Icon', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::ICONS,
            ]
        );
        $child_logos->add_control(
            'logo_url',
            [
                'label'       => esc_html__('Url', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'http://your-link.com',
                'default'     => [
                    'url' => '#',
                ],
                'dynamic' => ['active' => true],
            ]
        );
        $repeater->add_control(
            'item_logos',
            [
                'label'         => esc_html__('Logos', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $child_logos->get_controls(),
                'default'     => [
                    [
                        'logo_icon' => [
                            'value' => 'fab fa-wordpress',
                            'library' => 'fa-brands',
                        ],
                    ],
                    [
                        'logo_icon' => [
                            'value' => 'fab fa-elementor',
                            'library' => 'fa-brands',
                        ],
                    ],
                ],
                'item_actions'  => [
                    'add'       => false,
                ],
            ]
        );

        $child_skills = new Repeater();
        $child_skills->add_control(
            'skill_title',
            [
                'label'             => esc_html__('Title', 'elementify-addons-for-elementor'),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => ['active' => true],
                'label_block'       => true,
            ]
        );
        $repeater->add_control(
            'item_skills',
            [
                'label'         => esc_html__('Skills', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $child_skills->get_controls(),
                'default'     => [
                    [
                        'skill_title' => esc_html__('React', 'elementify-addons-for-elementor'),
                    ],
                    [
                        'skill_title' => esc_html__('Node.js', 'elementify-addons-for-elementor'),
                    ],
                    [
                        'skill_title' => esc_html__('MangoDB', 'elementify-addons-for-elementor'),
                    ],
                    [
                        'skill_title' => esc_html__('GraphQL', 'elementify-addons-for-elementor'),
                    ],
                ],
                'title_field'   => '{{{ skill_title }}}',
                'item_actions'  => [
                    'add'       => false,
                ],
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => esc_html__('Items', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'item_cats' => [
                            [
                                'cat_name' => esc_html__('Web Development', 'elementify-addons-for-elementor'),
                            ]
                        ],
                        'item_primary_img'    => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'item_title'       => esc_html__('E-Commerce Platform', 'elementify-addons-for-elementor'),
                        'item_desc'        => esc_html__('A modern e-commerce platform built with React and Node.js, featuring real-time inventory management and secure payment processing.', 'elementify-addons-for-elementor'),
                        'item_label'       => esc_html__('Web', 'elementify-addons-for-elementor'),
                        'item_button_text' => esc_html__('View Details', 'elementify-addons-for-elementor'),
                        'item_skills' => [
                            [
                                'skill_title' => esc_html__('React', 'elementify-addons-for-elementor'),
                            ],
                            [
                                'skill_title' => esc_html__('Node.js', 'elementify-addons-for-elementor'),
                            ],
                            [
                                'skill_title' => esc_html__('MangoDB', 'elementify-addons-for-elementor'),
                            ],
                            [
                                'skill_title' => esc_html__('Stripe', 'elementify-addons-for-elementor'),
                            ],
                        ],
                    ],
                    [
                        'item_cats' => [
                            [
                                'cat_name' => esc_html__('Mobile Apps', 'elementify-addons-for-elementor'),
                            ]
                        ],
                        'item_primary_img'    => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'item_title'       => esc_html__('Mobile Banking App', 'elementify-addons-for-elementor'),
                        'item_desc'        => esc_html__('Secure mobile banking application with biometric authentication and real-time transaction monitoring.', 'elementify-addons-for-elementor'),
                        'item_label'       => esc_html__('Mobile', 'elementify-addons-for-elementor'),
                        'item_button_text' => esc_html__('View Details', 'elementify-addons-for-elementor'),
                        'item_skills' => [
                            [
                                'skill_title' => esc_html__('React Native', 'elementify-addons-for-elementor'),
                            ],
                            [
                                'skill_title' => esc_html__('Firebase', 'elementify-addons-for-elementor'),
                            ],
                            [
                                'skill_title' => esc_html__('TypeScript', 'elementify-addons-for-elementor'),
                            ],
                        ],
                    ],
                    [
                        'item_cats' => [
                            [
                                'cat_name' => esc_html__('Design', 'elementify-addons-for-elementor'),
                            ]
                        ],
                        'item_primary_img'    => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'item_title'       => esc_html__('Brand Identity Design', 'elementify-addons-for-elementor'),
                        'item_desc'        => esc_html__('Complete brand identity package including logo design, color palette, and brand guidelines for a tech startup.', 'elementify-addons-for-elementor'),
                        'item_label'       => esc_html__('Design', 'elementify-addons-for-elementor'),
                        'item_button_text' => esc_html__('View Details', 'elementify-addons-for-elementor'),
                        'item_skills' => [
                            [
                                'skill_title' => esc_html__('Figma', 'elementify-addons-for-elementor'),
                            ],
                            [
                                'skill_title' => esc_html__('Adobe Illustrator', 'elementify-addons-for-elementor'),
                            ],
                            [
                                'skill_title' => esc_html__('Photoshop', 'elementify-addons-for-elementor'),
                            ],
                        ],
                    ],
                ],
                'title_field' => '{{{ item_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register filter controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_filter_controls(): void
    {
        $this->start_controls_section(
            'filter_section',
            [
                'label' => esc_html__('Filter', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_filter',
            [
                'label'     => esc_html__('Enable', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'filter_position',
            [
                'label'   => esc_html__('Position', 'elementify-addons-for-elementor'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'vertical'   => esc_html__('Vertical', 'elementify-addons-for-elementor'),
                    'horizontal' => esc_html__('Horizontal', 'elementify-addons-for-elementor'),
                ],
                'default'       => 'horizontal',
                'separator'     => 'before',
                'condition'     => ['enable_filter' => 'yes'],
            ]
        );
        $start = is_rtl() ? 'end' : 'start';
        $end = is_rtl() ? 'start' : 'end';

        $this->add_responsive_control(
            'filter_align_horiz',
            [
                'label' => esc_html__('Justify', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'elementify-addons-for-elementor'),
                        'icon' => "eicon-align-$start-h",
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-align-center-h',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'elementify-addons-for-elementor'),
                        'icon' => "eicon-align-$end-h",
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-align-stretch-h',
                    ],
                ],
                'condition' => [
                    'enable_filter' => 'yes',
                    'filter_position' => 'horizontal',
                ],
                'default' => 'start',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'filter_align_vert',
            [
                'label' => esc_html__('Justify', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-align-center-v',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-align-stretch-v',
                    ],
                ],
                'condition' => [
                    'enable_filter' => 'yes',
                    'filter_position' => 'vertical',
                ],
                'default' => 'start',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'filter_content_alignment',
            [
                'label' => esc_html__('Alignment', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'condition' => [
                    'enable_filter' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio-filter__button-inner' => 'justify-content: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'filter_width',
            [
                'label' => esc_html__('Width', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 500],
                    '%' => ['min' => 10, 'max' => 50],
                    'em' => ['min' => 1, 'max' => 50],
                    'rem' => ['min' => 1, 'max' => 50],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio[data-filter="vertical"]' => '--vertical-tab-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'enable_filter' => 'yes',
                    'filter_position' => 'vertical',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'enable_all_filter',
            [
                'label'     => esc_html__('Show "All" Tab', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'separator' => 'before',
                'condition' => ['enable_filter' => 'yes'],
            ]
        );
        $this->add_control(
            'all_filter_text',
            [
                'label'     => esc_html__('Text for "All" Tab', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__('All Projects', 'elementify-addons-for-elementor'),
                'separator' => 'before',
                'condition' => [
                    'enable_filter'     => 'yes',
                    'enable_all_filter'    => 'yes',
                ],
            ]
        );
        $this->add_control(
            'enable_filter_icon',
            [
                'label'     => esc_html__('Enable Icon', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'separator' => 'before',
                'condition' => ['enable_filter' => 'yes'],
            ]
        );
        $this->add_control(
            'all_filter_icon',
            [
                'label' => esc_html__('All Tab Icon', 'elementify-addons-for-elementor'),
                'description' => esc_html__('Global icon will be replaced with individual category icon.', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::ICONS,
                'separator' => 'before',
                'condition' => [
                    'enable_filter'     => 'yes',
                    'enable_filter_icon'   => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_icon_size',
            [
                'label' => esc_html__('Icon Size', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio-filter__icon' => '--eae-icon-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_filter'     => 'yes',
                    'enable_filter_icon'   => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'filter_icon_gap',
            [
                'label' => esc_html__('Icon Gap', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio-filter__button-inner' => 'gap: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'enable_filter'     => 'yes',
                    'enable_filter_icon'   => 'yes',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'filter_icon_align',
            [
                'label' => esc_html__('Icon Justify', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Start', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-align-center-v',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-align-end-v',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio-filter__button-inner' => 'align-items: {{VALUE}}',
                ],
                'condition' => [
                    'enable_filter'     => 'yes',
                    'enable_filter_icon'   => 'yes',
                ],
                'default' => 'center',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'fitter_icon_offset',
            [
                'label' => esc_html__('Icon Offset', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => -15,
                        'max' => 15,
                    ],
                    'em' => [
                        'min' => -1,
                        'max' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio-filter__button-inner' => '--eae-icon-v-offset: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_filter'     => 'yes',
                    'enable_filter_icon'   => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register grid controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_card_controls(): void
    {
        $this->start_controls_section(
            'card_section',
            [
                'label' => esc_html__('Card', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout',
            [
                'label'   => esc_html__('Layout', 'elementify-addons-for-elementor'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'grid'      => esc_html__('Boxes', 'elementify-addons-for-elementor'),
                    'masonry'   => esc_html__('Masonry', 'elementify-addons-for-elementor'),
                ],
                'default'       => 'grid',
                'separator'     => 'before',
            ]
        );

        $this->add_responsive_control(
            'cols_per_row',
            [
                'label'   => esc_html__('Columns', 'elementify-addons-for-elementor'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'default'        => '3', // Desktop default
                'tablet_default' => '2',
                'mobile_default' => '1',
                'separator'      => 'before',
            ]
        );

        $this->add_responsive_control(
            'cols_gap',
            [
                'label'           => esc_html__('Columns Gap', 'elementify-addons-for-elementor'),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => ['px'],
                'range'           => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'default'         => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'tablet_default'  => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'mobile_default'  => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'description'     => esc_html__('Gap between each column in pixels.', 'elementify-addons-for-elementor'),
                'separator'       => 'before',
                'selectors'       => [
                    '{{WRAPPER}} .eae-portfolio__grid' => '--gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'   => esc_html__('Title Tag', 'elementify-addons-for-elementor'),
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

        $this->end_controls_section();
    }

    /**
     * Register style controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_filter_style_controls(): void
    {
        $this->start_controls_section(
            'style_filter_section',
            [
                'label' => esc_html__('Filter', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'filter_typo',
                'global'        => [
                    'default'   => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector'      => '{{WRAPPER}} .eae-portfolio-filter__button',
            ]
        );

        $this->add_responsive_control(
            'filter_gap',
            [
                'label'         => esc_html__('Gap', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', 'rem'],
                'range'         => [
                    'px'        => ['min' => 0, 'max' => 200],
                    'em'        => ['min' => 0, 'max' => 40, 'step' => 0.1],
                    'rem'       => ['min' => 0, 'max' => 40, 'step' => 0.1],
                ],
                'default'       => [
                    'unit'      => 'px',
                    'size'      => 5,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .eae-portfolio-filter' => 'gap: {{SIZE}}{{UNIT}}',
                ],
                'separator'     => 'before',
            ]
        );

        $this->add_responsive_control(
            'content_distance',
            [
                'label'         => esc_html__('Bottom Spacing', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', 'rem'],
                'range'         => [
                    'px'        => ['min' => 0, 'max' => 100],
                    'em'        => ['min' => 0, 'max' => 40, 'step' => 0.1],
                    'rem'       => ['min' => 0, 'max' => 40, 'step' => 0.1],
                ],
                'default'       => [
                    'unit'      => 'px',
                    'size'      => 20,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .eae-portfolio' => '--eae-content-gap: {{SIZE}}{{UNIT}}',
                ],
                'separator'     => 'before',
            ]
        );

        $this->start_controls_tabs('filters_style');

        $this->start_controls_tab(
            'normal_filter',
            [
                'label' => esc_html__('Normal', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'filter_normal_text_color',
            [
                'label'     => esc_html__('Text Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio-filter__button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'      => 'filter_normal_text_shadow',
                'selector'  => '{{WRAPPER}} .eae-portfolio-filter__button',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name'      => 'filter_normal_text_stroke',
                'selector'  => '{{WRAPPER}} .eae-portfolio-filter__button',
            ]
        );

        $this->add_control(
            'filter_background_divider_before',
            [
                'type'      => Controls_Manager::DIVIDER,
                'style'     => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'filter_background',
                'types'         => ['classic', 'gradient'],
                'exclude'       => ['image'],
                'selector'      => '{{WRAPPER}} .eae-portfolio-filter__button',
            ]
        );

        $this->add_control(
            'filter_background_divider_after',
            [
                'type'      => Controls_Manager::DIVIDER,
                'style'     => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'filter_box_shadow',
                'selector'  => '{{WRAPPER}} .eae-portfolio-filter__button',
            ]
        );
        $this->add_control(
            'filter_border_divider_before',
            [
                'type'      => Controls_Manager::DIVIDER,
                'style'     => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'filter_border',
                'selector'  => '{{WRAPPER}} .eae-portfolio-filter__button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover_filter',
            [
                'label' => esc_html__('Hover', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'filter_hover_text_color',
            [
                'label'     => esc_html__('Text Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio-filter__button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'      => 'filter_hover_text_shadow',
                'selector'  => '{{WRAPPER}} .eae-portfolio-filter__button:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name'      => 'filter_hover_text_stroke',
                'selector'  => '{{WRAPPER}} .eae-portfolio-filter__button:hover',
            ]
        );

        $this->add_control(
            'filter_background_hover_divider_before',
            [
                'type'      => Controls_Manager::DIVIDER,
                'style'     => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'filter_hover_background',
                'types'         => ['classic', 'gradient'],
                'exclude'       => ['image'],
                'selector'      => '{{WRAPPER}} .eae-portfolio-filter__button:hover',
            ]
        );

        $this->add_control(
            'filter_background_hover_divider_after',
            [
                'type'      => Controls_Manager::DIVIDER,
                'style'     => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'filter_hover_box_shadow',
                'selector'  => '{{WRAPPER}} .eae-portfolio-filter__button:hover',
            ]
        );
        $this->add_control(
            'filter_border_hover_divider_before',
            [
                'type'      => Controls_Manager::DIVIDER,
                'style'     => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'filter_hover_border',
                'selector'  => '{{WRAPPER}} .eae-portfolio-filter__button:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'active_filter',
            [
                'label' => esc_html__('Active', 'elementify-addons-for-elementor'),
            ]
        );
        $this->add_control(
            'filter_active_text_color',
            [
                'label'     => esc_html__('Text Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio-filter__button.is--active' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'      => 'filter_active_text_shadow',
                'selector'  => '{{WRAPPER}} .eae-portfolio-filter__button.is--active',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name'      => 'filter_active_text_stroke',
                'selector'  => '{{WRAPPER}} .eae-portfolio-filter__button.is--active',
            ]
        );

        $this->add_control(
            'filter_background_active_divider_before',
            [
                'type'      => Controls_Manager::DIVIDER,
                'style'     => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'filter_active_background',
                'types'         => ['classic', 'gradient'],
                'exclude'       => ['image'],
                'selector'      => '{{WRAPPER}} .eae-portfolio-filter__button.is--active',
            ]
        );

        $this->add_control(
            'filter_background_active_divider_after',
            [
                'type'      => Controls_Manager::DIVIDER,
                'style'     => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'filter_active_box_shadow',
                'selector'  => '{{WRAPPER}} .eae-portfolio-filter__button:active',
            ]
        );
        $this->add_control(
            'filter_border_active_divider_before',
            [
                'type'      => Controls_Manager::DIVIDER,
                'style'     => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'filter_active_border',
                'selector'  => '{{WRAPPER}} .eae-portfolio-filter__button:active',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'filter_border_radius',
            [
                'label'         => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', '%', 'em', 'rem'],
                'default'       => [
                    'top'       => 4,
                    'right'     => 4,
                    'bottom'    => 4,
                    'left'      => 4,
                    'unit'      => 'px',
                    'isLinked'  => true,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .eae-portfolio-filter__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'     => 'before',
            ]
        );

        $this->add_responsive_control(
            'filter_padding',
            [
                'label'         => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', '%', 'em', 'rem'],
                'default'       => [
                    'top'       => 12,
                    'right'     => 15,
                    'bottom'    => 12,
                    'left'      => 15,
                    'unit'      => 'px',
                    'isLinked'  => true,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .eae-portfolio-filter__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'     => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register title style controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_title_style_controls()
    {
        $this->start_controls_section(
            'style_card_title_section',
            [
                'label' => esc_html__('Title', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typo',
                'global'   => ['default' => Global_Typography::TYPOGRAPHY_PRIMARY],
                'selector' => '{{WRAPPER}} .eae-portfolio__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'global'    => ['default' => Global_Colors::COLOR_SECONDARY],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'title_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'global'    => ['default' => Global_Colors::COLOR_PRIMARY],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item:hover .eae-portfolio__title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label'         => esc_html__('Bottom Spacing', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', 'rem'],
                'range'         => [
                    'px'        => ['min' => 0, 'max' => 100],
                    'em'        => ['min' => 0, 'max' => 40, 'step' => 0.1],
                    'rem'       => ['min' => 0, 'max' => 40, 'step' => 0.1],
                ],
                'default'       => [
                    'unit'      => 'px',
                    'size'      => 10,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();
    }


    /**
     * Register description style controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_desc_style_controls()
    {
        $this->start_controls_section(
            'style_desc_section',
            [
                'label' => esc_html__('Description', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typo',
                'global'   => ['default' => Global_Typography::TYPOGRAPHY_TEXT],
                'selector' => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__desc',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'global'    => ['default' => Global_Colors::COLOR_TEXT],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__desc' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'desc_spacing',
            [
                'label'         => esc_html__('Bottom Spacing', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', 'rem'],
                'range'         => [
                    'px'        => ['min' => 0, 'max' => 100],
                    'em'        => ['min' => 0, 'max' => 40, 'step' => 0.1],
                    'rem'       => ['min' => 0, 'max' => 40, 'step' => 0.1],
                ],
                'default'       => [
                    'unit'      => 'px',
                    'size'      => 10,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__desc' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register label style controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_label_style_controls()
    {
        $this->start_controls_section(
            'style_label_section',
            [
                'label' => esc_html__('Label', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typo',
                'global'   => ['default' => Global_Typography::TYPOGRAPHY_TEXT],
                'selector' => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__meta .eae-portfolio__label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'global'    => ['default' => Global_Colors::COLOR_TEXT],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__meta .eae-portfolio__label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_spacing',
            [
                'label'         => esc_html__('Spacing', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', 'rem'],
                'range'         => [
                    'px'        => ['min' => 0, 'max' => 100],
                    'em'        => ['min' => 0, 'max' => 40, 'step' => 0.1],
                    'rem'       => ['min' => 0, 'max' => 40, 'step' => 0.1],
                ],
                'default'       => [
                    'unit'      => 'px',
                    'size'      => 10,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__meta' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'label_background',
                'types'             => ['classic', 'gradient'],
                'exclude'           => ['image'],
                'fields_options'    => [
                    'background'    => ['default' => 'classic'],
                    'color'         => ['default' => 'rgba(0, 0, 0, 0.15)'],
                ],
                'selector'          => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__meta .eae-portfolio__label',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'label_border',
                'selector' => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__meta .eae-portfolio__label',
            ]
        );

        $this->add_responsive_control(
            'label_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top'      => 4,
                    'right'    => 4,
                    'bottom'   => 4,
                    'left'     => 4,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__meta .eae-portfolio__label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_padding',
            [
                'label'      => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top'      => 5,
                    'right'    => 10,
                    'bottom'   => 5,
                    'left'     => 10,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__meta .eae-portfolio__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register skill style controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_skill_style_controls()
    {
        $this->start_controls_section(
            'skill_style_section',
            [
                'label' => esc_html__('Skills', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'skill_typo',
                'global'   => ['default' => Global_Typography::TYPOGRAPHY_TEXT],
                'selector' => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__skill',
            ]
        );

        $this->add_control(
            'skill_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'global'    => ['default' => Global_Colors::COLOR_TEXT],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__skill' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'skill_gap',
            [
                'label'         => esc_html__('Gap', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', 'rem'],
                'range'         => [
                    'px'        => ['min' => 0, 'max' => 100],
                    'em'        => ['min' => 0, 'max' => 40, 'step' => 0.1],
                    'rem'       => ['min' => 0, 'max' => 40, 'step' => 0.1],
                ],
                'default'       => [
                    'unit'      => 'px',
                    'size'      => 10,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__skills' => 'gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'skill_background',
                'types'             => ['classic', 'gradient'],
                'exclude'           => ['image'],
                'fields_options'    => [
                    'background'    => ['default' => 'classic'],
                    'color'         => ['default' => 'rgba(0, 0, 0, 0.15)'],
                ],
                'selector'          => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__skill',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'skill_border',
                'selector' => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__skill',
            ]
        );

        $this->add_responsive_control(
            'skill_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top'      => 4,
                    'right'    => 4,
                    'bottom'   => 4,
                    'left'     => 4,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__skill' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'skill_padding',
            [
                'label'      => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top'      => 5,
                    'right'    => 10,
                    'bottom'   => 5,
                    'left'     => 10,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__body .eae-portfolio__skill' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register button style controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_button_style_controls()
    {
        $this->start_controls_section(
            'style_button_section',
            [
                'label' => esc_html__('Button', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typo',
                'global'   => ['default' => Global_Typography::TYPOGRAPHY_TEXT],
                'selector' => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__media .eae-portfolio__overlay .eae-portfolio__overlay-content .eae-portfolio__button',
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__media .eae-portfolio__overlay .eae-portfolio__overlay-content .eae-portfolio__button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'button_background',
                'types'             => ['classic', 'gradient'],
                'exclude'           => ['image'],
                'fields_options'    => [
                    'background'    => ['default' => 'classic'],
                    'color'         => ['default' => 'rgba(255, 255, 255, 0.4)'],
                ],
                'selector'          => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__media .eae-portfolio__overlay .eae-portfolio__overlay-content .eae-portfolio__button',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__media .eae-portfolio__overlay .eae-portfolio__overlay-content .eae-portfolio__button',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top'      => 50,
                    'right'    => 50,
                    'bottom'   => 50,
                    'left'     => 50,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__media .eae-portfolio__overlay .eae-portfolio__overlay-content .eae-portfolio__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top'      => 8,
                    'right'    => 10,
                    'bottom'   => 8,
                    'left'     => 10,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__media .eae-portfolio__overlay .eae-portfolio__overlay-content .eae-portfolio__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register social style controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_social_style_controls()
    {
        $this->start_controls_section(
            'social_style_section',
            [
                'label' => esc_html__('Socials', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'social_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__media .eae-portfolio__overlay .eae-portfolio__overlay-content .eae-portfolio__logos .eae-portfolio__logo' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_gap',
            [
                'label'         => esc_html__('Gap', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', 'rem'],
                'range'         => [
                    'px'        => ['min' => 0, 'max' => 100],
                    'em'        => ['min' => 0, 'max' => 40, 'step' => 0.1],
                    'rem'       => ['min' => 0, 'max' => 40, 'step' => 0.1],
                ],
                'default'       => [
                    'unit'      => 'px',
                    'size'      => 10,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__media .eae-portfolio__overlay .eae-portfolio__overlay-content .eae-portfolio__logos' => 'gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'social_background',
                'types'             => ['classic', 'gradient'],
                'exclude'           => ['image'],
                'fields_options'    => [
                    'background'    => ['default' => 'classic'],
                    'color'         => ['default' => 'rgba(255, 255, 255, 0.4)'],
                ],
                'selector'          => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__media .eae-portfolio__overlay .eae-portfolio__overlay-content .eae-portfolio__logos .eae-portfolio__logo',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'social_border',
                'selector' => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__media .eae-portfolio__overlay .eae-portfolio__overlay-content .eae-portfolio__logos .eae-portfolio__logo',
            ]
        );

        $this->add_responsive_control(
            'social_border_radius',
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
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__media .eae-portfolio__overlay .eae-portfolio__overlay-content .eae-portfolio__logos .eae-portfolio__logo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'social_padding',
            [
                'label'      => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top'      => 7,
                    'right'    => 7,
                    'bottom'   => 7,
                    'left'     => 7,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item .eae-portfolio__media .eae-portfolio__overlay .eae-portfolio__overlay-content .eae-portfolio__logos .eae-portfolio__logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
    protected function register_card_style_controls(): void
    {
        $this->start_controls_section(
            'style_card_section',
            [
                'label' => esc_html__('Card', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('card_style');

        $this->start_controls_tab(
            'normal_card',
            [
                'label' => esc_html__('Normal', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'card_shadow',
                'selector' => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item',
                'fields_options'    => [
                    'box_shadow_type'   => [
                        'default'       => 'yes',
                    ],
                    'box_shadow'    => [
                        'default'   => [
                            'horizontal' => 0,
                            'vertical'   => 1,
                            'blur'       => 2,
                            'spread'     => 0,
                            'color'      => 'rgba(0, 0, 0, 0.05)',
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_background',
                'types'    => ['classic', 'gradient'],
                'fields_options'    => [
                    'background'    => ['default' => 'classic'],
                    'color'         => ['default' => '#ffffff'],
                ],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .eae-portfolio__item',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .eae-portfolio__item',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover_card',
            [
                'label' => esc_html__('Hover', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'              => 'card_hover_shadow',
                'selector'          => '{{WRAPPER}} .eae-portfolio__content .eae-portfolio__item:hover',
                'fields_options'    => [
                    'box_shadow_type'   => [
                        'default'       => 'yes',
                    ],
                    'box_shadow'    => [
                        'default'   => [
                            'horizontal' => 0,
                            'vertical'   => 0,
                            'blur'       => 0,
                            'spread'     => 0,
                            'color'      => 'rgba(255, 255, 255, 0.985)',
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_hover_background',
                'types'    => ['classic', 'gradient'],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .eae-portfolio__item:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_hover_border',
                'selector' => '{{WRAPPER}} .eae-portfolio__item:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top'      => 4,
                    'right'    => 4,
                    'bottom'   => 4,
                    'left'     => 4,
                    'unit'     => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-portfolio__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                // 'selectors' => [
                //     '{{WRAPPER}} .eae-portfolio__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                // ],
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

    protected function get_image($image, $size = 'full'): string
    {
        if (empty($image) || empty($image['url'])) {
            return '';
        }

        $image_src  = esc_url($image['url']);
        $image_id   = attachment_url_to_postid($image_src);
        $image_data = ElementifyUtils::get_image_data($image_id, $image_src, $size);

        $settings['image_data'] = $image_data;

        return Group_Control_Image_Size::get_attachment_image_html($settings, $size, 'image_data');
    }

    /**
     * Get filters html
     *
     * @return html
     */
    public function render_filters()
    {
        $settings = $this->get_settings_for_display();
        $html = '';

        $category_list = $this->generate_category_data($settings['items']);

        if (empty($category_list) || !$settings['enable_filter']) {
            return false;
        }

        ob_start();
        $separator_html = Icons_Manager::render_icon($settings['all_filter_icon'], ['aria-hidden' => 'true', 'class' => 'eae-portfolio-filter__icon']);
        $separator_html = ob_get_clean();

        $all_label = isset($settings['all_filter_text']) ? $settings['all_filter_text'] : esc_html__('All Projects', 'elementify-addons-for-elementor');

        if ($settings['enable_all_filter']) {
            $html .= sprintf(
                '<button class="eae-portfolio-filter__button is--active" data-filter="*"><span class="eae-portfolio-filter__button-inner">%s %s</span></button>',
                $separator_html,
                $all_label
            );
        }

        foreach ($category_list as $slug => $category) {

            if (!empty($category['icon'] && $category['icon']['value'])) {
                ob_start();
                $separator_html = Icons_Manager::render_icon($category['icon'], ['aria-hidden' => 'true', 'class' => 'eae-portfolio-filter__icon']);
                $separator_html = ob_get_clean();
            } else {
                ob_start();
                $separator_html = Icons_Manager::render_icon($settings['all_filter_icon'], ['aria-hidden' => 'true', 'class' => 'eae-portfolio-filter__icon']);
                $separator_html = ob_get_clean();
            }

            $html .= sprintf(
                '<button class="eae-portfolio-filter__button" data-filter=".eae-%s"><span class="eae-portfolio-filter__button-inner">%s %s</span></button>',
                $slug,
                $separator_html,
                $category['name']
            );
        }

        $this->add_render_attribute('eae-portfolio-filter-wrapper', [
            'class'         => ['eae-portfolio-filter'],
        ]);

        if ($settings['filter_position'] == 'vertical') {
            $desktop_align  = isset($settings['filter_align_vert']) ? $settings['filter_align_vert'] : 'start';
            $tablet_align   = isset($settings['filter_align_vert_tablet']) ? $settings['filter_align_vert_tablet'] : $desktop_align;
            $mobile_align   = isset($settings['filter_align_vert_mobile']) ? $settings['filter_align_vert_mobile'] : $tablet_align;
            $this->add_render_attribute('eae-portfolio-filter-wrapper', [
                'class'         => 'eae-portfolio-filter--vertical',
                'data-align'    => esc_attr($mobile_align),
                'data-align-md' => esc_attr($tablet_align),
                'data-align-lg' => esc_attr($desktop_align),
            ]);
        } else {
            $desktop_align  = isset($settings['filter_align_horiz']) ? $settings['filter_align_horiz'] : 'start';
            $tablet_align   = isset($settings['filter_align_horiz_tablet']) ? $settings['filter_align_horiz_tablet'] : $desktop_align;
            $mobile_align   = isset($settings['filter_align_horiz_mobile']) ? $settings['filter_align_horiz_mobile'] : $tablet_align;
            $this->add_render_attribute('eae-portfolio-filter-wrapper', [
                'class'         => 'eae-portfolio-filter--horizontal',
                'data-align'    => esc_attr($mobile_align),
                'data-align-md' => esc_attr($tablet_align),
                'data-align-lg' => esc_attr($desktop_align),
            ]);
        }
?>
        <div <?php $this->print_render_attribute_string('eae-portfolio-filter-wrapper'); ?>>
            <?php echo wp_kses_post($html); ?>
        </div>
    <?php
    }

    public function render_box_content()
    {
        $settings = $this->get_settings_for_display();

        if (empty($settings['items'])) {
            return;
        }
        $title_tag  = Utils::validate_html_tag($settings['title_tag']);
        $classes    = $settings['layout'] == 'grid' ? 'eae-portfolio__grid eae-d-grid' : 'eae-portfolio__grid eae-columns';
        $this->add_render_attribute('eae-portfolio-card-wrapper', [
            'class'             => $classes,
            'data-preset'       => isset($settings['layout']) ? esc_attr($settings['layout']) : 'grid',
            'data-columns'      => isset($settings['cols_per_row_mobile']) ? esc_attr($settings['cols_per_row_mobile']) : '1',
            'data-columns-md'   => isset($settings['cols_per_row_tablet']) ? esc_attr($settings['cols_per_row_tablet']) : '2',
            'data-columns-lg'   => isset($settings['cols_per_row']) ? esc_attr($settings['cols_per_row']) : '3',
        ]);
    ?>
        <div class="eae-portfolio__content">
            <div <?php $this->print_render_attribute_string('eae-portfolio-card-wrapper'); ?>>

                <?php if ($settings['layout'] == 'masonry') : ?>
                    <div class="eae-grid-sizer"></div>
                <?php endif; ?>

                <?php foreach ($settings['items'] as $index => $item) :
                    // Items
                    $item_key = $this->get_repeater_setting_key('portfolio_item', 'portfolio', $index);
                    $this->add_render_attribute($item_key, [
                        'id'        => 'eae-portfolio-item-' . esc_attr($item['_id']),
                        'class'     => ['eae-portfolio__item eae-column'],
                    ]);
                    if (!empty($item['item_cats'])) {
                        $this->add_render_attribute($item_key, 'class', $this->get_item_slug($item));
                    }

                ?>
                    <div <?php $this->print_render_attribute_string($item_key); ?>>
                        <div class="eae-portfolio__item-inner">
                            <div class="eae-portfolio__media">
                                <?php if (!empty($item['item_primary_img']['url'])) : ?>
                                    <figure class="eae-portfolio__figure">
                                        <?php echo wp_kses_post($this->get_image($item['item_primary_img'])); ?>
                                    </figure>
                                <?php endif; ?>
                                <div class="eae-portfolio__overlay">
                                    <div class="eae-portfolio__overlay-content">
                                        <?php $this->render_logos($item); ?>

                                        <?php if ($item['item_button_text']) :
                                            $this->add_render_attribute('button_' . $index, 'class', 'eae-portfolio__button');
                                            $this->add_link_attributes('button_' . $index, map_deep(wp_unslash($item['item_button_url']), 'sanitize_text_field'));
                                        ?>
                                            <a
                                                <?php $this->print_render_attribute_string('button_' . $index); ?>><?php echo esc_html($item['item_button_text']); ?></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="eae-portfolio__body">
                                <div class="eae-portfolio__meta">
                                    <?php if (!empty($item['item_label'])) { ?>
                                        <span class="eae-portfolio__label">
                                            <?php echo esc_html($item['item_label']); ?>
                                        </span>
                                    <?php } ?>
                                </div>
                                <?php if (!empty($item['item_title'])) { ?>
                                    <<?php echo esc_html($title_tag); ?> class="eae-portfolio__title">
                                        <?php echo esc_html($this->sanitize_text($item['item_title'])); ?>
                                    </<?php echo esc_html($title_tag); ?>>
                                <?php } ?>

                                <?php if (!empty($item['item_desc'])) { ?>
                                    <div class="eae-portfolio__desc">
                                        <?php echo wp_kses_post($item['item_desc']); ?>
                                    </div>
                                <?php } ?>

                                <?php $this->render_skills($item); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Generates a list of categories from the portfolio items array.
     *
     * @since 1.0.0
     *
     * @param array $item_data Array of portfolio items.
     *
     * @return array List of categories, with each category as a key and the category name as the value.
     */
    public function generate_category_data(array $item_data)
    {
        $category_list = [];
        foreach ($item_data as $key => $item) {
            if (!empty($item['item_cats'])) {
                foreach ($item['item_cats'] as $key => $category) {
                    if (!empty($category['cat_name'])) {
                        $slug = sanitize_title($category['cat_name']);
                        if (!array_key_exists($slug, $category_list)) {
                            $category_list[$slug]['name'] = sanitize_text_field($category['cat_name']);
                            $category_list[$slug]['icon'] = $category['cat_icon'];
                        }
                    }
                }
            }
        }

        return $category_list;
    }

    public function render_logos(array $item_data)
    {
        echo '<div class="eae-portfolio__logos">';
        foreach ($item_data['item_logos'] as $key => $item) {
            if (!empty($item['logo_icon']['value'])) {
                $this->add_render_attribute('logo_' . $key, 'class', 'eae-portfolio__logo');
                $this->add_link_attributes('logo_' . $key, map_deep(wp_unslash($item['logo_url']), 'sanitize_text_field'));
        ?>
                <a <?php $this->print_render_attribute_string('logo_' . $key); ?>>
                    <?php Icons_Manager::render_icon($item['logo_icon'], ['aria-hidden' => 'true', 'class' => 'eae-portfolio__logo-icon']); ?>
                </a>
            <?php
            }
        }
        echo '</div>';
    }

    public function render_skills(array $item_data)
    {
        $total_skills = count($item_data['item_skills']);
        $display_limit = 3;

        echo '<div class="eae-portfolio__skills">';

        foreach ($item_data['item_skills'] as $key => $item) {
            // Only display up to the limit
            if ($key >= $display_limit) {
                break;
            }
            ?>
            <span class="eae-portfolio__skill">
                <?php echo esc_html($item['skill_title']); ?>
            </span>
        <?php
        }

        // Show "+X more" if there are more skills than the display limit
        if ($total_skills > $display_limit) {
            $remaining = $total_skills - $display_limit;
        ?>
            <span class="eae-portfolio__skill eae-portfolio__skill--more">
                <?php
                // Ensure $remaining is a non-negative integer
                $remaining = (isset($remaining) && is_numeric($remaining)) ? max(0, absint($remaining)) : 0;

                // Use wp_kses_post for safer output in WordPress context
                printf(
                    /* translators: %s: Number of remaining items */
                    esc_html__('+%s More', 'elementify-addons-for-elementor'),
                    esc_html(number_format_i18n($remaining))
                );
                ?>
            </span>
        <?php
        }

        echo '</div>';
    }

    /**
     * Get a list of item category slugs.
     *
     * @since 1.0.0
     *
     * @param array $item_data Array of portfolio item data.
     *
     * @return array List of item category slugs, e.g. ['eae-category1', 'eae-category2'].
     */
    public function get_item_slug($item_data)
    {
        $slug_list = [];

        if (empty($item_data) || empty($item_data['item_cats'])) {
            return $slug_list;
        }

        $categories = wp_list_pluck($item_data['item_cats'], 'cat_name');

        foreach ($categories as $key => $category) {
            $slug_list[] = 'eae-' . sanitize_title($category);
        }

        return $slug_list;
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

        if (empty($settings['items'])) {
            return;
        }

        // Set default values for gaps
        $cols_gap_mobile = isset($settings['cols_gap_mobile']['size']) ? esc_attr($settings['cols_gap_mobile']['size']) : '15px';
        $cols_gap_tablet = isset($settings['cols_gap_tablet']['size']) ? esc_attr($settings['cols_gap_tablet']['size']) : '15px';
        $cols_gap = isset($settings['cols_gap']['size']) ? esc_attr($settings['cols_gap']['size']) : '15px';
        $filter_position = isset($settings['filter_position']) ? esc_attr($settings['filter_position']) : 'horizontal';

        $this->add_render_attribute('eae-portfolio', [
            'class'         => ['eae-portfolio'],
            'data-layout'   => isset($settings['layout']) ? esc_attr($settings['layout']) : 'grid',
            'data-filter'   => $filter_position,
            'data-gutter-mobile'    => $cols_gap_mobile,
            'data-gutter-tablet'    => $cols_gap_tablet,
            'data-gutter-desktop'   => $cols_gap,
        ]);

        $this->add_render_attribute('eae-portfolio', [
            'class'         => $filter_position == 'vertical' ? 'eae-portfolio--vertical' : 'eae-portfolio--horizontal',
        ]);
        ?>
        <div <?php $this->print_render_attribute_string('eae-portfolio'); ?>>
            <?php $this->render_filters(); ?>
            <?php $this->render_box_content(); ?>
        </div>
<?php
    }
}
