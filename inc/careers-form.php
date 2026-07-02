<?php
/**
 * Careers application form handler.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * @return string
 */
function tutti_frutti_careers_notice_key() {
    $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'guest';
    return md5( 'careers_' . $ip . wp_salt() );
}

/**
 * @param string $type success|error.
 * @param string $message Message.
 */
function tutti_frutti_set_careers_notice( $type, $message ) {
    set_transient( 'tf_careers_notice_' . tutti_frutti_careers_notice_key(), array( 'type' => $type, 'message' => $message ), 120 );
}

/**
 * @return array|null
 */
function tutti_frutti_get_careers_notice() {
    $status = isset( $_GET['careers'] ) ? sanitize_key( wp_unslash( $_GET['careers'] ) ) : '';
    if ( ! in_array( $status, array( 'sent', 'error' ), true ) ) {
        return null;
    }

    $data = get_transient( 'tf_careers_notice_' . tutti_frutti_careers_notice_key() );
    if ( $data ) {
        delete_transient( 'tf_careers_notice_' . tutti_frutti_careers_notice_key() );
        return $data;
    }

    if ( 'sent' === $status ) {
        return array(
            'type'    => 'success',
            'message' => __( 'Thank you! Your application has been submitted.', 'tutti-frutti-cafe' ),
        );
    }

    return array(
        'type'    => 'error',
        'message' => __( 'Could not submit your application. Please try again.', 'tutti-frutti-cafe' ),
    );
}

/**
 * Render careers notice.
 */
function tutti_frutti_render_careers_notice() {
    $notice = tutti_frutti_get_careers_notice();
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
 * Allowed resume mime types.
 *
 * @return array
 */
function tutti_frutti_resume_mime_types() {
    return array(
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    );
}

/**
 * Handle careers form POST.
 */
function tutti_frutti_handle_careers_form() {
    if ( ! isset( $_POST['tf_careers_submit'] ) ) {
        return;
    }

    $redirect = tutti_frutti_page_url( 'careers' );

    if ( ! isset( $_POST['tf_careers_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tf_careers_nonce'] ) ), 'tf_careers_form' ) ) {
        tutti_frutti_set_careers_notice( 'error', __( 'Security check failed. Please try again.', 'tutti-frutti-cafe' ) );
        wp_safe_redirect( add_query_arg( 'careers', 'error', $redirect ) );
        exit;
    }

    $name    = isset( $_POST['careers_name'] ) ? sanitize_text_field( wp_unslash( $_POST['careers_name'] ) ) : '';
    $email   = isset( $_POST['careers_email'] ) ? sanitize_email( wp_unslash( $_POST['careers_email'] ) ) : '';
    $phone   = isset( $_POST['careers_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['careers_phone'] ) ) : '';
    $message = isset( $_POST['careers_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['careers_message'] ) ) : '';
    $job_id  = isset( $_POST['careers_job_id'] ) ? absint( $_POST['careers_job_id'] ) : 0;

    if ( empty( $name ) || empty( $email ) || ! is_email( $email ) ) {
        tutti_frutti_set_careers_notice( 'error', __( 'Please enter your name and a valid email.', 'tutti-frutti-cafe' ) );
        wp_safe_redirect( add_query_arg( 'careers', 'error', $redirect ) );
        exit;
    }

    if ( $job_id ) {
        $job = get_post( $job_id );
        if ( ! $job || 'tf_job' !== $job->post_type || 'publish' !== $job->post_status ) {
            $job_id = 0;
        }
    }

    $resume_path = '';
    $resume_file = '';

    if ( ! empty( $_FILES['careers_resume']['name'] ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';

        $overrides = array(
            'test_form' => false,
            'mimes'     => tutti_frutti_resume_mime_types(),
        );

        $upload = wp_handle_upload( $_FILES['careers_resume'], $overrides );

        if ( isset( $upload['error'] ) ) {
            tutti_frutti_set_careers_notice( 'error', esc_html( $upload['error'] ) );
            wp_safe_redirect( add_query_arg( 'careers', 'error', $redirect ) );
            exit;
        }

        $size = isset( $_FILES['careers_resume']['size'] ) ? (int) $_FILES['careers_resume']['size'] : 0;
        if ( $size > 5 * 1024 * 1024 ) {
            tutti_frutti_set_careers_notice( 'error', __( 'Resume must be 5MB or smaller.', 'tutti-frutti-cafe' ) );
            wp_safe_redirect( add_query_arg( 'careers', 'error', $redirect ) );
            exit;
        }

        $resume_path = $upload['file'];
        $resume_file = basename( $resume_path );
    }

    $app_id = wp_insert_post(
        array(
            'post_type'    => 'tf_application',
            'post_title'   => $name,
            'post_content' => $message,
            'post_status'  => 'publish',
        )
    );

    if ( is_wp_error( $app_id ) || ! $app_id ) {
        tutti_frutti_set_careers_notice( 'error', __( 'Could not save your application.', 'tutti-frutti-cafe' ) );
        wp_safe_redirect( add_query_arg( 'careers', 'error', $redirect ) );
        exit;
    }

    update_post_meta( $app_id, '_tf_email', $email );
    update_post_meta( $app_id, '_tf_phone', $phone );
    update_post_meta( $app_id, '_tf_job_id', $job_id );
    if ( $resume_path ) {
        update_post_meta( $app_id, '_tf_resume_path', $resume_path );
        update_post_meta( $app_id, '_tf_resume_file', $resume_file );
    }

    $job_title = $job_id ? get_the_title( $job_id ) : __( 'General Application', 'tutti-frutti-cafe' );
    $site_name = get_bloginfo( 'name' );

    $admin_subject = sprintf(
        __( '[%1$s] Job application from %2$s', 'tutti-frutti-cafe' ),
        $site_name,
        $name
    );
    $admin_body = sprintf(
        "Name: %s\nEmail: %s\nPhone: %s\nPosition: %s\n\nMessage:\n%s\n\nView in admin: %s",
        $name,
        $email,
        $phone ? $phone : '(none)',
        $job_title,
        $message ? $message : '(none)',
        admin_url( 'edit.php?post_type=tf_application' )
    );

    $attachments = $resume_path ? array( $resume_path ) : array();
    tutti_frutti_send_mail_to_recipients(
        tutti_frutti_get_careers_admin_emails(),
        $admin_subject,
        $admin_body,
        array( 'Reply-To: ' . $email ),
        $attachments
    );

    if ( get_theme_mod( 'tf_careers_applicant_email', true ) ) {
        $applicant_subject = get_theme_mod(
            'tf_careers_applicant_subject',
            __( 'We received your application — Tutti Frutti Café', 'tutti-frutti-cafe' )
        );
        $applicant_body = get_theme_mod( 'tf_careers_applicant_body', '' );
        if ( ! $applicant_body ) {
            $applicant_body = sprintf(
                "Hi %s,\n\nThank you for applying to Tutti Frutti Café (%s). We have received your application and will be in touch if your qualifications match our needs.\n\n— Tutti Frutti Café Team",
                $name,
                $job_title
            );
        }
        if ( function_exists( 'tutti_frutti_wp_mail' ) ) {
            tutti_frutti_wp_mail( $email, $applicant_subject, $applicant_body );
        } else {
            wp_mail( $email, $applicant_subject, $applicant_body );
        }
    }

    tutti_frutti_set_careers_notice( 'success', __( 'Thank you! Your application has been submitted.', 'tutti-frutti-cafe' ) );
    wp_safe_redirect( add_query_arg( 'careers', 'sent', $redirect ) );
    exit;
}
add_action( 'template_redirect', 'tutti_frutti_handle_careers_form' );

/**
 * Render careers application form.
 *
 * @param int    $job_id Optional job ID.
 * @param string $heading Form heading.
 */
function tutti_frutti_render_careers_form( $job_id = 0, $heading = '' ) {
    if ( ! $heading ) {
        $heading = __( 'Apply Now', 'tutti-frutti-cafe' );
    }
    $form_id = 'careers-form';
    $jobs    = tutti_frutti_get_active_jobs();
    ?>
    <form id="<?php echo esc_attr( $form_id ); ?>" class="careers-form tf-form" method="post" enctype="multipart/form-data" action="<?php echo esc_url( tutti_frutti_page_url( 'careers' ) ); ?>">
        <?php wp_nonce_field( 'tf_careers_form', 'tf_careers_nonce' ); ?>
        <h3 class="careers-form__title section-title"><?php echo esc_html( $heading ); ?></h3>
        <div class="tf-form__grid">
            <?php if ( ! empty( $jobs ) && ! $job_id ) : ?>
                <div class="tf-form__field tf-form__field--full">
                    <label for="<?php echo esc_attr( $form_id ); ?>-job"><?php esc_html_e( 'Position', 'tutti-frutti-cafe' ); ?></label>
                    <select id="<?php echo esc_attr( $form_id ); ?>-job" name="careers_job_id">
                        <option value=""><?php esc_html_e( 'General Application', 'tutti-frutti-cafe' ); ?></option>
                        <?php foreach ( $jobs as $job ) : ?>
                            <option value="<?php echo esc_attr( $job->ID ); ?>"><?php echo esc_html( $job->post_title ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else : ?>
                <input type="hidden" name="careers_job_id" value="<?php echo esc_attr( $job_id ); ?>">
            <?php endif; ?>
            <div class="tf-form__field">
                <label for="<?php echo esc_attr( $form_id ); ?>-name"><?php esc_html_e( 'Full Name', 'tutti-frutti-cafe' ); ?> *</label>
                <input type="text" id="<?php echo esc_attr( $form_id ); ?>-name" name="careers_name" required>
            </div>
            <div class="tf-form__field">
                <label for="<?php echo esc_attr( $form_id ); ?>-email"><?php esc_html_e( 'Email', 'tutti-frutti-cafe' ); ?> *</label>
                <input type="email" id="<?php echo esc_attr( $form_id ); ?>-email" name="careers_email" required>
            </div>
            <div class="tf-form__field">
                <label for="<?php echo esc_attr( $form_id ); ?>-phone"><?php esc_html_e( 'Phone', 'tutti-frutti-cafe' ); ?></label>
                <input type="tel" id="<?php echo esc_attr( $form_id ); ?>-phone" name="careers_phone">
            </div>
            <div class="tf-form__field tf-form__field--full">
                <label for="<?php echo esc_attr( $form_id ); ?>-message"><?php esc_html_e( 'Message / Cover letter', 'tutti-frutti-cafe' ); ?></label>
                <textarea id="<?php echo esc_attr( $form_id ); ?>-message" name="careers_message" rows="5"></textarea>
            </div>
            <div class="tf-form__field tf-form__field--full">
                <label for="<?php echo esc_attr( $form_id ); ?>-resume"><?php esc_html_e( 'Resume (PDF or Word, max 5MB)', 'tutti-frutti-cafe' ); ?></label>
                <input type="file" id="<?php echo esc_attr( $form_id ); ?>-resume" name="careers_resume" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
            </div>
            <div class="tf-form__field tf-form__field--full tf-form__actions">
                <button type="submit" name="tf_careers_submit" class="btn btn-brown"><?php esc_html_e( 'Submit Application', 'tutti-frutti-cafe' ); ?></button>
            </div>
        </div>
    </form>
    <?php
}
