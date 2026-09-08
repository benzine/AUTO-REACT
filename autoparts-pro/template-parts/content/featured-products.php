<?php
/**
 * Template part for displaying featured products section
 * 
 * @package AutoParts_Pro
 */

// Get section settings
$section_title = get_theme_mod('featured_products_title', __('Featured Products', 'autoparts-pro'));
$section_subtitle = get_theme_mod('featured_products_subtitle', __('Hand-picked premium parts for your vehicle', 'autoparts-pro'));
$products_count = get_theme_mod('featured_products_count', 8);
$columns = get_theme_mod('featured_products_columns', 4);

// Get featured products
$args = array(
    'post_type'      => 'product',
    'posts_per_page' => intval($products_count),
    'meta_query'     => array(
        array(
            'key'     => '_featured',
            'value'   => 'yes',
            'compare' => '=',
        ),
    ),
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$featured_products = new WP_Query($args);
?>

<?php if ($featured_products->have_posts()) : ?>
<section id="featured-products" class="autoparts-featured-section">
    <div class="container">
        
        <!-- Section Header -->
        <div class="section-header">
            <div class="header-content">
                <h2 class="section-title"><?php echo esc_html($section_title); ?></h2>
                <?php if ($section_subtitle) : ?>
                    <p class="section-subtitle"><?php echo esc_html($section_subtitle); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="header-actions">
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>?featured=true" class="btn btn-secondary">
                    <?php esc_html_e('View All Featured', 'autoparts-pro'); ?> <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-grid columns-<?php echo esc_attr($columns); ?>">
            <?php while ($featured_products->have_posts()) : $featured_products->the_post(); ?>
                <?php wc_get_template_part('content', 'product'); ?>
            <?php endwhile; ?>
        </div>

        <!-- Section CTA -->
        <div class="section-cta">
            <div class="cta-box">
                <h3><?php esc_html_e("Can't Find What You're Looking For?", 'autoparts-pro'); ?></h3>
                <p><?php esc_html_e('Our automotive experts can help you find the exact parts you need.', 'autoparts-pro'); ?></p>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-primary">
                    <i class="fas fa-headset"></i> <?php esc_html_e('Contact Our Team', 'autoparts-pro'); ?>
                </a>
            </div>
        </div>

    </div>
</section>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
