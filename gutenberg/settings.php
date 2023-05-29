<?php
// ++ just move to index as these are not real settings
add_action( 'after_setup_theme', function() {

    add_theme_support( 'align-wide' ); // gutenberg full-width and wide blocks

    add_theme_support( 'editor-color-palette', (function() { // +++unify the function to use to sizes too and in printing too
        $result = [];
        $format = function($array, $prefix = '') use (&$result, &$format) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $format($value, $prefix.$key.'-');
                } else {
                    $result[] = [
                        'name'  => ucfirst($prefix.$key),
                        'slug'  => FCT_SET['pref'].$prefix.$key,
                        'color' => $value,
                    ];
                }
            }
        };
        $format( FCT_SET['colors'] );
        return $result;
    })());
    
    add_theme_support( 'editor-font-sizes', array_reduce( array_keys( FCT_SET['font_sizes'] ), function( $result, $key ) {
        $format = function($k,$v) {
            return [
                'name'      => ( is_numeric( $k ) ? 'Font Size' : ucfirst( $k ) ) . ' '.$v,
                'shortName' => ( is_numeric( $k ) ? 'Size' : ucfirst( $k ) ) . ' '.$v,
                'size'      => $v,
                'slug'      => FCT_SET['pref'] . ( is_numeric( $k ) ? 'fs-'.str_replace( '.', '_', $v ) : $k ),
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