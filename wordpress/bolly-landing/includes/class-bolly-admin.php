<?php

if (!defined('ABSPATH')) {
    exit;
}

class Bolly_Admin
{
    public static function init(): void
    {
        add_action('admin_menu', [self::class, 'register_menu']);
        add_action('admin_notices', [self::class, 'render_dependency_notice']);
        add_action('admin_post_bolly_create_landing_page', [self::class, 'handle_create_page']);
    }

    public static function register_menu(): void
    {
        add_menu_page(
            __('Bolly Landing', 'bolly-landing'),
            __('Bolly Landing', 'bolly-landing'),
            'manage_options',
            'bolly-landing',
            [self::class, 'render_page'],
            'dashicons-art',
            58
        );
    }

    public static function render_dependency_notice(): void
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        if (did_action('elementor/loaded')) {
            return;
        }

        echo '<div class="notice notice-warning"><p>';
        esc_html_e('Bolly Landing Page works best with Elementor installed and activated.', 'bolly-landing');
        echo ' <a href="' . esc_url(admin_url('plugin-install.php?s=elementor&tab=search&type=term')) . '">';
        esc_html_e('Install Elementor', 'bolly-landing');
        echo '</a></p></div>';
    }

    public static function render_page(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $page_id = (int) get_option(Bolly_Installer::PAGE_OPTION, 0);
        $page_url = $page_id > 0 ? get_permalink($page_id) : '';
        $edit_url = $page_id > 0 ? admin_url('post.php?post=' . $page_id . '&action=elementor') : '';
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Bolly Landing Page', 'bolly-landing'); ?></h1>
            <p><?php esc_html_e('Interactive 3D shampoo landing page for WordPress + Elementor.', 'bolly-landing'); ?></p>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><?php esc_html_e('Landing page', 'bolly-landing'); ?></th>
                    <td>
                        <?php if ($page_url) : ?>
                            <a href="<?php echo esc_url($page_url); ?>" target="_blank" rel="noopener noreferrer">
                                <?php echo esc_html(get_the_title($page_id)); ?>
                            </a>
                            <?php if ($edit_url && did_action('elementor/loaded')) : ?>
                                | <a href="<?php echo esc_url($edit_url); ?>"><?php esc_html_e('Edit with Elementor', 'bolly-landing'); ?></a>
                            <?php endif; ?>
                        <?php else : ?>
                            <?php esc_html_e('Not created yet.', 'bolly-landing'); ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Shortcodes', 'bolly-landing'); ?></th>
                    <td>
                        <code>[bolly_landing]</code><br>
                        <code>[bolly_3d_bottle]</code>
                    </td>
                </tr>
            </table>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('bolly_create_landing_page'); ?>
                <input type="hidden" name="action" value="bolly_create_landing_page">
                <?php submit_button(__('Create / Recreate Landing Page', 'bolly-landing'), 'primary'); ?>
            </form>
        </div>
        <?php
    }

    public static function handle_create_page(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('Unauthorized', 'bolly-landing'));
        }

        check_admin_referer('bolly_create_landing_page');

        Bolly_Installer::create_landing_page();

        wp_safe_redirect(admin_url('admin.php?page=bolly-landing&created=1'));
        exit;
    }
}
