<?php
/**
 * Brands Page Template - AutoParts Pro
 * Showcase all automotive brands we carry
 */

get_header();
?>

<div class="autoparts-brands-page">
    <div class="container">
        
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title"><?php esc_html_e('Our Premium Brands', 'autoparts-pro'); ?></h1>
            <p class="page-subtitle"><?php esc_html_e('Trusted by mechanics and car enthusiasts worldwide', 'autoparts-pro'); ?></p>
        </div>

        <!-- Brand Filter/Search -->
        <div class="brands-search-section">
            <form class="brands-search-form" method="get">
                <input type="text" name="brand_search" placeholder="<?php esc_attr_e('Search brands...', 'autoparts-pro'); ?>" value="<?php echo esc_attr(get_query_var('brand_search')); ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            
            <div class="brand-filter-alphabet">
                <a href="#" data-letter="all" class="active"><?php esc_html_e('All', 'autoparts-pro'); ?></a>
                <?php foreach (range('A', 'Z') as $letter) : ?>
                    <a href="#" data-letter="<?php echo esc_attr($letter); ?>"><?php echo esc_html($letter); ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Brands Grid -->
        <div class="brands-grid">
            <?php
            $brands = get_terms(array(
                'taxonomy'   => 'product_brand',
                'hide_empty' => true,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ));
            
            if (!empty($brands) && !is_wp_error($brands)) {
                foreach ($brands as $brand) {
                    $brand_logo = get_term_meta($brand->term_id, '_brand_logo', true);
                    $brand_description = term_description($brand->term_id);
                    $product_count = $brand->count;
                    
                    ?>
                    <div class="brand-card" data-brand-name="<?php echo esc_attr($brand->name); ?>" data-letter="<?php echo esc_attr(strtoupper(substr($brand->name, 0, 1))); ?>">
                        <a href="<?php echo esc_url(get_term_link($brand)); ?>" class="brand-link">
                            <div class="brand-logo-wrapper">
                                <?php if ($brand_logo) : ?>
                                    <img src="<?php echo esc_url($brand_logo); ?>" alt="<?php echo esc_attr($brand->name); ?>" class="brand-logo">
                                <?php else : ?>
                                    <div class="brand-logo-placeholder">
                                        <span><?php echo esc_html(strtoupper(substr($brand->name, 0, 2))); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <h3 class="brand-name"><?php echo esc_html($brand->name); ?></h3>
                            
                            <?php if ($brand_description) : ?>
                                <p class="brand-description">
                                    <?php echo wp_trim_words($brand_description, 15); ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="brand-product-count">
                                <?php echo sprintf(_n('%d Product', '%d Products', $product_count, 'autoparts-pro'), $product_count); ?>
                            </div>
                            
                            <div class="brand-view-products">
                                <i class="fas fa-arrow-right"></i> <?php esc_html_e('View Products', 'autoparts-pro'); ?>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="no-brands-found">
                    <p><?php esc_html_e('No brands found.', 'autoparts-pro'); ?></p>
                </div>
                <?php
            }
            ?>
        </div>

        <!-- Featured Brands Section -->
        <?php
        $featured_brands = get_terms(array(
            'taxonomy'   => 'product_brand',
            'hide_empty' => true,
            'meta_query' => array(
                array(
                    'key'     => '_is_featured_brand',
                    'value'   => '1',
                    'compare' => '=',
                ),
            ),
        ));
        
        if (!empty($featured_brands) && !is_wp_error($featured_brands)) :
        ?>
            <div class="featured-brands-section">
                <h2><?php esc_html_e('Featured Partners', 'autoparts-pro'); ?></h2>
                <div class="featured-brands-logos">
                    <?php foreach ($featured_brands as $brand) : ?>
                        <?php $brand_logo = get_term_meta($brand->term_id, '_brand_logo', true); ?>
                        <div class="featured-brand-item">
                            <?php if ($brand_logo) : ?>
                                <img src="<?php echo esc_url($brand_logo); ?>" alt="<?php echo esc_attr($brand->name); ?>">
                            <?php else : ?>
                                <span><?php echo esc_html($brand->name); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Become a Partner CTA -->
        <div class="become-partner-cta">
            <h2><?php esc_html_e('Are You a Manufacturer?', 'autoparts-pro'); ?></h2>
            <p><?php esc_html_e('Join our network of premium automotive parts suppliers and reach thousands of customers.', 'autoparts-pro'); ?></p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-primary">
                <?php esc_html_e('Partner With Us', 'autoparts-pro'); ?>
            </a>
        </div>

    </div>
</div>

<?php get_footer(); ?>
