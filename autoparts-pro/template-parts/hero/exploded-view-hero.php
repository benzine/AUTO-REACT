<?php
/**
 * Template part for displaying the exploded view hero section
 * 
 * @package AutoParts_Pro
 */

// Get theme options
$hero_type = get_theme_mod('hero_type', 'exploded_view');
$hero_video = get_theme_mod('hero_video', '');
$hero_image = get_theme_mod('hero_image', '');
$hero_title = get_theme_mod('hero_title', __('Premium Auto Parts', 'autoparts-pro'));
$hero_subtitle = get_theme_mod('hero_subtitle', __('Engineered for Performance', 'autoparts-pro'));
$explosion_speed = get_theme_mod('explosion_speed', 1.5);
$show_labels = get_theme_mod('hero_show_labels', true);

// Vehicle selector visibility
$show_vehicle_selector = get_theme_mod('show_vehicle_selector_hero', true);
?>

<section id="hero-section" class="autoparts-hero <?php echo esc_attr($hero_type); ?>" 
         data-explosion-speed="<?php echo esc_attr($explosion_speed); ?>"
         data-show-labels="<?php echo esc_attr($show_labels ? 'true' : 'false'); ?>">
    
    <!-- Hero Background/Canvas -->
    <div class="hero-canvas-container">
        <?php if ($hero_type === 'exploded_view' || $hero_type === '3d') : ?>
            <!-- Three.js canvas will be injected here -->
            <canvas id="hero-canvas" data-type="exploded-view"></canvas>
            
            <!-- Loading overlay -->
            <div class="hero-loading">
                <div class="loading-spinner"></div>
                <span><?php esc_html_e('Loading 3D Engine...', 'autoparts-pro'); ?></span>
            </div>
        <?php elseif ($hero_type === 'video' && $hero_video) : ?>
            <video class="hero-video" autoplay muted loop playsinline poster="<?php echo esc_url($hero_image); ?>">
                <source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
                <?php esc_html_e('Your browser does not support the video tag.', 'autoparts-pro'); ?>
            </video>
        <?php else : ?>
            <div class="hero-image" style="background-image: url('<?php echo esc_url($hero_image); ?>');"></div>
        <?php endif; ?>
        
        <!-- Gradient Overlay -->
        <div class="hero-overlay"></div>
    </div>

    <!-- Hero Content -->
    <div class="hero-content container">
        <div class="hero-text">
            <?php if (has_custom_logo()) : ?>
                <div class="hero-logo">
                    <?php the_custom_logo(); ?>
                </div>
            <?php endif; ?>
            
            <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
            <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
            
            <?php if ($show_vehicle_selector) : ?>
                <div class="hero-vehicle-selector">
                    <h4><?php esc_html_e('Find Parts For Your Vehicle', 'autoparts-pro'); ?></h4>
                    <form class="vehicle-search-form" method="get" action="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
                        <div class="vehicle-fields">
                            <select name="vehicle_year" class="vehicle-select" required>
                                <option value=""><?php esc_html_e('Year', 'autoparts-pro'); ?></option>
                                <?php for ($year = date('Y'); $year >= 1980; $year--) : ?>
                                    <option value="<?php echo esc_attr($year); ?>"><?php echo esc_html($year); ?></option>
                                <?php endfor; ?>
                            </select>
                            
                            <select name="vehicle_make" class="vehicle-select" required disabled>
                                <option value=""><?php esc_html_e('Make', 'autoparts-pro'); ?></option>
                            </select>
                            
                            <select name="vehicle_model" class="vehicle-select" required disabled>
                                <option value=""><?php esc_html_e('Model', 'autoparts-pro'); ?></option>
                            </select>
                            
                            <select name="vehicle_engine" class="vehicle-select" required disabled>
                                <option value=""><?php esc_html_e('Engine', 'autoparts-pro'); ?></option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-search"></i> <?php esc_html_e('Find Parts', 'autoparts-pro'); ?>
                        </button>
                    </form>
                    
                    <p class="vehicle-selector-note">
                        <?php esc_html_e('Or', 'autoparts-pro'); ?> 
                        <a href="#" class="browse-categories"><?php esc_html_e('browse all categories', 'autoparts-pro'); ?></a>
                    </p>
                </div>
            <?php endif; ?>
            
            <!-- CTA Buttons -->
            <div class="hero-cta">
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-primary btn-large">
                    <i class="fas fa-store"></i> <?php esc_html_e('Shop Now', 'autoparts-pro'); ?>
                </a>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary btn-large">
                    <i class="fas fa-headset"></i> <?php esc_html_e('Get Help', 'autoparts-pro'); ?>
                </a>
            </div>
            
            <!-- Trust Indicators -->
            <div class="hero-trust-indicators">
                <div class="trust-item">
                    <i class="fas fa-shipping-fast"></i>
                    <span><?php esc_html_e('Free Shipping $99+', 'autoparts-pro'); ?></span>
                </div>
                <div class="trust-item">
                    <i class="fas fa-undo"></i>
                    <span><?php esc_html_e('30-Day Returns', 'autoparts-pro'); ?></span>
                </div>
                <div class="trust-item">
                    <i class="fas fa-shield-alt"></i>
                    <span><?php esc_html_e('2-Year Warranty', 'autoparts-pro'); ?></span>
                </div>
                <div class="trust-item">
                    <i class="fas fa-star"></i>
                    <span><?php esc_html_e('4.9/5 Rating', 'autoparts-pro'); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Exploded View Labels (for 3D mode) -->
        <?php if ($hero_type === 'exploded_view' && $show_labels) : ?>
            <div class="exploded-labels">
                <div class="exploded-label" data-part="piston">
                    <div class="label-marker"></div>
                    <div class="label-content">
                        <strong><?php esc_html_e('Pistons', 'autoparts-pro'); ?></strong>
                        <span><?php esc_html_e('High-performance forged aluminum', 'autoparts-pro'); ?></span>
                    </div>
                </div>
                
                <div class="exploded-label" data-part="crankshaft">
                    <div class="label-marker"></div>
                    <div class="label-content">
                        <strong><?php esc_html_e('Crankshaft', 'autoparts-pro'); ?></strong>
                        <span><?php esc_html_e('Precision-balanced steel alloy', 'autoparts-pro'); ?></span>
                    </div>
                </div>
                
                <div class="exploded-label" data-part="camshaft">
                    <div class="label-marker"></div>
                    <div class="label-content">
                        <strong><?php esc_html_e('Camshaft', 'autoparts-pro'); ?></strong>
                        <span><?php esc_html_e('Performance timing control', 'autoparts-pro'); ?></span>
                    </div>
                </div>
                
                <div class="exploded-label" data-part="valves">
                    <div class="label-marker"></div>
                    <div class="label-content">
                        <strong><?php esc_html_e('Valves', 'autoparts-pro'); ?></strong>
                        <span><?php esc_html_e('Titanium intake & exhaust', 'autoparts-pro'); ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator">
        <div class="mouse-icon">
            <div class="wheel"></div>
        </div>
        <span><?php esc_html_e('Scroll to explore', 'autoparts-pro'); ?></span>
    </div>
    
    <!-- Progress indicator for scroll animation -->
    <div class="hero-progress">
        <div class="progress-bar"></div>
    </div>
</section>

<?php
// Enqueue Three.js and GSAP for exploded view
if ($hero_type === 'exploded_view' || $hero_type === '3d') {
    wp_enqueue_script('three-js');
    wp_enqueue_script('gsap');
    wp_enqueue_script('gsap-scrolltrigger');
}
?>
