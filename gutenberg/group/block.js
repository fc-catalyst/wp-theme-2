( function() {

	var el = wp.element.createElement;
	var InnerBlocks = wp.blockEditor.InnerBlocks;

	wp.blocks.registerBlockType( 'fct-gutenberg/group', {
		title: 'FCT Group',
        icon: 'columns',
		category: 'widgets',

		attributes: {
			columns: {
				type: 'number'
			}
		},

		edit: function( props ) {
            var initial = props.attributes.columns ? props.attributes.columns : 2;
			return el( 'div',
				{ 'data-rows': initial },
				el( InnerBlocks, {
                    allowedBlocks: [
                        'fct-gutenberg/tile-one'
                    ],
                    template: [
                        [ 'fct-gutenberg/tile-one', {} ],
                        [ 'fct-gutenberg/tile-one', {} ]
                    ],
                    templateLock: false
                }),
                el( // sidebar
                    wp.element.Fragment,
                    {},
                    el( wp.blockEditor.InspectorControls, {},
                        el( wp.components.PanelBody, {},
                            el( wp.components.RangeControl, {
                                label: 'Columns',
                                value: initial,
                                onChange: function( value ) {
                                    props.setAttributes( { columns: value } );
                                },
                                min: 2,
                                max: 6
                            })
                        )
                    )
                )
			);
		},
		save: function( props ) {
			return el( InnerBlocks.Content );
		},
	} );
})();