<?php

/*
    Styles
   ========================================================================== */

function register_theme_styles()
{
    //wp_register_style( 'fa-style', '//pro.fontawesome.com/releases/v5.15.4/css/all.css', array(), '5.15.4' ); // Font Awesome V5 (CDN) (V6 uses now Kit)
    wp_register_style( 'swiper-style', get_lib_url('swiperjs/swiper-bundle.css'), array(), '7.4.1' );
    wp_register_style( 'theme-style', get_template_directory_uri() . '/style.css', array(), '1.0.0' );
}

function theme_style_attributes( $html, $handle )
{

    if ( 'fa-style' === $handle ) { // Font Awesome V5 => see fa-script
        return str_replace( "media='all'", 'media="all" integrity="sha384-rqn26AG5Pj86AF4SO72RK5fyefcQ/x32DNQfChxWvbXIyXFePlEktwD18fEz+kQU" crossorigin="anonymous"', $html );
    }

    return $html;
}
//add_filter( 'style_loader_tag', 'theme_style_attributes', 10, 2 );

function enqueue_theme_styles()
{
    /* conditionnal here if needed */

    //wp_enqueue_style( 'fa-style' );
    //wp_enqueue_style( 'swiper-style' );
    wp_enqueue_style( 'theme-style' );
}


/*
    Scripts
   ========================================================================== */

function register_theme_scripts()
{

    wp_register_script( 'bootstrap-script', get_lib_url( 'bootstrap.bundle.min.js' ), array( 'jquery' ), '4.5.0', true );
    wp_register_script( 'fa-script', '//kit.fontawesome.com/5d697b1d4b.js', [], '6.0.0', false ); // Since Font Awesome V6 (Kit instead of CDN)
    wp_register_script( 'axios-script', get_lib_url( 'scripts.js' ), [], '0.26.0', true );
    wp_register_script( 'swiper-script', get_lib_url( 'swiperjs/swiper-bundle.min.js' ), array(), '7.4.1', true );
    wp_register_script( 'sharethis-script', '//platform-api.sharethis.com/js/sharethis.js', [], false );
    wp_register_script( 'theme-script', get_js_url( 'scripts.js' ), array( 'jquery' ), '1.0.0', true );
    wp_register_script( 'jarallax-script', '//unpkg.com/jarallax@2.0.3/dist/jarallax.min.js', [], '2.0.3', true );
}

function add_scripts_params( $tag, $handle, $src )
{
    $st_key_field = function_exists('get_field_option') ? get_field_option( 'sharethis_key' ) : false;
    $st_key = $st_key_field && !empty( $st_key_field ) ? $st_key_field : '5fad0b46cc85000012ec2e4e';

    if ( $st_key_field && 'sharethis-script' === $handle ) {
        $tag = '<script src="' . esc_url( $src ) . '#property='. $st_key .'&product=custom-share-buttons" async="async" id="sharethis-script-js"></script>';
    }
    if ( 'fa-script' === $handle ) { // Since Font Awesome V6
        $tag = '<script src="' . esc_url( $src ) . '" crossorigin="anonymous" id="fa-script-js"></script>';
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'add_scripts_params', 10, 3 );

function enqueue_theme_scripts() {
    /* conditionnal here if needed */

    wp_enqueue_script( 'bootstrap-script' );
    wp_enqueue_script( 'fa-script' );
    //wp_enqueue_script( 'axios-script' );
    //wp_enqueue_script( 'swiper-script' );
    //wp_enqueue_script( 'jarallax-script' );
    wp_enqueue_script( 'theme-script' );


    // if ( is_singular( 'post' ) ) {
    //     wp_enqueue_script( 'sharethis-script' );
    // }
}


add_action( 'init', 'register_theme_styles' );
add_action( 'init', 'register_theme_scripts' );
add_action( 'wp_enqueue_scripts', 'enqueue_theme_scripts' );
add_action( 'wp_enqueue_scripts', 'enqueue_theme_styles' );
