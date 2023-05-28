<?php

add_action( 'after_setup_theme', function() {

    add_theme_support( 'align-wide' ); // gutenberg full-width and wide blocks

    add_theme_support( 'editor-color-palette', array_reduce( array_keys( FCT_SET['colors'] ), function( $result, $key ) {
        $format = function($k,$v, $prefix = '') use (&$format) {
            if ( is_array( $v ) ) {
                return array_map( function($l) use ($k, $v, $prefix, $format) {
                    return $format( $l, FCT_SET['colors'][ $k ][ $l ], $prefix.$k.'-' )[0];
                }, array_keys( $v ) );
            }
            return [[
                'name'  => ucfirst( $prefix.$k ),
                'slug'  => FCT_SET['pref'].$prefix.$k,
                'color' => $v,
            ]];
        };
        return array_merge( $result, $format( $key, FCT_SET['colors'][ $key ] ) );
    }, [] ));
    
    
    add_theme_support( 'editor-font-sizes', array_reduce( array_keys( FCT_SET['font_sizes'] ), function( $result, $key ) {
        $format = function($k,$v) {
            return [
                'name'      => ( is_numeric( $k ) ? 'Font Size' : ucfirst( $k ) ) . ' '.$v,
                'shortName' => ( is_numeric( $k ) ? 'Size' : ucfirst( $k ) ) . ' '.$v,
                'size'      => $v,
                'slug'      => FCT_SET['pref'] . ( is_numeric( $k ) ? 'fs' : $k ) . '-'.str_replace( '.', '_', $v ),
            ];
        };
        $result[] = $format( $key, FCT_SET['font_sizes'][ $key ] );
        return $result;
    }, [] ));

/* add later
    add_theme_support( 'editor-gradient-presets', [ // custom gradients
        [
            'name'     => __( 'Gradient', 'fct1' ) . ' 1',
            'gradient' => 'linear-gradient(60deg, #277888 10%, #58acbc 90%)',
            'slug'     => 'fct1-gradient-1'
        ],
        [
            'name'     => __( 'Gradient', 'fct1' ) . ' 2',
            'gradient' => 'linear-gradient(240deg, #fce0a9 20%, #d3af69 90%)',
            'slug'     => 'fct1-gradient-2'
        ]
    ]);
    add_action( 'admin_print_styles', function() { // custom gradients to work properly in the back-end
    ?>
<style>
    .has-fct1-gradient-1-gradient-background { background:linear-gradient(60deg, #277888 10%, #58acbc 90%) }
    .has-fct1-gradient-2-gradient-background { background:linear-gradient(240deg, #fce0a9 20%, #d3af69 90%) }
</style>
    <?php
    });
//*/

});