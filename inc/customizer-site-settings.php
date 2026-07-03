<?php
/**
 * Customizer: homepage toggles, emails, ChowNow.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function tutti_frutti_customizer_site_settings( $wp_customize ) {
    $wp_customize->add_section(
        'tf_homepage_settings',
        array(
            'title'    => __( 'Homepage Settings', 'tutti-frutti-cafe' ),
            'priority' => 34,
        )
    );

    $wp_customize->add_setting( 'tf_hero_min_height', array(
        'default'           => '30vh',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_hero_min_height', array(
        'label'       => __( 'Hero minimum height (e.g. 30vh)', 'tutti-frutti-cafe' ),
        'section'     => 'tf_homepage_settings',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'tf_grab_go_title', array(
        'default'           => 'Grab & Go',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_grab_go_title', array(
        'label'   => __( 'Grab & Go subtitle (below Featured Treats)', 'tutti-frutti-cafe' ),
        'section' => 'tf_homepage_settings',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'tf_featured_treats_title', array(
        'default'           => 'Featured Treats',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_featured_treats_title', array(
        'label'   => __( 'Featured Treats section title (above slider)', 'tutti-frutti-cafe' ),
        'section' => 'tf_homepage_settings',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'tf_careers_form_embed', array(
        'default'           => 'https://forms.cloud.microsoft/Pages/ResponsePage.aspx?id=QGI0uOtd9ECqpEQtKFKl0yOc0rvlMUBIogqH86u6DuhURDhQWTVBM1lBRzBIMU5FUDY3WjdINUxaSi4u&embed=true',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'tf_careers_form_embed', array(
        'label'       => __( 'Careers application form embed URL', 'tutti-frutti-cafe' ),
        'description' => __( 'Microsoft Forms or other embed URL for the Careers page.', 'tutti-frutti-cafe' ),
        'section'     => 'tf_homepage_settings',
        'type'        => 'url',
    ) );

    $wp_customize->add_setting( 'tf_slider_group', array(
        'default'           => 'grab-and-go',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_slider_group', array(
        'label'       => __( 'TF Slides group slug', 'tutti-frutti-cafe' ),
        'description' => __( 'Create slides in TF Slides with this exact group name.', 'tutti-frutti-cafe' ),
        'section'     => 'tf_homepage_settings',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'tf_slider_count', array(
        'default'           => 10,
        'sanitize_callback' => function( $value ) {
            $n = absint( $value );
            return $n > 0 ? min( 50, $n ) : 10;
        },
    ) );
    $wp_customize->add_control( 'tf_slider_count', array(
        'label'       => __( 'Featured Treats slide count (max 50)', 'tutti-frutti-cafe' ),
        'section'     => 'tf_homepage_settings',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 1, 'max' => 50, 'step' => 1 ),
    ) );

    $wp_customize->add_setting( 'tf_moments_min_height', array(
        'default'           => '420px',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_moments_min_height', array(
        'label'       => __( 'Moments section minimum height (e.g. 420px)', 'tutti-frutti-cafe' ),
        'section'     => 'tf_homepage_settings',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'tf_careers_embed_width', array(
        'default'           => '100%',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_careers_embed_width', array(
        'label'       => __( 'Careers form embed width (e.g. 100% or 960px)', 'tutti-frutti-cafe' ),
        'section'     => 'tf_homepage_settings',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'tf_careers_embed_height', array(
        'default'           => '720px',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_careers_embed_height', array(
        'label'       => __( 'Careers form embed height (e.g. 720px)', 'tutti-frutti-cafe' ),
        'section'     => 'tf_homepage_settings',
        'type'        => 'text',
    ) );

    $wp_customize->add_section(
        'tf_footer_settings',
        array(
            'title'    => __( 'Footer Settings', 'tutti-frutti-cafe' ),
            'priority' => 37,
        )
    );

    $wp_customize->add_setting( 'tf_footer_padding', array(
        'default'           => '40px',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_footer_padding', array(
        'label'       => __( 'Footer main padding (e.g. 40px)', 'tutti-frutti-cafe' ),
        'description' => __( 'Controls vertical padding of the dark footer block.', 'tutti-frutti-cafe' ),
        'section'     => 'tf_footer_settings',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'tf_show_promos', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'tf_show_promos', array(
        'label'   => __( 'Show Late Night & High Tea promos on homepage', 'tutti-frutti-cafe' ),
        'section' => 'tf_homepage_settings',
        'type'    => 'checkbox',
    ) );

    $wp_customize->add_setting( 'tf_show_brand_card_button', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'tf_show_brand_card_button', array(
        'label'       => __( 'Show Explore button on brand cards', 'tutti-frutti-cafe' ),
        'description' => __( 'Off by default. When on, each card shows the button text set under Brands → Card button text.', 'tutti-frutti-cafe' ),
        'section'     => 'tf_homepage_settings',
        'type'        => 'checkbox',
    ) );

    $wp_customize->add_setting( 'tf_show_footer_badges', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'tf_show_footer_badges', array(
        'label'   => __( 'Show footer badges strip (Multi-Brand, Halal, etc.)', 'tutti-frutti-cafe' ),
        'section' => 'tf_homepage_settings',
        'type'    => 'checkbox',
    ) );

    $wp_customize->add_setting( 'tf_hide_about_values', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'tf_hide_about_values', array(
        'label'   => __( 'Hide icon/value cards on About page', 'tutti-frutti-cafe' ),
        'section' => 'tf_homepage_settings',
        'type'    => 'checkbox',
    ) );

    $wp_customize->add_section(
        'tf_email_settings',
        array(
            'title'    => __( 'Email Settings', 'tutti-frutti-cafe' ),
            'priority' => 36,
        )
    );

    $wp_customize->add_setting( 'tf_email_contact_admins', array(
        'default'           => get_option( 'admin_email' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_email_contact_admins', array(
        'label'       => __( 'Contact form — admin To email(s)', 'tutti-frutti-cafe' ),
        'description' => __( 'Comma-separated primary recipients.', 'tutti-frutti-cafe' ),
        'section'     => 'tf_email_settings',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'tf_email_contact_cc', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_email_contact_cc', array(
        'label'       => __( 'Contact form — CC email(s)', 'tutti-frutti-cafe' ),
        'description' => __( 'Comma-separated. These addresses receive a copy of each contact submission.', 'tutti-frutti-cafe' ),
        'section'     => 'tf_email_settings',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'tf_contact_customer_email', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'tf_contact_customer_email', array(
        'label'   => __( 'Contact form — send confirmation to customer', 'tutti-frutti-cafe' ),
        'section' => 'tf_email_settings',
        'type'    => 'checkbox',
    ) );

    $wp_customize->add_setting( 'tf_email_careers_admins', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_email_careers_admins', array(
        'label'       => __( 'Careers applications — admin email(s)', 'tutti-frutti-cafe' ),
        'description' => __( 'Comma-separated. Empty = same as contact admins.', 'tutti-frutti-cafe' ),
        'section'     => 'tf_email_settings',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'tf_careers_applicant_email', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'tf_careers_applicant_email', array(
        'label'   => __( 'Careers form — send confirmation to applicant', 'tutti-frutti-cafe' ),
        'section' => 'tf_email_settings',
        'type'    => 'checkbox',
    ) );

    $wp_customize->add_setting( 'tf_chownow_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'tf_chownow_url', array(
        'label'       => __( 'ChowNow / default order URL', 'tutti-frutti-cafe' ),
        'description' => __( 'Used for brand product Order Now links unless overridden per product.', 'tutti-frutti-cafe' ),
        'section'     => 'tf_email_settings',
        'type'        => 'url',
    ) );

    $wp_customize->add_section(
        'tf_recaptcha_settings',
        array(
            'title'    => __( 'reCAPTCHA Settings', 'tutti-frutti-cafe' ),
            'priority' => 38,
        )
    );

    $wp_customize->add_setting( 'tf_recaptcha_site_key', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_recaptcha_site_key', array(
        'label'       => __( 'reCAPTCHA v2 Site Key', 'tutti-frutti-cafe' ),
        'description' => __( 'From google.com/recaptcha/admin — Checkbox ("I\'m not a robot") type. Leave blank to disable captcha.', 'tutti-frutti-cafe' ),
        'section'     => 'tf_recaptcha_settings',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'tf_recaptcha_secret_key', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tf_recaptcha_secret_key', array(
        'label'       => __( 'reCAPTCHA v2 Secret Key', 'tutti-frutti-cafe' ),
        'description' => __( 'Kept server-side, used to verify submissions.', 'tutti-frutti-cafe' ),
        'section'     => 'tf_recaptcha_settings',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'tf_custom_css_extra', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ) );
    $wp_customize->add_control( 'tf_custom_css_extra', array(
        'label'   => __( 'Extra custom CSS', 'tutti-frutti-cafe' ),
        'section' => 'tf_homepage_settings',
        'type'    => 'textarea',
    ) );
}
add_action( 'customize_register', 'tutti_frutti_customizer_site_settings', 30 );

/**
 * Output dynamic CSS variables for page heroes.
 */
function tutti_frutti_inline_hero_vars() {
    $careers = esc_url( tutti_frutti_get_page_banner( 'careers' ) );
    $rewards = esc_url( tutti_frutti_get_page_banner( 'rewards' ) );
    $hero_h      = sanitize_text_field( get_theme_mod( 'tf_hero_min_height', '30vh' ) );
    $moments_h   = sanitize_text_field( get_theme_mod( 'tf_moments_min_height', '420px' ) );
    $footer_pad  = sanitize_text_field( get_theme_mod( 'tf_footer_padding', '40px' ) );
    $embed_w     = sanitize_text_field( get_theme_mod( 'tf_careers_embed_width', '100%' ) );
    $embed_h     = sanitize_text_field( get_theme_mod( 'tf_careers_embed_height', '720px' ) );
    $extra       = get_theme_mod( 'tf_custom_css_extra', '' );

    echo '<style id="tutti-frutti-vars">';
    echo ':root{';
    echo '--tf-careers-hero-bg:url("' . $careers . '");';
    echo '--tf-rewards-hero-bg:url("' . $rewards . '");';
    echo '--tf-hero-min-height:' . esc_attr( $hero_h ) . ';';
    echo '--tf-moments-min-height:' . esc_attr( $moments_h ) . ';';
    echo '--tf-footer-padding:' . esc_attr( $footer_pad ) . ';';
    echo '--tf-careers-embed-width:' . esc_attr( $embed_w ) . ';';
    echo '--tf-careers-embed-height:' . esc_attr( $embed_h ) . ';';
    echo '}';
    if ( $extra ) {
        echo wp_strip_all_tags( $extra );
    }
    echo '</style>';
}
add_action( 'wp_head', 'tutti_frutti_inline_hero_vars', 99 );