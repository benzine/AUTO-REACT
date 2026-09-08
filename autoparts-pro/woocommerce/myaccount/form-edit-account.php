<?php
/**
 * My Account Edit Account Form
 * 
 * @package AutoParts_Pro
 */

defined('ABSPATH') || exit;

$user = wp_get_current_user();
?>

<div class="my-account-edit-account">
    <h2><?php esc_html_e('Account Details', 'autoparts-pro'); ?></h2>

    <form method="post" action="" class="woocommerce-EditAccountForm edit-account" enctype="multipart/form-data">

        <?php do_action('woocommerce_edit_account_form_start'); ?>

        <div class="form-row form-row-first">
            <label for="account_first_name"><?php esc_html_e('First name', 'autoparts-pro'); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" 
                   name="account_first_name" id="account_first_name" autocomplete="given-name"
                   value="<?php echo esc_attr($user->first_name); ?>" />
        </div>

        <div class="form-row form-row-last">
            <label for="account_last_name"><?php esc_html_e('Last name', 'autoparts-pro'); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" 
                   name="account_last_name" id="account_last_name" autocomplete="family-name"
                   value="<?php echo esc_attr($user->last_name); ?>" />
        </div>

        <div class="form-row form-row-wide">
            <label for="account_display_name"><?php esc_html_e('Display name', 'autoparts-pro'); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" 
                   name="account_display_name" id="account_display_name"
                   value="<?php echo esc_attr($user->display_name); ?>" />
            <span class="description"><?php esc_html_e('This will be how your name will be displayed in the account section and in reviews.', 'autoparts-pro'); ?></span>
        </div>

        <div class="form-row form-row-wide">
            <label for="account_email"><?php esc_html_e('Email address', 'autoparts-pro'); ?>&nbsp;<span class="required">*</span></label>
            <input type="email" class="woocommerce-Input woocommerce-Input--email input-text" 
                   name="account_email" id="account_email" autocomplete="email"
                   value="<?php echo esc_attr($user->user_email); ?>" />
        </div>

        <fieldset class="form-row form-row-wide">
            <legend><?php esc_html_e('Password Change', 'autoparts-pro'); ?></legend>
            
            <div class="form-row form-row-first">
                <label for="password_current"><?php esc_html_e('Current password (leave blank to leave unchanged)', 'autoparts-pro'); ?></label>
                <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" 
                       name="password_current" id="password_current" autocomplete="current-password" />
            </div>

            <div class="form-row form-row-last">
                <label for="password_1"><?php esc_html_e('New password (leave blank to leave unchanged)', 'autoparts-pro'); ?></label>
                <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" 
                       name="password_1" id="password_1" autocomplete="new-password" />
            </div>

            <div class="form-row form-row-wide">
                <label for="password_2"><?php esc_html_e('Confirm new password', 'autoparts-pro'); ?></label>
                <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" 
                       name="password_2" id="password_2" autocomplete="new-password" />
            </div>
        </fieldset>

        <?php do_action('woocommerce_edit_account_form'); ?>

        <div class="form-row">
            <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
            <button type="submit" class="button button-primary" name="save_account_details" value="1">
                <?php esc_html_e('Save changes', 'autoparts-pro'); ?>
            </button>
            <input type="hidden" name="action" value="save_account_details" />
        </div>

        <?php do_action('woocommerce_edit_account_form_end'); ?>
    </form>

    <div class="account-security-tips">
        <h3><?php esc_html_e('Security Tips', 'autoparts-pro'); ?></h3>
        <ul>
            <li><?php esc_html_e('Use a strong password with at least 12 characters', 'autoparts-pro'); ?></li>
            <li><?php esc_html_e('Include numbers, symbols, and uppercase letters', 'autoparts-pro'); ?></li>
            <li><?php esc_html_e('Never share your password with anyone', 'autoparts-pro'); ?></li>
            <li><?php esc_html_e('Change your password regularly', 'autoparts-pro'); ?></li>
        </ul>
    </div>
</div>
