<?php
/**
 * Front page template.
 *
 * @package Tutti_Frutti_Cafe
 */

get_header();
?>

<main id="primary" class="site-main site-main--home">
    <?php
    get_template_part( 'template-parts/home/hero' );
    get_template_part( 'template-parts/home/brands-grid' );
    get_template_part( 'template-parts/home/featured-treats' );
    get_template_part( 'template-parts/home/moments' );
    if ( get_theme_mod( 'tf_show_promos', false ) ) {
        get_template_part( 'template-parts/home/promo-banners' );
    }
    ?>
</main>

<?php
get_footer();
