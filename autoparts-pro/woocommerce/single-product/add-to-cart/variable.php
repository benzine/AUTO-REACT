<?php
/**
 * Add to Cart - Variable Product with Compatibility Check
 * 
 * @package AutoParts_Pro
 */

defined('ABSPATH') || exit;

global $product;

if (!$product->is_purchasable()) {
    return;
}

echo wc_get_stock_html($product);

// Get available variations
$available_variations = array();
foreach ($product->get_available_variations() as $variation) {
    $available_variations[] = $variation;
}

$attributes = $product->get_variation_attributes();
?>

<?php if ($product->is_in_stock()) : ?>

    <div class="autoparts-variable-add-to-cart-wrapper">
        <!-- Vehicle Compatibility Warning -->
        <div class="vehicle-compatibility-notice" style="display: none;" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
            <i class="ap-icon-alert-circle"></i>
            <span class="compatibility-message"></span>
        </div>

        <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
            
            <!-- Variation selection -->
            <div class="variations-table" role="table">
                <?php foreach ($product->get_attributes() as $attribute_name => $attribute) : ?>
                    <?php if ($attribute['is_variation']) : ?>
                        <div class="variation-row" role="row">
                            <div class="variation-label" role="cell">
                                <label for="<?php echo esc_attr(sanitize_title($attribute_name)); ?>">
                                    <?php echo wc_attribute_label($attribute_name); ?>
                                </label>
                            </div>
                            <div class="variation-value" role="cell">
                                <?php
                                $options = $attribute['is_taxonomy'] 
                                    ? wc_get_product_terms($product->get_id(), $attribute_name, array('fields' => 'all'))
                                    : $attribute['value'];
                                
                                woocommerce_dropdown_variation_attribute_options(array(
                                    'options' => $options,
                                    'attribute' => $attribute_name,
                                    'product' => $product,
                                    'name' => 'attribute_' . sanitize_title($attribute_name),
                                    'id' => sanitize_title($attribute_name),
                                    'class' => 'variation-select',
                                    'show_option_none' => __('Choose an option', 'autoparts-pro'),
                                ));
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Reset variations button -->
            <div class="reset-variations-wrap">
                <a class="reset_variations" href="#reset"><?php esc_html_e('Clear', 'autoparts-pro'); ?></a>
            </div>

            <!-- Quantity control -->
            <div class="quantity-control-wrapper">
                <?php do_action('woocommerce_before_add_to_cart_quantity'); ?>

                <div class="quantity-control">
                    <label for="quantity-<?php echo esc_attr($product->get_id()); ?>" class="screen-reader-text">
                        <?php esc_html_e('Quantity', 'autoparts-pro'); ?>
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
            </div>

            <!-- Add to cart button (initially disabled until variation selected) -->
            <div class="add-to-cart-action">
                <button type="submit" 
                        name="add-to-cart" 
                        value="<?php echo esc_attr($product->get_id()); ?>" 
                        class="single_add_to_cart_button button alt disabled"
                        disabled
                        data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                    <i class="ap-icon-cart"></i>
                    <?php echo esc_html($product->single_add_to_cart_text()); ?>
                </button>
            </div>

            <?php do_action('woocommerce_after_add_to_cart_button'); ?>

            <!-- Variation price display -->
            <div class="variation-price-display">
                <p class="price"></p>
            </div>

            <!-- Variation availability -->
            <div class="variation-availability">
                <p class="stock"></p>
            </div>

            <!-- Variation description -->
            <div class="variation-description">
                <p></p>
            </div>
        </form>

        <!-- Bulk pricing info for variations -->
        <div class="variation-bulk-pricing" style="display: none;">
            <h4><?php esc_html_e('Bulk Discounts Available', 'autoparts-pro'); ?></h4>
            <ul class="bulk-pricing-list"></ul>
        </div>
    </div>

    <?php wp_enqueue_script('wc-single-product'); ?>

    <script>
    jQuery(document).ready(function($) {
        // Quantity controls for variable products
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

        // Handle variation selection
        $('form.cart').on('found_variation', function(event, variation) {
            var $addToCartBtn = $(this).find('button[type="submit"]');
            
            // Enable add to cart button
            $addToCartBtn.prop('disabled', false).removeClass('disabled');
            
            // Check compatibility if vehicle is saved
            if (window.autopartsVehicleStore && window.autopartsVehicleStore.selectedVehicle) {
                checkVariationCompatibility(variation.variation_id);
            }
            
            // Show bulk pricing if available
            if (variation.bulk_pricing) {
                displayBulkPricing(variation.bulk_pricing);
            }
        });
        
        $('form.cart').on('reset_data', function() {
            var $addToCartBtn = $(this).find('button[type="submit"]');
            $addToCartBtn.prop('disabled', true).addClass('disabled');
            $('.vehicle-compatibility-notice').hide();
            $('.variation-bulk-pricing').hide();
        });
        
        function checkVariationCompatibility(variationId) {
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'autoparts_pro_check_variation_compatibility',
                    variation_id: variationId,
                    vehicle: window.autopartsVehicleStore.selectedVehicle,
                    nonce: '<?php echo wp_create_nonce('autoparts_pro_compatibility_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        var $notice = $('.vehicle-compatibility-notice');
                        $notice.removeClass('error').addClass('success');
                        $notice.find('.compatibility-message').text(response.data.message);
                        $notice.show();
                    }
                }
            });
        }
        
        function displayBulkPricing(bulkPricing) {
            var $bulkPricingContainer = $('.variation-bulk-pricing');
            var $list = $bulkPricingContainer.find('.bulk-pricing-list');
            $list.empty();
            
            bulkPricing.forEach(function(tier) {
                $list.append(
                    '<li class="pricing-tier">' +
                    '<span class="quantity-range">Buy ' + tier.min_qty + '-' + tier.max_qty + '</span>' +
                    '<span class="discount-badge">' + tier.discount_percent + '% OFF</span>' +
                    '</li>'
                );
            });
            
            $bulkPricingContainer.show();
        }
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
