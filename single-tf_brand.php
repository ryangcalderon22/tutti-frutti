<?php
/**
 * Single brand detail — menu-style layout.
 *
 * @package Tutti_Frutti_Cafe
 */

get_header();

while ( have_posts() ) :
    the_post();
    $brand_id = get_the_ID();

    $hero_image  = tutti_frutti_get_brand_hero_image_url( $brand_id );
    $hero_image_id = tutti_frutti_get_brand_hero_image_id( $brand_id );
    $brand_logo  = tutti_frutti_get_brand_logo_url( $brand_id );
    $hero_title  = get_post_meta( $brand_id, '_tf_hero_heading', true );
    $page_h1     = $hero_title ? $hero_title : get_the_title();
    $hero_desc   = get_post_meta( $brand_id, '_tf_hero_desc', true );
    $btn_text    = get_post_meta( $brand_id, '_tf_hero_btn_text', true );
    $hero_alt = $hero_image_id
        ? tutti_frutti_get_attachment_alt( $hero_image_id, get_the_title() )
        : tutti_frutti_get_image_alt( $hero_image, get_the_title() );
    $logo_alt = tutti_frutti_get_image_alt( $brand_logo, get_the_title() );
    $order_url   = get_post_meta( $brand_id, '_tf_order_url', true );
    $grouped     = tutti_frutti_get_brand_products_grouped( $brand_id );
    $product_title = get_post_meta( $brand_id, '_tf_products_title', true );

    $has_right_hero = (bool) $hero_image;
    $hero_class     = 'brand-detail-hero' . ( $has_right_hero ? '' : ' brand-detail-hero--no-media' );
    ?>
    <main id="primary" class="site-main page-brand-detail site-main--page">
        <section class="<?php echo esc_attr( $hero_class ); ?>">
            <div class="brand-detail-hero__content">
                <?php if ( $has_right_hero ) : ?>
                    <div class="brand-detail-hero__media">
                        <?php
                        if ( $hero_image_id ) {
                            // Picked in the media library: gives srcset for free.
                            echo wp_get_attachment_image( $hero_image_id, 'large', false, array( 'alt' => $hero_alt ) );
                        } else {
                            printf(
                                '<img src="%s" alt="%s">',
                                esc_url( $hero_image ),
                                esc_attr( $hero_alt )
                            );
                        }
                        ?>
                    </div>
                <?php endif; ?>
                <?php if ( $page_h1 ) : ?>
                    <h1 class="brand-detail-hero__title"><?php echo esc_html( $page_h1 ); ?></h1>
                <?php endif; ?>
                <?php if ( $hero_desc ) : ?>
                    <p class="brand-detail-hero__desc"><?php echo esc_html( $hero_desc ); ?></p>
                <?php endif; ?>
                <?php if ( $btn_text && $order_url ) : ?>
                    <a href="<?php echo esc_url( $order_url ); ?>" class="btn btn-brown brand-detail-hero__cta" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $btn_text ); ?></a>
                <?php endif; ?>
            </div>
        </section>

        <?php if ( ! empty( $grouped ) ) : ?>
            <section class="page-section page-section--cream brand-detail-menu">
                <div class="container">
                    <?php if ( $product_title ) : ?>
                        <h2 class="section-title brand-detail-products__title"><?php echo esc_html( $product_title ); ?></h2>
                    <?php endif; ?>
                    <div class="brand-menu-columns">
                        <?php
                        $current_parent_key   = null;
                        $column_index         = 0;
                        $column_open          = false;
                        foreach ( $grouped as $group ) :
                            $group_parent_title = ! empty( $group['parent_title'] ) ? $group['parent_title'] : '';
                            // Fall back to the title for any group built without a key.
                            $group_parent_key   = ! empty( $group['parent_key'] ) ? $group['parent_key'] : $group_parent_title;
                            $group_has_title    = ! empty( $group['title'] ) && 'Tutti Frutti Products' !== $group['title'];
                            /*
                             * Heading for the column. A child category is headed by its
                             * parent; a top-level category heads its own column, so its
                             * own name is the heading. Leaves the untitled catch-all
                             * group with no heading, as before.
                             */
                            $column_heading     = $group_parent_title ? $group_parent_title : ( $group_has_title ? $group['title'] : '' );
                            if ( $group_parent_key !== $current_parent_key ) :
                                $current_parent_key = $group_parent_key;
                                if ( $column_open ) :
                                    ?>
                                    </div>
                                    <?php
                                endif;
                                $column_index++;
                                $column_open = true;
                                ?>
                                <div class="brand-column brand-column-<?php echo esc_attr( $column_index ); ?>">
                                <?php if ( $column_heading ) : ?>
                                    <div class="brand-menu-parent">
                                        <?php if ( ! empty( $group['parent_image'] ) ) : ?>
                                            <img src="<?php echo esc_url( $group['parent_image'] ); ?>" alt="<?php echo esc_attr( tutti_frutti_get_image_alt( $group['image'], $group['title'] ) ); ?>" class="brand-menu-parent__image">
                                        <?php else : ?>
                                            <?php if ( $brand_logo ) : ?>
                                                <img src="<?php echo esc_url( $brand_logo ); ?>" alt="<?php echo esc_attr( $logo_alt ); ?>" class="brand-menu-parent__logo">
                                            <?php endif; ?>
                                            <h2 class="brand-menu-parent__title"><?php echo esc_html( $column_heading ); ?></h2>
                                        <?php endif; ?>
                                    </div>
                                    <?php
                                endif;
                            endif;
                            ?>
                            <div class="brand-menu-column">
                                <?php if ( ! $column_heading && $brand_logo ) : ?>
                                    <div class="brand-menu-column__header">
                                        <img src="<?php echo esc_url( $brand_logo ); ?>" alt="<?php echo esc_attr( $logo_alt ); ?>" class="brand-menu-column__logo">
                                    </div>
                                <?php endif; ?>
                                <?php if ( ! empty( $group['image'] ) ) : ?>
                                    <div class="brand-menu-category-card">
                                        <?php if ( ! empty( $group['order_url'] ) ) : ?>
                                            <a href="<?php echo esc_url( $group['order_url'] ); ?>" class="brand-menu-category-card__link" target="_blank" rel="noopener noreferrer">
                                                <img src="<?php echo esc_url( $group['image'] ); ?>" alt="<?php echo esc_attr( tutti_frutti_get_image_alt( $group['image'], $group['title'] ) ); ?>" class="brand-menu-category-card__image" loading="lazy">
                                                <span class="brand-menu-category-card__overlay">
                                                    <span class="btn btn-brown btn-sm"><?php esc_html_e( 'Order Now', 'tutti-frutti-cafe' ); ?></span>
                                                </span>
                                            </a>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url( $group['image'] ); ?>" alt="<?php echo esc_attr( tutti_frutti_get_image_alt( $group['image'], $group['title'] ) ); ?>" class="brand-menu-category-card__image" loading="lazy">
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ( $group_parent_title && $group_has_title ) : ?>
                                    <h3 class="brand-menu-category__title">
                                        <?php echo esc_html( $group['title'] ); ?>
                                    </h3>
                                <?php endif; ?>
                                <?php if ( ! empty( $group['products'] ) ) : ?>
                                    <ul class="brand-menu-products">
                                        <?php foreach ( $group['products'] as $product ) : ?>
                                            <?php
                                            $item_order_url = '';
                                            if ( ! empty( $product['id'] ) && function_exists( 'tutti_frutti_get_product_order_url' ) ) {
                                                $item_order_url = tutti_frutti_get_product_order_url( (int) $product['id'] );
                                            }
                                            if ( ! $item_order_url && ! empty( $product['order_url'] ) ) {
                                                $item_order_url = $product['order_url'];
                                            }
                                            ?>
                                            <li class="brand-menu-product">
                                                <div class="brand-menu-product__row">
                                                    <span class="brand-menu-product__name"><?php echo esc_html( $product['name'] ); ?></span>
                                                    <?php
$instore_products = array(
    'Açaí Bowls',
    'Frozen Yogurt',
);

if ( in_array( $product['name'], $instore_products, true ) ) : ?>
    <span class="brand-menu-product__order">
        <?php esc_html_e( 'In-store Purchase', 'tutti-frutti-cafe' ); ?>
    </span>
<?php elseif ( $item_order_url ) : ?>
    <a href="<?php echo esc_url( $item_order_url ); ?>" class="brand-menu-product__order" target="_blank" rel="noopener noreferrer">
        <?php esc_html_e( 'Order Now', 'tutti-frutti-cafe' ); ?>
    </a>
<?php endif; ?>
                                                </div>
                                                <?php if ( ! empty( $product['description'] ) ) : ?>
                                                    <span class="brand-menu-product__desc"><?php echo esc_html( $product['description'] ); ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        <?php if ( $column_open ) : ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <div class="brand-detail-all-brands">
            <?php get_template_part( 'template-parts/brands/grid', null, array( 'title' => __( 'Explore All Brands', 'tutti-frutti-cafe' ), 'wrap_section' => true ) ); ?>
        </div>
    </main>
    <?php
endwhile;

get_footer();