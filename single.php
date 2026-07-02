<?php
/**
 * The template for displaying single posts
 * 
 * @package Tutti_Frutti_Cafe
 */

get_header(); ?>

<main id="primary" class="site-main">
    <?php
    while ( have_posts() ) {
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <div class="entry-meta" style="color: rgba(255,255,255,0.8); margin-top: 10px;">
                    <span class="posted-on">
                        <?php esc_html_e( 'Posted on:', 'tutti-frutti-cafe' ); ?>
                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                    </span>
                    <span class="byline">
                        <?php esc_html_e( 'by', 'tutti-frutti-cafe' ); ?>
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                            <?php the_author(); ?>
                        </a>
                    </span>
                </div>
            </header>

            <?php
            if ( has_post_thumbnail() ) {
                echo '<div style="margin: 40px 0; text-align: center;">';
                the_post_thumbnail( 'large', array( 'style' => 'border-radius: 8px;' ) );
                echo '</div>';
            }
            ?>

            <div class="entry-content">
                <?php
                the_content( sprintf(
                    wp_kses_post( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'tutti-frutti-cafe' ) ),
                    wp_kses_post( get_the_title() )
                ) );

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'tutti-frutti-cafe' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>

            <footer class="entry-footer" style="padding-top: 30px; border-top: 2px solid #eee; margin-top: 40px;">
                <?php
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                    echo '<div style="margin-bottom: 15px;">';
                    echo esc_html__( 'Categories: ', 'tutti-frutti-cafe' );
                    echo implode( ', ', array_map( function( $cat ) {
                        return '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a>';
                    }, $categories ) );
                    echo '</div>';
                }

                $tags = get_the_tags();
                if ( ! empty( $tags ) ) {
                    echo '<div>';
                    echo esc_html__( 'Tags: ', 'tutti-frutti-cafe' );
                    echo implode( ', ', array_map( function( $tag ) {
                        return '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a>';
                    }, $tags ) );
                    echo '</div>';
                }
                ?>
            </footer>
        </article>

        <?php
        // Post navigation
        the_post_navigation( array(
            'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous Post:', 'tutti-frutti-cafe' ) . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next Post:', 'tutti-frutti-cafe' ) . '</span> <span class="nav-title">%title</span>',
        ) );

        // Comments
        if ( comments_open() || get_comments_number() ) {
            comments_template();
        }
    }
    ?>
</main>

<?php get_footer();
