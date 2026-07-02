<?php
/**
 * Optional page content from WordPress editor.
 *
 * @package Tutti_Frutti_Cafe
 */
if ( ! have_posts() ) {
    return;
}
while ( have_posts() ) :
    the_post();
    if ( ! get_the_content() ) {
        continue;
    }
    ?>
    <section class="page-section page-section--cream page-editable-content">
        <div class="container entry-content">
            <?php the_content(); ?>
        </div>
    </section>
    <?php
endwhile;
rewind_posts();
