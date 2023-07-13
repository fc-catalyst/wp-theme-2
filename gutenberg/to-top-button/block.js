( function() {

	const el = wp.element.createElement;
	const TextControl = wp.components.TextControl;

	const width = '192px';
	const bgcolor = 'grey';
	const target = '#document-top';

	const fixWidth = value => {
		return value + 'px';//value && /^[0-9\.]+$/.test( value ) ? value + 'px' : value;
	};

	wp.blocks.registerBlockType( 'fct-gutenberg/to-top-button', {
		title: 'FCT To Top Button',
        icon: 'block-default',
		category: 'widgets',

		attributes: {
            bgcolor: {
                type: 'string'
            },
			width: {
				type: 'string'
			},
			anchor: {
				type: 'string'
			}
		},

		edit: function( props ) {
			return el(
				'a',
				{
					className: 'to-top-button',
					href: props.attributes.target || target,
					style: {
						'--width': fixWidth( props.attributes.width ) || width,
						'--bgcolor': props.attributes.bgcolor || bgcolor
					}
				},
                el( // sidebar
                    wp.element.Fragment,
                    {},
                    el( wp.blockEditor.InspectorControls, {},
                        el( wp.components.PanelBody, {},
                            el( wp.components.ColorPalette, {
                                label: 'Background Color',
                                value: props.attributes.bgcolor,
                                onChange: function( value ) {
                                    props.setAttributes( { bgcolor: value } );
                                }
                            })
                        ),
                        el( wp.components.PanelBody, {},
                            el( TextControl, {
                                label: 'Width',
								placeholder: width,
                                value: props.attributes.width ? props.attributes.width : '',
                                onChange: function( value ) {
                                    props.setAttributes( { width: value } );
                                }
                            }),
                        ),
                        el( wp.components.PanelBody, {},
                            el( TextControl, {
                                label: 'Custom Target Anchor',
								placeholder: target,
                                value: props.attributes.anchor ? props.attributes.anchor : '',
                                onChange: function( value ) {
                                    props.setAttributes( { anchor: value } );
                                }
                            }),
                        )
                    )
                )
			);
		},
		save: function( props ) {
            return null;
		}
	} );
})();
