<?php
/**
 * Google Analytics (GA4) tracking snippet.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Customizer settings for Google Analytics.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function tutti_frutti_customizer_analytics( $wp_customize ) {
    $wp_customize->add_section(
        'tf_analytics_settings',
        array(
            'title'    => __( 'Analytics & Tracking', 'tutti-frutti-cafe' ),
            'priority' => 40,
        )
    );

    $wp_customize->add_setting( 'tf_ga_measurement_id', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_ga_measurement_id', array(
        'label'       => __( 'Google Analytics Measurement ID', 'tutti-frutti-cafe' ),
        'description' => __( 'From analytics.google.com — looks like G-XXXXXXXXXX. Leave blank to disable tracking.', 'tutti-frutti-cafe' ),
        'section'     => 'tf_analytics_settings',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'tf_ga_exclude_admins', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'tf_ga_exclude_admins', array(
        'label'       => __( 'Don\'t track logged-in Administrators', 'tutti-frutti-cafe' ),
        'description' => __( 'Recommended — keeps your own visits while working on the site out of the analytics data.', 'tutti-frutti-cafe' ),
        'section'     => 'tf_analytics_settings',
        'type'        => 'checkbox',
    ) );
}
add_action( 'customize_register', 'tutti_frutti_customizer_analytics' );

/**
 * Output the GA4 gtag.js snippet in wp_head.
 */
function tutti_frutti_output_google_analytics() {
    $measurement_id = get_theme_mod( 'tf_ga_measurement_id', '' );

    if ( ! $measurement_id ) {
        return;
    }

    if ( get_theme_mod( 'tf_ga_exclude_admins', true ) && current_user_can( 'manage_options' ) ) {
        return;
    }

    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $measurement_id ); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo esc_js( $measurement_id ); ?>');
    </script>
    <?php
}
add_action( 'wp_head', 'tutti_frutti_output_google_analytics', 1 );
