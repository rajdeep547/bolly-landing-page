<?php

if (!defined('ABSPATH')) {
    exit;
}

class Bolly_Installer
{
    public const PAGE_OPTION = 'bolly_landing_page_id';
    public const PAGE_SLUG = 'bolly-landing';

    public static function activate(): void
    {
        self::create_landing_page();
        flush_rewrite_rules();
    }

    public static function deactivate(): void
    {
        flush_rewrite_rules();
    }

    public static function create_landing_page(): int
    {
        $existing_id = (int) get_option(self::PAGE_OPTION, 0);

        if ($existing_id > 0 && get_post_status($existing_id)) {
            return $existing_id;
        }

        $page_id = wp_insert_post(
            [
                'post_title' => 'Bolly Landing',
                'post_name' => self::PAGE_SLUG,
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_content' => '',
            ],
            true
        );

        if (is_wp_error($page_id)) {
            return 0;
        }

        update_option(self::PAGE_OPTION, $page_id);
        update_post_meta($page_id, '_wp_page_template', 'elementor_canvas');

        if (did_action('elementor/loaded')) {
            self::apply_elementor_template((int) $page_id);
        } else {
            update_post_meta($page_id, '_bolly_pending_elementor_setup', 1);
        }

        return (int) $page_id;
    }

    public static function apply_elementor_template(int $page_id): void
    {
        $section_id = self::generate_elementor_id();
        $column_id = self::generate_elementor_id();
        $widget_id = self::generate_elementor_id();

        $elementor_data = [
            [
                'id' => $section_id,
                'elType' => 'section',
                'isInner' => false,
                'settings' => [
                    'layout' => 'full_width',
                    'gap' => 'no',
                    'height' => 'min-height',
                    'custom_height' => [
                        'unit' => 'vh',
                        'size' => 100,
                    ],
                    'content_position' => 'middle',
                    'padding' => [
                        'unit' => 'px',
                        'top' => '0',
                        'right' => '0',
                        'bottom' => '0',
                        'left' => '0',
                        'isLinked' => true,
                    ],
                ],
                'elements' => [
                    [
                        'id' => $column_id,
                        'elType' => 'column',
                        'isInner' => false,
                        'settings' => [
                            '_column_size' => 100,
                            'padding' => [
                                'unit' => 'px',
                                'top' => '0',
                                'right' => '0',
                                'bottom' => '0',
                                'left' => '0',
                                'isLinked' => true,
                            ],
                        ],
                        'elements' => [
                            [
                                'id' => $widget_id,
                                'elType' => 'widget',
                                'isInner' => false,
                                'widgetType' => 'bolly_landing_page',
                                'settings' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        update_post_meta($page_id, '_elementor_edit_mode', 'builder');
        update_post_meta($page_id, '_elementor_template_type', 'wp-page');
        update_post_meta($page_id, '_elementor_version', defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '3.0.0');
        update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode($elementor_data)));
        delete_post_meta($page_id, '_bolly_pending_elementor_setup');

        if (class_exists('\Elementor\Plugin')) {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
        }
    }

    public static function maybe_finish_elementor_setup(): void
    {
        if (!did_action('elementor/loaded')) {
            return;
        }

        $page_id = (int) get_option(self::PAGE_OPTION, 0);
        if ($page_id <= 0) {
            return;
        }

        if (get_post_meta($page_id, '_bolly_pending_elementor_setup', true)) {
            self::apply_elementor_template($page_id);
        }
    }

    private static function generate_elementor_id(): string
    {
        return substr(md5(uniqid((string) wp_rand(), true)), 0, 7);
    }
}
