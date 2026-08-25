<?php
/**
 * Shared brands grid.
 *
 * @package Tutti_Frutti_Cafe
 *
 * @var string $title Optional section title.
 */

if ( ! isset( $args ) || ! is_array( $args ) ) {
    $args = array();
}
$grid_title  = isset( $args['title'] ) ? $args['title'] : __( 'Explore Our Brands', 'tutti-frutti-cafe' );
$wrap        = isset( $args['wrap_section'] ) ? (bool) $args['wrap_section'] : true;
$show_button = isset( $args['show_button'] )
    ? (bool) $args['show_button']
    : (bool) get_theme_mod( 'tf_show_brand_card_button', false );
?>
<?php if ( $wrap ) : ?>
<section class="home-section home-brands page-brands__section page-section--cream">
    <div class="container">
<?php endif; ?>
        <?php if ( $grid_title ) : ?>
            <h2 class="section-title"><?php echo esc_html( $grid_title ); ?></h2>
        <?php endif; ?>
        <div class="brands-grid">
            <?php foreach ( tutti_frutti_get_brands() as $brand ) : ?>
                <?php
                $card_lines = ! empty( $brand['card_lines'] ) ? $brand['card_lines'] : array();
                $has_lines  = ! empty( $card_lines );

                $home_title = ! empty( $brand['home_card_title'] ) ? $brand['home_card_title'] : '';
                $home_desc  = ! empty( $brand['home_card_desc'] ) ? $brand['home_card_desc'] : '';
                $home_link  = ! empty( $brand['home_card_link_title'] ) ? $brand['home_card_link_title'] : '';
                $has_intro  = $home_title || $home_desc || $home_link;
                ?>
                <article class="brand-card<?php echo $has_lines ? ' brand-card--lines' : ' brand-card--compact'; ?>">
                    <a href="<?php echo esc_url( $brand['url'] ); ?>" class="brand-card__link">
                        <?php tutti_frutti_brand_card_logo( $brand ); ?>
                        <?php //if ( $has_lines ) : ?>
                            <!-- <ul class="brand-card__lines">
                                <?php //foreach ( $card_lines as $line ) : ?>
                                    <li class="brand-card__line"><?php //echo esc_html( $line ); ?></li>
                                <?php //endforeach; ?>
                            </ul> -->
                        <?php //endif; ?>
                    </a>
                    <?php if ( $has_intro ) : ?>
                        <div class="brand-card__intro">
                            <div class="brand-card__intro-content">
                                <?php if ( $home_title ) : ?>
                                    <h3 class="brand-card__heading"><?php echo esc_html( $home_title ); ?></h3>
                                <?php endif; ?>
                                <?php if ( $home_desc ) : ?>
                                    <p class="brand-card__summary"><?php echo esc_html( $home_desc ); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if ( $home_link ) : ?>
                                <a href="<?php echo esc_url( $brand['url'] ); ?>" class="brand-card__cta"><?php echo esc_html( $home_link ); ?></a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ( $show_button ) : ?>
                        <?php
                        $btn_label = ! empty( $brand['card_button'] ) ? $brand['card_button'] : __( 'Explore Menu', 'tutti-frutti-cafe' );
                        ?>
                        <a href="<?php echo esc_url( $brand['url'] ); ?>" class="btn btn-sm <?php echo esc_attr( $brand['btn'] ); ?>"><?php echo esc_html( $btn_label ); ?></a>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
<?php if ( $wrap ) : ?>
    </div>
</section>
<?php endif; ?>