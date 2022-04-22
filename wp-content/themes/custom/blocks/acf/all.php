<?php

$blocks = [
    'example',
    'alternate-media-content',
];

foreach ( $blocks as $block ) {
    require_once get_template_directory() . '/blocks/acf/' . $block . '/init.php';
}
