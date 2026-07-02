<?php
/**
 * Template Name: FAQs
 *
 * @package Tutti_Frutti_Cafe
 */
get_header();
?>

<main id="primary" class="site-main page-faqs site-main--page">
    <section class="page-hero page-hero--brown page-section--top">
        <div class="container">
            <h1 class="page-hero__title"><?php the_title(); ?></h1>
        </div>
    </section>

    <section class="page-section page-section--cream faqs-section">
        <div class="container">
            <?php
            while ( have_posts() ) :
                the_post();
                if ( get_the_content() ) {
                    echo '<div class="faqs-intro entry-content">';
                    the_content();
                    echo '</div>';
                }
            endwhile;
            rewind_posts();

            $faqs = tutti_frutti_get_faqs();
            if ( empty( $faqs ) ) :
                ?>
                <p><?php esc_html_e( 'No FAQs published yet. Add them under FAQs in the WordPress admin.', 'tutti-frutti-cafe' ); ?></p>
            <?php else : ?>
                <div class="faqs-accordion" role="list">
                    <?php foreach ( $faqs as $index => $faq ) : ?>
                        <details class="faq-item" role="listitem"<?php echo 0 === $index ? ' open' : ''; ?>>
                            <summary class="faq-item__question"><?php echo esc_html( $faq->post_title ); ?></summary>
                            <div class="faq-item__answer entry-content">
                                <?php echo wp_kses_post( wpautop( $faq->post_content ) ); ?>
                            </div>
                        </details>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
