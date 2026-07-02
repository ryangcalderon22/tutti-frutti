<?php
/**
 * Customizer: site content, logos, homepage text, footer badges.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function tutti_frutti_customizer_content( $wp_customize ) {
    $wp_customize->add_section(
        'tf_site_content',
        array(
            'title'    => __( 'Site Content', 'tutti-frutti-cafe' ),
            'priority' => 35,
        )
    );

    $wp_customize->add_setting(
        'tf_logo_on_dark',
        array(
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'tf_logo_on_dark',
            array(
                'label'       => __( 'Logo on Dark Background (homepage hero)', 'tutti-frutti-cafe' ),
                'description' => __( 'Light/white logo for hero. Banner image should be photo only — no logo in the image.', 'tutti-frutti-cafe' ),
                'section'     => 'tf_site_content',
            )
        )
    );

    $wp_customize->add_setting(
        'tf_logo_on_light',
        array(
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'tf_logo_on_light',
            array(
                'label'       => __( 'Logo on Light Background (header scroll & inner pages)', 'tutti-frutti-cafe' ),
                'description' => __( 'Colored logo for white/cream header. Falls back to Site Identity logo.', 'tutti-frutti-cafe' ),
                'section'     => 'tf_site_content',
            )
        )
    );

    $text_fields = array(
        'tf_hero_title'    => array( 'label' => __( 'Homepage hero title', 'tutti-frutti-cafe' ), 'default' => '' ),
        'tf_hero_tagline'  => array( 'label' => __( 'Homepage hero tagline', 'tutti-frutti-cafe' ), 'default' => '' ),
        'tf_moments_title' => array( 'label' => __( 'Moments section title', 'tutti-frutti-cafe' ), 'default' => '' ),
        'tf_moments_text'  => array( 'label' => __( 'Moments section text', 'tutti-frutti-cafe' ), 'default' => '', 'type' => 'textarea' ),
        'tf_promo1_title'  => array( 'label' => __( 'Promo 1 title', 'tutti-frutti-cafe' ), 'default' => 'Late Nights. Good Vibes. Great Treats.' ),
        'tf_promo1_btn'    => array( 'label' => __( 'Promo 1 button text', 'tutti-frutti-cafe' ), 'default' => 'Explore Midnight Menu' ),
        'tf_promo1_url'    => array( 'label' => __( 'Promo 1 link URL', 'tutti-frutti-cafe' ), 'default' => '' ),
        'tf_promo2_title'  => array( 'label' => __( 'Promo 2 title', 'tutti-frutti-cafe' ), 'default' => 'High Tea. Good Company. Timeless Tradition.' ),
        'tf_promo2_btn'    => array( 'label' => __( 'Promo 2 button text', 'tutti-frutti-cafe' ), 'default' => 'Discover High Tea' ),
        'tf_promo2_url'    => array( 'label' => __( 'Promo 2 link URL', 'tutti-frutti-cafe' ), 'default' => '' ),
        'tf_hero_btn1_text' => array( 'label' => __( 'Hero button 1 text', 'tutti-frutti-cafe' ), 'default' => '' ),
        'tf_hero_btn1_url'  => array( 'label' => __( 'Hero button 1 URL', 'tutti-frutti-cafe' ), 'default' => '' ),
        'tf_hero_btn2_text' => array( 'label' => __( 'Hero button 2 text', 'tutti-frutti-cafe' ), 'default' => '' ),
        'tf_hero_btn2_url'  => array( 'label' => __( 'Hero button 2 URL', 'tutti-frutti-cafe' ), 'default' => '' ),
        'tf_hero_btn3_text' => array( 'label' => __( 'Hero button 3 text', 'tutti-frutti-cafe' ), 'default' => '' ),
        'tf_hero_btn3_url'  => array( 'label' => __( 'Hero button 3 URL', 'tutti-frutti-cafe' ), 'default' => '' ),
    );

    foreach ( $text_fields as $id => $args ) {
        $wp_customize->add_setting(
            $id,
            array(
                'default'           => $args['default'],
                'sanitize_callback' => ( isset( $args['type'] ) && 'textarea' === $args['type'] ) ? 'sanitize_textarea_field' : 'sanitize_text_field',
            )
        );
        $wp_customize->add_control(
            $id,
            array(
                'label'   => $args['label'],
                'section' => 'tf_site_content',
                'type'    => isset( $args['type'] ) ? $args['type'] : 'text',
            )
        );
    }

    $wp_customize->add_setting( 'tf_moments_image', array( 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'tf_moments_image',
            array(
                'label'   => __( 'Moments section image', 'tutti-frutti-cafe' ),
                'section' => 'tf_site_content',
            )
        )
    );

    $wp_customize->add_setting( 'tf_promo1_image', array( 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control(
        new WP_Customize_Image_Control( $wp_customize, 'tf_promo1_image', array( 'label' => __( 'Promo 1 image', 'tutti-frutti-cafe' ), 'section' => 'tf_site_content' ) )
    );

    $wp_customize->add_setting( 'tf_promo2_image', array( 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control(
        new WP_Customize_Image_Control( $wp_customize, 'tf_promo2_image', array( 'label' => __( 'Promo 2 image', 'tutti-frutti-cafe' ), 'section' => 'tf_site_content' ) )
    );

    $social_defaults = array(
        'tf_social_facebook'  => 'https://www.facebook.com/profile.php?id=61574335391368&mibextid=wwXIfr',
        'tf_social_instagram' => 'https://www.instagram.com/tuttifrutticafeusa?igsh=NTc4MTIwNjQ2YQ%3D%3D&utm_source=qr',
        'tf_social_tiktok'    => 'https://www.tiktok.com/@tuttifrutticafe?_r=1&_t=ZT-96shZJOo7PJ',
        'tf_social_yelp'      => 'https://yelp.to/ABlQYKIyYS',
        'tf_social_x'         => 'https://x.com/tuttifrutticafe?s=21',
        'tf_social_nextdoor'  => 'https://nextdoor.com/page/tutti-frutti-cafe-la-verne-ca?share_platform=10&utm_campaign=1780423796210&share_action_id=cce158ec-6509-47fb-a00e-018bb372019b',
    );
    foreach ( $social_defaults as $id => $default ) {
        $wp_customize->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'esc_url_raw' ) );
        $wp_customize->add_control( $id, array(
            'label'   => sprintf( __( 'Social link: %s', 'tutti-frutti-cafe' ), str_replace( 'tf_social_', '', $id ) ),
            'section' => 'footer_section',
            'type'    => 'url',
        ) );
    }

    for ( $i = 1; $i <= 4; $i++ ) {
        $defaults = array(
            1 => 'Multi-Brand Café Experience',
            2 => 'Premium Ingredients. Made Fresh. Made to Satisfy.',
            3 => 'Halal Certified',
            4 => 'Family Owned & Community Focused',
        );
        $id = 'tf_footer_badge_' . $i;
        $wp_customize->add_setting(
            $id,
            array(
                'default'           => $defaults[ $i ],
                'sanitize_callback' => 'sanitize_text_field',
            )
        );
        $wp_customize->add_control(
            $id,
            array(
                'label'   => sprintf( __( 'Footer badge %d label', 'tutti-frutti-cafe' ), $i ),
                'section' => 'footer_section',
            )
        );
    }

    $wp_customize->add_setting(
        'tf_admin_email',
        array(
            'default'           => get_option( 'admin_email' ),
            'sanitize_callback' => 'sanitize_email',
        )
    );
    $wp_customize->add_control(
        'tf_admin_email',
        array(
            'label'       => __( 'Form notification email (admin)', 'tutti-frutti-cafe' ),
            'section'     => 'title_tagline',
            'type'        => 'email',
            'description' => __( 'Contact form submissions are sent here.', 'tutti-frutti-cafe' ),
        )
    );

    $wp_customize->add_setting(
        'tf_contact_customer_subject',
        array(
            'default'           => __( 'We received your message — Tutti Frutti Café', 'tutti-frutti-cafe' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'tf_contact_customer_subject',
        array(
            'label'   => __( 'Customer confirmation email subject', 'tutti-frutti-cafe' ),
            'section' => 'title_tagline',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'tf_contact_customer_body',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );
    $wp_customize->add_control(
        'tf_contact_customer_body',
        array(
            'label'       => __( 'Customer confirmation email body (optional)', 'tutti-frutti-cafe' ),
            'description' => __( 'Leave empty for default thank-you message including their message text.', 'tutti-frutti-cafe' ),
            'section'     => 'title_tagline',
            'type'        => 'textarea',
        )
    );
}
add_action( 'customize_register', 'tutti_frutti_customizer_content', 25 );

/**
 * Get theme mod with default.
 *
 * @param string $key Setting key.
 * @param string $default Default.
 * @return string
 */
function tutti_frutti_get_content( $key, $default = '' ) {
    $val = get_theme_mod( $key, $default );
    return $val ? $val : $default;
}
