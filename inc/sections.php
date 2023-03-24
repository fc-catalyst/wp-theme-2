<?php

// custom post type for global and reusable sections, like footer - uses Gutenberg for global sections' layouts

add_action('init', function () {
	$labels = [
		'name'                => __('Sections', 'fct'),
		'singular_name'       => __('Section', 'fct'),
		'menu_name'           => __('Sections', 'fct'),
		'all_items'           => __('All sections', 'fct'),
		'view_item'           => __('View Section', 'fct'),
		'add_new'             => __('Add New', 'fct'),
		'add_new_item'        => __('Add New Section', 'fct'),
		'edit_item'           => __('Edit Section', 'fct'),
		'update_item'         => __('Update Section', 'fct'),
		'search_items'        => __('Search Section', 'fct'),
		'not_found'           => __('Section not found', 'fct'),
		'not_found_in_trash'  => __('Section not found in Trash', 'fct'),
	];
	$args = [
		'label'               => 'fct-section',
		'description'         => __('Global sections, which use Gutenberg editor (footer, custom header)', 'fct'),
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