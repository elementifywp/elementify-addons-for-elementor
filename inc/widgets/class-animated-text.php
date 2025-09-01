<?php

/**
 * Animated Text Widget.
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
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Animated Text Widget
 *
 * @since 1.0.0
 */
class Animated_Text extends Widget_Base
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
        return 'eae-animated-text';
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
        return esc_html__('Animated Text', 'elementify-addons-for-elementor');
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
        return 'eae-icon-animated-text';
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
        return ['typed', 'elementify-addons-for-elementor-widget'];
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
        return ['elementify', 'animated text', 'heading', 'title', 'text'];
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
        $this->register_typed_js_controls();
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
            'prefix',
            [
                'label'       => esc_html__('Before Text', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => ['active' => true],
                'default'     => esc_html__('This is', 'elementify-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'text',
            [
                'label'       => esc_html__('Text', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => ['active' => true],
                'label_block' => true,
                'default'     => esc_html__('Dynamic Text', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'strings',
            [
                'label'       => esc_html__('Dynamic Text', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::REPEATER,
                'default'     => [
                    ['text' => esc_html__('Designer', 'elementify-addons-for-elementor')],
                    ['text' => esc_html__('Developer', 'elementify-addons-for-elementor')],
                    ['text' => esc_html__('Awesome', 'elementify-addons-for-elementor')],
                ],
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ text }}}',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'suffix',
            [
                'label'       => esc_html__('After Text', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => ['active' => true],
                'default'     => esc_html__('Text', 'elementify-addons-for-elementor'),
                'label_block' => true,
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'tag',
            [
                'label'   => esc_html__('HTML Tag', 'elementify-addons-for-elementor'),
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

        $this->add_responsive_control(
            'display',
            [
                'label'     => esc_html__('Display', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'inline' => esc_html__('Inline', 'elementify-addons-for-elementor'),
                    'block'  => esc_html__('Block', 'elementify-addons-for-elementor'),
                ],
                'default'   => 'inline',
                'selectors' => [
                    '{{WRAPPER}} .eae-animated-text__prefix, {{WRAPPER}} .eae-animated-text__suffix' => 'display: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label'   => esc_html__('Alignment', 'elementify-addons-for-elementor'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => [
                        'title' => esc_html__('Left', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'elementify-addons-for-elementor'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eae-animated-text' => 'text-align: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'link',
            [
                'label'     => esc_html__('Link', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::URL,
                'dynamic'   => ['active' => true],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Typed.js controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_typed_js_controls(): void
    {
        $this->start_controls_section(
            'typed_section',
            [
                'label' => esc_html__('Animation Settings', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'typing_speed',
            [
                'label'       => esc_html__('Typing Speed (ms)', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 90,
                'min'         => 0,
                'max'         => 1000,
                'step'        => 10,
                'description' => esc_html__('Speed at which typing happens in milliseconds.', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'back_speed',
            [
                'label'       => esc_html__('Backspace Speed (ms)', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 60,
                'min'         => 0,
                'max'         => 1000,
                'step'        => 10,
                'description' => esc_html__('Speed at which backspacing happens in milliseconds.', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'start_delay',
            [
                'label'       => esc_html__('Start Delay (ms)', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 90,
                'min'         => 0,
                'max'         => 10000,
                'step'        => 100,
                'description' => esc_html__('Delay before typing starts in milliseconds.', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'back_delay',
            [
                'label'       => esc_html__('Backspace Delay (ms)', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 60,
                'min'         => 0,
                'max'         => 10000,
                'step'        => 100,
                'description' => esc_html__('Delay before backspacing in milliseconds.', 'elementify-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'loop',
            [
                'label'     => esc_html__('Loop Animation', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'cursor',
            [
                'label'     => esc_html__('Show Cursor', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'fade_out',
            [
                'label'     => esc_html__('Fade Out Effect', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'smart_backspace',
            [
                'label'     => esc_html__('Smart Backspace', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'description' => esc_html__('Only backspace what doesn\'t match the next string', 'elementify-addons-for-elementor'),
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
    protected function register_style_controls(): void
    {
        $this->start_controls_section(
            'style_plain_section',
            [
                'label' => esc_html__('Static Text', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'plain_text_color',
            [
                'label'     => esc_html__('Text Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'global'    => ['default' => Global_Colors::COLOR_SECONDARY],
                'selectors' => [
                    '{{WRAPPER}} .eae-animated-text--plain-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'text_typo',
                'global'   => ['default' => Global_Typography::TYPOGRAPHY_PRIMARY],
                'selector' => '{{WRAPPER}} .eae-animated-text',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name'     => 'text_stroke',
                'selector' => '{{WRAPPER}} .eae-animated-text--plain-text',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_dynamic_section',
            [
                'label' => esc_html__('Animated Text', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'dynamic_text_color',
            [
                'label'     => esc_html__('Text Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'global'    => ['default' => Global_Colors::COLOR_PRIMARY],
                'selectors' => [
                    '{{WRAPPER}} .eae-animated-text--dynamic-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'dynamic_text_typo',
                'global'   => ['default' => Global_Typography::TYPOGRAPHY_PRIMARY],
                'selector' => '{{WRAPPER}} .eae-animated-text--dynamic-text',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name'     => 'dynamic_text_stroke',
                'selector' => '{{WRAPPER}} .eae-animated-text--dynamic-text',
            ]
        );

        $this->add_control(
            'dynamic_text_background_color',
            [
                'label'     => esc_html__('Background Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eae-animated-text--dynamic-text' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'dynamic_text_padding',
            [
                'label'      => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eae-animated-text--dynamic-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_cursor_section',
            [
                'label' => esc_html__('Cursor', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'dynamic_cursor_color',
            [
                'label'     => esc_html__('Color', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'global'    => ['default' => Global_Colors::COLOR_SECONDARY],
                'selectors' => [
                    '{{WRAPPER}} .typed-cursor' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'cursor_typo',
                'global'   => ['default' => Global_Typography::TYPOGRAPHY_PRIMARY],
                'selector' => '{{WRAPPER}} .typed-cursor',
            ]
        );

        $this->add_control(
            'cursor_blink_speed',
            [
                'label'     => esc_html__('Blink Speed (ms)', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 900,
                'min'       => 100,
                'max'       => 3000,
                'step'      => 100,
                'selectors' => [
                    '{{WRAPPER}} .typed-cursor' => 'animation-duration: {{VALUE}}ms;',
                ],
                'separator' => 'before',
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
     * Prepare animation settings for JavaScript.
     *
     * @since 1.0.0
     * @access protected
     * @param array $settings Widget settings.
     * @return array Prepared settings.
     */
    protected function prepare_animation_settings(array $settings): array
    {
        return [
            'strings'        => array_filter(array_map(function ($item) {
                return !empty($item['text']) ? $this->sanitize_text($item['text']) : '';
            }, $settings['strings'])),
            'typeSpeed'      => absint($settings['typing_speed']),
            'backSpeed'      => absint($settings['back_speed']),
            'startDelay'     => absint($settings['start_delay']),
            'backDelay'      => absint($settings['back_delay']),
            'showCursor'     => !empty($settings['cursor']),
            'loop'          => !empty($settings['loop']),
            'fadeOut'       => !empty($settings['fade_out']),
            'smartBackspace' => !empty($settings['smart_backspace']),
        ];
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

        if (empty($settings['strings'])) {
            return;
        }

        $tag = Utils::validate_html_tag($settings['tag']);
        $animation_settings = $this->prepare_animation_settings($settings);

        if (empty($animation_settings['strings'])) {
            return;
        }

        $this->add_render_attribute('eae-wrapper', [
            'class'         => 'eae-animated-text',
            'data-settings' => wp_json_encode($animation_settings),
        ]);

        $prefix = $this->sanitize_text($settings['prefix'] ?? '');
        $suffix = $this->sanitize_text($settings['suffix'] ?? '');
        $link = $settings['link'] ?? [];

        // Link attributes
        if (!empty($link['url'])) {
            $this->add_link_attributes('url', $link);
        }
?>
        <?php if (!empty($link['url'])) : ?>
            <a <?php $this->print_render_attribute_string('url'); ?>>
            <?php endif; ?>

            <<?php echo esc_html($tag); ?> <?php $this->print_render_attribute_string('eae-wrapper'); ?>>
                <?php if ($prefix) : ?>
                    <span class="eae-animated-text__prefix eae-animated-text--plain-text">
                        <?php echo esc_html($prefix); ?>
                    </span>
                <?php endif; ?>

                <span class="eae-animated-text__typed-wrapper">
                    <span class="eae-animated-text__typed eae-animated-text--dynamic-text"></span>
                </span>

                <?php if ($suffix) : ?>
                    <span class="eae-animated-text__suffix eae-animated-text--plain-text">
                        <?php echo esc_html($suffix); ?>
                    </span>
                <?php endif; ?>
            </<?php echo esc_html($tag); ?>>

            <?php if (!empty($link['url'])) : ?>
            </a>
        <?php endif; ?>
<?php
    }
}
