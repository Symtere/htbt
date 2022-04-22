<?php get_header(); ?>

    <div class="main-page page-content">
        <div class="container">
            <?php while ( have_posts() ) : the_post();
                do_action( 'theme_before_page_content' );

                if ( function_exists('page_has_core_cover_first') ) {
                    page_has_core_cover_first();
                }
                else {
                    the_content();
                }

                do_action( 'theme_after_page_content' );

                echo function_exists('get_social_items') ? sprintf('<div class="mb-5"><h3>Social Nav</h3><hr>%s</div>', get_social_items()) : '';
                echo function_exists('get_contact_map') ? sprintf('<div class="mb-5"><h3>Contact Map</h3><hr>%s</div>', get_contact_map()) : '';
            ?>
            <?php endwhile; ?>
        </div>
    </div>

<?php get_footer();
