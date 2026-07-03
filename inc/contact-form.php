<?php
/**
 * Contact form handler and inquiries CPT.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register inquiries CPT.
 */
function tutti_frutti_register_inquiry_cpt() {
    register_post_type(
        'tf_inquiry',
        array(
            'labels'              => array(
                'name'          => __( 'Contact Messages', 'tutti-frutti-cafe' ),
                'singular_name' => __( 'Contact Message', 'tutti-frutti-cafe' ),
                'view_item'     => __( 'View Message', 'tutti-frutti-cafe' ),
            ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-email-alt',
            'menu_position'       => 26,
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
add_action( 'init', 'tutti_frutti_register_inquiry_cpt' );

/**
 * Admin columns for inquiries.
 *
 * @param array $columns Columns.
 * @return array
 */
function tutti_frutti_inquiry_columns( $columns ) {
    $new = array();
    $new['cb']    = $columns['cb'];
    $new['title'] = __( 'Name', 'tutti-frutti-cafe' );
    $new['email'] = __( 'Email', 'tutti-frutti-cafe' );
    $new['phone'] = __( 'Phone', 'tutti-frutti-cafe' );
    $new['date']  = __( 'Date', 'tutti-frutti-cafe' );
    return $new;
}
add_filter( 'manage_tf_inquiry_posts_columns', 'tutti_frutti_inquiry_columns' );

/**
 * @param string $column Column.
 * @param int    $post_id Post ID.
 */
function tutti_frutti_inquiry_column_content( $column, $post_id ) {
    if ( 'email' === $column ) {
        echo esc_html( get_post_meta( $post_id, '_tf_email', true ) );
    }
    if ( 'phone' === $column ) {
        echo esc_html( get_post_meta( $post_id, '_tf_phone', true ) );
    }
}
add_action( 'manage_tf_inquiry_posts_custom_column', 'tutti_frutti_inquiry_column_content', 10, 2 );

/**
 * @return string
 */
function tutti_frutti_contact_notice_key() {
    $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'guest';
    return md5( $ip . wp_salt() );
}

/**
 * @param string $type success|error.
 * @param string $message Message.
 */
function tutti_frutti_set_contact_notice( $type, $message ) {
    set_transient( 'tf_contact_notice_' . tutti_frutti_contact_notice_key(), array( 'type' => $type, 'message' => $message ), 120 );
}

/**
 * @return array|null
 */
function tutti_frutti_get_contact_notice() {
    $status = isset( $_GET['contact'] ) ? sanitize_key( wp_unslash( $_GET['contact'] ) ) : '';
    if ( ! in_array( $status, array( 'sent', 'error' ), true ) ) {
        return null;
    }

    $data = get_transient( 'tf_contact_notice_' . tutti_frutti_contact_notice_key() );
    if ( $data ) {
        delete_transient( 'tf_contact_notice_' . tutti_frutti_contact_notice_key() );
        return $data;
    }

    if ( 'sent' === $status ) {
        return array(
            'type'    => 'success',
            'message' => __( 'Thank you! Your message has been sent.', 'tutti-frutti-cafe' ),
        );
    }

    if ( 'error' === $status ) {
        return array(
            'type'    => 'error',
            'message' => __( 'Could not send your message. Please try again.', 'tutti-frutti-cafe' ),
        );
    }

    return null;
}

/**
 * Render contact notice HTML.
 */
function tutti_frutti_render_contact_notice() {
    $notice = tutti_frutti_get_contact_notice();
    if ( ! $notice ) {
        return;
    }
    $class = 'success' === $notice['type'] ? 'contact-notice contact-notice--success' : 'contact-notice contact-notice--error';
    printf(
        '<div class="%s" role="alert">%s</div>',
        esc_attr( $class ),
        esc_html( $notice['message'] )
    );
}

/**
 * Verify a Google reCAPTCHA v2 token server-side.
 *
 * @param string $token  The g-recaptcha-response value from the form.
 * @param string $secret The reCAPTCHA secret key.
 * @return bool
 */
function tutti_frutti_verify_recaptcha( $token, $secret ) {
    $response = wp_remote_post(
        'https://www.google.com/recaptcha/api/siteverify',
        array(
            'timeout' => 10,
            'body'    => array(
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '',
            ),
        )
    );

    if ( is_wp_error( $response ) ) {
        return false;
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );

    return ! empty( $body['success'] );
}

/**
 * Handle contact form POST.
 */
function tutti_frutti_handle_contact_form() {
    if ( ! isset( $_POST['tf_contact_submit'] ) ) {
        return;
    }

    if ( ! isset( $_POST['tf_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tf_contact_nonce'] ) ), 'tf_contact_form' ) ) {
        tutti_frutti_set_contact_notice( 'error', __( 'Security check failed. Please try again.', 'tutti-frutti-cafe' ) );
        wp_safe_redirect( add_query_arg( 'contact', 'error', tutti_frutti_page_url( 'contact' ) ) );
        exit;
    }

    $recaptcha_secret = get_theme_mod( 'tf_recaptcha_secret_key', '' );
    if ( $recaptcha_secret ) {
        $recaptcha_response = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) ) : '';
        if ( empty( $recaptcha_response ) || ! tutti_frutti_verify_recaptcha( $recaptcha_response, $recaptcha_secret ) ) {
            tutti_frutti_set_contact_notice( 'error', __( 'Please confirm you are not a robot.', 'tutti-frutti-cafe' ) );
            wp_safe_redirect( add_query_arg( 'contact', 'error', tutti_frutti_page_url( 'contact' ) ) );
            exit;
        }
    }

    $first_name = isset( $_POST['contact_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_first_name'] ) ) : '';
    $last_name  = isset( $_POST['contact_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_last_name'] ) ) : '';
    $email      = isset( $_POST['contact_email'] ) ? sanitize_email( wp_unslash( $_POST['contact_email'] ) ) : '';
    $phone      = isset( $_POST['contact_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_phone'] ) ) : '';
    $message    = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';
    $name       = trim( $first_name . ' ' . $last_name );

    if ( empty( $first_name ) || empty( $last_name ) || empty( $email ) || empty( $message ) || ! is_email( $email ) ) {
        tutti_frutti_set_contact_notice( 'error', __( 'Please fill in all required fields with a valid email.', 'tutti-frutti-cafe' ) );
        wp_safe_redirect( add_query_arg( 'contact', 'error', tutti_frutti_page_url( 'contact' ) ) );
        exit;
    }

    $inquiry_id = wp_insert_post(
        array(
            'post_type'    => 'tf_inquiry',
            'post_title'   => $name,
            'post_content' => tutti_frutti_format_inquiry_content( $name, $email, $phone, $message ),
            'post_status'  => 'publish',
        )
    );

    if ( is_wp_error( $inquiry_id ) || ! $inquiry_id ) {
        tutti_frutti_set_contact_notice( 'error', __( 'Could not save your message. Please try again.', 'tutti-frutti-cafe' ) );
        wp_safe_redirect( add_query_arg( 'contact', 'error', tutti_frutti_page_url( 'contact' ) ) );
        exit;
    }

    update_post_meta( $inquiry_id, '_tf_first_name', $first_name );
    update_post_meta( $inquiry_id, '_tf_last_name', $last_name );
    update_post_meta( $inquiry_id, '_tf_email', $email );
    update_post_meta( $inquiry_id, '_tf_phone', $phone );
    tutti_frutti_save_inquiry_message_meta( $inquiry_id, $message );

    tutti_frutti_set_contact_notice( 'success', __( 'Thank you! Your message has been sent.', 'tutti-frutti-cafe' ) );

    wp_safe_redirect( add_query_arg( 'contact', 'sent', tutti_frutti_page_url( 'contact' ) ) );

    // Finish the HTTP response now so the visitor isn't stuck waiting on the
    // mail server. Sending mail can be slow (or hang) on local/dev
    // environments without SMTP configured — do it after the redirect has
    // already been delivered to the browser.
    if ( function_exists( 'fastcgi_finish_request' ) ) {
        fastcgi_finish_request();
    } else {
        while ( ob_get_level() > 0 ) {
            ob_end_flush();
        }
        flush();
    }

    $site_name = get_bloginfo( 'name' );
    $admin_subject = sprintf(
        __( '[%1$s] New contact from %2$s', 'tutti-frutti-cafe' ),
        $site_name,
        $name
    );
    $admin_body = sprintf(
        "Name: %s\nEmail: %s\nPhone: %s\n\nMessage:\n%s\n\n---\nSent from: %s",
        $name,
        $email,
        $phone ? $phone : '(none)',
        $message,
        tutti_frutti_page_url( 'contact' )
    );
    tutti_frutti_send_contact_admin_mail( $admin_subject, $admin_body, $email );

    if ( get_theme_mod( 'tf_contact_customer_email', true ) ) {
        $customer_subject = get_theme_mod(
            'tf_contact_customer_subject',
            __( 'We received your message — Tutti Frutti Café', 'tutti-frutti-cafe' )
        );
        $customer_body = get_theme_mod( 'tf_contact_customer_body', '' );
        if ( ! $customer_body ) {
            $customer_body = sprintf(
                "Hi %s,\n\nThank you for contacting Tutti Frutti Café. We have received your message and will get back to you soon.\n\nYour message:\n%s\n\n— Tutti Frutti Café Team",
                $name,
                $message
            );
        }
        if ( function_exists( 'tutti_frutti_wp_mail' ) ) {
            tutti_frutti_wp_mail( $email, $customer_subject, $customer_body );
        } else {
            wp_mail( $email, $customer_subject, $customer_body );
        }
    }

    exit;
}
add_action( 'template_redirect', 'tutti_frutti_handle_contact_form' );

/**
 * Format inquiry body for admin storage.
 *
 * @param string $name    Name.
 * @param string $email   Email.
 * @param string $phone   Phone.
 * @param string $message Message.
 * @return string
 */
function tutti_frutti_format_inquiry_content( $name, $email, $phone, $message ) {
    return sprintf(
        "Name: %s\nEmail: %s\nPhone: %s\n\nMessage:\n%s",
        $name,
        $email,
        $phone ? $phone : '(none)',
        $message
    );
}

/**
 * Admin meta box — full submission details.
 */
function tutti_frutti_inquiry_meta_box() {
    add_meta_box(
        'tf_inquiry_details',
        __( 'Form Submission', 'tutti-frutti-cafe' ),
        'tutti_frutti_inquiry_meta_box_render',
        'tf_inquiry',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'tutti_frutti_inquiry_meta_box' );

/**
 * @param WP_Post $post Post.
 */
function tutti_frutti_inquiry_meta_box_render( $post ) {
    $first_name = get_post_meta( $post->ID, '_tf_first_name', true );
    $last_name  = get_post_meta( $post->ID, '_tf_last_name', true );
    $email      = get_post_meta( $post->ID, '_tf_email', true );
    $phone      = get_post_meta( $post->ID, '_tf_phone', true );
    ?>
    <table class="widefat striped" style="margin-top:8px;">
        <tr><th style="width:120px;"><?php esc_html_e( 'First Name', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( $first_name ? $first_name : $post->post_title ); ?></td></tr>
        <tr><th><?php esc_html_e( 'Last Name', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( $last_name ); ?></td></tr>
        <tr><th><?php esc_html_e( 'Email', 'tutti-frutti-cafe' ); ?></th><td><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></td></tr>
        <tr><th><?php esc_html_e( 'Phone', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( $phone ? $phone : '—' ); ?></td></tr>
        <tr><th><?php esc_html_e( 'Submitted', 'tutti-frutti-cafe' ); ?></th><td><?php echo esc_html( get_the_date( '', $post ) . ' ' . get_the_time( '', $post ) ); ?></td></tr>
    </table>
    <h4 style="margin:16px 0 8px;"><?php esc_html_e( 'Message', 'tutti-frutti-cafe' ); ?></h4>
    <div style="background:#fff;border:1px solid #c3c4c7;padding:12px;border-radius:4px;white-space:pre-wrap;"><?php echo esc_html( get_post_meta( $post->ID, '_tf_message', true ) ?: wp_strip_all_tags( $post->post_content ) ); ?></div>
    <?php
}

/**
 * Save message meta separately for clean admin display.
 *
 * @param int    $inquiry_id Inquiry ID.
 * @param string $message    Message.
 */
function tutti_frutti_save_inquiry_message_meta( $inquiry_id, $message ) {
    update_post_meta( $inquiry_id, '_tf_message', $message );
}
