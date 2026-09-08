<?php
/**
 * Single Product Template
 * Overrides WooCommerce single product template
 *
 * @package AutoParts_Pro
 */

get_header();

while (have_posts()) :
    the_post();
    ?>
    
    <main id="primary" class="site-main autoparts-single-product">
        <div class="autoparts-container">
            
            <!-- Breadcrumb -->
            <?php woocommerce_breadcrumb(); ?>
            
            <div class="autoparts-product-wrapper">
                
                <!-- Product Gallery -->
                <div class="autoparts-product-gallery">
                    <?php woocommerce_show_product_images(); ?>
                </div>
                
                <!-- Product Summary -->
                <div class="autoparts-product-summary">
                    
                    <!-- Part Number & SKU -->
                    <?php
                    $part_number = get_post_meta(get_the_ID(), '_part_number', true);
                    $oem_number = get_post_meta(get_the_ID(), '_oem_number', true);
                    if ($part_number || $oem_number):
                    ?>
                    <div class="autoparts-part-numbers">
                        <?php if ($part_number): ?>
                            <span class="part-number"><?php _e('Part #:', 'autoparts-pro'); ?> <strong><?php echo esc_html($part_number); ?></strong></span>
                        <?php endif; ?>
                        <?php if ($oem_number): ?>
                            <span class="oem-number"><?php _e('OEM #:', 'autoparts-pro'); ?> <strong><?php echo esc_html($oem_number); ?></strong></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <h1 class="product-title"><?php the_title(); ?></h1>
                    
                    <!-- Price -->
                    <div class="autoparts-product-price">
                        <?php woocommerce_template_single_price(); ?>
                    </div>
                    
                    <!-- Stock Status -->
                    <div class="autoparts-stock-status">
                        <?php woocommerce_template_single_availability(); ?>
                    </div>
                    
                    <!-- Bulk Pricing -->
                    <?php
                    $bulk_pricing = get_post_meta(get_the_ID(), '_bulk_pricing', true);
                    if ($bulk_pricing && is_array($bulk_pricing)):
                    ?>
                    <div class="autoparts-bulk-pricing-display">
                        <h4><?php _e('Bulk Discounts:', 'autoparts-pro'); ?></h4>
                        <ul>
                            <?php foreach ($bulk_pricing as $tier): ?>
                                <li>
                                    <?php printf(__('Buy %d+ save %d%%', 'autoparts-pro'), $tier['min_qty'], $tier['discount']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Short Description -->
                    <div class="autoparts-short-description">
                        <?php woocommerce_template_single_excerpt(); ?>
                    </div>
                    
                    <!-- Add to Cart Form -->
                    <div class="autoparts-add-to-cart-form">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                    </div>
                    
                    <!-- Wishlist & Share -->
                    <div class="autoparts-product-actions">
                        <?php do_action('autoparts_wishlist_button'); ?>
                        <?php do_action('autoparts_share_buttons'); ?>
                    </div>
                    
                    <!-- Meta -->
                    <div class="product_meta">
                        <?php do_action('woocommerce_product_meta_start'); ?>
                        <?php wc_get_product_category_list(', ', '<span class="posted_in">' . __('Categories:', 'autoparts-pro') . ' ', '</span>'); ?>
                        <?php wc_get_product_tag_list(', ', '<span class="tagged_as">' . __('Tags:', 'autoparts-pro') . ' ', '</span>'); ?>
                        <?php do_action('woocommerce_product_meta_end'); ?>
                    </div>
                    
                </div>
                
            </div>
            
            <!-- Product Tabs -->
            <div class="autoparts-product-tabs">
                <?php woocommerce_output_product_data_tabs(); ?>
            </div>
            
            <!-- Related Products -->
            <div class="autoparts-related-products">
                <?php woocommerce_output_related_products(); ?>
            </div>
            
            <!-- Upsells -->
            <div class="autoparts-upsell-products">
                <?php woocommerce_output_upsells(); ?>
            </div>
            
        </div>
    </main>
    
    <?php
endwhile;

get_footer();
