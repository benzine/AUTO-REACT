<?php
/**
 * Custom Post Types for AutoParts Pro
 *
 * @package AutoParts_Pro
 */

namespace AutoPartsPro\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Vehicle post type
 */
function register_vehicle_post_type() {
    $labels = array(
        'name'                  => _x('Vehicles', 'Post Type General Name', 'autoparts-pro'),
        'singular_name'         => _x('Vehicle', 'Post Type Singular Name', 'autoparts-pro'),
        'menu_name'             => __('Vehicles', 'autoparts-pro'),
        'name_admin_bar'        => __('Vehicle', 'autoparts-pro'),
        'archives'              => __('Vehicle Archives', 'autoparts-pro'),
        'attributes'            => __('Vehicle Attributes', 'autoparts-pro'),
        'parent_item_colon'     => __('Parent Vehicle:', 'autoparts-pro'),
        'all_items'             => __('All Vehicles', 'autoparts-pro'),
        'add_new_item'          => __('Add New Vehicle', 'autoparts-pro'),
        'add_new'               => __('Add New', 'autoparts-pro'),
        'new_item'              => __('New Vehicle', 'autoparts-pro'),
        'edit_item'             => __('Edit Vehicle', 'autoparts-pro'),
        'update_item'           => __('Update Vehicle', 'autoparts-pro'),
        'view_item'             => __('View Vehicle', 'autoparts-pro'),
        'view_items'            => __('View Vehicles', 'autoparts-pro'),
        'search_items'          => __('Search Vehicles', 'autoparts-pro'),
        'not_found'             => __('No vehicles found.', 'autoparts-pro'),
        'not_found_in_trash'    => __('No vehicles found in Trash.', 'autoparts-pro'),
        'featured_image'        => __('Vehicle Image', 'autoparts-pro'),
        'set_featured_image'    => __('Set vehicle image', 'autoparts-pro'),
        'remove_featured_image' => __('Remove vehicle image', 'autoparts-pro'),
        'use_featured_image'    => __('Use as vehicle image', 'autoparts-pro'),
        'insert_into_item'      => __('Insert into vehicle', 'autoparts-pro'),
        'uploaded_to_this_item' => __('Uploaded to this vehicle', 'autoparts-pro'),
        'items_list'            => __('Vehicles list', 'autoparts-pro'),
        'items_list_navigation' => __('Vehicles list navigation', 'autoparts-pro'),
        'filter_items_list'     => __('Filter vehicles list', 'autoparts-pro'),
    );

    $args = array(
        'label'                 => __('Vehicle', 'autoparts-pro'),
        'description'           => __('Vehicle makes, models, and years for compatibility checking', 'autoparts-pro'),
        'labels'                => $labels,
        'supports'              => array('title', 'thumbnail', 'custom-fields'),
        'taxonomies'            => array('vehicle_make', 'vehicle_model', 'vehicle_year'),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-car',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type('vehicle', $args);
}
add_action('init', __NAMESPACE__ . '\\register_vehicle_post_type');

/**
 * Register Brand post type
 */
function register_brand_post_type() {
    $labels = array(
        'name'                  => _x('Brands', 'Post Type General Name', 'autoparts-pro'),
        'singular_name'         => _x('Brand', 'Post Type Singular Name', 'autoparts-pro'),
        'menu_name'             => __('Brands', 'autoparts-pro'),
        'name_admin_bar'        => __('Brand', 'autoparts-pro'),
        'archives'              => __('Brand Archives', 'autoparts-pro'),
        'all_items'             => __('All Brands', 'autoparts-pro'),
        'add_new_item'          => __('Add New Brand', 'autoparts-pro'),
        'add_new'               => __('Add New', 'autoparts-pro'),
        'new_item'              => __('New Brand', 'autoparts-pro'),
        'edit_item'             => __('Edit Brand', 'autoparts-pro'),
        'update_item'           => __('Update Brand', 'autoparts-pro'),
        'view_item'             => __('View Brand', 'autoparts-pro'),
        'view_items'            => __('View Brands', 'autoparts-pro'),
        'search_items'          => __('Search Brands', 'autoparts-pro'),
        'not_found'             => __('No brands found.', 'autoparts-pro'),
        'not_found_in_trash'    => __('No brands found in Trash.', 'autoparts-pro'),
        'featured_image'        => __('Brand Logo', 'autoparts-pro'),
        'set_featured_image'    => __('Set brand logo', 'autoparts-pro'),
        'remove_featured_image' => __('Remove brand logo', 'autoparts-pro'),
        'use_featured_image'    => __('Use as brand logo', 'autoparts-pro'),
    );

    $args = array(
        'label'                 => __('Brand', 'autoparts-pro'),
        'description'           => __('Auto parts brands and manufacturers', 'autoparts-pro'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 21,
        'menu_icon'             => 'dashicons-businessman',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type('brand', $args);
}
add_action('init', __NAMESPACE__ . '\\register_brand_post_type');

/**
 * Register vehicle taxonomy (Make)
 */
function register_vehicle_make_taxonomy() {
    $labels = array(
        'name'                       => _x('Makes', 'Taxonomy General Name', 'autoparts-pro'),
        'singular_name'              => _x('Make', 'Taxonomy Singular Name', 'autoparts-pro'),
        'menu_name'                  => __('Makes', 'autoparts-pro'),
        'all_items'                  => __('All Makes', 'autoparts-pro'),
        'parent_item'                => __('Parent Make', 'autoparts-pro'),
        'parent_item_colon'          => __('Parent Make:', 'autoparts-pro'),
        'new_item_name'              => __('New Make Name', 'autoparts-pro'),
        'add_new_item'               => __('Add New Make', 'autoparts-pro'),
        'edit_item'                  => __('Edit Make', 'autoparts-pro'),
        'update_item'                => __('Update Make', 'autoparts-pro'),
        'view_item'                  => __('View Make', 'autoparts-pro'),
        'separate_items_with_commas' => __('Separate makes with commas', 'autoparts-pro'),
        'add_or_remove_items'        => __('Add or remove makes', 'autoparts-pro'),
        'choose_from_most_used'      => __('Choose from the most used', 'autoparts-pro'),
        'popular_items'              => __('Popular Makes', 'autoparts-pro'),
        'search_items'               => __('Search Makes', 'autoparts-pro'),
        'not_found'                  => __('Not Found', 'autoparts-pro'),
        'no_terms'                   => __('No items', 'autoparts-pro'),
        'items_list'                 => __('Makes list', 'autoparts-pro'),
        'items_list_navigation'      => __('Makes list navigation', 'autoparts-pro'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
    );

    register_taxonomy('vehicle_make', array('vehicle', 'product'), $args);
}
add_action('init', __NAMESPACE__ . '\\register_vehicle_make_taxonomy');

/**
 * Register vehicle taxonomy (Year)
 */
function register_vehicle_year_taxonomy() {
    $labels = array(
        'name'                       => _x('Years', 'Taxonomy General Name', 'autoparts-pro'),
        'singular_name'              => _x('Year', 'Taxonomy Singular Name', 'autoparts-pro'),
        'menu_name'                  => __('Years', 'autoparts-pro'),
        'all_items'                  => __('All Years', 'autoparts-pro'),
        'new_item_name'              => __('New Year Name', 'autoparts-pro'),
        'add_new_item'               => __('Add New Year', 'autoparts-pro'),
        'edit_item'                  => __('Edit Year', 'autoparts-pro'),
        'update_item'                => __('Update Year', 'autoparts-pro'),
        'view_item'                  => __('View Year', 'autoparts-pro'),
        'search_items'               => __('Search Years', 'autoparts-pro'),
        'not_found'                  => __('Not Found', 'autoparts-pro'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
    );

    register_taxonomy('vehicle_year', array('vehicle', 'product'), $args);
}
add_action('init', __NAMESPACE__ . '\\register_vehicle_year_taxonomy');

/**
 * Flush rewrite rules on activation
 */
function flush_rewrite_rules_on_activation() {
    register_vehicle_post_type();
    register_brand_post_type();
    register_vehicle_make_taxonomy();
    register_vehicle_year_taxonomy();
    flush_rewrite_rules();
}

/**
 * Add meta boxes for vehicle compatibility
 */
function add_vehicle_meta_boxes() {
    add_meta_box(
        'vehicle_compatibility',
        __('Compatible Parts', 'autoparts-pro'),
        __NAMESPACE__ . '\\render_vehicle_compatibility_meta_box',
        'vehicle',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', __NAMESPACE__ . '\\add_vehicle_meta_boxes');

/**
 * Render vehicle compatibility meta box
 */
function render_vehicle_compatibility_meta_box($post) {
    wp_nonce_field('vehicle_compatibility_nonce', 'vehicle_compatibility_nonce');
    
    $compatible_parts = get_post_meta($post->ID, '_compatible_parts', true);
    ?>
    <p><?php _e('Select products that are compatible with this vehicle:', 'autoparts-pro'); ?></p>
    <select name="compatible_parts[]" multiple style="width: 100%; height: 300px;">
        <?php
        $products = wc_get_products(array(
            'limit' => -1,
            'return' => 'ids',
        ));
        
        foreach ($products as $product_id) {
            $selected = is_array($compatible_parts) && in_array($product_id, $compatible_parts) ? 'selected' : '';
            echo '<option value="' . esc_attr($product_id) . '" ' . $selected . '>' . 
                 esc_html(get_the_title($product_id)) . '</option>';
        }
        ?>
    </select>
    <p class="description"><?php _e('Hold Ctrl/Cmd to select multiple products', 'autoparts-pro'); ?></p>
    <?php
}

/**
 * Save vehicle meta box data
 */
function save_vehicle_meta_box($post_id) {
    if (!isset($_POST['vehicle_compatibility_nonce']) || 
        !wp_verify_nonce($_POST['vehicle_compatibility_nonce'], 'vehicle_compatibility_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['compatible_parts'])) {
        update_post_meta($post_id, '_compatible_parts', array_map('absint', $_POST['compatible_parts']));
    } else {
        delete_post_meta($post_id, '_compatible_parts');
    }
}
add_action('save_post_vehicle', __NAMESPACE__ . '\\save_vehicle_meta_box');
