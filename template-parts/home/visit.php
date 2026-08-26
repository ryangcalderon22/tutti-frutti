<?php
/**
 * Homepage "Visit us" location section.
 *
 * Address and phone read from the same Customizer settings that feed the
 * LocalBusiness schema (inc/schema-local-business.php) so the visible text and
 * the structured data always match.
 *
 * @package Tutti_Frutti_Cafe
 */
$visit_title = get_theme_mod( 'tf_visit_title', 'Visit Tutti Frutti Café in La Verne' );
$visit_text  = get_theme_mod( 'tf_visit_text', 'Visit us at 2357 Foothill Blvd, La Verne, CA 91750 for frozen yogurt, coffee, lunch, dessert and everything in between. Dine in, pick up your favorites, or order online for a convenient meal or treat.' );

$street = get_theme_mod( 'tf_schema_street', '2357 Foothill Blvd' );
$city   = get_theme_mod( 'tf_schema_city', 'La Verne' );
$state  = get_theme_mod( 'tf_schema_state', 'CA' );
$zip    = get_theme_mod( 'tf_schema_zip', '91750' );

$phone_display = get_theme_mod( 'tf_phone_display', '(909) 245-1383' );
$phone_digits  = preg_replace( '/[^0-9+]/', '', get_theme_mod( 'tf_schema_phone', '+1-909-245-1383' ) );

$city_line = trim( $city . ', ' . $state . ' ' . $zip );

// Same destination the Directions page uses, built from the settings above.
$maps_url  = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode( $street . ', ' . $city_line );
$order_url = function_exists( 'tutti_frutti_get_chownow_url' ) ? tutti_frutti_get_chownow_url() : home_url( '/order-online/' );
?>
<section class="home-section home-visit">
    <div class="container home-visit__inner">
        <?php if ( $visit_title ) : ?>
            <h2 class="section-title"><?php echo esc_html( $visit_title ); ?></h2>
        <?php endif; ?>

        <?php if ( $visit_text ) : ?>
            <p class="home-visit__text"><?php echo esc_html( $visit_text ); ?></p>
        <?php endif; ?>

        <address class="home-visit__address">
            <a href="<?php echo esc_url( $maps_url ); ?>" class="home-visit__address-link" target="_blank" rel="noopener">
                <span class="home-visit__street"><?php echo esc_html( $street ); ?></span>
                <span class="home-visit__city"><?php echo esc_html( $city_line ); ?></span>
            </a>
            <?php if ( $phone_display ) : ?>
                <a href="tel:<?php echo esc_attr( $phone_digits ); ?>" class="home-visit__phone"><?php echo esc_html( $phone_display ); ?></a>
            <?php endif; ?>
        </address>

        <div class="home-visit__actions">
            <a href="<?php echo esc_url( $maps_url ); ?>" class="btn btn-primary" target="_blank" rel="noopener"><?php esc_html_e( 'Get Directions', 'tutti-frutti-cafe' ); ?></a>
            <a href="<?php echo esc_url( $order_url ); ?>" class="btn btn-tertiary" target="_blank" rel="noopener"><?php esc_html_e( 'Order Online', 'tutti-frutti-cafe' ); ?></a>
        </div>
    </div>
</section>