<?php
/**
 * My Account Orders List
 * 
 * @package AutoParts_Pro
 */

defined('ABSPATH') || exit;

$customer_orders = wc_get_orders(array(
    'customer' => get_current_user_id(),
    'limit' => $orders_per_page ?? 10,
    'page' => $current_page ?? 1,
    'return' => 'objects',
));
?>

<div class="my-account-orders">
    <h2><?php esc_html_e('My Orders', 'autoparts-pro'); ?></h2>

    <?php if ($customer_orders) : ?>
        <table class="woocommerce-orders-table shop_table shop_table_responsive my_account_orders">
            <thead>
                <tr>
                    <th class="order-number"><?php esc_html_e('Order', 'autoparts-pro'); ?></th>
                    <th class="order-date"><?php esc_html_e('Date', 'autoparts-pro'); ?></th>
                    <th class="order-status"><?php esc_html_e('Status', 'autoparts-pro'); ?></th>
                    <th class="order-total"><?php esc_html_e('Total', 'autoparts-pro'); ?></th>
                    <th class="order-actions"><?php esc_html_e('Actions', 'autoparts-pro'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customer_orders as $order) : ?>
                    <tr class="order-row">
                        <td class="order-number" data-title="<?php esc_attr_e('Order number', 'autoparts-pro'); ?>">
                            <a href="<?php echo esc_url($order->get_view_order_url()); ?>">
                                #<?php echo esc_html($order->get_order_number()); ?>
                            </a>
                        </td>
                        <td class="order-date" data-title="<?php esc_attr_e('Date', 'autoparts-pro'); ?>">
                            <time datetime="<?php echo esc_attr($order->get_date_created()->date('c')); ?>">
                                <?php echo esc_html(wc_format_datetime($order->get_date_created())); ?>
                            </time>
                        </td>
                        <td class="order-status" data-title="<?php esc_attr_e('Status', 'autoparts-pro'); ?>">
                            <span class="order-status-badge status-<?php echo esc_attr($order->get_status()); ?>">
                                <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
                            </span>
                        </td>
                        <td class="order-total" data-title="<?php esc_attr_e('Total', 'autoparts-pro'); ?>">
                            <?php echo $order->get_formatted_order_total(); ?>
                        </td>
                        <td class="order-actions" data-title="<?php esc_attr_e('Actions', 'autoparts-pro'); ?>">
                            <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="button">
                                <?php esc_html_e('View', 'autoparts-pro'); ?>
                            </a>
                            <?php if (in_array($order->get_status(), array('processing', 'completed'))) : ?>
                                <a href="<?php echo esc_url($order->get_invoice_url()); ?>" class="button secondary" target="_blank">
                                    <?php esc_html_e('Invoice', 'autoparts-pro'); ?>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        $total_pages = $customer_orders->max_num_pages;
        if ($total_pages > 1) :
            ?>
            <nav class="woocommerce-pagination">
                <?php
                echo paginate_links(array(
                    'base' => get_permalink(wc_get_page_id('myaccount')) . '%#%',
                    'format' => '?orders-page=%#%',
                    'current' => max(1, $current_page ?? 1),
                    'total' => $total_pages,
                    'prev_text' => '&larr;',
                    'next_text' => '&rarr;',
                    'type' => 'list',
                ));
                ?>
            </nav>
        <?php endif; ?>

    <?php else : ?>
        <div class="no-orders-message">
            <i class="ap-icon-package"></i>
            <h3><?php esc_html_e('No orders yet', 'autoparts-pro'); ?></h3>
            <p><?php esc_html_e('Once you place an order, it will appear here.', 'autoparts-pro'); ?></p>
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="button button-primary">
                <?php esc_html_e('Start Shopping', 'autoparts-pro'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>
