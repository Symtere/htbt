<?php

$files = [
    'settings',
    'assets',
    'helpers',
    'class-wp-bootstrap-navwalker',
    'navs-menus',
    'breadcrumb',
    'taxonomies/all',
    'post-types/all',
    'pagination',
    'acf',
    //'shortcodes/all',
    'wpshapere',
    'forms',
    'comments',
    'search',
    //'rgpd',
    //'language',
];

foreach ( $files as $file ) {
    require_once get_template_directory() . '/functions/' . $file . '.php';
}


require_once get_template_directory() . '/blocks/all.php';
