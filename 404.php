<?php
/**
 * The template for displaying 404 pages (not found)
 * 
 * @package Tutti_Frutti_Cafe
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container" style="text-align: center; padding: 80px 20px;">
        <h1 style="font-size: 48px; color: #6B3E26; margin-bottom: 20px;">
            <?php esc_html_e( '404', 'tutti-frutti-cafe' ); ?>
        </h1>
        <p style="font-size: 24px; color: #F26B21; margin-bottom: 30px;">
            <?php esc_html_e( 'Page Not Found', 'tutti-frutti-cafe' ); ?>
        </p>
        <p style="margin-bottom: 40px; color: #555;">
            <?php esc_html_e( 'The page you are looking for might have been removed or is temporarily unavailable.', 'tutti-frutti-cafe' ); ?>
        </p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
            <?php esc_html_e( 'Back to Home', 'tutti-frutti-cafe' ); ?>
        </a>
    </div>
</main>

<?php get_footer();
