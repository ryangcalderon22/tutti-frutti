<?php
/**
 * Customizer: page banners.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register customizer controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function tutti_frutti_customizer_banners( $wp_customize ) {
    $wp_customize->add_section(
        'tf_page_banners',
        array(
            'title'    => __( 'Page Images', 'tutti-frutti-cafe' ),
            'priority' => 35,
        )
    );

    $banners = array(
        'home'      => __( 'Homepage Hero', 'tutti-frutti-cafe' ),
        'about'     => __( 'About Page', 'tutti-frutti-cafe' ),
        'pio'       => __( 'Pio Coffee Hero', 'tutti-frutti-cafe' ),
        'order'     => __( 'Order Online', 'tutti-frutti-cafe' ),
        'rewards'   => __( 'Rewards Page', 'tutti-frutti-cafe' ),
        'careers'   => __( 'Careers Page', 'tutti-frutti-cafe' ),
        'franchise' => __( 'Franchise Page', 'tutti-frutti-cafe' ),
        'business-opportunity' => __( 'Business Opportunity Page', 'tutti-frutti-cafe' ),
        'contact'   => __( 'Contact Page', 'tutti-frutti-cafe' ),
        'brands'    => __( 'Brands Page', 'tutti-frutti-cafe' ),
    );

    foreach ( $banners as $key => $label ) {
        $setting_id = 'tf_banner_' . $key;
        $wp_customize->add_setting(
            $setting_id,
            array( 'sanitize_callback' => 'esc_url_raw' )
        );
        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                $setting_id,
                array(
                    'label'   => $label,
                    'section' => 'tf_page_banners',
                )
            )
        );
    }
}
add_action( 'customize_register', 'tutti_frutti_customizer_banners', 20 );
