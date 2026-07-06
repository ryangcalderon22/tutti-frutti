<?php
/**
 * Product categories per brand.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function tutti_frutti_register_product_category_cpt() {
    register_post_type(
        'tf_product_category',
        array(
            'labels'              => array(
                'name'          => __( 'Product Categories', 'tutti-frutti-cafe' ),
                'singular_name' => __( 'Product Category', 'tutti-frutti-cafe' ),
                'add_new_item'  => __( 'Add New Category', 'tutti-frutti-cafe' ),
            ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-category',
            'menu_position'       => 21,
            'hierarchical'        => true,
            'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
            'show_in_rest'        => true,
        )
    );
}
add_action( 'init', 'tutti_frutti_register_product_category_cpt' );

function tutti_frutti_product_category_meta_box() {
    add_meta_box(
        'tf_product_cat_brand',
        __( 'Category Settings', 'tutti-frutti-cafe' ),
        'tutti_frutti_product_category_meta_render',
        'tf_product_category',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'tutti_frutti_product_category_meta_box' );

function tutti_frutti_product_category_meta_render( $post ) {
    wp_nonce_field( 'tf_product_cat_meta', 'tf_product_cat_meta_nonce' );
    $brand_id   = (int) get_post_meta( $post->ID, '_tf_brand_id', true );
    $order_url  = get_post_meta( $post->ID, '_tf_cat_order_url', true );
    $image_url  = get_post_meta( $post->ID, '_tf_cat_image', true );
    $brands     = get_posts( array( 'post_type' => 'tf_brand', 'posts_per_page' => -1, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC' ) );
    ?>
    <p>
        <label for="tf_cat_brand_id"><strong><?php esc_html_e( 'Brand', 'tutti-frutti-cafe' ); ?></strong></label>
        <select id="tf_cat_brand_id" name="tf_cat_brand_id" class="widefat">
            <option value=""><?php esc_html_e( '— Select Brand —', 'tutti-frutti-cafe' ); ?></option>
            <?php foreach ( $brands as $b ) : ?>
                <option value="<?php echo esc_attr( $b->ID ); ?>" <?php selected( $brand_id, $b->ID ); ?>><?php echo esc_html( $b->post_title ); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="tf_cat_order_url"><strong><?php esc_html_e( 'Order Now URL', 'tutti-frutti-cafe' ); ?></strong></label>
        <input type="url" id="tf_cat_order_url" name="tf_cat_order_url" value="<?php echo esc_url( $order_url ); ?>" class="widefat">
    </p>
    <p>
        <label for="tf_cat_image"><strong><?php esc_html_e( 'Category image URL (optional — or use Featured Image)', 'tutti-frutti-cafe' ); ?></strong></label>
        <input type="url" id="tf_cat_image" name="tf_cat_image" value="<?php echo esc_url( $image_url ); ?>" class="widefat">
    </p>
    <?php
}

function tutti_frutti_save_product_category_meta( $post_id ) {
    if ( ! isset( $_POST['tf_product_cat_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tf_product_cat_meta_nonce'] ) ), 'tf_product_cat_meta' ) ) {
        return;
    }
    if ( isset( $_POST['tf_cat_brand_id'] ) ) {
        update_post_meta( $post_id, '_tf_brand_id', absint( $_POST['tf_cat_brand_id'] ) );
    }
    if ( isset( $_POST['tf_cat_order_url'] ) ) {
        update_post_meta( $post_id, '_tf_cat_order_url', esc_url_raw( wp_unslash( $_POST['tf_cat_order_url'] ) ) );
    }
    if ( isset( $_POST['tf_cat_image'] ) ) {
        update_post_meta( $post_id, '_tf_cat_image', esc_url_raw( wp_unslash( $_POST['tf_cat_image'] ) ) );
    }
}
add_action( 'save_post_tf_product_category', 'tutti_frutti_save_product_category_meta' );

/**
 * Categories for a brand.
 *
 * @param int $brand_id Brand ID.
 * @return WP_Post[]
 */
function tutti_frutti_get_brand_categories( $brand_id ) {
    return get_posts(
        array(
            'post_type'      => 'tf_product_category',
            'posts_per_page' => 50,
            'post_status'    => 'publish',
            'orderby'        => array(
                'menu_order' => 'ASC',
                'title'      => 'ASC',
            ),
            'meta_query'     => array(
                array(
                    'key'   => '_tf_brand_id',
                    'value' => (int) $brand_id,
                ),
            ),
        )
    );
}
