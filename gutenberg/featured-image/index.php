<?php

$block_name = 'featured-image'; // basename( __DIR__ ) //++add optins to print different h and or.. maybe limit to sections type

add_action( 'init', function() use ( $block_name ) {

    $print_block = function( $props, $content = null ) {
        $post = get_queried_object();
        $image = fct_image( get_post_thumbnail_id( $post->ID ), [600,600], 1, $post->post_title );
        return $image ? '<div class="post-image">' . $image . '</div>' : '';
    };

    register_block_type( 'fct-gutenberg/' . $block_name, [
        'editor_script' => 'fct-' . $block_name . '-block',
        'render_callback' => $print_block
    ] );

    wp_register_script(
        'fct-' . $block_name . '-block',
        get_template_directory_uri() . '/gutenberg/' . $block_name . '/block.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components'],
        FCT_VER
    );

});
