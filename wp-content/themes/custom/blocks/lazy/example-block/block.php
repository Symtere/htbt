<?php

// https://lazyblocks.com/documentation/blocks-code/php/


/*
    ESCAPE fields for a better security

    title : esc_attr()
    content:
        allow all tags from WP editor => wp_kses_post($my_field)
        allow only br tags => wp_kses($my_field,['br' => []]);
    url : esc_url($my_field)
*/
