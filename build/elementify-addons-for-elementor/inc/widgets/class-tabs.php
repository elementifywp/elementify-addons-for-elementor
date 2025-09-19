<?php

/**
 * Tabs Widget.
 *
 * @package elementify-addons-for-elementor
 * @since 1.0.0
 */

namespace Elementify_Addons_For_Elementor\Inc\Widgets;

use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use Elementify_Addons_For_Elementor\Inc\Utils as ElementifyUtils;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Tabs extends Widget_Base
{

    /**
     * Get widget name.
     */
    public function get_name(): string
    {
        return 'eae-tabs';
    }

    /**
     * Get widget title.
     */
    public function get_title(): string
    {
        return esc_html__('Tabs', 'elementify-addons-for-elementor');
    }

    /**
     * Get widget icon.
     */
    public function get_icon(): string
    {
        return 'eae-icon-tabs';
    }

    /**
     * Get widget categories.
     */
    public function get_categories(): array
    {
        return ['elementify-addons-for-elementor-category'];
    }

    /**
     * Retrieve Widget Dependent CSS.
     */
    public function get_style_depends()
    {
        return ['elementify-addons-for-elementor-widget'];
    }

    /**
     * Retrieve Widget Dependent JS.
     */
    public function get_script_depends()
    {
        return ['elementify-addons-for-elementor-widget'];
    }

    /**
     * Get widget keywords.
     */
    public function get_keywords(): array
    {
        return ['elementify', 'tabs', 'accordion', 'toggle'];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls(): void
    {
        $this->register_content_controls();
        $this->register_title_controls();
        $this->register_style_tabs_controls();
        $this->register_style_title_controls();
        $this->register_style_icon_controls();
        $this->register_style_content_controls();
    }

    /**
     * Register content controls.
     */
    protected function register_content_controls()
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
            'tab_title',
            [
                'label'       => esc_html__('Tab Title', 'elementify-addons-for-elementor'),
                'dynamic'     => ['active' => true],
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__('Tab Title', 'elementify-addons-for-elementor'),
                'separator' => 'before',
            ]
        );

        $repeater->start_controls_tabs('content_tabs');

        $repeater->start_controls_tab(
            'content_normal_tab',
            [
                'label' => esc_html__('Normal', 'elementify-addons-for-elementor'),
            ]
        );

        $repeater->add_control(
            'tab_icon',
            [
                'label'         => esc_html__('Tab Icon', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::ICONS,
                'label_block'   => true,
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'content_hover_tab',
            [
                'label' => esc_html__('Hover', 'elementify-addons-for-elementor'),
            ]
        );

        $repeater->add_control(
            'tab_hover_icon',
            [
                'label'         => esc_html__('Tab Icon', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::ICONS,
                'label_block'   => true,
            ]
        );

        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $repeater->add_control(
            'content_type',
            [
                'label'     => esc_html__('Content Type', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'editor'    => esc_html__('Editor', 'elementify-addons-for-elementor'),
                    'template'  => esc_html__('Template', 'elementify-addons-for-elementor'),
                ],
                'default'   => 'editor',
                'label_block' => false,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'tab_content',
            [
                'label'         => esc_html__('Content', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::WYSIWYG,
                'default'       => esc_html__('Tab Content', 'elementify-addons-for-elementor'),
                'show_label'    => false,
                'condition'     => [
                    'content_type' => 'editor',
                ],
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'template_id',
            [
                'label'         => esc_html__('Select Template', 'elementify-addons-for-elementor'),
                'description' => sprintf(
                    /* translators: %1$s: Opening anchor tag, %2$s: Closing anchor tag */
                    __('To create a elementor section template click %1$s here %2$s.', 'elementify-addons-for-elementor'),
                    '<a target="_blank" href="' . esc_url(admin_url('/edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type=section')) . '">',
                    '</a>'
                ),
                'type'          => Controls_Manager::SELECT2,
                'options'       => ElementifyUtils::get_elementor_templates('section'),
                'label_block'   => true,
                'condition'     => [
                    'content_type' => 'template',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label'       => esc_html__('Items', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'tab_title'     => esc_html__('Branding', 'elementify-addons-for-elementor'),
                        'content_type'  => 'editor',
                        'tab_content'   => esc_html__('Lorem ipsum dolor sit amet consectetur adipisicing elit. Amet assumenda distinctio a excepturi.', 'elementify-addons-for-elementor')
                    ],
                    [
                        'tab_title' => esc_html__('Organizing', 'elementify-addons-for-elementor'),
                        'content_type'  => 'editor',
                        'tab_content'   => esc_html__('Lorem ipsum dolor sit amet consectetur adipisicing elit. Amet assumenda distinctio a excepturi.', 'elementify-addons-for-elementor')
                    ],
                ],
                'title_field' => '{{{ elementor.helpers.renderIcon( this, tab_icon, {}, "i", "panel" ) }}} {{{ tab_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register title controls.
     */
    protected function register_title_controls()
    {
        $this->start_controls_section(
            'title_section',
            [
                'label' => esc_html__('Tabs', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout',
            [
                'label'     => esc_html__('Layout', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'vertical'    => esc_html__('Vertical', 'elementify-addons-for-elementor'),
                    'horizontal'  => esc_html__('Horizontal', 'elementify-addons-for-elementor'),
                ],
                'default'       => 'vertical',
                'prefix_class'  => 'eae-tabs-preset-',
                'separator'     => 'before',
            ]
        );

        $start = is_rtl() ? 'end' : 'start';
        $end = is_rtl() ? 'start' : 'end';

        $this->add_responsive_control(
            'tabs_align_horizontal',
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
                'prefix_class'  => 'eae-tabs-h-align-',
                'selectors' => [
                    '{{WRAPPER}}.eae-tabs-preset-horizontal .eae-tab__list' => 'justify-content: {{VALUE}}',
                ],
                'condition' => [
                    'layout' => 'horizontal',
                ],
                'default' => 'start',
                'separator' => 'before',
            ]
        );


        $this->add_responsive_control(
            'tabs_align_vertical',
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
                'prefix_class'  => 'eae-tabs-v-align-',
                'selectors' => [
                    '{{WRAPPER}}.eae-tabs-preset-vertical .eae-tab__list' => 'justify-content: {{VALUE}}',
                ],
                'condition' => [
                    'layout' => 'vertical',
                ],
                'default' => 'start',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'title_alignment',
            [
                'label' => esc_html__('Title Align', 'elementify-addons-for-elementor'),
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
                'prefix_class'  => 'eae-tabs-v-align-',
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__item' => 'justify-content: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'title_width',
            [
                'label' => esc_html__('Title Width', 'elementify-addons-for-elementor'),
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
                    '{{WRAPPER}} .eae-tab' => '--eae-tab-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'layout' => 'vertical',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_icon_enable',
            [
                'label' => esc_html__('Show Icon', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => esc_html__('Show', 'elementify-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'elementify-addons-for-elementor'),
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register style tabs controls.
     */
    protected function register_style_tabs_controls()
    {
        $this->start_controls_section(
            'style_tabs_section',
            [
                'label' => esc_html__('Tabs', 'elementify-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'tabs_gap',
            [
                'label' => esc_html__('Tabs Gap', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 200],
                    'em' => ['min' => 0, 'max' => 40, 'step' => 0.1],
                    'rem' => ['min' => 0, 'max' => 40, 'step' => 0.1],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__list' => 'gap: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_content_distance',
            [
                'label' => esc_html__('Content Distance', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                    'em' => ['min' => 0, 'max' => 40, 'step' => 0.1],
                    'rem' => ['min' => 0, 'max' => 40, 'step' => 0.1],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-tab' => '--eae-content-gap: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('tabs_style');

        $this->start_controls_tab(
            'normal_tab',
            [
                'label' => esc_html__('Normal', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tab_background',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tab_border',
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tab_box_shadow',
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover_tab',
            [
                'label' => esc_html__('Hover', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tab_hover_background',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tab_hover_border',
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tab_hover_box_shadow',
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'active_tab',
            [
                'label' => esc_html__('Active', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tab_active_background',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => ['default' => 'classic'],
                    'color' => ['default' => 'rgba(0, 0, 0, 0.1)'],
                ],
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item.is--active',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tab_active_border',
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item.is--active',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tab_active_box_shadow',
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item.is--active',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'tab_border_radius',
            [
                'label' => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__list .eae-tab__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_padding',
            [
                'label' => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default' => [
                    'top' => 12,
                    'right' => 15,
                    'bottom' => 12,
                    'left' => 15,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__list .eae-tab__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register style title controls.
     */
    protected function register_style_title_controls()
    {
        $this->start_controls_section(
            'style_tab_title_section',
            [
                'label' => esc_html__('Titles', 'elementify-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_title_typo',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item',
            ]
        );

        $this->start_controls_tabs('ele_tab_title_style');

        $this->start_controls_tab(
            'ele_tab_title_style_normal',
            [
                'label' => esc_html__('Normal', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'ele_tab_title_normal_color',
            [
                'label' => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_SECONDARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__list .eae-tab__item' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'tab_title_shadow',
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'tab_title_stroke',
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ele_tab_title_style_hover',
            [
                'label' => esc_html__('Hover', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'ele_tab_title_hover_color',
            [
                'label' => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__list .eae-tab__item:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'tab_title_hover_shadow',
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'tab_title_hover_stroke',
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ele_tab_title_style_active',
            [
                'label' => esc_html__('Active', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'ele_tab_title_active_color',
            [
                'label' => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__list .eae-tab__item.is--active' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'tab_title_active_shadow',
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item.is--active',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'tab_title_active_stroke',
                'selector' => '{{WRAPPER}} .eae-tab__list .eae-tab__item.is--active',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Register style icon controls.
     */
    protected function register_style_icon_controls()
    {
        $this->start_controls_section(
            'style_tab_icon_section',
            [
                'label'     => esc_html__('Icons', 'elementify-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'title_icon_enable' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'default'    => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eae-tab__item-icon' => '--eae-icon-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'title_icon_enable' => 'yes',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'title_icon_gap',
            [
                'label'      => esc_html__('Icon Gap', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'default'    => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eae-tab__list .eae-tab__item' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'title_icon_enable' => 'yes',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'title_icon_align',
            [
                'label'     => esc_html__('Icon Justify', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'start'  => [
                        'title' => esc_html__('Start', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-align-center-v',
                    ],
                    'end'    => [
                        'title' => esc_html__('End', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-align-end-v',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__list .eae-tab__item' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'title_icon_enable' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'title_icon_offset',
            [
                'label'      => esc_html__('Icon Offset (Vertical)', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px' => [
                        'min' => -15,
                        'max' => 15,
                    ],
                    'em' => [
                        'min' => -1,
                        'max' => 1,
                    ],
                ],
                'default'    => [
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eae-tab__list .eae-tab__item' => '--eae-icon-v-offset: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'title_icon_enable' => 'yes',
                ],
                'separator'  => 'before',
            ]
        );

        // Tabs: Normal / Hover / Active
        $this->start_controls_tabs('ele_tab_icon_style');

        // Normal tab
        $this->start_controls_tab(
            'ele_tab_icon_style_normal',
            [
                'label'     => esc_html__('Normal', 'elementify-addons-for-elementor'),
                'condition' => [
                    'title_icon_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ele_tab_icon_normal_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__item-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'title_icon_enable' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover tab
        $this->start_controls_tab(
            'ele_tab_icon_style_hover',
            [
                'label'     => esc_html__('Hover', 'elementify-addons-for-elementor'),
                'condition' => [
                    'title_icon_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ele_tab_icon_hover_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__item:hover .eae-tab__item-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'title_icon_enable' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        // Active tab
        $this->start_controls_tab(
            'ele_tab_icon_style_active',
            [
                'label'     => esc_html__('Active', 'elementify-addons-for-elementor'),
                'condition' => [
                    'title_icon_enable' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ele_tab_icon_active_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__item.is--active .eae-tab__item-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'title_icon_enable' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs(); // End tab group

        $this->end_controls_section(); // End section
    }


    /**
     * Register style content controls.
     */
    protected function register_style_content_controls()
    {
        $this->start_controls_section(
            'tab_content_section',
            [
                'label' => esc_html__('Content', 'elementify-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tab_content_color',
            [
                'label' => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_TEXT,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__content .eae-tab__content-item' => 'color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_content_typo',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .eae-tab__content .eae-tab__content-item',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tab_content_background',
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'fields_options' => [
                    'background' => ['default' => 'classic'],
                    'color' => ['default' => '#fff'],
                ],
                'selector' => '{{WRAPPER}} .eae-tab__content',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tab_content_border',
                'selector' => '{{WRAPPER}} .eae-tab__content',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_content_border_radius',
            [
                'label' => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'tab_content_padding',
            [
                'label' => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default' => [
                    'top' => 32,
                    'right' => 32,
                    'bottom' => 32,
                    'left' => 32,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-tab__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output.
     */
    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('eae-wrapper', [
            'class' => 'eae-tabs',
            'data-layout' => $settings['layout'],
        ]);

        if (empty($settings['tabs'])) {
            return;
        }
?>
        <div <?php $this->print_render_attribute_string('eae-wrapper'); ?>>
            <div class="eae-tab">
                <div class="eae-tab__list" role="tablist">
                    <?php foreach ($settings['tabs'] as $index => $item) :
                        $tab_count = $index + 1;
                        $tab_title_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);

                        $this->add_render_attribute($tab_title_key, [
                            'id' => 'eae-tab__title-' . esc_attr($item['_id']),
                            'class' => ['eae-tab__item'],
                            'aria-selected' => 1 === $tab_count ? 'true' : 'false',
                            'data-tab' => $tab_count,
                            'role' => 'tab',
                            'tabindex' => 1 === $tab_count ? '0' : '-1',
                            'aria-controls' => '_tab-content-' . esc_attr($item['_id']),
                            'aria-expanded' => 'false',
                        ]);
                    ?>
                        <div <?php $this->print_render_attribute_string($tab_title_key); ?>>
                            <?php if (!empty($item['tab_icon']) && !empty($settings['title_icon_enable'])) :
                                $svg_opacity = (!empty($item['tab_hover_icon']) && $item['tab_hover_icon']['value'] !== '') ? 'eae-tab__tab-icon-opacity' : 'eae-tab__tab-icon';
                            ?>
                                <span class="eae-tab__item-icon">
                                    <?php
                                    Icons_Manager::render_icon($item['tab_icon'], ['aria-hidden' => 'true', 'class' => $svg_opacity]);
                                    if (!empty($item['tab_hover_icon'])) {
                                        Icons_Manager::render_icon($item['tab_hover_icon'], ['aria-hidden' => 'true', 'class' => 'eae-tab__tab-icon-hover']);
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>
                            <span class="eae-tab__item-title"><?php echo esc_html($item['tab_title']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="eae-tab__content">
                    <?php foreach ($settings['tabs'] as $index => $item) :
                        $tab_count = $index + 1;
                        $hidden = 1 === $tab_count ? 'false' : 'hidden';
                        $tab_content_key = $this->get_render_attribute_string('tab_content_' . $index);

                        $this->add_render_attribute('tab_content_' . $index, [
                            'id' => 'eae-tab__content-item-' . esc_attr($item['_id']),
                            'class' => ['eae-tab__content-item'],
                            'data-tab' => $tab_count,
                            'role' => 'tabpanel',
                            'aria-labelledby' => 'eae-tab__title-' . esc_attr($item['_id']),
                            'tabindex' => '0',
                            'hidden' => $hidden,
                        ]);
                    ?>
                        <div <?php echo wp_kses_post($this->get_render_attribute_string('tab_content_' . $index)); ?>>
                            <?php if ($item['content_type'] === 'template') : ?>
                                <?php echo wp_kses_post(Plugin::instance()->frontend->get_builder_content_for_display($item['template_id'])); ?>
                            <?php else : ?>
                                <?php echo wp_kses_post($item['tab_content']); ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
<?php
    }
}
