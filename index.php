<?php
/**
 * Main Template File
 * 
 * @package Tutti_Frutti_Cafe
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        if ( have_posts() ) {
            echo '<div class="posts-container">';
            
            while ( have_posts() ) {
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-item' ); ?>>
                    <header class="entry-header-small">
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="entry-meta">
                            <span class="posted-on">
                                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                    <?php echo esc_html( get_the_date() ); ?>
                                </time>
                            </span>
                            <span class="byline">
                                by <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a>
                            </span>
                        </div>
                    </header>

                    <?php
                    if ( has_post_thumbnail() ) {
                        echo '<div class="post-thumbnail">';
                        the_post_thumbnail( 'large' );
                        echo '</div>';
                    }
                    ?>

                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
                <?php
            }
            
            echo '</div>';
            
            // Pagination
            the_posts_pagination( array(
                'prev_text' => esc_html__( 'Previous', 'tutti-frutti-cafe' ),
                'next_text' => esc_html__( 'Next', 'tutti-frutti-cafe' ),
            ) );
        } else {
            echo '<p>' . esc_html__( 'No posts found.', 'tutti-frutti-cafe' ) . '</p>';
        }
        ?>
    </div>
</main>

<?php get_footer();
