<?php

// this file contains the initial settings sample for the theme

define( 'FCT', [
    'prefix'         => 'fct-',
    'gmap_api_key'   => '',
    'fonts_external' => '',
    
]);

define( 'FCT_VER', wp_get_theme()->get( 'Version' ) . FCT_DEV ? time() : '' );