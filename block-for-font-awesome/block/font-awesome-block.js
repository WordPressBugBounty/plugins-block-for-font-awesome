/**
 * Register editor block
 */
(function (blocks, blockEditor, components, i18n, element) {
    var el = element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var useBlockProps = blockEditor.useBlockProps;
    var InspectorControls = blockEditor.InspectorControls;

    var TextControl = components.TextControl;
    var SelectControl = components.SelectControl;
    var ServerSideRender = wp.serverSideRender;
    var ToggleControl = components.ToggleControl;
    var PanelBody = components.PanelBody;
    var ColorPalette = components.ColorPalette;
    var Fragment = element.Fragment;

    registerBlockType('getbutterfly/font-awesome', {
        apiVersion: 3,
        title: 'Font Awesome Icon',
        description: 'A single Font Awesome icon block.',
        icon: 'star-filled',
        category: 'getbutterfly',
        keywords: [
            i18n.__('fa'),
            i18n.__('font'),
            i18n.__('icon'),
            i18n.__('awesome'),
            i18n.__('pictogram')
        ],

        attributes: {
            faClass: {
                type: 'string',
                default: '',
            },
            faColor: {
                type: 'string',
                default: '#000000',
            },
            fixedWidth: {
                type: 'boolean',
                default: false,
            },
            newTab: {
                type: 'boolean',
                default: false,
            },
            faLink: {
                type: 'string',
                default: '',
            },
            faAlign: {
                type: 'string',
                default: 'left',
            },
            faSize: {
                type: 'string',
                default: '',
            },
        },

        edit: function (props) {
            var attributes = props.attributes;

            var faClass = attributes.faClass;
            var faColor = attributes.faColor;
            var faLink = attributes.faLink;
            var fixedWidth = attributes.fixedWidth;
            var newTab = attributes.newTab;
            var faAlign = attributes.faAlign;
            var faSize = attributes.faSize;

            // Get block props
            var blockProps = useBlockProps();

            return el(Fragment, {},
                el(InspectorControls, { key: 'inspector' },
                    el(
                        PanelBody, {
                        title: i18n.__('Icon Settings'),
                        className: 'getbutterfly_block',
                        initialOpen: true,
                    },
                        el(TextControl, {
                            type: 'text',
                            label: i18n.__('Icon class'),
                            placeholder: i18n.__('fa-solid fa-wand-magic-sparkles'),
                            help: i18n.__('Font Awesome class, including fixed width or animations. Custom classes are also allowed.'),
                            value: faClass,
                            onChange: function (new_faClass) {
                                props.setAttributes({ faClass: new_faClass });
                            },
                        }),
                        el(TextControl, {
                            type: 'url',
                            label: i18n.__('Icon URL'),
                            placeholder: 'https://',
                            help: i18n.__('Link to an internal page or an external resource.'),
                            value: faLink,
                            onChange: function (new_faLink) {
                                props.setAttributes({ faLink: new_faLink });
                            },
                        }),
                        el(ToggleControl, {
                            label: i18n.__('Fixed width (add the fa-fw class)'),
                            checked: !!fixedWidth,
                            onChange: function (new_fixedWidth) {
                                props.setAttributes({ fixedWidth: new_fixedWidth });
                            },
                        }),
                        el(ToggleControl, {
                            label: i18n.__('Open link in new tab'),
                            checked: !!newTab,
                            onChange: function (new_newTab) {
                                props.setAttributes({ newTab: new_newTab });
                            },
                        }),
                        el(SelectControl, {
                            label: i18n.__('Icon alignment'),
                            value: faAlign,
                            options: [
                                { label: i18n.__('Left'), value: 'left' },
                                { label: i18n.__('Center'), value: 'center' },
                                { label: i18n.__('Right'), value: 'right' },
                            ],
                            onChange: function (new_faAlign) {
                                props.setAttributes({ faAlign: new_faAlign });
                            },
                        }),
                        el(SelectControl, {
                            label: i18n.__('Icon Size'),
                            value: faSize,
                            options: [
                                { label: i18n.__('Default'), value: '' },
                                { label: i18n.__('1x'), value: 'fa-1x' },
                                { label: i18n.__('2x'), value: 'fa-2x' },
                                { label: i18n.__('3x'), value: 'fa-3x' },
                                { label: i18n.__('4x'), value: 'fa-4x' },
                                { label: i18n.__('5x'), value: 'fa-5x' },
                                { label: i18n.__('6x'), value: 'fa-6x' },
                                { label: i18n.__('7x'), value: 'fa-7x' },
                                { label: i18n.__('8x'), value: 'fa-8x' },
                                { label: i18n.__('9x'), value: 'fa-9x' },
                                { label: i18n.__('10x'), value: 'fa-10x' },
                            ],
                            onChange: function (new_faSize) {
                                props.setAttributes({ faSize: new_faSize });
                            },
                        }),
                        el('div', { className: 'components-base-control' },
                            el('p', { className: 'components-base-control__label' }, i18n.__('Icon Color')),
                            el(ColorPalette, {
                                value: faColor,
                                onChange: function (new_faColor) {
                                    props.setAttributes({ faColor: new_faColor });
                                },
                            }),
                        ),
                    ),
                ),
                el('div', blockProps,
                    el(ServerSideRender, {
                        block: 'getbutterfly/font-awesome',
                        attributes: props.attributes
                    })
                )
            );
        },

        save: function () {
            return null;
        },
    });
})(
    window.wp.blocks,
    window.wp.blockEditor,
    window.wp.components,
    window.wp.i18n,
    window.wp.element,
);
