/**
 * Template Tags for AutoParts Pro Theme
 * Custom functions for displaying theme elements
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display the primary logo with light/dark mode variants
 */
function autoparts_pro_the_logo() {
    $logo_light = get_theme_mod( 'logo_light', '' );
    $logo_dark = get_theme_mod( 'logo_dark', '' );
    $logo_text = get_bloginfo( 'name' );
    
    if ( has_custom_logo() ) {
        the_custom_logo();
    } else {
        ?>
        <div class="site-logo">
            <?php if ( $logo_light || $logo_dark ) : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-link">
                    <?php if ( $logo_light ) : ?>
                        <img src="<?php echo esc_url( $logo_light ); ?>" alt="<?php echo esc_attr( $logo_text ); ?>" class="logo-light">
                    <?php endif; ?>
                    <?php if ( $logo_dark ) : ?>
                        <img src="<?php echo esc_url( $logo_dark ); ?>" alt="<?php echo esc_attr( $logo_text ); ?>" class="logo-dark">
                    <?php endif; ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-text">
                    <?php echo esc_html( $logo_text ); ?>
                </a>
            <?php endif; ?>
        </div>
        <?php
    }
}

/**
 * Display the gear shift navigation menu
 */
function autoparts_pro_gear_shift_menu() {
    wp_nav_menu( array(
        'theme_location' => 'primary',
        'menu_class'     => 'gear-shift-menu',
        'container'      => 'nav',
        'container_class'=> 'gear-shift-navigation',
        'fallback_cb'    => false,
    ) );
}

/**
 * Display dark/light mode toggle switch
 */
function autoparts_pro_dark_mode_toggle() {
    ?>
    <button class="dark-mode-toggle" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'autoparts-pro' ); ?>">
        <i class="fas fa-moon"></i>
        <span class="toggle-text"><?php esc_html_e( 'Dark Mode', 'autoparts-pro' ); ?></span>
    </button>
    <?php
}

/**
 * Display wishlist button with counter
 */
function autoparts_pro_wishlist_button() {
    $wishlist_count = 0;
    if ( is_user_logged_in() ) {
        $wishlist_count = count( get_user_meta( get_current_user_id(), '_autoparts_wishlist', true ) ?: array() );
    }
    ?>
    <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'wishlist' ) ) ); ?>" class="wishlist-link">
        <i class="far fa-heart"></i>
        <span class="wishlist-counter"><?php echo esc_html( $wishlist_count ); ?></span>
        <span class="wishlist-text"><?php esc_html_e( 'Wishlist', 'autoparts-pro' ); ?></span>
    </a>
    <?php
}

/**
 * Display vehicle compatibility selector
 */
function autoparts_pro_vehicle_selector() {
    global $wpdb;
    
    // Get years (last 20 years)
    $current_year = date( 'Y' );
    $years = range( $current_year, $current_year - 20 );
    
    // Sample makes - in production, fetch from database or API
    $makes = array(
        'Toyota', 'Honda', 'Ford', 'Chevrolet', 'BMW', 'Mercedes-Benz',
        'Audi', 'Volkswagen', 'Nissan', 'Mazda', 'Subaru', 'Hyundai',
        'Kia', 'Lexus', 'Porsche', 'Ferrari', 'Lamborghini'
    );
    
    ?>
    <form class="vehicle-selector-form" method="get" action="<?php echo esc_url( home_url( '/shop' ) ); ?>">
        <div class="selector-group">
            <label for="vehicle-year"><?php esc_html_e( 'Year', 'autoparts-pro' ); ?></label>
            <select name="vehicle_year" id="vehicle-year" class="vehicle-select">
                <option value=""><?php esc_html_e( 'Select Year', 'autoparts-pro' ); ?></option>
                <?php foreach ( $years as $year ) : ?>
                    <option value="<?php echo esc_attr( $year ); ?>"><?php echo esc_html( $year ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="selector-group">
            <label for="vehicle-make"><?php esc_html_e( 'Make', 'autoparts-pro' ); ?></label>
            <select name="vehicle_make" id="vehicle-make" class="vehicle-select">
                <option value=""><?php esc_html_e( 'Select Make', 'autoparts-pro' ); ?></option>
                <?php foreach ( $makes as $make ) : ?>
                    <option value="<?php echo esc_attr( strtolower( str_replace( '-', '_', $make ) ) ); ?>"><?php echo esc_html( $make ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="selector-group">
            <label for="vehicle-model"><?php esc_html_e( 'Model', 'autoparts-pro' ); ?></label>
            <select name="vehicle_model" id="vehicle-model" class="vehicle-select">
                <option value=""><?php esc_html_e( 'Select Model', 'autoparts-pro' ); ?></option>
            </select>
        </div>
        
        <button type="submit" class="vehicle-search-btn">
            <i class="fas fa-search"></i>
            <?php esc_html_e( 'Find Parts', 'autoparts-pro' ); ?>
        </button>
    </form>
    <?php
}

/**
 * Display product categories grid
 */
function autoparts_pro_categories_grid( $args = array() ) {
    $defaults = array(
        'taxonomy'   => 'product_cat',
        'number'     => 8,
        'hide_empty' => true,
        'parent'     => 0,
    );
    
    $args = wp_parse_args( $args, $defaults );
    $categories = get_terms( $args );
    
    if ( empty( $categories ) || is_wp_error( $categories ) ) {
        return;
    }
    
    ?>
    <div class="categories-grid">
        <?php foreach ( $categories as $category ) : ?>
            <div class="category-card">
                <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="category-link">
                    <?php
                    $thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
                    if ( $thumbnail_id ) {
                        echo wp_get_attachment_image( $thumbnail_id, 'autoparts-category', false, array(
                            'class' => 'category-image',
                            'alt'   => $category->name,
                        ) );
                    } else {
                        ?>
                        <div class="category-placeholder">
                            <i class="fas fa-cog"></i>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="category-overlay">
                        <h3 class="category-title"><?php echo esc_html( $category->name ); ?></h3>
                        <span class="category-count">
                            <?php echo esc_html( sprintf( _n( '%d Product', '%d Products', $category->count, 'autoparts-pro' ), $category->count ) ); ?>
                        </span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Display featured products section
 */
function autoparts_pro_featured_products( $args = array() ) {
    $defaults = array(
        'limit'      => 8,
        'columns'    => 4,
        'visibility' => 'featured',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    echo do_shortcode( '[products limit="' . esc_attr( $args['limit'] ) . '" columns="' . esc_attr( $args['columns'] ) . '" visibility="featured"]' );
}

/**
 * Display sale products section
 */
function autoparts_pro_sale_products( $args = array() ) {
    $defaults = array(
        'limit'   => 8,
        'columns' => 4,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    echo do_shortcode( '[products limit="' . esc_attr( $args['limit'] ) . '" columns="' . esc_attr( $args['columns'] ) . '" on_sale="true"]' );
}

/**
 * Display countdown timer for flash deals
 */
function autoparts_pro_flash_deal_timer( $end_time ) {
    ?>
    <div class="countdown-timer" data-end-time="<?php echo esc_attr( $end_time ); ?>">
        <div class="countdown-segment">
            <span class="number">00</span>
            <span class="label"><?php esc_html_e( 'Days', 'autoparts-pro' ); ?></span>
        </div>
        <div class="countdown-segment">
            <span class="number">00</span>
            <span class="label"><?php esc_html_e( 'Hours', 'autoparts-pro' ); ?></span>
        </div>
        <div class="countdown-segment">
            <span class="number">00</span>
            <span class="label"><?php esc_html_e( 'Minutes', 'autoparts-pro' ); ?></span>
        </div>
        <div class="countdown-segment">
            <span class="number">00</span>
            <span class="label"><?php esc_html_e( 'Seconds', 'autoparts-pro' ); ?></span>
        </div>
    </div>
    <?php
}

/**
 * Display breadcrumb navigation
 */
function autoparts_pro_breadcrumb() {
    if ( function_exists( 'yoast_breadcrumb' ) ) {
        yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
    } elseif ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
        rank_math_the_breadcrumbs();
    } else {
        // Fallback breadcrumb
        echo '<nav class="breadcrumb-nav">';
        echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'autoparts-pro' ) . '</a>';
        
        if ( is_category() || is_single() ) {
            echo ' <span class="separator">/</span> ';
            the_category( ' / ' );
        }
        
        if ( is_single() ) {
            echo ' <span class="separator">/</span> ';
            the_title();
        }
        
        if ( is_page() ) {
            echo ' <span class="separator">/</span> ';
            the_title();
        }
        
        echo '</nav>';
    }
}

/**
 * Display social share buttons
 */
function autoparts_pro_share_buttons() {
    $url   = urlencode( get_permalink() );
    $title = urlencode( get_the_title() );
    
    ?>
    <div class="share-buttons">
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener" class="share-btn facebook" aria-label="<?php esc_attr_e( 'Share on Facebook', 'autoparts-pro' ); ?>">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener" class="share-btn twitter" aria-label="<?php esc_attr_e( 'Share on Twitter', 'autoparts-pro' ); ?>">
            <i class="fab fa-twitter"></i>
        </a>
        <a href="https://wa.me/?text=<?php echo $title . ' ' . $url; ?>" target="_blank" rel="noopener" class="share-btn whatsapp" aria-label="<?php esc_attr_e( 'Share on WhatsApp', 'autoparts-pro' ); ?>">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url; ?>&title=<?php echo $title; ?>" target="_blank" rel="noopener" class="share-btn linkedin" aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'autoparts-pro' ); ?>">
            <i class="fab fa-linkedin-in"></i>
        </a>
        <button class="share-btn copy-link" onclick="navigator.clipboard.writeText('<?php echo esc_js( get_permalink() ); ?>')" aria-label="<?php esc_attr_e( 'Copy link', 'autoparts-pro' ); ?>">
            <i class="fas fa-link"></i>
        </button>
    </div>
    <?php
}

/**
 * Display AI chatbot widget
 */
function autoparts_pro_chatbot_widget() {
    $greeting = get_theme_mod( 'chatbot_greeting', __( "Hi! I'm your automotive assistant. How can I help you find parts today?", 'autoparts-pro' ) );
    $avatar   = get_theme_mod( 'chatbot_avatar', '' );
    $enabled  = get_theme_mod( 'chatbot_enabled', true );
    
    if ( ! $enabled ) {
        return;
    }
    
    ?>
    <div class="chatbot-container">
        <button class="chatbot-toggle" aria-label="<?php esc_attr_e( 'Open chat', 'autoparts-pro' ); ?>">
            <i class="fas fa-comments"></i>
            <span class="chatbot-badge">1</span>
        </button>
        
        <div class="chatbot-widget">
            <div class="chatbot-header">
                <div class="chatbot-avatar">
                    <?php if ( $avatar ) : ?>
                        <img src="<?php echo esc_url( $avatar ); ?>" alt="<?php esc_attr_e( 'Chatbot', 'autoparts-pro' ); ?>">
                    <?php else : ?>
                        <i class="fas fa-robot"></i>
                    <?php endif; ?>
                </div>
                <div class="chatbot-info">
                    <h4><?php esc_html_e( 'Auto Assistant', 'autoparts-pro' ); ?></h4>
                    <span class="status online"><?php esc_html_e( 'Online', 'autoparts-pro' ); ?></span>
                </div>
                <button class="chatbot-close"><i class="fas fa-times"></i></button>
            </div>
            
            <div class="chatbot-messages">
                <div class="message bot">
                    <div class="message-content">
                        <?php echo esc_html( $greeting ); ?>
                    </div>
                </div>
            </div>
            
            <div class="chatbot-input">
                <input type="text" placeholder="<?php esc_attr_e( 'Type your message...', 'autoparts-pro' ); ?>">
                <button><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display back to top button
 */
function autoparts_pro_back_to_top() {
    $icon = get_theme_mod( 'back_to_top_icon', 'arrow' );
    
    $icons = array(
        'arrow'  => 'fa-arrow-up',
        'gear'   => 'fa-cog',
        'rocket' => 'fa-rocket',
    );
    
    $icon_class = isset( $icons[ $icon ] ) ? $icons[ $icon ] : $icons['arrow'];
    
    ?>
    <a href="#" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'autoparts-pro' ); ?>">
        <i class="fas <?php echo esc_attr( $icon_class ); ?>"></i>
    </a>
    <?php
}

/**
 * Check if product fits user's vehicle
 */
function autoparts_pro_compatibility_check( $product_id ) {
    // Get user's saved vehicles from garage
    $user_id = get_current_user_id();
    $garage  = get_user_meta( $user_id, '_autoparts_garage', true ) ?: array();
    
    if ( empty( $garage ) ) {
        return null;
    }
    
    // Get product compatibility data
    $compatible_vehicles = get_post_meta( $product_id, '_compatible_vehicles', true ) ?: array();
    
    foreach ( $garage as $vehicle ) {
        // Check if product is compatible with any saved vehicle
        // This is a simplified check - in production, use proper matching logic
        if ( in_array( $vehicle['year'] . '_' . $vehicle['make'] . '_' . $vehicle['model'], $compatible_vehicles ) ) {
            return true;
        }
    }
    
    return false;
}

/**
 * Display stock status badge
 */
function autoparts_pro_stock_status( $product_id ) {
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return;
    }
    
    $stock_quantity = $product->get_stock_quantity();
    $stock_status   = $product->get_stock_status();
    
    if ( 'instock' === $stock_status ) {
        if ( $stock_quantity && $stock_quantity <= 5 ) {
            echo '<span class="stock-status low-stock">' . esc_html__( 'Low Stock - Only ' . $stock_quantity . ' left!', 'autoparts-pro' ) . '</span>';
        } else {
            echo '<span class="stock-status in-stock">' . esc_html__( 'In Stock', 'autoparts-pro' ) . '</span>';
        }
    } elseif ( 'outofstock' === $stock_status ) {
        echo '<span class="stock-status out-of-stock">' . esc_html__( 'Out of Stock', 'autoparts-pro' ) . '</span>';
    }
}
