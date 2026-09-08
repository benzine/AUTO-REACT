<?php
/**
 * My Account Addresses
 * 
 * @package AutoParts_Pro
 */

defined('ABSPATH') || exit;

$customer = new WC_Customer(get_current_user_id());
$primary_address = 'billing';
?>

<div class="my-account-addresses">
    <h2><?php esc_html_e('My Addresses', 'autoparts-pro'); ?></h2>
    <p class="addresses-description"><?php esc_html_e('The following addresses will be used on the checkout page by default.', 'autoparts-pro'); ?></p>

    <div class="addresses-grid">
        <!-- Billing Address -->
        <div class="address-card billing-address">
            <header class="address-header">
                <h3><?php esc_html_e('Billing Address', 'autoparts-pro'); ?></h3>
                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'billing')); ?>" class="button button-small">
                    <?php esc_html_e('Edit', 'autoparts-pro'); ?>
                </a>
            </header>
            
            <?php
            $billing_address = $customer->get_billing();
            if (array_filter($billing_address)) :
                ?>
                <address class="address-content">
                    <?php echo WC()->countries->get_formatted_address($customer->get_billing_address_array()); ?>
                    <?php if ($customer->get_billing_phone()) : ?>
                        <p class="phone"><?php echo esc_html($customer->get_billing_phone()); ?></p>
                    <?php endif; ?>
                    <?php if ($customer->get_billing_email()) : ?>
                        <p class="email"><?php echo esc_html($customer->get_billing_email()); ?></p>
                    <?php endif; ?>
                </address>
            <?php else : ?>
                <p class="no-address"><?php esc_html_e('You have not set up this type of address yet.', 'autoparts-pro'); ?></p>
            <?php endif; ?>
        </div>

        <!-- Shipping Address -->
        <div class="address-card shipping-address">
            <header class="address-header">
                <h3><?php esc_html_e('Shipping Address', 'autoparts-pro'); ?></h3>
                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'shipping')); ?>" class="button button-small">
                    <?php esc_html_e('Edit', 'autoparts-pro'); ?>
                </a>
            </header>
            
            <?php
            $shipping_address = $customer->get_shipping();
            if (array_filter($shipping_address)) :
                ?>
                <address class="address-content">
                    <?php echo WC()->countries->get_formatted_address($customer->get_shipping_address_array()); ?>
                    <?php if ($customer->get_shipping_phone()) : ?>
                        <p class="phone"><?php echo esc_html($customer->get_shipping_phone()); ?></p>
                    <?php endif; ?>
                </address>
            <?php else : ?>
                <p class="no-address"><?php esc_html_e('You have not set up this type of address yet.', 'autoparts-pro'); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Additional Addresses (if supported) -->
    <?php if (class_exists('WC_Customer_Addresses') || apply_filters('autoparts_pro_support_multiple_addresses', false)) : ?>
        <div class="additional-addresses-section">
            <h3><?php esc_html_e('Additional Addresses', 'autoparts-pro'); ?></h3>
            <p class="add-address-cta">
                <a href="<?php echo esc_url(wc_get_endpoint_url('add-address')); ?>" class="button button-primary">
                    <i class="ap-icon-plus"></i>
                    <?php esc_html_e('Add New Address', 'autoparts-pro'); ?>
                </a>
            </p>
            
            <?php
            // Get additional addresses logic would go here
            $additional_addresses = get_user_meta(get_current_user_id(), '_autoparts_additional_addresses', true);
            $additional_addresses = is_array($additional_addresses) ? $additional_addresses : [];
            
            if (!empty($additional_addresses)) :
                ?>
                <div class="additional-addresses-list">
                    <?php foreach ($additional_addresses as $index => $address) : ?>
                        <div class="address-card additional-address">
                            <header class="address-header">
                                <h4><?php echo esc_html($address['label'] ?? sprintf(__('Address %d', 'autoparts-pro'), $index + 1)); ?></h4>
                                <div class="address-actions">
                                    <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', $index)); ?>" class="button button-small">
                                        <?php esc_html_e('Edit', 'autoparts-pro'); ?>
                                    </a>
                                    <button class="button button-small button-danger delete-address" data-index="<?php echo esc_attr($index); ?>">
                                        <?php esc_html_e('Delete', 'autoparts-pro'); ?>
                                    </button>
                                </div>
                            </header>
                            <address class="address-content">
                                <?php
                                echo esc_html($address['first_name'] . ' ' . $address['last_name']);
                                echo '<br>' . esc_html($address['address_1']);
                                if (!empty($address['address_2'])) {
                                    echo '<br>' . esc_html($address['address_2']);
                                }
                                echo '<br>' . esc_html($address['city'] . ', ' . $address['postcode']);
                                echo '<br>' . esc_html(WC()->countries->countries[$address['country']] ?? '');
                                if (!empty($address['phone'])) {
                                    echo '<br><span class="phone">' . esc_html($address['phone']) . '</span>';
                                }
                                ?>
                            </address>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Save for later / Garage addresses -->
    <div class="garage-addresses-section">
        <h3><?php esc_html_e('Garage / Workshop Addresses', 'autoparts-pro'); ?></h3>
        <p class="description"><?php esc_html_e('Save addresses where you work on vehicles for faster checkout.', 'autoparts-pro'); ?></p>
        
        <?php
        $garage_addresses = get_user_meta(get_current_user_id(), '_autoparts_garage_addresses', true);
        $garage_addresses = is_array($garage_addresses) ? $garage_addresses : [];
        
        if (!empty($garage_addresses)) :
            ?>
            <div class="garage-addresses-list">
                <?php foreach ($garage_addresses as $index => $address) : ?>
                    <div class="address-card garage-address">
                        <header class="address-header">
                            <h4>
                                <i class="ap-icon-wrench"></i>
                                <?php echo esc_html($address['name'] ?? sprintf(__('Garage %d', 'autoparts-pro'), $index + 1)); ?>
                            </h4>
                            <button class="button button-small button-link delete-garage-address" data-index="<?php echo esc_attr($index); ?>">
                                <?php esc_html_e('Remove', 'autoparts-pro'); ?>
                            </button>
                        </header>
                        <address class="address-content">
                            <?php echo esc_html($address['full_address']); ?>
                        </address>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="no-garage-addresses"><?php esc_html_e('No garage addresses saved yet.', 'autoparts-pro'); ?></p>
        <?php endif; ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Delete additional address
    $('.delete-address').on('click', function(e) {
        e.preventDefault();
        if (confirm('<?php echo esc_js(__('Are you sure you want to delete this address?', 'autoparts-pro')); ?>')) {
            // AJAX delete logic
        }
    });
    
    // Delete garage address
    $('.delete-garage-address').on('click', function(e) {
        e.preventDefault();
        if (confirm('<?php echo esc_js(__('Are you sure you want to remove this garage address?', 'autoparts-pro')); ?>')) {
            // AJAX delete logic
        }
    });
});
</script>
