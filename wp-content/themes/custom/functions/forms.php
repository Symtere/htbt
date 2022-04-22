<?php

//== Caldera {privacy_page}
if ( function_exists('icl_get_languages') ) {

    add_filter( 'caldera_forms_privacy_policy_page_url', function(){
        return get_translated_page_permalink( 'politique-de-confidentialite' );
    });
}

add_filter( 'caldera_forms_render_form_wrapper_classes', 'slug_add_cf_wrapper_classes', 10, 2 );
function slug_add_cf_wrapper_classes( $form_wrapper_classes, $form ) {

    //add form ID as class
    $form_wrapper_classes[] = $form[ 'ID' ];

    //add form slug as class
    $form_wrapper_classes[] = strtolower( sanitize_title( $form['name'] ) );

    //add something arbitrary as a class
    $form_wrapper_classes[] = 'form-theme';

    return $form_wrapper_classes;
}
