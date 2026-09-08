<?php
/**
 * Single Product - Compatibility Tab Content
 * 
 * @package AutoParts_Pro
 */

defined('ABSPATH') || exit;

global $product;

$compatibility_data = get_post_meta($product->get_id(), '_autoparts_compatibility', true);
$compatible_vehicles = is_array($compatibility_data) && isset($compatibility_data['vehicles']) 
    ? $compatibility_data['vehicles'] 
    : array();
?>

<div class="compatibility-tab-content" id="compatibility-tab">
    <div class="compatibility-checker-widget">
        <h3><?php esc_html_e('Will This Fit Your Vehicle?', 'autoparts-pro'); ?></h3>
        
        <!-- Quick check with selected vehicle -->
        <div class="quick-compatibility-check">
            <?php
            $selected_vehicle = isset($_COOKIE['autoparts_selected_vehicle']) 
                ? json_decode(stripslashes($_COOKIE['autoparts_selected_vehicle']), true) 
                : null;
            
            if ($selected_vehicle) :
                $is_compatible = autoparts_pro_check_product_compatibility($product->get_id(), $selected_vehicle);
                ?>
                <div class="compatibility-result <?php echo $is_compatible ? 'compatible' : 'not-compatible'; ?>">
                    <i class="ap-icon-<?php echo $is_compatible ? 'check-circle' : 'x-circle'; ?>"></i>
                    <div class="result-message">
                        <strong><?php echo $is_compatible ? esc_html__('✓ Fits Your Vehicle', 'autoparts-pro') : esc_html__('✗ Does Not Fit', 'autoparts-pro'); ?></strong>
                        <p>
                            <?php 
                            echo esc_html(sprintf(
                                __('Compatible with your %d %s %s', 'autoparts-pro'),
                                $selected_vehicle['year'],
                                $selected_vehicle['make'],
                                $selected_vehicle['model']
                            )); 
                            ?>
                        </p>
                    </div>
                </div>
            <?php else : ?>
                <div class="no-vehicle-selected">
                    <i class="ap-icon-alert-circle"></i>
                    <p><?php esc_html_e('Select your vehicle from the garage to check compatibility', 'autoparts-pro'); ?></p>
                    <button class="button button-primary open-vehicle-selector">
                        <?php esc_html_e('Select Vehicle', 'autoparts-pro'); ?>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Full compatibility list -->
    <?php if (!empty($compatible_vehicles)) : ?>
        <div class="full-compatibility-list">
            <h4><?php esc_html_e('Compatible Vehicles', 'autoparts-pro'); ?></h4>
            
            <!-- Search/Filter -->
            <div class="compatibility-filter">
                <input type="text" 
                       class="compatibility-search" 
                       placeholder="<?php esc_attr_e('Search by year, make, or model...', 'autoparts-pro'); ?>" 
                       aria-label="<?php esc_attr_e('Search compatible vehicles', 'autoparts-pro'); ?>" />
                
                <select class="compatibility-year-filter" aria-label="<?php esc_attr_e('Filter by year', 'autoparts-pro'); ?>">
                    <option value=""><?php esc_html_e('All Years', 'autoparts-pro'); ?></option>
                    <?php
                    $years = array_unique(array_column($compatible_vehicles, 'year'));
                    sort($years, SORT_DESC);
                    foreach ($years as $year) {
                        echo '<option value="' . esc_attr($year) . '">' . esc_html($year) . '</option>';
                    }
                    ?>
                </select>
                
                <select class="compatibility-make-filter" aria-label="<?php esc_attr_e('Filter by make', 'autoparts-pro'); ?>">
                    <option value=""><?php esc_html_e('All Makes', 'autoparts-pro'); ?></option>
                    <?php
                    $makes = array_unique(array_column($compatible_vehicles, 'make'));
                    sort($makes);
                    foreach ($makes as $make) {
                        echo '<option value="' . esc_attr($make) . '">' . esc_html($make) . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <!-- Compatibility table -->
            <table class="compatibility-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Year', 'autoparts-pro'); ?></th>
                        <th><?php esc_html_e('Make', 'autoparts-pro'); ?></th>
                        <th><?php esc_html_e('Model', 'autoparts-pro'); ?></th>
                        <th><?php esc_html_e('Engine', 'autoparts-pro'); ?></th>
                        <th><?php esc_html_e('Notes', 'autoparts-pro'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($compatible_vehicles as $vehicle) : ?>
                        <tr class="compatibility-row" 
                            data-year="<?php echo esc_attr($vehicle['year']); ?>"
                            data-make="<?php echo esc_attr($vehicle['make']); ?>"
                            data-model="<?php echo esc_attr($vehicle['model']); ?>">
                            <td><?php echo esc_html($vehicle['year']); ?></td>
                            <td><?php echo esc_html($vehicle['make']); ?></td>
                            <td><?php echo esc_html($vehicle['model']); ?></td>
                            <td><?php echo esc_html($vehicle['engine'] ?? __('All Engines', 'autoparts-pro')); ?></td>
                            <td><?php echo esc_html($vehicle['notes'] ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination if many results -->
            <?php if (count($compatible_vehicles) > 20) : ?>
                <div class="compatibility-pagination">
                    <button class="button load-more-compatibility">
                        <?php esc_html_e('Load More', 'autoparts-pro'); ?>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <div class="no-compatibility-info">
            <i class="ap-icon-info"></i>
            <p><?php esc_html_e('No specific vehicle compatibility information available. Please use the compatibility checker above or contact us to verify fitment.', 'autoparts-pro'); ?></p>
        </div>
    <?php endif; ?>

    <!-- OEM Cross Reference -->
    <?php
    $oem_numbers = get_post_meta($product->get_id(), '_autoparts_oem_numbers', true);
    if (!empty($oem_numbers)) :
        ?>
        <div class="oem-cross-reference">
            <h4><?php esc_html_e('OEM Part Numbers', 'autoparts-pro'); ?></h4>
            <ul class="oem-numbers-list">
                <?php foreach ((array)$oem_numbers as $oem_number) : ?>
                    <li class="oem-number-item">
                        <span class="oem-label"><?php esc_html_e('OEM:', 'autoparts-pro'); ?></span>
                        <code><?php echo esc_html($oem_number); ?></code>
                        <button class="copy-oem-btn" data-oem="<?php echo esc_attr($oem_number); ?>" title="<?php esc_attr_e('Copy to clipboard', 'autoparts-pro'); ?>">
                            <i class="ap-icon-copy"></i>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Installation Notes -->
    <?php
    $installation_notes = get_post_meta($product->get_id(), '_autoparts_installation_notes', true);
    if (!empty($installation_notes)) :
        ?>
        <div class="installation-notes">
            <h4><?php esc_html_e('Installation Notes', 'autoparts-pro'); ?></h4>
            <div class="notes-content">
                <?php echo wp_kses_post($installation_notes); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    // Filter compatibility table
    $('.compatibility-search, .compatibility-year-filter, .compatibility-make-filter').on('change keyup', function() {
        var searchText = $('.compatibility-search').val().toLowerCase();
        var yearFilter = $('.compatibility-year-filter').val();
        var makeFilter = $('.compatibility-make-filter').val();
        
        $('.compatibility-row').each(function() {
            var $row = $(this);
            var year = $row.data('year');
            var make = $row.data('make').toLowerCase();
            var model = $row.data('model').toLowerCase();
            
            var matchesSearch = !searchText || 
                year.toString().includes(searchText) ||
                make.includes(searchText) ||
                model.includes(searchText);
            
            var matchesYear = !yearFilter || year == yearFilter;
            var matchesMake = !makeFilter || make === makeFilter.toLowerCase();
            
            if (matchesSearch && matchesYear && matchesMake) {
                $row.show();
            } else {
                $row.hide();
            }
        });
    });
    
    // Copy OEM number
    $('.copy-oem-btn').on('click', function() {
        var oemNumber = $(this).data('oem');
        navigator.clipboard.writeText(oemNumber).then(function() {
            var $btn = $(this);
            $btn.html('<i class="ap-icon-check"></i>');
            setTimeout(function() {
                $btn.html('<i class="ap-icon-copy"></i>');
            }, 2000);
        }.bind(this));
    });
    
    // Open vehicle selector
    $('.open-vehicle-selector').on('click', function() {
        $('#global-vehicle-selector .vehicle-selector-toggle').trigger('click');
    });
});
</script>
