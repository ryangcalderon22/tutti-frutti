<?php
/**
 * Template Name: Contact
 *
 * @package Tutti_Frutti_Cafe
 */

get_header();
?>

<main id="primary" class="site-main page-contact site-main--page">

<section class="page-section page-section--cream page-section--top">

<div class="container">

    <h2 class="section-title section-title--contact">
        We'd Love to Hear From You
    </h2>

    <div class="contact-layout">

        <!-- LEFT COLUMN -->
        <aside class="contact-info">

            <?php
            $contact_title_image = get_theme_mod( 'tf_contact_title_image', '' );
            $contact_title_text  = get_theme_mod( 'tf_contact_title_text', 'Tutti Frutti Cafe' );
            ?>
            <?php if ( $contact_title_image ) : ?>
                <img src="<?php echo esc_url( $contact_title_image ); ?>" alt="<?php echo esc_attr( $contact_title_text ); ?>" class="contact-info__title-image">
            <?php elseif ( $contact_title_text ) : ?>
                <h2><?php echo esc_html( $contact_title_text ); ?></h2>
            <?php endif; ?>

            <div class="contact-item">
                <h3>Address</h3>

                <p>
                    2357 Foothill Blvd<br>
                    La Verne, CA 91750
                </p>
            </div>

            <div class="contact-item">

                <h3>Phone</h3>

                <p>(909) 245-1383</p>

            </div>

            <div class="contact-item">

                <h3>Email</h3>

                <p>
                    <a href="mailto:GM@TFCLaverne.com">GM@TFCLaverne.com</a>
                </p>


            <!-- <div class="contact-item">

                <h3>Business Hours</h3>

                <p>
                    Monday – Thursday<br>
                    10:00 AM – 9:00 PM
                </p>

                <p>
                    Friday – Sunday<br>
                    10:00 AM – 10:00 PM
                </p>

            </div> -->

        </aside>

        <!-- RIGHT COLUMN -->
        <section class="contact-form-wrapper">

            <!-- <h2 class="contact-form-title">
                Contact Form
            </h2> -->

            <?php tutti_frutti_render_contact_notice(); ?>

            <?php $tf_recaptcha_site_key = get_theme_mod( 'tf_recaptcha_site_key', '' ); ?>
            <form
                class="contact-form tf-form"
                action="<?php echo esc_url( tutti_frutti_page_url( 'contact' ) ); ?>"
                method="post"
                <?php echo $tf_recaptcha_site_key ? ' data-recaptcha="1"' : ''; ?>>

                <?php wp_nonce_field( 'tf_contact_form', 'tf_contact_nonce' ); ?>
                <?php if ( $tf_recaptcha_site_key ) : ?>
                    <input type="hidden" name="g-recaptcha-response" value="">
                <?php endif; ?>

                <div class="tf-form__grid">

                    <div class="tf-form__field">

                        <label for="contact_first_name">
                            <?php esc_html_e( 'First Name', 'tutti-frutti-cafe' ); ?> *
                        </label>

                        <input
                            type="text"
                            id="contact_first_name"
                            name="contact_first_name"
                            required>

                    </div>

                    <div class="tf-form__field">

                        <label for="contact_last_name">
                            <?php esc_html_e( 'Last Name', 'tutti-frutti-cafe' ); ?> *
                        </label>

                        <input
                            type="text"
                            id="contact_last_name"
                            name="contact_last_name"
                            required>

                    </div>

                    <div class="tf-form__field">

                        <label for="contact_email">
                            <?php esc_html_e( 'Email', 'tutti-frutti-cafe' ); ?> *
                        </label>

                        <input
                            type="email"
                            id="contact_email"
                            name="contact_email"
                            required>

                    </div>

                    <div class="tf-form__field">

                        <label for="contact_phone">
                            <?php esc_html_e( 'Phone', 'tutti-frutti-cafe' ); ?>
                        </label>

                        <input
                            type="text"
                            id="contact_phone"
                            name="contact_phone">

                    </div>

                    <div class="tf-form__field tf-form__field--full">

                        <label for="contact_message">
                            <?php esc_html_e( 'Message', 'tutti-frutti-cafe' ); ?> *
                        </label>

                        <textarea
                            id="contact_message"
                            name="contact_message"
                            rows="6"
                            required></textarea>

                    </div>

                    <div class="tf-form__field tf-form__field--full tf-form__actions">

                        <button
                            type="submit"
                            name="tf_contact_submit"
                            value="1"
                            class="btn btn-brown">

                            <?php esc_html_e( 'Send Message', 'tutti-frutti-cafe' ); ?>

                        </button>

                    </div>

                </div>

            </form>

        </section>

    </div>

</div>

</section>

<?php get_template_part( 'template-parts/page-editable-content' ); ?>

</main>

<?php get_footer(); ?>