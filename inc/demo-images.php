<?php
/**
 * Centralized demo placeholder image URLs.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get demo image URL by key.
 *
 * @param string $key Image key.
 * @return string
 */
function tutti_frutti_get_image( $key ) {
    $images = tutti_frutti_demo_images();
    return isset( $images[ $key ] ) ? $images[ $key ] : $images['placeholder'];
}

/**
 * All demo image URLs.
 *
 * @return array<string, string>
 */
function tutti_frutti_demo_images() {
    return array(
        'placeholder'      => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1200&h=800&fit=crop',
        'hero'             => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=1600&h=900&fit=crop',
        'brand_tutti'      => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=600&h=400&fit=crop',
        'brand_pio'        => 'https://images.unsplash.com/photo-1497935586761-19863be0a990?w=600&h=400&fit=crop',
        'brand_cookies'    => 'https://images.unsplash.com/photo-1558961363-fa8ccf758b6f?w=600&h=400&fit=crop',
        'brand_bites'      => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&h=400&fit=crop',
        'moments'          => 'https://images.unsplash.com/photo-1521017432531-fbd92d768814?w=1400&h=600&fit=crop',
        'promo_late'       => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&h=500&fit=crop',
        'promo_hightea'    => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=800&h=500&fit=crop',
        'about_interior'   => 'https://images.unsplash.com/photo-1559925393-8be0ec4767c8?w=1400&h=700&fit=crop',
        'about_gather'     => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1400&h=700&fit=crop',
        'pio_hero'         => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=1400&h=700&fit=crop',
        'drink_karak'      => 'https://images.unsplash.com/photo-1571934811356-5cc061b6821f?w=400&h=400&fit=crop',
        'drink_pistachio'  => 'https://images.unsplash.com/photo-1461023058943-f07fa80d8f37?w=400&h=400&fit=crop',
        'drink_caramel'    => 'https://images.unsplash.com/photo-1572442388796-11668a67e3d9?w=400&h=400&fit=crop',
        'drink_spanish'    => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=400&h=400&fit=crop',
        'drink_coldbrew'   => 'https://images.unsplash.com/photo-1517701603999-4f6297508109?w=400&h=400&fit=crop',
        'order_phone'      => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=500&h=900&fit=crop',
        'rewards_phone'    => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=500&h=900&fit=crop',
        'careers_team'     => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200&h=700&fit=crop',
        'franchise'        => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1200&h=700&fit=crop',
        'treat_default'    => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=400&h=400&fit=crop',
        'logo_fallback'    => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=360&h=100&fit=crop',
    );
}

/**
 * Output img tag for demo image.
 *
 * @param string $key     Image key.
 * @param string $alt     Alt text.
 * @param string $class   CSS class.
 * @param string $loading Loading attribute.
 */
function tutti_frutti_demo_img( $key, $alt = '', $class = '', $loading = 'lazy' ) {
    $url = tutti_frutti_get_image( $key );
    printf(
        '<img src="%s" alt="%s" class="%s" loading="%s">',
        esc_url( $url ),
        esc_attr( $alt ),
        esc_attr( $class ),
        esc_attr( $loading )
    );
}
