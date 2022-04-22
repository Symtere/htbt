<?php

$files = [
    //'post-type',
];

foreach ( $files as $file ) {
    require_once get_template_directory() . '/functions/post-types/' . $file . '.php';
}