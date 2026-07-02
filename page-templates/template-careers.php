<?php
/**
 * Template Name: Careers
 *
 * @package Tutti_Frutti_Cafe
 */
get_header();

$embed_url = get_theme_mod(
    'tf_careers_form_embed',
    'https://forms.cloud.microsoft/Pages/ResponsePage.aspx?id=QGI0uOtd9ECqpEQtKFKl0yOc0rvlMUBIogqH86u6DuhURDhQWTVBM1lBRzBIMU5FUDY3WjdINUxaSi4u&embed=true'
);
$embed_width  = sanitize_text_field( get_theme_mod( 'tf_careers_embed_width', '100%' ) );
$embed_height = sanitize_text_field( get_theme_mod( 'tf_careers_embed_height', '720px' ) );
?>

<main id="primary" class="site-main page-careers site-main--page">
    <?php tutti_frutti_render_page_sections( 'careers' ); ?>

    <?php if ( $embed_url ) : ?>
        <section class="page-section page-section--cream careers-embed-section">
            <div class="container">
                <div class="careers-embed-wrap" style="max-width: <?php echo esc_attr( $embed_width ); ?>;">
                    <iframe
                        class="careers-embed-frame"
                        src="<?php echo esc_url( $embed_url ); ?>"
                        width="100%"
                        height="<?php echo esc_attr( $embed_height ); ?>"
                        frameborder="0"
                        marginwidth="0"
                        marginheight="0"
                        style="border: none; max-width:100%; height: <?php echo esc_attr( $embed_height ); ?>;"
                        allowfullscreen
                        webkitallowfullscreen
                        mozallowfullscreen
                        msallowfullscreen
                        title="<?php esc_attr_e( 'Careers Application Form', 'tutti-frutti-cafe' ); ?>"
                    ></iframe>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php get_template_part( 'template-parts/page-editable-content' ); ?>
</main>

<?php get_footer(); ?>
