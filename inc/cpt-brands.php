<?php
/**
 * Brands custom post type.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register tf_brand CPT (public single pages).
 */
function tutti_frutti_register_brand_cpt() {
    register_post_type(
        'tf_brand',
        array(
            'labels'              => array(
                'name'          => __( 'Brands', 'tutti-frutti-cafe' ),
                'singular_name' => __( 'Brand', 'tutti-frutti-cafe' ),
                'add_new_item'  => __( 'Add New Brand', 'tutti-frutti-cafe' ),
                'edit_item'     => __( 'Edit Brand', 'tutti-frutti-cafe' ),
                'view_item'     => __( 'View Brand Page', 'tutti-frutti-cafe' ),
            ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-store',
            'menu_position'       => 21,
            'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
            'show_in_rest'        => true,
            'has_archive'         => false,
            'rewrite'             => array(
                'slug'       => 'brand',
                'with_front' => false,
            ),
        )
    );
}
add_action( 'init', 'tutti_frutti_register_brand_cpt' );

/**
 * Flush rewrite rules when theme activates.
 */
function tutti_frutti_flush_brand_rewrites() {
    tutti_frutti_register_brand_cpt();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'tutti_frutti_flush_brand_rewrites' );

/**
 * Brand meta box.
 */
function tutti_frutti_brand_meta_box() {
    add_meta_box(
        'tf_brand_details',
        __( 'Brand Settings', 'tutti-frutti-cafe' ),
        'tutti_frutti_brand_meta_box_render',
        'tf_brand',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'tutti_frutti_brand_meta_box' );

/**
 * @param WP_Post $post Post.
 */
function tutti_frutti_brand_meta_box_render( $post ) {
    wp_nonce_field( 'tf_brand_meta', 'tf_brand_meta_nonce' );
    $btn           = get_post_meta( $post->ID, '_tf_button_style', true );
    $key           = get_post_meta( $post->ID, '_tf_demo_key', true );
    $detail_image   = get_post_meta( $post->ID, '_tf_detail_image', true );
    $brand_logo     = get_post_meta( $post->ID, '_tf_brand_logo', true );
    $hero_heading   = get_post_meta( $post->ID, '_tf_hero_heading', true );
    $hero_desc      = get_post_meta( $post->ID, '_tf_hero_desc', true );
    $hero_btn_text  = get_post_meta( $post->ID, '_tf_hero_btn_text', true );
    $products_title = get_post_meta( $post->ID, '_tf_products_title', true );
    $order_url      = get_post_meta( $post->ID, '_tf_order_url', true );
    $card_title     = get_post_meta( $post->ID, '_tf_card_title', true );
    $card_desc      = get_post_meta( $post->ID, '_tf_card_desc', true );
    $card_button    = get_post_meta( $post->ID, '_tf_card_button_text', true );
    $card_lines     = get_post_meta( $post->ID, '_tf_card_lines', true );
    ?>
    <p><em><?php esc_html_e( 'Featured Image = detail hero (right). Logo URL = left + brand cards. Leave fields empty to hide on front.', 'tutti-frutti-cafe' ); ?></em></p>
    <table class="form-table">
        <tr>
            <th><label for="tf_brand_logo"><?php esc_html_e( 'Brand logo URL (left side)', 'tutti-frutti-cafe' ); ?></label></th>
            <td><input type="url" id="tf_brand_logo" name="tf_brand_logo" value="<?php echo esc_url( $brand_logo ); ?>" class="large-text"></td>
        </tr>
        <tr>
            <th><label for="tf_hero_heading"><?php esc_html_e( 'Hero title (left)', 'tutti-frutti-cafe' ); ?></label></th>
            <td><input type="text" id="tf_hero_heading" name="tf_hero_heading" value="<?php echo esc_attr( $hero_heading ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="tf_hero_desc"><?php esc_html_e( 'Hero description (left)', 'tutti-frutti-cafe' ); ?></label></th>
            <td><textarea id="tf_hero_desc" name="tf_hero_desc" class="large-text" rows="3"><?php echo esc_textarea( $hero_desc ); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="tf_hero_btn_text"><?php esc_html_e( 'Hero button text', 'tutti-frutti-cafe' ); ?></label></th>
            <td><input type="text" id="tf_hero_btn_text" name="tf_hero_btn_text" value="<?php echo esc_attr( $hero_btn_text ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="tf_detail_image"><?php esc_html_e( 'Detail hero image URL (right)', 'tutti-frutti-cafe' ); ?></label></th>
            <td>
                <input type="url" id="tf_detail_image" name="tf_detail_image" value="<?php echo esc_url( $detail_image ); ?>" class="large-text">
                <p class="description"><?php esc_html_e( 'Or use Featured Image. Empty = no right hero image.', 'tutti-frutti-cafe' ); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="tf_card_lines"><?php esc_html_e( 'Homepage card text lines', 'tutti-frutti-cafe' ); ?></label></th>
            <td>
                <textarea id="tf_card_lines" name="tf_card_lines" class="large-text" rows="6" placeholder="<?php esc_attr_e( "Frozen Yogurt\nAcai\nSmoothies", 'tutti-frutti-cafe' ); ?>"><?php echo esc_textarea( $card_lines ); ?></textarea>
                <p class="description"><?php esc_html_e( 'One item per line. Logo + text link to this brand detail page. No button on homepage unless enabled in Customize.', 'tutti-frutti-cafe' ); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="tf_card_title"><?php esc_html_e( 'Card title (optional fallback)', 'tutti-frutti-cafe' ); ?></label></th>
            <td><input type="text" id="tf_card_title" name="tf_card_title" value="<?php echo esc_attr( $card_title ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="tf_card_desc"><?php esc_html_e( 'Card description (legacy fallback)', 'tutti-frutti-cafe' ); ?></label></th>
            <td><textarea id="tf_card_desc" name="tf_card_desc" class="large-text" rows="2"><?php echo esc_textarea( $card_desc ); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="tf_card_button_text"><?php esc_html_e( 'Card button text (only if enabled in Customize)', 'tutti-frutti-cafe' ); ?></label></th>
            <td>
                <input type="text" id="tf_card_button_text" name="tf_card_button_text" value="<?php echo esc_attr( $card_button ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Explore Menu', 'tutti-frutti-cafe' ); ?>">
                <p class="description"><?php esc_html_e( 'Hidden by default. Enable: Appearance → Customize → Homepage Settings → Show Explore button on brand cards.', 'tutti-frutti-cafe' ); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="tf_products_title"><?php esc_html_e( 'Products section title', 'tutti-frutti-cafe' ); ?></label></th>
            <td><input type="text" id="tf_products_title" name="tf_products_title" value="<?php echo esc_attr( $products_title ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Featured Products', 'tutti-frutti-cafe' ); ?>"></td>
        </tr>
        <tr>
            <th><label for="tf_order_url"><?php esc_html_e( 'Hero button URL', 'tutti-frutti-cafe' ); ?></label></th>
            <td><input type="url" id="tf_order_url" name="tf_order_url" value="<?php echo esc_url( $order_url ); ?>" class="large-text"></td>
        </tr>
        <tr>
            <th><label for="tf_button_style"><?php esc_html_e( 'Card button color', 'tutti-frutti-cafe' ); ?></label></th>
            <td>
                <select id="tf_button_style" name="tf_button_style">
                    <?php
                    $styles = array(
                        'btn-brand--purple' => __( 'Purple', 'tutti-frutti-cafe' ),
                        'btn-brand--orange' => __( 'Orange', 'tutti-frutti-cafe' ),
                        'btn-brand--yellow' => __( 'Yellow', 'tutti-frutti-cafe' ),
                        'btn-brand--green'  => __( 'Green', 'tutti-frutti-cafe' ),
                    );
                    foreach ( $styles as $value => $label ) {
                        printf( '<option value="%s" %s>%s</option>', esc_attr( $value ), selected( $btn, $value, false ), esc_html( $label ) );
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="tf_demo_key"><?php esc_html_e( 'Fallback image key', 'tutti-frutti-cafe' ); ?></label></th>
            <td><input type="text" id="tf_demo_key" name="tf_demo_key" value="<?php echo esc_attr( $key ); ?>" class="regular-text" placeholder="brand_tutti"></td>
        </tr>
    </table>
    <?php if ( 'publish' === $post->post_status ) : ?>
        <p><strong><?php esc_html_e( 'Brand page URL:', 'tutti-frutti-cafe' ); ?></strong>
            <a href="<?php echo esc_url( get_permalink( $post ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( get_permalink( $post ) ); ?></a>
        </p>
    <?php endif; ?>
    <?php
}

/**
 * Save brand meta.
 *
 * @param int $post_id Post ID.
 */
function tutti_frutti_save_brand_meta( $post_id ) {
    if ( ! isset( $_POST['tf_brand_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tf_brand_meta_nonce'] ) ), 'tf_brand_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = array(
        'tf_brand_logo'        => 'esc_url_raw',
        'tf_hero_heading'      => 'sanitize_text_field',
        'tf_hero_desc'         => 'sanitize_textarea_field',
        'tf_hero_btn_text'     => 'sanitize_text_field',
        'tf_detail_image'      => 'esc_url_raw',
        'tf_products_title'    => 'sanitize_text_field',
        'tf_order_url'         => 'esc_url_raw',
        'tf_card_title'        => 'sanitize_text_field',
        'tf_card_desc'         => 'sanitize_textarea_field',
        'tf_card_lines'        => 'sanitize_textarea_field',
        'tf_card_button_text'  => 'sanitize_text_field',
        'tf_button_style'      => 'sanitize_text_field',
        'tf_demo_key'          => 'sanitize_text_field',
    );

    foreach ( $fields as $field => $sanitize ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, call_user_func( $sanitize, wp_unslash( $_POST[ $field ] ) ) );
        }
    }

    if ( 'publish' === get_post_status( $post_id ) ) {
        update_post_meta( $post_id, '_tf_link_url', get_permalink( $post_id ) );
    }
}
add_action( 'save_post_tf_brand', 'tutti_frutti_save_brand_meta' );

/**
 * Default homepage card lines by brand slug.
 *
 * @return array<string, string>
 */
function tutti_frutti_default_brand_card_lines() {
    return array(
        'tutti-frutti' => "Frozen Yogurt\nAcai\nSmoothies\nRefreshers\nMatcha",
        'pio-coffee'   => "Iced & Blended Coffee\nHot Coffee\nTeas",
        'my-cookies'   => "Gourmet Cookies\nDesserts\nCakes",
        'o-my'         => "Gourmet Cookies\nDesserts\nCakes",
        'tf-bites'     => "Sandwiches\nPasta\nPizza\nBites",
    );
}

/**
 * Seed homepage card lines on existing brands (one-time).
 */
function tutti_frutti_seed_brand_card_lines() {
    if ( get_option( 'tutti_frutti_card_lines_seeded' ) ) {
        return;
    }

    $defaults = tutti_frutti_default_brand_card_lines();
    $brands   = get_posts(
        array(
            'post_type'      => 'tf_brand',
            'posts_per_page' => -1,
            'post_status'    => 'any',
        )
    );

    foreach ( $brands as $brand ) {
        if ( get_post_meta( $brand->ID, '_tf_card_lines', true ) ) {
            continue;
        }
        if ( isset( $defaults[ $brand->post_name ] ) ) {
            update_post_meta( $brand->ID, '_tf_card_lines', $defaults[ $brand->post_name ] );
        }
    }

    update_option( 'tutti_frutti_card_lines_seeded', 1 );
}
add_action( 'init', 'tutti_frutti_seed_brand_card_lines', 110 );

/**
 * Brand slug map for demo import.
 *
 * @return array<string, string> title => slug
 */
function tutti_frutti_brand_slug_map() {
    return array(
        'Tutti Frutti' => 'tutti-frutti',
        'Pio Coffee'   => 'pio-coffee',
        'My Cookies!'  => 'my-cookies',
        'TF Bites'     => 'tf-bites',
    );
}

/**
 * Import demo brands from PHP config once.
 */
function tutti_frutti_maybe_import_demo_brands() {
    if ( get_option( 'tutti_frutti_brands_imported_v2' ) ) {
        return;
    }

    $existing = get_posts(
        array(
            'post_type'      => 'tf_brand',
            'posts_per_page' => 1,
            'post_status'    => 'any',
            'fields'         => 'ids',
        )
    );

    if ( ! empty( $existing ) ) {
        update_option( 'tutti_frutti_brands_imported_v2', 1 );
        tutti_frutti_sync_existing_brand_links();
        return;
    }

    $slug_map = tutti_frutti_brand_slug_map();

    foreach ( tutti_frutti_get_brands_fallback() as $index => $brand ) {
        $slug = isset( $slug_map[ $brand['name'] ] ) ? $slug_map[ $brand['name'] ] : sanitize_title( $brand['name'] );
        $id   = wp_insert_post(
            array(
                'post_type'    => 'tf_brand',
                'post_title'   => $brand['name'],
                'post_name'    => $slug,
                'post_excerpt' => $brand['desc'],
                'post_content' => '',
                'post_status'  => 'publish',
                'menu_order'   => $index,
            )
        );

        if ( $id && ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_tf_button_style', $brand['btn'] );
            update_post_meta( $id, '_tf_demo_key', $brand['key'] );
            update_post_meta( $id, '_tf_products_preview', 4 );
            update_post_meta( $id, '_tf_link_url', get_permalink( $id ) );
        }
    }

    update_option( 'tutti_frutti_brands_imported_v2', 1 );
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'tutti_frutti_maybe_import_demo_brands' );
add_action( 'init', 'tutti_frutti_maybe_import_demo_brands', 99 );

/**
 * One-time upgrade: public brand URLs + permalink sync.
 */
function tutti_frutti_upgrade_brands_v14() {
    if ( get_option( 'tutti_frutti_v14_upgrade' ) ) {
        return;
    }
    tutti_frutti_register_brand_cpt();
    flush_rewrite_rules();
    tutti_frutti_sync_existing_brand_links();
    update_option( 'tutti_frutti_v14_upgrade', 1 );
}
add_action( 'init', 'tutti_frutti_upgrade_brands_v14', 25 );

/**
 * Sync permalink into link meta for existing brands.
 */
function tutti_frutti_sync_existing_brand_links() {
    $brands = get_posts(
        array(
            'post_type'      => 'tf_brand',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        )
    );
    foreach ( $brands as $brand ) {
        update_post_meta( $brand->ID, '_tf_link_url', get_permalink( $brand ) );
    }
}