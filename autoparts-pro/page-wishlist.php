<?php
/**
 * Template Name: Wishlist Page
 * Display user's saved wishlist items
 *
 * @package AutoParts_Pro
 */

get_header();

$current_user_id = get_current_user_id();
$is_logged_in = is_user_logged_in();
?>

<main id="primary" class="site-main autoparts-wishlist-page">
    <div class="autoparts-container">
        
        <header class="autoparts-page-header">
            <h1 class="autoparts-page-title"><?php _e('My Wishlist', 'autoparts-pro'); ?></h1>
            <?php if ($is_logged_in): ?>
                <p class="autoparts-page-subtitle"><?php _e('Your saved items are synced across devices', 'autoparts-pro'); ?></p>
            <?php else: ?>
                <p class="autoparts-page-subtitle"><?php _e('Your wishlist is stored locally. Log in to sync across devices.', 'autoparts-pro'); ?></p>
            <?php endif; ?>
        </header>

        <div class="autoparts-wishlist-content" data-user-id="<?php echo esc_attr($current_user_id); ?>" data-logged-in="<?php echo $is_logged_in ? 'true' : 'false'; ?>">
            
            <!-- Wishlist Actions -->
            <div class="autoparts-wishlist-actions">
                <button class="autoparts-btn autoparts-btn-primary autoparts-add-all-to-cart" <?php disabled(!$is_logged_in && empty($_COOKIE['autoparts_guest_wishlist'])); ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    <?php _e('Add All to Cart', 'autoparts-pro'); ?>
                </button>
                
                <button class="autoparts-btn autoparts-btn-outline autoparts-share-wishlist">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                    </svg>
                    <?php _e('Share Wishlist', 'autoparts-pro'); ?>
                </button>
                
                <span class="autoparts-wishlist-count">
                    <?php _e('Items:', 'autoparts-pro'); ?> <strong class="count-number">0</strong>
                </span>
            </div>

            <!-- Wishlist Grid -->
            <div class="autoparts-wishlist-grid">
                <!-- Items will be loaded dynamically via JavaScript -->
                <div class="autoparts-loading-spinner">
                    <div class="spinner"></div>
                    <p><?php _e('Loading your wishlist...', 'autoparts-pro'); ?></p>
                </div>
            </div>

            <!-- Empty State -->
            <div class="autoparts-wishlist-empty" style="display:none;">
                <svg class="autoparts-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <h2><?php _e('Your wishlist is empty', 'autoparts-pro'); ?></h2>
                <p><?php _e('Save products you love by clicking the heart icon on any product.', 'autoparts-pro'); ?></p>
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="autoparts-btn autoparts-btn-primary">
                    <?php _e('Browse Products', 'autoparts-pro'); ?>
                </a>
            </div>

        </div>

        <!-- Create New Wishlist (for logged-in users) -->
        <?php if ($is_logged_in): ?>
        <div class="autoparts-create-wishlist-section">
            <h3><?php _e('Create New Wishlist', 'autoparts-pro'); ?></h3>
            <form class="autoparts-new-wishlist-form">
                <input type="text" name="wishlist_name" placeholder="<?php esc_attr_e('e.g., Engine Rebuild, Winter Prep', 'autoparts-pro'); ?>" required>
                <button type="submit" class="autoparts-btn autoparts-btn-secondary">
                    <?php _e('Create', 'autoparts-pro'); ?>
                </button>
            </form>
            <div class="autoparts-existing-wishlists">
                <!-- Existing wishlists will be shown here -->
            </div>
        </div>
        <?php endif; ?>

    </div>
</main>

<script>
jQuery(document).ready(function($) {
    const userId = $('.autoparts-wishlist-content').data('user-id');
    const isLoggedIn = $('.autoparts-wishlist-content').data('logged-in') === 'true';
    
    // Load wishlist items
    function loadWishlist() {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'autoparts_get_wishlist',
                user_id: userId,
                nonce: '<?php echo wp_create_nonce('autoparts_wishlist_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    renderWishlistItems(response.data.items);
                } else {
                    showEmptyState();
                }
            }
        });
    }
    
    function renderWishlistItems(items) {
        const $grid = $('.autoparts-wishlist-grid');
        
        if (!items || items.length === 0) {
            showEmptyState();
            return;
        }
        
        $('.autoparts-wishlist-empty').hide();
        $('.autoparts-loading-spinner').hide();
        
        let html = '';
        items.forEach(function(item) {
            html += `
                <div class="autoparts-wishlist-item" data-product-id="${item.id}">
                    <div class="autoparts-wishlist-image">
                        <img src="${item.image}" alt="${item.name}">
                        ${!item.in_stock ? '<span class="out-of-stock-badge">' + '<?php _e('Out of Stock', 'autoparts-pro'); ?>' + '</span>' : ''}
                    </div>
                    <div class="autoparts-wishlist-details">
                        <h3 class="autoparts-wishlist-title">
                            <a href="${item.url}">${item.name}</a>
                        </h3>
                        <div class="autoparts-wishlist-price">
                            ${item.price_html}
                        </div>
                        ${item.stock_status ? '<div class="autoparts-wishlist-stock">' + item.stock_status + '</div>' : ''}
                        <div class="autoparts-wishlist-actions">
                            <button class="autoparts-btn autoparts-btn-small add-to-cart" data-product-id="${item.id}">
                                ${item.in_stock ? '<?php _e('Add to Cart', 'autoparts-pro'); ?>' : '<?php _e('Out of Stock', 'autoparts-pro'); ?>'}
                            </button>
                            <button class="autoparts-btn autoparts-btn-small autoparts-remove-wishlist" data-product-id="${item.id}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $grid.html(html);
        updateCount(items.length);
    }
    
    function showEmptyState() {
        $('.autoparts-loading-spinner').hide();
        $('.autoparts-wishlist-grid').empty();
        $('.autoparts-wishlist-empty').show();
        updateCount(0);
    }
    
    function updateCount(count) {
        $('.count-number').text(count);
        // Update header badge
        $('.autoparts-wishlist-count-badge').text(count);
    }
    
    // Remove from wishlist
    $(document).on('click', '.autoparts-remove-wishlist', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const $item = $(this).closest('.autoparts-wishlist-item');
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'autoparts_remove_from_wishlist',
                product_id: productId,
                user_id: userId,
                nonce: '<?php echo wp_create_nonce('autoparts_wishlist_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $item.fadeOut(300, function() {
                        $(this).remove();
                        const remaining = $('.autoparts-wishlist-item').length;
                        if (remaining === 0) {
                            showEmptyState();
                        } else {
                            updateCount(remaining);
                        }
                    });
                }
            }
        });
    });
    
    // Add to cart from wishlist
    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const $btn = $(this);
        
        $btn.addClass('loading');
        
        $.ajax({
            url: '<?php echo WC_AJAX::get_endpoint('add_to_cart'); ?>',
            type: 'POST',
            data: {
                product_id: productId
            },
            success: function(response) {
                $btn.removeClass('loading').addClass('success');
                $btn.html('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> <?php _e('Added!', 'autoparts-pro'); ?>');
                
                // Update cart fragments
                $(document.body).trigger('wc_fragment_refresh');
                
                setTimeout(function() {
                    $btn.removeClass('success').html('<?php _e('Add to Cart', 'autoparts-pro'); ?>');
                }, 2000);
            }
        });
    });
    
    // Add all to cart
    $('.autoparts-add-all-to-cart').on('click', function(e) {
        e.preventDefault();
        const productIds = [];
        
        $('.autoparts-wishlist-item').each(function() {
            productIds.push($(this).data('product-id'));
        });
        
        if (productIds.length === 0) return;
        
        const $btn = $(this);
        $btn.addClass('loading');
        
        // Add items one by one
        let added = 0;
        productIds.forEach(function(id) {
            $.ajax({
                url: '<?php echo WC_AJAX::get_endpoint('add_to_cart'); ?>',
                type: 'POST',
                async: false,
                data: { product_id: id },
                success: function() { added++; }
            });
        });
        
        $(document.body).trigger('wc_fragment_refresh');
        
        $btn.removeClass('loading').addClass('success');
        $btn.html('<?php _e('All Added!', 'autoparts-pro'); ?>');
        
        setTimeout(function() {
            $btn.removeClass('success').html('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg> <?php _e('Add All to Cart', 'autoparts-pro'); ?>');
        }, 2000);
    });
    
    // Share wishlist
    $('.autoparts-share-wishlist').on('click', function(e) {
        e.preventDefault();
        
        const shareUrl = window.location.href;
        const shareTitle = '<?php echo esc_js(get_bloginfo('name')); ?> - <?php _e('Wishlist', 'autoparts-pro'); ?>';
        
        if (navigator.share) {
            navigator.share({
                title: shareTitle,
                url: shareUrl
            });
        } else {
            // Fallback: copy to clipboard
            navigator.clipboard.writeText(shareUrl).then(function() {
                alert('<?php _e('Wishlist link copied to clipboard!', 'autoparts-pro'); ?>');
            });
        }
    });
    
    // Create new wishlist
    $('.autoparts-new-wishlist-form').on('submit', function(e) {
        e.preventDefault();
        const name = $(this).find('input[name="wishlist_name"]').val();
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'autoparts_create_wishlist',
                name: name,
                nonce: '<?php echo wp_create_nonce('autoparts_wishlist_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    alert('<?php _e('Wishlist created!', 'autoparts-pro'); ?>');
                    $(this).find('input').val('');
                }
            }
        });
    });
    
    // Initial load
    loadWishlist();
});
</script>

<?php
get_footer();
