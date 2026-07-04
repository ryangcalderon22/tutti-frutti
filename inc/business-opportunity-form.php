<?php
/**
 * Business Opportunity form handler.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * @return string
 */
function tutti_frutti_business_notice_key() {
    $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'guest';
    return md5( 'business_' . $ip . wp_salt() );
}

/**
 * @param string $type success|error.
 * @param string $message Message.
 */
function tutti_frutti_set_business_notice( $type, $message ) {
    set_transient( 'tf_business_notice_' . tutti_frutti_business_notice_key(), array( 'type' => $type, 'message' => $message ), 120 );
}

/**
 * @return array|null
 */
function tutti_frutti_get_business_notice() {
    $status = isset( $_GET['business'] ) ? sanitize_key( wp_unslash( $_GET['business'] ) ) : '';
    if ( ! in_array( $status, array( 'sent', 'error' ), true ) ) {
        return null;
    }

    $data = get_transient( 'tf_business_notice_' . tutti_frutti_business_notice_key() );
    if ( $data ) {
        delete_transient( 'tf_business_notice_' . tutti_frutti_business_notice_key() );
        return $data;
    }

    if ( 'sent' === $status ) {
        return array(
            'type'    => 'success',
            'message' => __( 'Thank you! Your proposal has been submitted.', 'tutti-frutti-cafe' ),
        );
    }

    return array(
        'type'    => 'error',
        'message' => __( 'Could not submit your proposal. Please try again.', 'tutti-frutti-cafe' ),
    );
}

/**
 * Render business opportunity notice.
 */
function tutti_frutti_render_business_notice() {
    $notice = tutti_frutti_get_business_notice();
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
 * Allowed proposal mime types.
 *
 * @return array
 */
function tutti_frutti_proposal_mime_types() {
    return array(
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    );
}

/**
 * Handle business opportunity form POST.
 */
function tutti_frutti_handle_business_form() {
    if ( ! isset( $_POST['tf_business_submit'] ) ) {
        return;
    }

    $redirect = tutti_frutti_page_url( 'business-opportunity' );

    if ( ! isset( $_POST['tf_business_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tf_business_nonce'] ) ), 'tf_business_form' ) ) {
        tutti_frutti_set_business_notice( 'error', __( 'Security check failed. Please try again.', 'tutti-frutti-cafe' ) );
        wp_safe_redirect( add_query_arg( 'business', 'error', $redirect ) );
        exit;
    }

    $first_name = isset( $_POST['business_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['business_first_name'] ) ) : '';
    $last_name  = isset( $_POST['business_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['business_last_name'] ) ) : '';
    $email      = isset( $_POST['business_email'] ) ? sanitize_email( wp_unslash( $_POST['business_email'] ) ) : '';
    $mobile     = isset( $_POST['business_mobile'] ) ? sanitize_text_field( wp_unslash( $_POST['business_mobile'] ) ) : '';
    $city       = isset( $_POST['business_city'] ) ? sanitize_text_field( wp_unslash( $_POST['business_city'] ) ) : '';
    $state      = isset( $_POST['business_state'] ) ? sanitize_text_field( wp_unslash( $_POST['business_state'] ) ) : '';
    $zip        = isset( $_POST['business_zip'] ) ? sanitize_text_field( wp_unslash( $_POST['business_zip'] ) ) : '';
    $social_x   = isset( $_POST['business_social_x'] ) ? sanitize_text_field( wp_unslash( $_POST['business_social_x'] ) ) : '';
    $social_fb  = isset( $_POST['business_social_facebook'] ) ? sanitize_text_field( wp_unslash( $_POST['business_social_facebook'] ) ) : '';
    $social_ig  = isset( $_POST['business_social_instagram'] ) ? sanitize_text_field( wp_unslash( $_POST['business_social_instagram'] ) ) : '';
    $message    = isset( $_POST['business_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['business_message'] ) ) : '';
    $name       = trim( $first_name . ' ' . $last_name );

    if ( empty( $first_name ) || empty( $last_name ) || empty( $email ) || ! is_email( $email ) || empty( $mobile ) || empty( $city ) || empty( $state ) || empty( $zip ) ) {
        tutti_frutti_set_business_notice( 'error', __( 'Please fill in all required fields with a valid email.', 'tutti-frutti-cafe' ) );
        wp_safe_redirect( add_query_arg( 'business', 'error', $redirect ) );
        exit;
    }

    $proposal_path = '';
    $proposal_file = '';
    $proposal_url  = '';

    if ( empty( $_FILES['business_proposal']['name'] ) ) {
        tutti_frutti_set_business_notice( 'error', __( 'Please attach your proposal.', 'tutti-frutti-cafe' ) );
        wp_safe_redirect( add_query_arg( 'business', 'error', $redirect ) );
        exit;
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';

    $overrides = array(
        'test_form' => false,
        'mimes'     => tutti_frutti_proposal_mime_types(),
    );

    $upload = wp_handle_upload( $_FILES['business_proposal'], $overrides );

    if ( isset( $upload['error'] ) ) {
        tutti_frutti_set_business_notice( 'error', esc_html( $upload['error'] ) );
        wp_safe_redirect( add_query_arg( 'business', 'error', $redirect ) );
        exit;
    }

    $size = isset( $_FILES['business_proposal']['size'] ) ? (int) $_FILES['business_proposal']['size'] : 0;
    if ( $size > 5 * 1024 * 1024 ) {
        tutti_frutti_set_business_notice( 'error', __( 'Proposal must be 5MB or smaller.', 'tutti-frutti-cafe' ) );
        wp_safe_redirect( add_query_arg( 'business', 'error', $redirect ) );
        exit;
    }

    $proposal_path = $upload['file'];
    $proposal_file = basename( $proposal_path );
    $proposal_url  = isset( $upload['url'] ) ? $upload['url'] : '';

    $inquiry_id = wp_insert_post(
        array(
            'post_type'    => 'tf_business_inquiry',
            'post_title'   => $name,
            'post_content' => $message,
            'post_status'  => 'publish',
        )
    );

    if ( is_wp_error( $inquiry_id ) || ! $inquiry_id ) {
        tutti_frutti_set_business_notice( 'error', __( 'Could not save your submission.', 'tutti-frutti-cafe' ) );
        wp_safe_redirect( add_query_arg( 'business', 'error', $redirect ) );
        exit;
    }

    update_post_meta( $inquiry_id, '_tf_first_name', $first_name );
    update_post_meta( $inquiry_id, '_tf_last_name', $last_name );
    update_post_meta( $inquiry_id, '_tf_email', $email );
    update_post_meta( $inquiry_id, '_tf_mobile', $mobile );
    update_post_meta( $inquiry_id, '_tf_city', $city );
    update_post_meta( $inquiry_id, '_tf_state', $state );
    update_post_meta( $inquiry_id, '_tf_zip', $zip );
    update_post_meta( $inquiry_id, '_tf_social_x', $social_x );
    update_post_meta( $inquiry_id, '_tf_social_facebook', $social_fb );
    update_post_meta( $inquiry_id, '_tf_social_instagram', $social_ig );
    if ( $proposal_path ) {
        update_post_meta( $inquiry_id, '_tf_proposal_path', $proposal_path );
        update_post_meta( $inquiry_id, '_tf_proposal_file', $proposal_file );
        update_post_meta( $inquiry_id, '_tf_proposal_url', $proposal_url );
    }

    tutti_frutti_set_business_notice( 'success', __( 'Thank you! Your proposal has been submitted.', 'tutti-frutti-cafe' ) );
    wp_safe_redirect( add_query_arg( 'business', 'sent', $redirect ) );

    // Finish the HTTP response now so the visitor isn't stuck waiting on the
    // mail server — email sending can be slow (or hang) if SMTP isn't
    // reachable. Do it after the redirect has already been delivered.
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
        __( '[%1$s] Business opportunity inquiry from %2$s', 'tutti-frutti-cafe' ),
        $site_name,
        $name
    );
    $admin_body = sprintf(
        "Name: %s\nEmail: %s\nMobile: %s\nCity/State/Zip: %s, %s %s\nX: %s\nFacebook: %s\nInstagram: %s\n\nMessage:\n%s\n\nView in admin: %s",
        $name,
        $email,
        $mobile,
        $city,
        $state,
        $zip,
        $social_x ? $social_x : '(none)',
        $social_fb ? $social_fb : '(none)',
        $social_ig ? $social_ig : '(none)',
        $message ? $message : '(none)',
        admin_url( 'edit.php?post_type=tf_business_inquiry' )
    );

    $attachments = $proposal_path ? array( $proposal_path ) : array();
    tutti_frutti_send_mail_to_recipients(
        tutti_frutti_get_business_admin_emails(),
        $admin_subject,
        $admin_body,
        array( 'Reply-To: ' . $email ),
        $attachments
    );

    if ( get_theme_mod( 'tf_business_applicant_email', true ) ) {
        $applicant_subject = get_theme_mod(
            'tf_business_applicant_subject',
            __( 'We received your submission — Tutti Frutti Café', 'tutti-frutti-cafe' )
        );
        $applicant_body = get_theme_mod( 'tf_business_applicant_body', '' );
        if ( ! $applicant_body ) {
            $applicant_body = sprintf(
                "Hi %s,\n\nThank you for your interest in a business opportunity with Tutti Frutti Café. We have received your submission and will be in touch soon.\n\n— Tutti Frutti Café Team",
                $name
            );
        }
        if ( function_exists( 'tutti_frutti_wp_mail' ) ) {
            tutti_frutti_wp_mail( $email, $applicant_subject, $applicant_body );
        } else {
            wp_mail( $email, $applicant_subject, $applicant_body );
        }
    }

    exit;
}
add_action( 'template_redirect', 'tutti_frutti_handle_business_form' );

/**
 * Render business opportunity form.
 *
 * @param string $heading Form heading.
 */
function tutti_frutti_render_business_form( $heading = '' ) {
    if ( ! $heading ) {
        $heading = __( 'Submit Your Proposal', 'tutti-frutti-cafe' );
    }
    $form_id = 'business-form';
    ?>
    <form id="<?php echo esc_attr( $form_id ); ?>" class="business-form tf-form" method="post" enctype="multipart/form-data" action="<?php echo esc_url( tutti_frutti_page_url( 'business-opportunity' ) ); ?>">
        <?php wp_nonce_field( 'tf_business_form', 'tf_business_nonce' ); ?>
        <h3 class="business-form__title section-title"><?php echo esc_html( $heading ); ?></h3>
        <div class="tf-form__grid">
            <div class="tf-form__field">
                <label for="<?php echo esc_attr( $form_id ); ?>-first-name"><?php esc_html_e( 'First Name', 'tutti-frutti-cafe' ); ?> *</label>
                <input type="text" id="<?php echo esc_attr( $form_id ); ?>-first-name" name="business_first_name" required>
            </div>
            <div class="tf-form__field">
                <label for="<?php echo esc_attr( $form_id ); ?>-last-name"><?php esc_html_e( 'Last Name', 'tutti-frutti-cafe' ); ?> *</label>
                <input type="text" id="<?php echo esc_attr( $form_id ); ?>-last-name" name="business_last_name" required>
            </div>
            <div class="tf-form__field">
                <label for="<?php echo esc_attr( $form_id ); ?>-mobile"><?php esc_html_e( 'Mobile Number', 'tutti-frutti-cafe' ); ?> *</label>
                <input type="tel" id="<?php echo esc_attr( $form_id ); ?>-mobile" name="business_mobile" required>
            </div>
            <div class="tf-form__field">
                <label for="<?php echo esc_attr( $form_id ); ?>-email"><?php esc_html_e( 'Email', 'tutti-frutti-cafe' ); ?> *</label>
                <input type="email" id="<?php echo esc_attr( $form_id ); ?>-email" name="business_email" required>
            </div>
            <div class="tf-form__field">
                <label for="<?php echo esc_attr( $form_id ); ?>-city"><?php esc_html_e( 'City', 'tutti-frutti-cafe' ); ?> *</label>
                <input type="text" id="<?php echo esc_attr( $form_id ); ?>-city" name="business_city" required>
            </div>
            <div class="tf-form__field">
                <label for="<?php echo esc_attr( $form_id ); ?>-state"><?php esc_html_e( 'State', 'tutti-frutti-cafe' ); ?> *</label>
                <input type="text" id="<?php echo esc_attr( $form_id ); ?>-state" name="business_state" required>
            </div>
            <div class="tf-form__field">
                <label for="<?php echo esc_attr( $form_id ); ?>-zip"><?php esc_html_e( 'Zip', 'tutti-frutti-cafe' ); ?> *</label>
                <input type="text" id="<?php echo esc_attr( $form_id ); ?>-zip" name="business_zip" required>
            </div>

            <h4 class="tf-form__subheading tf-form__field--full"><?php esc_html_e( 'Social Media', 'tutti-frutti-cafe' ); ?></h4>

            <div class="tf-form__field">
                <label for="<?php echo esc_attr( $form_id ); ?>-social-x"><?php esc_html_e( 'X (Twitter)', 'tutti-frutti-cafe' ); ?></label>
                <input type="text" id="<?php echo esc_attr( $form_id ); ?>-social-x" name="business_social_x" placeholder="@handle">
            </div>
            <div class="tf-form__field">
                <label for="<?php echo esc_attr( $form_id ); ?>-social-facebook"><?php esc_html_e( 'Facebook', 'tutti-frutti-cafe' ); ?></label>
                <input type="text" id="<?php echo esc_attr( $form_id ); ?>-social-facebook" name="business_social_facebook" placeholder="facebook.com/yourname">
            </div>
            <div class="tf-form__field">
                <label for="<?php echo esc_attr( $form_id ); ?>-social-instagram"><?php esc_html_e( 'Instagram', 'tutti-frutti-cafe' ); ?></label>
                <input type="text" id="<?php echo esc_attr( $form_id ); ?>-social-instagram" name="business_social_instagram" placeholder="@handle">
            </div>

            <div class="tf-form__field tf-form__field--full">
                <label for="<?php echo esc_attr( $form_id ); ?>-message"><?php esc_html_e( 'Message / Additional Details', 'tutti-frutti-cafe' ); ?></label>
                <textarea id="<?php echo esc_attr( $form_id ); ?>-message" name="business_message" rows="5"></textarea>
            </div>
            <div class="tf-form__field tf-form__field--full">
                <label for="<?php echo esc_attr( $form_id ); ?>-proposal"><?php esc_html_e( 'Upload Proposal (PDF or Word, max 5MB)', 'tutti-frutti-cafe' ); ?> *</label>
                <input type="file" id="<?php echo esc_attr( $form_id ); ?>-proposal" name="business_proposal" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
            </div>
            <div class="tf-form__field tf-form__field--full tf-form__actions">
                <button type="submit" name="tf_business_submit" class="btn btn-brown"><?php esc_html_e( 'Submit Proposal', 'tutti-frutti-cafe' ); ?></button>
            </div>
        </div>
    </form>
    <?php
}
