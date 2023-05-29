( function() {

    var effected_blocks = ['core/image'];
    
    // add new settings variable
    wp.hooks.addFilter(
        'blocks.registerBlockType',
        'fct-gutenberg/img-to-bg-variable',
        function (settings, name) {

            if ( typeof settings.attributes === 'undefined' || !~effected_blocks.indexOf( name ) ) { return settings }

            settings.attributes = Object.assign( settings.attributes, {
                turnToHero: {
                    type: 'boolean',
                }
            });

            return settings;
        }
    );

    // add the control / input
    var el = wp.element.createElement;
    
    wp.hooks.addFilter(
        'editor.BlockEdit',
        'fct-gutenberg/img-to-bg-control',
        wp.compose.createHigherOrderComponent( function ( BlockEdit ) {
            return function ( props ) {
                return el(
                    wp.element.Fragment,
                    {},
                    el( BlockEdit, props ),
                    props.isSelected && ~effected_blocks.indexOf( props.name ) ? (
                        el( wp.blockEditor.InspectorControls, {},
                            el( wp.components.PanelBody, {},
                                el( wp.components.ToggleControl, {
                                    label: 'Apply as the Background', // ++add translation
                                    checked: !!props.attributes.turnToHero,
                                    onChange: function() {
                                        props.setAttributes( { turnToHero: !props.attributes.turnToHero } );
                                    }
                                })
                            )
                        )
                    ) : null
                );
            };
        })
    );

    // add class name to the output block on save
    wp.hooks.addFilter(
        'blocks.getSaveContent.extraProps',
        'fct-gutenberg/img-to-bg-save',
        function (extraProps, blockType, attributes) {

            if ( !~effected_blocks.indexOf( blockType.name ) ) { return extraProps }
            if ( typeof attributes.turnToHero === 'undefined' || !attributes.turnToHero ) { return extraProps }

            extraProps.className = extraProps.className + ' fct-img-to-bg';
            return extraProps;
        }
    );

})();