<?php

/*
    https://www.advancedcustomfields.com/resources/blocks/
    https://www.advancedcustomfields.com/resources/acf_register_block_type/
*/

function register_products_selection_acf_block_types()
{

    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'products-selection',
            'title'             => "Listes de produits",
            'description'       => "Choix des produits à mettre en avant",
            'category'          => 'theme', // https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
            'icon'              => LOGO_SVG, // See `Constants` in wp-content/themes/webexpr/functions/settings.php
            'keywords'          => array( 'webexpr', 'produits' ), // TODO replace 'webexpr' by `client-name`
            'mode'              => 'edit',
            'multiple'          => true, // allows the block to be added multiple times
            'supports'          => [
                'mode' => false, // disable preview/edit toggle
                'anchor' => true,
                'className' => true,
                'align' => true,
                'alignWide' => true,
                'color' => [
                    'gradients' => true,
                    'background' => true,
                    'text' => false
                ],
            ],
            'render_template' => 'blocks/acf/example/render.php',
        ));
    }
}
add_action('acf/init', 'register_products_selection_acf_block_types');
