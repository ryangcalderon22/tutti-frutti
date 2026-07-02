<?php
/**
 * Homepage hero
 *
 * @package Tutti_Frutti_Cafe
 */
$hero_bg = tutti_frutti_get_page_banner( 'home' );

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
<section class="home-hero" style="background-image: url('<?php echo esc_url( $hero_bg ); ?>');">
    <div class="home-hero__overlay"></div>
    <div class="container home-hero__content">
        <?php tutti_frutti_the_logo( 'hero' ); ?>
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
