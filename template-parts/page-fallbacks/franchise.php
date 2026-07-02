<?php
/**
 * Franchise fallback.
 *
 * @package Tutti_Frutti_Cafe
 */
$top_class = ! empty( $top ) ? ' page-section--top' : '';
?>
<section class="page-section page-section--cream<?php echo esc_attr( $top_class ); ?>">
    <div class="container page-split page-split--reverse">
        <div class="page-split__content">
            <h1><?php esc_html_e( 'Franchise Opportunities', 'tutti-frutti-cafe' ); ?></h1>
            <p><?php esc_html_e( 'Bring Tutti Frutti Café to your community.', 'tutti-frutti-cafe' ); ?></p>
            <a href="<?php echo esc_url( tutti_frutti_page_url( 'contact' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Get In Touch', 'tutti-frutti-cafe' ); ?></a>
        </div>
        <div class="page-split__media">
            <img src="<?php echo esc_url( tutti_frutti_get_page_banner( 'franchise' ) ); ?>" alt="" class="page-banner-img">
        </div>
    </div>
</section>
