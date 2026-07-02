<?php
/**
 * Order page fallback.
 *
 * @package Tutti_Frutti_Cafe
 */
$top_class = ! empty( $top ) ? ' page-section--top' : '';
?>
<section class="order-top<?php echo esc_attr( $top_class ); ?>">
    <div class="container page-split page-split--reverse">
        <div class="page-split__content">
            <h1><?php esc_html_e( 'Order Your Favorites Pickup or Delivery', 'tutti-frutti-cafe' ); ?></h1>
            <p><?php esc_html_e( 'Skip the line and enjoy your favorite treats, drinks and meals.', 'tutti-frutti-cafe' ); ?></p>
            <a href="https://order.chownow.com/order/43470/locations/65443" class="btn btn-primary"><?php esc_html_e( 'Order Now', 'tutti-frutti-cafe' ); ?></a>
        </div>
        <div class="page-split__media">
            <img src="<?php echo esc_url( tutti_frutti_get_page_banner( 'order' ) ); ?>" alt="" class="phone-mockup">
        </div>
    </div>
</section>
<section class="order-features">
    <div class="container features-grid--4">
        <?php
        $features = array(
            array( '⚡', __( 'Fast & Easy', 'tutti-frutti-cafe' ) ),
            array( '🕐', __( 'Real-Time Tracking', 'tutti-frutti-cafe' ) ),
            array( '🔒', __( 'Secure Payment', 'tutti-frutti-cafe' ) ),
            array( '🎁', __( 'Earn Rewards', 'tutti-frutti-cafe' ) ),
        );
        foreach ( $features as $f ) :
            ?>
            <div class="feature-item">
                <span class="feature-item__icon" aria-hidden="true"><?php echo esc_html( $f[0] ); ?></span>
                <span class="feature-item__label"><?php echo esc_html( $f[1] ); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</section>
