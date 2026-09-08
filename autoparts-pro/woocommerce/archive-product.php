<?php
/**
 * Product Archive/Shop Page Template - AutoParts Pro
 * Premium shop layout with advanced filtering and vehicle compatibility
 */

defined('ABSPATH') || exit;

get_header('shop');

// Get current term for category pages
$current_term = is_product_category() ? get_queried_object() : null;
?>

<div class="autoparts-shop-page">
    
    <!-- Shop Header with Category Hero -->
    <?php if ($current_term) : ?>
        <div class="category-hero" style="<?php if (get_term_meta($current_term->term_id, '_category_banner', true)) { echo 'background-image: url(' . esc_url(get_term_meta($current_term->term_id, '_category_banner', true)) . ');'; } ?>">
            <div class="container">
                <div class="category-hero-content">
                    <h1 class="category-title"><?php echo esc_html($current_term->name); ?></h1>
                    <?php if (wc_get_format_subcategory_count()) : ?>
                        <p class="category-count"><?php echo sprintf(_n('%d product', '%d products', $current_term->count, 'autoparts-pro'), $current_term->count); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($current_term->description) : ?>
                        <div class="category-description">
                            <?php echo wp_kses_post($current_term->description); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="shop-header">
            <div class="container">
                <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
            </div>
        </div>
    <?php endif; ?>

    <!-- Vehicle Compatibility Bar -->
    <div class="compatibility-bar">
        <div class="container">
            <div class="compatibility-bar-content">
                <i class="fas fa-car"></i>
                <span><?php esc_html_e('Filtering parts for:', 'autoparts-pro'); ?></span>
                <span class="current-vehicle-display"><?php echo function_exists('autoparts_get_current_vehicle') ? esc_html(autoparts_get_current_vehicle()) : esc_html__('Select your vehicle', 'autoparts-pro'); ?></span>
                <button class="btn-change-vehicle"><?php esc_html_e('Change', 'autoparts-pro'); ?></button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="shop-layout">
            
            <!-- Sidebar Filters -->
            <aside class="shop-sidebar">
                <div class="filter-sections">
                    
                    <!-- Filter Toggle for Mobile -->
                    <button class="filter-toggle mobile-only">
                        <i class="fas fa-filter"></i> <?php esc_html_e('Filters', 'autoparts-pro'); ?>
                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <div class="filter-content">
                        
                        <!-- Search in Category -->
                        <div class="filter-widget">
                            <h4 class="filter-title"><?php esc_html_e('Search', 'autoparts-pro'); ?></h4>
                            <form class="shop-search" method="get" action="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
                                <input type="text" name="s" placeholder="<?php esc_attr_e('Search parts...', 'autoparts-pro'); ?>" value="<?php echo esc_attr(get_search_query()); ?>">
                                <input type="hidden" name="post_type" value="product">
                                <button type="submit"><i class="fas fa-search"></i></button>
                            </form>
                        </div>

                        <!-- Price Filter -->
                        <div class="filter-widget">
                            <h4 class="filter-title"><?php esc_html_e('Price Range', 'autoparts-pro'); ?></h4>
                            <div class="price-slider" data-min="<?php echo esc_attr(wc_get_price_min()); ?>" data-max="<?php echo esc_attr(wc_get_price_max()); ?>">
                                <div class="slider-range"></div>
                                <div class="slider-handle"></div>
                            </div>
                            <div class="price-inputs">
                                <input type="number" class="price-min" placeholder="<?php esc_attr_e('Min', 'autoparts-pro'); ?>">
                                <span>-</span>
                                <input type="number" class="price-max" placeholder="<?php esc_attr_e('Max', 'autoparts-pro'); ?>">
                            </div>
                            <button class="btn-apply-price"><?php esc_html_e('Apply', 'autoparts-pro'); ?></button>
                        </div>

                        <!-- Categories -->
                        <div class="filter-widget">
                            <h4 class="filter-title"><?php esc_html_e('Categories', 'autoparts-pro'); ?></h4>
                            <ul class="category-list">
                                <?php
                                $categories = get_terms(array(
                                    'taxonomy'   => 'product_cat',
                                    'hide_empty' => true,
                                    'parent'     => 0,
                                ));
                                
                                if (!empty($categories) && !is_wp_error($categories)) {
                                    foreach ($categories as $category) {
                                        echo '<li class="' . (is_product_category($category->slug) ? 'active' : '') . '">';
                                        echo '<a href="' . esc_url(get_term_link($category)) . '">';
                                        echo esc_html($category->name);
                                        echo ' <span class="count">(' . $category->count . ')</span>';
                                        echo '</a>';
                                        echo '</li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>

                        <!-- Brands -->
                        <div class="filter-widget">
                            <h4 class="filter-title"><?php esc_html_e('Brands', 'autoparts-pro'); ?></h4>
                            <div class="brand-list">
                                <?php
                                $brands = get_terms(array(
                                    'taxonomy'   => 'product_brand',
                                    'hide_empty' => true,
                                ));
                                
                                if (!empty($brands) && !is_wp_error($brands)) {
                                    foreach (array_slice($brands, 0, 8) as $brand) {
                                        echo '<label class="brand-checkbox">';
                                        echo '<input type="checkbox" name="brand[]" value="' . esc_attr($brand->slug) . '">';
                                        echo esc_html($brand->name);
                                        echo '</label>';
                                    }
                                    if (count($brands) > 8) {
                                        echo '<button class="show-more-brands">' . sprintf(__('Show %d more', 'autoparts-pro'), count($brands) - 8) . '</button>';
                                    }
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Rating Filter -->
                        <div class="filter-widget">
                            <h4 class="filter-title"><?php esc_html_e('Rating', 'autoparts-pro'); ?></h4>
                            <ul class="rating-list">
                                <?php for ($i = 5; $i >= 1; $i--) : ?>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="rating" value="<?php echo esc_attr($i); ?>">
                                            <div class="star-rating">
                                                <?php for ($j = 0; $j < $i; $j++) : ?>
                                                    <i class="fas fa-star"></i>
                                                <?php endfor; ?>
                                                <?php for ($j = $i; $j < 5; $j++) : ?>
                                                    <i class="far fa-star"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <span>& Up</span>
                                        </label>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </div>

                        <!-- In Stock Only -->
                        <div class="filter-widget">
                            <label class="checkbox-label">
                                <input type="checkbox" name="in_stock" value="1">
                                <span class="checkmark"></span>
                                <?php esc_html_e('In Stock Only', 'autoparts-pro'); ?>
                            </label>
                        </div>

                        <!-- OEM vs Aftermarket -->
                        <div class="filter-widget">
                            <h4 class="filter-title"><?php esc_html_e('Part Type', 'autoparts-pro'); ?></h4>
                            <label class="radio-label">
                                <input type="radio" name="part_type" value="oem">
                                <?php esc_html_e('OEM Parts', 'autoparts-pro'); ?>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="part_type" value="aftermarket">
                                <?php esc_html_e('Aftermarket', 'autoparts-pro'); ?>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="part_type" value="both" checked>
                                <?php esc_html_e('Both', 'autoparts-pro'); ?>
                            </label>
                        </div>

                        <!-- Clear Filters -->
                        <button class="btn-clear-filters">
                            <i class="fas fa-times"></i> <?php esc_html_e('Clear All Filters', 'autoparts-pro'); ?>
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Products Grid -->
            <main class="shop-main">
                
                <!-- Shop Controls -->
                <div class="shop-controls">
                    <div class="results-count">
                        <?php woocommerce_result_count(); ?>
                    </div>
                    
                    <div class="shop-view-options">
                        <button class="view-btn grid-view active" data-view="grid">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button class="view-btn list-view" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                    
                    <div class="shop-orderby">
                        <?php woocommerce_catalog_ordering(); ?>
                    </div>
                </div>

                <!-- Products Loop -->
                <?php if (woocommerce_product_loop()) : ?>
                    
                    <?php do_action('woocommerce_before_shop_loop'); ?>

                    <div class="products-wrapper">
                        <ul class="products columns-<?php echo esc_attr(woocommerce_get_columns_per_row()); ?>">
                            <?php
                            while (have_posts()) :
                                the_post();
                                do_action('woocommerce_shop_loop');
                                wc_get_template_part('content', 'product');
                            endwhile;
                            ?>
                        </ul>
                    </div>

                    <?php do_action('woocommerce_after_shop_loop'); ?>

                    <!-- Pagination -->
                    <?php woocommerce_pagination(); ?>

                <?php else : ?>
                    
                    <!-- No Products Found -->
                    <div class="no-products-found">
                        <i class="fas fa-search"></i>
                        <h2><?php esc_html_e('No products found', 'autoparts-pro'); ?></h2>
                        <p><?php esc_html_e('Try adjusting your filters or search terms.', 'autoparts-pro'); ?></p>
                        <button class="btn-clear-filters-large">
                            <?php esc_html_e('Clear All Filters', 'autoparts-pro'); ?>
                        </button>
                    </div>

                <?php endif; ?>
            </main>
        </div>
    </div>

    <!-- SEO Content Bottom -->
    <?php if (is_product_category() && $current_term->description) : ?>
        <div class="category-seo-content">
            <div class="container">
                <?php echo wp_kses_post($current_term->description); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php get_footer('shop'); ?>
