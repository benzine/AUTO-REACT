<?php
/**
 * Footer Template
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<footer class="site-footer" id="colophon">
    <!-- Footer Widgets -->
    <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
        <div class="footer-widgets">
            <div class="container">
                <div class="footer-grid">
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar( 'footer-1' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar( 'footer-2' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar( 'footer-3' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar( 'footer-4' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Copyright Bar -->
    <div class="copyright-bar">
        <div class="container">
            <div class="copyright-content">
                <p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All Rights Reserved.', 'autoparts-pro' ); ?></p>
                
                <?php wp_nav_menu( array(
                    'theme_location' => 'footer',
                    'menu_class'     => 'footer-menu',
                    'depth'          => 1,
                ) ); ?>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<?php autoparts_pro_back_to_top(); ?>

<!-- AI Chatbot Widget -->
<?php autoparts_pro_chatbot_widget(); ?>

<?php wp_footer(); ?>

</body>
</html>
