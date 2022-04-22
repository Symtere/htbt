<?php get_header(); ?>

    <div class="container">
        <div class="text-center page-404-content d-flex align-items-center justify-content-center">
            <div>
                <h1 class="mb-5 text-secondary"><?php echo __( 'Page non trouvée','webexpr' ); ?></h1>
                <p class="mb-5 h4"><?php echo __( "Le contenu que vous recherchez n'est pas disponible ou a été supprimé", 'webexpr' ); ?></p>
                <a href="<?php echo home_url(); ?>" class="btn btn-primary">
                    <span><?php echo __( "Revenir à l'accueil", 'webexpr' ); ?></span>
                </a>
            </div>
        </div>
    </div>

<?php get_footer();
