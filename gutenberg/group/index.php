<?php

$block_name = 'group'; // basename( __DIR__ )

add_action( 'init', function() use ( $block_name ) {

    $print_block = function( $props, $content = null ) use ( $block_name ) {
        ob_start();

        ?>
            <div class="fct-<?php echo $block_name ?>" data-rows="<?php echo $props['columns'] ? $props['columns'] : 2 ?>">
                <?php echo( $content ) ?>
            </div>
        <?php

        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    };

    register_block_type( 'fct-gutenberg/' . $block_name, [
        'editor_script' => 'fct-' . $block_name . '-block',
        'editor_style' => 'fct-' . $block_name . '-editor',
        'render_callback' => $print_block
    ] );

    wp_register_script(
        'fct-' . $block_name . '-block',
        get_template_directory_uri() . '/gutenberg/' . $block_name . '/block.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components'],
        FCT_VER
    );
    
    wp_register_style(
        'fct-' . $block_name . '-editor',
        get_template_directory_uri() . '/gutenberg/' . $block_name . '/editor.css',
        ['wp-edit-blocks'],
        FCT_VER
    );
});

add_action( 'wp_enqueue_scripts', function() use ( $block_name ) { // ++add first screen option

    if ( !has_block( 'fct-gutenberg/' . $block_name ) ) { return; }

    wp_enqueue_style( 'fct-' . $block_name,
        get_template_directory_uri() . '/gutenberg/' . $block_name . '/style.css',
        false,
        FCT_VER,
        'all'
    );
});
