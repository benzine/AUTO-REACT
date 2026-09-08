<?php
/**
 * My Account Downloads
 * 
 * @package AutoParts_Pro
 */

defined('ABSPATH') || exit;

$downloads = WC_Customer_Download::get_downloads_for_customer();
?>

<div class="my-account-downloads">
    <h2><?php esc_html_e('My Downloads', 'autoparts-pro'); ?></h2>

    <?php if ($downloads) : ?>
        <table class="woocommerce-downloads-table shop_table shop_table_responsive my_account_downloads">
            <thead>
                <tr>
                    <th class="download-product"><?php esc_html_e('Product', 'autoparts-pro'); ?></th>
                    <th class="download-file"><?php esc_html_e('File', 'autoparts-pro'); ?></th>
                    <th class="download-date"><?php esc_html_e('Date Added', 'autoparts-pro'); ?></th>
                    <th class="download-expire"><?php esc_html_e('Expires', 'autoparts-pro'); ?></th>
                    <th class="download-remaining"><?php esc_html_e('Downloads Remaining', 'autoparts-pro'); ?></th>
                    <th class="download-actions"><?php esc_html_e('Actions', 'autoparts-pro'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($downloads as $download) : ?>
                    <tr class="download-row">
                        <td class="download-product" data-title="<?php esc_attr_e('Product', 'autoparts-pro'); ?>">
                            <a href="<?php echo esc_url(get_permalink($download['product_id'])); ?>">
                                <?php echo esc_html(get_the_title($download['product_id'])); ?>
                            </a>
                        </td>
                        <td class="download-file" data-title="<?php esc_attr_e('File', 'autoparts-pro'); ?>">
                            <?php echo esc_html($download['download_name']); ?>
                        </td>
                        <td class="download-date" data-title="<?php esc_attr_e('Date Added', 'autoparts-pro'); ?>">
                            <?php echo esc_html(wc_format_datetime($download['download_timestamp'])); ?>
                        </td>
                        <td class="download-expire" data-title="<?php esc_attr_e('Expires', 'autoparts-pro'); ?>">
                            <?php if (!empty($download['access_expires'])) : ?>
                                <time datetime="<?php echo esc_attr($download['access_expires']); ?>">
                                    <?php echo esc_html(wc_format_datetime($download['access_expires'])); ?>
                                </time>
                            <?php else : ?>
                                <?php esc_html_e('Never', 'autoparts-pro'); ?>
                            <?php endif; ?>
                        </td>
                        <td class="download-remaining" data-title="<?php esc_attr_e('Downloads Remaining', 'autoparts-pro'); ?>">
                            <?php if (is_numeric($download['downloads_remaining'])) : ?>
                                <?php echo esc_html($download['downloads_remaining']); ?>
                            <?php else : ?>
                                <?php esc_html_e('Unlimited', 'autoparts-pro'); ?>
                            <?php endif; ?>
                        </td>
                        <td class="download-actions" data-title="<?php esc_attr_e('Actions', 'autoparts-pro'); ?>">
                            <a href="<?php echo esc_url($download['download_url']); ?>" class="button button-primary" download>
                                <i class="ap-icon-download"></i>
                                <?php esc_html_e('Download', 'autoparts-pro'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <div class="no-downloads-message">
            <i class="ap-icon-download"></i>
            <h3><?php esc_html_e('No downloads available yet', 'autoparts-pro'); ?></h3>
            <p><?php esc_html_e('Once you purchase a product with downloadable files, they will appear here.', 'autoparts-pro'); ?></p>
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="button button-primary">
                <?php esc_html_e('Browse Products', 'autoparts-pro'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>
