<?php

// custom post type for global and reusable sections, like footer - uses Gutenberg for global sections' layouts

namespace FCT\Sections;

/* create post type */

add_action('init', function () {
	$labels = [
		'name'                => __('Sections', FCT_SET['var']),
		'singular_name'       => __('Section', FCT_SET['var']),
		'menu_name'           => __('Sections', FCT_SET['var']),
		'all_items'           => __('All sections', FCT_SET['var']),
		'view_item'           => __('View Section', FCT_SET['var']),
		'add_new'             => __('Add New', FCT_SET['var']),
		'add_new_item'        => __('Add New Section', FCT_SET['var']),
		'edit_item'           => __('Edit Section', FCT_SET['var']),
		'update_item'         => __('Update Section', FCT_SET['var']),
		'search_items'        => __('Search Section', FCT_SET['var']),
		'not_found'           => __('Section not found', FCT_SET['var']),
		'not_found_in_trash'  => __('Section not found in Trash', FCT_SET['var']),
	];
	$args = [
		'label'               => FCT_SET['pref'].'section',
		'description'         => __('Global sections, which use Gutenberg editor (footer, custom header)', FCT_SET['var']),
		'labels'              => $labels,
		'supports'            => [
			'title',
			'editor',
		],
		'hierarchical'        => false,
		'public'              => false,
		'show_in_rest'        => true, // turn on Gutenberg
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-schedule',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capabilities'        => [ // only admins
            'edit_post'          => 'switch_themes',
            'read_post'          => 'switch_themes',
            'delete_post'        => 'switch_themes',
            'edit_posts'         => 'switch_themes',
            'edit_others_posts'  => 'switch_themes',
            'delete_posts'       => 'switch_themes',
            'publish_posts'      => 'switch_themes',
            'read_private_posts' => 'switch_themes'
        ]
	];
	register_post_type($args['label'], $args);
});


/* modifying the admin table */
add_filter( 'manage_'.FCT_SET['pref'].'section'.'_posts_columns', function( $columns ) {
    $columns[ FCT_SET['pref'].'category' ] = 'Category';
    $columns[ FCT_SET['pref'].'default' ] = 'Default';
    return $columns;
});
add_action( 'manage_'.FCT_SET['pref'].'section'.'_posts_custom_column' , function( $column, $post_id ) {
    switch ( $column ) {
        case FCT_SET['pref'].'category':
            echo FCT_SET['sections'][ get_post_meta( $post_id, FCT_SET['pref'].'category', true ) ] ?? '';
            break;
        case FCT_SET['pref'].'default':
            $default_categories = get_option( FCT_SET['pref'].'default' ) ?? [];
            echo in_array( $post_id, $default_categories ) ? '<span style="color:gold;font-size:24px">&#9733;</span>' : '';
            break;
    }
}, 10, 2 );


/* printing function */

function get_section($category, $start = '', $end = '') {

	static $default_categories = [], $details = [];

    // add start & end
    $format = function($details) use ($start, $end) {
		$details['content'] = $start . $details['content'] . $end;
		return $details;
    };
    // return structure empty value
    $empty = (object) [ 'content' => '', 'menu_below' => false, 'empty' => true ];

    // save the default categories
	if ( empty( $default_categories ) ) {
		$default_categories = get_option( FCT_SET['pref'].'default' ) ?? [];
	}

    $default_id = $default_categories[ $category ] ?? null;
    $custom_id = get_post_meta( get_queried_object_id(), FCT_SET['pref'].'section'.'-'.$category, true );
    $section_id = $custom_id ?: $default_id ?: 'none'; // none is if custom is none or if is no default
    // ++ test get_queried_object_id() on taxonomies and blog pages

    if ( $section_id === 'none' ) { return $empty; }

    // get saved content
    if ( isset( $details[ $category ] ) ) {
        return (object) $format( $details[ $category ] );
    }

    // proceed if no saved data
    // get the section by ID
	$the_query = new \WP_Query( [
		'post_type'  => 'fct-section',
		'page_id'         => $section_id,
		'meta_query' => [ // only for sanitizing purpose
			[
				'key' => FCT_SET['pref'].'category',
				'value' => $category,
				'compare' => '=',
			],
		],
	]);

    if ( !$the_query->have_posts() ) { return $empty; }

    // get menu position for header - exception
    if ( $category === 'header' ) {
        $menu_below = get_post_meta( $section_id, FCT_SET['pref'].'menu-below', true ) === '1';
    }

    $collect = '';
	while ( $the_query->have_posts() ) {
		$p = $the_query->next_post();
		$collect .= apply_filters( 'the_content', $p->post_content );
	}

	// save in static
	$details[ $category ] = [
        'content' => $collect,
        'menu_below' => $menu_below ?? false,
		'empty' => false,
    ];

    return (object) $format( $details[ $category ] );
}

function print_section($category, $start = '', $end = '') {
	$content = get_section($category, $start, $end)->content;
	echo $content; return;
	if ( $category === 'header' ) {
		$content = str_replace( 'loading="lazy"', '', $content );
	}
	echo $content;
}


// add class-names for printing sidebars in post
add_filter( 'body_class', function($classes) {
	if ( !is_single() ) { return $classes; }

	if ( get_section( 'aside-left' )->empty ) {
		$classes[] = 'fct-no-aside-left';
	}
	if ( get_section( 'aside-right' )->empty ) {
		$classes[] = 'fct-no-aside-right';
	}

    return $classes;
});