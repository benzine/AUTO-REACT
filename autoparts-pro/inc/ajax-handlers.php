<?php
/**
 * AJAX Handlers for AutoParts Pro Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Update wishlist via AJAX
 */
function autoparts_pro_update_wishlist() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'autoparts-pro-nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed', 'autoparts-pro' ) ) );
    }
    
    $product_id = isset( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : '';
    $wishlist   = isset( $_POST['wishlist'] ) ? json_decode( sanitize_text_field( $_POST['wishlist'] ), true ) : array();
    
    if ( empty( $product_id ) ) {
        wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'autoparts-pro' ) ) );
    }
    
    // For logged-in users, save to user meta
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        update_user_meta( $user_id, '_autoparts_wishlist', $wishlist );
    }
    
    // Return success with updated wishlist count
    wp_send_json_success( array(
        'count' => count( $wishlist ),
        'message' => in_array( $product_id, $wishlist ) 
            ? __( 'Added to wishlist!', 'autoparts-pro' )
            : __( 'Removed from wishlist', 'autoparts-pro' ),
    ) );
}
add_action( 'wp_ajax_autoparts_update_wishlist', 'autoparts_pro_update_wishlist' );
add_action( 'wp_ajax_nopriv_autoparts_update_wishlist', 'autoparts_pro_update_wishlist' );

/**
 * Search products by vehicle compatibility
 */
function autoparts_pro_search_by_vehicle() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'autoparts-pro-nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed', 'autoparts-pro' ) ) );
    }
    
    $year  = isset( $_POST['year'] ) ? sanitize_text_field( $_POST['year'] ) : '';
    $make  = isset( $_POST['make'] ) ? sanitize_text_field( $_POST['make'] ) : '';
    $model = isset( $_POST['model'] ) ? sanitize_text_field( $_POST['model'] ) : '';
    
    if ( empty( $year ) || empty( $make ) ) {
        wp_send_json_error( array( 'message' => __( 'Please select year and make', 'autoparts-pro' ) ) );
    }
    
    // Build query arguments
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => '_compatible_vehicles',
                'value'   => "{$year}_{$make}",
                'compare' => 'LIKE',
            ),
        ),
    );
    
    $query = new WP_Query( $args );
    
    if ( $query->have_posts() ) {
        $products = array();
        
        while ( $query->have_posts() ) {
            $query->the_post();
            
            $product = wc_get_product( get_the_ID() );
            
            if ( $product ) {
                $products[] = array(
                    'id'           => $product->get_id(),
                    'name'         => $product->get_name(),
                    'price'        => $product->get_price_html(),
                    'image'        => wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_thumbnail' ),
                    'permalink'    => $product->get_permalink(),
                    'stock_status' => $product->get_stock_status(),
                );
            }
        }
        
        wp_reset_postdata();
        
        wp_send_json_success( array(
            'count'    => count( $products ),
            'products' => $products,
        ) );
    } else {
        wp_send_json_error( array(
            'message' => __( 'No compatible parts found for this vehicle', 'autoparts-pro' ),
        ) );
    }
}
add_action( 'wp_ajax_autoparts_search_by_vehicle', 'autoparts_pro_search_by_vehicle' );
add_action( 'wp_ajax_nopriv_autoparts_search_by_vehicle', 'autoparts_pro_search_by_vehicle' );

/**
 * Save vehicle to garage
 */
function autoparts_pro_save_to_garage() {
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => __( 'Please log in to save vehicles', 'autoparts-pro' ) ) );
    }
    
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'autoparts-pro-nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed', 'autoparts-pro' ) ) );
    }
    
    $vehicle = array(
        'year'  => isset( $_POST['year'] ) ? sanitize_text_field( $_POST['year'] ) : '',
        'make'  => isset( $_POST['make'] ) ? sanitize_text_field( $_POST['make'] ) : '',
        'model' => isset( $_POST['model'] ) ? sanitize_text_field( $_POST['model'] ) : '',
        'engine' => isset( $_POST['engine'] ) ? sanitize_text_field( $_POST['engine'] ) : '',
    );
    
    if ( empty( $vehicle['year'] ) || empty( $vehicle['make'] ) ) {
        wp_send_json_error( array( 'message' => __( 'Invalid vehicle data', 'autoparts-pro' ) ) );
    }
    
    $user_id = get_current_user_id();
    $garage  = get_user_meta( $user_id, '_autoparts_garage', true ) ?: array();
    
    // Check if vehicle already exists
    $vehicle_key = $vehicle['year'] . '_' . $vehicle['make'] . '_' . $vehicle['model'];
    $exists = false;
    
    foreach ( $garage as $key => $saved_vehicle ) {
        if ( 
            $saved_vehicle['year'] == $vehicle['year'] &&
            $saved_vehicle['make'] == $vehicle['make'] &&
            $saved_vehicle['model'] == $vehicle['model']
        ) {
            $exists = true;
            break;
        }
    }
    
    if ( ! $exists ) {
        $garage[] = $vehicle;
        update_user_meta( $user_id, '_autoparts_garage', $garage );
    }
    
    wp_send_json_success( array(
        'message' => $exists 
            ? __( 'Vehicle already in garage', 'autoparts-pro' )
            : __( 'Vehicle saved to garage!', 'autoparts-pro' ),
        'count'   => count( $garage ),
    ) );
}
add_action( 'wp_ajax_autoparts_save_to_garage', 'autoparts_pro_save_to_garage' );

/**
 * Chatbot message handler (placeholder for AI integration)
 */
function autoparts_pro_chatbot_message() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'autoparts-pro-nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed', 'autoparts-pro' ) ) );
    }
    
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';
    
    if ( empty( $message ) ) {
        wp_send_json_error( array( 'message' => __( 'Please enter a message', 'autoparts-pro' ) ) );
    }
    
    // Simple keyword-based responses (replace with actual AI integration)
    $response = autoparts_pro_chatbot_get_response( $message );
    
    wp_send_json_success( array(
        'response' => $response,
    ) );
}
add_action( 'wp_ajax_autoparts_chatbot_message', 'autoparts_pro_chatbot_message' );
add_action( 'wp_ajax_nopriv_autoparts_chatbot_message', 'autoparts_pro_chatbot_message' );

/**
 * Get chatbot response based on keywords
 */
function autoparts_pro_chatbot_get_response( $message ) {
    $message = strtolower( $message );
    
    $responses = array(
        'shipping' => __( 'We offer same-day shipping on orders placed before 2PM. Standard delivery takes 2-5 business days.', 'autoparts-pro' ),
        'return'   => __( 'We have a 30-day hassle-free return policy. Items must be unused and in original packaging.', 'autoparts-pro' ),
        'warranty' => __( 'All our parts come with manufacturer warranty. Duration varies by product - check individual product pages for details.', 'autoparts-pro' ),
        'compatibility' => __( 'Use our vehicle selector at the top of the page to find parts that fit your specific vehicle. You can also save vehicles to your garage for quick access.', 'autoparts-pro' ),
        'payment'  => __( 'We accept all major credit cards, PayPal, and bank transfers. All transactions are secured with SSL encryption.', 'autoparts-pro' ),
        'track'    => __( 'You can track your order by logging into your account and visiting the Order History page. You\'ll receive tracking info via email once shipped.', 'autoparts-pro' ),
    );
    
    foreach ( $responses as $keyword => $response ) {
        if ( strpos( $message, $keyword ) !== false ) {
            return $response;
        }
    }
    
    // Default response
    return __( "Thanks for your message! Our team will get back to you shortly. For immediate assistance, try searching our FAQ or browse our product categories.", 'autoparts-pro' );
}
