<?php
/**
 * Careers fallback.
 *
 * @package Tutti_Frutti_Cafe
 */
$top_class = ! empty( $top ) ? ' page-section--top' : '';
?>
<section class="careers-hero<?php echo esc_attr( $top_class ); ?>">
    <div class="careers-hero__content">
        <h1><?php esc_html_e( 'Grow Your Career. Build Your Future.', 'tutti-frutti-cafe' ); ?></h1>
        <p class="careers-hero__intro"><?php esc_html_e( 'Join a team that values passion, growth and opportunity.', 'tutti-frutti-cafe' ); ?></p>
    </div>
    <div class="careers-hero__media">
        <img src="<?php echo esc_url( tutti_frutti_get_page_banner( 'careers' ) ); ?>" alt="">
    </div>
</section>
<section class="careers-paths">
    <div class="container careers-paths__inner">
        <?php
        $paths = array( 'entry', 'lead', 'store', 'own' );
        $labels = array(
            __( 'Entry Level Opportunities', 'tutti-frutti-cafe' ),
            __( 'Leadership & Management', 'tutti-frutti-cafe' ),
            __( 'Multi-Store Territory', 'tutti-frutti-cafe' ),
            __( 'Earn-Out Ownership', 'tutti-frutti-cafe' ),
        );
        foreach ( $paths as $i => $icon ) :
            ?>
            <div class="career-path">
                <span class="career-path__icon career-path__icon--<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></span>
                <span class="career-path__label"><?php echo esc_html( $labels[ $i ] ); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</section>
