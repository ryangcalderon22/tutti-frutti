<?php
/**
 * Template Name: Pio Coffee Brand (legacy — redirects to brand page)
 *
 * @package Tutti_Frutti_Cafe
 */
$brand = get_page_by_path( 'pio-coffee', OBJECT, 'tf_brand' );
if ( $brand ) {
    wp_safe_redirect( get_permalink( $brand ), 301 );
    exit;
}

get_header();
?>
<main class="site-main site-main--page page-section--cream page-section--top">
    <div class="container">
        <p><?php esc_html_e( 'Brand page not found. Please add Pio Coffee under Brands in admin.', 'tutti-frutti-cafe' ); ?></p>
    </div>
</main>
<?php
get_footer();
