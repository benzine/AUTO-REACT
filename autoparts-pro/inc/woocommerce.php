<?php
/**
 * WooCommerce Integration for AutoParts Pro
 *
 * @package AutoParts_Pro
 */

namespace AutoPartsPro\WooCommerce;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Declare WooCommerce support
 */
function declare_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', __NAMESPACE__ . '\\declare_support');

/**
 * Change number of products per page
 */
function products_per_page($columns) {
    return get_theme_mod('autoparts_products_per_page', 12);
}
add_filter('loop_shop_per_page', __NAMESPACE__ . '\\products_per_page');

/**
 * Change columns in product archives
 */
function loop_columns($columns) {
    return get_theme_mod('autoparts_products_columns', 4);
}
add_filter('loop_shop_columns', __NAMESPACE__ . '\\loop_columns');

/**
 * Remove default WooCommerce wrapper
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Add custom wrapper start
 */
function wrapper_start() {
    ?>
    <main id="primary" class="site-main autoparts-woo-main">
        <div class="autoparts-container">
    <?php
}
add_action('woocommerce_before_main_content', __NAMESPACE__ . '\\wrapper_start', 10);

/**
 * Add custom wrapper end
 */
function wrapper_end() {
    ?>
        </div>
    </main>
    <?php
}
add_action('woocommerce_after_main_content', __NAMESPACE__ . '\\wrapper_end', 10);

/**
 * Remove sidebar from single product
 */
function remove_sidebar_single_product($sidebar) {
    if (is_product()) {
        return false;
    }
    return $sidebar;
}
add_filter('is_active_sidebar', __NAMESPACE__ . '\\remove_sidebar_single_product');

/**
 * Customize product tabs
 */
function customize_tabs($tabs) {
    // Reorder tabs
    $new_order = array(
        'description' => __('Description', 'autoparts-pro'),
        'additional_information' => __('Specifications', 'autoparts-pro'),
        'reviews' => __('Reviews', 'autoparts-pro'),
        'compatibility' => __('Compatibility', 'autoparts-pro')
    );

    foreach ($new_order as $key => $label) {
        if (isset($tabs[$key])) {
            $tabs[$key]['title'] = $label;
        }
    }

    // Add compatibility tab
    $tabs['compatibility'] = array(
        'title' => __('Compatibility', 'autoparts-pro'),
        'priority' => 30,
        'callback' => __NAMESPACE__ . '\\compatibility_tab_content'
    );

    return $tabs;
}
add_filter('woocommerce_product_tabs', __NAMESPACE__ . '\\customize_tabs');

/**
 * Compatibility tab content
 */
function compatibility_tab_content() {
    global $product;
    
    $compatible_vehicles = get_post_meta($product->get_id(), '_compatible_vehicles', true);
    
    if ($compatible_vehicles) {
        echo '<h2>' . __('Compatible Vehicles', 'autoparts-pro') . '</h2>';
        echo '<div class="autoparts-compatibility-list">';
        
        if (is_array($compatible_vehicles)) {
            foreach ($compatible_vehicles as $vehicle) {
                echo '<div class="autoparts-vehicle-item">';
                echo '<span class="autoparts-vehicle-year">' . esc_html($vehicle['year']) . '</span> ';
                echo '<span class="autoparts-vehicle-make">' . esc_html($vehicle['make']) . '</span> ';
                echo '<span class="autoparts-vehicle-model">' . esc_html($vehicle['model']) . '</span>';
                if (!empty($vehicle['engine'])) {
                    echo ' - <span class="autoparts-vehicle-engine">' . esc_html($vehicle['engine']) . '</span>';
                }
                echo '</div>';
            }
        }
        
        echo '</div>';
    } else {
        echo '<p>' . __('No vehicle compatibility information available.', 'autoparts-pro') . '</p>';
    }
}

/**
 * Add part number to product data
 */
function add_part_number_to_product_data($product_data) {
    global $product;
    
    $part_number = get_post_meta($product->get_id(), '_part_number', true);
    $oem_number = get_post_meta($product->get_id(), '_oem_number', true);
    
    if ($part_number) {
        $product_data['Part Number'] = $part_number;
    }
    
    if ($oem_number) {
        $product_data['OEM Number'] = $oem_number;
    }
    
    return $product_data;
}
add_filter('woocommerce_product_additional_information', __NAMESPACE__ . '\\add_part_number_to_product_data');

/**
 * Add bulk pricing display
 */
function bulk_pricing_display($html, $product) {
    $bulk_prices = get_post_meta($product->get_id(), '_bulk_pricing', true);
    
    if ($bulk_prices && is_array($bulk_prices)) {
        $html .= '<div class="autoparts-bulk-pricing">';
        $html .= '<h4>' . __('Bulk Pricing', 'autoparts-pro') . '</h4>';
        $html .= '<ul class="autoparts-bulk-pricing-list">';
        
        foreach ($bulk_prices as $tier) {
            $min_qty = $tier['min_qty'];
            $discount = $tier['discount'];
            $html .= '<li>';
            $html .= sprintf(__('Buy %d+ and save %d%%', 'autoparts-pro'), $min_qty, $discount);
            $html .= '</li>';
        }
        
        $html .= '</ul>';
        $html .= '</div>';
    }
    
    return $html;
}
add_filter('woocommerce_get_price_html', __NAMESPACE__ . '\\bulk_pricing_display', 10, 2);

/**
 * Add stock status with color coding
 */
function stock_status_with_color($availability, $product) {
    if (!$product->is_in_stock()) {
        return '<span class="autoparts-stock-status out-of-stock">' . __('Out of Stock', 'autoparts-pro') . '</span>';
    }
    
    $stock_quantity = $product->get_stock_quantity();
    
    if ($stock_quantity !== null && $stock_quantity <= 5) {
        return '<span class="autoparts-stock-status low-stock">' . 
               sprintf(__('Only %d left in stock', 'autoparts-pro'), $stock_quantity) . 
               '</span>';
    }
    
    return '<span class="autoparts-stock-status in-stock">' . __('In Stock', 'autoparts-pro') . '</span>';
}
add_filter('woocommerce_get_availability', __NAMESPACE__ . '\\stock_status_with_color', 10, 2);

/**
 * Add wishlist button to product loops
 */
function add_wishlist_button_loop() {
    global $product;
    ?>
    <button class="autoparts-wishlist-btn autoparts-wishlist-btn-loop" 
            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
            aria-label="<?php esc_attr_e('Add to wishlist', 'autoparts-pro'); ?>">
        <svg class="icon-heart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
        </svg>
    </button>
    <?php
}
add_action('woocommerce_after_shop_loop_item_title', __NAMESPACE__ . '\\add_wishlist_button_loop', 15);

/**
 * Add wishlist button to single product
 */
function add_wishlist_button_single() {
    global $product;
    ?>
    <button class="autoparts-wishlist-btn autoparts-wishlist-btn-single" 
            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
            aria-label="<?php esc_attr_e('Add to wishlist', 'autoparts-pro'); ?>">
        <svg class="icon-heart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
        </svg>
        <span><?php _e('Add to Wishlist', 'autoparts-pro'); ?></span>
    </button>
    <?php
}
add_action('woocommerce_single_product_summary', __NAMESPACE__ . '\\add_wishlist_button_single', 35);

/**
 * Add share buttons to single product
 */
function add_share_buttons() {
    global $product;
    $product_url = get_permalink($product->get_id());
    $product_title = rawurlencode($product->get_name());
    ?>
    <div class="autoparts-share-buttons">
        <span class="autoparts-share-label"><?php _e('Share:', 'autoparts-pro'); ?></span>
        
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($product_url); ?>" 
           target="_blank" 
           rel="noopener noreferrer" 
           class="autoparts-share-btn facebook"
           aria-label="<?php esc_attr_e('Share on Facebook', 'autoparts-pro'); ?>">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        
        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($product_url); ?>&text=<?php echo $product_title; ?>" 
           target="_blank" 
           rel="noopener noreferrer" 
           class="autoparts-share-btn twitter"
           aria-label="<?php esc_attr_e('Share on Twitter', 'autoparts-pro'); ?>">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
        </a>
        
        <a href="https://wa.me/?text=<?php echo $product_title; ?>%20<?php echo urlencode($product_url); ?>" 
           target="_blank" 
           rel="noopener noreferrer" 
           class="autoparts-share-btn whatsapp"
           aria-label="<?php esc_attr_e('Share on WhatsApp', 'autoparts-pro'); ?>">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
        </a>
        
        <button class="autoparts-share-btn copy-link" 
                data-url="<?php echo esc_url($product_url); ?>"
                aria-label="<?php esc_attr_e('Copy link', 'autoparts-pro'); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        </button>
    </div>
    <?php
}
add_action('woocommerce_single_product_summary', __NAMESPACE__ . '\\add_share_buttons', 40);

/**
 * Related products arguments
 */
function related_products_args($args) {
    $args['posts_per_page'] = get_theme_mod('autoparts_related_products_count', 4);
    $args['columns'] = get_theme_mod('autoparts_related_products_columns', 4);
    return $args;
}
add_filter('woocommerce_output_related_products_args', __NAMESPACE__ . '\\related_products_args');

/**
 * Upsell products arguments
 */
function upsell_products_args($args) {
    $args['posts_per_page'] = get_theme_mod('autoparts_upsell_products_count', 4);
    $args['columns'] = get_theme_mod('autoparts_upsell_products_columns', 4);
    return $args;
}
add_filter('woocommerce_upsell_display_args', __NAMESPACE__ . '\\upsell_products_args');

/**
 * Mini cart fragments update
 */
function mini_cart_fragments($fragments) {
    ob_start();
    ?>
    <span class="autoparts-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['span.autoparts-cart-count'] = ob_get_clean();

    ob_start();
    ?>
    <span class="autoparts-cart-total"><?php echo WC()->cart->get_cart_total(); ?></span>
    <?php
    $fragments['span.autoparts-cart-total'] = ob_get_clean();

    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', __NAMESPACE__ . '\\mini_cart_fragments');

/**
 * Change add to cart button text
 */
function add_to_cart_text($text, $product) {
    if ($product->is_type('simple')) {
        return __('Add to Cart', 'autoparts-pro');
    }
    return $text;
}
add_filter('woocommerce_product_add_to_cart_text', __NAMESPACE__ . '\\add_to_cart_text', 10, 2);

/**
 * Add QR code generator
 */
function qr_code_generator() {
    global $product;
    $product_url = get_permalink($product->get_id());
    $qr_api = 'https://api.qrserver.com/v1/create-qr-code/';
    $qr_url = $qr_api . '?size=150x150&data=' . urlencode($product_url);
    ?>
    <div class="autoparts-qr-code">
        <img src="<?php echo esc_url($qr_url); ?>" alt="<?php esc_attr_e('QR Code', 'autoparts-pro'); ?>">
        <span><?php _e('Scan to view', 'autoparts-pro'); ?></span>
    </div>
    <?php
}
add_action('woocommerce_single_product_summary', __NAMESPACE__ . '\\qr_code_generator', 45);
