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
            'title'        => __( 'Explore Tutti Frutti Café Brands', 'tutti-frutti-cafe' ),
            'description'  => __( 'Tutti Frutti Café brings four complementary café concepts together in one La Verne location: Tutti Frutti frozen yogurt, açaí, smoothies and matcha; PIO Coffee; O MY Cookies & Desserts; and TF Bites sandwiches, pasta, pizza and savory bites.', 'tutti-frutti-cafe' ),
            'wrap_section' => true,
            'heading_tag'  => 'h1',
        )
    );
    ?>
    <?php get_template_part( 'template-parts/page-editable-content' ); ?>
</main>

<?php get_footer(); ?>
