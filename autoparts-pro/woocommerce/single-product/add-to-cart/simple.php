<?php
/**
 * Add to Cart - Simple Product with Bulk Pricing
 * 
 * @package AutoParts_Pro
 */

defined('ABSPATH') || exit;

global $product;

if (!$product->is_purchasable()) {
    return;
}

echo wc_get_stock_html($product);

if ($product->is_in_stock()) : ?>

    <div class="autoparts-add-to-cart-wrapper">
        <!-- Bulk Pricing Tiers -->
        <?php
        $bulk_pricing = get_post_meta($product->get_id(), '_autoparts_bulk_pricing', true);
        if (!empty($bulk pricing) && is_array($bulk_pricing)) :
            ?>
            <div class="bulk-pricing-tiers">
                <h4><?php esc_html_e('Bulk Discounts', 'autoparts-pro'); ?></h4>
                <ul class="pricing-tiers-list">
                    <?php foreach ($bulk_pricing as $tier) : ?>
                        <li class="pricing-tier">
                            <span class="quantity-range">
                                <?php echo esc_html(sprintf(__('Buy %d-%d', 'autoparts-pro'), $tier['min_qty'], $tier['max_qty'])); ?>
                            </span>
                            <span class="discount-badge">
                                <?php echo esc_html(sprintf(__('%d%% OFF', 'autoparts-pro'), $tier['discount_percent'])); ?>
                            </span>
                            <span class="tier-price">
                                <?php 
                                $tier_price = $product->get_regular_price() * (1 - $tier['discount_percent'] / 100);
                                echo wc_price($tier_price);
                                ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
            <div class="cart-input-group">
                <?php do_action('woocommerce_before_add_to_cart_quantity'); ?>

                <div class="quantity-control">
                    <label for="quantity-<?php echo esc_attr($product->get_id()); ?>" class="screen-reader-text">
                        <?php echo esc_html__('Quantity', 'autoparts-pro'); ?>
                    </label>
                    
                    <button type="button" class="qty-btn qty-minus" aria-label="<?php esc_attr_e('Decrease quantity', 'autoparts-pro'); ?>">
                        <i class="ap-icon-minus"></i>
                    </button>
                    
                    <input type="number" 
                           id="quantity-<?php echo esc_attr($product->get_id()); ?>" 
                           class="input-text qty text" 
                           step="<?php echo esc_attr($step); ?>" 
                           min="<?php echo esc_attr($min_value); ?>" 
                           max="<?php echo esc_attr(0 < $max_value ? $max_value : ''); ?>" 
                           name="quantity" 
                           value="<?php echo esc_attr($input_value); ?>" 
                           title="<?php echo esc_attr_x('Qty', 'Product quantity input tooltip', 'autoparts-pro'); ?>" 
                           size="4" 
                           pattern="<?php echo esc_attr($pattern); ?>" 
                           inputmode="<?php echo esc_attr($inputmode); ?>" />
                    
                    <button type="button" class="qty-btn qty-plus" aria-label="<?php esc_attr_e('Increase quantity', 'autoparts-pro'); ?>">
                        <i class="ap-icon-plus"></i>
                    </button>
                </div>

                <?php do_action('woocommerce_after_add_to_cart_quantity'); ?>

                <button type="submit" 
                        name="add-to-cart" 
                        value="<?php echo esc_attr($product->get_id()); ?>" 
                        class="single_add_to_cart_button button alt"
                        data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                    <i class="ap-icon-cart"></i>
                    <?php echo esc_html($product->single_add_to_cart_text()); ?>
                </button>
            </div>

            <?php do_action('woocommerce_after_add_to_cart_button'); ?>

            <!-- Quick compatibility check -->
            <div class="quick-compatibility-check">
                <p class="compatibility-status">
                    <i class="ap-icon-check-circle"></i>
                    <span><?php esc_html_e('Check compatibility with your vehicle', 'autoparts-pro'); ?></span>
                </p>
                <button type="button" class="button button-small check-compatibility-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                    <?php esc_html_e('Check Now', 'autoparts-pro'); ?>
                </button>
            </div>
        </form>

        <!-- Stock status with real-time indicator -->
        <div class="stock-status-indicator">
            <?php if ($product->get_stock_quantity() > 10) : ?>
                <span class="stock-badge in-stock">
                    <i class="ap-icon-circle-filled"></i>
                    <?php echo esc_html__('In Stock', 'autoparts-pro'); ?>
                    <?php if (get_option('woocommerce_display_stock_amount')) : ?>
                        <span class="stock-amount">(<?php echo esc_html($product->get_stock_quantity()); ?> <?php esc_html_e('available', 'autoparts-pro'); ?>)</span>
                    <?php endif; ?>
                </span>
            <?php elseif ($product->get_stock_quantity() > 0) : ?>
                <span class="stock-badge low-stock">
                    <i class="ap-icon-alert-triangle"></i>
                    <?php echo esc_html__('Low Stock - Order Soon!', 'autoparts-pro'); ?>
                    <span class="stock-amount">(<?php echo esc_html($product->get_stock_quantity()); ?> <?php esc_html_e('left', 'autoparts-pro'); ?>)</span>
                </span>
            <?php else : ?>
                <span class="stock-badge out-of-stock">
                    <i class="ap-icon-x-circle"></i>
                    <?php echo esc_html__('Out of Stock', 'autoparts-pro'); ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Estimated delivery -->
        <div class="estimated-delivery">
            <i class="ap-icon-truck"></i>
            <span><?php echo esc_html__('Estimated delivery:', 'autoparts-pro'); ?> 
                <strong><?php echo esc_html(autoparts_pro_get_estimated_delivery_date()); ?></strong>
            </span>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Quantity controls
        $('.qty-minus').on('click', function() {
            var $input = $(this).siblings('input.qty');
            var currentVal = parseInt($input.val()) || 1;
            var min = parseInt($input.attr('min')) || 1;
            if (currentVal > min) {
                $input.val(currentVal - 1).trigger('change');
            }
        });
        
        $('.qty-plus').on('click', function() {
            var $input = $(this).siblings('input.qty');
            var currentVal = parseInt($input.val()) || 1;
            var max = parseInt($input.attr('max')) || 999;
            if (currentVal < max) {
                $input.val(currentVal + 1).trigger('change');
            }
        });
        
        // Update bulk pricing display on quantity change
        $('input.qty').on('change', function() {
            var qty = parseInt($(this).val()) || 1;
            $('.pricing-tier').each(function() {
                var $tier = $(this);
                var minQty = parseInt($tier.find('.quantity-range').text().match(/\d+/)[0]);
                var maxQty = parseInt($tier.find('.quantity-range').text().match(/\d+$/)[0]);
                
                if (qty >= minQty && qty <= maxQty) {
                    $tier.addClass('active');
                } else {
                    $tier.removeClass('active');
                }
            });
        });
    });
    </script>

<?php else : ?>
    <p class="stock out-of-stock">
        <?php echo esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock.', 'autoparts-pro'))); ?>
    </p>
    
    <!-- Notify when available -->
    <div class="notify-when-available">
        <h4><?php esc_html_e('Notify me when available', 'autoparts-pro'); ?></h4>
        <form class="notify-form" method="post">
            <input type="email" name="customer_email" placeholder="<?php esc_attr_e('Your email address', 'autoparts-pro'); ?>" required />
            <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>" />
            <?php wp_nonce_field('autoparts_pro_notify_when_available', 'notify_nonce'); ?>
            <button type="submit" class="button"><?php esc_html_e('Notify Me', 'autoparts-pro'); ?></button>
        </form>
    </div>
<?php endif; ?>
