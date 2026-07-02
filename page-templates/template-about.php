<?php
/**
 * Template Name: About
 *
 * @package Tutti_Frutti_Cafe
 */
get_header();
?>

<main id="primary" class="site-main page-about site-main--page">
    <?php tutti_frutti_render_page_sections( 'about' ); ?>
    <?php get_template_part( 'template-parts/page-editable-content' ); ?>
</main>

<?php get_footer(); ?>
