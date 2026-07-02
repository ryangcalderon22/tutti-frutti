<?php
/**
 * The template for displaying pages
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
                the_content();

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'tutti-frutti-cafe' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>
        </article>

        <?php
        // Comments
        if ( comments_open() || get_comments_number() ) {
            comments_template();
        }
    }
    ?>
</main>

<?php get_footer();
