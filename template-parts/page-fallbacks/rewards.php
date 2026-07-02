<?php
/**
 * Rewards fallback.
 *
 * @package Tutti_Frutti_Cafe
 */
$top_class = ! empty( $top ) ? ' page-section--top' : '';
?>
<section class="rewards-section<?php echo esc_attr( $top_class ); ?>">
    <div class="container page-split">
        <div class="page-split__media">
            <img src="<?php echo esc_url( tutti_frutti_get_page_banner( 'rewards' ) ); ?>" alt="" class="phone-mockup">
        </div>
        <div class="page-split__content">
            <h1><?php esc_html_e( 'Earn Points. Get Rewarded.', 'tutti-frutti-cafe' ); ?></h1>
            <p><?php esc_html_e( 'Join the VIP Club and earn points every time you order.', 'tutti-frutti-cafe' ); ?></p>
        </div>
    </div>
</section>
