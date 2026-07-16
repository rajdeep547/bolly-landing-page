<?php

if (!defined('ABSPATH')) {
    exit;
}

class Bolly_3D_Bottle_Widget extends \Elementor\Widget_Base
{
    public function get_name(): string
    {
        return 'bolly_3d_bottle';
    }

    public function get_title(): string
    {
        return esc_html__('Bolly 3D Bottle', 'bolly-landing');
    }

    public function get_icon(): string
    {
        return 'eicon-product-images';
    }

    public function get_categories(): array
    {
        return ['bolly'];
    }

    public function get_keywords(): array
    {
        return ['bolly', '3d', 'bottle', 'shampoo', 'product'];
    }

    protected function register_controls(): void
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Settings', 'bolly-landing'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => esc_html__('Viewer Height', 'bolly-landing'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => ['min' => 200, 'max' => 900],
                    'vh' => ['min' => 20, 'max' => 100],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 520,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bolly-3d-viewer' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        wp_enqueue_style('bolly-landing');
        wp_enqueue_script('bolly-3d-bottle');

        echo '<div class="bolly-3d-viewer" data-bolly-3d aria-label="' . esc_attr__('Interactive shampoo bottle', 'bolly-landing') . '"></div>';
    }
}
