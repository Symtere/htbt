<?php

    /* Template Name: Homepage */

    get_header(); ?>

    <div class="index-page page-content">
        <div class="container">
            <?php while ( have_posts() ) : the_post();
                the_content();
            ?>
            <?php endwhile; ?>
        </div>
    </div>

<?php get_footer();
