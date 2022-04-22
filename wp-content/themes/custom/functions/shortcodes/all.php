<?php

if ( function_exists('vc_add_shortcode_param') ) {

    require_once get_template_directory() . '/functions/shortcodes/functions.php';

    $shorts = [
        'news/filters',
    ];

    foreach ( $shorts as $short ) {
        require_once get_template_directory() . '/functions/shortcodes/' . $short . '.php';
    }
}
