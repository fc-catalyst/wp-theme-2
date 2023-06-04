<?php

// meta-boxes, on-page interface

namespace FCT\MetaBoxes;

// admin interface for posts
add_action( 'add_meta_boxes', function() {
    if ( !current_user_can( 'administrator' ) ) { return; }
    add_meta_box(
        FCT_SET['pref'].'settings',
        'Theme settings',
        __NAMESPACE__.'\posts_meta_settings',
        array_keys( get_public_post_types() ),
        'side',
        'low'
    );
});

// style meta boxes && settings
add_action( 'admin_enqueue_scripts', function() {

    if ( !current_user_can( 'administrator' ) ) { return; }

    $screen = get_current_screen();
    if ( !isset( $screen ) || !is_object( $screen ) || $screen->base !== 'post' ) { return; }

    //wp_enqueue_style( $handle, $url, [], FCPPBK_VER, 'all' );

});

function posts_meta_settings() {
    global $post;
    ?>
    <div>
        <?php
        checkbox( (object) [
            'name' => FCT_SET['pref'].'hide-h1',
            'value' => get_post_meta( $post->ID, FCT_SET['pref'].'hide-h1' )[0] ?? '0',
            'label' => 'Hide H1 on this page',
            'comment' => 'It is recommended to enable this option only if there is another H1 somewhere else on the page.',
        ]);

        $sections = get_sections();
        foreach( FCT_SET['sections'] as $k => $v ) {
            ?><p><strong><?php echo $v ?></strong></p><p><?php
            select( (object) [
                'name' => FCT_SET['pref'].'section'.'-'.$k,
                'options' => ['' => 'Default'] + ($sections[ $k ] ?? []) + ['none' => 'None'],
                'value' => get_post_meta( $post->ID, FCT_SET['pref'].'section'.'-'.$k )[0] ?? [],
            ]);
            ?></p><?php
        }
        ?>
    </div>

    <input type="hidden" name="<?php echo esc_attr( FCT_SET['pref'] ) ?>nonce" value="<?= esc_attr( wp_create_nonce( FCT_SET['pref'].'nonce' ) ) ?>">

    <?php
}

// save meta data
add_action( 'save_post', function( $postID ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( empty( $_POST[ FCT_SET['pref'].'nonce' ] ) || !wp_verify_nonce( $_POST[ FCT_SET['pref'].'nonce' ], FCT_SET['pref'].'nonce' ) ) { return; }
    if ( !current_user_can( 'administrator' ) ) { return; }

    $post = get_post( $postID );
    if ( $post->post_type === 'revision' ) { return; } // kama has a different solution

    $fields = [ 'hide-h1' ];
    foreach( FCT_SET['sections'] as $k => $v ) {
        $fields[] = 'section'.'-'.$k;
    }

    foreach ( $fields as $f ) {
        $f = FCT_SET['pref'] . $f;
        if ( empty( $_POST[ $f ] ) || empty( $new_value = sanitize_meta( $_POST[ $f ], $f, $postID ) ) ) {
            delete_post_meta( $postID, $f );
            continue;
        }
        update_post_meta( $postID, $f, $new_value );
    }
});

function sanitize_meta( $value, $field, $postID ) {

    return $value;
    // ++

    $field = ( strpos( $field, FCT_SET['pref'] ) === 0 ) ? substr( $field, strlen( FCT_SET['pref'] ) ) : $field;

    switch ( $field ) {
        case ( 'hide-h1' ):
            return $value === '1' ? '1' : null;
        break;
    }

    return null;
}

function get_public_post_types() {
    static $store = [];

    if ( !empty( $store ) ) { return $store; }

    $all = get_post_types( [], 'objects' );
    foreach ( $all as $type ) {
        if ( !$type->public ) { continue; }
        $slug = $type->rewrite->slug ?? $type->name;
        $store[ $slug ] = $type->label;
    }

    asort( $store, SORT_STRING );

    return $store;
}

function get_sections() {

    $default_categories = get_option( \FCT_SET['pref'].'default' );

	$the_query = new \WP_Query( [
		'post_type'  => 'fct-section',
		'post__not_in'   => array_values( $default_categories ),
	]);

    $result = [];
	while ( $the_query->have_posts() ) {
		$p = $the_query->next_post();
        $category = get_post_meta( $p->ID, FCT_SET['pref'].'category' )[0] ?? null; // ++select all metas by key to not run it every time..
		$result[ $category ][ $p->ID ] = apply_filters( 'the_title', $p->post_title );
	}
    return $result;
}