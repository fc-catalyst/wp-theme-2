<?php

define( 'FCT_SET', [
    'var'            => 'fct',
    'pref'           => 'fct-',
    'gmap_api_key'   => '',
    'fonts_external' => '',
    'colors' => [ // can not use 'text', 'background'
        'plain' => '#22323d',
        'link' => '#007991',
        'hover' => '#ffab5e',
        'headline' => '#208294',
        'gutenberg' => [ // keep the position / index to change globally
            '#ffffff', '#000000', '#f2f8f9', '#ecf0f2', '#ededed', '#eaeef0', 
        ]
    ],
    'font_sizes' => [ 13, 14, 15, 'sixteen' => 16, 20.8, 25, 37, 45, 80 ],
    'defer_styles' => [
        'wp-block-library', 'classic-theme-styles'
    ],
    'defer_styles_theme' => true,
    
]);

define( 'FCT_VER', wp_get_theme()->get( 'Version' ).FCT_DEV ? time() : '' );