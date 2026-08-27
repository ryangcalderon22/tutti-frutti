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
 * Banner keys and their Customizer labels.
 *
 * @return array<string, string>
 */
function tutti_frutti_banner_keys() {
    return array(
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

    foreach ( tutti_frutti_banner_keys() as $key => $label ) {
        /*
         * Attachment ID. WP_Customize_Media_Control stores the ID rather than a
         * URL, which is what lets templates render with wp_get_attachment_image()
         * and pick up Media Library alt text and srcset automatically.
         */
        $id_setting = 'tf_banner_' . $key . '_id';
        $wp_customize->add_setting(
            $id_setting,
            array( 'sanitize_callback' => 'absint' )
        );
        $wp_customize->add_control(
            new WP_Customize_Media_Control(
                $wp_customize,
                $id_setting,
                array(
                    'label'       => $label,
                    'description' => __( 'Alt text comes from the Media Library — set it there (Media → select image → Alternative Text).', 'tutti-frutti-cafe' ),
                    'section'     => 'tf_page_banners',
                    'mime_type'   => 'image',
                )
            )
        );

        /*
         * Legacy URL value. Registered without a control so anything saved
         * before the switch keeps rendering as a fallback, while editors only
         * see the picker above.
         */
        $wp_customize->add_setting(
            'tf_banner_' . $key,
            array( 'sanitize_callback' => 'esc_url_raw' )
        );
    }
}
add_action( 'customize_register', 'tutti_frutti_customizer_banners', 20 );

/**
 * Convert previously saved banner URLs into attachment IDs (one-time).
 *
 * The URL theme_mod is left in place as a fallback for anything that cannot be
 * resolved — an image outside the Media Library, or one since deleted.
 */
function tutti_frutti_migrate_banner_ids() {
    if ( get_option( 'tutti_frutti_banner_ids_migrated' ) ) {
        return;
    }

    foreach ( array_keys( tutti_frutti_banner_keys() ) as $key ) {
        if ( absint( get_theme_mod( 'tf_banner_' . $key . '_id', 0 ) ) ) {
            continue;
        }

        $url = get_theme_mod( 'tf_banner_' . $key, '' );
        if ( ! $url ) {
            continue;
        }

        // attachment_url_to_postid() only matches the original file.
        $full = preg_replace( '/-\d+x\d+(?=\.[a-z0-9]+$)/i', '', $url );
        $id   = attachment_url_to_postid( $full );
        if ( ! $id && $full !== $url ) {
            $id = attachment_url_to_postid( $url );
        }

        if ( $id ) {
            set_theme_mod( 'tf_banner_' . $key . '_id', $id );
        }
    }

    update_option( 'tutti_frutti_banner_ids_migrated', 1 );
}
add_action( 'init', 'tutti_frutti_migrate_banner_ids', 112 );
