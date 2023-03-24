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
remove_filter( 'render_block', 'wp_render_layout_support_flag', 10, 2 );
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

/*
 * Convert Rank Math FAQ Block Into Accordion - Option 2
 * https://rankmath.com/kb/turn-faq-block-into-accordion/
 */
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