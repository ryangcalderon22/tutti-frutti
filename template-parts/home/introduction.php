<?php
/**
 * Homepage introduction section, v1 — centered heading + one paragraph.
 *
 * Superseded on the front page by template-parts/home/introduction.php (v2, collage).
 * Kept so the simple layout is one get_template_part() swap away in front-page.php.
 *
 * @package Tutti_Frutti_Cafe
 */
$intro_title = get_theme_mod( 'tf_intro_title', 'More Than Frozen Yogurt' );
$intro_text  = get_theme_mod( 'tf_intro_text', 'Tutti Frutti may be known for frozen yogurt, but at our La Verne location we are introducing the next generation café that offers much more. Order online or stop in for lunch, an afternoon coffee, dessert, or an easy meal with family and friends. Choose from gourmet sandwiches, pasta and savory bites, specialty coffee and drinks, gourmet cookies and desserts, açaí bowls, smoothies, matcha, and the frozen yogurt you already know and love.' );

if ( ! $intro_title && ! $intro_text ) {
    return;
}
?>
<section class="home-section home-intro">
    <div class="container home-intro__inner">
        <?php if ( $intro_title ) : ?>
            <h2 class="section-title home-intro__title"><?php echo esc_html( $intro_title ); ?></h2>
        <?php endif; ?>
        <?php if ( $intro_text ) : ?>
            <p class="home-intro__text"><?php echo esc_html( $intro_text ); ?></p>
        <?php endif; ?>
        <?php if ($intro_title || $intro_text) : ?>
            <hr class="home-intro__divider">
        <?php endif; ?>
    </div>
</section>
