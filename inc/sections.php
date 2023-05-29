<?php

// custom post type for global and reusable sections, like footer - uses Gutenberg for global sections' layouts

namespace FCT\Sections;

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


add_action( 'add_meta_boxes', function() {
    if ( !current_user_can( 'administrator' ) ) { return; }
    add_meta_box(
        FCT_SET['pref'].'section-settings',
        'Settings',
        __NAMESPACE__.'\settings_bar',
        [FCT_SET['pref'].'section'],
        'side',
        'high'
    );
});

add_action( 'save_post', function( $postID ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( !isset( $_POST[ FCT_SET['pref'].'nonce' ] ) || !wp_verify_nonce( $_POST[ FCT_SET['pref'].'nonce' ], FCT_SET['pref'].'nonce' ) ) { return; }
    if ( !current_user_can( 'administrator' ) ) { return; }

    $post = get_post( $postID );
    if ( $post->post_type === 'revision' ) { return; }

    $fields = [ 'category' ];

    foreach ( $fields as $f ) {
        $f = FCT_SET['pref'] . $f;
        if ( empty( $_POST[ $f ] ) || empty( $new_value = sanitize_meta( $_POST[ $f ], $f, $postID ) ) ) {
            delete_post_meta( $postID, $f );
        } else {
        	update_post_meta( $postID, $f, $new_value );
		}

		// update the default option
		$d = FCT_SET['pref'].'default';
		if ( $f === FCT_SET['pref'].'category' ) {
			$defaults = get_option( $d ) ?: [];
			if ( ( $found = array_search( $postID, $defaults ) ) !== false ) {
				unset( $defaults[ $found ] );
			}
			if ( $_POST[ $f ] && $_POST[ $d ] ) {
				$defaults[ $_POST[ $f ] ] = $postID;
			}
			update_option( $d, $defaults );
		}
    }

});

function sanitize_meta( $value, $field, $postID ) {

    $field = ( strpos( $field, FCT_SET['pref'] ) === 0 ) ? substr( $field, strlen( FCT_SET['pref'] ) ) : $field;

    switch ( $field ) {
        case ( 'category' ):
            return in_array( $value, ['', 'header', 'footer'] ) ? $value : 'header';
        break;
    }

    return '';
}

function settings_bar() {
    global $post;

	?><p><strong>Position</strong></p><?php

	select( (object) [
		'name' => FCT_SET['pref'].'category',
		'options' => ['' => 'None', 'header' => 'Header', 'footer' => 'Footer'],
		'value' => get_post_meta( $post->ID, FCT_SET['pref'].'category' )[0] ?? [],
	]);

	?><br><br><p><strong>Default</strong></p><?php

	$d = FCT_SET['pref'].'default';

	checkbox( (object) [ // ++ replace with apply per post-type, archieve.. lime in first-screen css plugin
		'name' => $d,
		'option' => '1',
		'label' => 'Use as default',
		'value' => in_array( $post->ID, get_option( $d ) ?: [] ),
	]);


	?>
    
    <input type="hidden" name="<?php echo esc_attr( FCT_SET['pref'].'nonce' ) ?>" value="<?= esc_attr( wp_create_nonce( FCT_SET['pref'].'nonce' ) ) ?>">

    <?php
}

function select($a) {
    ?>
    <select
        name="<?php echo esc_attr( $a->name ) ?>"
        id="<?php echo esc_attr( $a->id ?? $a->name ) ?>"
        class="<?php echo esc_attr( $a->className ?? '' ) ?>"
    >
    <?php foreach ( $a->options as $k => $v ) { ?>
        <option value="<?php echo esc_attr( $k ) ?>"
            <?php selected( !empty( $a->value ) && $k === $a->value, true ) ?>
        ><?php echo esc_html( $v ) ?></option>
    <?php } ?>
    </select>
    <?php echo isset( $a->comment ) ? '<p><em>'.esc_html( $a->comment ).'</em></p>' : '' ?>
    <?php
}
function checkbox($a) {
    ?>
    <label>
        <input type="checkbox"
            name="<?php echo esc_attr( $a->name ) ?>"
            id="<?php echo esc_attr( $a->id ?? $a->name ) ?>"
            value="<?php echo esc_attr( $a->option ) ?>"
            class="<?php echo esc_attr( $a->className ?? '' ) ?>"
            <?php checked( $a->option, $a->value ) ?>
        >
        <span><?php echo esc_html( $a->label ) ?></span>
    </label>
    <?php echo isset( $a->comment ) ? '<p><em>'.esc_html( $a->comment ).'</em></p>' : '' ?>
    <?php
}

function print_section($category) {
	static $default_categories = [];

	if ( empty( $default_categories ) ) {
		$default_categories = get_option( FCT_SET['pref'].'default' );
	}

	if ( !isset( $default_categories[ $category ] ) ) { return; }

	$the_query = new \WP_Query( [
		'post_type'  => 'fct-section',
		'post__in'   => [ $default_categories[ $category ] ],
		'meta_query' => [
			[
				'key' => FCT_SET['pref'].'category',
				'value' => $category,
				'compare' => '=',
			],
		],
	]);

	while ( $the_query->have_posts() ) {
		$p = $the_query->next_post();
		echo apply_filters( 'the_content', $p->post_content );
	}
}