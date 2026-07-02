<?php
/**
 * SMTP mail settings (Settings → Tutti Frutti Email).
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register settings page.
 */
function tutti_frutti_smtp_admin_menu() {
    add_options_page(
        __( 'Tutti Frutti Email', 'tutti-frutti-cafe' ),
        __( 'Tutti Frutti Email', 'tutti-frutti-cafe' ),
        'manage_options',
        'tutti-frutti-email',
        'tutti_frutti_smtp_settings_page'
    );
}
add_action( 'admin_menu', 'tutti_frutti_smtp_admin_menu' );

/**
 * Register options.
 */
function tutti_frutti_smtp_register_settings() {
    $fields = array(
        'tf_smtp_enabled',
        'tf_smtp_host',
        'tf_smtp_port',
        'tf_smtp_encryption',
        'tf_smtp_username',
        'tf_smtp_password',
        'tf_smtp_from_email',
        'tf_smtp_from_name',
    );
    foreach ( $fields as $field ) {
        register_setting( 'tutti_frutti_email', $field, array(
            'sanitize_callback' => 'tutti_frutti_sanitize_smtp_field',
        ) );
    }
}
add_action( 'admin_init', 'tutti_frutti_smtp_register_settings' );

/**
 * @param mixed $value Value.
 * @return mixed
 */
function tutti_frutti_sanitize_smtp_field( $value ) {
    if ( is_string( $value ) ) {
        return sanitize_text_field( $value );
    }
    return $value;
}

/**
 * Handle test email POST from settings page.
 */
function tutti_frutti_smtp_handle_test_email() {
    if ( ! isset( $_POST['tf_send_test_email'] ) || ! current_user_can( 'manage_options' ) ) {
        return;
    }
    if ( ! isset( $_POST['tf_test_email_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tf_test_email_nonce'] ) ), 'tf_test_email' ) ) {
        add_settings_error( 'tutti_frutti_email', 'tf_test_nonce', __( 'Security check failed.', 'tutti-frutti-cafe' ), 'error' );
        return;
    }

    $to      = get_option( 'admin_email' );
    $subject = sprintf( __( '[%s] SMTP test email', 'tutti-frutti-cafe' ), get_bloginfo( 'name' ) );
    $body    = __( 'This is a test email from Tutti Frutti Café SMTP settings. If you received this, mail is working.', 'tutti-frutti-cafe' );

    $sent = function_exists( 'tutti_frutti_wp_mail' )
        ? tutti_frutti_wp_mail( $to, $subject, $body )
        : wp_mail( $to, $subject, $body );

    if ( $sent ) {
        add_settings_error( 'tutti_frutti_email', 'tf_test_sent', sprintf( __( 'Test email sent to %s.', 'tutti-frutti-cafe' ), $to ), 'success' );
    } else {
        add_settings_error( 'tutti_frutti_email', 'tf_test_failed', __( 'Test email failed. Check Email Logs below and your SMTP settings.', 'tutti-frutti-cafe' ), 'error' );
    }
}
add_action( 'admin_init', 'tutti_frutti_smtp_handle_test_email' );

/**
 * Settings page HTML.
 */
function tutti_frutti_smtp_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    settings_errors( 'tutti_frutti_email' );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Tutti Frutti Email (SMTP)', 'tutti-frutti-cafe' ); ?></h1>
        <p><?php esc_html_e( 'Send contact and notification emails through your SMTP provider (Gmail, Outlook, SendGrid, etc.).', 'tutti-frutti-cafe' ); ?></p>
        <p><strong><?php esc_html_e( 'Gmail:', 'tutti-frutti-cafe' ); ?></strong> <?php esc_html_e( 'Use an App Password (not your regular password). Host: smtp.gmail.com, Port: 587, Encryption: TLS.', 'tutti-frutti-cafe' ); ?></p>
        <p><?php esc_html_e( 'Contact recipients and CC addresses are set under Appearance → Customize → Email Settings.', 'tutti-frutti-cafe' ); ?></p>
        <form method="post" action="options.php">
            <?php settings_fields( 'tutti_frutti_email' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e( 'Enable SMTP', 'tutti-frutti-cafe' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="tf_smtp_enabled" value="1" <?php checked( get_option( 'tf_smtp_enabled' ), '1' ); ?>>
                            <?php esc_html_e( 'Use SMTP instead of default server mail', 'tutti-frutti-cafe' ); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th><label for="tf_smtp_host"><?php esc_html_e( 'SMTP Host', 'tutti-frutti-cafe' ); ?></label></th>
                    <td><input type="text" id="tf_smtp_host" name="tf_smtp_host" value="<?php echo esc_attr( get_option( 'tf_smtp_host', '' ) ); ?>" class="regular-text" placeholder="smtp.gmail.com"></td>
                </tr>
                <tr>
                    <th><label for="tf_smtp_port"><?php esc_html_e( 'SMTP Port', 'tutti-frutti-cafe' ); ?></label></th>
                    <td><input type="number" id="tf_smtp_port" name="tf_smtp_port" value="<?php echo esc_attr( get_option( 'tf_smtp_port', '587' ) ); ?>" class="small-text"></td>
                </tr>
                <tr>
                    <th><label for="tf_smtp_encryption"><?php esc_html_e( 'Encryption', 'tutti-frutti-cafe' ); ?></label></th>
                    <td>
                        <select id="tf_smtp_encryption" name="tf_smtp_encryption">
                            <?php
                            $enc = get_option( 'tf_smtp_encryption', 'tls' );
                            foreach ( array( 'none' => 'None', 'ssl' => 'SSL', 'tls' => 'TLS' ) as $val => $label ) {
                                printf( '<option value="%s" %s>%s</option>', esc_attr( $val ), selected( $enc, $val, false ), esc_html( $label ) );
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="tf_smtp_username"><?php esc_html_e( 'SMTP Username', 'tutti-frutti-cafe' ); ?></label></th>
                    <td><input type="text" id="tf_smtp_username" name="tf_smtp_username" value="<?php echo esc_attr( get_option( 'tf_smtp_username', '' ) ); ?>" class="regular-text" autocomplete="off"></td>
                </tr>
                <tr>
                    <th><label for="tf_smtp_password"><?php esc_html_e( 'SMTP Password', 'tutti-frutti-cafe' ); ?></label></th>
                    <td><input type="password" id="tf_smtp_password" name="tf_smtp_password" value="<?php echo esc_attr( get_option( 'tf_smtp_password', '' ) ); ?>" class="regular-text" autocomplete="new-password"></td>
                </tr>
                <tr>
                    <th><label for="tf_smtp_from_email"><?php esc_html_e( 'From Email', 'tutti-frutti-cafe' ); ?></label></th>
                    <td><input type="email" id="tf_smtp_from_email" name="tf_smtp_from_email" value="<?php echo esc_attr( get_option( 'tf_smtp_from_email', get_option( 'admin_email' ) ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="tf_smtp_from_name"><?php esc_html_e( 'From Name', 'tutti-frutti-cafe' ); ?></label></th>
                    <td><input type="text" id="tf_smtp_from_name" name="tf_smtp_from_name" value="<?php echo esc_attr( get_option( 'tf_smtp_from_name', get_bloginfo( 'name' ) ) ); ?>" class="regular-text"></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>

        <hr>

        <h2><?php esc_html_e( 'Send Test Email', 'tutti-frutti-cafe' ); ?></h2>
        <p><?php esc_html_e( 'Sends a test message to the site admin email using your current SMTP settings.', 'tutti-frutti-cafe' ); ?></p>
        <form method="post">
            <?php wp_nonce_field( 'tf_test_email', 'tf_test_email_nonce' ); ?>
            <p>
                <button type="submit" name="tf_send_test_email" value="1" class="button button-secondary">
                    <?php esc_html_e( 'Send Test Email', 'tutti-frutti-cafe' ); ?>
                </button>
                <span class="description" style="margin-left:12px;">
                    <?php
                    printf(
                        /* translators: %s: admin email */
                        esc_html__( 'Recipient: %s', 'tutti-frutti-cafe' ),
                        esc_html( get_option( 'admin_email' ) )
                    );
                    ?>
                </span>
            </p>
        </form>

        <?php if ( function_exists( 'tutti_frutti_get_recent_email_logs' ) ) : ?>
            <hr>
            <h2><?php esc_html_e( 'Recent Email Logs', 'tutti-frutti-cafe' ); ?></h2>
            <p>
                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=tf_email_log' ) ); ?>" class="button button-link">
                    <?php esc_html_e( 'View all logs', 'tutti-frutti-cafe' ); ?>
                </a>
            </p>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Date', 'tutti-frutti-cafe' ); ?></th>
                        <th><?php esc_html_e( 'To', 'tutti-frutti-cafe' ); ?></th>
                        <th><?php esc_html_e( 'Subject', 'tutti-frutti-cafe' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'tutti-frutti-cafe' ); ?></th>
                        <th><?php esc_html_e( 'Error', 'tutti-frutti-cafe' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $logs = tutti_frutti_get_recent_email_logs( 15 );
                    if ( empty( $logs ) ) :
                        ?>
                        <tr><td colspan="5"><?php esc_html_e( 'No email logs yet.', 'tutti-frutti-cafe' ); ?></td></tr>
                    <?php else : ?>
                        <?php foreach ( $logs as $log ) : ?>
                            <tr>
                                <td><?php echo esc_html( get_the_date( 'Y-m-d H:i', $log ) ); ?></td>
                                <td><?php echo esc_html( get_post_meta( $log->ID, '_tf_email_to', true ) ); ?></td>
                                <td><?php echo esc_html( $log->post_title ); ?></td>
                                <td><?php echo esc_html( get_post_meta( $log->ID, '_tf_email_status', true ) ); ?></td>
                                <td><?php echo esc_html( get_post_meta( $log->ID, '_tf_email_error', true ) ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Configure PHPMailer when SMTP is enabled.
 *
 * @param PHPMailer $phpmailer Mailer.
 */
function tutti_frutti_configure_smtp( $phpmailer ) {
    if ( '1' !== get_option( 'tf_smtp_enabled' ) ) {
        return;
    }

    $host = get_option( 'tf_smtp_host', '' );
    if ( ! $host ) {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host       = $host;
    $phpmailer->Port       = (int) get_option( 'tf_smtp_port', 587 );
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Username   = get_option( 'tf_smtp_username', '' );
    $phpmailer->Password   = get_option( 'tf_smtp_password', '' );

    $encryption = get_option( 'tf_smtp_encryption', 'tls' );
    if ( 'ssl' === $encryption ) {
        $phpmailer->SMTPSecure = 'ssl';
    } elseif ( 'tls' === $encryption ) {
        $phpmailer->SMTPSecure = 'tls';
    } else {
        $phpmailer->SMTPSecure  = '';
        $phpmailer->SMTPAutoTLS = false;
    }

    $from_email = get_option( 'tf_smtp_from_email', '' );
    $from_name  = get_option( 'tf_smtp_from_name', '' );
    if ( $from_email && is_email( $from_email ) ) {
        $phpmailer->setFrom( $from_email, $from_name ? $from_name : get_bloginfo( 'name' ) );
    }
}
add_action( 'phpmailer_init', 'tutti_frutti_configure_smtp' );
