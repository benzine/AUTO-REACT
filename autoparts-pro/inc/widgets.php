<?php
/**
 * Custom Widgets for AutoParts Pro
 *
 * @package AutoParts_Pro
 */

namespace AutoPartsPro\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Product Categories Widget
 */
class Product_Categories_Widget extends \WP_Widget {

    public function __construct() {
        parent::__construct(
            'autoparts_product_categories',
            __('AutoParts: Product Categories', 'autoparts-pro'),
            array(
                'description' => __('Display product categories with images', 'autoparts-pro'),
                'classname' => 'autoparts-widget-categories'
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Categories', 'autoparts-pro');
        $count = !empty($instance['count']) ? absint($instance['count']) : 6;
        $show_counts = !empty($instance['show_counts']) ? true : false;

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'number' => $count,
            'hide_empty' => true,
            'parent' => 0
        ));

        if (!is_wp_error($categories) && !empty($categories)) {
            echo '<ul class="autoparts-category-list">';
            foreach ($categories as $category) {
                $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                $image = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
                
                echo '<li class="autoparts-category-item">';
                echo '<a href="' . esc_url(get_term_link($category)) . '" class="autoparts-category-link">';
                
                if ($image) {
                    echo '<div class="autoparts-category-image">';
                    echo '<img src="' . esc_url($image) . '" alt="' . esc_attr($category->name) . '">';
                    echo '</div>';
                }
                
                echo '<span class="autoparts-category-name">' . esc_html($category->name) . '</span>';
                
                if ($show_counts && $category->count > 0) {
                    echo '<span class="autoparts-category-count">(' . $category->count . ')</span>';
                }
                
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $count = !empty($instance['count']) ? absint($instance['count']) : 6;
        $show_counts = !empty($instance['show_counts']) ? true : false;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'autoparts-pro'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('count')); ?>"><?php _e('Number of categories:', 'autoparts-pro'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('count')); ?>" name="<?php echo esc_attr($this->get_field_name('count')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($count); ?>" size="3">
        </p>
        <p>
            <input class="checkbox" id="<?php echo esc_attr($this->get_field_id('show_counts')); ?>" name="<?php echo esc_attr($this->get_field_name('show_counts')); ?>" type="checkbox" <?php checked($show_counts); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_counts')); ?>"><?php _e('Show product counts', 'autoparts-pro'); ?></label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['count'] = absint($new_instance['count']);
        $instance['show_counts'] = isset($new_instance['show_counts']) ? true : false;
        return $instance;
    }
}

/**
 * Featured Products Widget
 */
class Featured_Products_Widget extends \WP_Widget {

    public function __construct() {
        parent::__construct(
            'autoparts_featured_products',
            __('AutoParts: Featured Products', 'autoparts-pro'),
            array(
                'description' => __('Display featured products', 'autoparts-pro'),
                'classname' => 'autoparts-widget-featured'
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Featured Products', 'autoparts-pro');
        $count = !empty($instance['count']) ? absint($instance['count']) : 4;

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $products = wc_get_products(array(
            'limit' => $count,
            'featured' => true,
            'visibility' => 'visible',
            'status' => 'publish'
        ));

        if (!empty($products)) {
            echo '<div class="autoparts-featured-products-grid">';
            foreach ($products as $product) {
                wc_get_template_part('content', 'product');
            }
            echo '</div>';
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $count = !empty($instance['count']) ? absint($instance['count']) : 4;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'autoparts-pro'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('count')); ?>"><?php _e('Number of products:', 'autoparts-pro'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('count')); ?>" name="<?php echo esc_attr($this->get_field_name('count')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($count); ?>" size="3">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['count'] = absint($new_instance['count']);
        return $instance;
    }
}

/**
 * Vehicle Compatibility Widget
 */
class Vehicle_Compatibility_Widget extends \WP_Widget {

    public function __construct() {
        parent::__construct(
            'autoparts_vehicle_selector',
            __('AutoParts: Vehicle Selector', 'autoparts-pro'),
            array(
                'description' => __('Vehicle year/make/model selector', 'autoparts-pro'),
                'classname' => 'autoparts-widget-vehicle'
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Find Parts for Your Vehicle', 'autoparts-pro');
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('Check Compatibility', 'autoparts-pro');

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        ?>
        <div class="autoparts-vehicle-selector-widget">
            <form class="autoparts-vehicle-form" data-widget="true">
                <div class="autoparts-form-group">
                    <select name="year" class="autoparts-select" required>
                        <option value=""><?php _e('Select Year', 'autoparts-pro'); ?></option>
                        <?php
                        $current_year = date('Y');
                        for ($i = $current_year; $i >= $current_year - 25; $i--) {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="autoparts-form-group">
                    <select name="make" class="autoparts-select" required disabled>
                        <option value=""><?php _e('Select Make', 'autoparts-pro'); ?></option>
                    </select>
                </div>
                <div class="autoparts-form-group">
                    <select name="model" class="autoparts-select" required disabled>
                        <option value=""><?php _e('Select Model', 'autoparts-pro'); ?></option>
                    </select>
                </div>
                <button type="submit" class="autoparts-btn autoparts-btn-primary autoparts-btn-full">
                    <?php echo esc_html($button_text); ?>
                </button>
            </form>
            <div class="autoparts-vehicle-result"></div>
        </div>
        <?php

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('Check Compatibility', 'autoparts-pro');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'autoparts-pro'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php _e('Button Text:', 'autoparts-pro'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['button_text'] = sanitize_text_field($new_instance['button_text']);
        return $instance;
    }
}

/**
 * Brand Logos Widget
 */
class Brand_Logos_Widget extends \WP_Widget {

    public function __construct() {
        parent::__construct(
            'autoparts_brand_logos',
            __('AutoParts: Brand Logos', 'autoparts-pro'),
            array(
                'description' => __('Display brand/partner logos', 'autoparts-pro'),
                'classname' => 'autoparts-widget-brands'
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $brands = !empty($instance['brands']) ? $instance['brands'] : array();

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        if (!empty($brands)) {
            echo '<div class="autoparts-brand-logos">';
            foreach ($brands as $brand) {
                if (!empty($brand['image'])) {
                    echo '<div class="autoparts-brand-item">';
                    echo '<img src="' . esc_url($brand['image']) . '" alt="' . esc_attr($brand['name']) . '">';
                    echo '</div>';
                }
            }
            echo '</div>';
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $brands = !empty($instance['brands']) ? $instance['brands'] : array();
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'autoparts-pro'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p><strong><?php _e('Brands (JSON format):', 'autoparts-pro'); ?></strong></p>
        <textarea class="widefat" rows="8" id="<?php echo esc_attr($this->get_field_id('brands')); ?>" name="<?php echo esc_attr($this->get_field_name('brands')); ?>"><?php echo esc_textarea(json_encode($brands, JSON_PRETTY_PRINT)); ?></textarea>
        <small><?php _e('Format: [{"name":"Brand","image":"URL"}]', 'autoparts-pro'); ?></small>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['brands'] = json_decode($new_instance['brands'], true) ?: array();
        return $instance;
    }
}

/**
 * Register Widgets
 */
function register_widgets() {
    register_widget(__NAMESPACE__ . '\\Product_Categories_Widget');
    register_widget(__NAMESPACE__ . '\\Featured_Products_Widget');
    register_widget(__NAMESPACE__ . '\\Vehicle_Compatibility_Widget');
    register_widget(__NAMESPACE__ . '\\Brand_Logos_Widget');
}
add_action('widgets_init', __NAMESPACE__ . '\\register_widgets');
