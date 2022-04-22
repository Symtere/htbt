<?php get_header(); ?>

    <div class="single-page page-content">
        <div class="container single-container">
            <?php while ( have_posts() ) : the_post();

                do_action( 'theme_before_page_content' );

                if ( function_exists('page_has_core_cover_first') ) {
                    page_has_core_cover_first();
                }
                else {
                    the_content();
                }

                do_action( 'theme_after_page_content' );
            ?>
            <?php endwhile; ?>
        </div>
    </div>

<?php get_footer();
