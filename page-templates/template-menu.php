<?php
/**
 * Template Name: Menu
 *
 * @package Tutti_Frutti_Cafe
 */

$hero = tutti_frutti_get_page_hero();

get_header();
?>

<main id="primary" class="site-main page-menu site-main--page">
    <section class="page-hero page-section--top">
        <div class="container page-hero__inner">
            <h1 class="page-title"><?php the_title(); ?></h1>

            <?php if ( $hero['desc'] ) : ?>
                <p class="page-hero__desc"><?php echo esc_html( $hero['desc'] ); ?></p>
            <?php endif; ?>

            <?php if ( ! empty( $hero['buttons'] ) ) : ?>
                <div class="page-hero__actions">
                    <?php foreach ( $hero['buttons'] as $btn ) : ?>
                        <a href="<?php echo esc_url( $btn['url'] ); ?>" class="<?php echo esc_attr( $btn['class'] ); ?>"<?php echo $btn['new_tab'] ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $btn['text'] ); ?><?php if ( $btn['new_tab'] ) : ?><span class="screen-reader-text"><?php esc_html_e( ' (opens in a new tab)', 'tutti-frutti-cafe' ); ?></span><?php endif; ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="page-section page-section--cream">
        <div class="container">
            <?php get_template_part( 'template-parts/page-editable-content' ); ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>