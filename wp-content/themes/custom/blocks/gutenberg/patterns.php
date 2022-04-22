<?php

function we_register_blocks_patterns()
{
    // Add new pattern category
    register_block_pattern_category(
        'webepxr', [ 'label' => 'Webepxr' ],
    );

    //== My pattern
    register_block_pattern(
        'webexpr/my-pattern',
        [
            'title' => 'My pattern',
            'categories' => [ 'webepxr' ],
            'keywords' => [ 'webepxr', "contenu" ],
            'content' => '<!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center">Lorem paragraph</p>
            <!-- /wp:paragraph -->
            '
        ],
    );
}
//add_action( 'init', 'we_register_blocks_patterns' );

/*

{"url":"'. wp_get_upload_dir()['baseurl'] . '/2022/03/xxxx.jpg"

<!-- wp:image {"id":687,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="'. get_img_url('default-img.gif').'" alt="" class="wp-image-687"/></figure>
<!-- /wp:image -->

<div class="wp-block-buttons"><!-- wp:button {"align":"center","className":"btn-primary-100"} -->
<div class="wp-block-button aligncenter btn-primary-100"><a class="wp-block-button__link" href="'. get_site_url() . '/contact/">Contactez-nous</a></div>
<!-- /wp:button --></div>

*/
