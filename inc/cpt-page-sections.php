<?php
/**
 * Page sections — editable blocks for inner pages.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Pages that support CMS sections.
 *
 * @return array<string, string>
 */
function tutti_frutti_page_section_targets() {
    return array(
        'about'      => __( 'About Us', 'tutti-frutti-cafe' ),
        'franchise'  => __( 'Franchise', 'tutti-frutti-cafe' ),
        'order'      => __( 'Order Online', 'tutti-frutti-cafe' ),
        'contact'    => __( 'Contact Us', 'tutti-frutti-cafe' ),
        'careers'    => __( 'Careers', 'tutti-frutti-cafe' ),
        'rewards'    => __( 'Rewards', 'tutti-frutti-cafe' ),
        'directions' => __( 'Directions', 'tutti-frutti-cafe' ),
    );
}

/**
 * Register tf_page_section CPT.
 */
function tutti_frutti_register_page_section_cpt() {
    register_post_type(
        'tf_page_section',
        array(
            'labels'              => array(
                'name'          => __( 'Page Sections', 'tutti-frutti-cafe' ),
                'singular_name' => __( 'Page Section', 'tutti-frutti-cafe' ),
                'add_new_item'  => __( 'Add New Section', 'tutti-frutti-cafe' ),
            ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-layout',
            'menu_position'       => 23,
            'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'show_in_rest'        => true,
            'has_archive'         => false,
        )
    );
}
add_action( 'init', 'tutti_frutti_register_page_section_cpt' );

/**
 * Admin list columns for page sections.
 *
 * @param array $columns Columns.
 * @return array
 */
function tutti_frutti_page_section_columns( $columns ) {
    $new = array();
    $new['cb']    = $columns['cb'];
    $new['title'] = __( 'Section title', 'tutti-frutti-cafe' );
    $new['tf_page'] = __( 'Page', 'tutti-frutti-cafe' );
    $new['tf_layout'] = __( 'Layout', 'tutti-frutti-cafe' );
    $new['menu_order'] = __( 'Order', 'tutti-frutti-cafe' );
    $new['date']  = $columns['date'];
    return $new;
}
add_filter( 'manage_tf_page_section_posts_columns', 'tutti_frutti_page_section_columns' );

/**
 * @param string $column Column key.
 * @param int    $post_id Post ID.
 */
function tutti_frutti_page_section_column_content( $column, $post_id ) {
    $targets = tutti_frutti_page_section_targets();

    if ( 'tf_page' === $column ) {
        $page = get_post_meta( $post_id, '_tf_page', true );
        echo esc_html( isset( $targets[ $page ] ) ? $targets[ $page ] : $page );
    }

    if ( 'tf_layout' === $column ) {
        $layout = get_post_meta( $post_id, '_tf_layout', true ) ?: 'split';
        $labels = array(
            'split'        => __( 'Image + text', 'tutti-frutti-cafe' ),
            'icon_card'    => __( 'Icon card', 'tutti-frutti-cafe' ),
            'careers_hero' => __( 'Careers hero', 'tutti-frutti-cafe' ),
            'text_only'    => __( 'Text only', 'tutti-frutti-cafe' ),
        );
        echo esc_html( isset( $labels[ $layout ] ) ? $labels[ $layout ] : $layout );
    }
}
add_action( 'manage_tf_page_section_posts_custom_column', 'tutti_frutti_page_section_column_content', 10, 2 );

/**
 * Sortable Page column.
 *
 * @param array $columns Sortable columns.
 * @return array
 */
function tutti_frutti_page_section_sortable_columns( $columns ) {
    $columns['tf_page'] = 'tf_page';
    return $columns;
}
add_filter( 'manage_edit-tf_page_section_sortable_columns', 'tutti_frutti_page_section_sortable_columns' );

/**
 * Filter dropdown: sections by page.
 */
function tutti_frutti_page_section_admin_filter() {
    global $typenow;
    if ( 'tf_page_section' !== $typenow ) {
        return;
    }
    $current = isset( $_GET['tf_page_filter'] ) ? sanitize_key( wp_unslash( $_GET['tf_page_filter'] ) ) : '';
    echo '<select name="tf_page_filter">';
    echo '<option value="">' . esc_html__( 'All pages', 'tutti-frutti-cafe' ) . '</option>';
    foreach ( tutti_frutti_page_section_targets() as $key => $label ) {
        printf(
            '<option value="%s" %s>%s</option>',
            esc_attr( $key ),
            selected( $current, $key, false ),
            esc_html( $label )
        );
    }
    echo '</select>';
}
add_action( 'restrict_manage_posts', 'tutti_frutti_page_section_admin_filter' );

/**
 * Apply page filter to section query.
 *
 * @param WP_Query $query Query.
 */
function tutti_frutti_page_section_filter_query( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }
    if ( 'tf_page_section' !== $query->get( 'post_type' ) ) {
        return;
    }
    if ( empty( $_GET['tf_page_filter'] ) ) {
        return;
    }
    $page = sanitize_key( wp_unslash( $_GET['tf_page_filter'] ) );
    $query->set(
        'meta_query',
        array(
            array(
                'key'   => '_tf_page',
                'value' => $page,
            ),
        )
    );
}
add_action( 'pre_get_posts', 'tutti_frutti_page_section_filter_query' );

/**
 * Meta box.
 */
function tutti_frutti_page_section_meta_box() {
    add_meta_box(
        'tf_page_section_details',
        __( 'Section Settings', 'tutti-frutti-cafe' ),
        'tutti_frutti_page_section_meta_box_render',
        'tf_page_section',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'tutti_frutti_page_section_meta_box' );

/**
 * @param WP_Post $post Post.
 */
function tutti_frutti_page_section_meta_box_render( $post ) {
    wp_nonce_field( 'tf_page_section_meta', 'tf_page_section_meta_nonce' );
    $page   = get_post_meta( $post->ID, '_tf_page', true );
    $layout = get_post_meta( $post->ID, '_tf_layout', true );
    $icon   = get_post_meta( $post->ID, '_tf_icon', true );
    $label  = get_post_meta( $post->ID, '_tf_label', true );
    $side   = get_post_meta( $post->ID, '_tf_image_side', true );
    $btn_t  = get_post_meta( $post->ID, '_tf_button_text', true );
    $btn_u  = get_post_meta( $post->ID, '_tf_button_url', true );
    $img_k  = get_post_meta( $post->ID, '_tf_demo_image_key', true );
    ?>
    <p>
        <label for="tf_page"><strong><?php esc_html_e( 'Show on page', 'tutti-frutti-cafe' ); ?></strong></label>
        <select id="tf_page" name="tf_page" class="widefat">
            <?php foreach ( tutti_frutti_page_section_targets() as $key => $lbl ) : ?>
                <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $page, $key ); ?>><?php echo esc_html( $lbl ); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="tf_layout"><strong><?php esc_html_e( 'Layout', 'tutti-frutti-cafe' ); ?></strong></label>
        <select id="tf_layout" name="tf_layout" class="widefat">
            <option value="split" <?php selected( $layout, 'split' ); ?>><?php esc_html_e( 'Image + text (split)', 'tutti-frutti-cafe' ); ?></option>
            <option value="icon_card" <?php selected( $layout, 'icon_card' ); ?>><?php esc_html_e( 'Icon card (grid item)', 'tutti-frutti-cafe' ); ?></option>
            <option value="careers_hero" <?php selected( $layout, 'careers_hero' ); ?>><?php esc_html_e( 'Careers hero (text + image)', 'tutti-frutti-cafe' ); ?></option>
            <option value="text_only" <?php selected( $layout, 'text_only' ); ?>><?php esc_html_e( 'Text only block', 'tutti-frutti-cafe' ); ?></option>
        </select>
    </p>
    <p>
        <label for="tf_image_side"><strong><?php esc_html_e( 'Image side (split)', 'tutti-frutti-cafe' ); ?></strong></label>
        <select id="tf_image_side" name="tf_image_side" class="widefat">
            <option value="left" <?php selected( $side, 'left' ); ?>><?php esc_html_e( 'Left', 'tutti-frutti-cafe' ); ?></option>
            <option value="right" <?php selected( $side, 'right' ); ?>><?php esc_html_e( 'Right', 'tutti-frutti-cafe' ); ?></option>
        </select>
    </p>
    <p>
        <label for="tf_label"><strong><?php esc_html_e( 'Small label (optional)', 'tutti-frutti-cafe' ); ?></strong></label>
        <input type="text" id="tf_label" name="tf_label" value="<?php echo esc_attr( $label ); ?>" class="widefat">
    </p>
    <p>
        <label for="tf_icon"><strong><?php esc_html_e( 'Icon / symbol (emoji or text)', 'tutti-frutti-cafe' ); ?></strong></label>
        <input type="text" id="tf_icon" name="tf_icon" value="<?php echo esc_attr( $icon ); ?>" class="widefat" placeholder="☕">
    </p>
    <p>
        <label for="tf_button_text"><strong><?php esc_html_e( 'Button text', 'tutti-frutti-cafe' ); ?></strong></label>
        <input type="text" id="tf_button_text" name="tf_button_text" value="<?php echo esc_attr( $btn_t ); ?>" class="widefat">
    </p>
    <p>
        <label for="tf_button_url"><strong><?php esc_html_e( 'Button URL', 'tutti-frutti-cafe' ); ?></strong></label>
        <input type="url" id="tf_button_url" name="tf_button_url" value="<?php echo esc_url( $btn_u ); ?>" class="widefat">
    </p>
    <p>
        <label for="tf_demo_image_key"><strong><?php esc_html_e( 'Fallback image key', 'tutti-frutti-cafe' ); ?></strong></label>
        <input type="text" id="tf_demo_image_key" name="tf_demo_image_key" value="<?php echo esc_attr( $img_k ); ?>" class="widefat">
    </p>
    <p><em><?php esc_html_e( 'Featured Image = section photo. Title = heading. Content = paragraph / list.', 'tutti-frutti-cafe' ); ?></em></p>
    <?php
}

/**
 * @param int $post_id Post ID.
 */
function tutti_frutti_save_page_section_meta( $post_id ) {
    if ( ! isset( $_POST['tf_page_section_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tf_page_section_meta_nonce'] ) ), 'tf_page_section_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $text_fields = array( 'tf_page', 'tf_layout', 'tf_icon', 'tf_label', 'tf_image_side', 'tf_button_text', 'tf_demo_image_key' );
    foreach ( $text_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }
    if ( isset( $_POST['tf_button_url'] ) ) {
        update_post_meta( $post_id, '_tf_button_url', esc_url_raw( wp_unslash( $_POST['tf_button_url'] ) ) );
    }
}
add_action( 'save_post_tf_page_section', 'tutti_frutti_save_page_section_meta' );

/**
 * Get sections for a page key.
 *
 * @param string $page_key Page key.
 * @return WP_Post[]
 */
function tutti_frutti_get_page_sections( $page_key ) {
    return get_posts(
        array(
            'post_type'      => 'tf_page_section',
            'posts_per_page' => 50,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
            'meta_query'     => array(
                array(
                    'key'   => '_tf_page',
                    'value' => $page_key,
                ),
            ),
        )
    );
}

/**
 * Section image URL.
 *
 * @param WP_Post $section Section post.
 * @return string
 */
function tutti_frutti_section_image_url( $section, $page_key = '' ) {
    if ( $page_key ) {
        $mod = get_theme_mod( 'tf_banner_' . $page_key );
        if ( $mod ) {
            return $mod;
        }
    }
    $thumb = get_the_post_thumbnail_url( $section, 'large' );
    if ( $thumb ) {
        return $thumb;
    }
    if ( $page_key ) {
        return tutti_frutti_get_page_banner( $page_key );
    }
    $key = get_post_meta( $section->ID, '_tf_demo_image_key', true );
    return $key ? tutti_frutti_get_image( $key ) : tutti_frutti_get_image( 'placeholder' );
}

/**
 * Hero background style attribute for page sections.
 *
 * @param string $page_key Page key.
 * @return string
 */
function tutti_frutti_page_hero_style_attr( $page_key ) {
    $url = esc_url( tutti_frutti_get_page_banner( $page_key ) );
    return ' style="--tf-page-hero-bg: url(\'' . $url . '\');"';
}

/**
 * Render all sections for a page (or fallback).
 *
 * @param string $page_key Page key.
 * @param bool   $top_padding Add top padding to first section.
 */
function tutti_frutti_render_page_sections( $page_key, $top_padding = true ) {
    $sections = tutti_frutti_get_page_sections( $page_key );

    if ( empty( $sections ) ) {
        tutti_frutti_render_page_sections_fallback( $page_key, $top_padding );
        return;
    }

    $first       = true;
    $icon_buffer = array();

    foreach ( $sections as $section ) {
        $layout = get_post_meta( $section->ID, '_tf_layout', true ) ?: 'split';

        if ( 'icon_card' === $layout ) {
            if ( 'about' === $page_key && get_theme_mod( 'tf_hide_about_values', true ) ) {
                continue;
            }
            if ( 'careers' === $page_key ) {
                continue;
            }
            $icon_buffer[] = $section;
            continue;
        }

        if ( ! empty( $icon_buffer ) ) {
            tutti_frutti_render_icon_card_grid( $icon_buffer, $page_key );
            $icon_buffer = array();
        }

        $extra_class = ( $first && $top_padding ) ? ' page-section--top' : '';
        $first       = false;

        if ( 'careers_hero' === $layout ) {
            tutti_frutti_render_section_careers_hero( $section, $extra_class );
        } elseif ( 'text_only' === $layout ) {
            tutti_frutti_render_section_text_only( $section, $extra_class );
        } else {
            tutti_frutti_render_section_split( $section, $extra_class, $page_key );
        }
    }

    if ( ! empty( $icon_buffer ) ) {
        tutti_frutti_render_icon_card_grid( $icon_buffer, $page_key );
    }
}

/**
 * @param WP_Post[] $sections Icon sections.
 * @param string    $page_key Page key.
 */
function tutti_frutti_render_icon_card_grid( $sections, $page_key = '' ) {
    $is_careers = ( 'careers' === $page_key );
    $section_class = 'page-section page-section--cream' . ( $is_careers ? ' careers-paths' : '' );
    $grid_class    = 'values-grid' . ( $is_careers ? ' careers-paths__inner' : '' );
    $count         = count( $sections );
    ?>
    <section class="<?php echo esc_attr( $section_class ); ?>">
        <div class="container">
            <div class="<?php echo esc_attr( $grid_class ); ?>" style="--icon-cols: <?php echo esc_attr( min( $count, 4 ) ); ?>">
                <?php foreach ( $sections as $section ) : ?>
                    <div class="value-card career-path">
                        <span class="value-card__icon career-path__icon" aria-hidden="true"><?php echo esc_html( get_post_meta( $section->ID, '_tf_icon', true ) ); ?></span>
                        <span class="value-card__label career-path__label"><?php echo esc_html( $section->post_title ); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

/**
 * @param WP_Post $section Section.
 * @param string  $extra_class Extra class.
 */
function tutti_frutti_render_section_split( $section, $extra_class = '', $page_key = '' ) {
    $side  = get_post_meta( $section->ID, '_tf_image_side', true ) ?: 'left';
    $label = get_post_meta( $section->ID, '_tf_label', true );
    $icon  = get_post_meta( $section->ID, '_tf_icon', true );
    $btn_t = get_post_meta( $section->ID, '_tf_button_text', true );
    $btn_u = get_post_meta( $section->ID, '_tf_button_url', true );
    $img   = tutti_frutti_section_image_url( $section, $page_key );
    $radius = ( 'right' === $side ) ? 'about-img-radius-tr' : 'about-img-radius-tl';
    $hero_attr = $page_key ? tutti_frutti_page_hero_style_attr( $page_key ) : '';
    $hero_class = $page_key ? ' page-hero--' . esc_attr( $page_key ) : '';
    ?>
    <section class="page-section page-section--cream<?php echo esc_attr( $extra_class . $hero_class ); ?>"<?php echo $hero_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
        <div class="container">
            <div class="page-split<?php echo 'right' === $side ? ' page-split--reverse' : ''; ?>">
                <?php if ( 'left' === $side ) : ?>
                <div class="page-split__media">
                    <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $section->post_title ); ?>" class="<?php echo esc_attr( $radius ); ?>">
                </div>
                <?php endif; ?>
                <div class="page-split__content">
                    <?php if ( $label ) : ?>
                        <span class="about-story-label"><?php echo esc_html( $label ); ?></span>
                    <?php endif; ?>
                    <?php if ( $icon ) : ?>
                        <span class="section-icon" aria-hidden="true"><?php echo esc_html( $icon ); ?></span>
                    <?php endif; ?>
                    <h2><?php echo esc_html( $section->post_title ); ?></h2>
                    <div class="entry-content"><?php echo wp_kses_post( wpautop( $section->post_content ) ); ?></div>
                    <?php if ( $btn_t && $btn_u ) : ?>
                        <a href="<?php echo esc_url( $btn_u ); ?>" class="btn btn-primary"><?php echo esc_html( $btn_t ); ?></a>
                    <?php endif; ?>
                </div>
                <?php if ( 'right' === $side ) : ?>
                <div class="page-split__media">
                    <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $section->post_title ); ?>" class="<?php echo esc_attr( $radius ); ?>">
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
}

/**
 * @param WP_Post $section Section.
 * @param string  $extra_class Extra class.
 */
function tutti_frutti_render_section_text_only( $section, $extra_class = '' ) {
    ?>
    <section class="page-section page-section--cream<?php echo esc_attr( $extra_class ); ?>">
        <div class="container">
            <h2><?php echo esc_html( $section->post_title ); ?></h2>
            <div class="entry-content"><?php echo wp_kses_post( wpautop( $section->post_content ) ); ?></div>
        </div>
    </section>
    <?php
}

/**
 * @param WP_Post $section Section.
 * @param string  $extra_class Extra class.
 */
function tutti_frutti_render_section_careers_hero( $section, $extra_class = '' ) {
    $img = tutti_frutti_section_image_url( $section, 'careers' );
    ?>
    <section class="careers-hero page-hero--careers<?php echo esc_attr( $extra_class ); ?>"<?php echo tutti_frutti_page_hero_style_attr( 'careers' ); // phpcs:ignore ?>>
        <div class="careers-hero__content">
            <h1><?php echo esc_html( $section->post_title ); ?></h1>
            <div class="careers-hero__intro entry-content"><?php echo wp_kses_post( wpautop( $section->post_content ) ); ?></div>
        </div>
        <div class="careers-hero__media">
            <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $section->post_title ); ?>">
        </div>
    </section>
    <?php
}

/**
 * Fallback hardcoded layouts when no CMS sections exist.
 *
 * @param string $page_key Page key.
 * @param bool   $top_padding Top padding.
 */
function tutti_frutti_render_page_sections_fallback( $page_key, $top_padding = true ) {
    $file = get_template_directory() . '/template-parts/page-fallbacks/' . $page_key . '.php';
    if ( file_exists( $file ) ) {
        $top = $top_padding;
        include $file;
        return;
    }
}

/**
 * Import demo page sections once.
 */
function tutti_frutti_maybe_import_page_sections() {
    if ( get_option( 'tutti_frutti_page_sections_imported' ) ) {
        return;
    }

    $existing = get_posts(
        array(
            'post_type'      => 'tf_page_section',
            'posts_per_page' => 1,
            'post_status'    => 'any',
            'fields'         => 'ids',
        )
    );

    if ( ! empty( $existing ) ) {
        update_option( 'tutti_frutti_page_sections_imported', 1 );
        return;
    }

    $demos = array(
        array(
            'page' => 'about', 'layout' => 'split', 'side' => 'left', 'order' => 0,
            'title' => 'From Frozen Yogurt to a Modern Café Experience',
            'label' => 'Our Story',
            'content' => 'Tutti Frutti Café brings together frozen treats, specialty coffee, fresh bakery, and savory bites under one welcoming roof.',
            'btn' => 'Our Journey', 'url' => '', 'img' => 'about_interior',
        ),
        array(
            'page' => 'about', 'layout' => 'split', 'side' => 'right', 'order' => 1,
            'title' => 'A Place to Gather',
            'content' => 'Great coffee. Irresistible desserts. Savory bites. And most importantly, great company.',
            'img' => 'about_gather',
        ),
        array(
            'page' => 'franchise', 'layout' => 'split', 'side' => 'right', 'order' => 0,
            'title' => 'Franchise Opportunities',
            'content' => 'Bring Tutti Frutti Café to your community. Proven recipes, training, and marketing support.',
            'btn' => 'Get In Touch', 'url' => '', 'img' => 'franchise',
        ),
        array(
            'page' => 'order', 'layout' => 'split', 'side' => 'right', 'order' => 0,
            'title' => 'Order Your Favorites Pickup or Delivery',
            'content' => 'Skip the line and enjoy your favorite treats, drinks and meals.',
            'btn' => 'Order Now', 'url' => '#', 'img' => 'order_phone',
        ),
        array( 'page' => 'order', 'layout' => 'icon_card', 'icon' => '⚡', 'order' => 1, 'title' => 'Fast & Easy' ),
        array( 'page' => 'order', 'layout' => 'icon_card', 'icon' => '🕐', 'order' => 2, 'title' => 'Real-Time Tracking' ),
        array( 'page' => 'order', 'layout' => 'icon_card', 'icon' => '🔒', 'order' => 3, 'title' => 'Secure Payment' ),
        array( 'page' => 'order', 'layout' => 'icon_card', 'icon' => '🎁', 'order' => 4, 'title' => 'Earn Rewards' ),
        array(
            'page' => 'contact', 'layout' => 'split', 'side' => 'right', 'order' => 0,
            'title' => 'Contact Us',
            'content' => 'We would love to hear from you.',
            'img' => 'about_interior',
        ),
        array(
            'page' => 'careers', 'layout' => 'careers_hero', 'order' => 0,
            'title' => 'Grow Your Career. Build Your Future.',
            'content' => 'Join a team that values passion, growth and opportunity.',
            'img' => 'careers_team',
        ),
        array(
            'page' => 'rewards', 'layout' => 'split', 'side' => 'left', 'order' => 0,
            'title' => 'Earn Points. Get Rewarded.',
            'content' => 'Join the VIP Club and earn points every time you order.',
            'btn' => 'Join VIP Club', 'url' => '#', 'img' => 'rewards_phone',
        ),
    );

    foreach ( $demos as $d ) {
        $id = wp_insert_post(
            array(
                'post_type'    => 'tf_page_section',
                'post_title'   => $d['title'],
                'post_content' => isset( $d['content'] ) ? $d['content'] : '',
                'post_status'  => 'publish',
                'menu_order'   => $d['order'],
            )
        );
        if ( ! $id || is_wp_error( $id ) ) {
            continue;
        }
        update_post_meta( $id, '_tf_page', $d['page'] );
        update_post_meta( $id, '_tf_layout', $d['layout'] );
        if ( ! empty( $d['side'] ) ) {
            update_post_meta( $id, '_tf_image_side', $d['side'] );
        }
        if ( ! empty( $d['label'] ) ) {
            update_post_meta( $id, '_tf_label', $d['label'] );
        }
        if ( ! empty( $d['icon'] ) ) {
            update_post_meta( $id, '_tf_icon', $d['icon'] );
        }
        if ( ! empty( $d['img'] ) ) {
            update_post_meta( $id, '_tf_demo_image_key', $d['img'] );
        }
        if ( ! empty( $d['btn'] ) ) {
            update_post_meta( $id, '_tf_button_text', $d['btn'] );
            $url = ! empty( $d['url'] ) ? $d['url'] : '';
            if ( 'Our Journey' === $d['btn'] ) {
                $url = tutti_frutti_page_url( 'brands' );
            }
            if ( 'Get In Touch' === $d['btn'] ) {
                $url = tutti_frutti_page_url( 'contact' );
            }
            update_post_meta( $id, '_tf_button_url', $url );
        }
    }

    update_option( 'tutti_frutti_page_sections_imported', 1 );
}
add_action( 'init', 'tutti_frutti_maybe_import_page_sections', 101 );