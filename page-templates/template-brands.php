<?php
/**
 * Template Name: Brands
 *
 * @package Tutti_Frutti_Cafe
 */
get_header();
?>

<main id="primary" class="site-main page-brands site-main--page">
    <?php
    get_template_part(
        'template-parts/brands/grid',
        null,
        array(
            'title'        => __( 'Explore Our Brands', 'tutti-frutti-cafe' ),
            'wrap_section' => true,
        )
    );
    ?>
    <?php get_template_part( 'template-parts/page-editable-content' ); ?>
</main>

<?php get_footer(); ?>
