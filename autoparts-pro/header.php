<?php
/**
 * Header Template
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="masthead">
    <div class="header-container">
        <div class="gear-shift-navigation">
            <!-- Logo -->
            <?php autoparts_pro_the_logo(); ?>
            
            <!-- Vehicle Selector (optional) -->
            <?php if ( get_theme_mod( 'show_vehicle_selector', true ) ) : ?>
                <div class="header-vehicle-selector">
                    <?php autoparts_pro_vehicle_selector(); ?>
                </div>
            <?php endif; ?>
            
            <!-- Primary Navigation -->
            <?php autoparts_pro_gear_shift_menu(); ?>
            
            <!-- Header Actions -->
            <div class="header-actions">
                <!-- Dark Mode Toggle -->
                <?php autoparts_pro_dark_mode_toggle(); ?>
                
                <!-- Wishlist -->
                <?php autoparts_pro_wishlist_button(); ?>
                
                <!-- Search -->
                <button class="search-toggle" aria-label="<?php esc_attr_e( 'Search', 'autoparts-pro' ); ?>">
                    <i class="fas fa-search"></i>
                </button>
                
                <!-- Cart -->
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <a class="cart-toggle" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'Cart', 'autoparts-pro' ); ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
