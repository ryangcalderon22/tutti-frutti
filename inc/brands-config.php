<?php
/**
 * Brand cards configuration (homepage + brands page).
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Parse homepage card lines (one label per line).
 *
 * @param string $raw Raw textarea.
 * @return string[]
 */
function tutti_frutti_parse_brand_card_lines( $raw ) {
    if ( ! is_string( $raw ) || '' === trim( $raw ) ) {
        return array();
    }

    $lines = preg_split( '/\r\n|\r|\n/', $raw );
    $out   = array();

    foreach ( $lines as $line ) {
        $line = trim( $line );
        if ( $line ) {
            $out[] = $line;
        }
    }

    return $out;
}

/**
 * Get all brand cards — from CPT or PHP fallback.
 *
 * @return array[]
 */
function tutti_frutti_get_brands() {
    $posts = get_posts(
        array(
            'post_type'      => 'tf_brand',
            'posts_per_page' => 20,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        )
    );

    if ( ! empty( $posts ) ) {
        $brands = array();
        foreach ( $posts as $post ) {
            $btn   = get_post_meta( $post->ID, '_tf_button_style', true );
            $key   = get_post_meta( $post->ID, '_tf_demo_key', true );
            $logo  = get_post_meta( $post->ID, '_tf_brand_logo', true );
            $thumb = get_the_post_thumbnail_url( $post, 'medium' );
            $url   = get_permalink( $post );

            $brands[] = array(
                'id'          => $post->ID,
                'key'         => $key ? $key : 'brand_tutti',
                'name'        => $post->post_title,
                'url'         => $url ? $url : home_url( '/' ),
                'btn'         => $btn ? $btn : 'btn-brand--purple',
                'logo'        => $logo ? $logo : ( $thumb ? $thumb : '' ),
                'card_title'  => get_post_meta( $post->ID, '_tf_card_title', true ),
                'card_desc'   => get_post_meta( $post->ID, '_tf_card_desc', true ),
                'card_button' => get_post_meta( $post->ID, '_tf_card_button_text', true ),
                'card_lines'  => tutti_frutti_parse_brand_card_lines( get_post_meta( $post->ID, '_tf_card_lines', true ) ),
            );
        }
        return $brands;
    }

    return tutti_frutti_get_brands_fallback();
}

/**
 * Brand detail hero image (right side) — no fallback.
 *
 * @param int $brand_id Brand post ID.
 * @return string
 */
function tutti_frutti_get_brand_hero_image_url( $brand_id ) {
    $detail = get_post_meta( $brand_id, '_tf_detail_image', true );
    if ( $detail ) {
        return $detail;
    }
    $thumb = get_the_post_thumbnail_url( $brand_id, 'large' );
    return $thumb ? $thumb : '';
}

/**
 * Brand logo URL — no fallback.
 *
 * @param int $brand_id Brand post ID.
 * @return string
 */
function tutti_frutti_get_brand_logo_url( $brand_id ) {
    $logo = get_post_meta( $brand_id, '_tf_brand_logo', true );
    if ( $logo ) {
        return $logo;
    }
    $thumb = get_the_post_thumbnail_url( $brand_id, 'medium' );
    return $thumb ? $thumb : '';
}

/**
 * Get products for a brand (by post ID).
 *
 * @param int $brand_id Brand post ID.
 * @return array[]
 */
function tutti_frutti_get_brand_products( $brand_id ) {
    return tutti_frutti_get_menu_items( $brand_id );
}

/**
 * Products grouped by category for brand detail page.
 *
 * @param int $brand_id Brand ID.
 * @return array[] Each: title, products[].
 */
function tutti_frutti_get_brand_products_grouped( $brand_id ) {
    $all        = tutti_frutti_get_menu_items( $brand_id );
    $categories = tutti_frutti_get_brand_categories( $brand_id );
    $grouped    = array();
    $assigned   = array();

    foreach ( $categories as $cat ) {
        $products = array();
        foreach ( $all as $p ) {
            if ( (int) $p['category_id'] === (int) $cat->ID ) {
                $products[] = $p;
                $assigned[ $p['id'] ] = true;
            }
        }
        if ( ! empty( $products ) ) {
            $cat_image = get_the_post_thumbnail_url( $cat, 'medium_large' );
            if ( ! $cat_image ) {
                $cat_image = get_post_meta( $cat->ID, '_tf_cat_image', true );
            }
            $cat_order = get_post_meta( $cat->ID, '_tf_cat_order_url', true );
            $grouped[] = array(
                'title'     => $cat->post_title,
                'image'     => $cat_image ? $cat_image : '',
                'order_url' => $cat_order,
                'products'  => $products,
            );
        }
    }

    $other = array();
    foreach ( $all as $p ) {
        if ( empty( $assigned[ $p['id'] ] ) ) {
            $other[] = $p;
        }
    }
    if ( ! empty( $other ) ) {
        $grouped[] = array(
            'title'     => '',
            'image'     => '',
            'order_url' => '',
            'products'  => $other,
        );
    }

    if ( empty( $grouped ) && ! empty( $all ) ) {
        $grouped[] = array(
            'title'     => '',
            'image'     => '',
            'order_url' => '',
            'products'  => $all,
        );
    }

    return $grouped;
}

/**
 * Hardcoded fallback brands.
 *
 * @return array[]
 */
function tutti_frutti_get_brands_fallback() {
    return array(
        array(
            'id'    => 0,
            'key'   => 'brand_tutti',
            'name'  => __( 'Tutti Frutti', 'tutti-frutti-cafe' ),
            'desc'  => __( 'Frozen treats & froyo', 'tutti-frutti-cafe' ),
            'url'   => home_url( '/brand/tutti-frutti/' ),
            'btn'   => 'btn-brand--purple',
            'image' => '',
        ),
        array(
            'id'    => 0,
            'key'   => 'brand_pio',
            'name'  => __( 'Pio Coffee', 'tutti-frutti-cafe' ),
            'desc'  => __( 'Coffee & chai', 'tutti-frutti-cafe' ),
            'url'   => home_url( '/brand/pio-coffee/' ),
            'btn'   => 'btn-brand--orange',
            'image' => '',
        ),
        array(
            'id'    => 0,
            'key'   => 'brand_cookies',
            'name'  => __( 'My Cookies!', 'tutti-frutti-cafe' ),
            'desc'  => __( 'Cookies, bakery & buns', 'tutti-frutti-cafe' ),
            'url'   => home_url( '/brand/my-cookies/' ),
            'btn'   => 'btn-brand--yellow',
            'image' => '',
        ),
        array(
            'id'    => 0,
            'key'   => 'brand_bites',
            'name'  => __( 'TF Bites', 'tutti-frutti-cafe' ),
            'desc'  => __( 'Savory snacks & meals', 'tutti-frutti-cafe' ),
            'url'   => home_url( '/brand/tf-bites/' ),
            'btn'   => 'btn-brand--green',
            'image' => '',
        ),
    );
}

/**
 * Output brand card logo.
 *
 * @param array $brand Brand data.
 */
function tutti_frutti_brand_card_logo( $brand ) {
    if ( ! empty( $brand['logo'] ) ) {
        printf(
            '<img src="%s" alt="%s" class="brand-card__logo" loading="lazy">',
            esc_url( $brand['logo'] ),
            esc_attr( $brand['name'] )
        );
    }
}

/**
 * Output brand card image (legacy).
 *
 * @param array $brand Brand data.
 */
function tutti_frutti_brand_image( $brand ) {
    tutti_frutti_brand_card_logo( $brand );
}