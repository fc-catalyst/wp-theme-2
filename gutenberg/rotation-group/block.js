(() => {

    /* add click/hover/none behavior, add button (with text) option, remove max-height and overall opening alg?, default button with no text - use no text */
    /* add option to hide button if click and show if hover????? */

	const el = wp.element.createElement;

    
	wp.blocks.registerBlockType( blockModName, {
		title: 'FCT Rotation Group',
        icon: 'columns',
		category: 'widgets',

		edit: props => {
			return el( 'div',
				{ className: `${props.className} ${prefix}main` },
				el( wp.blockEditor.InnerBlocks, {})
			);
		},
		save: props => {
			return el( wp.blockEditor.InnerBlocks.Content );
		},
	} );
})();