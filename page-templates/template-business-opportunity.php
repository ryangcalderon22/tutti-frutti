<?php
/**
 * Template Name: Business Opportunity
 *
 * @package Tutti_Frutti_Cafe
 */
get_header();
?>

<main id="primary" class="site-main page-business-opportunity site-main--page">
    <?php tutti_frutti_render_page_sections( 'business-opportunity' ); ?>

    <section class="page-section page-section--cream business-form-section">
        <div class="container">
            <div class="business-form-wrap">
                <?php
                $tf_business_notice = tutti_frutti_get_business_notice();
                tutti_frutti_render_business_notice( $tf_business_notice );
                tutti_frutti_render_business_form( '', $tf_business_notice );
                ?>
            </div>
        </div>
    </section>

    <?php get_template_part( 'template-parts/page-editable-content' ); ?>
</main>

<?php get_footer(); ?>
