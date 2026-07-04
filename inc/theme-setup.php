<?php
/**
 * Theme setup: auto-create pages on activation.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Run setup when theme is activated.
 */
function tutti_frutti_after_switch_theme() {
    tutti_frutti_create_demo_pages();
    tutti_frutti_set_front_page();
    tutti_frutti_create_primary_menu();
}
add_action( 'after_switch_theme', 'tutti_frutti_after_switch_theme' );

/**
 * Pages to create with templates.
 *
 * @return array[]
 */
function tutti_frutti_get_pages_config() {
    return array(
        array(
            'title'    => 'Home',
            'slug'     => 'home',
            'template' => '',
            'content'  => '',
        ),
        array(
            'title'    => 'About',
            'slug'     => 'about',
            'template' => 'page-templates/template-about.php',
        ),
        array(
            'title'    => 'Brands',
            'slug'     => 'brands',
            'template' => 'page-templates/template-brands.php',
        ),
        array(
            'title'    => 'Pio Coffee',
            'slug'     => 'pio-coffee',
            'template' => 'page-templates/template-brand-pio.php',
        ),
        array(
            'title'    => 'Order Online',
            'slug'     => 'order-online',
            'template' => 'page-templates/template-order.php',
        ),
        array(
            'title'    => 'Rewards',
            'slug'     => 'rewards',
            'template' => 'page-templates/template-rewards.php',
        ),
        array(
            'title'    => 'Careers',
            'slug'     => 'careers',
            'template' => 'page-templates/template-careers.php',
        ),
        array(
            'title'    => 'Franchise',
            'slug'     => 'franchise',
            'template' => 'page-templates/template-franchise.php',
        ),
        array(
            'title'    => 'Business Opportunity',
            'slug'     => 'business-opportunity',
            'template' => 'page-templates/template-business-opportunity.php',
        ),
        array(
            'title'    => 'Contact',
            'slug'     => 'contact',
            'template' => 'page-templates/template-contact.php',
        ),
        array(
            'title'    => 'FAQs',
            'slug'     => 'faqs',
            'template' => 'page-templates/template-faqs.php',
        ),
    );
}

/**
 * Create demo pages if they do not exist.
 */
function tutti_frutti_create_demo_pages() {
    foreach ( tutti_frutti_get_pages_config() as $page_config ) {
        $existing = get_page_by_path( $page_config['slug'] );

        if ( $existing ) {
            continue;
        }

        $page_id = wp_insert_post(
            array(
                'post_title'   => $page_config['title'],
                'post_name'    => $page_config['slug'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => isset( $page_config['content'] ) ? $page_config['content'] : '',
            )
        );

        if ( is_wp_error( $page_id ) || ! $page_id ) {
            continue;
        }

        if ( ! empty( $page_config['template'] ) ) {
            update_post_meta( $page_id, '_wp_page_template', $page_config['template'] );
        }
    }
}

/**
 * Set static front page to Home.
 */
function tutti_frutti_set_front_page() {
    $home = get_page_by_path( 'home' );

    if ( ! $home ) {
        return;
    }

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $home->ID );
}

/**
 * Create and assign primary navigation menu.
 */
function tutti_frutti_create_primary_menu() {
    $menu_name = 'Primary Menu';
    $menu      = wp_get_nav_menu_object( $menu_name );

    if ( ! $menu ) {
        $menu_id = wp_create_nav_menu( $menu_name );
    } else {
        $menu_id = $menu->term_id;
    }

    if ( is_wp_error( $menu_id ) || ! $menu_id ) {
        return;
    }

    $items = array(
        'about'        => 'About',
        'brands'       => 'Brands',
        'order-online' => 'Order Online',
        'rewards'      => 'Rewards',
        'franchise'    => 'Franchise',
        'business-opportunity' => 'Business Opportunity',
        'careers'      => 'Careers',
        'contact'      => 'Contact',
        'faqs'         => 'FAQs',
    );

    $existing_items = wp_get_nav_menu_items( $menu_id );
    if ( ! empty( $existing_items ) ) {
        $locations = get_theme_mod( 'nav_menu_locations', array() );
        if ( empty( $locations['primary'] ) ) {
            $locations['primary'] = $menu_id;
            set_theme_mod( 'nav_menu_locations', $locations );
        }
        return;
    }

    foreach ( $items as $slug => $label ) {
        $page = get_page_by_path( $slug );
        if ( ! $page ) {
            continue;
        }

        wp_update_nav_menu_item(
            $menu_id,
            0,
            array(
                'menu-item-title'     => $label,
                'menu-item-object'    => 'page',
                'menu-item-object-id' => $page->ID,
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
            )
        );
    }

    $locations = get_theme_mod( 'nav_menu_locations', array() );
    $locations['primary'] = $menu_id;
    set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Fallback navigation when no menu assigned.
 */
function tutti_frutti_fallback_menu() {
    $items = array(
        array( 'slug' => 'about', 'label' => __( 'About', 'tutti-frutti-cafe' ) ),
        array( 'slug' => 'brands', 'label' => __( 'Brands', 'tutti-frutti-cafe' ) ),
        array( 'slug' => 'order-online', 'label' => __( 'Order Online', 'tutti-frutti-cafe' ) ),
        array( 'slug' => 'rewards', 'label' => __( 'Rewards', 'tutti-frutti-cafe' ) ),
        array( 'slug' => 'franchise', 'label' => __( 'Franchise', 'tutti-frutti-cafe' ) ),
        array( 'slug' => 'careers', 'label' => __( 'Careers', 'tutti-frutti-cafe' ) ),
        array( 'slug' => 'contact', 'label' => __( 'Contact', 'tutti-frutti-cafe' ) ),
        array( 'slug' => 'faqs', 'label' => __( 'FAQs', 'tutti-frutti-cafe' ) ),
    );

    echo '<ul id="primary-menu" class="menu">';
    foreach ( $items as $item ) {
        $page = get_page_by_path( $item['slug'] );
        $url  = $page ? get_permalink( $page ) : home_url( '/' . $item['slug'] . '/' );
        printf(
            '<li><a href="%s">%s</a></li>',
            esc_url( $url ),
            esc_html( $item['label'] )
        );
    }
    echo '</ul>';
}

/**
 * Ensure a page exists from theme config.
 *
 * @param string $slug Page slug.
 * @return WP_Post|null
 */
function tutti_frutti_ensure_page_exists( $slug ) {
    $page = get_page_by_path( $slug, OBJECT, 'page' );
    if ( $page && 'publish' === $page->post_status ) {
        return $page;
    }

    foreach ( tutti_frutti_get_pages_config() as $page_config ) {
        if ( $page_config['slug'] !== $slug ) {
            continue;
        }

        $page_id = wp_insert_post(
            array(
                'post_title'  => $page_config['title'],
                'post_name'   => $page_config['slug'],
                'post_status' => 'publish',
                'post_type'   => 'page',
            )
        );

        if ( $page_id && ! is_wp_error( $page_id ) && ! empty( $page_config['template'] ) ) {
            update_post_meta( $page_id, '_wp_page_template', $page_config['template'] );
        }

        return get_post( $page_id );
    }

    return null;
}

/**
 * Ensure all required pages exist (runs once per request max).
 */
function tutti_frutti_ensure_required_pages() {
    static $done = false;
    if ( $done ) {
        return;
    }
    $done = true;

    $slugs = array( 'about', 'brands', 'pio-coffee', 'order-online', 'rewards', 'careers', 'franchise', 'contact', 'faqs', 'home' );
    foreach ( $slugs as $slug ) {
        $page = get_page_by_path( $slug, OBJECT, 'page' );
        if ( ! $page || 'publish' !== $page->post_status ) {
            tutti_frutti_ensure_page_exists( $slug );
        }
    }
}
add_action( 'init', 'tutti_frutti_ensure_required_pages', 20 );

/**
 * Get page URL by slug helper.
 *
 * @param string $slug Page slug.
 * @return string
 */
function tutti_frutti_page_url( $slug ) {
    $page = get_page_by_path( $slug, OBJECT, 'page' );
    if ( ! $page || 'publish' !== $page->post_status ) {
        $page = tutti_frutti_ensure_page_exists( $slug );
    }
    if ( $page && 'publish' === $page->post_status ) {
        return get_permalink( $page );
    }
    return home_url( user_trailingslashit( $slug ) );
}
