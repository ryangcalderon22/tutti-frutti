<?php
/**
 * Page hero meta: description and two CTA buttons.
 *
 * Used by page templates that render a hero — currently template-menu.php.
 * Read the values with tutti_frutti_get_page_hero().
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register the meta box on pages.
 */
function tutti_frutti_page_hero_meta_box() {
    add_meta_box(
        'tf_page_hero',
        __( 'Page Hero — Description & Buttons', 'tutti-frutti-cafe' ),
        'tutti_frutti_page_hero_meta_render',
        'page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'tutti_frutti_page_hero_meta_box' );

/**
 * Render the meta box.
 *
 * @param WP_Post $post Current page.
 */
function tutti_frutti_page_hero_meta_render( $post ) {
    wp_nonce_field( 'tf_page_hero_meta', 'tf_page_hero_meta_nonce' );

    $desc = get_post_meta( $post->ID, '_tf_page_hero_desc', true );
    ?>
    <p><em><?php esc_html_e( 'Leave a field empty to hide it. A button needs both a label and a URL before it renders.', 'tutti-frutti-cafe' ); ?></em></p>
    <table class="form-table">
        <tr>
            <th><label for="tf_page_hero_desc"><?php esc_html_e( 'Hero description', 'tutti-frutti-cafe' ); ?></label></th>
            <td>
                <textarea id="tf_page_hero_desc" name="tf_page_hero_desc" class="large-text" rows="3"><?php echo esc_textarea( $desc ); ?></textarea>
                <p class="description"><?php esc_html_e( 'Short paragraph under the page title.', 'tutti-frutti-cafe' ); ?></p>
            </td>
        </tr>
        <?php for ( $i = 1; $i <= 2; $i++ ) : ?>
            <?php
            $text    = get_post_meta( $post->ID, '_tf_page_hero_btn' . $i . '_text', true );
            $url     = get_post_meta( $post->ID, '_tf_page_hero_btn' . $i . '_url', true );
            $new_tab = (bool) get_post_meta( $post->ID, '_tf_page_hero_btn' . $i . '_new_tab', true );
            ?>
            <tr>
                <th>
                    <label for="tf_page_hero_btn<?php echo esc_attr( (string) $i ); ?>_text">
                        <?php
                        /* translators: %d: button number. */
                        printf( esc_html__( 'Button %d', 'tutti-frutti-cafe' ), (int) $i );
                        ?>
                    </label>
                </th>
                <td>
                    <p>
                        <input type="text" id="tf_page_hero_btn<?php echo esc_attr( (string) $i ); ?>_text"
                            name="tf_page_hero_btn<?php echo esc_attr( (string) $i ); ?>_text"
                            value="<?php echo esc_attr( $text ); ?>" class="regular-text"
                            placeholder="<?php esc_attr_e( 'Label', 'tutti-frutti-cafe' ); ?>">
                    </p>
                    <p>
                        <input type="url" id="tf_page_hero_btn<?php echo esc_attr( (string) $i ); ?>_url"
                            name="tf_page_hero_btn<?php echo esc_attr( (string) $i ); ?>_url"
                            value="<?php echo esc_url( $url ); ?>" class="large-text"
                            placeholder="https://">
                    </p>
                    <p>
                        <label>
                            <input type="checkbox" value="1"
                                name="tf_page_hero_btn<?php echo esc_attr( (string) $i ); ?>_new_tab"
                                <?php checked( $new_tab ); ?>>
                            <?php esc_html_e( 'Open in a new tab', 'tutti-frutti-cafe' ); ?>
                        </label>
                    </p>
                </td>
            </tr>
        <?php endfor; ?>
    </table>
    <?php
}

/**
 * Save the meta box.
 *
 * @param int $post_id Page ID.
 */
function tutti_frutti_save_page_hero_meta( $post_id ) {
    if ( ! isset( $_POST['tf_page_hero_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tf_page_hero_meta_nonce'] ) ), 'tf_page_hero_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = array(
        'tf_page_hero_desc'      => 'sanitize_textarea_field',
        'tf_page_hero_btn1_text' => 'sanitize_text_field',
        'tf_page_hero_btn1_url'  => 'esc_url_raw',
        'tf_page_hero_btn2_text' => 'sanitize_text_field',
        'tf_page_hero_btn2_url'  => 'esc_url_raw',
    );

    foreach ( $fields as $field => $sanitize ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, call_user_func( $sanitize, wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    /*
     * Checkboxes are absent from $_POST when unchecked, so they cannot go
     * through the loop above — that would make them impossible to switch off.
     */
    for ( $i = 1; $i <= 2; $i++ ) {
        $key = 'tf_page_hero_btn' . $i . '_new_tab';
        update_post_meta( $post_id, '_' . $key, isset( $_POST[ $key ] ) ? 1 : 0 );
    }
}
add_action( 'save_post_page', 'tutti_frutti_save_page_hero_meta' );

/**
 * Hero content for a page.
 *
 * @param int $post_id Page ID. Defaults to the current page.
 * @return array{desc:string, buttons:array<int, array{text:string, url:string, new_tab:bool, class:string}>}
 */
function tutti_frutti_get_page_hero( $post_id = 0 ) {
    $post_id = $post_id ? absint( $post_id ) : get_the_ID();

    $hero = array(
        'desc'    => (string) get_post_meta( $post_id, '_tf_page_hero_desc', true ),
        'buttons' => array(),
    );

    for ( $i = 1; $i <= 2; $i++ ) {
        $text = (string) get_post_meta( $post_id, '_tf_page_hero_btn' . $i . '_text', true );
        $url  = (string) get_post_meta( $post_id, '_tf_page_hero_btn' . $i . '_url', true );

        if ( ! $text || ! $url ) {
            continue;
        }

        $hero['buttons'][] = array(
            'text'    => $text,
            'url'     => $url,
            'new_tab' => (bool) get_post_meta( $post_id, '_tf_page_hero_btn' . $i . '_new_tab', true ),
            // btn-tertiary is white-on-transparent and disappears on the cream hero.
            'class'   => 1 === $i ? 'btn btn-primary' : 'btn btn-tertiary',
        );
    }

    return $hero;
}
