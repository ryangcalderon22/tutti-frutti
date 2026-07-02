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
                if ( empty( $card_lines ) && ! empty( $brand['card_desc'] ) ) {
                    $card_lines = tutti_frutti_parse_brand_card_lines( $brand['card_desc'] );
                }
                $has_lines = ! empty( $card_lines );
                ?>
                <article class="brand-card<?php echo $has_lines ? ' brand-card--lines' : ' brand-card--compact'; ?>">
                    <a href="<?php echo esc_url( $brand['url'] ); ?>" class="brand-card__link">
                        <?php tutti_frutti_brand_card_logo( $brand ); ?>
                        <?php if ( $has_lines ) : ?>
                            <ul class="brand-card__lines">
                                <?php foreach ( $card_lines as $line ) : ?>
                                    <li class="brand-card__line"><?php echo esc_html( $line ); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php elseif ( ! empty( $brand['card_title'] ) ) : ?>
                            <span class="brand-card__name"><?php echo esc_html( $brand['card_title'] ); ?></span>
                        <?php endif; ?>
                    </a>
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