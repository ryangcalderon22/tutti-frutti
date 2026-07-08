<?php
/**
 * Homepage Featured Treats + Grab & Go slider section.
 *
 * @package Tutti_Frutti_Cafe
 */
$section_title = get_theme_mod( 'tf_featured_treats_title', 'Featured Treats' );
$subtitle      = get_theme_mod( 'tf_grab_go_title', 'Grab & Go' );
$group         = sanitize_title( get_theme_mod( 'tf_slider_group', 'grab-and-go' ) );
$slide_count   = min( 50, max( 1, absint( get_theme_mod( 'tf_slider_count', 10 ) ) ) );
?>
<section class="home-section home-featured">
    <div class="container">
        <?php if ( $section_title ) : ?>
            <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
        <?php endif; ?>
        <?php if ( $subtitle ) : ?>
            <p class="home-featured__subtitle section-subtitle"><?php echo esc_html( $subtitle ); ?></p>
        <?php endif; ?>
        <?php if ( shortcode_exists( 'tutti_slider' ) ) : ?>
            <?php
            $slider_html = do_shortcode( '[tutti_slider group="' . esc_attr( $group ) . '" slides="' . esc_attr( (string) $slide_count ) . '" style="featured"]' );
            echo tutti_frutti_ensure_img_alt( $slider_html );
            ?>
        <?php else : ?>
            <div class="tf-slider tf-slider--empty">
                <p><?php esc_html_e( 'Activate the Tutti Frutti Slider plugin and add slides with the Grab & Go group.', 'tutti-frutti-cafe' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>
