<?php

function we_register_cover_styles() {

    register_block_style('core/cover', [
        'name' => 'has-parallax',
        'label' => 'Parallax',
        //'is_default' => true,
    ]);

}
//add_action('init', 'we_register_cover_styles');

function we_enqueue_cover_assets() {
    $dir = get_stylesheet_directory_uri() . '/css';
    wp_register_style('my-cover', $dir . '/my-cover.css', false);
}
//add_action('wp_enqueue_scripts', 'we_enqueue_cover_assets', 99);
