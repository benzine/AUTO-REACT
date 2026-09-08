<?php
/**
 * Contact Page Template - AutoParts Pro
 * Advanced contact form with spam protection and store locator
 */

get_header();

// Handle form submission
$form_submitted = false;
$form_error = null;
$form_success = null;

if (isset($_POST['contact_form_submit']) && wp_verify_nonce($_POST['contact_nonce'], 'autoparts_contact_form')) {
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $phone = sanitize_text_field($_POST['contact_phone']);
    $subject = sanitize_text_field($_POST['contact_subject']);
    $message = sanitize_textarea_field($_POST['contact_message']);
    $department = sanitize_text_field($_POST['contact_department']);
    
    // Honeypot check
    if (!empty($_POST['contact_website'])) {
        $form_error = __('Spam detected.', 'autoparts-pro');
    } elseif (empty($name) || empty($email) || empty($message)) {
        $form_error = __('Please fill in all required fields.', 'autoparts-pro');
    } else {
        // Time-based spam detection (form submitted too quickly)
        $form_time = isset($_POST['form_time']) ? intval($_POST['form_time']) : 0;
        if ($form_time > 0 && time() - $form_time < 3) {
            $form_error = __('Spam detected.', 'autoparts-pro');
        } else {
            // Prepare email
            $to = get_option('admin_email');
            $email_subject = sprintf('[AutoParts Pro] %s - %s', $subject, $name);
            $email_body = sprintf(
                "Name: %s\nEmail: %s\nPhone: %s\nDepartment: %s\n\nMessage:\n%s",
                $name, $email, $phone, $department, $message
            );
            
            $headers = array(
                'Content-Type: text/plain; charset=UTF-8',
                'Reply-To: ' . $email,
            );
            
            if (wp_mail($to, $email_subject, $email_body, $headers)) {
                $form_success = __('Thank you! Your message has been sent successfully.', 'autoparts-pro');
                $form_submitted = true;
            } else {
                $form_error = __('Failed to send message. Please try again later.', 'autoparts-pro');
            }
        }
    }
}

// Get store locations
$stores = get_posts(array(
    'post_type'      => 'store_location',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
));
?>

<div class="autoparts-contact-page">
    <div class="container">
        
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title"><?php esc_html_e('Contact Us', 'autoparts-pro'); ?></h1>
            <p class="page-subtitle"><?php esc_html_e('Get in touch with our automotive experts', 'autoparts-pro'); ?></p>
        </div>

        <!-- Contact Info Cards -->
        <div class="contact-info-grid">
            <div class="contact-info-card">
                <div class="info-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <div class="info-content">
                    <h4><?php esc_html_e('Call Us', 'autoparts-pro'); ?></h4>
                    <p><a href="tel:+18001234567">+1 (800) 123-4567</a></p>
                    <small><?php esc_html_e('Mon-Fri: 8AM - 8PM EST', 'autoparts-pro'); ?></small>
                </div>
            </div>
            
            <div class="contact-info-card">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                    <h4><?php esc_html_e('Email Us', 'autoparts-pro'); ?></h4>
                    <p><a href="mailto:support@autopartspro.com">support@autopartspro.com</a></p>
                    <small><?php esc_html_e('Response within 24 hours', 'autoparts-pro'); ?></small>
                </div>
            </div>
            
            <div class="contact-info-card">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                    <h4><?php esc_html_e('Visit Us', 'autoparts-pro'); ?></h4>
                    <p><?php esc_html_e('123 Auto Parts Blvd, Detroit, MI 48201', 'autoparts-pro'); ?></p>
                    <small><?php esc_html_e('Mon-Sat: 9AM - 7PM', 'autoparts-pro'); ?></small>
                </div>
            </div>
            
            <div class="contact-info-card">
                <div class="info-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="info-content">
                    <h4><?php esc_html_e('Live Chat', 'autoparts-pro'); ?></h4>
                    <p><?php esc_html_e('Chat with our AI assistant', 'autoparts-pro'); ?></p>
                    <small><?php esc_html_e('Available 24/7', 'autoparts-pro'); ?></small>
                </div>
            </div>
        </div>

        <div class="contact-main-grid">
            
            <!-- Contact Form -->
            <div class="contact-form-section">
                <h2><?php esc_html_e('Send Us a Message', 'autoparts-pro'); ?></h2>
                
                <?php if ($form_success) : ?>
                    <div class="form-success-message">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo esc_html($form_success); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($form_error) : ?>
                    <div class="form-error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo esc_html($form_error); ?></span>
                    </div>
                <?php endif; ?>
                
                <form method="post" class="contact-form" action="">
                    <?php wp_nonce_field('autoparts_contact_form', 'contact_nonce'); ?>
                    <input type="hidden" name="form_time" value="<?php echo esc_attr(time()); ?>">
                    
                    <!-- Honeypot field (hidden from users) -->
                    <div style="display:none !important;" aria-hidden="true">
                        <label for="contact_website"><?php esc_html_e('Website', 'autoparts-pro'); ?></label>
                        <input type="text" name="contact_website" id="contact_website" value="" tabindex="-1" autocomplete="off">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact_name"><?php esc_html_e('Full Name', 'autoparts-pro'); ?> <span class="required">*</span></label>
                            <input type="text" name="contact_name" id="contact_name" placeholder="<?php esc_attr_e('John Doe', 'autoparts-pro'); ?>" value="<?php echo esc_attr(isset($_POST['contact_name']) ? $_POST['contact_name'] : ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_email"><?php esc_html_e('Email Address', 'autoparts-pro'); ?> <span class="required">*</span></label>
                            <input type="email" name="contact_email" id="contact_email" placeholder="<?php esc_attr_e('john@example.com', 'autoparts-pro'); ?>" value="<?php echo esc_attr(isset($_POST['contact_email']) ? $_POST['contact_email'] : ''); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact_phone"><?php esc_html_e('Phone Number', 'autoparts-pro'); ?></label>
                            <input type="tel" name="contact_phone" id="contact_phone" placeholder="<?php esc_attr_e('+1 (555) 123-4567', 'autoparts-pro'); ?>" value="<?php echo esc_attr(isset($_POST['contact_phone']) ? $_POST['contact_phone'] : ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_department"><?php esc_html_e('Department', 'autoparts-pro'); ?></label>
                            <select name="contact_department" id="contact_department">
                                <option value="sales"><?php esc_html_e('Sales Inquiry', 'autoparts-pro'); ?></option>
                                <option value="support"><?php esc_html_e('Technical Support', 'autoparts-pro'); ?></option>
                                <option value="parts"><?php esc_html_e('Parts Compatibility', 'autoparts-pro'); ?></option>
                                <option value="wholesale"><?php esc_html_e('Wholesale/Bulk Orders', 'autoparts-pro'); ?></option>
                                <option value="returns"><?php esc_html_e('Returns & Refunds', 'autoparts-pro'); ?></option>
                                <option value="other"><?php esc_html_e('Other', 'autoparts-pro'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="contact_subject"><?php esc_html_e('Subject', 'autoparts-pro'); ?> <span class="required">*</span></label>
                            <input type="text" name="contact_subject" id="contact_subject" placeholder="<?php esc_attr_e('How can we help?', 'autoparts-pro'); ?>" value="<?php echo esc_attr(isset($_POST['contact_subject']) ? $_POST['contact_subject'] : ''); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="contact_message"><?php esc_html_e('Message', 'autoparts-pro'); ?> <span class="required">*</span></label>
                            <textarea name="contact_message" id="contact_message" rows="6" placeholder="<?php esc_attr_e('Tell us about your vehicle and what parts you need...', 'autoparts-pro'); ?>" required><?php echo esc_textarea(isset($_POST['contact_message']) ? $_POST['contact_message'] : ''); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-submit">
                        <button type="submit" name="contact_form_submit" class="btn btn-primary btn-large">
                            <i class="fas fa-paper-plane"></i> <?php esc_html_e('Send Message', 'autoparts-pro'); ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Store Locator Map -->
            <div class="store-locator-section">
                <h2><?php esc_html_e('Our Locations', 'autoparts-pro'); ?></h2>
                
                <?php if (!empty($stores)) : ?>
                    <!-- Google Map Container -->
                    <div id="store-locator-map" class="store-map" data-api-key="<?php echo esc_attr(get_theme_mod('google_maps_api_key', '')); ?>"></div>
                    
                    <!-- Store List -->
                    <div class="store-list">
                        <?php foreach ($stores as $store) : ?>
                            <?php
                            $address = get_post_meta($store->ID, '_store_address', true);
                            $city = get_post_meta($store->ID, '_store_city', true);
                            $state = get_post_meta($store->ID, '_store_state', true);
                            $zip = get_post_meta($store->ID, '_store_zip', true);
                            $phone = get_post_meta($store->ID, '_store_phone', true);
                            $email = get_post_meta($store->ID, '_store_email', true);
                            $hours = get_post_meta($store->ID, '_store_hours', true);
                            $lat = get_post_meta($store->ID, '_store_lat', true);
                            $lng = get_post_meta($store->ID, '_store_lng', true);
                            ?>
                            <div class="store-item" data-lat="<?php echo esc_attr($lat); ?>" data-lng="<?php echo esc_attr($lng); ?>">
                                <h4><?php echo esc_html($store->post_title); ?></h4>
                                <address>
                                    <?php echo esc_html($address); ?><br>
                                    <?php echo esc_html("$city, $state $zip"); ?>
                                </address>
                                <div class="store-contact">
                                    <?php if ($phone) : ?>
                                        <p><i class="fas fa-phone"></i> <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a></p>
                                    <?php endif; ?>
                                    <?php if ($email) : ?>
                                        <p><i class="fas fa-envelope"></i> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
                                    <?php endif; ?>
                                </div>
                                <?php if ($hours) : ?>
                                    <div class="store-hours">
                                        <strong><?php esc_html_e('Hours:', 'autoparts-pro'); ?></strong>
                                        <p><?php echo wp_kses_post($hours); ?></p>
                                    </div>
                                <?php endif; ?>
                                <div class="store-actions">
                                    <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo urlencode($address . ' ' . $city . ' ' . $state . ' ' . $zip); ?>" target="_blank" rel="noopener" class="btn btn-small">
                                        <i class="fas fa-directions"></i> <?php esc_html_e('Get Directions', 'autoparts-pro'); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="no-stores">
                        <p><?php esc_html_e('No store locations found. Please contact us for assistance.', 'autoparts-pro'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="contact-faq-section">
            <h2><?php esc_html_e('Frequently Asked Questions', 'autoparts-pro'); ?></h2>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <h4><?php esc_html_e('How do I know if a part fits my vehicle?', 'autoparts-pro'); ?></h4>
                    <p><?php esc_html_e('Use our compatibility checker on each product page by selecting your year, make, model, and engine. You can also save your vehicle in your garage for quick checking.', 'autoparts-pro'); ?></p>
                </div>
                
                <div class="faq-item">
                    <h4><?php esc_html_e('What is your return policy?', 'autoparts-pro'); ?></h4>
                    <p><?php esc_html_e('We offer a 30-day return policy on most items. Parts must be unused and in original packaging. See our Returns Policy page for details.', 'autoparts-pro'); ?></p>
                </div>
                
                <div class="faq-item">
                    <h4><?php esc_html_e('Do you offer bulk discounts?', 'autoparts-pro'); ?></h4>
                    <p><?php esc_html_e('Yes! Many products have tiered bulk pricing. Look for the "Bulk Pricing" indicator on product cards. For wholesale inquiries, contact our sales team.', 'autoparts-pro'); ?></p>
                </div>
                
                <div class="faq-item">
                    <h4><?php esc_html_e('How long does shipping take?', 'autoparts-pro'); ?></h4>
                    <p><?php esc_html_e('Standard shipping takes 3-7 business days. Express options are available at checkout. Free shipping on orders over $99.', 'autoparts-pro'); ?></p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php get_footer(); ?>
