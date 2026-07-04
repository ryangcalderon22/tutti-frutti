<?php
/**
 * Email helpers — multiple recipients.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Parse comma-separated emails.
 *
 * @param string $raw Raw string.
 * @return array
 */
function tutti_frutti_parse_email_list( $raw ) {
    if ( ! $raw ) {
        return array();
    }
    $parts = array_map( 'trim', explode( ',', $raw ) );
    $valid = array();
    foreach ( $parts as $email ) {
        if ( is_email( $email ) ) {
            $valid[] = $email;
        }
    }
    return $valid;
}

/**
 * Get contact admin recipients.
 *
 * @return array
 */
function tutti_frutti_get_contact_admin_emails() {
    $raw = get_theme_mod( 'tf_email_contact_admins', '' );
    $list = tutti_frutti_parse_email_list( $raw );
    if ( ! empty( $list ) ) {
        return $list;
    }
    $legacy = get_theme_mod( 'tf_admin_email', get_option( 'admin_email' ) );
    return is_email( $legacy ) ? array( $legacy ) : array( get_option( 'admin_email' ) );
}

/**
 * Get careers admin recipients.
 *
 * @return array
 */
function tutti_frutti_get_careers_admin_emails() {
    $raw = get_theme_mod( 'tf_email_careers_admins', '' );
    $list = tutti_frutti_parse_email_list( $raw );
    if ( ! empty( $list ) ) {
        return $list;
    }
    return tutti_frutti_get_contact_admin_emails();
}

/**
 * Get contact CC recipients.
 *
 * @return array
 */
function tutti_frutti_get_contact_cc_emails() {
    return tutti_frutti_parse_email_list( get_theme_mod( 'tf_email_contact_cc', '' ) );
}

/**
 * Get business opportunity admin recipients.
 *
 * @return array
 */
function tutti_frutti_get_business_admin_emails() {
    $raw = get_theme_mod( 'tf_email_business_admins', '' );
    $list = tutti_frutti_parse_email_list( $raw );
    if ( ! empty( $list ) ) {
        return $list;
    }
    return tutti_frutti_get_contact_admin_emails();
}

/**
 * Send email to admin recipients with optional CC.
 *
 * @param string $subject Subject.
 * @param string $body    Body.
 * @param string $reply_to Reply-To email.
 * @return bool
 */
function tutti_frutti_send_contact_admin_mail( $subject, $body, $reply_to = '' ) {
    $to_list = tutti_frutti_get_contact_admin_emails();
    $cc_list = tutti_frutti_get_contact_cc_emails();

    if ( empty( $to_list ) ) {
        $to_list = array( get_option( 'admin_email' ) );
    }

    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    if ( $reply_to && is_email( $reply_to ) ) {
        $headers[] = 'Reply-To: ' . $reply_to;
    }
    foreach ( $cc_list as $cc ) {
        $headers[] = 'Cc: ' . $cc;
    }

    $sent = false;
    foreach ( $to_list as $to ) {
        if ( function_exists( 'tutti_frutti_wp_mail' ) ) {
            $ok = tutti_frutti_wp_mail( $to, $subject, $body, $headers );
        } else {
            $ok = wp_mail( $to, $subject, $body, $headers );
        }
        if ( $ok ) {
            $sent = true;
        }
    }
    return $sent;
}

/**
 * Send mail to multiple recipients.
 *
 * @param array  $recipients Emails.
 * @param string $subject    Subject.
 * @param string $body       Body.
 * @param array  $headers    Headers.
 * @param array  $attachments Attachments.
 * @return bool
 */
function tutti_frutti_send_mail_to_recipients( $recipients, $subject, $body, $headers = array(), $attachments = array() ) {
    $sent = false;
    foreach ( $recipients as $to ) {
        if ( function_exists( 'tutti_frutti_wp_mail' ) ) {
            $ok = tutti_frutti_wp_mail( $to, $subject, $body, $headers, $attachments );
        } else {
            $ok = wp_mail( $to, $subject, $body, $headers, $attachments );
        }
        if ( $ok ) {
            $sent = true;
        }
    }
    return $sent;
}

/**
 * Global ChowNow / order URL.
 *
 * @return string
 */
function tutti_frutti_get_chownow_url() {
    $url = get_theme_mod( 'tf_chownow_url', '' );
    if ( $url ) {
        return $url;
    }
    return tutti_frutti_page_url( 'order-online' );
}
