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
$grid_desc = isset( $args['description'] ) ? $args['description'] : '';
$wrap        = isset( $args['wrap_section'] ) ? (bool) $args['wrap_section'] : true;
$show_button = isset( $args['show_button'] )
    ? (bool) $args['show_button']
    : (bool) get_theme_mod( 'tf_show_brand_card_button', false );

/*
 * Heading level. The homepage keeps <h2> because the hero already owns the <h1>;
 * the Brands page passes 'h1' since this heading is that page's main title.
 * Whitelisted because the value is written straight into the tag.
 */
$heading_tag = isset( $args['heading_tag'] ) && in_array( $args['heading_tag'], array( 'h1', 'h2' ), true )
    ? $args['heading_tag']
    : 'h2';

// The page-level heading is a page title, not a section title.
$heading_class = ( 'h1' === $heading_tag ) ? 'page-title' : 'section-title';
?>
<?php if ( $wrap ) : ?>
<section class="home-section home-brands page-brands__section page-section--cream">
    <div class="container">
<?php endif; ?>
        <?php if ( $grid_title ) : ?>
            <?php printf( '<%1$s class="%2$s">%3$s</%1$s>', $heading_tag, esc_attr( $heading_class ), esc_html( $grid_title ) ); ?>
        <?php endif; ?>
        <?php if ( $grid_desc ) : ?>
            <p class="section-description"><?php echo esc_html( $grid_desc ); ?></p>
        <?php endif; ?>
        <div class="brands-grid">
            <?php foreach ( tutti_frutti_get_brands() as $brand ) : ?>
                <?php
                $card_lines = ! empty( $brand['card_lines'] ) ? $brand['card_lines'] : array();
                $has_lines  = ! empty( $card_lines );

                $home_title = ! empty( $brand['home_card_title'] ) ? $brand['home_card_title'] : '';
                $home_desc  = ! empty( $brand['home_card_desc'] ) ? $brand['home_card_desc'] : '';
                $home_link  = ! empty( $brand['home_card_link_title'] ) ? $brand['home_card_link_title'] : '';

                // Descriptive link is suppressed on the homepage.
                $show_cta   = $home_link && !! is_front_page();
                $has_intro  = $home_title || $home_desc || $show_cta;
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
                                    <h3 class="brand-card__heading"><a href="<?php echo esc_url( $brand['url'] ); ?>" class="brand-card__link"><?php echo esc_html( $home_title ); ?></a></h3>
                                <?php endif; ?>
                                <?php if ( $home_desc ) : ?>
                                    <p class="brand-card__summary"><?php echo esc_html( $home_desc ); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if ( $show_cta ) : ?>
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