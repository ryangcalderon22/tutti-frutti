<?php
/**
 * Homepage promo banners
 *
 * @package Tutti_Frutti_Cafe
 */
$promo1_url = tutti_frutti_get_content( 'tf_promo1_url', '' );
$promo2_url = tutti_frutti_get_content( 'tf_promo2_url', '' );
if ( ! $promo1_url ) {
    $promo1_url = tutti_frutti_page_url( 'contact' );
}
if ( ! $promo2_url ) {
    $promo2_url = tutti_frutti_page_url( 'brands' );
}
$promo1_img = get_theme_mod( 'tf_promo1_image' );
$promo2_img = get_theme_mod( 'tf_promo2_image' );
?>
<section class="home-section home-promos">
    <div class="container promo-grid">
        <a href="<?php echo esc_url( $promo1_url ); ?>" class="promo-card promo-card--late">
            <?php if ( $promo1_img ) : ?>
                <img src="<?php echo esc_url( $promo1_img ); ?>" alt="" class="promo-card__bg" loading="lazy">
            <?php else : ?>
                <?php tutti_frutti_demo_img( 'promo_late', '', 'promo-card__bg' ); ?>
            <?php endif; ?>
            <div class="promo-card__overlay"></div>
            <h3 class="promo-card__title"><?php echo esc_html( tutti_frutti_get_content( 'tf_promo1_title', __( 'Late Nights. Good Vibes. Great Treats.', 'tutti-frutti-cafe' ) ) ); ?></h3>
            <span class="btn btn-sm btn-promo-purple"><?php echo esc_html( tutti_frutti_get_content( 'tf_promo1_btn', __( 'Explore Midnight Menu', 'tutti-frutti-cafe' ) ) ); ?></span>
        </a>
        <a href="<?php echo esc_url( $promo2_url ); ?>" class="promo-card promo-card--tea">
            <?php if ( $promo2_img ) : ?>
                <img src="<?php echo esc_url( $promo2_img ); ?>" alt="" class="promo-card__bg" loading="lazy">
            <?php else : ?>
                <?php tutti_frutti_demo_img( 'promo_hightea', '', 'promo-card__bg' ); ?>
            <?php endif; ?>
            <div class="promo-card__overlay"></div>
            <h3 class="promo-card__title"><?php echo esc_html( tutti_frutti_get_content( 'tf_promo2_title', __( 'High Tea. Good Company. Timeless Tradition.', 'tutti-frutti-cafe' ) ) ); ?></h3>
            <span class="btn btn-sm btn-promo-gold"><?php echo esc_html( tutti_frutti_get_content( 'tf_promo2_btn', __( 'Discover High Tea', 'tutti-frutti-cafe' ) ) ); ?></span>
        </a>
    </div>
</section>
