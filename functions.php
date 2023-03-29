<?php

define( 'FCT_DEV', true ); // developer mode

require __DIR__ . ( is_file(__DIR__ . '/settings.php') ? '/settings.php' : '/settings-sample.php' );
require __DIR__ . '/inc/styles-load.php';
require __DIR__ . '/inc/image-onthefly.php';
//require __DIR__ . '/inc/text-filtering.php'; // ++move to the plugin
require __DIR__ . '/inc/shortcodes.php';
require __DIR__ . '/inc/comments.php';
require __DIR__ . '/inc/crutches.php';
require __DIR__ . '/inc/sections.php';
require __DIR__ . '/gutenberg/index.php';
require __DIR__ . '/gutenberg/settings.php';


/* translations */
add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'fct', get_template_directory() . '/languages' );
});


/* theme settings */
add_action( 'after_setup_theme', function () {

	add_theme_support('post-thumbnails');
	add_theme_support('title-tag');

	add_theme_support('custom-logo', [ // upload logo field for customizer
		'width'       => 700,
		'height'      => 160,
		'flex-width'  => true, // can't use simple svg support with strict rules here
		'flex-height' => true, // or here
		'header-text' => '',
		'unlink-homepage-logo' => true,
	]);
});

// menu & thumbnails
add_action( 'init', function () {
	register_nav_menus([
		'main' => 'Main Menu Visitor',
		'logged' => 'Main Menu User', // logged in
	]);
});

// remove 'Archive: ' text from the headline
/* not used here ??
add_filter( 'get_the_archive_title', function ($title) {

	if (is_post_type_archive()) {
		//return post_type_archive_title('', false);
	}

	// ++argument the following
	global $post;
	return $post->post_title;
});
//*/

// excerpt // can't effect the order
add_filter( 'excerpt_length', function ($number) {
	return 18;
});
add_filter( 'excerpt_more', function ($more) {
	return '';
});
add_filter( 'wp_trim_excerpt', function($text, $raw_excerpt) {
	return rtrim( $text, ',.…!?&([{-_ "„“' ) . '…';
}, 5, 2 );


// SVG support
add_filter( 'upload_mimes', function ($types) { // better install the plugin for SVG support!! no conflicts
	if ( !current_user_can('administrator') ) { return $types; }
	return $types + [ 'svg' => 'image/svg+xml' ];
}, 10, 2 );



/* economy */

// disable creating default sizes on upload, solved by inc/image-onthefly.php
add_action( 'intermediate_image_sizes_advanced', function ($sizes) {
	unset(
		$sizes['medium'],
		//$sizes['large'], // kept for blog posts
		$sizes['medium_large'],
		$sizes['1536x1536'],
		$sizes['2048x2048']
	);
	return $sizes;
});
// disable displaying medium size in admin
add_action( 'admin_enqueue_scripts', function () {
	$screen = get_current_screen();
	if ($screen->id !== 'options-media') { return; }

	$name = 'fct-media-settings-hide-option';
	wp_register_style( $name, false );
	wp_enqueue_style( $name );
	wp_add_inline_style( $name, '
		#wpbody-content form > table:first-of-type tr:nth-of-type(2) {
			display: none;
		}	
	' );
});


// disable emoji, just taken from somewhere
add_action('init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	// Remove from TinyMCE
	add_filter( 'tiny_mce_plugins', function ($plugins) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, ['wpemoji'] );
		} else {
			return [];
		}
	});
});

// remove jquery migrate, as nothing uses it
add_action('wp_default_scripts', function ($scripts) {
	if ( !is_admin() && isset( $scripts->registered['jquery'] ) ) {
		$script = $scripts->registered['jquery'];
		if ( $script->deps ) {
			$script->deps = array_diff( $script->deps, ['jquery-migrate'] );
		}
	}
});
// ++ defer loading jquery if no external plugins demand it
// ++ remove wp-blocks as the most of it is loaded on the first screen


/* theme details */

// add custom theme styling for admin-side
add_action( 'admin_init', function () {

	wp_admin_css_color(
		'klinikerfahrungen',
		'Klinikerfahrungen',
		get_template_directory_uri() . '/assets/styles/style-admin.css',
		['#0b4562', '#89cad6', '#fff', '#fff', '#fff', '#fda7a7']
	);

});


// print the loadings script the highest
$fcLoadScriptVariable = function () {
	$name = 'fcLoadScriptVariable';
	$settings = (object) [
		'ver' => FCT_VER,
		'gmapKey' => FCT_SET['gmap_api_key'] ?? '',
	];

	$content  = file_get_contents( __DIR__ . '/assets/fcLoadScriptVariable' . ( FCT_DEV ? '.js' : '.min.js' ) );
	$content .= 'window.fct = ' . json_encode( $settings );

	wp_register_script( $name, '' );
	wp_enqueue_script( $name );
	wp_add_inline_script( $name, $content );
};
add_action( 'wp_head', $fcLoadScriptVariable, 0 );
add_action( 'admin_enqueue_scripts', $fcLoadScriptVariable, 0 );
unset( $fcLoadScriptVariable );