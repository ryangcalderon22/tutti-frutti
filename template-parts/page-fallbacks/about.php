<?php
/**
 * About page fallback sections.
 *
 * @package Tutti_Frutti_Cafe
 */
$top_class = ! empty( $top ) ? ' page-section--top' : '';
?>
<section class="page-section page-section--cream<?php echo esc_attr( $top_class ); ?>">
    <div class="container">
        <div class="page-split">
            <div class="page-split__media">
                <?php tutti_frutti_demo_img( 'about_interior', __( 'Café interior', 'tutti-frutti-cafe' ), 'about-img-radius-tl' ); ?>
            </div>
            <div class="page-split__content">
                <span class="about-story-label"><?php esc_html_e( 'Our Story', 'tutti-frutti-cafe' ); ?></span>
                <h1><?php esc_html_e( 'From Frozen Yogurt to a Modern Café Experience', 'tutti-frutti-cafe' ); ?></h1>
                <p><?php esc_html_e( 'Tutti Frutti Café brings together frozen treats, specialty coffee, fresh bakery, and savory bites under one welcoming roof.', 'tutti-frutti-cafe' ); ?></p>
                <a href="<?php echo esc_url( tutti_frutti_page_url( 'brands' ) ); ?>" class="btn btn-green"><?php esc_html_e( 'Our Journey', 'tutti-frutti-cafe' ); ?></a>
            </div>
        </div>
    </div>
</section>
<section class="page-section page-section--cream">
    <div class="container">
        <div class="values-grid">
            <?php
            $values = array(
                array( '🌿', __( 'Premium Ingredients', 'tutti-frutti-cafe' ) ),
                array( '☕', __( 'Made Fresh Daily', 'tutti-frutti-cafe' ) ),
                array( '✓', __( 'Halal Certified', 'tutti-frutti-cafe' ) ),
                array( '👨‍👩‍👧', __( 'Family Owned', 'tutti-frutti-cafe' ) ),
                array( '🤝', __( 'Community Focused', 'tutti-frutti-cafe' ) ),
            );
            foreach ( $values as $v ) :
                ?>
                <div class="value-card">
                    <span class="value-card__icon" aria-hidden="true"><?php echo esc_html( $v[0] ); ?></span>
                    <span class="value-card__label"><?php echo esc_html( $v[1] ); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
