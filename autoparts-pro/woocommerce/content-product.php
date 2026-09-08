<?php
/**
 * Product Content Template - AutoParts Pro
 * Used in loops for product cards with premium styling
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility
if (empty($product) || !$product->is_visible()) {
    return;
}

// Get compatibility status
$is_compatible = false;
if (function_exists('autoparts_check_compatibility')) {
    $is_compatible = autoparts_check_compatibility($product->get_id());
}

// Get bulk pricing
$bulk_pricing = $product->get_meta('_bulk_pricing');
$has_bulk_discount = !empty($bulk_pricing) && is_array($bulk_pricing);

// Stock status
$stock_status = $product->get_stock_status();
$stock_quantity = $product->get_stock_quantity();
$is_low_stock = $stock_quantity && $stock_quantity <= 5;
?>

<li <?php wc_product_class('autoparts-product-card', $product); ?>>
    
    <div class="product-card-inner">
        
        <!-- Badges -->
        <div class="product-badges">
            <?php if ($product->is_on_sale()) : ?>
                <span class="badge sale-badge">
                    <?php
                    $regular_price = (float) $product->get_regular_price();
                    $sale_price = (float) $product->get_sale_price();
                    if ($regular_price > 0) {
                        $discount_percent = round((($regular_price - $sale_price) / $regular_price) * 100);
                        echo sprintf(__('-%d%%', 'autoparts-pro'), $discount_percent);
                    } else {
                        esc_html_e('Sale!', 'autoparts-pro');
                    }
                    ?>
                </span>
            <?php endif; ?>
            
            <?php if ($product->is_featured()) : ?>
                <span class="badge featured-badge"><?php esc_html_e('Featured', 'autoparts-pro'); ?></span>
            <?php endif; ?>
            
            <?php if ($is_low_stock && $product->is_in_stock()) : ?>
                <span class="badge low-stock-badge"><?php esc_html_e('Low Stock', 'autoparts-pro'); ?></span>
            <?php endif; ?>
            
            <?php if ($is_compatible) : ?>
                <span class="badge compatible-badge">
                    <i class="fas fa-check"></i> <?php esc_html_e('Fits Your Vehicle', 'autoparts-pro'); ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Wishlist Button -->
        <button class="btn-quick-wishlist" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php esc_attr_e('Add to wishlist', 'autoparts-pro'); ?>">
            <i class="far fa-heart"></i>
        </button>

        <!-- Product Image -->
        <div class="product-card-image">
            <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                <?php echo $product->get_image('woocommerce_thumbnail', array('class' => 'attachment-woocommerce_thumbnail size-woocommerce_thumbnail')); ?>
            </a>
            
            <!-- Quick View Button -->
            <button class="btn-quick-view" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                <i class="fas fa-eye"></i>
                <span><?php esc_html_e('Quick View', 'autoparts-pro'); ?></span>
            </button>
            
            <!-- 360° View Indicator -->
            <?php if ($product->get_meta('_has_360_view')) : ?>
                <span class="view-360-indicator">
                    <i class="fas fa-sync"></i> <?php esc_html_e('360°', 'autoparts-pro'); ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="product-card-info">
            
            <!-- Category -->
            <?php
            $terms = get_the_terms($product->get_id(), 'product_cat');
            if ($terms && !is_wp_error($terms)) {
                $term = array_shift($terms);
                echo '<span class="product-category">' . esc_html($term->name) . '</span>';
            }
            ?>

            <!-- Product Title -->
            <h3 class="product-card-title">
                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                    <?php echo wp_kses_post($product->get_name()); ?>
                </a>
            </h3>

            <!-- Part Number -->
            <?php if ($part_number = $product->get_meta('_part_number')) : ?>
                <span class="product-part-number"><?php echo esc_html__('Part #:', 'autoparts-pro') . ' ' . esc_html($part_number); ?></span>
            <?php endif; ?>

            <!-- Rating -->
            <?php if (wc_review_ratings_enabled()) : ?>
                <div class="product-card-rating">
                    <?php echo wc_get_rating_html($product->get_average_rating(), $product->get_review_count()); ?>
                    <span class="rating-count">(<?php echo esc_html($product->get_review_count()); ?>)</span>
                </div>
            <?php endif; ?>

            <!-- Price -->
            <div class="product-card-price">
                <?php echo $product->get_price_html(); ?>
                
                <?php if ($has_bulk_discount) : ?>
                    <span class="bulk-discount-indicator" title="<?php esc_attr_e('Bulk discounts available', 'autoparts-pro'); ?>">
                        <i class="fas fa-tags"></i> <?php esc_html_e('Bulk Pricing', 'autoparts-pro'); ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Stock Status -->
            <div class="product-card-stock">
                <?php if ($product->is_in_stock()) : ?>
                    <?php if ($is_low_stock) : ?>
                        <span class="stock-indicator low">
                            <i class="fas fa-triangle-exclamation"></i>
                            <?php echo sprintf(__('Only %d left!', 'autoparts-pro'), $stock_quantity); ?>
                        </span>
                    <?php else : ?>
                        <span class="stock-indicator in-stock">
                            <i class="fas fa-check-circle"></i> <?php esc_html_e('In Stock', 'autoparts-pro'); ?>
                        </span>
                    <?php endif; ?>
                <?php else : ?>
                    <span class="stock-indicator out-of-stock">
                        <i class="fas fa-times-circle"></i> <?php esc_html_e('Out of Stock', 'autoparts-pro'); ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Add to Cart Button -->
            <div class="product-card-actions">
                <?php if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) : ?>
                    <button class="btn-add-to-cart-ajax" data-product-id="<?php echo esc_attr($product->get_id()); ?>" data-quantity="1">
                        <i class="fas fa-shopping-cart"></i>
                        <span><?php esc_html_e('Add to Cart', 'autoparts-pro'); ?></span>
                    </button>
                <?php elseif (!$product->is_in_stock()) : ?>
                    <button class="btn-notify-me" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <i class="fas fa-bell"></i>
                        <span><?php esc_html_e('Notify Me', 'autoparts-pro'); ?></span>
                    </button>
                <?php else : ?>
                    <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="btn-view-product">
                        <i class="fas fa-arrow-right"></i>
                        <span><?php esc_html_e('View Details', 'autoparts-pro'); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</li>
