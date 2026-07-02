<?php
/**
 * Brand products (menu items) custom post type.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register tf_menu_item CPT.
 */
function tutti_frutti_register_menu_item_cpt() {
    register_post_type(
        'tf_menu_item',
        array(
            'labels'              => array(
                'name'          => __( 'Brand Products', 'tutti-frutti-cafe' ),
                'singular_name' => __( 'Product', 'tutti-frutti-cafe' ),
                'add_new_item'  => __( 'Add New Product', 'tutti-frutti-cafe' ),
            ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-cart',
            'menu_position'       => 22,
            'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
            'show_in_rest'        => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
        )
    );
}
add_action( 'init', 'tutti_frutti_register_menu_item_cpt' );

/**
 * Register product meta for reliable save (incl. block editor).
 */
function tutti_frutti_register_menu_item_meta() {
    $string_meta = array(
        '_tf_order_url'     => 'esc_url_raw',
        '_tf_product_desc'  => 'sanitize_text_field',
        '_tf_demo_key'      => 'sanitize_text_field',
        '_tf_brand_slug'    => 'sanitize_text_field',
    );
    foreach ( $string_meta as $key => $sanitize ) {
        register_post_meta(
            'tf_menu_item',
            $key,
            array(
                'show_in_rest'      => true,
                'single'            => true,
                'type'              => 'string',
                'sanitize_callback' => $sanitize,
                'auth_callback'     => function () {
                    return current_user_can( 'edit_posts' );
                },
            )
        );
    }
    register_post_meta(
        'tf_menu_item',
        '_tf_brand_id',
        array(
            'show_in_rest'      => true,
            'single'            => true,
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
            'auth_callback'     => function () {
                return current_user_can( 'edit_posts' );
            },
        )
    );
    register_post_meta(
        'tf_menu_item',
        '_tf_category_id',
        array(
            'show_in_rest'      => true,
            'single'            => true,
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
            'auth_callback'     => function () {
                return current_user_can( 'edit_posts' );
            },
        )
    );
}
add_action( 'init', 'tutti_frutti_register_menu_item_meta', 20 );

/**
 * Get product Order Now URL from post meta.
 *
 * @param int $post_id Product post ID.
 * @return string
 */
function tutti_frutti_get_product_order_url( $post_id ) {
    $post_id = (int) $post_id;
    if ( ! $post_id ) {
        return '';
    }

    $keys = array( '_tf_order_url', '_tf_product_order_url', 'tf_order_url' );
    foreach ( $keys as $key ) {
        $url = trim( (string) get_post_meta( $post_id, $key, true ) );
        if ( $url ) {
            return $url;
        }
    }

    return '';
}

/**
 * Menu item meta box.
 */
function tutti_frutti_menu_item_meta_box() {
    add_meta_box(
        'tf_menu_item_details',
        __( 'Product Details', 'tutti-frutti-cafe' ),
        'tutti_frutti_menu_item_meta_box_render',
        'tf_menu_item',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'tutti_frutti_menu_item_meta_box' );

/**
 * @return array<int, string>
 */
function tutti_frutti_get_brand_choices() {
    $choices = array( '' => __( '— Select Brand —', 'tutti-frutti-cafe' ) );
    $brands  = get_posts(
        array(
            'post_type'      => 'tf_brand',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        )
    );
    foreach ( $brands as $brand ) {
        $choices[ $brand->ID ] = $brand->post_title;
    }
    return $choices;
}

/**
 * @param WP_Post $post Post.
 */
function tutti_frutti_menu_item_meta_box_render( $post ) {
    wp_nonce_field( 'tf_menu_item_meta', 'tf_menu_item_meta_nonce' );
    $brand_id    = (int) get_post_meta( $post->ID, '_tf_brand_id', true );
    $cat_id      = (int) get_post_meta( $post->ID, '_tf_category_id', true );
    $order_url   = tutti_frutti_get_product_order_url( $post->ID );
    $prod_desc   = get_post_meta( $post->ID, '_tf_product_desc', true );
    $key         = get_post_meta( $post->ID, '_tf_demo_key', true );
    $choices     = tutti_frutti_get_brand_choices();
    $categories  = $brand_id ? tutti_frutti_get_brand_categories( $brand_id ) : array();
    ?>
    <p>
        <label for="tf_brand_id"><strong><?php esc_html_e( 'Brand', 'tutti-frutti-cafe' ); ?></strong></label>
        <select id="tf_brand_id" name="tf_brand_id" class="widefat" required>
            <?php foreach ( $choices as $id => $label ) : ?>
                <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $brand_id, (int) $id ); ?>><?php echo esc_html( $label ); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="tf_category_id"><strong><?php esc_html_e( 'Category', 'tutti-frutti-cafe' ); ?></strong></label>
        <select id="tf_category_id" name="tf_category_id" class="widefat">
            <option value=""><?php esc_html_e( '— Uncategorized —', 'tutti-frutti-cafe' ); ?></option>
            <?php foreach ( $categories as $cat ) : ?>
                <option value="<?php echo esc_attr( $cat->ID ); ?>" <?php selected( $cat_id, $cat->ID ); ?>><?php echo esc_html( $cat->post_title ); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="tf_product_desc"><strong><?php esc_html_e( 'Description (ingredients line)', 'tutti-frutti-cafe' ); ?></strong></label>
        <input type="text" id="tf_product_desc" name="tf_product_desc" value="<?php echo esc_attr( $prod_desc ); ?>" class="widefat" placeholder="Starfruit, Passionfruit, Mango">
    </p>
    <p>
        <label for="tf_product_order_url"><strong><?php esc_html_e( 'Order Now / Buy URL', 'tutti-frutti-cafe' ); ?></strong></label>
        <input type="url" id="tf_product_order_url" name="tf_product_order_url" value="<?php echo esc_url( $order_url ); ?>" class="widefat" placeholder="https://">
        <span class="description"><?php esc_html_e( 'Shows an Order Now button below this product on the brand page.', 'tutti-frutti-cafe' ); ?></span>
    </p>
    <p>
        <label for="tf_menu_demo_key"><strong><?php esc_html_e( 'Fallback image key', 'tutti-frutti-cafe' ); ?></strong></label>
        <input type="text" id="tf_menu_demo_key" name="tf_menu_demo_key" value="<?php echo esc_attr( $key ); ?>" class="widefat" placeholder="drink_karak">
    </p>
    <?php
}

/**
 * @param int $post_id Post ID.
 */
function tutti_frutti_save_menu_item_meta( $post_id ) {
    if ( ! isset( $_POST['tf_menu_item_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tf_menu_item_meta_nonce'] ) ), 'tf_menu_item_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['tf_brand_id'] ) ) {
        $brand_id = absint( $_POST['tf_brand_id'] );
        update_post_meta( $post_id, '_tf_brand_id', $brand_id );
        $brand = get_post( $brand_id );
        if ( $brand ) {
            update_post_meta( $post_id, '_tf_brand_slug', $brand->post_name );
        }
    }
    if ( isset( $_POST['tf_category_id'] ) ) {
        update_post_meta( $post_id, '_tf_category_id', absint( $_POST['tf_category_id'] ) );
    }
    if ( isset( $_POST['tf_product_desc'] ) ) {
        update_post_meta( $post_id, '_tf_product_desc', sanitize_text_field( wp_unslash( $_POST['tf_product_desc'] ) ) );
    }
    if ( array_key_exists( 'tf_product_order_url', $_POST ) ) {
        $raw = trim( wp_unslash( $_POST['tf_product_order_url'] ) );
        $url = $raw ? esc_url_raw( $raw ) : '';
        if ( $raw && ! $url ) {
            $url = sanitize_text_field( $raw );
        }
        update_post_meta( $post_id, '_tf_order_url', $url );
    }
    if ( isset( $_POST['tf_menu_demo_key'] ) ) {
        update_post_meta( $post_id, '_tf_demo_key', sanitize_text_field( wp_unslash( $_POST['tf_menu_demo_key'] ) ) );
    }
}

/**
 * Format product array from post.
 *
 * @param WP_Post $post Post.
 * @return array
 */
function tutti_frutti_format_menu_item( $post ) {
    $thumb = get_the_post_thumbnail_url( $post, 'medium' );
    $key   = get_post_meta( $post->ID, '_tf_demo_key', true );
    return array(
        'id'          => $post->ID,
        'name'        => $post->post_title,
        'description' => get_post_meta( $post->ID, '_tf_product_desc', true ),
        'image'       => $thumb ? $thumb : '',
        'key'         => $key ? $key : 'treat_default',
        'order_url'   => tutti_frutti_get_product_order_url( $post->ID ),
        'category_id' => (int) get_post_meta( $post->ID, '_tf_category_id', true ),
    );
}
add_action( 'save_post_tf_menu_item', 'tutti_frutti_save_menu_item_meta' );

/**
 * Get products for a brand (ID or legacy slug).
 *
 * @param int|string $brand Brand post ID or slug.
 * @return array[]
 */
function tutti_frutti_get_menu_items( $brand = 0 ) {
    $meta_query = array();

    if ( is_numeric( $brand ) && (int) $brand > 0 ) {
        $meta_query[] = array(
            'key'   => '_tf_brand_id',
            'value' => (int) $brand,
        );
    } elseif ( is_string( $brand ) && $brand ) {
        $meta_query[] = array(
            'key'   => '_tf_brand_slug',
            'value' => $brand,
        );
    } else {
        return array();
    }

    $posts = get_posts(
        array(
            'post_type'      => 'tf_menu_item',
            'posts_per_page' => 50,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
            'meta_query'     => $meta_query,
        )
    );

    $items = array();
    foreach ( $posts as $post ) {
        $items[] = tutti_frutti_format_menu_item( $post );
    }

    if ( ! empty( $items ) ) {
        return $items;
    }

    if ( is_string( $brand ) ) {
        return tutti_frutti_get_menu_items_fallback( $brand );
    }

    return array();
}

/**
 * Demo drinks fallback for Pio.
 *
 * @param string $brand_slug Brand slug.
 * @return array[]
 */
function tutti_frutti_get_menu_items_fallback( $brand_slug ) {
    if ( 'pio-coffee' !== $brand_slug ) {
        return array();
    }

    $order_url = tutti_frutti_get_chownow_url();
    $items     = array(
        array( 'key' => 'drink_karak', 'name' => 'Karak Chai' ),
        array( 'key' => 'drink_pistachio', 'name' => 'Pistachio Latte' ),
        array( 'key' => 'drink_caramel', 'name' => 'Caramel Latte' ),
        array( 'key' => 'drink_spanish', 'name' => 'Spanish Latte' ),
        array( 'key' => 'drink_coldbrew', 'name' => 'Cold Brew' ),
    );
    $out = array();
    foreach ( $items as $item ) {
        $out[] = array(
            'id'          => 0,
            'key'         => $item['key'],
            'name'        => $item['name'],
            'image'       => '',
            'order_url'   => $order_url,
            'category_id' => 0,
        );
    }
    return $out;
}

/**
 * Find brand ID by slug.
 *
 * @param string $slug Post name.
 * @return int
 */
function tutti_frutti_get_brand_id_by_slug( $slug ) {
    $post = get_page_by_path( $slug, OBJECT, 'tf_brand' );
    return $post ? (int) $post->ID : 0;
}

/**
 * Import demo products once.
 */
function tutti_frutti_maybe_import_demo_menu_items() {
    if ( get_option( 'tutti_frutti_menu_items_imported_v2' ) ) {
        return;
    }

    $pio_id = tutti_frutti_get_brand_id_by_slug( 'pio-coffee' );
    if ( ! $pio_id ) {
        return;
    }

    $existing = get_posts(
        array(
            'post_type'      => 'tf_menu_item',
            'posts_per_page' => 1,
            'post_status'    => 'any',
            'fields'         => 'ids',
        )
    );

    if ( empty( $existing ) ) {
        $order = 0;
        foreach ( tutti_frutti_get_menu_items_fallback( 'pio-coffee' ) as $item ) {
            $id = wp_insert_post(
                array(
                    'post_type'   => 'tf_menu_item',
                    'post_title'  => $item['name'],
                    'post_status' => 'publish',
                    'menu_order'  => $order++,
                )
            );
            if ( $id && ! is_wp_error( $id ) ) {
                update_post_meta( $id, '_tf_brand_id', $pio_id );
                update_post_meta( $id, '_tf_brand_slug', 'pio-coffee' );
                update_post_meta( $id, '_tf_demo_key', $item['key'] );
            }
        }
    } else {
        foreach ( get_posts( array( 'post_type' => 'tf_menu_item', 'posts_per_page' => -1, 'post_status' => 'any' ) ) as $item ) {
            if ( ! get_post_meta( $item->ID, '_tf_brand_id', true ) ) {
                $slug = get_post_meta( $item->ID, '_tf_brand_slug', true );
                if ( 'pio-coffee' === $slug ) {
                    update_post_meta( $item->ID, '_tf_brand_id', $pio_id );
                }
            }
        }
    }

    update_option( 'tutti_frutti_menu_items_imported_v2', 1 );
}
add_action( 'after_switch_theme', 'tutti_frutti_maybe_import_demo_menu_items' );
add_action( 'init', 'tutti_frutti_maybe_import_demo_menu_items', 100 );