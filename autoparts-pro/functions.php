/**
 * AutoParts Pro Theme
 *
 * @package AutoParts_Pro
 * @author Automotive Themes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'AUTOPARTS_PRO_VERSION', '1.0.0' );
define( 'AUTOPARTS_PRO_DIR', get_template_directory() );
define( 'AUTOPARTS_PRO_URI', get_template_directory_uri() );

/**
 * Core theme setup
 */
function autoparts_pro_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );

    // Register navigation menus
    register_nav_menus( array(
        'primary'   => esc_html__( 'Primary Menu (Gear Shift)', 'autoparts-pro' ),
        'secondary' => esc_html__( 'Secondary Menu', 'autoparts-pro' ),
        'mobile'    => esc_html__( 'Mobile Menu', 'autoparts-pro' ),
        'footer'    => esc_html__( 'Footer Menu', 'autoparts-pro' ),
    ) );

    // Switch default core markup for various elements to HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Add support for core custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Add support for custom background
    add_theme_support( 'custom-background' );

    // Add support for custom header
    add_theme_support( 'custom-header', array(
        'default-image'      => '',
        'width'              => 1920,
        'height'             => 400,
        'flex-height'        => true,
        'flex-width'         => true,
    ) );

    // Add support for WooCommerce
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Add support for Gutenberg
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );

    // Add custom image sizes
    add_image_size( 'autoparts-hero', 1920, 800, true );
    add_image_size( 'autoparts-product-card', 400, 400, true );
    add_image_size( 'autoparts-category', 600, 400, true );
}
add_action( 'after_setup_theme', 'autoparts_pro_setup' );

/**
 * Set the content width in pixels
 */
function autoparts_pro_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'autoparts_pro_content_width', 1200 );
}
add_action( 'after_setup_theme', 'autoparts_pro_content_width', 0 );

/**
 * Register widget areas
 */
function autoparts_pro_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Main Sidebar', 'autoparts-pro' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'autoparts-pro' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Shop Sidebar', 'autoparts-pro' ),
        'id'            => 'shop-sidebar',
        'description'   => esc_html__( 'Add widgets here to appear in your shop sidebar.', 'autoparts-pro' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 1', 'autoparts-pro' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'First footer widget area.', 'autoparts-pro' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 2', 'autoparts-pro' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Second footer widget area.', 'autoparts-pro' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 3', 'autoparts-pro' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Third footer widget area.', 'autoparts-pro' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 4', 'autoparts-pro' ),
        'id'            => 'footer-4',
        'description'   => esc_html__( 'Fourth footer widget area.', 'autoparts-pro' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'autoparts_pro_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function autoparts_pro_scripts() {
    // Main stylesheet
    wp_enqueue_style( 'autoparts-pro-style', get_stylesheet_uri(), array(), AUTOPARTS_PRO_VERSION );

    // Google Fonts
    wp_enqueue_style( 'autoparts-pro-fonts', 'https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap', array(), null );

    // GSAP for animations
    wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), '3.12.2', true );
    wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array('gsap'), '3.12.2', true );

    // Three.js for 3D effects
    wp_enqueue_script( 'threejs', 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js', array(), 'r128', true );

    // Main theme JavaScript
    wp_enqueue_script( 'autoparts-pro-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'gsap', 'gsap-scrolltrigger'), AUTOPARTS_PRO_VERSION, true );

    // Comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    // Localize script with AJAX URL
    wp_localize_script( 'autoparts-pro-main', 'autopartsPro', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'autoparts-pro-nonce' ),
        'isRTL'   => is_rtl(),
    ) );
}
add_action( 'wp_enqueue_scripts', 'autoparts_pro_scripts' );

/**
 * Custom template tags
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom widgets
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * WooCommerce compatibility
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 * Theme options panel
 */
require get_template_directory() . '/inc/theme-options.php';

/**
 * Custom post types
 */
require get_template_directory() . '/inc/post-types.php';

/**
 * AJAX handlers
 */
require get_template_directory() . '/inc/ajax-handlers.php';
