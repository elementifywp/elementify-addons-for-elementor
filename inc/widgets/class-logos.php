<?php

/**
 * Logos Widget.
 *
 * @package elementify-addons-for-elementor
 * @since 1.0.0
 */

namespace Elementify_Addons_For_Elementor\Inc\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementify_Addons_For_Elementor\Inc\Utils as ElementifyUtils;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Logos Widget
 *
 * @since 1.0.0
 */
class Logos extends Widget_Base
{
    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @return string Widget name.
     */
    public function get_name(): string
    {
        return 'eae-logos';
    }

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @return string Widget title.
     */
    public function get_title(): string
    {
        return esc_html__('Logos Carousel', 'elementify-addons-for-elementor');
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @return string Widget icon.
     */
    public function get_icon(): string
    {
        return 'eae-icon-logo-carousel';
    }

    /**
     * Get widget categories.
     *
     * @since 1.0.0
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
     * @return array Script dependencies.
     */
    public function get_script_depends(): array
    {
        return ['elementify-addons-for-elementor-widget'];
    }

    /**
     * Get widget keywords.
     *
     * @since 1.0.0
     * @return array Widget keywords.
     */
    public function get_keywords(): array
    {
        return ['elementify', 'logos', 'carousel', 'brands', 'clients'];
    }

    /**
     * Register all widget controls.
     *
     * @since 1.0.0
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
            'logo_title',
            [
                'label'       => esc_html__('Title', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Logo', 'elementify-addons-for-elementor'),
                'dynamic'     => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'logo_image',
            [
                'label'       => esc_html__('Image', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic'    => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'logo_link',
            [
                'label'       => esc_html__('Link', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'elementify-addons-for-elementor'),
                'dynamic'     => ['active' => true],
                'default'     => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        $this->add_control(
            'logos_list',
            [
                'label'       => esc_html__('Logos', 'elementify-addons-for-elementor'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['logo_title' => esc_html__('Logo 1', 'elementify-addons-for-elementor')],
                    ['logo_title' => esc_html__('Logo 2', 'elementify-addons-for-elementor')],
                    ['logo_title' => esc_html__('Logo 3', 'elementify-addons-for-elementor')],
                ],
                'title_field' => '{{{ logo_title }}}',
            ]
        );

        $this->add_control(
            'direction',
            [
                'label'     => esc_html__('Scroll Direction', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'left',
                'options'   => [
                    'left'  => esc_html__('Left', 'elementify-addons-for-elementor'),
                    'right' => esc_html__('Right', 'elementify-addons-for-elementor'),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'scroll_speed',
            [
                'label'     => esc_html__('Scroll Speed (seconds)', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'default'  => 40,
                'min'       => 5,
                'max'       => 100,
                'selectors' => [
                    '{{WRAPPER}} .eae-logo-scroller' => '--animation-duration: {{VALUE}}s;',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_width',
            [
                'label'      => esc_html__('Logo Width', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 50, 'max' => 500],
                    '%'  => ['min' => 5, 'max' => 100],
                ],
                'default'   => ['unit' => 'px', 'size' => 200],
                'selectors'  => [
                    '{{WRAPPER}} .eae-logo-item' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'enable_links',
            [
                'label'     => esc_html__('Enable Links', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label'     => esc_html__('Pause on Hover', 'elementify-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'no',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register style controls.
     *
     * @since 1.0.0
     */
    protected function register_style_controls(): void
    {
        $this->start_controls_section(
            'logo_style_section',
            [
                'label' => esc_html__('Logos', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'logo_hover_effect',
            [
                'label'   => esc_html__('Hover Effect', 'elementify-addons-for-elementor'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'none'      => esc_html__('None', 'elementify-addons-for-elementor'),
                    'grayscale' => esc_html__('Grayscale', 'elementify-addons-for-elementor'),
                    'scale'     => esc_html__('Scale', 'elementify-addons-for-elementor'),
                    'opacity'   => esc_html__('Opacity', 'elementify-addons-for-elementor'),
                ],
                'default' => 'none',
                'prefix_class' => 'eae-logo-hover-',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'logo_border',
                'selector'  => '{{WRAPPER}} .eae-logo-item',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'logo_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eae-logo-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'logo_shadow',
                'selector' => '{{WRAPPER}} .eae-logo-item',
            ]
        );

        $this->add_responsive_control(
            'logo_padding',
            [
                'label'      => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eae-logo-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'logo_margin',
            [
                'label'      => esc_html__('Margin', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eae-logo-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'container_style_section',
            [
                'label' => esc_html__('Container', 'elementify-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'container_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eae-logo-scroller',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label'      => esc_html__('Padding', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eae-logo-scroller' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label'      => esc_html__('Margin', 'elementify-addons-for-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eae-logos' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the logo image.
     *
     * @since 1.0.0
     * @param array $logo Logo data.
     * @return string HTML for the logo image.
     */
    protected function render_logo_image(array $logo): string
    {
        if (empty($logo['logo_image']['url'])) {
            return '';
        }

        $image_src = esc_url($logo['logo_image']['url']);
        $image_id = attachment_url_to_postid($image_src);

        if (!$image_id) {
            return sprintf(
                '<img src="%s" alt="%s" class="eae-logo-img" />',
                esc_url($image_src),
                esc_attr($logo['logo_title'] ?? '')
            );
        }

        $settings['image_data'] = ElementifyUtils::get_image_data($image_id, $image_src, 'thumbnail');
        return Group_Control_Image_Size::get_attachment_image_html($settings, 'thumbnail', 'image_data');
    }

    /**
     * Render the widget output.
     *
     * @since 1.0.0
     */
    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        if (empty($settings['logos_list'])) {
            return;
        }

        $this->add_render_attribute([
            'wrapper' => [
                'class' => 'eae-logos',
            ],
            'scroller' => [
                'class' => 'eae-logo-scroller',
                'data-direction' => esc_attr($settings['direction']),
                'data-pause-hover' => esc_attr($settings['pause_on_hover']),
            ],
            'scroller-inner' => [
                'class' => 'eae-logo-scroller__inner scroller__inner',
            ],
        ]);

?>
        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <div <?php $this->print_render_attribute_string('scroller'); ?>>
                <ul <?php $this->print_render_attribute_string('scroller-inner'); ?>>
                    <?php foreach ($settings['logos_list'] as $item):
                        if (empty($item['logo_image']['url'])) {
                            continue;
                        }

                        $logo_link_attr = [
                            'class' => 'eae-logo-link',
                            'aria-label' => esc_attr($item['logo_title'] ?? ''),
                        ];

                        if ('yes' === $settings['enable_links'] && !empty($item['logo_link']['url'])) {
                            $logo_link_attr['href'] = esc_url($item['logo_link']['url']);

                            if ($item['logo_link']['is_external']) {
                                $logo_link_attr['target'] = '_blank';
                            }

                            if ($item['logo_link']['nofollow']) {
                                $logo_link_attr['rel'] = 'nofollow';
                            }
                        }
                    ?>
                        <li class="eae-logo-item">
                            <?php if ('yes' === $settings['enable_links'] && !empty($item['logo_link']['url'])) : ?>
                                <a <?php echo wp_kses_post($this->render_html_attributes($logo_link_attr)); ?>>
                                    <figure class="eae-logo-image">
                                        <?php echo wp_kses_post($this->render_logo_image($item)); ?>
                                    </figure>
                                </a>
                            <?php else : ?>
                                <figure class="eae-logo-image">
                                    <?php echo wp_kses_post($this->render_logo_image($item)); ?>
                                </figure>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
<?php
    }

    /**
     * Utils method to render HTML attributes.
     *
     * @since 1.0.0
     * @param array $attributes Key-value pairs of attributes.
     * @return string Rendered HTML attributes.
     */
    protected function render_html_attributes(array $attributes): string
    {
        $rendered_attributes = [];

        foreach ($attributes as $key => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $rendered_attributes[] = esc_attr($key);
                }
            } elseif (isset($value)) {
                $rendered_attributes[] = sprintf('%s="%s"', esc_attr($key), esc_attr($value));
            }
        }

        return implode(' ', $rendered_attributes);
    }
}
