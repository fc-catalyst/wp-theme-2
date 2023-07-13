<?php

$block_name = 'to-top-button'; // basename( __DIR__ ) //++add optins to print different h and or.. maybe limit to sections type

add_action( 'init', function() use ( $block_name ) {

    $print_block = function( $props, $content = null ) {

        $width = '192px';
        $bgcolor = 'grey';
        $target = '#document-top';

        if ( is_numeric( $props['width'] ?? '' ) ) {
            $props['width'] .= 'px';
        }

        ob_start();

        ?>
        <div>
            <a href="<?php echo esc_attr( $props['target'] ?? $target ?: $target ) ?>" class="to-top-button" title="Scroll Top"></a>
        </div>
        <style>
            .to-top-button {
                --width: <?php echo esc_attr( $props['width'] ?? $width ?: $width ) ?>;
                --bgcolor: <?php echo esc_attr( $props['bgcolor'] ?? $bgcolor ?: $bgcolor ) ?>;
            }
        </style>
        <?php

        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    };

    register_block_type( 'fct-gutenberg/' . $block_name, [
        'editor_script' => 'fct-' . $block_name . '-block',
        'editor_style' => 'fct-' . $block_name . '-editor',
        'render_callback' => $print_block
    ]);

    wp_register_script(
        'fct-' . $block_name . '-block',
        get_template_directory_uri() . '/gutenberg/' . $block_name . '/block.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components'],
        FCT_VER
    );

    wp_register_style(
        'fct-' . $block_name . '-editor',
        get_template_directory_uri() . '/gutenberg/' . $block_name . '/style.css',
        ['wp-edit-blocks'],
        FCT_VER
    );
});

add_action( 'wp_enqueue_scripts', function() use ( $block_name ) {

    //if ( !has_block( 'fct-gutenberg/' . $block_name ) ) { return; }

    wp_enqueue_style(
        'fct-' . $block_name, // ++!! change to inline?
        get_template_directory_uri() . '/gutenberg/' . $block_name . '/style.css',
        [],
        FCT_VER,
        'all'
    );
});