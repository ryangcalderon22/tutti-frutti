<?php
/**
 * Homepage introduction section, v2 — product collage band.
 *
 * Layout imported from the Claude Design project "Tutti Frutti Hero" (concept 3,
 * product collage). Heading and paragraph come from the same Customizer fields as
 * v1 (template-parts/home/introduction-v1.php).
 *
 * @package Tutti_Frutti_Cafe
 */
$intro_title = get_theme_mod( 'tf_intro_title', 'More Than Frozen Yogurt' );
$intro_text  = get_theme_mod( 'tf_intro_text', 'Tutti Frutti may be known for frozen yogurt, but at our La Verne location we are introducing the next generation café that offers much more. Order online or stop in for lunch, an afternoon coffee, dessert, or an easy meal with family and friends. Choose from gourmet sandwiches, pasta and savory bites, specialty coffee and drinks, gourmet cookies and desserts, açaí bowls, smoothies, matcha, and the frozen yogurt you already know and love.' );

if ( ! $intro_title && ! $intro_text ) {
    return;
}

$intro_img_base = get_template_directory_uri() . '/assets/images/intro/';

/**
 * Collage artwork: slug => array( width, height ). Slugs match the design project's
 * img/ filenames; position and size per breakpoint live in assets/css/home.css.
 *
 * Purely decorative — the paragraph beside them already names everything on the
 * menu, so they carry empty alt text and the wrapper is hidden from assistive tech.
 * Subjects, in order: frozen yogurt cups, gourmet cookies, soft pretzel with cheese
 * dip, crispy chicken sandwich, strawberry smoothie, iced mocha, waffle fries,
 * dessert platter, chicken pretzel-bun sandwich.
 */
$intro_images = array(
    'froyo'            => array( 512, 512 ),
    'cookies'          => array( 384, 384 ),
    'pretzel'          => array( 384, 384 ),
    'chicken-sandwich' => array( 384, 384 ),
    'smoothie'         => array( 384, 384 ),
    'coffee'           => array( 384, 384 ),
    'fries'            => array( 384, 384 ),
    'desserts'         => array( 640, 358 ),
    'pretzel-sandwich' => array( 448, 448 ),
);
?>
<section class="home-section home-intro home-intro--collage">
    <div class="home-intro__band">
        <div class="home-intro__art" aria-hidden="true">
            <?php foreach ( $intro_images as $slug => $img ) : ?>
                <picture class="home-intro__item home-intro__item--<?php echo esc_attr( $slug ); ?>">
                    <source srcset="<?php echo esc_url( $intro_img_base . $slug . '.webp' ); ?>" type="image/webp">
                    <img src="<?php echo esc_url( $intro_img_base . $slug . '.png' ); ?>"
                         alt=""
                         width="<?php echo esc_attr( $img[0] ); ?>"
                         height="<?php echo esc_attr( $img[1] ); ?>"
                         loading="lazy" decoding="async">
                </picture>
            <?php endforeach; ?>
        </div>
        <div class="home-intro__content">
            <?php if ( $intro_title ) : ?>
                <h2 class="home-intro__title"><?php echo esc_html( $intro_title ); ?></h2>
            <?php endif; ?>
            <?php if ( $intro_text ) : ?>
                <p class="home-intro__text"><?php echo esc_html( $intro_text ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>
