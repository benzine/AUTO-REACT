<?php
/**
 * Theme Options Panel for AutoParts Pro
 *
 * @package AutoParts_Pro
 */

namespace AutoPartsPro\Options;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add theme options page
 */
function add_options_page() {
    add_menu_page(
        __('AutoParts Pro Options', 'autoparts-pro'),
        __('AutoParts Pro', 'autoparts-pro'),
        'manage_options',
        'autoparts-pro-options',
        __NAMESPACE__ . '\\render_options_page',
        'dashicons-car',
        60
    );
}
add_action('admin_menu', __NAMESPACE__ . '\\add_options_page');

/**
 * Register settings
 */
function register_settings() {
    // General Settings
    register_setting('autoparts_general', 'autoparts_logo_light');
    register_setting('autoparts_general', 'autoparts_logo_dark');
    register_setting('autoparts_general', 'autoparts_sticky_logo');
    register_setting('autoparts_general', 'autoparts_mobile_logo');
    register_setting('autoparts_general', 'autoparts_site_tagline');
    register_setting('autoparts_general', 'autoparts_header_phone');
    register_setting('autoparts_general', 'autoparts_header_email');
    register_setting('autoparts_general', 'autoparts_social_links');
    
    // Hero Settings
    register_setting('autoparts_hero', 'autoparts_hero_type');
    register_setting('autoparts_hero', 'autoparts_hero_video');
    register_setting('autoparts_hero', 'autoparts_hero_image');
    register_setting('autoparts_hero', 'autoparts_hero_title');
    register_setting('autoparts_hero', 'autoparts_hero_subtitle');
    register_setting('autoparts_hero', 'autoparts_hero_button_text');
    register_setting('autoparts_hero', 'autoparts_hero_button_link');
    register_setting('autoparts_hero', 'autoparts_hero_explosion_speed');
    register_setting('autoparts_hero', 'autoparts_hero_enable');
    
    // Color Settings
    register_setting('autoparts_colors', 'autoparts_color_primary');
    register_setting('autoparts_colors', 'autoparts_color_dark');
    register_setting('autoparts_colors', 'autoparts_color_accent');
    register_setting('autoparts_colors', 'autoparts_light_bg');
    register_setting('autoparts_colors', 'autoparts_dark_bg');
    register_setting('autoparts_colors', 'autoparts_light_text');
    register_setting('autoparts_colors', 'autoparts_dark_text');
    
    // Typography
    register_setting('autoparts_typography', 'autoparts_font_heading');
    register_setting('autoparts_typography', 'autoparts_font_body');
    register_setting('autoparts_typography', 'autoparts_font_numbers');
    register_setting('autoparts_typography', 'autoparts_font_size_base');
    
    // Shop Settings
    register_setting('autoparts_shop', 'autoparts_products_per_page');
    register_setting('autoparts_shop', 'autoparts_products_columns');
    register_setting('autoparts_shop', 'autoparts_related_products_count');
    register_setting('autoparts_shop', 'autoparts_upsell_products_count');
    register_setting('autoparts_shop', 'autoparts_show_bulk_pricing');
    register_setting('autoparts_shop', 'autoparts_show_qr_code');
    
    // Chatbot Settings
    register_setting('autoparts_chatbot', 'autoparts_chatbot_enable');
    register_setting('autoparts_chatbot', 'autoparts_chatbot_auto_open');
    register_setting('autoparts_chatbot', 'autoparts_chatbot_delay');
    register_setting('autoparts_chatbot', 'autoparts_chatbot_avatar');
    register_setting('autoparts_chatbot', 'autoparts_chatbot_greeting');
    register_setting('autoparts_chatbot', 'autoparts_chatbot_color');
    
    // Footer Settings
    register_setting('autoparts_footer', 'autoparts_footer_text');
    register_setting('autoparts_footer', 'autoparts_footer_phone');
    register_setting('autoparts_footer', 'autoparts_footer_email');
    register_setting('autoparts_footer', 'autoparts_footer_address');
    register_setting('autoparts_footer', 'autoparts_footer_map_api_key');
    register_setting('autoparts_footer', 'autoparts_show_back_to_top');
}
add_action('admin_init', __NAMESPACE__ . '\\register_settings');

/**
 * Render options page
 */
function render_options_page() {
    ?>
    <div class="wrap autoparts-options-wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <div class="autoparts-options-nav">
            <button class="nav-tab nav-tab-active" data-tab="general"><?php _e('General', 'autoparts-pro'); ?></button>
            <button class="nav-tab" data-tab="hero"><?php _e('Hero Section', 'autoparts-pro'); ?></button>
            <button class="nav-tab" data-tab="colors"><?php _e('Colors', 'autoparts-pro'); ?></button>
            <button class="nav-tab" data-tab="typography"><?php _e('Typography', 'autoparts-pro'); ?></button>
            <button class="nav-tab" data-tab="shop"><?php _e('Shop', 'autoparts-pro'); ?></button>
            <button class="nav-tab" data-tab="chatbot"><?php _e('Chatbot', 'autoparts-pro'); ?></button>
            <button class="nav-tab" data-tab="footer"><?php _e('Footer', 'autoparts-pro'); ?></button>
        </div>
        
        <form method="post" action="options.php" class="autoparts-options-form">
            <?php settings_fields('autoparts_general'); ?>
            
            <!-- General Settings -->
            <div class="autoparts-options-tab" id="tab-general">
                <h2><?php _e('General Settings', 'autoparts-pro'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Logo (Light Mode)', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="text" name="autoparts_logo_light" value="<?php echo esc_attr(get_option('autoparts_logo_light')); ?>" class="regular-text">
                            <button class="button autoparts-upload-btn"><?php _e('Upload', 'autoparts-pro'); ?></button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Logo (Dark Mode)', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="text" name="autoparts_logo_dark" value="<?php echo esc_attr(get_option('autoparts_logo_dark')); ?>" class="regular-text">
                            <button class="button autoparts-upload-btn"><?php _e('Upload', 'autoparts-pro'); ?></button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Header Phone', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="text" name="autoparts_header_phone" value="<?php echo esc_attr(get_option('autoparts_header_phone')); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Header Email', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="email" name="autoparts_header_email" value="<?php echo esc_attr(get_option('autoparts_header_email')); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Hero Settings -->
            <div class="autoparts-options-tab" id="tab-hero" style="display:none;">
                <h2><?php _e('Hero Section Settings', 'autoparts-pro'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Enable Hero', 'autoparts-pro'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="autoparts_hero_enable" value="1" <?php checked(get_option('autoparts_hero_enable', true)); ?>>
                                <?php _e('Enable exploded view hero animation', 'autoparts-pro'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Hero Type', 'autoparts-pro'); ?></th>
                        <td>
                            <select name="autoparts_hero_type">
                                <option value="video" <?php selected(get_option('autoparts_hero_type'), 'video'); ?>><?php _e('Video', 'autoparts-pro'); ?></option>
                                <option value="3d" <?php selected(get_option('autoparts_hero_type'), '3d'); ?>><?php _e('3D Model (Three.js)', 'autoparts-pro'); ?></option>
                                <option value="image" <?php selected(get_option('autoparts_hero_type'), 'image'); ?>><?php _e('Static Image', 'autoparts-pro'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Hero Video URL', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="text" name="autoparts_hero_video" value="<?php echo esc_attr(get_option('autoparts_hero_video')); ?>" class="regular-text">
                            <p class="description"><?php _e('MP4 video URL for the hero animation', 'autoparts-pro'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Hero Title', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="text" name="autoparts_hero_title" value="<?php echo esc_attr(get_option('autoparts_hero_title', __('Premium Auto Parts', 'autoparts-pro'))); ?>" class="large-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Explosion Speed', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="range" name="autoparts_hero_explosion_speed" min="0.5" max="3" step="0.1" value="<?php echo esc_attr(get_option('autoparts_hero_explosion_speed', 1)); ?>">
                            <span class="description"><?php echo esc_html(get_option('autoparts_hero_explosion_speed', 1)); ?>x</span>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Colors Settings -->
            <div class="autoparts-options-tab" id="tab-colors" style="display:none;">
                <h2><?php _e('Color Settings', 'autoparts-pro'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Primary Color (Racing Red)', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="color" name="autoparts_color_primary" value="<?php echo esc_attr(get_option('autoparts_color_primary', '#DC2626')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Dark Background', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="color" name="autoparts_color_dark" value="<?php echo esc_attr(get_option('autoparts_color_dark', '#0A0A0A')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Accent Color (Burnt Orange)', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="color" name="autoparts_color_accent" value="<?php echo esc_attr(get_option('autoparts_color_accent', '#EA580C')); ?>">
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Typography Settings -->
            <div class="autoparts-options-tab" id="tab-typography" style="display:none;">
                <h2><?php _e('Typography Settings', 'autoparts-pro'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Heading Font', 'autoparts-pro'); ?></th>
                        <td>
                            <select name="autoparts_font_heading">
                                <option value="Rajdhani" <?php selected(get_option('autoparts_font_heading'), 'Rajdhani'); ?>>Rajdhani</option>
                                <option value="Oswald" <?php selected(get_option('autoparts_font_heading'), 'Oswald'); ?>>Oswald</option>
                                <option value="Bebas Neue" <?php selected(get_option('autoparts_font_heading'), 'Bebas Neue'); ?>>Bebas Neue</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Body Font', 'autoparts-pro'); ?></th>
                        <td>
                            <select name="autoparts_font_body">
                                <option value="Inter" <?php selected(get_option('autoparts_font_body'), 'Inter'); ?>>Inter</option>
                                <option value="Roboto" <?php selected(get_option('autoparts_font_body'), 'Roboto'); ?>>Roboto</option>
                                <option value="Open Sans" <?php selected(get_option('autoparts_font_body'), 'Open Sans'); ?>>Open Sans</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Shop Settings -->
            <div class="autoparts-options-tab" id="tab-shop" style="display:none;">
                <h2><?php _e('Shop Settings', 'autoparts-pro'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Products Per Page', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="number" name="autoparts_products_per_page" value="<?php echo esc_attr(get_option('autoparts_products_per_page', 12)); ?>" min="4" max="48">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Products Columns', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="number" name="autoparts_products_columns" value="<?php echo esc_attr(get_option('autoparts_products_columns', 4)); ?>" min="2" max="6">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Show Bulk Pricing', 'autoparts-pro'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="autoparts_show_bulk_pricing" value="1" <?php checked(get_option('autoparts_show_bulk_pricing', true)); ?>>
                                <?php _e('Display bulk pricing tiers on products', 'autoparts-pro'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Show QR Code', 'autoparts-pro'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="autoparts_show_qr_code" value="1" <?php checked(get_option('autoparts_show_qr_code', true)); ?>>
                                <?php _e('Display QR code on product pages', 'autoparts-pro'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Chatbot Settings -->
            <div class="autoparts-options-tab" id="tab-chatbot" style="display:none;">
                <h2><?php _e('Chatbot Settings', 'autoparts-pro'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Enable Chatbot', 'autoparts-pro'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="autoparts_chatbot_enable" value="1" <?php checked(get_option('autoparts_chatbot_enable', true)); ?>>
                                <?php _e('Enable AI chatbot widget', 'autoparts-pro'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Auto-Open Chatbot', 'autoparts-pro'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="autoparts_chatbot_auto_open" value="1" <?php checked(get_option('autoparts_chatbot_auto_open', true)); ?>>
                                <?php _e('Automatically open chatbot after delay', 'autoparts-pro'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Auto-Open Delay (seconds)', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="number" name="autoparts_chatbot_delay" value="<?php echo esc_attr(get_option('autoparts_chatbot_delay', 3)); ?>" min="1" max="30">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Greeting Message', 'autoparts-pro'); ?></th>
                        <td>
                            <textarea name="autoparts_chatbot_greeting" rows="3" class="large-text"><?php echo esc_textarea(get_option('autoparts_chatbot_greeting', __('Hi! Need help finding the right part for your vehicle?', 'autoparts-pro'))); ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Footer Settings -->
            <div class="autoparts-options-tab" id="tab-footer" style="display:none;">
                <h2><?php _e('Footer Settings', 'autoparts-pro'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Footer Text', 'autoparts-pro'); ?></th>
                        <td>
                            <textarea name="autoparts_footer_text" rows="3" class="large-text"><?php echo esc_textarea(get_option('autoparts_footer_text', __('© 2024 AutoParts Pro. All rights reserved.', 'autoparts-pro'))); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Footer Address', 'autoparts-pro'); ?></th>
                        <td>
                            <textarea name="autoparts_footer_address" rows="3" class="large-text"><?php echo esc_textarea(get_option('autoparts_footer_address')); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Google Maps API Key', 'autoparts-pro'); ?></th>
                        <td>
                            <input type="text" name="autoparts_footer_map_api_key" value="<?php echo esc_attr(get_option('autoparts_footer_map_api_key')); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Show Back to Top', 'autoparts-pro'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="autoparts_show_back_to_top" value="1" <?php checked(get_option('autoparts_show_back_to_top', true)); ?>>
                                <?php _e('Display back-to-top button', 'autoparts-pro'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            
            <?php submit_button(__('Save Settings', 'autoparts-pro')); ?>
        </form>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        $('.autoparts-options-nav .nav-tab').on('click', function() {
            var tab = $(this).data('tab');
            
            $('.autoparts-options-nav .nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            $('.autoparts-options-tab').hide();
            $('#tab-' + tab).show();
        });
        
        $('.autoparts-upload-btn').on('click', function(e) {
            e.preventDefault();
            var $input = $(this).prev('input');
            var mediaUploader = wp.media({
                title: '<?php _e('Choose Image', 'autoparts-pro'); ?>',
                button: { text: '<?php _e('Use this image', 'autoparts-pro'); ?>' },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $input.val(attachment.url);
            });
            
            mediaUploader.open();
        });
    });
    </script>
    
    <style>
    .autoparts-options-wrap { max-width: 900px; }
    .autoparts-options-nav { margin-bottom: 20px; border-bottom: 1px solid #ccc; }
    .autoparts-options-nav .nav-tab { cursor: pointer; margin-bottom: -1px; }
    .autoparts-options-form table.form-table th { width: 200px; }
    .autoparts-options-tab { background: #fff; padding: 20px; border: 1px solid #ccd0d4; }
    </style>
    <?php
}
