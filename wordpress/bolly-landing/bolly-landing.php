<?php
/**
 * Plugin Name: Bolly Landing Page
 * Plugin URI: https://github.com/example/bolly-landing
 * Description: Recreates the Bolly shampoo landing page with an interactive 3D product bottle for Elementor.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Frontend Assignment
 * Text Domain: bolly-landing
 * License: MIT
 */

if (!defined('ABSPATH')) {
    exit;
}

define('BOLLY_LANDING_VERSION', '1.0.0');
define('BOLLY_LANDING_PATH', plugin_dir_path(__FILE__));
define('BOLLY_LANDING_URL', plugin_dir_url(__FILE__));

require_once BOLLY_LANDING_PATH . 'includes/class-bolly-installer.php';
require_once BOLLY_LANDING_PATH . 'includes/class-bolly-admin.php';

final class Bolly_Landing_Plugin
{
    /** @var Bolly_Landing_Plugin|null */
    private static $instance = null;

    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
        add_action('init', [$this, 'register_page_template']);
        add_action('elementor/loaded', [Bolly_Installer::class, 'maybe_finish_elementor_setup']);

        add_shortcode('bolly_landing', [$this, 'render_landing_shortcode']);
        add_shortcode('bolly_3d_bottle', [$this, 'render_bottle_shortcode']);

        add_action('elementor/widgets/register', [$this, 'register_elementor_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'register_elementor_category']);

        Bolly_Admin::init();
    }

    public function register_assets()
    {
        wp_register_style(
            'bolly-landing-fonts',
            'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap',
            [],
            BOLLY_LANDING_VERSION
        );

        wp_register_style(
            'bolly-landing',
            BOLLY_LANDING_URL . 'assets/css/bolly-landing.css',
            ['bolly-landing-fonts'],
            BOLLY_LANDING_VERSION
        );

        wp_register_script(
            'three-js',
            BOLLY_LANDING_URL . 'assets/vendor/three.min.js',
            [],
            '0.128.0',
            true
        );

        wp_register_script(
            'three-orbit-controls',
            BOLLY_LANDING_URL . 'assets/vendor/OrbitControls.js',
            ['three-js'],
            '0.128.0',
            true
        );

        wp_register_script(
            'bolly-3d-bottle',
            BOLLY_LANDING_URL . 'assets/js/bolly-3d-bottle.js',
            ['three-js', 'three-orbit-controls'],
            BOLLY_LANDING_VERSION,
            true
        );
    }

    public function register_page_template()
    {
        add_filter('theme_page_templates', static function ($templates) {
            $templates['bolly-canvas.php'] = __('Bolly Canvas', 'bolly-landing');
            return $templates;
        });

        add_filter('template_include', static function ($template) {
            if (!is_page()) {
                return $template;
            }

            $selected = get_page_template_slug();
            if ($selected === 'bolly-canvas.php') {
                return BOLLY_LANDING_PATH . 'templates/bolly-canvas.php';
            }

            return $template;
        });
    }

    public function enqueue_landing_assets()
    {
        wp_enqueue_style('bolly-landing');
        wp_enqueue_script('bolly-3d-bottle');
    }

    public function render_landing_shortcode()
    {
        $this->enqueue_landing_assets();

        ob_start();
        include BOLLY_LANDING_PATH . 'templates/landing-page.php';
        return (string) ob_get_clean();
    }

    public function render_bottle_shortcode()
    {
        $this->enqueue_landing_assets();

        return '<div class="bolly-3d-viewer" data-bolly-3d aria-label="' . esc_attr__('Interactive shampoo bottle', 'bolly-landing') . '"></div>';
    }

    public function register_elementor_category($elements_manager)
    {
        $elements_manager->add_category(
            'bolly',
            [
                'title' => esc_html__('Bolly', 'bolly-landing'),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    public function register_elementor_widgets($widgets_manager)
    {
        if (!did_action('elementor/loaded')) {
            return;
        }

        require_once BOLLY_LANDING_PATH . 'includes/class-bolly-elementor-widget.php';
        require_once BOLLY_LANDING_PATH . 'includes/class-bolly-landing-elementor-widget.php';

        $widgets_manager->register(new Bolly_3D_Bottle_Widget());
        $widgets_manager->register(new Bolly_Landing_Page_Widget());
    }
}

register_activation_hook(__FILE__, [Bolly_Installer::class, 'activate']);
register_deactivation_hook(__FILE__, [Bolly_Installer::class, 'deactivate']);

add_action('plugins_loaded', static function () {
    Bolly_Landing_Plugin::instance();
});
