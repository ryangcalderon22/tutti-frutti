<?php
/**
 * Footer template
 *
 * @package Tutti_Frutti_Cafe
 */

$badge_icons = array( 'pot', 'leaf', 'halal', 'heart' );
$badge_defaults = array(
    __( 'Multi-Brand Café Experience', 'tutti-frutti-cafe' ),
    __( 'Premium Ingredients. Made Fresh. Made to Satisfy.', 'tutti-frutti-cafe' ),
    __( 'Halal Certified', 'tutti-frutti-cafe' ),
    __( 'Family Owned & Community Focused', 'tutti-frutti-cafe' ),
);
$badges = array();
for ( $i = 1; $i <= 4; $i++ ) {
    $badges[] = array(
        'icon'  => $badge_icons[ $i - 1 ],
        'label' => tutti_frutti_get_content( 'tf_footer_badge_' . $i, $badge_defaults[ $i - 1 ] ),
    );
}
?>

    <footer id="colophon" class="site-footer">
        <?php if ( get_theme_mod( 'tf_show_footer_badges', false ) ) : ?>
        <div class="footer-badges">
            <div class="container footer-badges__inner">
                <?php foreach ( $badges as $badge ) : ?>
                    <div class="footer-badge">
                        <span class="footer-badge__icon footer-badge__icon--<?php echo esc_attr( $badge['icon'] ); ?>" aria-hidden="true"></span>
                        <span class="footer-badge__label"><?php echo esc_html( $badge['label'] ); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="footer-main">
            <div class="container footer-grid">
                <div class="footer-col">
                   
                    <?php get_template_part( 'template-parts/footer-social' ); ?>
                </div>

                <div class="footer-col">
                    <h4><?php esc_html_e( 'Quick Links', 'tutti-frutti-cafe' ); ?></h4>
                    
					<ul class="footer-links">
    <div class="footer-link-col">
        <!-- <li><a href="<?php //echo esc_url( tutti_frutti_page_url( 'about' ) ); ?>">About</a></li> -->
        <li><a href="<?php echo esc_url( tutti_frutti_page_url( 'brands' ) ); ?>">Brands</a></li>
        <li><a href="<?php echo esc_url( tutti_frutti_page_url( 'rewards' ) ); ?>">Rewards</a></li>
    </div>

    <div class="footer-link-col">
        <!-- <li><a href="<?php //echo esc_url( tutti_frutti_page_url( 'rewards' ) ); ?>">Rewards</a></li> -->
        <li><a href="<?php echo esc_url( tutti_frutti_page_url( 'franchise' ) ); ?>">Franchise</a></li>
        <li><a href="<?php echo esc_url( tutti_frutti_page_url( 'contact' ) ); ?>">Contact</a></li>
    </div>

    <div class="footer-link-col">
        <!-- <li><a href="<?php //echo esc_url( tutti_frutti_page_url( 'careers' ) ); ?>">Careers</a></li> -->
        <!-- <li><a href="<?php //echo esc_url( tutti_frutti_page_url( 'contact' ) ); ?>">Contact</a></li> -->
        <li><a href="<?php echo esc_url( tutti_frutti_get_chownow_url() ); ?>" target="_blank" rel="noopener noreferrer">Order Online</a></li>
    </div>
    <!-- <div class="footer-link-col">
        
    </div> -->
</ul>		
					
                </div>
            
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p><?php echo wp_kses_post( get_theme_mod( 'footer_text', '&copy; ' . gmdate( 'Y' ) . ' Tutti Frutti Café. All rights reserved.' ) ); ?></p>
            </div>
        </div>
    </footer>

<?php wp_footer(); ?>
</body>
</html>
