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
        'cb'     => $columns['cb'],
        'title'  => __( 'Applicant', 'tutti-frutti-cafe' ),
        'job'    => __( 'Job', 'tutti-frutti-cafe' ),
        'email'  => __( 'Email', 'tutti-frutti-cafe' ),
        'mobile' => __( 'Mobile', 'tutti-frutti-cafe' ),
        'resume' => __( 'Resume', 'tutti-frutti-cafe' ),
        'date'   => __( 'Date', 'tutti-frutti-cafe' ),
    );
}
add_filter( 'manage_tf_application_posts_columns', 'tutti_frutti_application_columns' );

function tutti_frutti_application_column_content( $column, $post_id ) {
    if ( 'email' === $column ) {
        echo esc_html( get_post_meta( $post_id, '_tf_email', true ) );
    }
    if ( 'mobile' === $column ) {
        echo esc_html( get_post_meta( $post_id, '_tf_mobile', true ) );
    }
    if ( 'job' === $column ) {
        $job_id = (int) get_post_meta( $post_id, '_tf_job_id', true );
        echo $job_id ? esc_html( get_the_title( $job_id ) ) : esc_html__( 'General Application', 'tutti-frutti-cafe' );
    }
    if ( 'resume' === $column ) {
        $resume_url = get_post_meta( $post_id, '_tf_resume_url', true );
        if ( $resume_url ) {
            printf( '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>', esc_url( $resume_url ), esc_html__( 'Download', 'tutti-frutti-cafe' ) );
        } else {
            echo '—';
        }
    }
}
add_action( 'manage_tf_application_posts_custom_column', 'tutti_frutti_application_column_content', 10, 2 );

/**
 * Full application details meta box.
 */
function tutti_frutti_application_meta_box() {
    add_meta_box(
        'tf_application_details',
        __( 'Application Details', 'tutti-frutti-cafe' ),
        'tutti_frutti_application_meta_box_render',
        'tf_application',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'tutti_frutti_application_meta_box' );

/**
 * @param WP_Post $post Post.
 */
function tutti_frutti_application_meta_box_render( $post ) {
    $first_name = get_post_meta( $post->ID, '_tf_first_name', true );
    $last_name  = get_post_meta( $post->ID, '_tf_last_name', true );
    $email      = get_post_meta( $post->ID, '_tf_email', true );
    $mobile     = get_post_meta( $post->ID, '_tf_mobile', true );
    $city       = get_post_meta( $post->ID, '_tf_city', true );
    $state      = get_post_meta( $post->ID, '_tf_state', true );
    $zip        = get_post_meta( $post->ID, '_tf_zip', true );
    $social_x   = get_post_meta( $post->ID, '_tf_social_x', true );
    $social_fb  = get_post_meta( $post->ID, '_tf_social_facebook', true );
    $social_ig  = get_post_meta( $post->ID, '_tf_social_instagram', true );
    $job_id     = (int) get_post_meta( $post->ID, '_tf_job_id', true );
    $job_title  = $job_id ? get_the_title( $job_id ) : __( 'General Application', 'tutti-frutti-cafe' );
    $resume_url = get_post_meta( $post->ID, '_tf_resume_url', true );
    ?>
    <table class="widefat striped" style="margin-top:8px;">
        <tr><th style="width:160px;"><?php esc_html_e( 'First Name', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( $first_name ? $first_name : $post->post_title ); ?></td></tr>
        <tr><th><?php esc_html_e( 'Last Name', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( $last_name ); ?></td></tr>
        <tr><th><?php esc_html_e( 'Email', 'tutti-frutti-cafe' ); ?></th><td><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></td></tr>
        <tr><th><?php esc_html_e( 'Mobile Number', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( $mobile ? $mobile : '—' ); ?></td></tr>
        <tr><th><?php esc_html_e( 'City / State / Zip', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( trim( "$city, $state $zip", ', ' ) ); ?></td></tr>
        <tr><th><?php esc_html_e( 'X (Twitter)', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( $social_x ? $social_x : '—' ); ?></td></tr>
        <tr><th><?php esc_html_e( 'Facebook', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( $social_fb ? $social_fb : '—' ); ?></td></tr>
        <tr><th><?php esc_html_e( 'Instagram', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( $social_ig ? $social_ig : '—' ); ?></td></tr>
        <tr><th><?php esc_html_e( 'Position', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( $job_title ); ?></td></tr>
        <tr><th><?php esc_html_e( 'Resume', 'tutti-frutti-cafe' ); ?></th><td>
            <?php if ( $resume_url ) : ?>
                <a href="<?php echo esc_url( $resume_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Download Resume', 'tutti-frutti-cafe' ); ?></a>
            <?php else : ?>
                —
            <?php endif; ?>
        </td></tr>
        <tr><th><?php esc_html_e( 'Submitted', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( get_the_date( '', $post ) . ' ' . get_the_time( '', $post ) ); ?></td></tr>
    </table>
    <h4 style="margin:16px 0 8px;"><?php esc_html_e( 'Message / Cover Letter', 'tutti-frutti-cafe' ); ?></h4>
    <div style="background:#fff;border:1px solid #c3c4c7;padding:12px;border-radius:4px;white-space:pre-wrap;"><?php echo esc_html( wp_strip_all_tags( $post->post_content ) ); ?></div>
    <?php
}

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
