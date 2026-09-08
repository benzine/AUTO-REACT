<?php
/**
 * Main Template File
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main class="site-main" id="primary">
    
    <!-- Hero Section with Exploded View Animation -->
    <section class="hero-exploded-view">
        <div class="engine-model">
            <!-- Engine Parts - These will explode on scroll -->
            <div class="engine-part" data-part="piston"><i class="fas fa-circle"></i></div>
            <div class="engine-part" data-part="crankshaft"><i class="fas fa-minus"></i></div>
            <div class="engine-part" data-part="valve"><i class="fas fa-arrow-down"></i></div>
            <div class="engine-part" data-part="camshaft"><i class="fas fa-grip-horizontal"></i></div>
            <div class="engine-part" data-part="spark-plug"><i class="fas fa-bolt"></i></div>
            <div class="engine-part" data-part="gasket"><i class="fas fa-ring"></i></div>
            
            <!-- Part Labels -->
            <div class="part-label" data-target="piston"><?php esc_html_e( 'Piston', 'autoparts-pro' ); ?></div>
            <div class="part-label" data-target="crankshaft"><?php esc_html_e( 'Crankshaft', 'autoparts-pro' ); ?></div>
            <div class="part-label" data-target="valve"><?php esc_html_e( 'Valve', 'autoparts-pro' ); ?></div>
            <div class="part-label" data-target="camshaft"><?php esc_html_e( 'Camshaft', 'autoparts-pro' ); ?></div>
            <div class="part-label" data-target="spark-plug"><?php esc_html_e( 'Spark Plug', 'autoparts-pro' ); ?></div>
            <div class="part-label" data-target="gasket"><?php esc_html_e( 'Gasket', 'autoparts-pro' ); ?></div>
        </div>
        
        <div class="hero-content">
            <h1><?php echo esc_html( get_theme_mod( 'hero_title', __( 'Premium Auto Parts', 'autoparts-pro' ) ) ); ?></h1>
            <p><?php echo esc_html( get_theme_mod( 'hero_subtitle', __( 'Engineered for Excellence', 'autoparts-pro' ) ) ); ?></p>
        </div>
    </section>
    
    <!-- Product Categories Section -->
    <section class="categories-section">
        <div class="container">
            <header class="section-header">
                <h2><?php esc_html_e( 'Shop by Category', 'autoparts-pro' ); ?></h2>
                <p><?php esc_html_e( 'Find the perfect parts for your vehicle', 'autoparts-pro' ); ?></p>
            </header>
            
            <?php autoparts_pro_categories_grid( array( 'number' => 8 ) ); ?>
        </div>
    </section>
    
    <!-- Featured Products Section -->
    <section class="featured-products-section">
        <div class="container">
            <header class="section-header">
                <h2><?php esc_html_e( 'Featured Products', 'autoparts-pro' ); ?></h2>
                <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="view-all-link">
                    <?php esc_html_e( 'View All', 'autoparts-pro' ); ?> <i class="fas fa-arrow-right"></i>
                </a>
            </header>
            
            <?php autoparts_pro_featured_products( array( 'limit' => 8, 'columns' => 4 ) ); ?>
        </div>
    </section>
    
    <!-- Flash Deals Section -->
    <section class="flash-deals-section">
        <div class="container">
            <header class="section-header">
                <div>
                    <h2><?php esc_html_e( 'Flash Deals', 'autoparts-pro' ); ?></h2>
                    <p><?php esc_html_e( 'Limited time offers - Don\'t miss out!', 'autoparts-pro' ); ?></p>
                </div>
                
                <?php 
                // Set deal end time (24 hours from now for demo)
                $deal_end = strtotime( '+24 hours' );
                autoparts_pro_flash_deal_timer( date( 'Y-m-d H:i:s', $deal_end ) );
                ?>
            </header>
            
            <?php autoparts_pro_sale_products( array( 'limit' => 8, 'columns' => 4 ) ); ?>
        </div>
    </section>
    
    <!-- Why Choose Us Section -->
    <section class="features-section">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shipping-fast"></i></div>
                    <h3><?php esc_html_e( 'Fast Shipping', 'autoparts-pro' ); ?></h3>
                    <p><?php esc_html_e( 'Same-day dispatch on orders before 2PM', 'autoparts-pro' ); ?></p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    <h3><?php esc_html_e( 'Quality Guarantee', 'autoparts-pro' ); ?></h3>
                    <p><?php esc_html_e( 'OEM and premium aftermarket parts only', 'autoparts-pro' ); ?></p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-headset"></i></div>
                    <h3><?php esc_html_e( 'Expert Support', 'autoparts-pro' ); ?></h3>
                    <p><?php esc_html_e( 'Certified mechanics ready to help', 'autoparts-pro' ); ?></p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-undo"></i></div>
                    <h3><?php esc_html_e( 'Easy Returns', 'autoparts-pro' ); ?></h3>
                    <p><?php esc_html_e( '30-day hassle-free return policy', 'autoparts-pro' ); ?></p>
                </div>
            </div>
        </div>
    </section>
    
</main>

<?php
get_footer();
