<?php
/**
 * Footer social links with brand SVG icons.
 *
 * @package Tutti_Frutti_Cafe
 */

$social_links = array(
    array(
        'url'   => get_theme_mod( 'tf_social_facebook', 'https://www.facebook.com/profile.php?id=61574335391368&mibextid=wwXIfr' ),
        'label' => 'Facebook',
        'icon'  => 'facebook',
    ),
    array(
        'url'   => get_theme_mod( 'tf_social_instagram', 'https://www.instagram.com/tuttifrutticafeusa?igsh=NTc4MTIwNjQ2YQ%3D%3D&utm_source=qr' ),
        'label' => 'Instagram',
        'icon'  => 'instagram',
    ),
    array(
        'url'   => get_theme_mod( 'tf_social_tiktok', 'https://www.tiktok.com/@tuttifrutticafe?_r=1&_t=ZT-96shZJOo7PJ' ),
        'label' => 'TikTok',
        'icon'  => 'tiktok',
    ),
    array(
        'url'   => get_theme_mod( 'tf_social_yelp', 'https://yelp.to/ABlQYKIyYS' ),
        'label' => 'Yelp',
        'icon'  => 'yelp',
    ),
    array(
        'url'   => get_theme_mod( 'tf_social_x', 'https://x.com/tuttifrutticafe?s=21' ),
        'label' => 'X',
        'icon'  => 'x',
    ),
    array(
        'url'   => get_theme_mod( 'tf_social_nextdoor', 'https://nextdoor.com/page/tutti-frutti-cafe-la-verne-ca?share_platform=10&utm_campaign=1780423796210&share_action_id=cce158ec-6509-47fb-a00e-018bb372019b' ),
        'label' => 'Nextdoor',
        'icon'  => 'nextdoor',
    ),
);
?>
<div class="footer-social">
    <?php foreach ( $social_links as $link ) : ?>
        <?php if ( empty( $link['url'] ) ) { continue; } ?>
        <a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $link['label'] ); ?>" class="footer-social__link footer-social__link--<?php echo esc_attr( $link['icon'] ); ?>">
            <?php echo tutti_frutti_social_icon_svg( $link['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </a>
    <?php endforeach; ?>
</div>
