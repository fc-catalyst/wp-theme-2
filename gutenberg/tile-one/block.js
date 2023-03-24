( function() {

	var el = wp.element.createElement;
    var MediaUpload = wp.blockEditor.MediaUpload;
    var TextControl = wp.components.TextControl;

	wp.blocks.registerBlockType( 'fct-gutenberg/tile-one', {
		title: 'FCT Tile One',
        icon: 'columns',
		category: 'widgets',

		attributes: {
			mediaID: {
				type: 'number'
			},
			mediaURL: {
				type: 'string'
			},
			excerpt: {
				type: 'string'
			},
			url: {
				type: 'string'
			},
            color1: {
                type: 'string'
            },
            numbered: {
                type: 'boolean'
            }
		},

		edit: function( props ) {

			var onSelectImage = function( media ) {
				return props.setAttributes( {
					mediaURL: media.sizes && media.sizes.thumbnail ? media.sizes.thumbnail.url : media.url,
					mediaID: media.id,
				} );
			};
            
			return el( 'div',
				{ 'className': !!props.attributes.numbered ? 'numbered' : '' },
                el( MediaUpload, {
                    onSelect: onSelectImage,
                    allowedTypes: 'image',
                    value: props.attributes.mediaID,
                    render: function( obj ) {
                        return el( wp.components.Button,
                            {
                                onClick: obj.open,
                            },
                            ! props.attributes.mediaID
                                ? 'Upload Image'
                                : el( 'img', { src: props.attributes.mediaURL } )
                        );
                    }
                }),
                el( TextControl, {
                    placeholder: 'Excerpt',
                    value: props.attributes.excerpt ? props.attributes.excerpt : '',
                    onChange: function( value ) {
						props.setAttributes( { excerpt: value } );
					}
                }),
                el( // sidebar
                    wp.element.Fragment,
                    {},
                    el( wp.blockEditor.InspectorControls, {},
                        el( wp.components.PanelBody, {},
                            el( wp.components.ToggleControl, {
                                label: 'Numbered',
                                checked: !!props.attributes.numbered,
                                onChange: function() {
                                    props.setAttributes( { numbered: !props.attributes.numbered } );
                                }
                            })
                        ),
                        el( wp.components.PanelBody, {},
                            el( wp.components.ColorPalette, {
                                label: 'Color for the Number',
                                colors: [ // ++find a way to use the default pallet ++use classes from slug!! ++toggle
                                    {
                                        'name'  : 'Dark 1',
                                        'slug'  : 'fct-dark-1',
                                        'color' : '#23667b'
                                    },
                                    {
                                        'name'  : 'Dark 2',
                                        'slug'  : 'fct-dark-2',
                                        'color' : '#277888'
                                    },
                                    {
                                        'name'  : 'Dark 3',
                                        'slug'  : 'fct-dark-3',
                                        'color' : '#58acbc'
                                    },
                                    {
                                        'name'  : 'Dark 4',
                                        'slug'  : 'fct-dark-4',
                                        'color' : '#0087a0'
                                    },
                                    {
                                        'name'  : 'Dark 5',
                                        'slug'  : 'fct-dark-5',
                                        'color' : '#dfc082'
                                    },
                                    {
                                        'name'  : 'Light 1',
                                        'slug'  : 'fct-light-1',
                                        'color' : '#87c8d3'
                                    },
                                    {
                                        'name'  : 'Warning 1',
                                        'slug'  : 'fct-warning-1',
                                        'color' : '#fda7a7'
                                    },
                                    {
                                        'name'  : 'White',
                                        'slug'  : 'white',
                                        'color' : '#fff'
                                    },
                                    {
                                        'name'  : 'Black',
                                        'slug'  : 'black',
                                        'color' : '#000'
                                    },
                                    {
                                        'name'  : 'Grey 1',
                                        'slug'  : 'fct-grey-1',
                                        'color' : '#22262c'
                                    },
                                    {
                                        'name'  : 'Grey 2',
                                        'slug'  : 'fct-grey-2',
                                        'color' : '#2f3339'
                                    },
                                    {
                                        'name'  : 'Grey 3',
                                        'slug'  : 'fct-grey-3',
                                        'color' : '#2c3538'
                                    },
                                    {
                                        'name'  : 'Grey 4',
                                        'slug'  : 'fct-grey-4',
                                        'color' : '#d3d7da'
                                    }
                                ],
                                value: props.attributes.color1,
                                onChange: function( value ) {
                                    props.setAttributes( { color1: value } );
                                }
                            })
                        ),
                        el( wp.components.PanelBody, {},
                            el( TextControl, {
                                label: 'Link URL',
                                value: props.attributes.url ? props.attributes.url : '',
                                onChange: function( value ) {
                                    props.setAttributes( { url: value } );
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