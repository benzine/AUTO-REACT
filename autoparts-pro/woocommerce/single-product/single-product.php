<?php
/**
 * Single Product Template - AutoParts Pro
 * Premium product page with exploded view, compatibility checker, and bulk pricing
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility
if (empty($product) || !$product->is_visible()) {
    return;
}

do_action('autoparts_single_product_before', $product);
?>

<article id="product-<?php echo esc_attr($product->get_id()); ?>" <?php wc_product_class('autoparts-single-product', $product); ?>>
    
    <div class="container">
        
        <!-- Breadcrumb -->
        <?php woocommerce_breadcrumb(); ?>

        <!-- Product Header with Part Number -->
        <div class="product-header">
            <div class="product-meta-top">
                <?php if ($sku = $product->get_sku()) : ?>
                    <span class="sku-label"><?php esc_html_e('SKU:', 'autoparts-pro'); ?> <strong><?php echo esc_html($sku); ?></strong></span>
                <?php endif; ?>
                
                <?php if ($part_number = $product->get_meta('_part_number')) : ?>
                    <span class="part-number-label"><?php esc_html_e('Part #:', 'autoparts-pro'); ?> <strong><?php echo esc_html($part_number); ?></strong></span>
                <?php endif; ?>
                
                <?php if ($oem_number = $product->get_meta('_oem_number')) : ?>
                    <span class="oem-number-label"><?php esc_html_e('OEM #:', 'autoparts-pro'); ?> <strong><?php echo esc_html($oem_number); ?></strong></span>
                <?php endif; ?>
            </div>
            
            <h1 class="product-title"><?php the_title(); ?></h1>
            
            <!-- Compatibility Badge -->
            <?php if (function_exists('autoparts_check_compatibility')) : ?>
                <div class="compatibility-check-result">
                    <?php 
                    $is_compatible = autoparts_check_compatibility($product->get_id());
                    if ($is_compatible) :
                    ?>
                        <span class="compatibility-badge compatible">
                            <i class="fas fa-check-circle"></i> 
                            <?php esc_html_e('Fits Your Vehicle', 'autoparts-pro'); ?>
                        </span>
                    <?php else : ?>
                        <span class="compatibility-badge check-fit">
                            <i class="fas fa-exclamation-circle"></i> 
                            <?php esc_html_e('Check Compatibility', 'autoparts-pro'); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-main-grid">
            
            <!-- Left: Product Gallery -->
            <div class="product-gallery-section">
                <?php do_action('woocommerce_before_single_product_summary'); ?>
                
                <!-- 360° View Button (if available) -->
                <?php if ($product->get_meta('_has_360_view')) : ?>
                    <button class="btn-360-view" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <i class="fas fa-sync"></i> <?php esc_html_e('360° View', 'autoparts-pro'); ?>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Right: Product Info & Purchase -->
            <div class="product-info-section">
                
                <!-- Price with Bulk Pricing -->
                <div class="product-price-wrapper">
                    <?php woocommerce_template_single_price(); ?>
                    
                    <?php
                    $bulk_pricing = $product->get_meta('_bulk_pricing');
                    if ($bulk_pricing && is_array($bulk_pricing)) :
                    ?>
                        <div class="bulk-pricing-tiers">
                            <h4><?php esc_html_e('Bulk Discounts:', 'autoparts-pro'); ?></h4>
                            <ul>
                                <?php foreach ($bulk_pricing as $tier) : ?>
                                    <li>
                                        <span class="qty-range"><?php echo sprintf('%d-%d', $tier['min_qty'], $tier['max_qty'] ?: '∞'); ?> <?php esc_html_e('units', 'autoparts-pro'); ?></span>
                                        <span class="discount-percent"><?php echo sprintf('Save %s%%', $tier['discount']); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Stock Status -->
                <div class="product-stock-status">
                    <?php
                    $stock_status = $product->get_stock_status();
                    $stock_quantity = $product->get_stock_quantity();
                    
                    if ($product->is_in_stock()) :
                        if ($stock_quantity && $stock_quantity <= 5) :
                    ?>
                        <span class="stock-status low-stock">
                            <i class="fas fa-triangle-exclamation"></i>
                            <?php echo sprintf(__('Only %d left in stock!', 'autoparts-pro'), $stock_quantity); ?>
                        </span>
                    <?php else : ?>
                        <span class="stock-status in-stock">
                            <i class="fas fa-check-circle"></i>
                            <?php esc_html_e('In Stock', 'autoparts-pro'); ?>
                        </span>
                    <?php 
                        endif;
                    else :
                    ?>
                        <span class="stock-status out-of-stock">
                            <i class="fas fa-times-circle"></i>
                            <?php esc_html_e('Out of Stock', 'autoparts-pro'); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Short Description -->
                <div class="product-short-description">
                    <?php woocommerce_template_single_excerpt(); ?>
                </div>

                <!-- Vehicle Compatibility Selector -->
                <div class="vehicle-compatibility-selector">
                    <h4><?php esc_html_e('Verify Fitment:', 'autoparts-pro'); ?></h4>
                    <form class="compatibility-form" method="post">
                        <div class="compatibility-fields">
                            <select name="year" class="compat-select" required>
                                <option value=""><?php esc_html_e('Select Year', 'autoparts-pro'); ?></option>
                                <?php
                                // Populate years dynamically
                                for ($year = date('Y'); $year >= 1980; $year--) {
                                    echo '<option value="' . esc_attr($year) . '">' . esc_html($year) . '</option>';
                                }
                                ?>
                            </select>
                            
                            <select name="make" class="compat-select" required disabled>
                                <option value=""><?php esc_html_e('Select Make', 'autoparts-pro'); ?></option>
                            </select>
                            
                            <select name="model" class="compat-select" required disabled>
                                <option value=""><?php esc_html_e('Select Model', 'autoparts-pro'); ?></option>
                            </select>
                            
                            <select name="engine" class="compat-select" required disabled>
                                <option value=""><?php esc_html_e('Select Engine', 'autoparts-pro'); ?></option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-check-fit">
                            <i class="fas fa-search"></i> <?php esc_html_e('Check Fit', 'autoparts-pro'); ?>
                        </button>
                    </form>
                    
                    <div class="compatibility-result" style="display:none;">
                        <div class="result-message"></div>
                    </div>
                </div>

                <!-- Add to Cart Form -->
                <div class="product-cart-form">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>

                <!-- Wishlist & Share Buttons -->
                <div class="product-actions">
                    <button class="btn-wishlist" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <i class="far fa-heart"></i>
                        <span><?php esc_html_e('Add to Wishlist', 'autoparts-pro'); ?></span>
                    </button>
                    
                    <div class="share-buttons">
                        <span class="share-label"><?php esc_html_e('Share:', 'autoparts-pro'); ?></span>
                        <a href="#" class="share-btn facebook" data-share="facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="share-btn twitter" data-share="twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="share-btn whatsapp" data-share="whatsapp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <button class="share-btn copy-link" data-share="copy">
                            <i class="fas fa-link"></i>
                        </button>
                        <button class="share-btn qr-code" data-share="qr">
                            <i class="fas fa-qrcode"></i>
                        </button>
                    </div>
                </div>

                <!-- Additional Info Tabs Preview -->
                <div class="product-tabs-preview">
                    <ul class="tab-links">
                        <li><a href="#description"><?php esc_html_e('Description', 'autoparts-pro'); ?></a></li>
                        <li><a href="#specifications"><?php esc_html_e('Specifications', 'autoparts-pro'); ?></a></li>
                        <li><a href="#compatibility"><?php esc_html_e('Compatibility', 'autoparts-pro'); ?></a></li>
                        <li><a href="#installation"><?php esc_html_e('Installation', 'autoparts-pro'); ?></a></li>
                        <li><a href="#reviews"><?php esc_html_e('Reviews', 'autoparts-pro'); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Product Tabs Full Content -->
        <div class="product-tabs-full">
            <?php woocommerce_output_product_data_tabs(); ?>
        </div>

        <!-- Related Products -->
        <div class="related-products-section">
            <?php woocommerce_output_related_products(array(
                'posts_per_page' => 4,
                'columns' => 4,
            )); ?>
        </div>

        <!-- Recently Viewed -->
        <?php if (function_exists('autoparts_recently_viewed_products')) : ?>
            <div class="recently-viewed-section">
                <?php autoparts_recently_viewed_products(); ?>
            </div>
        <?php endif; ?>

    </div>
</article>

<?php do_action('autoparts_single_product_after', $product); ?>
