<?php
/**
 * Bolly landing page hero template.
 *
 * @package Bolly_Landing
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<section class="bolly-landing" aria-label="<?php esc_attr_e('Bolly shampoo hero', 'bolly-landing'); ?>">
    <div class="bolly-landing__grain" aria-hidden="true"></div>
    <div class="bolly-landing__glow" aria-hidden="true"></div>

    <header class="bolly-header">
        <a class="bolly-logo" href="<?php echo esc_url(home_url('/')); ?>">bolly</a>

        <nav class="bolly-nav" aria-label="<?php esc_attr_e('Primary navigation', 'bolly-landing'); ?>">
            <a href="#shop" class="bolly-nav__link">
                <?php esc_html_e('Shop', 'bolly-landing'); ?>
                <span class="bolly-nav__chevron" aria-hidden="true">&#8964;</span>
            </a>
            <a href="#about" class="bolly-nav__link"><?php esc_html_e('About', 'bolly-landing'); ?></a>
            <a href="#blog" class="bolly-nav__link"><?php esc_html_e('Blog', 'bolly-landing'); ?></a>
            <a href="#contact" class="bolly-nav__link"><?php esc_html_e('Contact', 'bolly-landing'); ?></a>
        </nav>

        <a class="bolly-cart" href="#cart">
            <span class="bolly-cart__label"><?php esc_html_e('Cart', 'bolly-landing'); ?></span>
            <span class="bolly-cart__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 6h15l-1.5 9h-12z"></path>
                    <circle cx="9" cy="20" r="1.5"></circle>
                    <circle cx="18" cy="20" r="1.5"></circle>
                    <path d="M6 6L5 3H2"></path>
                </svg>
            </span>
        </a>
    </header>

    <div class="bolly-hero">
        <div class="bolly-hero__left">
            <div class="bolly-hero__tagline">
                <span class="bolly-hero__tagline-text"><?php esc_html_e('FROM ROOT', 'bolly-landing'); ?></span>
                <span class="bolly-hero__tagline-pill"><?php esc_html_e('TO SHINE', 'bolly-landing'); ?></span>
            </div>
            <h1 class="bolly-hero__headline">
                <span><?php esc_html_e('KNOCK', 'bolly-landing'); ?></span>
                <span><?php esc_html_e('OUT', 'bolly-landing'); ?></span>
                <span><?php esc_html_e('FLAKES', 'bolly-landing'); ?></span>
            </h1>
        </div>

        <div class="bolly-hero__center">
            <div class="bolly-3d-viewer" data-bolly-3d aria-label="<?php esc_attr_e('Interactive shampoo bottle', 'bolly-landing'); ?>"></div>
        </div>

        <div class="bolly-hero__right">
            <p class="bolly-hero__description">
                <?php esc_html_e('Journey in to the wonderful world of shampoo', 'bolly-landing'); ?>
            </p>
            <a class="bolly-hero__cta" href="#explore">
                <span><?php esc_html_e('EXPLORE MORE', 'bolly-landing'); ?></span>
                <span class="bolly-hero__cta-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M7 17L17 7"></path>
                        <path d="M9 7h8v8"></path>
                    </svg>
                </span>
            </a>
        </div>
    </div>
</section>
