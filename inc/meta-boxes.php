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