<?php
/**
 * Cart Page Template - AutoParts Pro
 * Premium automotive cart with vehicle compatibility check
 */

defined('ABSPATH') || exit;

do_action('autoparts_cart_before', $cart);
?>

<div class="autoparts-cart-page">
    <div class="container">
        <?php if (!WC()->cart->is_empty()) : ?>
            
            <!-- Cart Header -->
            <div class="cart-header">
                <h1 class="page-title"><?php esc_html_e('Shopping Cart', 'autoparts-pro'); ?></h1>
                <?php if (function_exists('autoparts_get_current_vehicle')) : ?>
                    <div class="cart-vehicle-check">
                        <span class="vehicle-label"><?php esc_html_e('Checking compatibility for:', 'autoparts-pro'); ?></span>
                        <span class="current-vehicle"><?php echo esc_html(autoparts_get_current_vehicle()); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Cart Form -->
            <?php do_action('autoparts_cart_form_start'); ?>
            <form class="cart-form" method="post" action="<?php echo esc_url(wc_get_cart_url()); ?>">
                
                <div class="cart-grid">
                    <!-- Cart Items Table -->
                    <div class="cart-items-section">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th class="product-remove"></th>
                                    <th class="product-thumbnail"><?php esc_html_e('Product', 'autoparts-pro'); ?></th>
                                    <th class="product-price"><?php esc_html_e('Price', 'autoparts-pro'); ?></th>
                                    <th class="product-quantity"><?php esc_html_e('Quantity', 'autoparts-pro'); ?></th>
                                    <th class="product-subtotal"><?php esc_html_e('Subtotal', 'autoparts-pro'); ?></th>
                                    <th class="product-compatibility"><?php esc_html_e('Fitment', 'autoparts-pro'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                                    if ($_product && $_product->exists() && WC()->cart->get_cart_item_quantity($product_id, $cart_item_key)) {
                                        ?>
                                        <tr class="cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                                            
                                            <!-- Remove Item -->
                                            <td class="product-remove">
                                                <?php
                                                echo apply_filters(
                                                    'woocommerce_cart_item_remove_link',
                                                    sprintf(
                                                        '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                                        esc_url(WC()->cart->get_remove_item_url($cart_item_key)),
                                                        esc_html__('Remove this item', 'autoparts-pro'),
                                                        absint($product_id),
                                                        esc_attr($_product->get_sku())
                                                    ),
                                                    $cart_item_key
                                                );
                                                ?>
                                            </td>

                                            <!-- Product Thumbnail & Name -->
                                            <td class="product-thumbnail">
                                                <?php
                                                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                                                echo $thumbnail;
                                                ?>
                                                <div class="product-info">
                                                    <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="product-name">
                                                        <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)); ?>
                                                    </a>
                                                    <?php
                                                    // Display part number
                                                    if ($_product->get_meta('_part_number')) {
                                                        echo '<span class="part-number">' . esc_html__('Part #: ', 'autoparts-pro') . esc_html($_product->get_meta('_part_number')) . '</span>';
                                                    }
                                                    
                                                    // Display bulk pricing info
                                                    $bulk_pricing = $_product->get_meta('_bulk_pricing');
                                                    if ($bulk_pricing && is_array($bulk_pricing)) {
                                                        echo '<div class="bulk-pricing-info">';
                                                        foreach ($bulk_pricing as $tier) {
                                                            if ($cart_item['quantity'] >= $tier['min_qty']) {
                                                                echo '<span class="bulk-badge">' . sprintf(esc_html__('Save %s%%', 'autoparts-pro'), $tier['discount']) . '</span>';
                                                            }
                                                        }
                                                        echo '</div>';
                                                    }
                                                    ?>
                                                </div>
                                            </td>

                                            <!-- Price -->
                                            <td class="product-price" data-title="<?php esc_attr_e('Price', 'autoparts-pro'); ?>">
                                                <?php
                                                echo WC()->cart->get_product_price($_product);
                                                if ($_product->is_on_sale()) {
                                                    echo '<span class="original-price">' . wc_price($_product->get_regular_price()) . '</span>';
                                                }
                                                ?>
                                            </td>

                                            <!-- Quantity -->
                                            <td class="product-quantity" data-title="<?php esc_attr_e('Quantity', 'autoparts-pro'); ?>">
                                                <?php
                                                if ($_product->is_sold_individually()) {
                                                    $min_quantity = 1;
                                                    $max_quantity = 1;
                                                } else {
                                                    $min_quantity = 0;
                                                    $max_quantity = $_product->backorders_allowed() ? '' : $_product->get_stock_quantity();
                                                }

                                                woocommerce_quantity_input(array(
                                                    'input_id'    => "quantity_{$cart_item_key}",
                                                    'input_name'  => "cart[{$cart_item_key}][qty]",
                                                    'input_value' => $cart_item['quantity'],
                                                    'max_value'   => $max_quantity,
                                                    'min_value'   => $min_quantity,
                                                    'product_name' => $_product->get_name(),
                                                ));
                                                ?>
                                            </td>

                                            <!-- Subtotal -->
                                            <td class="product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'autoparts-pro'); ?>">
                                                <?php
                                                echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                                                ?>
                                            </td>

                                            <!-- Compatibility Badge -->
                                            <td class="product-compatibility">
                                                <?php
                                                if (function_exists('autoparts_check_compatibility')) {
                                                    $is_compatible = autoparts_check_compatibility($product_id);
                                                    if ($is_compatible) {
                                                        echo '<span class="compatibility-badge compatible"><i class="fas fa-check-circle"></i> ' . esc_html__('Fits', 'autoparts-pro') . '</span>';
                                                    } else {
                                                        echo '<span class="compatibility-badge not-compatible"><i class="fas fa-times-circle"></i> ' . esc_html__('Check Fit', 'autoparts-pro') . '</span>';
                                                    }
                                                } else {
                                                    echo '<span class="compatibility-badge unknown">' . esc_html__('?', 'autoparts-pro') . '</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>

                        <!-- Cart Actions -->
                        <div class="cart-actions">
                            <div class="coupon-section">
                                <label for="coupon_code"><?php esc_html_e('Coupon:', 'autoparts-pro'); ?></label>
                                <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'autoparts-pro'); ?>" />
                                <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'autoparts-pro'); ?>"><?php esc_html_e('Apply coupon', 'autoparts-pro'); ?></button>
                                <?php wp_nonce_field('woocommerce-cart'); ?>
                            </div>
                            
                            <div class="update-cart-section">
                                <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e('Update cart', 'autoparts-pro'); ?>"><?php esc_html_e('Update cart', 'autoparts-pro'); ?></button>
                                <?php wp_nonce_field('woocommerce-cart'); ?>
                            </div>
                        </div>

                        <!-- Continue Shopping -->
                        <div class="continue-shopping">
                            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> <?php esc_html_e('Continue Shopping', 'autoparts-pro'); ?>
                            </a>
                        </div>
                    </div>

                    <!-- Cart Totals -->
                    <div class="cart-totals-section">
                        <div class="cart-totals">
                            <h3><?php esc_html_e('Cart Totals', 'autoparts-pro'); ?></h3>
                            
                            <table class="shop_table">
                                <tbody>
                                    <tr class="cart-subtotal">
                                        <th><?php esc_html_e('Subtotal', 'autoparts-pro'); ?></th>
                                        <td data-title="<?php esc_attr_e('Subtotal', 'autoparts-pro'); ?>">
                                            <?php WC()->cart->subtotals(); ?>
                                        </td>
                                    </tr>

                                    <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                                        <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                                            <th><?php esc_html_e('Coupon:', 'autoparts-pro'); ?> <?php echo esc_html(wc_cart_totals_coupon_label($coupon)); ?></th>
                                            <td data-title="<?php echo esc_attr(wc_cart_totals_coupon_label($coupon, false)); ?>">
                                                <?php wc_cart_totals_coupon_html($coupon); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                                        <?php do_action('woocommerce_cart_totals_before_shipping'); ?>
                                        <?php wc_cart_totals_shipping_html(); ?>
                                        <?php do_action('woocommerce_cart_totals_after_shipping'); ?>
                                    <?php elseif (WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc')) : ?>
                                        <tr class="shipping">
                                            <th><?php esc_html_e('Shipping', 'autoparts-pro'); ?></th>
                                            <td data-title="<?php esc_attr_e('Shipping', 'autoparts-pro'); ?>">
                                                <?php woocommerce_shipping_calculator(); ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php foreach (WC()->cart->get_fees() as $fee) : ?>
                                        <tr class="fee">
                                            <th><?php echo esc_html($fee->name); ?></th>
                                            <td data-title="<?php echo esc_attr($fee->name); ?>">
                                                <?php wc_cart_totals_fee_html($fee); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
                                        <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                                            <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                                                <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                                                    <th><?php echo esc_html($tax->label); ?></th>
                                                    <td data-title="<?php echo esc_attr($tax->label); ?>">
                                                        <?php echo wp_kses_post($tax->formatted_tax_amount); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr class="tax-total">
                                                <th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                                                <td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>">
                                                    <?php wc_cart_totals_tax_html(); ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <tr class="order-total">
                                        <th><?php esc_html_e('Total', 'autoparts-pro'); ?></th>
                                        <td data-title="<?php esc_attr_e('Total', 'autoparts-pro'); ?>">
                                            <strong><?php wc_cart_totals_order_total_html(); ?></strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Checkout Button -->
                            <div class="checkout-button">
                                <?php do_action('woocommerce_proceed_to_checkout'); ?>
                            </div>

                            <!-- Trust Badges -->
                            <div class="trust-badges">
                                <div class="trust-item">
                                    <i class="fas fa-shield-alt"></i>
                                    <span><?php esc_html_e('Secure Checkout', 'autoparts-pro'); ?></span>
                                </div>
                                <div class="trust-item">
                                    <i class="fas fa-truck"></i>
                                    <span><?php esc_html_e('Fast Shipping', 'autoparts-pro'); ?></span>
                                </div>
                                <div class="trust-item">
                                    <i class="fas fa-undo"></i>
                                    <span><?php esc_html_e('Easy Returns', 'autoparts-pro'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php do_action('autoparts_cart_form_end'); ?>

        <?php else : ?>
            
            <!-- Empty Cart -->
            <div class="cart-empty">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h2><?php esc_html_e('Your cart is currently empty.', 'autoparts-pro'); ?></h2>
                <p><?php esc_html_e('Ready to upgrade your ride? Browse our premium auto parts.', 'autoparts-pro'); ?></p>
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-primary btn-large">
                    <i class="fas fa-store"></i> <?php esc_html_e('Start Shopping', 'autoparts-pro'); ?>
                </a>
                
                <!-- Quick Links -->
                <div class="empty-cart-links">
                    <h4><?php esc_html_e('Popular Categories:', 'autoparts-pro'); ?></h4>
                    <ul>
                        <?php
                        $categories = get_terms(array(
                            'taxonomy'   => 'product_cat',
                            'number'     => 4,
                            'hide_empty' => true,
                        ));
                        
                        if (!empty($categories) && !is_wp_error($categories)) {
                            foreach ($categories as $category) {
                                echo '<li><a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php do_action('autoparts_cart_after', $cart); ?>
