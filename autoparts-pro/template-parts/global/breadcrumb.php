<?php
/**
 * Global Breadcrumb Component
 * 
 * @package AutoParts_Pro
 */

defined('ABSPATH') || exit;

if (!function_exists('woocommerce_breadcrumb')) {
    return;
}
?>

<nav class="autoparts-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'autoparts-pro'); ?>">
    <div class="breadcrumb-container">
        <?php woocommerce_breadcrumb(array(
            'delimiter'   => '<li class="breadcrumb-separator" aria-hidden="true"><i class="ap-icon-chevron-right"></i></li>',
            'wrap_before' => '<ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">',
            'wrap_after'  => '</ol>',
            'before'      => '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">',
            'after'       => '</li>',
            'home'        => _x('Home', 'breadcrumb', 'autoparts-pro'),
        )); ?>
    </div>
    
    <!-- Vehicle compatibility badge in breadcrumb -->
    <?php if (is_product() && function_exists('autoparts_pro_get_compatibility_badge')) : ?>
        <div class="breadcrumb-compatibility-badge">
            <?php echo autoparts_pro_get_compatibility_badge(); ?>
        </div>
    <?php endif; ?>
</nav>
