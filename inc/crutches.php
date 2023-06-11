<?php

// clear cloudflare cached archieves and posts
//* doesn't really always work.. maybe cloudflare has a queue.. most probably
add_action( 'transition_post_status', function ($new_status, $old_status, $post) {

    if ( !class_exists( '\CF\WordPress\Hooks' ) ) { return; }
	if ( $new_status === $old_status ) { return; }
    if ( $old_status !== 'publish' && $new_status !== 'publish' ) { return; }

    $cf = new \CF\WordPress\Hooks;
    $cf->purgeCacheEverything();
	//$cf->purgeCacheByRelevantURLs( $post->ID );

}, 10, 3 );


// fix the gap, caused by wp-container-{ID}
//remove_filter( 'render_block', 'wp_render_layout_support_flag', 10, 2 ); // it breaks the buttons flex aligning
remove_filter( 'render_block', 'gutenberg_render_layout_support_flag', 10, 2 );


// a special menu links, which transform from anchor to a back-end hashed links
add_filter('wp_nav_menu_objects', function ($items) {
	foreach ($items as &$item) {
		if ($item->url === '#fct-logout') {
			$item->url = wp_logout_url();
		}
		if ($item->url === '#fct-profile') {
			$item->url = get_edit_profile_url();
		}
	}
	return $items;
}, 10);


// rankmath crutches
add_filter( 'rank_math/sitemap/enable_caching', '__return_false' );

// sitemap for doctors
add_filter( 'rank_math/sitemap/post_type_archive_link', function( $archive_url, $post_type ){
	return $post_type === 'doctor' ? false : $archive_url;
}, 10, 2 );

// rankmath breadcrumbs for doctors
add_filter( 'rank_math/frontend/breadcrumb/items', function( $crumbs, $class ) {
	if ( strpos( $crumbs[1][1], '/aerzte/' ) !== false ) {
		$crumbs[1][0] = 'Kliniken';
		$crumbs[1][1] = str_replace( '/aerzte/', '/kliniken/', $crumbs[1][1] );
	}
	return $crumbs;
}, 10, 2);

// return the rank math breadcrumbs for the main loop
add_shortcode( 'rank_math_breadcrumb--global', function() {
    if ( !function_exists('rank_math_the_breadcrumbs') ) { return; }
    ob_start();

    wp_reset_postdata();
    rank_math_the_breadcrumbs();

    //++-- can restore the current query here, but was never demanded

    $content = ob_get_contents();
    ob_end_clean();
    return $content;
});

// Advanced Ads async to rotate on cached pages
add_shortcode( 'the_ad_group-async', function($atts) {
	static $ind = 0;
	$ind++;

	$atts = shortcode_atts([
		'id' => 0,
	], $atts );

	$unique = md5( $ind );

	ob_start();
	?>

	<div id="advanced-ads-async-<?php echo $unique ?>"></div>
	<script>
		!function(){let a=setInterval(function(){let b=document.readyState;if(b!=='complete'&&b!=='interactive'||typeof jQuery==='undefined'){return}let $=jQuery;clearInterval(a);a=null;
			let t = '<?php echo esc_attr( $atts['id'] ) ?>';
			//const d = new Date(); // avoid any caching
			//t = + d + d.getMilliseconds();
			$.get( '/wp-json/advanced-ads/v1/group/' + t, function( data ) {
				$( '#advanced-ads-async-<?php echo $unique ?>' ).replaceWith( data );
			});
		}, 300 )}();
	</script>

	<?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
});

add_action( 'rest_api_init', function () {

	$args = [
		'methods'  => 'GET',
		'callback' => function( WP_REST_Request $request ) {
			
			if ( !defined( 'ADVADS_BASE_PATH' ) ) { return new \WP_REST_Response( '', 200 ); }

			$result = do_shortcode( '[the_ad_group id="'.$request['id'].'"]' );

			$result = new \WP_REST_Response( $result, 200 );

			nocache_headers();

			return $result;
		},
		'permission_callback' => function() { // just a debugging rake
			if ( empty( $_SERVER['HTTP_REFERER'] ) ) { return false; }
			if ( strtolower( parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_HOST ) ) !== strtolower( $_SERVER['HTTP_HOST'] ) ) { return false; }
			return true;
		},
		'args' => [
			'id' => [
				'description' => 'The search query',
				'type'        => 'integer',
				'validate_callback' => function($param) {
					return is_numeric( $param ) ? true : false;
				},
				'sanitize_settings' => function($param, $request, $key) {
					return sanitize_text_field( intval( $param ) );
				},
			],
		],
	];

	register_rest_route( 'advanced-ads/v1', '/group/(?P<id>[\d]{1,16})', $args );
});

// lucky toc return $this->make($attrs, true); is commented in shortcode

/*
 * Convert Rank Math FAQ Block Into Accordion - Option 2
 * https://rankmath.com/kb/turn-faq-block-into-accordion/
 */
/*
add_action('wp_footer', function () { // ++make conditional, take idea from Voslamber
	?>
<script>
(function($) {

var rankMath = {
	accordion: function() {
		$('.rank-math-block').find('.rank-math-answer').hide();
		$('.rank-math-block').find('.rank-math-question').click(function() {
			//Expand or collapse this panel
			$(this).nextAll('.rank-math-answer').eq(0).slideToggle('fast', function() {
				if ($(this).hasClass('collapse')) {
					$(this).removeClass('collapse');
				} else {
					$(this).addClass('collapse');
				}
			});
			//Hide the other panels
			$(".rank-math-answer").not($(this).nextAll('.rank-math-answer').eq(0)).slideUp('fast');
		});

		$('.rank-math-block .rank-math-question').click(function() {
			$('.rank-math-block .rank-math-question').not($(this)).removeClass('collapse');
			if ($(this).hasClass('collapse')) {
				$(this).removeClass('collapse');
			} else {
				$(this).addClass('collapse');
			}
		});
	}
};

rankMath.accordion();

})(jQuery);
</script>
<?php
});
//*/