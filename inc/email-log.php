<?php
/**
 * Email log CPT and wp_mail hooks.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register email log CPT.
 */
function tutti_frutti_register_email_log_cpt() {
    register_post_type(
        'tf_email_log',
        array(
            'labels'              => array(
                'name'          => __( 'Email Logs', 'tutti-frutti-cafe' ),
                'singular_name' => __( 'Email Log', 'tutti-frutti-cafe' ),
                'view_item'     => __( 'View Log Entry', 'tutti-frutti-cafe' ),
            ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => 'options-general.php',
            'menu_icon'           => 'dashicons-email',
            'supports'            => array( 'title' ),
            'capability_type'     => 'post',
            'capabilities'        => array(
                'create_posts' => 'do_not_allow',
            ),
            'map_meta_cap'        => true,
            'exclude_from_search' => true,
        )
    );
}
add_action( 'init', 'tutti_frutti_register_email_log_cpt' );

/**
 * Admin columns.
 *
 * @param array $columns Columns.
 * @return array
 */
function tutti_frutti_email_log_columns( $columns ) {
    return array(
        'cb'      => $columns['cb'],
        'title'   => __( 'Subject', 'tutti-frutti-cafe' ),
        'to'      => __( 'To', 'tutti-frutti-cafe' ),
        'status'  => __( 'Status', 'tutti-frutti-cafe' ),
        'error'   => __( 'Error', 'tutti-frutti-cafe' ),
        'date'    => __( 'Date', 'tutti-frutti-cafe' ),
    );
}
add_filter( 'manage_tf_email_log_posts_columns', 'tutti_frutti_email_log_columns' );

/**
 * @param string $column  Column.
 * @param int    $post_id Post ID.
 */
function tutti_frutti_email_log_column_content( $column, $post_id ) {
    if ( 'to' === $column ) {
        echo esc_html( get_post_meta( $post_id, '_tf_email_to', true ) );
    }
    if ( 'status' === $column ) {
        $status = get_post_meta( $post_id, '_tf_email_status', true );
        echo esc_html( $status ? $status : '—' );
    }
    if ( 'error' === $column ) {
        $err = get_post_meta( $post_id, '_tf_email_error', true );
        echo esc_html( $err ? $err : '—' );
    }
}
add_action( 'manage_tf_email_log_posts_custom_column', 'tutti_frutti_email_log_column_content', 10, 2 );

/**
 * Log an email attempt.
 *
 * @param string|array $to      Recipient(s).
 * @param string       $subject Subject.
 * @param string       $status  sent|failed.
 * @param string       $error   Error message.
 */
function tutti_frutti_log_email( $to, $subject, $status, $error = '' ) {
    if ( is_array( $to ) ) {
        $to = implode( ', ', $to );
    }

    $log_id = wp_insert_post(
        array(
            'post_type'   => 'tf_email_log',
            'post_title'  => $subject ? $subject : __( '(no subject)', 'tutti-frutti-cafe' ),
            'post_status' => 'publish',
        ),
        true
    );

    if ( is_wp_error( $log_id ) || ! $log_id ) {
        return;
    }

    update_post_meta( $log_id, '_tf_email_to', sanitize_text_field( $to ) );
    update_post_meta( $log_id, '_tf_email_status', sanitize_key( $status ) );
    update_post_meta( $log_id, '_tf_email_error', sanitize_textarea_field( $error ) );
}

/**
 * wp_mail wrapper with logging.
 *
 * @param string|array $to          To.
 * @param string       $subject     Subject.
 * @param string       $message     Body.
 * @param string|array $headers     Headers.
 * @param string|array $attachments Attachments.
 * @return bool
 */
function tutti_frutti_wp_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
    $GLOBALS['tf_current_mail'] = array(
        'to'      => $to,
        'subject' => $subject,
    );

    $sent = wp_mail( $to, $subject, $message, $headers, $attachments );

    if ( $sent ) {
        tutti_frutti_log_email( $to, $subject, 'sent' );
    } elseif ( empty( $GLOBALS['tf_mail_failed_logged'] ) ) {
        $err = isset( $GLOBALS['tf_last_mail_error'] ) ? $GLOBALS['tf_last_mail_error'] : __( 'wp_mail returned false', 'tutti-frutti-cafe' );
        tutti_frutti_log_email( $to, $subject, 'failed', $err );
    }

    unset( $GLOBALS['tf_current_mail'], $GLOBALS['tf_mail_failed_logged'], $GLOBALS['tf_last_mail_error'] );

    return $sent;
}

/**
 * Capture wp_mail failures.
 *
 * @param WP_Error $error Error.
 */
function tutti_frutti_on_wp_mail_failed( $error ) {
    if ( ! is_wp_error( $error ) ) {
        return;
    }

    $message = $error->get_error_message();
    $GLOBALS['tf_last_mail_error']     = $message;
    $GLOBALS['tf_mail_failed_logged']    = true;

    $to      = '';
    $subject = '';
    if ( isset( $GLOBALS['tf_current_mail'] ) ) {
        $to      = $GLOBALS['tf_current_mail']['to'];
        $subject = $GLOBALS['tf_current_mail']['subject'];
    } else {
        $data = $error->get_error_data();
        if ( is_array( $data ) ) {
            if ( isset( $data['to'] ) ) {
                $to = $data['to'];
            }
            if ( isset( $data['subject'] ) ) {
                $subject = $data['subject'];
            }
        }
    }

    if ( $to || $subject ) {
        tutti_frutti_log_email( $to, $subject, 'failed', $message );
    }
}
add_action( 'wp_mail_failed', 'tutti_frutti_on_wp_mail_failed' );

/**
 * Recent email logs for admin table.
 *
 * @param int $limit Max entries.
 * @return WP_Post[]
 */
function tutti_frutti_get_recent_email_logs( $limit = 20 ) {
    return get_posts(
        array(
            'post_type'      => 'tf_email_log',
            'posts_per_page' => $limit,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        )
    );
}
