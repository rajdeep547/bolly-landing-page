<?php

if (!defined('ABSPATH')) {
    exit;
}

class Bolly_Landing_Page_Widget extends \Elementor\Widget_Base
{
    public function get_name(): string
    {
        return 'bolly_landing_page';
    }

    public function get_title(): string
    {
        return esc_html__('Bolly Landing Hero', 'bolly-landing');
    }

    public function get_icon(): string
    {
        return 'eicon-single-page';
    }

    public function get_categories(): array
    {
        return ['bolly'];
    }

    public function get_keywords(): array
    {
        return ['bolly', 'landing', 'hero', 'shampoo'];
    }

    protected function render(): void
    {
        wp_enqueue_style('bolly-landing');
        wp_enqueue_script('bolly-3d-bottle');

        include BOLLY_LANDING_PATH . 'templates/landing-page.php';
    }
}
