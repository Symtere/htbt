<?php

function get_translated_page_id( $slug ) {

    if ( has_filter( 'wpml_object_id' ) ) {
        return apply_filters( 'wpml_object_id', get_page_by_path( $slug ), 'page', TRUE );
    }
}
function get_translated_page_permalink( $slug ) {
    return get_permalink( get_translated_page_id( $slug ) );
}
function get_translated_page_title( $slug ) {
    return get_the_title( get_translated_page_id( $slug ) );
}

function languages_list_header() {

    if ( has_filter('wpml_active_languages') ) {

        $languages = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=1&orderby=KEY' );

        if ( !empty( $languages ) ) {
            $langs = '';

            echo '<div class="lang-dropdown dropdown">';
            foreach ( $languages as $l ) {

                if ( !empty( $l['active'] ) && $l['active'] === '1' ) {
                    echo '<button class="btn btn-default dropdown-toggle" type="button" id="l-dropdown-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">' . $l['language_code'] . '<i class="fa fa-angle-down"></i></button>';
                }
                if ( $l['active'] === 0 ) {
                    $langs .= '<li><a href="'.$l['url'].'">'. $l['language_code'] . '</a></li>';
                }
            }
            echo '<ul class="dropdown-menu" aria-labelledby="l-dropdown-menu">' . $langs .'</ul></div>';
        }
    }
}
