<?php
/**
 * Business Opportunity inquiries CPT.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function tutti_frutti_register_business_inquiry_cpt() {
    register_post_type(
        'tf_business_inquiry',
        array(
            'labels'              => array(
                'name'          => __( 'Business Opportunity Inquiries', 'tutti-frutti-cafe' ),
                'singular_name' => __( 'Business Inquiry', 'tutti-frutti-cafe' ),
                'view_item'     => __( 'View Inquiry', 'tutti-frutti-cafe' ),
            ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-businessman',
            'menu_position'       => 30,
            'supports'            => array( 'title', 'editor' ),
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
            'capabilities'        => array(
                'create_posts' => 'do_not_allow',
            ),
            'exclude_from_search' => true,
        )
    );
}
add_action( 'init', 'tutti_frutti_register_business_inquiry_cpt' );

function tutti_frutti_business_inquiry_columns( $columns ) {
    return array(
        'cb'       => $columns['cb'],
        'title'    => __( 'Applicant', 'tutti-frutti-cafe' ),
        'email'    => __( 'Email', 'tutti-frutti-cafe' ),
        'mobile'   => __( 'Mobile', 'tutti-frutti-cafe' ),
        'proposal' => __( 'Proposal', 'tutti-frutti-cafe' ),
        'date'     => __( 'Date', 'tutti-frutti-cafe' ),
    );
}
add_filter( 'manage_tf_business_inquiry_posts_columns', 'tutti_frutti_business_inquiry_columns' );

function tutti_frutti_business_inquiry_column_content( $column, $post_id ) {
    if ( 'email' === $column ) {
        echo esc_html( get_post_meta( $post_id, '_tf_email', true ) );
    }
    if ( 'mobile' === $column ) {
        echo esc_html( get_post_meta( $post_id, '_tf_mobile', true ) );
    }
    if ( 'proposal' === $column ) {
        $proposal_url = get_post_meta( $post_id, '_tf_proposal_url', true );
        if ( $proposal_url ) {
            printf( '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>', esc_url( $proposal_url ), esc_html__( 'Download', 'tutti-frutti-cafe' ) );
        } else {
            echo '—';
        }
    }
}
add_action( 'manage_tf_business_inquiry_posts_custom_column', 'tutti_frutti_business_inquiry_column_content', 10, 2 );

/**
 * Full inquiry details meta box.
 */
function tutti_frutti_business_inquiry_meta_box() {
    add_meta_box(
        'tf_business_inquiry_details',
        __( 'Inquiry Details', 'tutti-frutti-cafe' ),
        'tutti_frutti_business_inquiry_meta_box_render',
        'tf_business_inquiry',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'tutti_frutti_business_inquiry_meta_box' );

/**
 * @param WP_Post $post Post.
 */
function tutti_frutti_business_inquiry_meta_box_render( $post ) {
    $first_name   = get_post_meta( $post->ID, '_tf_first_name', true );
    $last_name    = get_post_meta( $post->ID, '_tf_last_name', true );
    $email        = get_post_meta( $post->ID, '_tf_email', true );
    $mobile       = get_post_meta( $post->ID, '_tf_mobile', true );
    $city         = get_post_meta( $post->ID, '_tf_city', true );
    $state        = get_post_meta( $post->ID, '_tf_state', true );
    $zip          = get_post_meta( $post->ID, '_tf_zip', true );
    $social_x     = get_post_meta( $post->ID, '_tf_social_x', true );
    $social_fb    = get_post_meta( $post->ID, '_tf_social_facebook', true );
    $social_ig    = get_post_meta( $post->ID, '_tf_social_instagram', true );
    $proposal_url = get_post_meta( $post->ID, '_tf_proposal_url', true );
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
        <tr><th><?php esc_html_e( 'Proposal', 'tutti-frutti-cafe' ); ?></th><td>
            <?php if ( $proposal_url ) : ?>
                <a href="<?php echo esc_url( $proposal_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Download Proposal', 'tutti-frutti-cafe' ); ?></a>
            <?php else : ?>
                —
            <?php endif; ?>
        </td></tr>
        <tr><th><?php esc_html_e( 'Submitted', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( get_the_date( '', $post ) . ' ' . get_the_time( '', $post ) ); ?></td></tr>
    </table>
    <h4 style="margin:16px 0 8px;"><?php esc_html_e( 'Message / Additional Details', 'tutti-frutti-cafe' ); ?></h4>
    <div style="background:#fff;border:1px solid #c3c4c7;padding:12px;border-radius:4px;white-space:pre-wrap;"><?php echo esc_html( wp_strip_all_tags( $post->post_content ) ); ?></div>
    <?php
}
