<?php
/**
 * Template Name: Flash Deals Page
 * Displays flash deals with countdown timers
 */

get_header();
?>

<main class="flash-deals-page">
  <!-- Hero Section -->
  <section class="page-hero">
    <div class="container">
      <h1 class="page-title">
        <span class="highlight">Flash Deals</span>
        <span class="subtitle">Limited Time Offers - Don't Miss Out!</span>
      </h1>
      <div class="countdown-banner">
        <div class="countdown-timer" data-end-time="<?php echo strtotime('+24 hours'); ?>">
          <div class="countdown-item">
            <span class="countdown-number" data-days>00</span>
            <span class="countdown-label">Days</span>
          </div>
          <div class="countdown-separator">:</div>
          <div class="countdown-item">
            <span class="countdown-number" data-hours>00</span>
            <span class="countdown-label">Hours</span>
          </div>
          <div class="countdown-separator">:</div>
          <div class="countdown-item">
            <span class="countdown-number" data-minutes>00</span>
            <span class="countdown-label">Minutes</span>
          </div>
          <div class="countdown-separator">:</div>
          <div class="countdown-item">
            <span class="countdown-number" data-seconds>00</span>
            <span class="countdown-label">Seconds</span>
          </div>
        </div>
        <p class="urgency-text">⚡ Deals expire in:</p>
      </div>
    </div>
  </section>

  <!-- Flash Deals Grid -->
  <section class="flash-deals-section">
    <div class="container">
      <div class="deals-grid">
        <?php
        // Query for products on sale
        $args = array(
          'post_type' => 'product',
          'posts_per_page' => 12,
          'meta_query' => array(
            array(
              'key' => '_sale_price',
              'value' => 0,
              'compare' => '>',
              'type' => 'NUMERIC',
            ),
          ),
        );
        
        $flash_deals = new WP_Query($args);
        
        if ($flash_deals->have_posts()) :
          while ($flash_deals->have_posts()) : $flash_deals->the_post();
            global $product;
            $sale_price = $product->get_sale_price();
            $regular_price = $product->get_regular_price();
            $discount_percentage = $regular_price > 0 ? round((($regular_price - $sale_price) / $regular_price) * 100) : 0;
            ?>
            <div class="flash-deal-card" data-product-id="<?php echo get_the_ID(); ?>">
              <div class="deal-badge">-<?php echo $discount_percentage; ?>% OFF</div>
              <div class="deal-progress">
                <div class="progress-bar" style="width: <?php echo rand(20, 90); ?>%">
                  <span class="progress-text"><?php echo rand(1, 50); ?> sold</span>
                </div>
              </div>
              
              <div class="deal-image">
                <a href="<?php the_permalink(); ?>">
                  <?php echo get_the_post_thumbnail(get_the_ID(), 'medium'); ?>
                </a>
                <button class="quick-view-btn" data-product-id="<?php echo get_the_ID(); ?>">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/>
                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                  </svg>
                </button>
              </div>
              
              <div class="deal-info">
                <h3 class="deal-title">
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                
                <div class="deal-pricing">
                  <span class="current-price">$<?php echo number_format($sale_price, 2); ?></span>
                  <span class="original-price">$<?php echo number_format($regular_price, 2); ?></span>
                </div>
                
                <div class="deal-stock">
                  <span class="stock-available">Only <?php echo rand(3, 20); ?> left in stock!</span>
                </div>
                
                <div class="deal-actions">
                  <button class="add-to-cart-btn" data-product-id="<?php echo get_the_ID(); ?>">
                    Add to Cart
                  </button>
                  <button class="wishlist-btn" data-product-id="<?php echo get_the_ID(); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                      <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
            <?php
          endwhile;
          wp_reset_postdata();
        else :
          ?>
          <div class="no-deals">
            <p>No flash deals available at the moment. Check back soon!</p>
          </div>
          <?php
        endif;
        ?>
      </div>
    </div>
  </section>

  <!-- Newsletter Signup for Deal Alerts -->
  <section class="deal-alerts-section">
    <div class="container">
      <div class="alerts-box">
        <h2>Never Miss a Deal!</h2>
        <p>Subscribe to get instant notifications when new flash deals go live</p>
        <form class="alerts-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
          <input type="hidden" name="action" value="subscribe_deal_alerts">
          <div class="form-group">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit" class="subscribe-btn">Subscribe</button>
          </div>
          <p class="form-note">We'll never share your email. Unsubscribe anytime.</p>
        </form>
      </div>
    </div>
  </section>
</main>

<script>
// Countdown Timer
function updateCountdown(endTime) {
  const now = Math.floor(Date.now() / 1000);
  const distance = endTime - now;
  
  if (distance <= 0) {
    // Timer expired
    document.querySelectorAll('.countdown-number').forEach(el => el.textContent = '00');
    return;
  }
  
  const days = Math.floor(distance / (60 * 60 * 24));
  const hours = Math.floor((distance % (60 * 60 * 24)) / (60 * 60));
  const minutes = Math.floor((distance % (60 * 60)) / 60);
  const seconds = Math.floor(distance % 60);
  
  const daysEl = document.querySelector('[data-days]');
  const hoursEl = document.querySelector('[data-hours]');
  const minutesEl = document.querySelector('[data-minutes]');
  const secondsEl = document.querySelector('[data-seconds]');
  
  if (daysEl) daysEl.textContent = String(days).padStart(2, '0');
  if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
  if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
  if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
}

// Initialize countdown
const countdownTimer = document.querySelector('.countdown-timer');
if (countdownTimer) {
  const endTime = parseInt(countdownTimer.dataset.endTime);
  setInterval(() => updateCountdown(endTime), 1000);
  updateCountdown(endTime);
}
</script>

<?php get_footer(); ?>
