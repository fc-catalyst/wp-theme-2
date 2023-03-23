( function( blocks, element, blockEditor ) {
	var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

	blocks.registerBlockType( 'fct1-gutenberg/headline', {
		title: 'FCT1 Main Query H1',
        icon: 'block-default',
		category: 'widgets',
		edit: function( props ) {
			return el(
				'h1',
				{ className: props.className },
                'The Main Query H1'
			);
		},
		save: function( props ) {
            return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor );
