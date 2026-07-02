<?php
/**
 * Homepage moments section — text only, no icons.
 *
 * @package Tutti_Frutti_Cafe
 */
$moments_title = get_theme_mod( 'tf_moments_title', '' );
$moments_text  = get_theme_mod( 'tf_moments_text', '' );
$moments_img   = get_theme_mod( 'tf_moments_image', '' );
?>
<section class="home-section home-moments">
    <div class="container moments-split">
        <div class="moments-split__content">
            <?php if ( $moments_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $moments_title ); ?></h2>
            <?php endif; ?>
            <?php if ( $moments_text ) : ?>
                <p class="moments-split__text"><?php echo esc_html( $moments_text ); ?></p>
            <?php endif; ?>
        </div>
        <?php if ( $moments_img ) : ?>
            <div class="moments-split__media">
               <img src="<?php echo esc_url( $moments_img ); ?>" alt="" loading="lazy">
            </div>
        <?php endif; ?>
    </div>
</section>