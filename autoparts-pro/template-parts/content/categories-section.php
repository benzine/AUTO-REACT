<?php
/**
 * Template part for displaying product categories section
 * 
 * @package AutoParts_Pro
 */

// Get section settings
$section_title = get_theme_mod('categories_section_title', __('Shop by Category', 'autoparts-pro'));
$section_subtitle = get_theme_mod('categories_section_subtitle', __('Find the perfect parts for your vehicle', 'autoparts-pro'));
$categories_count = get_theme_mod('categories_display_count', 8);
$show_counts = get_theme_mod('categories_show_counts', true);

// Get product categories
$categories = get_terms(array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'number'     => intval($categories_count),
    'orderby'    => 'count',
    'order'      => 'DESC',
));
?>

<?php if (!empty($categories) && !is_wp_error($categories)) : ?>
<section id="categories-section" class="autoparts-categories-section">
    <div class="container">
        
        <!-- Section Header -->
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html($section_title); ?></h2>
            <?php if ($section_subtitle) : ?>
                <p class="section-subtitle"><?php echo esc_html($section_subtitle); ?></p>
            <?php endif; ?>
            
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-secondary">
                <?php esc_html_e('View All Categories', 'autoparts-pro'); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <!-- Categories Grid -->
        <div class="categories-grid">
            <?php foreach ($categories as $category) : ?>
                <?php
                // Get category thumbnail
                $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                $category_image = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
                
                // Get icon based on category slug
                $category_icon = autoparts_get_category_icon($category->slug);
                ?>
                
                <a href="<?php echo esc_url(get_term_link($category)); ?>" class="category-card" data-category="<?php echo esc_attr($category->slug); ?>">
                    <div class="category-card-image">
                        <?php if ($category_image) : ?>
                            <img src="<?php echo esc_url($category_image); ?>" alt="<?php echo esc_attr($category->name); ?>">
                        <?php else : ?>
                            <div class="category-icon-placeholder">
                                <i class="fas <?php echo esc_attr($category_icon); ?>"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Hover Overlay -->
                        <div class="category-overlay">
                            <span class="view-products-btn">
                                <i class="fas fa-shopping-cart"></i>
                                <?php esc_html_e('Shop Now', 'autoparts-pro'); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="category-card-content">
                        <h3 class="category-name"><?php echo esc_html($category->name); ?></h3>
                        
                        <?php if ($category->description) : ?>
                            <p class="category-description">
                                <?php echo wp_trim_words($category->description, 12); ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($show_counts) : ?>
                            <span class="category-count">
                                <?php echo sprintf(_n('%d Product', '%d Products', $category->count, 'autoparts-pro'), $category->count); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Category Features -->
        <div class="category-features">
            <div class="feature-item">
                <i class="fas fa-check-circle"></i>
                <span><?php esc_html_e('Guaranteed Fitment', 'autoparts-pro'); ?></span>
            </div>
            <div class="feature-item">
                <i class="fas fa-medal"></i>
                <span><?php esc_html_e('Premium Quality', 'autoparts-pro'); ?></span>
            </div>
            <div class="feature-item">
                <i class="fas fa-tags"></i>
                <span><?php esc_html_e('Competitive Pricing', 'autoparts-pro'); ?></span>
            </div>
            <div class="feature-item">
                <i class="fas fa-headset"></i>
                <span><?php esc_html_e('Expert Support', 'autoparts-pro'); ?></span>
            </div>
        </div>
        
    </div>
</section>
<?php endif; ?>

<?php
/**
 * Helper function to get category icon based on slug
 */
if (!function_exists('autoparts_get_category_icon')) {
    function autoparts_get_category_icon($slug) {
        $icons = array(
            'engine-parts'      => 'fa-cogs',
            'engine'            => 'fa-cogs',
            'brakes'            => 'fa-compact-disc',
            'suspension'        => 'fa-compress-arrows-alt',
            'electrical'        => 'fa-car-battery',
            'filters'           => 'fa-filter',
            'exhaust'           => 'fa-wind',
            'cooling'           => 'fa-snowflake',
            'transmission'      => 'fa-random',
            'body-parts'        => 'fa-car',
            'interior'          => 'fa-chair',
            'lights'            => 'fa-lightbulb',
            'wheels-tires'      => 'fa-ring',
            'oils-fluids'       => 'fa-oil-can',
            'tools-equipment'   => 'fa-tools',
        );
        
        foreach ($icons as $key => $icon) {
            if (strpos($slug, $key) !== false) {
                return $icon;
            }
        }
        
        return 'fa-auto';
    }
}
?>
