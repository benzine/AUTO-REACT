<?php
/**
 * Vehicle Selector Component (Global)
 * 
 * @package AutoParts_Pro
 */

defined('ABSPATH') || exit;

// Get saved vehicles for current user
$user_id = get_current_user_id();
$saved_vehicles = $user_id ? get_user_meta($user_id, '_autoparts_garage_vehicles', true) : array();
$saved_vehicles = is_array($saved_vehicles) ? $saved_vehicles : array();

// Get currently selected vehicle from session/cookie
$selected_vehicle = isset($_COOKIE['autoparts_selected_vehicle']) 
    ? json_decode(stripslashes($_COOKIE['autoparts_selected_vehicle']), true) 
    : null;
?>

<div class="global-vehicle-selector" id="global-vehicle-selector">
    <button class="vehicle-selector-toggle" type="button" aria-expanded="false" aria-haspopup="true">
        <i class="ap-icon-car"></i>
        <span class="selector-label"><?php esc_html_e('Your Vehicle', 'autoparts-pro'); ?></span>
        <span class="selected-vehicle-display">
            <?php if ($selected_vehicle) : ?>
                <?php echo esc_html($selected_vehicle['year'] . ' ' . $selected_vehicle['make'] . ' ' . $selected_vehicle['model']); ?>
            <?php else : ?>
                <?php esc_html_e('Select Vehicle', 'autoparts-pro'); ?>
            <?php endif; ?>
        </span>
        <i class="ap-icon-chevron-down"></i>
    </button>

    <div class="vehicle-selector-dropdown" style="display: none;">
        <!-- Saved Vehicles (Garage) -->
        <?php if (!empty($saved_vehicles)) : ?>
            <div class="garage-vehicles-section">
                <h4><?php esc_html_e('My Garage', 'autoparts-pro'); ?></h4>
                <ul class="saved-vehicles-list">
                    <?php foreach ($saved_vehicles as $index => $vehicle) : ?>
                        <li class="saved-vehicle-item" data-vehicle='<?php echo esc_attr(json_encode($vehicle)); ?>'>
                            <div class="vehicle-info">
                                <span class="vehicle-year"><?php echo esc_html($vehicle['year']); ?></span>
                                <span class="vehicle-make-model"><?php echo esc_html($vehicle['make'] . ' ' . $vehicle['model']); ?></span>
                                <?php if (!empty($vehicle['engine'])) : ?>
                                    <span class="vehicle-engine"><?php echo esc_html($vehicle['engine']); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($selected_vehicle && $selected_vehicle === $vehicle) : ?>
                                <span class="active-badge">
                                    <i class="ap-icon-check"></i>
                                    <?php esc_html_e('Selected', 'autoparts-pro'); ?>
                                </span>
                            <?php endif; ?>
                            <button class="remove-vehicle-btn" data-index="<?php echo esc_attr($index); ?>" aria-label="<?php esc_attr_e('Remove vehicle', 'autoparts-pro'); ?>">
                                <i class="ap-icon-trash"></i>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Add New Vehicle -->
        <div class="add-vehicle-section">
            <h4><?php esc_html_e('Add Your Vehicle', 'autoparts-pro'); ?></h4>
            <form class="vehicle-add-form" method="post">
                <div class="form-row">
                    <label for="vehicle-year"><?php esc_html_e('Year', 'autoparts-pro'); ?></label>
                    <select id="vehicle-year" name="year" required>
                        <option value=""><?php esc_html_e('Select Year', 'autoparts-pro'); ?></option>
                        <?php
                        $current_year = date('Y');
                        for ($y = $current_year; $y >= $current_year - 30; $y--) {
                            echo '<option value="' . esc_attr($y) . '">' . esc_html($y) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-row">
                    <label for="vehicle-make"><?php esc_html_e('Make', 'autoparts-pro'); ?></label>
                    <select id="vehicle-make" name="make" required>
                        <option value=""><?php esc_html_e('Select Make', 'autoparts-pro'); ?></option>
                        <?php
                        $makes = autoparts_pro_get_vehicle_makes();
                        foreach ($makes as $make) {
                            echo '<option value="' . esc_attr($make->slug) . '">' . esc_html($make->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-row">
                    <label for="vehicle-model"><?php esc_html_e('Model', 'autoparts-pro'); ?></label>
                    <select id="vehicle-model" name="model" required>
                        <option value=""><?php esc_html_e('Select Model', 'autoparts-pro'); ?></option>
                    </select>
                </div>

                <div class="form-row">
                    <label for="vehicle-engine"><?php esc_html_e('Engine', 'autoparts-pro'); ?></label>
                    <select id="vehicle-engine" name="engine">
                        <option value=""><?php esc_html_e('Select Engine (Optional)', 'autoparts-pro'); ?></option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="button button-primary">
                        <i class="ap-icon-plus"></i>
                        <?php esc_html_e('Add to Garage', 'autoparts-pro'); ?>
                    </button>
                </div>
            </form>
        </div>

        <?php if (empty($saved_vehicles)) : ?>
            <div class="no-saved-vehicles-notice">
                <i class="ap-icon-car"></i>
                <p><?php esc_html_e('No vehicles saved yet. Add your first vehicle to check part compatibility.', 'autoparts-pro'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    var $selector = $('#global-vehicle-selector');
    var $toggle = $selector.find('.vehicle-selector-toggle');
    var $dropdown = $selector.find('.vehicle-selector-dropdown');
    
    // Toggle dropdown
    $toggle.on('click', function(e) {
        e.preventDefault();
        var isExpanded = $(this).attr('aria-expanded') === 'true';
        $(this).attr('aria-expanded', !isExpanded);
        $dropdown.slideToggle(200);
    });
    
    // Close on outside click
    $(document).on('click', function(e) {
        if (!$(e.target).closest($selector).length) {
            $toggle.attr('aria-expanded', 'false');
            $dropdown.slideUp(200);
        }
    });
    
    // Select saved vehicle
    $selector.on('click', '.saved-vehicle-item', function(e) {
        if ($(e.target).closest('.remove-vehicle-btn').length) {
            return;
        }
        
        var vehicleData = $(this).data('vehicle');
        selectVehicle(vehicleData);
    });
    
    // Remove vehicle
    $selector.on('click', '.remove-vehicle-btn', function(e) {
        e.stopPropagation();
        var index = $(this).data('index');
        removeVehicle(index);
    });
    
    // Handle make change to load models
    $('#vehicle-make').on('change', function() {
        var makeSlug = $(this).val();
        loadModelsForMake(makeSlug);
    });
    
    // Handle model change to load engines
    $('#vehicle-model').on('change', function() {
        var modelSlug = $(this).val();
        var makeSlug = $('#vehicle-make').val();
        loadEnginesForModel(makeSlug, modelSlug);
    });
    
    // Form submission
    $('.vehicle-add-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = {
            year: $('#vehicle-year').val(),
            make: $('#vehicle-make').val(),
            model: $('#vehicle-model').val(),
            engine: $('#vehicle-engine').val()
        };
        
        addVehicleToGarage(formData);
    });
    
    function selectVehicle(vehicle) {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'autoparts_pro_select_vehicle',
                vehicle: vehicle,
                nonce: '<?php echo wp_create_nonce('autoparts_pro_vehicle_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            }
        });
    }
    
    function removeVehicle(index) {
        if (!confirm('<?php echo esc_js(__('Are you sure you want to remove this vehicle?', 'autoparts-pro')); ?>')) {
            return;
        }
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'autoparts_pro_remove_vehicle',
                index: index,
                nonce: '<?php echo wp_create_nonce('autoparts_pro_vehicle_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            }
        });
    }
    
    function addVehicleToGarage(vehicleData) {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'autoparts_pro_add_vehicle',
                vehicle: vehicleData,
                nonce: '<?php echo wp_create_nonce('autoparts_pro_vehicle_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.data.message || '<?php echo esc_js(__('Failed to add vehicle. Please try again.', 'autoparts-pro')); ?>');
                }
            }
        });
    }
    
    function loadModelsForMake(makeSlug) {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'autoparts_pro_get_models',
                make: makeSlug,
                nonce: '<?php echo wp_create_nonce('autoparts_pro_models_nonce'); ?>'
            },
            success: function(response) {
                var $modelSelect = $('#vehicle-model');
                $modelSelect.empty().append('<option value=""><?php echo esc_js(__('Select Model', 'autoparts-pro')); ?></option>');
                
                if (response.success && response.data.models) {
                    response.data.models.forEach(function(model) {
                        $modelSelect.append('<option value="' + model.slug + '">' + model.name + '</option>');
                    });
                }
            }
        });
    }
    
    function loadEnginesForModel(makeSlug, modelSlug) {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'autoparts_pro_get_engines',
                make: makeSlug,
                model: modelSlug,
                nonce: '<?php echo wp_create_nonce('autoparts_pro_engines_nonce'); ?>'
            },
            success: function(response) {
                var $engineSelect = $('#vehicle-engine');
                $engineSelect.empty().append('<option value=""><?php echo esc_js(__('Select Engine (Optional)', 'autoparts-pro')); ?></option>');
                
                if (response.success && response.data.engines) {
                    response.data.engines.forEach(function(engine) {
                        $engineSelect.append('<option value="' + engine.slug + '">' + engine.name + '</option>');
                    });
                }
            }
        });
    }
});
</script>
