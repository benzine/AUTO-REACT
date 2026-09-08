<?php
/**
 * Customizer Additions for AutoParts Pro Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add customizer sections, settings, and controls
 */
function autoparts_pro_customize_register( $wp_customize ) {
    
    // ==========================================
    // COLOR PALETTE SECTION
    // ==========================================
    
    $wp_customize->add_section( 'autoparts_colors', array(
        'title'    => __( 'Color Palette', 'autoparts-pro' ),
        'priority' => 30,
    ) );
    
    // Light Mode Colors
    $wp_customize->add_setting( 'color_primary_red', array(
        'default'           => '#DC2626',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_primary_red', array(
        'label'   => __( 'Primary Red (Racing Red)', 'autoparts-pro' ),
        'section' => 'autoparts_colors',
    ) ) );
    
    $wp_customize->add_setting( 'color_dark_black', array(
        'default'           => '#0A0A0A',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_dark_black', array(
        'label'   => __( 'Dark Black (Obsidian)', 'autoparts-pro' ),
        'section' => 'autoparts_colors',
    ) ) );
    
    $wp_customize->add_setting( 'color_accent_orange', array(
        'default'           => '#EA580C',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_accent_orange', array(
        'label'   => __( 'Accent Orange (Burnt)', 'autoparts-pro' ),
        'section' => 'autoparts_colors',
    ) ) );
    
    // Dark Mode Colors
    $wp_customize->add_setting( 'dark_mode_bg', array(
        'default'           => '#0A0A0A',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_mode_bg', array(
        'label'   => __( 'Dark Mode Background', 'autoparts-pro' ),
        'section' => 'autoparts_colors',
    ) ) );
    
    // ==========================================
    // TYPOGRAPHY SECTION
    // ==========================================
    
    $wp_customize->add_section( 'autoparts_typography', array(
        'title'    => __( 'Typography', 'autoparts-pro' ),
        'priority' => 35,
    ) );
    
    $wp_customize->add_setting( 'font_heading', array(
        'default'           => 'Rajdhani',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'font_heading', array(
        'label'   => __( 'Heading Font', 'autoparts-pro' ),
        'section' => 'autoparts_typography',
        'type'    => 'select',
        'choices' => array(
            'Rajdhani' => 'Rajdhani',
            'Oswald'   => 'Oswald',
            'Roboto'   => 'Roboto',
        ),
    ) );
    
    $wp_customize->add_setting( 'font_body', array(
        'default'           => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'font_body', array(
        'label'   => __( 'Body Font', 'autoparts-pro' ),
        'section' => 'autoparts_typography',
        'type'    => 'select',
        'choices' => array(
            'Inter'     => 'Inter',
            'Open Sans' => 'Open Sans',
            'Roboto'    => 'Roboto',
        ),
    ) );
    
    // ==========================================
    // LOGO SECTION
    // ==========================================
    
    $wp_customize->add_section( 'autoparts_logo', array(
        'title'    => __( 'Logo Settings', 'autoparts-pro' ),
        'priority' => 35,
    ) );
    
    $wp_customize->add_setting( 'logo_light', array(
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo_light', array(
        'label'   => __( 'Light Mode Logo', 'autoparts-pro' ),
        'section' => 'autoparts_logo',
    ) ) );
    
    $wp_customize->add_setting( 'logo_dark', array(
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo_dark', array(
        'label'   => __( 'Dark Mode Logo', 'autoparts-pro' ),
        'section' => 'autoparts_logo',
    ) ) );
    
    // ==========================================
    // HERO SECTION SETTINGS
    // ==========================================
    
    $wp_customize->add_section( 'autoparts_hero', array(
        'title'    => __( 'Hero Section', 'autoparts-pro' ),
        'priority' => 40,
    ) );
    
    $wp_customize->add_setting( 'hero_animation_type', array(
        'default'           => 'exploded_view',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'hero_animation_type', array(
        'label'   => __( 'Hero Animation Type', 'autoparts-pro' ),
        'section' => 'autoparts_hero',
        'type'    => 'select',
        'choices' => array(
            'exploded_view' => 'Exploded View (3D)',
            'video'         => 'Video Background',
            'static'        => 'Static Image',
        ),
    ) );
    
    $wp_customize->add_setting( 'hero_video_url', array(
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( 'hero_video_url', array(
        'label'   => __( 'Hero Video URL', 'autoparts-pro' ),
        'section' => 'autoparts_hero',
    ) );
    
    $wp_customize->add_setting( 'hero_title', array(
        'default'           => __( 'Premium Auto Parts', 'autoparts-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'hero_title', array(
        'label'   => __( 'Hero Title', 'autoparts-pro' ),
        'section' => 'autoparts_hero',
    ) );
    
    $wp_customize->add_setting( 'hero_subtitle', array(
        'default'           => __( 'Engineered for Excellence', 'autoparts-pro' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'hero_subtitle', array(
        'label'   => __( 'Hero Subtitle', 'autoparts-pro' ),
        'section' => 'autoparts_hero',
    ) );
    
    // ==========================================
    // CHATBOT SETTINGS
    // ==========================================
    
    $wp_customize->add_section( 'autoparts_chatbot', array(
        'title'    => __( 'AI Chatbot', 'autoparts-pro' ),
        'priority' => 50,
    ) );
    
    $wp_customize->add_setting( 'chatbot_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ) );
    
    $wp_customize->add_control( 'chatbot_enabled', array(
        'label'   => __( 'Enable Chatbot', 'autoparts-pro' ),
        'section' => 'autoparts_chatbot',
        'type'    => 'checkbox',
    ) );
    
    $wp_customize->add_setting( 'chatbot_greeting', array(
        'default'           => __( "Hi! I'm your automotive assistant. How can I help you find parts today?", 'autoparts-pro' ),
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    
    $wp_customize->add_control( 'chatbot_greeting', array(
        'label'   => __( 'Greeting Message', 'autoparts-pro' ),
        'section' => 'autoparts_chatbot',
        'type'    => 'textarea',
    ) );
    
    $wp_customize->add_setting( 'chatbot_avatar', array(
        'sanitize_callback' => 'esc_url_raw',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'chatbot_avatar', array(
        'label'   => __( 'Chatbot Avatar', 'autoparts-pro' ),
        'section' => 'autoparts_chatbot',
    ) ) );
    
    // ==========================================
    // BACK TO TOP SETTINGS
    // ==========================================
    
    $wp_customize->add_section( 'autoparts_back_to_top', array(
        'title'    => __( 'Back to Top Button', 'autoparts-pro' ),
        'priority' => 55,
    ) );
    
    $wp_customize->add_setting( 'back_to_top_icon', array(
        'default'           => 'arrow',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'back_to_top_icon', array(
        'label'   => __( 'Icon Style', 'autoparts-pro' ),
        'section' => 'autoparts_back_to_top',
        'type'    => 'select',
        'choices' => array(
            'arrow'  => 'Arrow',
            'gear'   => 'Gear',
            'rocket' => 'Rocket',
        ),
    ) );
    
    // ==========================================
    // HEADER SETTINGS
    // ==========================================
    
    $wp_customize->add_section( 'autoparts_header', array(
        'title'    => __( 'Header Settings', 'autoparts-pro' ),
        'priority' => 25,
    ) );
    
    $wp_customize->add_setting( 'show_vehicle_selector', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ) );
    
    $wp_customize->add_control( 'show_vehicle_selector', array(
        'label'   => __( 'Show Vehicle Selector in Header', 'autoparts-pro' ),
        'section' => 'autoparts_header',
        'type'    => 'checkbox',
    ) );
    
    $wp_customize->add_setting( 'header_layout', array(
        'default'           => 'gear_shift',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'header_layout', array(
        'label'   => __( 'Header Layout', 'autoparts-pro' ),
        'section' => 'autoparts_header',
        'type'    => 'select',
        'choices' => array(
            'gear_shift' => 'Gear Shift Navigation',
            'standard'   => 'Standard',
            'minimal'    => 'Minimal',
        ),
    ) );
}
add_action( 'customize_register', 'autoparts_pro_customize_register' );

/**
 * Output custom CSS from customizer settings
 */
function autoparts_pro_customizer_css() {
    ?>
    <style type="text/css">
        :root {
            --color-primary-red: <?php echo esc_attr( get_theme_mod( 'color_primary_red', '#DC2626' ) ); ?>;
            --color-dark-black: <?php echo esc_attr( get_theme_mod( 'color_dark_black', '#0A0A0A' ) ); ?>;
            --color-accent-orange: <?php echo esc_attr( get_theme_mod( 'color_accent_orange', '#EA580C' ) ); ?>;
            --font-heading: <?php echo esc_attr( get_theme_mod( 'font_heading', 'Rajdhani' ) ); ?>;
            --font-body: <?php echo esc_attr( get_theme_mod( 'font_body', 'Inter' ) ); ?>;
        }
        
        .dark-mode {
            --bg-color: <?php echo esc_attr( get_theme_mod( 'dark_mode_bg', '#0A0A0A' ) ); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'autoparts_pro_customizer_css' );
