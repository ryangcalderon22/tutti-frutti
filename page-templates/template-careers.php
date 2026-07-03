<?php
/**
 * Template Name: Careers
 *
 * @package Tutti_Frutti_Cafe
 */
get_header();
?>

<main id="primary" class="site-main page-careers site-main--page">
    <?php tutti_frutti_render_page_sections( 'careers' ); ?>

    <section class="page-section page-section--cream careers-form-section">
        <div class="container">
            <div class="careers-form-wrap">
                <?php tutti_frutti_render_careers_notice(); ?>
                <?php tutti_frutti_render_careers_form(); ?>
            </div>
        </div>
    </section>

    <?php get_template_part( 'template-parts/page-editable-content' ); ?>
</main>

<?php get_footer(); ?>
