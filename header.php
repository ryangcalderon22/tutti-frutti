<?php
/**
 * The header for our theme
 *
 * @package Tutti_Frutti_Cafe
 */

// $order_url = tutti_frutti_page_url( 'order-online' );
$order_url = tutti_frutti_get_chownow_url();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="z6WnFhhN4jm5Zpp52JX1fR1vJnEHGFGFfFK1DcFLuEc" />
    <link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="masthead" class="site-header">
    <div class="header-inner">
        <div class="site-branding">
            <?php tutti_frutti_the_logo( is_front_page() ? 'header-home' : 'header-light' ); ?>
        </div>

        <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary', 'tutti-frutti-cafe' ); ?>">
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    )
                );
            } else {
                tutti_frutti_fallback_menu();
            }
            ?>
            <a href="<?php echo esc_url( $order_url ); ?>" class="btn-order-nav btn-order-nav--green" target="_blank">
                <?php esc_html_e( 'Order Now', 'tutti-frutti-cafe' ); ?>
            </a>
        </nav>

        <button class="hamburger" id="mobileMenuToggle" aria-label="<?php esc_attr_e( 'Toggle menu', 'tutti-frutti-cafe' ); ?>" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
