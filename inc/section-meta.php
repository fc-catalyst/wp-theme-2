<?php

namespace FCT\Sections;

/* add meta boxes */

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

    $fields = [ 'category', 'menu-below' ];

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
            return in_array( $value, array_merge( [''], array_keys( FCT_SET['sections'] ) ) ) ? $value : 'header';
        break;
        case ( 'menu-below' ):
            return $value === '1' ? '1' : null;
        break;
        case ( 'default' ):
            return $value === '1' ? '1' : null;
        break;
    }

    return '';
}

function settings_bar() {
    global $post;

	?><p><strong>Category</strong></p><p><?php

	$category = get_post_meta( $post->ID, FCT_SET['pref'].'category' )[0] ?? '';
	\FCT\MetaBoxes\select( (object) [
		'name' => FCT_SET['pref'].'category',
		'options' => array_merge( ['' => 'None'], FCT_SET['sections'] ),
		'value' => $category,
	]);

	if ( $category === 'header' ) { // the exception only for the header

		?></p></p><?php

		\FCT\MetaBoxes\checkbox( (object) [
			'name' => FCT_SET['pref'].'menu-below',
			'option' => '1',
			'label' => 'Place Menu under the Header',
			'value' => get_post_meta( $post->ID, FCT_SET['pref'].'menu-below' )[0] ?? '0',
		]);
	}

	?></p><p><strong>Default</strong></p><?php

	\FCT\MetaBoxes\checkbox( (object) [ // ++ replace with apply per post-type, archieve.. like in first-screen css plugin
		'name' => FCT_SET['pref'].'default',
		'option' => '1',
		'label' => 'Use as default',
		'value' => in_array( $post->ID, get_option( FCT_SET['pref'].'default' ) ?: [] ),
	]);


	?>
    
    <input type="hidden" name="<?php echo esc_attr( FCT_SET['pref'].'nonce' ) ?>" value="<?= esc_attr( wp_create_nonce( FCT_SET['pref'].'nonce' ) ) ?>">

    <?php
}