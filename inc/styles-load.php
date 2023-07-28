<?php

namespace FCT\Styles;

// add styles
add_action( 'wp_enqueue_scripts', function() { // using wp_footer reduced the pagespeed score somehow

    $enqueue_dir = get_template_directory() . '/assets/styles/';
    $enqueue_url = get_template_directory_uri() . '/assets/styles/';
    $enqueue_files = array_merge( ['fonts'], css_files_get() );
    $min = '';//FCT_DEV ? '' : '.min';

    foreach ( $enqueue_files as $v ) {
        if ( !is_file( $enqueue_dir . $v . $min . '.css' ) ) { continue; }
        wp_enqueue_style(
            FCT_SET['pref'] . $v,
            $enqueue_url . $v . $min . '.css',
            [ FCT_SET['pref'] . 'style' ], // after the main one to override
            FCT_VER,
            'all'
        );
    }
    
    // main css & js
    wp_enqueue_style( FCT_SET['pref'] . 'style',
    	get_template_directory_uri() . '/style'.$min.'.css',
    	[],
        FCT_VER,
        'all'
    );
    wp_enqueue_script( FCT_SET['pref'] . 'common',
		get_template_directory_uri() . '/assets/common'.$min.'.js',
		[ 'jquery' ],
		FCT_VER,
		1
	);

    // defer loading
    if ( ( FCT_SET['defer_styles_theme'] ?? false ) === true ) {
        $defer_list = array_reduce( array_merge( $enqueue_files, ['style'] ), function( $result, $item ) {
            $result[] = FCT_SET['pref'].$item;
            return $result;
        }, [] );
        defer( $defer_list );
    }
    if ( !empty( FCT_SET['defer_styles'] ?? [] ) ) {
        defer( FCT_SET['defer_styles'] );
    }

});

// add first screen styles
add_action( 'wp_enqueue_scripts', function() {

    $enqueue_inline = function ( $name, $content ) {
        wp_register_style( $name, false );
        wp_enqueue_style( $name );
        wp_add_inline_style( $name, ( FCT_DEV ? $content : css_minify( $content ) ) );
    };

    // print theme settings
    $enqueue_inline( FCT_SET['pref'].'settings', get_settings_variables() . get_settings_fonts() . get_settings_gutenberg() );

    // print external fonts
    echo FCT_SET['fonts_external'] ?? '';

    // print the styles from the first-screen
    $include_dir = get_template_directory() . '/assets/styles/first-screen/';
    $include_files = array_merge( ['style'], css_files_get() );

    foreach ( $include_files as $v ) {
        $path = $include_dir . $v . '.css';
        if ( !is_file( $path ) ) { continue; }
        $enqueue_inline( FCT_SET['pref'].'first-'.$v, file_get_contents( $path ) );
    }

}, 0 );


// make a list of .css files names, according to the url: post-type, archive, front
function css_files_get() {
    static $files = null;
    if ( $files !== null ) { return $files; }

    $files = [];

    // get post type
    $qo = get_queried_object();
    $post_type = '';
    if ( is_object( $qo ) ) {
        if ( get_class( $qo ) === 'WP_Post_Type' ) {
            $post_type = $qo->name;
        }
        if ( get_class( $qo ) === 'WP_Post' ) {
            $post_type = $qo->post_type;
        }
    }

    if ( is_singular( $post_type ) ) {
        $files[] = $post_type;
    }
    if ( is_home() || is_archive() && ( !$post_type || $post_type === 'page' ) ) {
        $files[] = 'archive-post';
    }
    if( is_post_type_archive( $post_type ) ) {
        $files[] = 'archive-' . $post_type;
    }
    
    if ( is_front_page() ) {
        $files[] = 'front-page';
    }
    if ( is_search() ) {
        $files[] = 'search';
    }
    
    if ( comments_open() ) {
        $files[] = 'comment-form';
    }

    if ( get_comments_number() && post_type_supports( get_post_type(), 'comments' ) || isset( $_GET['unapproved'] ) && $_GET['unapproved'] || isset( $_COOKIE[ 'comment_author_' . COOKIEHASH ] ) || isset( $_COOKIE[ 'comment_author_email_' . COOKIEHASH ] ) ) {
        $files[] = 'comments';
    }

    return $files;
}

function css_minify($text) {
    $text = preg_replace( '/\/\*(?:.*?)*\*\//', '', $text ); // remove comments
    $text = preg_replace( '/\s+/', ' ', $text ); // one-line & only single speces
    $text = preg_replace( '/ ?([\{\};:\>\~\+]) ?/', '$1', $text ); // remove spaces
    $text = preg_replace( '/\+(\d|var)/', ' + $1', $text ); // restore spaces in functions
    $text = preg_replace( '/(?:[^\}]*)\{\}/', '', $text ); // remove empty properties
    $text = str_replace( [';}', '( ', ' )'], ['}', '(', ')'], $text ); // remove last ; and spaces
    return trim( $text );
}

function defer($name, $priority = 10) {
    static $store = [];

    $name = array_diff( (array) $name, $store );
    $store = array_merge( $store, $name );

    add_filter( 'style_loader_tag', function ($tag, $handle) use ($name) {
        if ( is_string( $name ) && $handle !== $name || is_array( $name ) && !in_array( $handle, $name ) ) { return $tag; }
        return
            str_replace( [ 'rel="stylesheet"', "rel='stylesheet'" ], [
                'rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"',
                "rel='preload' as='style' onload='this.onload=null;this.rel=\"stylesheet\"'"
            ], $tag ).
            '<noscript>'.str_replace(
                [ ' id="'.$handle.'-css"', " id='".$handle."-css'" ], // remove doubling id
                [ '', '' ],
                substr( $tag, 0, -1 )
            ).'</noscript>' . "\n"
        ;
    }, $priority, 2 );
}

function get_settings_gutenberg() {

    $colors_to_css = function($colors) {
        $result = [];
        $priority = [];
        $format = function($array, $prefix = '') use (&$result, &$priority, &$format) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $format($value, $prefix.$key.'-');
                } else {
                    $slug = _wp_to_kebab_case( FCT_SET['pref'].$prefix.$key );
                    $result[] = '
.has-background.has-'.$slug.'-background-color { background-color: '.$value.' }
.has-text-color.has-'.$slug.'-color,
.has-text-color.has-'.$slug.'-color * { color: '.$value.' }
                    ';
                    $priority[] = '
.has-text-color .has-text-color.has-'.$slug.'-color,
.has-text-color .has-text-color.has-'.$slug.'-color * { color: '.$value.' }
                    ';
                }
            }
        };
        $format( $colors );
        return array_merge( $result, $priority );
    };

    $fonts_to_css = function( $fonts ) {
        return array_reduce( array_keys( $fonts ), function ( $result, $key ) use ( $fonts ) {
            $size = $fonts[ $key ];
            $slug = _wp_to_kebab_case( FCT_SET['pref'] . ( is_numeric( $key ) ? 'fs-'.str_replace( '.', '_', $size ) : $key ) );
            $value  = floatval( $size );
            $result[] = '.has-'.$slug.'-font-size { font-size:'.$value.'px }' . "\n";
            return $result;
        }, [] );
    };

    $content  = '';
    $content .= '/* gutenberg colors */'."\n" . implode( '', $colors_to_css( FCT_SET['colors'] ) ) . "\n\n"; // keep the array so it can be modified similar to the gutenberg settings alike function
    $content .= '/* gutenberg font-sizes */'."\n" . implode( '', $fonts_to_css( FCT_SET['font_sizes'] ) ) . "\n\n";

    return $content;
}

function get_settings_variables() {

    $colors_to_var = function( $colors ) {
        return array_reduce( array_keys( $colors ), function ( $result, $item ) use ( $colors ) {
            $color = $colors[ $item ];
            if ( !is_string( $color ) ) { return $result; }
            $result .= "\t".'--'.FCT_SET['pref'].sanitize_html_class( $item ).': '.$color.';'."\n";
            return $result;
        }, '' );
    };

    return '/* main variables */'."\n" . ':root {' . "\n" . $colors_to_var( FCT_SET['colors'] ) . "\n" . '}' . "\n\n";

}

function get_settings_fonts() {

    $fonts_to_selectors = function( $fonts ) {
        return array_reduce( array_keys( $fonts ), function ( $result, $key ) use ( $fonts ) {
            $font = $fonts[ $key ];
            $selector = ['all'=>'*','headings'=>'h1,h2,h3,h4,h5,h6'][ $key ] ?? $key;
            $result .= $selector.' { font-family:'.$font.' }'."\n";
            return $result;
        }, '' );
    };

    return '/* font families */'."\n" . $fonts_to_selectors( FCT_SET['fonts'] ) . "\n\n";

}