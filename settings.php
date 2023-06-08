<?php

define( 'FCT_SET', [
    'var'            => 'fct',
    'pref'           => 'fct-',
    'gmap_api_key'   => '',
    'fonts' => [
        'all' => '"Raleway", "Open Sans", sans-serif',
        'headings' => '"Raleway", sans-serif',
        //'h1' => '"Poppins", sans-serif',
        //'h2' => '"Poppins", sans-serif',
    ],
    'fonts_external' => '',
    'colors' => [ // can not use 'text', 'background'
        'plain' => '#666666',
        'link' => '#6ea2cc',
        'hover' => '#22323d',
        'headline' => '#60615f',
        'background' => '#83bff1',
        'border-easy' => '#eeeeee',
        'border-divide' => '#dddddd',
        'border-separate' => '#c5c5c5',
        'bg' => [
            'light' => '#83bff1',
            'medium' => '#78b0de',
            'dark' => '#6ea2cc',
        ],
        'gutenberg' => [ // keep the position or add text index index to change globally
            '#ffffff',
            '#000000',
            'bg' => [
                '#f8f9f9',
                '#ffffff',
                '#ababab',
            ]
        ]
    ],
    'font_sizes' => [ 11, 13, 14, 16, 18, 19, 22, 40 ],
    'defer_styles' => [
        'wp-block-library', 'classic-theme-styles'
    ],
    'defer_styles_theme' => true,
    'sections' => [
        'header' => 'Header',
        'footer' => 'Footer',
        'aside-left' => 'Left Sidebar',
        'aside-right' => 'Right Sidebar',
        'aside-bottom' => 'Bottom Sidebar',
    ],
    
]);

define( 'FCT_VER', wp_get_theme()->get( 'Version' ).FCT_DEV ? time() : '' );