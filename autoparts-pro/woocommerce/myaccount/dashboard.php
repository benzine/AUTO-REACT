<?php
/**
 * My Account Dashboard
 * 
 * @package AutoParts_Pro
 */

defined('ABSPATH') || exit;

do_action('autoparts_pro_before_dashboard');
?>

<div class="autoparts-dashboard">
    <div class="dashboard-header">
        <h2><?php esc_html_e('Welcome back', 'autoparts-pro'); ?> <?php echo esc_html(wp_get_current_user()->display_name); ?></h2>
        <p class="dashboard-subtitle"><?php esc_html_e('Manage your orders, vehicles, and wishlist from your garage.', 'autoparts-pro'); ?></p>
    </div>

    <div class="dashboard-grid">
        <!-- Quick Stats -->
        <div class="dashboard-card stats-card">
            <div class="stat-icon">
                <i class="ap-icon-package"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html($total_orders = wp_count_posts('shop_order')->publish ?? 0); ?></h3>
                <p><?php esc_html_e('Total Orders', 'autoparts-pro'); ?></p>
            </div>
        </div>

        <div class="dashboard-card stats-card">
            <div class="stat-icon">
                <i class="ap-icon-car"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html(count(get_user_meta(get_current_user_id(), '_autoparts_garage_vehicles', true) ?: [])); ?></h3>
                <p><?php esc_html_e('Vehicles in Garage', 'autoparts-pro'); ?></p>
            </div>
        </div>

        <div class="dashboard-card stats-card">
            <div class="stat-icon">
                <i class="ap-icon-heart"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html(count(get_user_meta(get_current_user_id(), '_autoparts_wishlist', true) ?: [])); ?></h3>
                <p><?php esc_html_e('Wishlist Items', 'autoparts-pro'); ?></p>
            </div>
        </div>

        <div class="dashboard-card stats-card">
            <div class="stat-icon">
                <i class="ap-icon-download"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo esc_html(count(WC_Customer_Download::get_downloads_for_customer())); ?></h3>
                <p><?php esc_html_e('Available Downloads', 'autoparts-pro'); ?></p>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="dashboard-section">
        <h3><?php esc_html_e('Recent Orders', 'autoparts-pro'); ?></h3>
        <?php
        $customer_orders = wc_get_orders(array(
            'customer' => get_current_user_id(),
            'limit' => 5,
            'return' => 'objects',
        ));

        if ($customer_orders) : ?>
            <table class="dashboard-orders-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Order', 'autoparts-pro'); ?></th>
                        <th><?php esc_html_e('Date', 'autoparts-pro'); ?></th>
                        <th><?php esc_html_e('Status', 'autoparts-pro'); ?></th>
                        <th><?php esc_html_e('Total', 'autoparts-pro'); ?></th>
                        <th><?php esc_html_e('Actions', 'autoparts-pro'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customer_orders as $order) : ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url($order->get_view_order_url()); ?>">
                                    #<?php echo esc_html($order->get_order_number()); ?>
                                </a>
                            </td>
                            <td><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></td>
                            <td>
                                <span class="order-status status-<?php echo esc_attr($order->get_status()); ?>">
                                    <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
                                </span>
                            </td>
                            <td><?php echo $order->get_formatted_order_total(); ?></td>
                            <td>
                                <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="button">
                                    <?php esc_html_e('View', 'autoparts-pro'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="view-all-link">
                <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>">
                    <?php esc_html_e('View all orders →', 'autoparts-pro'); ?>
                </a>
            </div>
        <?php else : ?>
            <p class="no-orders"><?php esc_html_e('No orders yet.', 'autoparts-pro'); ?></p>
        <?php endif; ?>
    </div>

    <!-- Saved Vehicles Preview -->
    <div class="dashboard-section">
        <h3><?php esc_html_e('My Garage', 'autoparts-pro'); ?></h3>
        <?php
        $garage_vehicles = get_user_meta(get_current_user_id(), '_autoparts_garage_vehicles', true);
        $garage_vehicles = is_array($garage_vehicles) ? $garage_vehicles : [];

        if (!empty($garage_vehicles)) : ?>
            <div class="garage-preview-grid">
                <?php foreach (array_slice($garage_vehicles, 0, 3) as $index => $vehicle) : ?>
                    <div class="vehicle-card-mini">
                        <div class="vehicle-image">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/vehicle-placeholder.svg'); ?>" 
                                 alt="<?php echo esc_attr($vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model']); ?>">
                        </div>
                        <div class="vehicle-info">
                            <h4><?php echo esc_html($vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model']); ?></h4>
                            <?php if (!empty($vehicle['engine'])) : ?>
                                <p class="engine-spec"><?php echo esc_html($vehicle['engine']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="view-all-link">
                <a href="<?php echo esc_url(home_url('/garage')); ?>">
                    <?php esc_html_e('Manage garage →', 'autoparts-pro'); ?>
                </a>
            </div>
        <?php else : ?>
            <p class="no-vehicles">
                <?php esc_html_e('No vehicles saved yet. Add your first vehicle to check part compatibility.', 'autoparts-pro'); ?>
            </p>
            <a href="<?php echo esc_url(home_url('/garage')); ?>" class="button button-primary">
                <?php esc_html_e('Add Vehicle', 'autoparts-pro'); ?>
            </a>
        <?php endif; ?>
    </div>

    <!-- Quick Links -->
    <div class="dashboard-section">
        <h3><?php esc_html_e('Quick Actions', 'autoparts-pro'); ?></h3>
        <div class="quick-actions-grid">
            <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" class="quick-action-card">
                <i class="ap-icon-package"></i>
                <span><?php esc_html_e('Orders', 'autoparts-pro'); ?></span>
            </a>
            <a href="<?php echo esc_url(wc_get_endpoint_url('downloads')); ?>" class="quick-action-card">
                <i class="ap-icon-download"></i>
                <span><?php esc_html_e('Downloads', 'autoparts-pro'); ?></span>
            </a>
            <a href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>" class="quick-action-card">
                <i class="ap-icon-user"></i>
                <span><?php esc_html_e('Account Details', 'autoparts-pro'); ?></span>
            </a>
            <a href="<?php echo esc_url(home_url('/wishlist')); ?>" class="quick-action-card">
                <i class="ap-icon-heart"></i>
                <span><?php esc_html_e('Wishlist', 'autoparts-pro'); ?></span>
            </a>
            <a href="<?php echo esc_url(home_url('/garage')); ?>" class="quick-action-card">
                <i class="ap-icon-car"></i>
                <span><?php esc_html_e('Garage', 'autoparts-pro'); ?></span>
            </a>
            <a href="<?php echo esc_url(home_url('/technical-docs')); ?>" class="quick-action-card">
                <i class="ap-icon-book"></i>
                <span><?php esc_html_e('Guides', 'autoparts-pro'); ?></span>
            </a>
        </div>
    </div>
</div>

<?php do_action('autoparts_pro_after_dashboard'); ?>
