<?php
/**
 * Checkout Form - AutoParts Pro
 * Premium checkout with vehicle compatibility verification
 */

if (!defined('ABSPATH')) {
    exit;
}

// If checkout registration is disabled and not logged in, user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'autoparts-pro')));
    return;
}

do_action('autoparts_checkout_before', $checkout);
?>

<div class="autoparts-checkout-page">
    <div class="container">
        
        <!-- Checkout Header -->
        <div class="checkout-header">
            <h1 class="page-title"><?php esc_html_e('Checkout', 'autoparts-pro'); ?></h1>
            <?php if (function_exists('autoparts_get_current_vehicle')) : ?>
                <div class="checkout-vehicle-verify">
                    <i class="fas fa-car"></i>
                    <span><?php esc_html_e('Verifying fitment for:', 'autoparts-pro'); ?> <strong><?php echo esc_html(autoparts_get_current_vehicle()); ?></strong></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Checkout Progress Steps -->
        <div class="checkout-progress">
            <div class="progress-step active">
                <span class="step-number">1</span>
                <span class="step-label"><?php esc_html_e('Information', 'autoparts-pro'); ?></span>
            </div>
            <div class="progress-step">
                <span class="step-number">2</span>
                <span class="step-label"><?php esc_html_e('Shipping', 'autoparts-pro'); ?></span>
            </div>
            <div class="progress-step">
                <span class="step-number">3</span>
                <span class="step-label"><?php esc_html_e('Payment', 'autoparts-pro'); ?></span>
            </div>
        </div>

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

            <div class="checkout-grid">
                
                <!-- Checkout Form Sections -->
                <div class="checkout-form-section">
                    
                    <?php if ($checkout->get_checkout_fields()) : ?>

                        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                        <div class="col2-set" id="customer_details">
                            <div class="col-1">
                                <?php do_action('woocommerce_checkout_billing'); ?>
                            </div>

                            <div class="col-2">
                                <?php do_action('woocommerce_checkout_shipping'); ?>
                            </div>
                        </div>

                        <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                    <?php endif; ?>
                    
                    <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
                    
                    <h3 id="order_review_heading"><?php esc_html_e('Order Summary', 'autoparts-pro'); ?></h3>
                    
                    <?php do_action('woocommerce_checkout_before_order_review'); ?>

                    <div id="order_review" class="woocommerce-check-order-review">
                        <table class="shop_table woocommerce-check-order-review">
                            <thead>
                                <tr>
                                    <th class="product-name"><?php esc_html_e('Product', 'autoparts-pro'); ?></th>
                                    <th class="product-total"><?php esc_html_e('Subtotal', 'autoparts-pro'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                do_action('woocommerce_review_order_before_cart_contents');

                                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

                                    if ($_product && $_product->exists()) {
                                        ?>
                                        <tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                                            <td class="product-name">
                                                <?php
                                                echo apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;';
                                                echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key);
                                                echo wc_get_formatted_cart_item_data($cart_item);
                                                
                                                // Show part number
                                                if ($_product->get_meta('_part_number')) {
                                                    echo '<br><small class="part-number">' . esc_html__('Part #:', 'autoparts-pro') . ' ' . esc_html($_product->get_meta('_part_number')) . '</small>';
                                                }
                                                ?>
                                            </td>
                                            <td class="product-total">
                                                <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }

                                do_action('woocommerce_review_order_after_cart_contents');
                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="cart-subtotal">
                                    <th><?php esc_html_e('Subtotal', 'autoparts-pro'); ?></th>
                                    <td><?php wc_cart_totals_subtotal_html(); ?></td>
                                </tr>

                                <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                                    <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                                        <th><?php esc_html_e('Coupon:', 'autoparts-pro'); ?> <?php echo esc_html(wc_cart_totals_coupon_label($coupon)); ?></th>
                                        <td><?php wc_cart_totals_coupon_html($coupon); ?></td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                                    <?php do_action('woocommerce_review_order_before_shipping'); ?>
                                    <?php wc_cart_totals_shipping_html(); ?>
                                    <?php do_action('woocommerce_review_order_after_shipping'); ?>
                                <?php endif; ?>

                                <?php foreach (WC()->cart->get_fees() as $fee) : ?>
                                    <tr class="fee">
                                        <th><?php echo esc_html($fee->name); ?></th>
                                        <td><?php wc_cart_totals_fee_html($fee); ?></td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
                                    <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                                        <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                                            <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                                                <th><?php echo esc_html($tax->label); ?></th>
                                                <td><?php echo wp_kses_post($tax->formatted_tax_amount); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr class="tax-total">
                                            <th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                                            <td><?php wc_cart_totals_tax_html(); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <tr class="order-total">
                                    <th><?php esc_html_e('Total', 'autoparts-pro'); ?></th>
                                    <td><?php wc_cart_totals_order_total_html(); ?></td>
                                </tr>
                            </tfoot>
                        </table>

                        <div id="payment" class="woocommerce-check-payment">
                            <?php if (WC()->cart->needs_payment()) : ?>
                                <ul id="payment_methods" class="wc_payment_methods payment_methods methods">
                                    <?php
                                    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

                                    if ($available_gateways) {
                                        current(array_keys($available_gateways));
                                    }

                                    foreach ($available_gateways as $gateway) {
                                        wc_get_template('checkout/payment-method.php', array('available_gateways' => $available_gateways, 'current_gateway' => $gateway));
                                    }
                                    ?>
                                </ul>

                                <div class="form-row place-order">
                                    <noscript><?php esc_html_e('JavaScript is required to check out.', 'autoparts-pro'); ?></noscript>
                                    <?php wc_get_template('checkout/terms.php'); ?>
                                    <?php do_action('woocommerce_review_order_before_submit'); ?>
                                    
                                    <button type="submit" class="button alt wp-element-button" id="place_order" value="<?php esc_attr_e('Place order', 'autoparts-pro'); ?>">
                                        <i class="fas fa-lock"></i> <?php esc_html_e('Place Secure Order', 'autoparts-pro'); ?>
                                    </button>
                                    
                                    <?php do_action('woocommerce_review_order_after_submit'); ?>
                                    <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
                                </div>
                            <?php else : ?>
                                <div class="form-row place-order">
                                    <?php wc_get_template('checkout/terms.php'); ?>
                                    <?php do_action('woocommerce_review_order_before_submit'); ?>
                                    
                                    <button type="submit" class="button alt wp-element-button" id="place_order" value="<?php esc_attr_e('Place order', 'autoparts-pro'); ?>">
                                        <i class="fas fa-check-circle"></i> <?php esc_html_e('Complete Order', 'autoparts-pro'); ?>
                                    </button>
                                    
                                    <?php do_action('woocommerce_review_order_after_submit'); ?>
                                    <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php do_action('woocommerce_checkout_after_order_review'); ?>

                </div>

                <!-- Order Summary Sidebar (Mobile: Top) -->
                <div class="checkout-order-summary">
                    <div class="order-summary-sticky">
                        <h3><?php esc_html_e('Your Order', 'autoparts-pro'); ?></h3>
                        
                        <div class="order-summary-products">
                            <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) : ?>
                                <?php
                                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                if ($_product && $_product->exists()) {
                                    ?>
                                    <div class="summary-product-item">
                                        <div class="summary-product-image">
                                            <?php echo $_product->get_image(array(60, 60)); ?>
                                        </div>
                                        <div class="summary-product-details">
                                            <span class="summary-product-name"><?php echo esc_html($_product->get_name()); ?></span>
                                            <span class="summary-product-qty"><?php echo sprintf('%s × %d', esc_html__('Qty', 'autoparts-pro'), $cart_item['quantity']); ?></span>
                                        </div>
                                        <div class="summary-product-price">
                                            <?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-summary-totals">
                            <div class="summary-row">
                                <span><?php esc_html_e('Subtotal', 'autoparts-pro'); ?></span>
                                <span><?php echo WC()->cart->get_subtotal(); ?></span>
                            </div>
                            <?php if (WC()->cart->needs_shipping()) : ?>
                                <div class="summary-row">
                                    <span><?php esc_html_e('Shipping', 'autoparts-pro'); ?></span>
                                    <span><?php esc_html_e('Calculated at next step', 'autoparts-pro'); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="summary-row total">
                                <span><?php esc_html_e('Total', 'autoparts-pro'); ?></span>
                                <span><?php echo WC()->cart->get_total(); ?></span>
                            </div>
                        </div>

                        <!-- Trust Badges -->
                        <div class="checkout-trust-badges">
                            <div class="trust-badge">
                                <i class="fas fa-shield-alt"></i>
                                <span><?php esc_html_e('SSL Secured', 'autoparts-pro'); ?></span>
                            </div>
                            <div class="trust-badge">
                                <i class="fas fa-credit-card"></i>
                                <span><?php esc_html_e('Secure Payment', 'autoparts-pro'); ?></span>
                            </div>
                            <div class="trust-badge">
                                <i class="fas fa-undo"></i>
                                <span><?php esc_html_e('30-Day Returns', 'autoparts-pro'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<?php do_action('autoparts_checkout_after', $checkout); ?>
