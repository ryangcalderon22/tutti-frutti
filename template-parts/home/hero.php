<?php
/**
 * Homepage hero
 *
 * @package Tutti_Frutti_Cafe
 */
$hero_bg = tutti_frutti_get_page_banner( 'home' );
$hero_id = tutti_frutti_get_page_banner_id( 'home' );

// Media Library alt text, falling back to a description of the shot.
$hero_alt = $hero_id
    ? tutti_frutti_get_attachment_alt( $hero_id, __( 'Tutti Frutti Café in La Verne', 'tutti-frutti-cafe' ) )
    : tutti_frutti_get_image_alt( $hero_bg, __( 'Tutti Frutti Café in La Verne', 'tutti-frutti-cafe' ) );

$heroEyebrow = get_theme_mod( 'tf_heroEyebrow', '' );
$hero_title   = get_theme_mod( 'tf_hero_title', '' );
$hero_tagline = get_theme_mod( 'tf_hero_tagline', '' );

$hero_buttons = array();
for ( $i = 1; $i <= 3; $i++ ) {
    $text = get_theme_mod( 'tf_hero_btn' . $i . '_text', '' );
    $url  = get_theme_mod( 'tf_hero_btn' . $i . '_url', '' );
    if ( $text && $url ) {
        $hero_buttons[] = array(
            'text'  => $text,
            'url'   => $url,
            'class' => 1 === $i ? 'btn btn-primary' : 'btn btn-tertiary',
        );
    }
}
?>
<section class="home-hero">
    <?php
    if ( $hero_id ) {
        // Picked in the Customizer: carries alt text and srcset.
        echo wp_get_attachment_image(
            $hero_id,
            'full',
            false,
            array(
                'class'         => 'home-hero__media',
                'alt'           => $hero_alt,
                'sizes'         => '100vw',
                'fetchpriority' => 'high',
                'decoding'      => 'async',
            )
        );
    } elseif ( $hero_bg ) {
        printf(
            '<img class="home-hero__media" src="%s" alt="%s" fetchpriority="high" decoding="async">',
            esc_url( $hero_bg ),
            esc_attr( $hero_alt )
        );
    }
    ?>
    <div class="home-hero__overlay"></div>
    <div class="container home-hero__content">
        <?php tutti_frutti_the_logo( 'hero' ); ?>
        <?php //if ( $heroEyebrow ) : ?>
            <!-- <span class="home-hero__eyebrow"><?php echo esc_html( $heroEyebrow ); ?></span> -->
        <?php //endif; ?>
        <?php if ( $hero_title ) : ?>
            <h1 class="home-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
        <?php endif; ?>
        <?php if ( $hero_tagline ) : ?>
            <span class="home-hero__tagline"><?php echo esc_html( $hero_tagline ); ?></span>
        <?php endif; ?>
        <?php if ( ! empty( $hero_buttons ) ) : ?>
            <div class="home-hero__actions">
                <?php foreach ( $hero_buttons as $btn ) : ?>
                    <a href="<?php echo esc_url( $btn['url'] ); ?>" class="<?php echo esc_attr( $btn['class'] ); ?>"><?php echo esc_html( $btn['text'] ); ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
