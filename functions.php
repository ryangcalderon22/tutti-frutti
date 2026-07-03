<?php
/**
 * Tutti Frutti Cafe Theme Functions
 * 
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once get_template_directory() . '/inc/demo-images.php';
require_once get_template_directory() . '/inc/brands-config.php';
require_once get_template_directory() . '/inc/assets-helper.php';
require_once get_template_directory() . '/inc/email-helper.php';
require_once get_template_directory() . '/inc/email-log.php';
require_once get_template_directory() . '/inc/smtp-settings.php';
require_once get_template_directory() . '/inc/customizer-banners.php';
require_once get_template_directory() . '/inc/customizer-content.php';
require_once get_template_directory() . '/inc/customizer-site-settings.php';
require_once get_template_directory() . '/inc/cpt-brands.php';
require_once get_template_directory() . '/inc/cpt-product-categories.php';
require_once get_template_directory() . '/inc/cpt-menu-items.php';
require_once get_template_directory() . '/inc/contact-form.php';
require_once get_template_directory() . '/inc/careers-form.php';
require_once get_template_directory() . '/inc/cpt-faq.php';
require_once get_template_directory() . '/inc/cpt-jobs.php';
require_once get_template_directory() . '/inc/cpt-page-sections.php';
require_once get_template_directory() . '/inc/theme-setup.php';

/**
 * Theme Setup
 */
function tutti_frutti_setup() {
    // Add theme support
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 50,
            'width'       => 180,
            'flex-height' => true,
            'flex-width'  => true,
        )
    );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'woocommerce' );
    
    // Register navigation menus
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'tutti-frutti-cafe' ),
        'footer'  => esc_html__( 'Footer Menu', 'tutti-frutti-cafe' ),
    ) );
}
add_action( 'after_setup_theme', 'tutti_frutti_setup' );

/**
 * Register page templates from page-templates directory.
 *
 * @param array $templates Existing templates.
 * @return array
 */
function tutti_frutti_register_page_templates( $templates ) {
    $dir = get_template_directory() . '/page-templates/';
    if ( ! is_dir( $dir ) ) {
        return $templates;
    }

    $files = glob( $dir . 'template-*.php' );
    foreach ( $files as $file ) {
        $headers = get_file_data(
            $file,
            array( 'Template Name' => 'Template Name' )
        );
        if ( ! empty( $headers['Template Name'] ) ) {
            $templates[ 'page-templates/' . basename( $file ) ] = $headers['Template Name'];
        }
    }

    return $templates;
}
add_filter( 'theme_page_templates', 'tutti_frutti_register_page_templates' );

/**
 * Load page templates from page-templates subdirectory.
 *
 * @param string $template Template path.
 * @return string
 */
function tutti_frutti_load_page_template( $template ) {
    if ( ! is_page() ) {
        return $template;
    }

    $slug = get_page_template_slug();
    if ( $slug && 0 === strpos( $slug, 'page-templates/' ) ) {
        $file = get_template_directory() . '/' . $slug;
        if ( file_exists( $file ) ) {
            return $file;
        }
    }

    return $template;
}
add_filter( 'page_template', 'tutti_frutti_load_page_template' );

/**
 * Enqueue Styles and Scripts
 */
function tutti_frutti_enqueue_scripts() {
    $version = '1.7.0';

    wp_enqueue_style( 'tutti-frutti-style', get_stylesheet_uri(), array(), $version );

    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Dancing+Script:wght@500;600&family=Oswald:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    $page_deps = array( 'tutti-frutti-style' );

    if ( is_front_page() ) {
        wp_enqueue_style(
            'tutti-frutti-home',
            get_template_directory_uri() . '/assets/css/home.css',
            array( 'tutti-frutti-style' ),
            $version
        );
        $page_deps[] = 'tutti-frutti-home';
    }

    if ( is_page() || is_singular( 'tf_brand' ) ) {
        wp_enqueue_style(
            'tutti-frutti-pages',
            get_template_directory_uri() . '/assets/css/pages.css',
            array( 'tutti-frutti-style' ),
            $version
        );
        $page_deps[] = 'tutti-frutti-pages';
    }

    wp_enqueue_style(
        'tutti-frutti-custom',
        get_template_directory_uri() . '/assets/css/custom.css',
        $page_deps,
        $version
    );

    // Main JavaScript
    wp_enqueue_script( 'tutti-frutti-script', get_template_directory_uri() . '/js/main.js', array(), $version, true );

    wp_enqueue_script( 'mobile-menu', get_template_directory_uri() . '/js/mobile-menu.js', array(), $version, true );

    if ( is_front_page() ) {
        wp_enqueue_script( 'header-scroll', get_template_directory_uri() . '/js/header-scroll.js', array(), $version, true );
    }

    if ( is_singular( 'tf_brand' ) ) {
        wp_enqueue_script( 'brand-products', get_template_directory_uri() . '/js/brand-products.js', array(), $version, true );
    }

    if ( is_page_template( 'page-templates/template-contact.php' ) ) {
        $recaptcha_site_key = get_theme_mod( 'tf_recaptcha_site_key', '' );
        if ( $recaptcha_site_key ) {
            wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'tutti_frutti_enqueue_scripts' );

/**
 * Register Widget Areas
 */
function tutti_frutti_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Primary Sidebar', 'tutti-frutti-cafe' ),
        'id'            => 'primary-sidebar',
        'description'   => esc_html__( 'Main sidebar for pages and posts', 'tutti-frutti-cafe' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 1', 'tutti-frutti-cafe' ),
        'id'            => 'footer-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );
    
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 2', 'tutti-frutti-cafe' ),
        'id'            => 'footer-2',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );
    
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 3', 'tutti-frutti-cafe' ),
        'id'            => 'footer-3',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'tutti_frutti_widgets_init' );

/**
 * Custom Logo
 */
function tutti_frutti_custom_logo() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $html = sprintf(
        '<a href="%1$s" class="custom-logo-link">%2$s</a>',
        esc_url( home_url( '/' ) ),
        wp_get_attachment_image( $custom_logo_id, 'full' )
    );
    return $html;
}

/**
 * Customize Excerpt Length
 */
function tutti_frutti_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'tutti_frutti_excerpt_length' );

/**
 * Customize Excerpt More
 */
function tutti_frutti_excerpt_more( $more ) {
    return ' ... <a href="' . get_permalink() . '" class="read-more">' . esc_html__( 'Read More', 'tutti-frutti-cafe' ) . '</a>';
}
add_filter( 'excerpt_more', 'tutti_frutti_excerpt_more' );

/**
 * Add Custom Post Types
 */
function tutti_frutti_register_post_types() {
    // Team Members
    register_post_type( 'team', array(
        'labels' => array(
            'name'               => esc_html__( 'Team Members', 'tutti-frutti-cafe' ),
            'singular_name'      => esc_html__( 'Team Member', 'tutti-frutti-cafe' ),
            'add_new'            => esc_html__( 'Add New Team Member', 'tutti-frutti-cafe' ),
            'add_new_item'       => esc_html__( 'Add New Team Member', 'tutti-frutti-cafe' ),
            'edit_item'          => esc_html__( 'Edit Team Member', 'tutti-frutti-cafe' ),
        ),
        'public'      => true,
        'has_archive' => false,
        'supports'    => array( 'title', 'editor', 'thumbnail' ),
        'show_in_rest' => true,
    ) );
    
    // Testimonials
    register_post_type( 'testimonial', array(
        'labels' => array(
            'name'               => esc_html__( 'Testimonials', 'tutti-frutti-cafe' ),
            'singular_name'      => esc_html__( 'Testimonial', 'tutti-frutti-cafe' ),
            'add_new'            => esc_html__( 'Add Testimonial', 'tutti-frutti-cafe' ),
        ),
        'public'      => true,
        'has_archive' => false,
        'supports'    => array( 'title', 'editor', 'thumbnail' ),
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'tutti_frutti_register_post_types' );

/**
 * Add Admin Columns for Custom Posts
 */
function tutti_frutti_add_custom_columns( $columns ) {
    $columns['date'] = esc_html__( 'Date', 'tutti-frutti-cafe' );
    return $columns;
}
add_filter( 'manage_team_posts_columns', 'tutti_frutti_add_custom_columns' );
add_filter( 'manage_testimonial_posts_columns', 'tutti_frutti_add_custom_columns' );

/**
 * WooCommerce Support
 */
function tutti_frutti_woocommerce_setup() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'tutti_frutti_woocommerce_setup' );

/**
 * Add Custom Metaboxes for Posts
 */
function tutti_frutti_add_metaboxes() {
    add_meta_box(
        'post-featured-video',
        esc_html__( 'Featured Video URL', 'tutti-frutti-cafe' ),
        'tutti_frutti_featured_video_callback',
        'post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'tutti_frutti_add_metaboxes' );

function tutti_frutti_featured_video_callback( $post ) {
    $video_url = get_post_meta( $post->ID, '_featured_video', true );
    ?>
    <input type="url" id="featured_video" name="featured_video" value="<?php echo esc_url( $video_url ); ?>" style="width: 100%; padding: 8px;" placeholder="Enter YouTube or Vimeo URL">
    <p class="description"><?php esc_html_e( 'Enter the URL of a YouTube or Vimeo video', 'tutti-frutti-cafe' ); ?></p>
    <?php
}

function tutti_frutti_save_metaboxes( $post_id ) {
    if ( isset( $_POST['featured_video'] ) ) {
        update_post_meta( $post_id, '_featured_video', esc_url( $_POST['featured_video'] ) );
    }
}
add_action( 'save_post', 'tutti_frutti_save_metaboxes' );

/**
 * Custom Theme Options
 */
function tutti_frutti_customize_register( $wp_customize ) {
    // Site Identity Section
    $wp_customize->add_setting( 'contact_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    
    $wp_customize->add_control( 'contact_phone', array(
        'label'       => esc_html__( 'Contact Phone Number', 'tutti-frutti-cafe' ),
        'section'     => 'title_tagline',
        'type'        => 'text',
    ) );
    
    $wp_customize->add_setting( 'contact_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ) );
    
    $wp_customize->add_control( 'contact_email', array(
        'label'       => esc_html__( 'Contact Email', 'tutti-frutti-cafe' ),
        'section'     => 'title_tagline',
        'type'        => 'text',
    ) );
    
    // Footer Section
    $wp_customize->add_section( 'footer_section', array(
        'title'       => esc_html__( 'Footer Settings', 'tutti-frutti-cafe' ),
        'priority'    => 130,
    ) );
    
    $wp_customize->add_setting( 'footer_text', array(
        'default'           => '&copy; ' . date( 'Y' ) . ' Tutti Frutti Café. All rights reserved.',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    
    $wp_customize->add_control( 'footer_text', array(
        'label'       => esc_html__( 'Footer Copyright Text', 'tutti-frutti-cafe' ),
        'section'     => 'footer_section',
        'type'        => 'textarea',
    ) );
}
add_action( 'customize_register', 'tutti_frutti_customize_register' );

/**
 * Admin dashboard quick links widget.
 */
function tutti_frutti_dashboard_widget() {
    wp_add_dashboard_widget(
        'tutti_frutti_quick_links',
        esc_html__( 'Tutti Frutti Café — Quick Links', 'tutti-frutti-cafe' ),
        'tutti_frutti_dashboard_widget_render'
    );
}
add_action( 'wp_dashboard_setup', 'tutti_frutti_dashboard_widget' );

/**
 * Dashboard widget content.
 */
function tutti_frutti_dashboard_widget_render() {
    $links = array(
        __( 'Customize Site', 'tutti-frutti-cafe' )     => admin_url( 'customize.php' ),
        __( 'Brands', 'tutti-frutti-cafe' )             => admin_url( 'edit.php?post_type=tf_brand' ),
        __( 'Brand Products', 'tutti-frutti-cafe' )     => admin_url( 'edit.php?post_type=tf_menu_item' ),
        __( 'Page Sections', 'tutti-frutti-cafe' )      => admin_url( 'edit.php?post_type=tf_page_section' ),
        __( 'Contact Messages', 'tutti-frutti-cafe' )   => admin_url( 'edit.php?post_type=tf_inquiry' ),
        __( 'Email / SMTP Settings', 'tutti-frutti-cafe' ) => admin_url( 'options-general.php?page=tutti-frutti-email' ),
        __( 'Product Categories', 'tutti-frutti-cafe' ) => admin_url( 'edit.php?post_type=tf_product_category' ),
        __( 'FAQs', 'tutti-frutti-cafe' )               => admin_url( 'edit.php?post_type=tf_faq' ),
        __( 'Jobs', 'tutti-frutti-cafe' )               => admin_url( 'edit.php?post_type=tf_job' ),
        __( 'Job Applications', 'tutti-frutti-cafe' )   => admin_url( 'edit.php?post_type=tf_application' ),
        __( 'TF Slides', 'tutti-frutti-cafe' )          => admin_url( 'edit.php?post_type=tf_slide' ),
        __( 'Pages', 'tutti-frutti-cafe' )              => admin_url( 'edit.php?post_type=page' ),
    );
    echo '<ul style="margin:0;padding-left:1.2em;">';
    foreach ( $links as $label => $url ) {
        printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
    }
    echo '</ul>';
    echo '<p style="margin-top:12px;font-size:12px;color:#646970;">' . esc_html__( 'See ADMIN-GUIDE.md in the theme folder for full instructions.', 'tutti-frutti-cafe' ) . '</p>';
}

/**
 * Sanitize Text
 */
function tutti_frutti_sanitize_text( $text ) {
    return sanitize_text_field( $text );
}
