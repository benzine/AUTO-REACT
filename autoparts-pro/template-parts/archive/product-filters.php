<?php
/**
 * Archive Product Filters Component
 * 
 * @package AutoParts_Pro
 */

defined('ABSPATH') || exit;
?>

<div class="autoparts-product-filters" id="product-filters">
    <div class="filters-header">
        <h3><?php esc_html_e('Filter Products', 'autoparts-pro'); ?></h3>
        <button class="filters-toggle-mobile" aria-expanded="false">
            <i class="ap-icon-sliders"></i>
            <?php esc_html_e('Filters', 'autoparts-pro'); ?>
        </button>
        <button class="clear-all-filters" style="display: none;">
            <i class="ap-icon-x"></i>
            <?php esc_html_e('Clear All', 'autoparts-pro'); ?>
        </button>
    </div>

    <div class="filters-body">
        <!-- Compatibility Filter (Vehicle Based) -->
        <div class="filter-section compatibility-filter-section">
            <button class="filter-section-toggle" aria-expanded="true">
                <span class="section-title">
                    <i class="ap-icon-car"></i>
                    <?php esc_html_e('Compatibility', 'autoparts-pro'); ?>
                </span>
                <i class="ap-icon-chevron-down"></i>
            </button>
            <div class="filter-section-content">
                <div class="compatibility-status">
                    <?php
                    $selected_vehicle = isset($_COOKIE['autoparts_selected_vehicle']) 
                        ? json_decode(stripslashes($_COOKIE['autoparts_selected_vehicle']), true) 
                        : null;
                    
                    if ($selected_vehicle) :
                        ?>
                        <div class="vehicle-selected-badge">
                            <i class="ap-icon-check-circle"></i>
                            <span><?php echo esc_html(sprintf(__('Fits: %d %s %s', 'autoparts-pro'), $selected_vehicle['year'], $selected_vehicle['make'], $selected_vehicle['model'])); ?></span>
                        </div>
                        <label class="checkbox-label">
                            <input type="checkbox" name="compatible_only" value="1" checked />
                            <span><?php esc_html_e('Show only compatible parts', 'autoparts-pro'); ?></span>
                        </label>
                    <?php else : ?>
                        <p class="no-vehicle-message">
                            <i class="ap-icon-alert-circle"></i>
                            <?php esc_html_e('Select your vehicle to filter compatible parts', 'autoparts-pro'); ?>
                        </p>
                        <button class="button button-small button-primary open-vehicle-selector-btn">
                            <?php esc_html_e('Select Vehicle', 'autoparts-pro'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Category Filter -->
        <div class="filter-section category-filter-section">
            <button class="filter-section-toggle" aria-expanded="true">
                <span class="section-title">
                    <i class="ap-icon-folder"></i>
                    <?php esc_html_e('Category', 'autoparts-pro'); ?>
                </span>
                <i class="ap-icon-chevron-down"></i>
            </button>
            <div class="filter-section-content">
                <?php
                $current_category = get_query_var('product_cat');
                $categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                    'parent' => 0,
                ));
                
                if (!empty($categories)) :
                    ?>
                    <ul class="category-filter-list">
                        <li>
                            <label class="checkbox-label">
                                <input type="checkbox" name="category" value="" <?php checked(empty($current_category)); ?> />
                                <span><?php esc_html_e('All Categories', 'autoparts-pro'); ?></span>
                            </label>
                        </li>
                        <?php foreach ($categories as $category) : ?>
                            <li>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="category" value="<?php echo esc_attr($category->slug); ?>" <?php checked($current_category === $category->slug); ?> />
                                    <span><?php echo esc_html($category->name); ?></span>
                                    <span class="count">(<?php echo esc_html($category->count); ?>)</span>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Price Range Filter -->
        <div class="filter-section price-filter-section">
            <button class="filter-section-toggle" aria-expanded="true">
                <span class="section-title">
                    <i class="ap-icon-dollar-sign"></i>
                    <?php esc_html_e('Price Range', 'autoparts-pro'); ?>
                </span>
                <i class="ap-icon-chevron-down"></i>
            </button>
            <div class="filter-section-content">
                <div class="price-range-slider">
                    <div class="price-slider-track">
                        <div class="price-slider-range"></div>
                        <div class="price-slider-handle price-slider-handle-min"></div>
                        <div class="price-slider-handle price-slider-handle-max"></div>
                    </div>
                    <div class="price-inputs">
                        <div class="price-input-group">
                            <label for="min-price"><?php esc_html_e('Min', 'autoparts-pro'); ?></label>
                            <input type="text" id="min-price" name="min_price" value="0" />
                        </div>
                        <div class="price-input-group">
                            <label for="max-price"><?php esc_html_e('Max', 'autoparts-pro'); ?></label>
                            <input type="text" id="max-price" name="max_price" value="1000" />
                        </div>
                    </div>
                    <button class="button button-small apply-price-filter">
                        <?php esc_html_e('Apply', 'autoparts-pro'); ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- Brand Filter -->
        <div class="filter-section brand-filter-section">
            <button class="filter-section-toggle" aria-expanded="false">
                <span class="section-title">
                    <i class="ap-icon-tag"></i>
                    <?php esc_html_e('Brand', 'autoparts-pro'); ?>
                </span>
                <i class="ap-icon-chevron-down"></i>
            </button>
            <div class="filter-section-content">
                <?php
                $brands = get_terms(array(
                    'taxonomy' => 'product_brand',
                    'hide_empty' => true,
                ));
                
                if (!empty($brands)) :
                    ?>
                    <div class="brand-search">
                        <input type="text" class="brand-filter-search" placeholder="<?php esc_attr_e('Search brands...', 'autoparts-pro'); ?>" />
                    </div>
                    <ul class="brand-filter-list">
                        <?php foreach ($brands as $brand) : ?>
                            <li>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="brand" value="<?php echo esc_attr($brand->slug); ?>" />
                                    <span><?php echo esc_html($brand->name); ?></span>
                                    <span class="count">(<?php echo esc_html($brand->count); ?>)</span>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Rating Filter -->
        <div class="filter-section rating-filter-section">
            <button class="filter-section-toggle" aria-expanded="false">
                <span class="section-title">
                    <i class="ap-icon-star"></i>
                    <?php esc_html_e('Rating', 'autoparts-pro'); ?>
                </span>
                <i class="ap-icon-chevron-down"></i>
            </button>
            <div class="filter-section-content">
                <ul class="rating-filter-list">
                    <?php for ($i = 5; $i >= 1; $i--) : ?>
                        <li>
                            <label class="checkbox-label">
                                <input type="radio" name="rating" value="<?php echo esc_attr($i); ?>" />
                                <span class="rating-stars">
                                    <?php for ($j = 1; $j <= 5; $j++) : ?>
                                        <i class="ap-icon-star<?php echo $j <= $i ? ' filled' : ''; ?>"></i>
                                    <?php endfor; ?>
                                </span>
                                <span class="rating-text">& Up</span>
                            </label>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>

        <!-- Stock Status Filter -->
        <div class="filter-section stock-filter-section">
            <button class="filter-section-toggle" aria-expanded="false">
                <span class="section-title">
                    <i class="ap-icon-package"></i>
                    <?php esc_html_e('Availability', 'autoparts-pro'); ?>
                </span>
                <i class="ap-icon-chevron-down"></i>
            </button>
            <div class="filter-section-content">
                <label class="checkbox-label">
                    <input type="checkbox" name="in_stock" value="1" />
                    <span><?php esc_html_e('In Stock Only', 'autoparts-pro'); ?></span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="on_sale" value="1" />
                    <span><?php esc_html_e('On Sale', 'autoparts-pro'); ?></span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="oem_only" value="1" />
                    <span><?php esc_html_e('OEM Parts Only', 'autoparts-pro'); ?></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Active Filters Display -->
    <div class="active-filters-display" style="display: none;">
        <h4><?php esc_html_e('Active Filters:', 'autoparts-pro'); ?></h4>
        <div class="active-filters-list"></div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Toggle filter sections
    $('.filter-section-toggle').on('click', function() {
        var $section = $(this).closest('.filter-section');
        var $content = $section.find('.filter-section-content');
        var isExpanded = $(this).attr('aria-expanded') === 'true';
        
        $(this).attr('aria-expanded', !isExpanded);
        $content.slideToggle(200);
    });
    
    // Mobile filters toggle
    $('.filters-toggle-mobile').on('click', function() {
        var $filters = $('#product-filters');
        var isExpanded = $(this).attr('aria-expanded') === 'true';
        
        $(this).attr('aria-expanded', !isExpanded);
        $filters.toggleClass('filters-open');
    });
    
    // Brand search filter
    $('.brand-filter-search').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        
        $('.brand-filter-list li').each(function() {
            var brandName = $(this).find('span').first().text().toLowerCase();
            if (brandName.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    // Update active filters display
    function updateActiveFilters() {
        var $activeFiltersList = $('.active-filters-list');
        $activeFiltersList.empty();
        
        var hasFilters = false;
        
        // Check all checked filters
        $('#product-filters input:checked').each(function() {
            var $input = $(this);
            var label = $input.closest('label').find('span').first().text();
            var value = $input.val();
            
            if (value !== '' && label) {
                hasFilters = true;
                $activeFiltersList.append(
                    '<span class="active-filter-tag">' +
                    '<span>' + label + '</span>' +
                    '<button class="remove-filter" data-input="' + $input.attr('name') + '" data-value="' + value + '">' +
                    '<i class="ap-icon-x"></i>' +
                    '</button>' +
                    '</span>'
                );
            }
        });
        
        if (hasFilters) {
            $('.active-filters-display').show();
            $('.clear-all-filters').show();
        } else {
            $('.active-filters-display').hide();
            $('.clear-all-filters').hide();
        }
    }
    
    // Listen for filter changes
    $('#product-filters').on('change', 'input[type="checkbox"], input[type="radio"]', function() {
        updateActiveFilters();
        // Trigger filter apply (AJAX or page reload)
        applyFilters();
    });
    
    // Remove individual filter
    $(document).on('click', '.remove-filter', function() {
        var name = $(this).data('input');
        var value = $(this).data('value');
        
        $('#product-filters input[name="' + name + '"][value="' + value + '"]').prop('checked', false);
        updateActiveFilters();
        applyFilters();
    });
    
    // Clear all filters
    $('.clear-all-filters').on('click', function() {
        $('#product-filters input[type="checkbox"], #product-filters input[type="radio"]').prop('checked', false);
        updateActiveFilters();
        applyFilters();
    });
    
    // Open vehicle selector
    $('.open-vehicle-selector-btn').on('click', function() {
        $('#global-vehicle-selector .vehicle-selector-toggle').trigger('click');
    });
    
    function applyFilters() {
        // Collect all filter values
        var filters = {
            category: [],
            brand: [],
            min_price: $('#min-price').val(),
            max_price: $('#max-price').val(),
            rating: $('input[name="rating"]:checked').val(),
            in_stock: $('input[name="in_stock"]').is(':checked'),
            on_sale: $('input[name="on_sale"]').is(':checked'),
            compatible_only: $('input[name="compatible_only"]').is(':checked')
        };
        
        $('input[name="category"]:checked').each(function() {
            filters.category.push($(this).val());
        });
        
        $('input[name="brand"]:checked').each(function() {
            filters.brand.push($(this).val());
        });
        
        // AJAX filter or URL redirect
        console.log('Applying filters:', filters);
        // Implement AJAX filtering or URL parameter update here
    }
});
</script>
