<?php
/**
 * Order Tracking Page Template - AutoParts Pro
 * Allow customers to track their orders by order number and email
 */

get_header();

// Handle form submission
$tracking_result = null;
$tracking_error = null;

if (isset($_POST['track_order_submit']) && wp_verify_nonce($_POST['track_order_nonce'], 'autoparts_track_order')) {
    $order_id = isset($_POST['order_id']) ? sanitize_text_field($_POST['order_id']) : '';
    $order_email = isset($_POST['order_email']) ? sanitize_email($_POST['order_email']) : '';
    
    if (empty($order_id) || empty($order_email)) {
        $tracking_error = __('Please enter both order ID and email.', 'autoparts-pro');
    } else {
        // Try to find the order
        $order = wc_get_order($order_id);
        
        if ($order && strtolower($order->get_billing_email()) === strtolower($order_email)) {
            $tracking_result = $order;
        } else {
            $tracking_error = __('Order not found. Please check your order ID and email.', 'autoparts-pro');
        }
    }
}
?>

<div class="autoparts-order-tracking-page">
    <div class="container">
        
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title"><?php esc_html_e('Track Your Order', 'autoparts-pro'); ?></h1>
            <p class="page-subtitle"><?php esc_html_e('Enter your order details to see real-time status updates', 'autoparts-pro'); ?></p>
        </div>

        <!-- Tracking Form -->
        <div class="tracking-form-section">
            <form method="post" class="tracking-form" action="">
                <?php wp_nonce_field('autoparts_track_order', 'track_order_nonce'); ?>
                
                <div class="form-row">
                    <label for="order_id"><?php esc_html_e('Order ID', 'autoparts-pro'); ?></label>
                    <input type="text" name="order_id" id="order_id" placeholder="<?php esc_attr_e('e.g., 12345', 'autoparts-pro'); ?>" value="<?php echo esc_attr(isset($_POST['order_id']) ? $_POST['order_id'] : ''); ?>" required>
                    <small><?php esc_html_e('Found in your order confirmation email', 'autoparts-pro'); ?></small>
                </div>
                
                <div class="form-row">
                    <label for="order_email"><?php esc_html_e('Email Address', 'autoparts-pro'); ?></label>
                    <input type="email" name="order_email" id="order_email" placeholder="<?php esc_attr_e('Your email address', 'autoparts-pro'); ?>" value="<?php echo esc_attr(isset($_POST['order_email']) ? $_POST['order_email'] : ''); ?>" required>
                    <small><?php esc_html_e('Email used when placing the order', 'autoparts-pro'); ?></small>
                </div>
                
                <button type="submit" name="track_order_submit" class="btn btn-primary btn-large">
                    <i class="fas fa-search"></i> <?php esc_html_e('Track Order', 'autoparts-pro'); ?>
                </button>
            </form>
            
            <?php if ($tracking_error) : ?>
                <div class="tracking-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo esc_html($tracking_error); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tracking Results -->
        <?php if ($tracking_result) : ?>
            <div class="tracking-results">
                
                <!-- Order Summary -->
                <div class="order-summary-card">
                    <div class="summary-header">
                        <h2><?php esc_html_e('Order Details', 'autoparts-pro'); ?></h2>
                        <span class="order-id">#<?php echo esc_html($tracking_result->get_id()); ?></span>
                    </div>
                    
                    <div class="summary-grid">
                        <div class="summary-item">
                            <span class="label"><?php esc_html_e('Order Date:', 'autoparts-pro'); ?></span>
                            <span class="value"><?php echo wc_format_datetime($tracking_result->get_date_created()); ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="label"><?php esc_html_e('Status:', 'autoparts-pro'); ?></span>
                            <span class="value order-status-<?php echo esc_attr($tracking_result->get_status()); ?>">
                                <?php echo esc_html(wc_get_order_status_name($tracking_result->get_status())); ?>
                            </span>
                        </div>
                        <div class="summary-item">
                            <span class="label"><?php esc_html_e('Total:', 'autoparts-pro'); ?></span>
                            <span class="value"><?php echo $tracking_result->get_formatted_order_total(); ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="label"><?php esc_html_e('Payment Method:', 'autoparts-pro'); ?></span>
                            <span class="value"><?php echo esc_html($tracking_result->get_payment_method_title()); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Order Status Timeline -->
                <div class="order-timeline">
                    <h3><?php esc_html_e('Order Progress', 'autoparts-pro'); ?></h3>
                    
                    <div class="timeline-steps">
                        <?php
                        $status = $tracking_result->get_status();
                        $date_created = $tracking_result->get_date_created();
                        
                        $statuses = array(
                            'pending' => array(
                                'label' => __('Order Placed', 'autoparts-pro'),
                                'icon' => 'fa-clipboard-check',
                            ),
                            'processing' => array(
                                'label' => __('Processing', 'autoparts-pro'),
                                'icon' => 'fa-cogs',
                            ),
                            'on-hold' => array(
                                'label' => __('On Hold', 'autoparts-pro'),
                                'icon' => 'fa-pause-circle',
                            ),
                            'completed' => array(
                                'label' => __('Completed', 'autoparts-pro'),
                                'icon' => 'fa-check-circle',
                            ),
                            'shipped' => array(
                                'label' => __('Shipped', 'autoparts-pro'),
                                'icon' => 'fa-shipping-fast',
                            ),
                            'delivered' => array(
                                'label' => __('Delivered', 'autoparts-pro'),
                                'icon' => 'fa-home',
                            ),
                            'cancelled' => array(
                                'label' => __('Cancelled', 'autoparts-pro'),
                                'icon' => 'fa-times-circle',
                            ),
                            'refunded' => array(
                                'label' => __('Refunded', 'autoparts-pro'),
                                'icon' => 'fa-undo',
                            ),
                        );
                        
                        $status_order = array('pending', 'processing', 'shipped', 'delivered');
                        $current_index = array_search($status, $status_order);
                        if ($current_index === false) {
                            $current_index = 0;
                        }
                        
                        foreach ($status_order as $index => $status_key) :
                            $is_completed = $index <= $current_index;
                            $is_current = $index == $current_index;
                            $status_info = $statuses[$status_key];
                        ?>
                            <div class="timeline-step <?php echo $is_completed ? 'completed' : ''; ?> <?php echo $is_current ? 'current' : ''; ?>">
                                <div class="step-marker">
                                    <i class="fas <?php echo esc_attr($status_info['icon']); ?>"></i>
                                </div>
                                <div class="step-info">
                                    <span class="step-label"><?php echo esc_html($status_info['label']); ?></span>
                                    <?php if ($is_completed) : ?>
                                        <span class="step-date">
                                            <?php 
                                            if ($is_current && $date_created) {
                                                echo wc_format_datetime($date_created);
                                            } elseif ($is_completed) {
                                                // In a real implementation, you'd store timestamps for each status change
                                                echo __('Completed', 'autoparts-pro');
                                            }
                                            ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($index < count($status_order) - 1) : ?>
                                    <div class="step-line <?php echo $index < $current_index ? 'completed' : ''; ?>"></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="order-items-card">
                    <h3><?php esc_html_e('Order Items', 'autoparts-pro'); ?></h3>
                    
                    <table class="order-items-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Product', 'autoparts-pro'); ?></th>
                                <th><?php esc_html_e('SKU', 'autoparts-pro'); ?></th>
                                <th><?php esc_html_e('Quantity', 'autoparts-pro'); ?></th>
                                <th><?php esc_html_e('Price', 'autoparts-pro'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tracking_result->get_items() as $item) : ?>
                                <?php
                                $product = $item->get_product();
                                $sku = $product ? $product->get_sku() : '-';
                                ?>
                                <tr>
                                    <td>
                                        <div class="item-info">
                                            <?php if ($product) : ?>
                                                <?php echo $product->get_image(array(50, 50)); ?>
                                            <?php endif; ?>
                                            <span class="item-name"><?php echo esc_html($item->get_name()); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo esc_html($sku); ?></td>
                                    <td><?php echo esc_html($item->get_quantity()); ?></td>
                                    <td><?php echo wc_price($item->get_subtotal()); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Shipping Information -->
                <?php if ($tracking_result->has_shipping_address()) : ?>
                    <div class="shipping-info-card">
                        <h3><?php esc_html_e('Shipping Address', 'autoparts-pro'); ?></h3>
                        <address>
                            <?php echo wp_kses_post($tracking_result->get_formatted_shipping_address()); ?>
                        </address>
                    </div>
                <?php endif; ?>

                <!-- Need Help -->
                <div class="tracking-help">
                    <h4><?php esc_html_e('Need Help?', 'autoparts-pro'); ?></h4>
                    <p><?php esc_html_e('If you have questions about your order, please contact our support team.', 'autoparts-pro'); ?></p>
                    <div class="help-actions">
                        <a href="mailto:<?php echo esc_attr(get_option('admin_email')); ?>" class="btn btn-secondary">
                            <i class="fas fa-envelope"></i> <?php esc_html_e('Email Support', 'autoparts-pro'); ?>
                        </a>
                        <a href="tel:<?php echo esc_attr(get_theme_mod('contact_phone', '+1-800-AUTOPARTS')); ?>" class="btn btn-secondary">
                            <i class="fas fa-phone"></i> <?php esc_html_e('Call Us', 'autoparts-pro'); ?>
                        </a>
                    </div>
                </div>

            </div>
        <?php endif; ?>

        <!-- Additional Info -->
        <div class="tracking-info-section">
            <div class="info-grid">
                <div class="info-card">
                    <i class="fas fa-clock"></i>
                    <h4><?php esc_html_e('Real-Time Updates', 'autoparts-pro'); ?></h4>
                    <p><?php esc_html_e('Get instant notifications as your order progresses through each stage.', 'autoparts-pro'); ?></p>
                </div>
                <div class="info-card">
                    <i class="fas fa-shield-alt"></i>
                    <h4><?php esc_html_e('Secure Tracking', 'autoparts-pro'); ?></h4>
                    <p><?php esc_html_e('Your order information is protected and only accessible with your order details.', 'autoparts-pro'); ?></p>
                </div>
                <div class="info-card">
                    <i class="fas fa-headset"></i>
                    <h4><?php esc_html_e('24/7 Support', 'autoparts-pro'); ?></h4>
                    <p><?php esc_html_e('Our team is always available to help with any questions about your order.', 'autoparts-pro'); ?></p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php get_footer(); ?>
