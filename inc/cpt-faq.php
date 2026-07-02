<?php
/**
 * FAQs CPT.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function tutti_frutti_register_faq_cpt() {
    register_post_type(
        'tf_faq',
        array(
            'labels'              => array(
                'name'          => __( 'FAQs', 'tutti-frutti-cafe' ),
                'singular_name' => __( 'FAQ', 'tutti-frutti-cafe' ),
                'add_new_item'  => __( 'Add New FAQ', 'tutti-frutti-cafe' ),
            ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-editor-help',
            'menu_position'       => 27,
            'supports'            => array( 'title', 'editor', 'page-attributes' ),
            'show_in_rest'        => true,
        )
    );
}
add_action( 'init', 'tutti_frutti_register_faq_cpt' );

/**
 * @return WP_Post[]
 */
function tutti_frutti_get_faqs() {
    return get_posts(
        array(
            'post_type'      => 'tf_faq',
            'posts_per_page' => 100,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        )
    );
}
