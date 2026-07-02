<?php
/**
 * Jobs and job applications CPTs.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function tutti_frutti_register_job_cpts() {
    register_post_type(
        'tf_job',
        array(
            'labels'              => array(
                'name'          => __( 'Jobs', 'tutti-frutti-cafe' ),
                'singular_name' => __( 'Job', 'tutti-frutti-cafe' ),
                'add_new_item'  => __( 'Add New Job', 'tutti-frutti-cafe' ),
            ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-id',
            'menu_position'       => 28,
            'supports'            => array( 'title', 'editor', 'page-attributes' ),
            'show_in_rest'        => true,
        )
    );

    register_post_type(
        'tf_application',
        array(
            'labels'              => array(
                'name'          => __( 'Job Applications', 'tutti-frutti-cafe' ),
                'singular_name' => __( 'Application', 'tutti-frutti-cafe' ),
            ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-clipboard',
            'menu_position'       => 29,
            'supports'            => array( 'title', 'editor' ),
            'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
            'map_meta_cap'        => true,
        )
    );
}
add_action( 'init', 'tutti_frutti_register_job_cpts' );

function tutti_frutti_job_meta_box() {
    add_meta_box( 'tf_job_details', __( 'Job Details', 'tutti-frutti-cafe' ), 'tutti_frutti_job_meta_render', 'tf_job', 'side' );
}
add_action( 'add_meta_boxes', 'tutti_frutti_job_meta_box' );

function tutti_frutti_job_meta_render( $post ) {
    wp_nonce_field( 'tf_job_meta', 'tf_job_meta_nonce' );
    $location = get_post_meta( $post->ID, '_tf_job_location', true );
    $active   = get_post_meta( $post->ID, '_tf_job_active', true );
    ?>
    <p>
        <label for="tf_job_location"><?php esc_html_e( 'Location', 'tutti-frutti-cafe' ); ?></label>
        <input type="text" id="tf_job_location" name="tf_job_location" value="<?php echo esc_attr( $location ); ?>" class="widefat">
    </p>
    <p>
        <label><input type="checkbox" name="tf_job_active" value="1" <?php checked( $active, '1' ); ?>> <?php esc_html_e( 'Active (show on Careers page)', 'tutti-frutti-cafe' ); ?></label>
    </p>
    <?php
}

function tutti_frutti_save_job_meta( $post_id ) {
    if ( ! isset( $_POST['tf_job_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tf_job_meta_nonce'] ) ), 'tf_job_meta' ) ) {
        return;
    }
    update_post_meta( $post_id, '_tf_job_location', isset( $_POST['tf_job_location'] ) ? sanitize_text_field( wp_unslash( $_POST['tf_job_location'] ) ) : '' );
    update_post_meta( $post_id, '_tf_job_active', isset( $_POST['tf_job_active'] ) ? '1' : '0' );
}

/**
 * New jobs default to active.
 *
 * @param int $post_id Post ID.
 */
function tutti_frutti_default_job_active( $post_id ) {
    if ( 'tf_job' !== get_post_type( $post_id ) ) {
        return;
    }
    if ( '' === get_post_meta( $post_id, '_tf_job_active', true ) ) {
        update_post_meta( $post_id, '_tf_job_active', '1' );
    }
}
add_action( 'save_post_tf_job', 'tutti_frutti_default_job_active', 5 );
add_action( 'save_post_tf_job', 'tutti_frutti_save_job_meta' );

function tutti_frutti_application_columns( $columns ) {
    return array(
        'cb'    => $columns['cb'],
        'title' => __( 'Applicant', 'tutti-frutti-cafe' ),
        'job'   => __( 'Job', 'tutti-frutti-cafe' ),
        'email' => __( 'Email', 'tutti-frutti-cafe' ),
        'date'  => __( 'Date', 'tutti-frutti-cafe' ),
    );
}
add_filter( 'manage_tf_application_posts_columns', 'tutti_frutti_application_columns' );

function tutti_frutti_application_column_content( $column, $post_id ) {
    if ( 'email' === $column ) {
        echo esc_html( get_post_meta( $post_id, '_tf_email', true ) );
    }
    if ( 'job' === $column ) {
        $job_id = (int) get_post_meta( $post_id, '_tf_job_id', true );
        echo $job_id ? esc_html( get_the_title( $job_id ) ) : esc_html__( 'General Application', 'tutti-frutti-cafe' );
    }
}
add_action( 'manage_tf_application_posts_custom_column', 'tutti_frutti_application_column_content', 10, 2 );

/**
 * Active jobs.
 *
 * @return WP_Post[]
 */
function tutti_frutti_get_active_jobs() {
    return get_posts(
        array(
            'post_type'      => 'tf_job',
            'posts_per_page' => 50,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
            'meta_query'     => array(
                array(
                    'key'   => '_tf_job_active',
                    'value' => '1',
                ),
            ),
        )
    );
}
