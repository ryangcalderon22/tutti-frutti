<?php
/**
 * LocalBusiness (CafeOrCoffeeShop) JSON-LD structured data.
 *
 * Yoast SEO (free) only outputs generic Organization/WebSite schema — it
 * does not include LocalBusiness data unless the paid Local SEO add-on is
 * active. This adds a standalone LocalBusiness JSON-LD block so search
 * engines get proper address/hours/phone data for the business.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Customizer settings for the schema fields.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function tutti_frutti_customizer_schema( $wp_customize ) {
    $wp_customize->add_section(
        'tf_schema_settings',
        array(
            'title'    => __( 'Local Business Schema (SEO)', 'tutti-frutti-cafe' ),
            'priority' => 39,
        )
    );

    $wp_customize->add_setting( 'tf_schema_enable', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'tf_schema_enable', array(
        'label'       => __( 'Output LocalBusiness structured data (JSON-LD)', 'tutti-frutti-cafe' ),
        'description' => __( 'Turn off if you add the Yoast Local SEO add-on (or another plugin) that already provides this, to avoid duplicate listings.', 'tutti-frutti-cafe' ),
        'section'     => 'tf_schema_settings',
        'type'        => 'checkbox',
    ) );

    $fields = array(
        'tf_schema_street'              => array(
            'label'   => __( 'Street address', 'tutti-frutti-cafe' ),
            'default' => '2357 Foothill Blvd',
        ),
        'tf_schema_city'                => array(
            'label'   => __( 'City', 'tutti-frutti-cafe' ),
            'default' => 'La Verne',
        ),
        'tf_schema_state'               => array(
            'label'   => __( 'State (abbreviation)', 'tutti-frutti-cafe' ),
            'default' => 'CA',
        ),
        'tf_schema_zip'                 => array(
            'label'   => __( 'Zip code', 'tutti-frutti-cafe' ),
            'default' => '91750',
        ),
        'tf_schema_phone'               => array(
            'label'   => __( 'Phone (schema format, e.g. +1-909-245-1383)', 'tutti-frutti-cafe' ),
            'default' => '+1-909-245-1383',
        ),
        'tf_schema_price'               => array(
            'label'   => __( 'Price range (e.g. $ or $$)', 'tutti-frutti-cafe' ),
            'default' => '$$',
        ),
        'tf_schema_cuisine'             => array(
            'label'   => __( 'Cuisine / specialties (comma-separated)', 'tutti-frutti-cafe' ),
            'default' => 'Frozen Yogurt, Açaí, Coffee, Chai, Bakery, Sandwiches, Pasta, Pizza',
        ),
        'tf_schema_hours_weekday_open'  => array(
            'label'   => __( 'Weekday (Mon–Thu) opening time — 24hr format, e.g. 10:00', 'tutti-frutti-cafe' ),
            'default' => '10:00',
        ),
        'tf_schema_hours_weekday_close' => array(
            'label'   => __( 'Weekday (Mon–Thu) closing time — 24hr format, e.g. 21:00', 'tutti-frutti-cafe' ),
            'default' => '21:00',
        ),
        'tf_schema_hours_weekend_open'  => array(
            'label'   => __( 'Weekend (Fri–Sun) opening time — 24hr format, e.g. 10:00', 'tutti-frutti-cafe' ),
            'default' => '10:00',
        ),
        'tf_schema_hours_weekend_close' => array(
            'label'   => __( 'Weekend (Fri–Sun) closing time — 24hr format, e.g. 22:00', 'tutti-frutti-cafe' ),
            'default' => '22:00',
        ),
    );

    foreach ( $fields as $id => $args ) {
        $wp_customize->add_setting(
            $id,
            array(
                'default'           => $args['default'],
                'sanitize_callback' => 'sanitize_text_field',
            )
        );
        $wp_customize->add_control(
            $id,
            array(
                'label'   => $args['label'],
                'section' => 'tf_schema_settings',
                'type'    => 'text',
            )
        );
    }
}
add_action( 'customize_register', 'tutti_frutti_customizer_schema' );

/**
 * Build the openingHoursSpecification array from Customizer settings.
 *
 * @return array[]
 */
function tutti_frutti_schema_opening_hours() {
    return array(
        array(
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday' ),
            'opens'     => get_theme_mod( 'tf_schema_hours_weekday_open', '10:00' ),
            'closes'    => get_theme_mod( 'tf_schema_hours_weekday_close', '21:00' ),
        ),
        array(
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => array( 'Friday', 'Saturday', 'Sunday' ),
            'opens'     => get_theme_mod( 'tf_schema_hours_weekend_open', '10:00' ),
            'closes'    => get_theme_mod( 'tf_schema_hours_weekend_close', '22:00' ),
        ),
    );
}

/**
 * Output the LocalBusiness JSON-LD block in wp_head.
 */
function tutti_frutti_output_local_business_schema() {
    if ( ! get_theme_mod( 'tf_schema_enable', true ) ) {
        return;
    }

    if ( ! is_front_page() && ! is_page() ) {
        return;
    }

    $same_as = array_filter(
        array(
            get_theme_mod( 'tf_social_facebook', '' ),
            get_theme_mod( 'tf_social_instagram', '' ),
            get_theme_mod( 'tf_social_tiktok', '' ),
            get_theme_mod( 'tf_social_yelp', '' ),
            get_theme_mod( 'tf_social_x', '' ),
            get_theme_mod( 'tf_social_nextdoor', '' ),
        )
    );

    $schema = array(
        '@context'   => 'https://schema.org',
        '@type'      => array( 'CafeOrCoffeeShop', 'Restaurant' ),
        'name'       => get_bloginfo( 'name' ),
        'url'        => home_url( '/' ),
        'image'      => tutti_frutti_get_page_banner( 'about' ),
        'telephone'  => get_theme_mod( 'tf_schema_phone', '+1-909-245-1383' ),
        'priceRange' => get_theme_mod( 'tf_schema_price', '$$' ),
        'address'    => array(
            '@type'           => 'PostalAddress',
            'streetAddress'   => get_theme_mod( 'tf_schema_street', '2357 Foothill Blvd' ),
            'addressLocality' => get_theme_mod( 'tf_schema_city', 'La Verne' ),
            'addressRegion'   => get_theme_mod( 'tf_schema_state', 'CA' ),
            'postalCode'      => get_theme_mod( 'tf_schema_zip', '91750' ),
            'addressCountry'  => 'US',
        ),
        'openingHoursSpecification' => tutti_frutti_schema_opening_hours(),
    );

    $cuisine_raw = get_theme_mod( 'tf_schema_cuisine', '' );
    if ( $cuisine_raw ) {
        $schema['servesCuisine'] = array_map( 'trim', explode( ',', $cuisine_raw ) );
    }

    if ( ! empty( $same_as ) ) {
        $schema['sameAs'] = array_values( $same_as );
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'tutti_frutti_output_local_business_schema', 5 );
