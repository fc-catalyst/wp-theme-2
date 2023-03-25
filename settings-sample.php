<?php

// this file contains the initial settings sample for the theme
// ++move these to the settings page, but first create it

// ++TEST IF CAN get_option TO SET TO THE CONSTANT

define( 'FCT_SET', [
    'pref'         => 'fct-',
    'gmap_api_key'   => '',
    'fonts_external' => '',
    'colors' => [
        'str-color' => '',
        'a-color' => '',
        'a-color-h' => '',
        'h-color' => '',
        'gutenberg' => [
            '#ffffff', '#000000', '#23667b', '#277888', '#87c8d3', '#fda7a7', 
        ]
    ],
    'defer_styles' => [
        'wp-block-library', 'classic-theme-styles'
    ],
    'defer_styles_theme' => true,
    
]);

define( 'FCT_VER', wp_get_theme()->get( 'Version' ) . FCT_DEV ? time() : '' );



