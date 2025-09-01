<?php

/**
 * Form Styler Widget.
 *
 * @package elementify-addons-for-elementor
 * @since 1.0.0
 */

namespace Elementify_Addons_For_Elementor\Inc\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementify_Addons_For_Elementor\Inc\Utils as ElementifyUtils;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Form Styler Widget
 *
 * @since 1.0.0
 */
class Form_Styler extends Widget_Base
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
        return 'eae-form-styler';
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
        return esc_html__('Form Styler', 'elementify-addons-for-elementor');
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
        return 'eae-icon-form-styler';
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
        return ['elementify-addons-for-elementor-widget'];
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
        return ['elementify', 'cf7', 'mailchimp', 'contact form 7', 'caldera forms', 'ninja forms', 'wpforms', 'wp forms'];
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

        $this->add_control(
            'plugin_select',
            [
                'label'     => esc_html__('Source', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'select',
                'options'   => [
                    'select'    => esc_html__('- Select -', 'elementify-addons-for-elementor'),
                    'cf-7'      => esc_html__('Contact Form 7', 'elementify-addons-for-elementor'),
                    'wpforms'   => esc_html__('WPForms', 'elementify-addons-for-elementor'),
                    'ninja'     => esc_html__('Ninja Forms', 'elementify-addons-for-elementor'),
                    'caldera'   => esc_html__('Caldera Forms', 'elementify-addons-for-elementor'),
                    'mailchimp' => esc_html__('Mailchimp', 'elementify-addons-for-elementor'),
                ],
                'label_block' => true,
            ]
        );

        // Contact Form 7
        if (class_exists('WPCF7_ContactForm')) {

            // Get posts for dropdown
            $cf7_post_type = ElementifyUtils::get_posts([
                'post_type'         => 'wpcf7_contact_form',
                'posts_per_page'    => -1,
                'orderby'           => 'title',
                'order'             => 'ASC'
            ]);
            $cf7_templates = [];

            if (!empty($cf7_post_type) && !is_wp_error($cf7_post_type)) {
                foreach ($cf7_post_type as $template_id => $template_title) {
                    $cf7_templates[$template_id] = $template_title;
                }
            } else {
                $cf7_templates['empty'] = esc_html__('No Forms Found!', 'elementify-addons-for-elementor');
            }

            $this->add_control(
                'cf7_templates',
                [
                    'label'         => esc_html__('Template', 'elementify-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => $cf7_templates,
                    'default'       => 'empty',
                    'condition'     => [
                        'plugin_select' => 'cf-7'
                    ],
                    'label_block'   => true,
                ]
            );
        } else {
            $this->add_control(
                'cf7_notice',
                [
                    'label'     => esc_html__('Please install Contact Form 7 plugin to use this feature.', 'elementify-addons-for-elementor'),
                    'type'      => Controls_Manager::RAW_HTML,
                    'separator' => 'before',
                    'condition' => [
                        'plugin_select' => 'cf-7'
                    ]
                ]
            );
        }

        // WP Forms
        if (class_exists('WPForms')) {

            // Get posts for dropdown
            $wpforms_post_type = ElementifyUtils::get_posts([
                'post_type'         => 'wpforms',
                'posts_per_page'    => -1,
                'orderby'           => 'title',
                'order'             => 'ASC'
            ]);
            $wpforms_templates = [];

            if (!empty($wpforms_post_type) && !is_wp_error($wpforms_post_type)) {
                foreach ($wpforms_post_type as $template_id => $template_title) {
                    $wpforms_templates[$template_id] = $template_title;
                }
            } else {
                $wpforms_templates['empty'] = esc_html__('No Forms Found!', 'elementify-addons-for-elementor');
            }

            $this->add_control(
                'wpforms_templates',
                [
                    'label'         => esc_html__('Template', 'elementify-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => $wpforms_templates,
                    'default'       => 'empty',
                    'condition'     => [
                        'plugin_select' => 'wpforms'
                    ],
                    'label_block'   => true,
                ]
            );

            $this->add_control(
                'show_form_title',
                [
                    'label'         => esc_html__('Show Title', 'elementify-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                    'condition'     => [
                        'plugin_select' => 'wpforms'
                    ]
                ]
            );

            $this->add_control(
                'show_form_description',
                [
                    'label'         => esc_html__('Show Description', 'elementify-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                    'selectors_dictionary' => [
                        ''      => 'none',
                        'yes'   => 'block'
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .nf-form-fields-required' => 'display: {{VALUE}};',
                    ],
                    'condition' => [
                        'plugin_select' => ['wpforms', 'ninja']
                    ]
                ]
            );
        } else {
            $this->add_control(
                'wpforms_notice',
                [
                    'label'     => esc_html__('Please install WPForms plugin to use this feature.', 'elementify-addons-for-elementor'),
                    'type'      => Controls_Manager::RAW_HTML,
                    'separator' => 'before',
                    'condition' => [
                        'plugin_select' => 'wpforms'
                    ]
                ]
            );
        }

        // Ninja Forms
        if (class_exists('Ninja_Forms')) {

            $ninja_forms_templates = [];

            $ninja_forms_post_type = Ninja_Forms()->form()->get_forms();

            if (!empty($ninja_forms_post_type) && !is_wp_error($ninja_forms_post_type)) {
                foreach ($ninja_forms_post_type as $template) {
                    $ninja_forms_templates[$template->get_id()] = $template->get_setting('title');
                }
            } else {
                $ninja_forms_templates['empty'] = esc_html__('No Forms Found!', 'elementify-addons-for-elementor');
            }

            $this->add_control(
                'ninja_forms_templates',
                [
                    'label'     => esc_html__('Template', 'elementify-addons-for-elementor'),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => $ninja_forms_templates,
                    'default'   => 'empty',
                    'condition' => [
                        'plugin_select' => 'ninja'
                    ],
                    'label_block' => true
                ]
            );
        } else {
            $this->add_control(
                'ninja_forms_notice',
                [
                    'label'     => esc_html__('Please install Ninja Forms plugin to use this feature.', 'elementify-addons-for-elementor'),
                    'type'      => Controls_Manager::RAW_HTML,
                    'separator' => 'before',
                    'condition' => [
                        'plugin_select' => 'ninja'
                    ]
                ]
            );
        }

        // Caldera Forms
        if (class_exists('Caldera_Forms')) {
            $caldera_forms_templates = [];

            $caldera_forms_post_type = Caldera_Forms_Forms::get_forms(true, true);

            if (!empty($caldera_forms_post_type) && !is_wp_error($caldera_forms_post_type)) {
                foreach ($caldera_forms_post_type as $template) {
                    $caldera_forms_templates[$template['ID']] = $template['name'];
                }
            } else {
                $caldera_forms_templates['empty'] = esc_html__('No Forms Found!', 'elementify-addons-for-elementor');
            }

            $this->add_control(
                'caldera_forms_templates',
                [
                    'label'         => esc_html__('Template', 'elementify-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => $caldera_forms_templates,
                    'default'       => 'empty',
                    'condition'     => [
                        'plugin_select' => 'caldera'
                    ],
                    'label_block'   => true
                ]
            );
        } else {
            $this->add_control(
                'caldera_notice',
                [
                    'label'         => esc_html__('Please install Caldera Forms plugin to use this feature.', 'elementify-addons-for-elementor'),
                    'type'          => Controls_Manager::RAW_HTML,
                    'separator'     => 'before',
                    'condition'     => [
                        'plugin_select' => 'caldera'
                    ]
                ]
            );
        }


        // Mailchimp Forms
        if (class_exists('MC4WP_MailChimp')) {
            $mailchimp_forms = [];

            $forms = mc4wp_get_forms();

            if (!empty($forms) && !is_wp_error($forms)) {
                foreach ($forms as $form) {
                    $mailchimp_forms[$form->ID] = $form->name;
                }
            } else {
                $mailchimp_forms['empty'] = esc_html__('No Forms Found!', 'elementify-addons-for-elementor');
            }

            $this->add_control(
                'mailchimp_form_templates',
                [
                    'label'     => esc_html__('Template', 'elementify-addons-for-elementor'),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => $mailchimp_forms,
                    'default'   => 'empty',
                    'condition' => [
                        'plugin_select' => 'mailchimp'
                    ],
                    'label_block'   => true,
                ]
            );
        } else {
            $this->add_control(
                'mailchimp_notice',
                [
                    'label'         => esc_html__('Please install and activate the Mailchimp for WordPress plugin and add an API key to use this feature.', 'elementify-addons-for-elementor'),
                    'type'          => Controls_Manager::RAW_HTML,
                    'separator'     => 'before',
                    'condition'     => [
                        'plugin_select' => 'mailchimp'
                    ]
                ]
            );
        }

        $this->add_control(
            'label_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control(
            'show_field_labels',
            [
                'label'     => esc_html__('Show Labels', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'selectors_dictionary' => [
                    ''      => 'none !important',
                    'yes'   => 'block'
                ],
                'selectors' => [
                    '{{WRAPPER}} .caldera-grid .control-label' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-field-label' => 'display: {{VALUE}};',
                    '{{WRAPPER}} .nf-field-label' => 'display: {{VALUE}};',
                ],
                'condition' => [
                    'plugin_select!' => ['cf-7', 'mailchimp']
                ]
            ]
        );

        $this->add_control(
            'show_field_placeholders',
            [
                'label'     => esc_html__('Show Placeholders', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
            ]
        );

        $this->add_control(
            'notice_divider',
            [
                'type'      => Controls_Manager::DIVIDER,
                'style'     => 'thick',
            ]
        );

        $this->add_control(
            'show_error_notices',
            [
                'label'     => esc_html__('Show Notices', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'selectors_dictionary' => [
                    ''      => 'display: none !important;',
                    'yes'   => 'display: block;'
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container .wpcf7-not-valid-tip' => '{{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container .wpcf7-response-output.wpcf7-validation-errors' => '{{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container label.wpforms-error' => '{{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container .nf-error-msg' => '{{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container .caldera_ajax_error_block' => '{{VALUE}}',
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
    protected function register_style_controls(): void
    {
        $this->start_controls_section(
            'content_style_section',
            [
                'label'         => esc_html__('Container', 'elementify-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'show_label'    => false,
            ]
        );

        $this->add_control(
            'form_align',
            [
                'label'         => esc_html__('Form Alignment', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'  => [
                        'title' => esc_html__('Left', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'elementify-addons-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default'       => 'left',
                'prefix_class'  => 'eae-forms-align-',
                'selectors'     => [
                    '{{WRAPPER}} .eae-form-styler-container' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .eae-form-styler-container .wpcf7-form' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .eae-form-styler-container .wpforms-field-container' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .eae-form-styler-container .nf-form-wrap' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .eae-form-styler-container .nf-form-wrap .field-wrap' => 'justify-content: {{VALUE}};',
                ],
                'separator'     => 'after'
            ]
        );

        $this->add_control(
            'container_background',
            [
                'label'     => esc_html__('Background Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'container_border_color',
            [
                'label'     => esc_html__('Border Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'container_box_shadow',
                'selector'  => '{{WRAPPER}} .eae-form-styler-container',
            ]
        );

        $this->add_control(
            'container_border_type',
            [
                'label'     => esc_html__('Border Type', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none'      => esc_html__('None', 'elementify-addons-for-elementor'),
                    'solid'     => esc_html__('Solid', 'elementify-addons-for-elementor'),
                    'double'    => esc_html__('Double', 'elementify-addons-for-elementor'),
                    'dotted'    => esc_html__('Dotted', 'elementify-addons-for-elementor'),
                    'dashed'    => esc_html__('Dashed', 'elementify-addons-for-elementor'),
                    'groove'    => esc_html__('Groove', 'elementify-addons-for-elementor'),
                ],
                'default'   => 'none',
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'container_border_width',
            [
                'label'     => esc_html__('Border Width', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default'   => [
                    'top'       => 1,
                    'right'     => 1,
                    'bottom'    => 1,
                    'left'      => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'container_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label'     => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default'   => [
                    'top'       => 0,
                    'right'     => 0,
                    'bottom'    => 0,
                    'left'  => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'container_radius',
            [
                'label'     => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'   => [
                    'top'       => 0,
                    'right'     => 0,
                    'bottom'    => 0,
                    'left'  => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Section: Title & Description
        $this->start_controls_section(
            'section_style_header',
            [
                'label'     => esc_html__('Form Header', 'elementify-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'conditions' => [
                    'relation'  => 'or',
                    'terms'     => [
                        [
                            'name'      => 'show_form_title',
                            'operator'  => '!==',
                            'value'     => '',
                        ],
                        [
                            'name'      => 'show_form_description',
                            'operator'  => '!==',
                            'value'     => '',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'header_title_color',
            [
                'label'     => esc_html__('Title Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpforms-head-container .wpforms-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .nf-form-title h3' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'header_title_typography',
                'selector'  => '{{WRAPPER}} .wpforms-head-container .wpforms-title, {{WRAPPER}} .nf-form-title h3',
            ]
        );

        $this->add_control(
            'header_description_color',
            [
                'label'     => esc_html__('Description Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpforms-head-container .wpforms-description' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .nf-form-fields-required' => 'color: {{VALUE}}',
                ],
                'default'   => '#606060',
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'header_description_typography',
                'selector'  => '{{WRAPPER}} .wpforms-head-container .wpforms-description, {{WRAPPER}} .nf-form-fields-required',
            ]
        );

        $this->add_responsive_control(
            'header_distance',
            [
                'label'     => esc_html__('Distance', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px'    => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'   => [
                    'unit'  => 'px',
                    'size'  => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpforms-head-container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .nf-form-fields-required' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

        // Section: Labels -----------
        $this->start_controls_section(
            'section_style_labels',
            [
                'label'         => esc_html__('Field Labels', 'elementify-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'show_label'    => false,
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'         => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'default'       => '#818181',
                'selectors'     => [
                    '{{WRAPPER}} .wpcf7-form' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .mc4wp-form' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-response-output' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .nf-field-container label' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .wpforms-field-label' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-image-choices-label' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-field-label-inline' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-captcha-question' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-captcha-equation' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-payment-total' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .caldera-grid .control-label' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .checkbox label' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .radio label' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .total-line' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .caldera-forms-gdpr-field-label' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .caldera-forms-gdpr-field-label a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .caldera-forms-gdpr-field-label a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-forms-summary-field ul li' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'label_typography',
                'selector'  => '{{WRAPPER}} .wpcf7-form,{{WRAPPER}} .mc4wp-form, {{WRAPPER}} .nf-field-container label, {{WRAPPER}} .wpforms-field-label, {{WRAPPER}} .wpforms-image-choices-label, {{WRAPPER}} .wpforms-field-label-inline, {{WRAPPER}} .wpforms-captcha-question, {{WRAPPER}} .wpforms-captcha-equation, {{WRAPPER}} .wpforms-payment-total, {{WRAPPER}} .caldera-grid .control-label, {{WRAPPER}} .caldera-forms-summary-field ul li, {{WRAPPER}} .caldera-grid .total-line, {{WRAPPER}} .caldera-grid .checkbox label, {{WRAPPER}} .caldera-grid .radio label, {{WRAPPER}} .caldera-grid .caldera-forms-gdpr-field-label, {{WRAPPER}} .eae-form-styler-container .wpforms-confirmation-container-full, {{WRAPPER}} .eae-form-styler-container .nf-response-msg',
            ]
        );

        $this->add_responsive_control(
            'label_spacing',
            [
                'label'     => esc_html__('Spacing', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px'    => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'default'   => [
                    'unit'  => 'px',
                    'size'  => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form .wpcf7-form-control' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mc4wp-form .mc4wp-form-fields input:not([type="submit"])' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .nf-field-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-field-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-captcha-question' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .control-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

        // Section: Description ------
        $this->start_controls_section(
            'section_style_descriptions',
            [
                'label'     => esc_html__('Field Description', 'elementify-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'plugin_select' => ['ninja', 'wpforms', 'caldera']
                ]
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'     => esc_html__('Text Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .nf-field-description' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-field-sublabel' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-field-description' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .help-block' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'description_link_color',
            [
                'label'     => esc_html__('Link Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .nf-field-description a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-field-sublabel a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-field-description a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .help-block a' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'description_typography',
                'selector'  => '{{WRAPPER}} .nf-field-description, {{WRAPPER}} .wpforms-field-sublabel, {{WRAPPER}} .wpforms-field-description, {{WRAPPER}} .caldera-grid .help-block',
            ]
        );

        $this->add_responsive_control(
            'description_spacing',
            [
                'label'     => esc_html__('Spacing', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px'    => [
                        'min' => 0,
                        'max' => 25,
                    ],
                ],
                'default'   => [
                    'unit'  => 'px',
                    'size'  => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .label-above .nf-field-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .label-hidden .nf-field-description' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-field-sublabel' => 'margin-top: calc({{SIZE}}{{UNIT}} / 2)',
                    '{{WRAPPER}} .wpforms-field-description' => 'margin-top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .caldera-grid .help-block' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

        // Section: Inputs -----------
        $this->start_controls_section(
            'section_style_inputs',
            [
                'label'     => esc_html__('Fields (Input, Textarea)', 'elementify-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->start_controls_tabs('tabs_forms_inputs_style');

        $this->start_controls_tab(
            'tab_inputs_normal',
            [
                'label' => esc_html__('Normal', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'input_color',
            [
                'label'     => esc_html__('Text Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#474747',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"])' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .mc4wp-form-fields textarea' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-text' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-textarea' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-date' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-number' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-quiz' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-select' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .ninja-forms-field' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .wpforms-form input[type=date]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=email]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=month]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=number]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=password]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=range]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=search]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=tel]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=text]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=time]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=url]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=week]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form select' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form textarea' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid select.form-control' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid textarea.form-control' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label'     => esc_html__('Placeholder Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ADADAD',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .mc4wp-form-fields textarea::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-text::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-textarea::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-date::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-number::placeholder' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .ninja-forms-field::placeholder' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .wpforms-form input[type=date]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=email]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=month]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=number]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=password]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=range]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=search]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=tel]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=text]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=time]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=url]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=week]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form select::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form textarea::placeholder' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid select.form-control::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid textarea.form-control::placeholder' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_field_placeholders' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'input_background_color',
            [
                'label'     => esc_html__('Background Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"])' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .mc4wp-form-fields textarea' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-text' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-textarea' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-date' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-number' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-quiz' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-select' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .field-wrap:not(.submit-wrap) .ninja-forms-field' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .wpforms-form input[type=date]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=email]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=month]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=number]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=password]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=range]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=search]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=tel]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=text]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=time]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=url]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=week]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form select' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form textarea' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid select.form-control' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid textarea.form-control' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label'     => esc_html__('Border Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e8e8e8',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"])' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .mc4wp-form-fields textarea' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-text' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-textarea' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-date' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-number' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-quiz' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-select' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .ninja-forms-field' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .wpforms-form input[type=date]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=email]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=month]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=number]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=password]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=range]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=search]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=tel]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=text]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=time]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=url]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=week]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form select' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form textarea' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid select.form-control' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid textarea.form-control' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_inputs_hover',
            [
                'label' => esc_html__('Focus', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'input_color_fc',
            [
                'label'     => esc_html__('Text Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"]):focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .mc4wp-form-fields textarea:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-text:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-textarea:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-date:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-number:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-quiz:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-select:focus' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .ninja-forms-field:focus' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .wpforms-form input[type=date]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=email]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=month]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=number]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=password]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=range]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=search]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=tel]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=text]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=time]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=url]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=week]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form select:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form textarea:focus' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid select.form-control:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid textarea.form-control:focus' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'input_placeholder_color_fc',
            [
                'label'     => esc_html__('Placeholder Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"]):focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .mc4wp-form-fields textarea:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-text:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-textarea:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-date:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-number:focus::placeholder' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .ninja-forms-field:focus::placeholder' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .wpforms-form input[type=date]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=email]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=month]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=number]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=password]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=range]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=search]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=tel]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=text]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=time]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=url]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=week]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form select:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form textarea:focus::placeholder' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid select.form-control:focus::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid textarea.form-control:focus::placeholder' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_field_placeholders' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'input_background_color_fc',
            [
                'label'     => esc_html__('Background Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"]):focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .mc4wp-form-fields textarea:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-text:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-textarea:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-date:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-number:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-quiz:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-select:focus' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .ninja-forms-field:focus' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .wpforms-form input[type=date]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=email]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=month]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=number]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=password]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=range]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=search]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=tel]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=text]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=time]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=url]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=week]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form select:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form textarea:focus' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid select.form-control:focus' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid textarea.form-control:focus' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'input_border_color_fc',
            [
                'label'     => esc_html__('Border Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e8e8e8',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"]):focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .mc4wp-form-fields textarea:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-text:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-textarea:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-date:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-number:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-quiz:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-select:focus' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .ninja-forms-field:focus' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .wpforms-form input[type=date]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=email]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=month]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=number]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=password]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=range]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=search]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=tel]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=text]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=time]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=url]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form input[type=week]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form select:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-form textarea:focus' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid select.form-control:focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid textarea.form-control:focus' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'input_box_shadow',
                'selector'  => '{{WRAPPER}} .wpcf7-text, {{WRAPPER}} .wpcf7-textarea,{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"]),{{WRAPPER}} .mc4wp-form-fields textarea,{{WRAPPER}} .mc4wp-form-fields textarea, {{WRAPPER}} .wpcf7-date, {{WRAPPER}} .wpcf7-number, {{WRAPPER}} .wpcf7-select, {{WRAPPER}} .wpcf7-quiz, {{WRAPPER}} .ninja-forms-field, {{WRAPPER}} .wpforms-form input[type=date], {{WRAPPER}} .wpforms-form input[type=datetime], {{WRAPPER}} .wpforms-form input[type=datetime-local], {{WRAPPER}} .wpforms-form input[type=email], {{WRAPPER}} .wpforms-form input[type=month], {{WRAPPER}} .wpforms-form input[type=number], {{WRAPPER}} .wpforms-form input[type=password], {{WRAPPER}} .wpforms-form input[type=range], {{WRAPPER}} .wpforms-form input[type=search], {{WRAPPER}} .wpforms-form input[type=tel], {{WRAPPER}} .wpforms-form input[type=text], {{WRAPPER}} .wpforms-form input[type=time], {{WRAPPER}} .wpforms-form input[type=url], {{WRAPPER}} .wpforms-form input[type=week], {{WRAPPER}} .wpforms-form select, {{WRAPPER}} .wpforms-form textarea, {{WRAPPER}} .caldera-grid .form-control[type=text], {{WRAPPER}} .caldera-grid .form-control[type=email], {{WRAPPER}} .caldera-grid .form-control[type=tel], {{WRAPPER}} .caldera-grid .form-control[type=phone], {{WRAPPER}} .caldera-grid .form-control[type=number], {{WRAPPER}} .caldera-grid .form-control[type=url], {{WRAPPER}} .caldera-grid .form-control[type=color_picker], {{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc], {{WRAPPER}} .caldera-grid select.form-control, {{WRAPPER}} .caldera-grid textarea.form-control',
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'input_transition_duration',
            [
                'label'     => esc_html__('Transition Duration', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 0.1,
                'min'       => 0,
                'max'       => 5,
                'step'      => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"]), {{WRAPPER}} .mc4wp-form-fields textarea' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpcf7-text' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpcf7-textarea' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpcf7-date' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpcf7-number' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpcf7-quiz' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpcf7-select' => 'transition-duration: {{VALUE}}s',

                    '{{WRAPPER}} .ninja-forms-field' => 'transition-duration: {{VALUE}}s',

                    '{{WRAPPER}} .wpforms-form input[type=date]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form input[type=email]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form input[type=month]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form input[type=number]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form input[type=password]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form input[type=range]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form input[type=search]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form input[type=tel]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form input[type=text]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form input[type=time]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form input[type=url]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form input[type=week]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form select' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-form textarea' => 'transition-duration: {{VALUE}}s',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .caldera-grid select.form-control' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .caldera-grid textarea.form-control' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'input_typography',
                'selector'  => '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"]),{{WRAPPER}} .mc4wp-form-fields textarea,{{WRAPPER}} .wpcf7-text, {{WRAPPER}} .wpcf7-textarea, {{WRAPPER}} .wpcf7-date, {{WRAPPER}} .wpcf7-number, {{WRAPPER}} .wpcf7-select, {{WRAPPER}} .wpcf7-quiz, {{WRAPPER}} .ninja-forms-field, {{WRAPPER}} .wpforms-form input[type=date], {{WRAPPER}} .wpforms-form input[type=datetime], {{WRAPPER}} .wpforms-form input[type=datetime-local], {{WRAPPER}} .wpforms-form input[type=email], {{WRAPPER}} .wpforms-form input[type=month], {{WRAPPER}} .wpforms-form input[type=number], {{WRAPPER}} .wpforms-form input[type=password], {{WRAPPER}} .wpforms-form input[type=range], {{WRAPPER}} .wpforms-form input[type=search], {{WRAPPER}} .wpforms-form input[type=tel], {{WRAPPER}} .wpforms-form input[type=text], {{WRAPPER}} .wpforms-form input[type=time], {{WRAPPER}} .wpforms-form input[type=url], {{WRAPPER}} .wpforms-form input[type=week], {{WRAPPER}} .wpforms-form select, {{WRAPPER}} .wpforms-form textarea, {{WRAPPER}} .caldera-grid .form-control[type=text], {{WRAPPER}} .caldera-grid .form-control[type=email], {{WRAPPER}} .caldera-grid .form-control[type=tel], {{WRAPPER}} .caldera-grid .form-control[type=phone], {{WRAPPER}} .caldera-grid .form-control[type=number], {{WRAPPER}} .caldera-grid .form-control[type=url], {{WRAPPER}} .caldera-grid .form-control[type=color_picker], {{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc], {{WRAPPER}} .caldera-grid select.form-control, {{WRAPPER}} .caldera-grid textarea.form-control',
            ]
        );

        $this->add_control(
            'input_border_type',
            [
                'label'     => esc_html__('Border Type', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none'      => esc_html__('None', 'elementify-addons-for-elementor'),
                    'solid'     => esc_html__('Solid', 'elementify-addons-for-elementor'),
                    'double'    => esc_html__('Double', 'elementify-addons-for-elementor'),
                    'dotted'    => esc_html__('Dotted', 'elementify-addons-for-elementor'),
                    'dashed'    => esc_html__('Dashed', 'elementify-addons-for-elementor'),
                    'groove'    => esc_html__('Groove', 'elementify-addons-for-elementor'),
                ],
                'default'   => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"]),{{WRAPPER}} .mc4wp-form-fields textarea' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpcf7-text' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpcf7-textarea' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpcf7-date' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpcf7-number' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpcf7-quiz' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpcf7-select' => 'border-style: {{VALUE}};',

                    '{{WRAPPER}} .ninja-forms-field' => 'border-style: {{VALUE}};',

                    '{{WRAPPER}} .wpforms-form input[type=date]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form input[type=email]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form input[type=month]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form input[type=number]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form input[type=password]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form input[type=range]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form input[type=search]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form input[type=tel]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form input[type=text]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form input[type=time]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form input[type=url]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form input[type=week]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form select' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .wpforms-form textarea' => 'border-style: {{VALUE}};',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]' => 'border-style {{VALUE}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .caldera-grid select.form-control' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .caldera-grid textarea.form-control' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'input_border_width',
            [
                'label'     => esc_html__('Border Width', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default'   => [
                    'top'       => 1,
                    'right'     => 1,
                    'bottom'    => 1,
                    'left'      => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"]),{{WRAPPER}} .mc4wp-form-fields textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-text' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-date' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-number' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-quiz' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .ninja-forms-field' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .wpforms-form input[type=date]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=email]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=month]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=number]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=password]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=range]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=search]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=tel]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=text]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=time]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=url]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=week]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid select.form-control' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid textarea.form-control' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'input_border_type!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'input_border_divider',
            [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_responsive_control(
            'input_width',
            [
                'label'     => esc_html__('Input Width', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'     => [
                    'px'    => [
                        'min' => 30,
                        'max' => 500,
                    ],
                ],
                'default'   => [
                    'unit'  => '%',
                    'size'  => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"])' => 'width: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .wpcf7-text' => 'width: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .wpcf7-email' => 'width: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .wpcf7-quiz' => 'width: {{SIZE}}{{UNIT}} !important;',

                    '{{WRAPPER}} .wpforms-field-medium:not(textarea)' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-field-address' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-field-phone' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-page-indicator' => 'width: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}} .nf-field-container:not(.textarea-container) .nf-field-element' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'plugin_select!' => 'caldera'
                ]
            ]
        );

        $this->add_responsive_control(
            'input_height',
            [
                'label'     => esc_html__('Input Height', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px'    => [
                        'min' => 30,
                        'max' => 100,
                    ],
                ],
                'default'   => [
                    'unit'  => 'px',
                    'size'  => 45,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"]),{{WRAPPER}} .mc4wp-form-fields textarea' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpcf7-text' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpcf7-textarea' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpcf7-number' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpcf7-quiz' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpcf7-select' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpcf7-date' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpcf7-number' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',

                    '{{WRAPPER}} .field-wrap:not(.submit-wrap):not(.textarea-wrap):not(.list-multiselect-wrap) .ninja-forms-field:not(hr)' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .nf-pass.field-wrap .nf-field-element:after' => 'height: {{SIZE}}px; line-height: {{SIZE}}px; font-size: calc({{SIZE}}px / 2);',
                    '{{WRAPPER}} .nf-error.field-wrap .nf-field-element:after' => 'line-height: {{SIZE}}px !important;',
                    '{{WRAPPER}} .textarea-wrap .ninja-forms-field' => 'line-height: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}} .wpforms-form input[type=date]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form input[type=email]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form input[type=month]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form input[type=number]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form input[type=password]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form input[type=range]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form input[type=search]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form input[type=tel]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form input[type=text]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form input[type=time]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form input[type=url]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form input[type=week]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form select' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .wpforms-form textarea' => 'line-height: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .caldera-grid select.form-control' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
                    '{{WRAPPER}} .caldera-grid textarea.form-control' => 'line-height: {{SIZE}}px;',
                ],
                'separator' => 'after'
            ]
        );

        $this->add_responsive_control(
            'input_textarea_height',
            [
                'label'     => esc_html__('Textarea (Message) Height', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px'    => [
                        'min' => 100,
                        'max' => 500,
                    ],
                ],
                'default'   => [
                    'unit' => 'px',
                    'size' => 300,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields textarea' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-textarea' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .textarea-wrap .ninja-forms-field' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form textarea' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid textarea.form-control' => 'height: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'textarea_width',
            [
                'label'     => esc_html__('Textarea Width', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'     => [
                    'px'    => [
                        'min' => 30,
                        'max' => 500,
                    ],
                ],
                'default'   => [
                    'unit'  => '%',
                    'size'  => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields textarea' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-textarea' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} textarea.wpforms-field-medium' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .nf-field-container.textarea-container .nf-field-element' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'plugin_select!' => 'caldera'
                ]
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label'     => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default'   => [
                    'top'       => 0,
                    'right'     => 15,
                    'bottom'    => 0,
                    'left'      => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"]),{{WRAPPER}} .mc4wp-form-fields textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-quiz' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .field-wrap:not(.listselect-wrap):not(.submit-wrap) .ninja-forms-field:not(hr)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .wpforms-form input[type=date]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=email]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=month]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=number]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=password]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=range]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=search]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=tel]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=text]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=time]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=url]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=week]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid select.form-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid textarea.form-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'input_radius',
            [
                'label'     => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'   => [
                    'top'       => 2,
                    'right'     => 2,
                    'bottom'    => 2,
                    'left'      => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"]),{{WRAPPER}} .mc4wp-form-fields textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-number' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-quiz' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .wpforms-form input[type=date]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=datetime]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=datetime-local]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=email]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=month]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=number]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=password]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=range]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=search]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=tel]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=text]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=time]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=url]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form input[type=week]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-form textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .nf-field-container:not(.list-container) .ninja-forms-field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .nf-field-container .nf-field-element select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .nf-error.field-wrap .nf-field-element:after' => 'border-radius: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',

                    '{{WRAPPER}} .caldera-grid .form-control[type=text]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=email]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=tel]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=phone]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=number]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=url]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=color_picker]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .form-control[type=credit_card_cvc]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid select.form-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid textarea.form-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_spacing',
            [
                'label'     => esc_html__('Vertical Gutter', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px'    => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default'   => [
                    'unit'  => 'px',
                    'size'  => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input:not([type="submit"]),{{WRAPPER}} .mc4wp-form-fields textarea' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-form-control' => 'margin-bottom: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}} .nf-field-container' => 'margin-bottom: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}} .wpforms-field' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-field-address .wpforms-field-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}} .caldera-grid .form-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .eae-caldera-html' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

        // Section: Submit Button ----
        $this->start_controls_section(
            'section_style_submit_btn',
            [
                'label'     => esc_html__('Submit Button', 'elementify-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'submit_btn_align',
            [
                'label'     => esc_html__('Alignment', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'  => [
                        'title' => esc_html__('Left', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'prefix_class'  => 'eae-forms-submit-',
                'default'       => 'left',
            ]
        );

        $this->add_control(
            'submit_btn_align_divider',
            [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->start_controls_tabs('tabs_submit_btn_style');

        $this->start_controls_tab(
            'tab_submit_btn_normal',
            [
                'label' => esc_html__('Normal', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'submit_btn_bg_color',
                'label'         => esc_html__('Background', 'elementify-addons-for-elementor'),
                'types'         => ['classic', 'gradient'],
                'fields_options' => [
                    'color'     => [
                        'default' => '#605BE5',
                    ],
                ],
                'selector'      => '{{WRAPPER}} .mc4wp-form-fields input[type="submit"],{{WRAPPER}} .wpcf7-submit, {{WRAPPER}} .submit-wrap .ninja-forms-field, {{WRAPPER}} .submit-wrap .ninja-forms-field, {{WRAPPER}} .wpforms-submit, {{WRAPPER}} .wpforms-page-next, {{WRAPPER}} .wpforms-page-previous, {{WRAPPER}} .caldera-grid .btn-default, {{WRAPPER}} .caldera-grid .cf2-dropzone button, {{WRAPPER}} #contact-btn'
            ]
        );

        $this->add_control(
            'submit_btn_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="submit"]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-submit' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .submit-wrap .ninja-forms-field' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-submit' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-page-next' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-page-previous' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .btn-default' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .cf2-dropzone button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'submit_btn_border_color',
            [
                'label'     => esc_html__('Border Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="submit"]' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-submit' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .submit-wrap .ninja-forms-field' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .submit-wrap .ninja-forms-field' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-submit' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-page-next' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-page-previous' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .cf2-dropzone button' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'submit_btn_box_shadow',
                'selector'  => '{{WRAPPER}} .mc4wp-form-fields input[type="submit"],{{WRAPPER}} .wpcf7-submit, {{WRAPPER}} .submit-wrap .ninja-forms-field, {{WRAPPER}} .wpforms-submit, {{WRAPPER}} .wpforms-page-next, {{WRAPPER}} .wpforms-page-previous, {{WRAPPER}} .caldera-grid .btn-default:not(a), {{WRAPPER}} .caldera-grid .cf2-dropzone button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_submit_btn_hover',
            [
                'label' => esc_html__('Hover', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'read_more_bg_color_hr',
                'label'         => esc_html__('Background', 'elementify-addons-for-elementor'),
                'types'         => ['classic', 'gradient'],
                'fields_options' => [
                    'color'     => [
                        'default' => '#4A45D2',
                    ],
                ],
                'selector' => '{{WRAPPER}} .mc4wp-form-fields input[type="submit"]:hover,{{WRAPPER}}  .wpcf7-submit:hover, {{WRAPPER}} .submit-wrap .ninja-forms-field:hover, {{WRAPPER}} .wpforms-submit:hover, {{WRAPPER}} .wpforms-page-next:hover, {{WRAPPER}} .wpforms-page-previous:hover, {{WRAPPER}} .caldera-grid .btn-default:hover, {{WRAPPER}} .caldera-grid .btn-success, {{WRAPPER}} .caldera-grid .cf2-dropzone button:hover, {{WRAPPER}} #contact-btn:hover'
            ]
        );

        $this->add_control(
            'submit_btn_color_hr',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="submit"]:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-submit:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .submit-wrap .ninja-forms-field:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-submit:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-page-next:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-page-previous:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .btn-default:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .btn-success' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .cf2-dropzone button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'submit_btn_border_color_hr',
            [
                'label'     => esc_html__('Border Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="submit"]:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpcf7-submit:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .submit-wrap .ninja-forms-field:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-submit:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-page-next:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .wpforms-page-previous:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .btn-default:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .btn-success' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .cf2-dropzone button:hover' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'submit_btn_box_shadow_hr',
                'selector'  => '{{WRAPPER}} .mc4wp-form-fields input[type="submit"]:hover,{{WRAPPER}} .wpcf7-submit:hover, {{WRAPPER}} .submit-wrap .ninja-forms-field:hover, {{WRAPPER}} .wpforms-submit:hover, {{WRAPPER}} .wpforms-page-next:hover, {{WRAPPER}} .wpforms-page-previous:hover, {{WRAPPER}} .caldera-grid .btn-default:not(a):hover, {{WRAPPER}} .caldera-grid .cf2-dropzone button:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'submit_btn_divider',
            [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_control(
            'submit_btn_transition_duration',
            [
                'label'     => esc_html__('Transition Duration', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 0.1,
                'min'       => 0,
                'max'       => 5,
                'step'      => 0.1,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="submit"]' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpcf7-submit' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .submit-wrap .ninja-forms-field' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-submit' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-page-next' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .wpforms-page-previous' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .caldera-grid .btn-default' => 'transition-duration: {{VALUE}}s',
                    '{{WRAPPER}} .caldera-grid .cf2-dropzone button' => 'transition-duration: {{VALUE}}s',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'submit_btn_typography',
                'selector'  => '{{WRAPPER}} .mc4wp-form-fields input[type="submit"],{{WRAPPER}} .wpcf7-submit, {{WRAPPER}} .submit-wrap .ninja-forms-field, {{WRAPPER}} .wpforms-submit, {{WRAPPER}} .wpforms-page-next, {{WRAPPER}} .wpforms-page-previous, {{WRAPPER}} .caldera-grid .btn-default, {{WRAPPER}} .caldera-grid .btn-success, {{WRAPPER}} .caldera-grid .cf2-dropzone button'
            ]
        );

        $this->add_control(
            'submit_btn_border_type',
            [
                'label'     => esc_html__('Border Type', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none'      => esc_html__('None', 'elementify-addons-for-elementor'),
                    'solid'     => esc_html__('Solid', 'elementify-addons-for-elementor'),
                    'double'    => esc_html__('Double', 'elementify-addons-for-elementor'),
                    'dotted'    => esc_html__('Dotted', 'elementify-addons-for-elementor'),
                    'dashed'    => esc_html__('Dashed', 'elementify-addons-for-elementor'),
                    'groove'    => esc_html__('Groove', 'elementify-addons-for-elementor'),
                ],
                'default'   => 'none',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="submit"]' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .eae-form-styler-container .wpcf7-submit' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .eae-form-styler-container .submit-wrap .ninja-forms-field' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .eae-form-styler-container .wpforms-submit' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .eae-form-styler-container .wpforms-page-next' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .eae-form-styler-container .wpforms-page-previous' => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .caldera-grid .btn-default' => 'border-style: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .btn-success' => 'border-style: {{VALUE}}',
                    '{{WRAPPER}} .caldera-grid .cf2-dropzone button' => 'border-style: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'submit_btn_border_width',
            [
                'label'     => esc_html__('Border Width', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default'   => [
                    'top'       => 1,
                    'right'     => 1,
                    'bottom'    => 1,
                    'left'      => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="submit"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eae-form-styler-container .wpcf7-submit' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eae-form-styler-container .submit-wrap .ninja-forms-field' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eae-form-styler-container .wpforms-submit' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eae-form-styler-container .wpforms-page-next' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eae-form-styler-container .wpforms-page-previous' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .btn-default' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .btn-success' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .cf2-dropzone button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'submit_btn_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_btn_padding',
            [
                'label'     => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default'   => [
                    'top'       => 12,
                    'right'     => 30,
                    'bottom'    => 12,
                    'left'      => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .submit-wrap .ninja-forms-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-page-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-page-previous' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .btn-default' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .btn-success' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .cf2-dropzone button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'submit_btn_radius',
            [
                'label'     => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'   => [
                    'top'       => 2,
                    'right'     => 2,
                    'bottom'    => 2,
                    'left'      => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpcf7-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .nf-field-container .submit-wrap .ninja-forms-field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-page-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpforms-page-previous' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .btn-default:not(a)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .caldera-grid .cf2-dropzone button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'submit_btn_spacing',
            [
                'label'     => esc_html__('Top Distance', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'     => [
                    'px'    => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default'   => [
                    'unit'  => 'px',
                    'size'  => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="submit"]' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eae-form-styler-container .wpcf7-submit' => 'margin-top: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}} .eae-form-styler-container .nf-field-container .submit-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}} .eae-form-styler-container .wpforms-submit' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eae-form-styler-container .wpforms-page-next' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eae-form-styler-container .wpforms-page-previous' => 'margin-top: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}} .wpcf7-form .caldera-grid .btn-default:not(a)' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

        // Section: Checkboxes -------
        $this->start_controls_section(
            'section_style_checkbox_radio',
            [
                'label' => esc_html__('Checkbox & Radio', 'elementify-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'plugin_select!' => 'caldera'
                ]
            ]
        );

        $this->add_control(
            'checkbox_radio_custom',
            [
                'label' => esc_html__('Use Custom Styles', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'checkbox_radio_static_color',
            [
                'label' => esc_html__('Static Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .eae-custom-check-radio .wpcf7-checkbox .wpcf7-list-item-label:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpcf7-radio .wpcf7-list-item-label:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpcf7-acceptance .wpcf7-list-item-label:before' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-custom-check-radio .listradio-wrap .nf-field-element label:after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .listradio-wrap .nf-field-element label.nf-checked-label:after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .checkbox-wrap .nf-field-label label:after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .checkbox-container .nf-field-element label:after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .listcheckbox-container .nf-field-element label:after' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-checkbox input + label:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-gdpr-checkbox input + label:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-radio input + label:before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-radio input + span:before' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'checkbox_radio_custom' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'checkbox_radio_active_color',
            [
                'label' => esc_html__('Active Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#605BE5',
                'selectors' => [
                    '{{WRAPPER}} .eae-custom-check-radio .wpcf7-checkbox input:checked + .wpcf7-list-item-label:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpcf7-radio input:checked + .wpcf7-list-item-label:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpcf7-acceptance input:checked + .wpcf7-list-item-label:before' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .eae-custom-check-radio .checkbox-wrap .nf-field-label label.nf-checked-label:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .listcheckbox-wrap .nf-field-element label.nf-checked-label:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .listradio-wrap .nf-field-element label.nf-checked-label:before' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-checkbox input:checked + label:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-gdpr-checkbox input:checked + label:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-radio input:checked + label:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-image-choices input:checked + span:before' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'checkbox_radio_custom' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'checkbox_radio_border_color',
            [
                'label' => esc_html__('Border Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .eae-custom-check-radio .wpcf7-checkbox .wpcf7-list-item-label:before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpcf7-radio .wpcf7-list-item-label:before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpcf7-acceptance .wpcf7-list-item-label:before' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-custom-check-radio .listradio-wrap .nf-field-element label:after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .listradio-wrap .nf-field-element label.nf-checked-label:after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .checkbox-wrap .nf-field-label label:after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .checkbox-container .nf-field-element label:after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .listcheckbox-container .nf-field-element label:after' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-checkbox label:before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-gdpr-checkbox label:before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-radio label:before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-radio input + span:before' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'checkbox_radio_custom' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'checkbox_radio_size',
            [
                'label' => esc_html__('Size', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eae-custom-check-radio .wpcf7-checkbox .wpcf7-list-item-label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 1.3);',
                    '{{WRAPPER}} .eae-custom-check-radio .wpcf7-radio .wpcf7-list-item-label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 1.3);',
                    '{{WRAPPER}} .eae-custom-check-radio .wpcf7-acceptance .wpcf7-list-item-label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 1.3);',

                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-checkbox label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 1.3);',
                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-gdpr-checkbox label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 1.3);',
                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-radio label:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 1.3);',
                    '{{WRAPPER}} .eae-custom-check-radio .wpforms-field-radio input + span:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 1.3);',
                ],
                'separator' => 'before',
                'condition' => [
                    'plugin_select!' => 'ninja',
                    'checkbox_radio_custom' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

        // Section: Custom HTML ------
        $this->start_controls_section(
            'section_style_html',
            [
                'label' => esc_html__('Custom HTML', 'elementify-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'html_color',
            [
                'label' => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .wpforms-field-html' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .nf-field-container .html-wrap' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-caldera-html' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'plugin_select!' => 'cf-7'
                ]
            ]
        );

        $this->add_control(
            'html_link_color',
            [
                'label' => esc_html__('Link Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .wpforms-field-html a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .nf-field-container .html-wrap a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-caldera-html a' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'html_divider_color',
            [
                'label' => esc_html__('Divider Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#999999',
                'selectors' => [
                    '{{WRAPPER}} .nf-field-container .hr-wrap hr' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-caldera-html hr' => 'border-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'html_typography',
                'selector' => '{{WRAPPER}} .wpforms-field-html, {{WRAPPER}} .nf-field-container .html-wrap, {{WRAPPER}} .eae-caldera-html',
            ]
        );

        $this->end_controls_section();

        // Section: Notices ----------
        $this->start_controls_section(
            'section_style_notices',
            [
                'label' => esc_html__('Notices', 'elementify-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    // 'plugin_select' => 'caldera'
                ]
            ]
        );

        $this->add_control(
            'notice_error_text_color',
            [
                'label' => esc_html__('Error Text Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF348B',
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container .wpcf7-not-valid-tip' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container .wpcf7-response-output' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container label.wpforms-error' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container label.wpforms-error a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container label .wpforms-required-label' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .nf-error-msg' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container .ninja-forms-req-symbol' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .caldera_ajax_error_block' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container .caldera-grid .control-label .field_required' => 'color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'notice_error_text_typography',
                'selector' => '{{WRAPPER}} .eae-form-styler-container .wpcf7-not-valid-tip, {{WRAPPER}} .eae-form-styler-container .wpcf7-response-output, {{WRAPPER}} .eae-form-styler-container label.wpforms-error, {{WRAPPER}} .eae-form-styler-container .caldera_ajax_error_block, {{WRAPPER}} .eae-form-styler-container .nf-error-msg'
            ]
        );

        $this->add_control(
            'notice_error_field_color',
            [
                'label' => esc_html__('Error Field Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF348B',
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container input.wpcf7-not-valid' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container textarea.wpcf7-not-valid' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container input.wpforms-error' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container textarea.wpforms-error' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .nf-error.field-wrap .nf-field-element:after' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .caldera-grid .parsley-error' => 'color: {{VALUE}}',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'notice_error_field_bg_color',
            [
                'label' => esc_html__('Error Field Background Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FDD3D3',
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container input.wpcf7-not-valid' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container textarea.wpcf7-not-valid' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container input.wpforms-error' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container textarea.wpforms-error' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .nf-error.field-wrap .nf-field-element:after' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .caldera-grid .parsley-error:not(.checkbox-inline)' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'notice_error_field_bd_color',
            [
                'label' => esc_html__('Error Field Border Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container input.wpcf7-not-valid' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container textarea.wpcf7-not-valid' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container input.wpforms-error' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container textarea.wpforms-error' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .nf-error.field-wrap .ninja-forms-field' => 'border-color: {{VALUE}} !important',

                    '{{WRAPPER}} .eae-form-styler-container .caldera-grid .parsley-error' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'notice_success_text_color',
            [
                'label' => esc_html__('Success Text Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#38DDD2',
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container .wpcf7-mail-sent-ok' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .wpforms-confirmation-container-full' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .nf-response-msg' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .caldera-grid .alert-success' => 'color: {{VALUE}}',
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'notice_success_bg_color',
            [
                'label' => esc_html__('Success Background Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container .wpcf7-mail-sent-ok' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .wpforms-confirmation-container-full' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .nf-response-msg' => 'background-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .caldera-grid .alert-success' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'notice_success_bd_color',
            [
                'label' => esc_html__('Success Border Color', 'elementify-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .eae-form-styler-container .wpcf7-mail-sent-ok' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .wpforms-confirmation-container-full' => 'border-color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .nf-response-msg' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eae-form-styler-container .nf-pass .ninja-forms-field' => 'border-color: {{VALUE}} !important',
                    '{{WRAPPER}} .eae-form-styler-container .nf-pass.field-wrap .nf-field-element:after' => 'color: {{VALUE}}',

                    '{{WRAPPER}} .eae-form-styler-container .caldera-grid .alert-success' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }


    /**
     * Render CF7 template
     *
     * @param array $settings Widget settings
     */
    protected function render_cf7_template($settings)
    {
        if (!empty($settings['cf7_templates']) && $settings['cf7_templates'] !== 'empty') {
            echo do_shortcode('[contact-form-7 id="' . esc_attr($settings['cf7_templates']) . '"]');
        }
    }

    /**
     * Render WPForms template
     *
     * @param array $settings Widget settings
     */
    protected function render_wpforms_template($settings)
    {
        if (!empty($settings['wpforms_templates']) && $settings['wpforms_templates'] !== 'empty') {
            if (function_exists('wpforms_display')) {
                echo wp_kses_post(
                    wpforms_display(
                        esc_attr($settings['wpforms_templates']),
                        isset($settings['show_form_title']) && $settings['show_form_title'] === 'yes',
                        isset($settings['show_form_description']) && $settings['show_form_description'] === 'yes'
                    )
                );
            }
        }
    }

    /**
     * Render Ninja Forms template
     *
     * @param array $settings Widget settings
     */
    protected function render_ninja_forms_template($settings)
    {
        if (!empty($settings['ninja_forms_templates']) && $settings['ninja_forms_templates'] !== 'empty') {
            echo do_shortcode('[ninja_form id="' . esc_attr($settings['ninja_forms_templates']) . '"]');
        }
    }

    /**
     * Render Caldera Forms template
     *
     * @param array $settings Widget settings
     */
    protected function render_caldera_forms_template($settings)
    {
        if (!empty($settings['caldera_forms_templates']) && $settings['caldera_forms_templates'] !== 'empty') {
            echo do_shortcode('[caldera_form id="' . esc_attr($settings['caldera_forms_templates']) . '"]');
        }
    }

    /**
     * Render MailChimp Forms template
     *
     * @param array $settings Widget settings
     */
    protected function render_mailchimp_forms_template($settings)
    {
        if (!empty($settings['mailchimp_form_templates']) && $settings['mailchimp_form_templates'] !== 'empty') {
            echo do_shortcode('[mc4wp_form id="' . esc_attr($settings['mailchimp_form_templates']) . '"]');
        }
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

        $this->add_render_attribute('eae-container', [
            'class' => ['eae-form-styler-container'],
        ]);

        if (isset($settings['checkbox_radio_custom']) && 'yes' === $settings['checkbox_radio_custom']) {
            $this->add_render_attribute('eae-container', [
                'class' => 'eae-custom-check-radio',
            ]);
        }


        $this->add_render_attribute('eae-template', [
            'class' => ['eae-form-styler__template'],
        ]);

        if (isset($settings['show_field_placeholders']) && 'yes' === $settings['show_field_placeholders']) {
            $this->add_render_attribute('eae-template', [
                'class' => 'eae-show-placeholders',
            ]);
        }


?>
        <div <?php $this->print_render_attribute_string('eae-container'); ?>>
            <div <?php $this->print_render_attribute_string('eae-template'); ?>>
                <?php
                switch ($settings['plugin_select']) {
                    case 'cf-7':
                        $this->render_cf7_template($settings);
                        break;

                    case 'wpforms':
                        $this->render_wpforms_template($settings);
                        break;

                    case 'ninja':
                        $this->render_ninja_forms_template($settings);
                        break;

                    case 'caldera':
                        $this->render_caldera_forms_template($settings);
                        break;

                    case 'mailchimp':
                        $this->render_mailchimp_forms_template($settings);
                        break;

                    default:
                        esc_html_e('Please select a form source and template from the widget settings.', 'elementify-addons-for-elementor');
                        break;
                }
                ?>
            </div>
        </div>
<?php
    }
}
