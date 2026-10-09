const { registerBlockType, createBlock } = wp.blocks;
const { useBlockProps, InspectorControls, BlockControls } = wp.blockEditor;
const { PanelBody, TextControl, SelectControl, ToggleControl, Modal, SearchControl, Button, ToolbarGroup, ToolbarButton, Placeholder, Spinner } = wp.components;
const { createElement: el, Fragment, useState, useEffect, useMemo } = wp.element;
const { __, sprintf } = wp.i18n;
const ServerSideRender = wp.serverSideRender;

// Plugin root URL, resolved from this script's own location (block/edit.js).
const base = new URL('../', document.currentScript.src).href;
const styleNames = { s: 'solid', r: 'regular', b: 'brands' };
const maxResults = 300;

let indexPromise;
const loadIndex = () => (indexPromise ??= fetch(`${base}assets/icons.json`).then((response) => response.json()));

const spriteCache = new Map();
const loadSprite = (style) => {
    if (!spriteCache.has(style)) {
        spriteCache.set(style, fetch(`${base}assets/sprites/${style}.svg`)
            .then((response) => response.text())
            .then((text) => new DOMParser().parseFromString(text, 'image/svg+xml')));
    }

    return spriteCache.get(style);
};

const loadSvg = async (icon) => {
    const [style, name] = icon.split('/');
    const symbol = (await loadSprite(style)).getElementById(name);

    return symbol ? `<svg xmlns="http://www.w3.org/2000/svg" viewBox="${symbol.getAttribute('viewBox')}">${symbol.innerHTML}</svg>` : '';
};

const spriteUse = (icon) => {
    const [style, name] = icon.split('/');

    return { __html: `<svg width="24" height="24" aria-hidden="true"><use href="${base}assets/sprites/${style}.svg#${name}"></use></svg>` };
};

const IconPreview = ({ icon, className }) => {
    const [svg, setSvg] = useState('');

    useEffect(() => {
        let live = true;

        loadSvg(icon).then((text) => {
            if (live) {
                setSvg(text.replace('<svg ', `<svg class="gbfa ${className}" aria-hidden="true" focusable="false" `));
            }
        });

        return () => {
            live = false;
        };
    }, [icon, className]);

    return el('span', { dangerouslySetInnerHTML: { __html: svg } });
};

const moveFocus = (event) => {
    const buttons = [...event.currentTarget.querySelectorAll('button')];
    const index = buttons.indexOf(event.target.closest('button'));

    if (index < 0) {
        return;
    }

    const columns = getComputedStyle(event.currentTarget).gridTemplateColumns.split(' ').length;
    const step = { ArrowRight: 1, ArrowLeft: -1, ArrowDown: columns, ArrowUp: -columns, Home: -index, End: buttons.length - 1 - index }[event.key];

    if (step === undefined) {
        return;
    }

    event.preventDefault();
    buttons[Math.min(Math.max(index + step, 0), buttons.length - 1)].focus();
};

const IconPicker = ({ onSelect, onClose }) => {
    const [icons, setIcons] = useState(null);
    const [search, setSearch] = useState('');
    const [style, setStyle] = useState('');

    useEffect(() => {
        loadIndex().then(setIcons);
    }, []);

    const results = useMemo(() => {
        const query = search.trim().toLowerCase();
        const found = [];

        for (const [name, label, styles, terms] of icons ?? []) {
            if (query && !name.includes(query) && !label.toLowerCase().includes(query) && !terms.includes(query)) {
                continue;
            }

            for (const code of styles) {
                if (!style || style === styleNames[code]) {
                    found.push({ icon: `${styleNames[code]}/${name}`, label: `${label} (${styleNames[code]})` });
                }
            }
        }

        return found;
    }, [icons, search, style]);

    return el(
        Modal,
        { title: __('Choose an icon', 'block-for-font-awesome'), onRequestClose: onClose, size: 'large' },
        el(
            'div',
            { style: { display: 'flex', gap: 16, alignItems: 'end', marginBlockEnd: 16 } },
            el('div', { style: { flex: 1 } }, el(SearchControl, { __nextHasNoMarginBottom: true, value: search, onChange: setSearch, label: __('Search icons', 'block-for-font-awesome'), placeholder: __('Search, e.g. phone, house, arrow', 'block-for-font-awesome') })),
            el(SelectControl, {
                __nextHasNoMarginBottom: true,
                __next40pxDefaultSize: true,
                label: __('Style', 'block-for-font-awesome'),
                value: style,
                onChange: setStyle,
                options: [
                    { label: __('All styles', 'block-for-font-awesome'), value: '' },
                    { label: __('Solid', 'block-for-font-awesome'), value: 'solid' },
                    { label: __('Regular', 'block-for-font-awesome'), value: 'regular' },
                    { label: __('Brands', 'block-for-font-awesome'), value: 'brands' },
                ],
            })
        ),
        !icons
            ? el(Spinner)
            : el(
                Fragment,
                null,
                el('p', { role: 'status', style: { marginBlock: '0 8px' } }, results.length > maxResults
                    ? sprintf(__('Showing %1$d of %2$d icons. Refine your search to see more.', 'block-for-font-awesome'), maxResults, results.length)
                    : sprintf(__('%d icons', 'block-for-font-awesome'), results.length)),
                el(
                    'div',
                    {
                        role: 'group',
                        'aria-label': __('Icons', 'block-for-font-awesome'),
                        onKeyDown: moveFocus,
                        style: { display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(56px, 1fr))', gap: 4 },
                    },
                    results.slice(0, maxResults).map(({ icon, label }) => el(
                        Button,
                        {
                            key: icon,
                            label,
                            showTooltip: true,
                            onClick: () => onSelect(icon),
                            style: { height: 56, justifyContent: 'center' },
                        },
                        el('span', { dangerouslySetInnerHTML: spriteUse(icon) })
                    ))
                )
            )
    );
};

registerBlockType('getbutterfly/font-awesome', {
    edit({ attributes, setAttributes }) {
        const [isPicking, setPicking] = useState(false);
        const legacyColor = attributes.faColor && !attributes.textColor && !attributes.style?.color?.text;
        const blockProps = useBlockProps({
            className: `has-text-align-${attributes.faAlign}`,
            style: legacyColor ? { color: attributes.faColor } : undefined,
        });
        const extra = [attributes.fixedWidth && 'fa-fw', attributes.faSize].filter(Boolean).join(' ');
        const choose = (icon) => {
            setAttributes({ icon, faClass: '' });
            setPicking(false);
        };

        let preview;

        if (attributes.icon) {
            preview = el(IconPreview, { icon: attributes.icon, className: extra });
        } else if (attributes.faClass) {
            preview = el(ServerSideRender, { block: 'getbutterfly/font-awesome', attributes, skipBlockSupportAttributes: true });
        } else {
            preview = el(
                Placeholder,
                { icon: 'star-filled', label: __('Font Awesome Icon', 'block-for-font-awesome'), instructions: __('Search 2,000+ free icons. No class names needed.', 'block-for-font-awesome') },
                el(Button, { variant: 'primary', onClick: () => setPicking(true) }, __('Choose icon', 'block-for-font-awesome'))
            );
        }

        return el(
            Fragment,
            null,
            el(
                BlockControls,
                null,
                el(ToolbarGroup, null, el(ToolbarButton, { icon: 'search', label: __('Choose icon', 'block-for-font-awesome'), onClick: () => setPicking(true) }))
            ),
            el(
                InspectorControls,
                null,
                el(
                    PanelBody,
                    { title: __('Icon', 'block-for-font-awesome'), initialOpen: true },
                    el('p', null, attributes.icon || __('No icon selected.', 'block-for-font-awesome')),
                    el(Button, { variant: 'secondary', onClick: () => setPicking(true), style: { marginBlockEnd: 16 } }, attributes.icon ? __('Replace icon', 'block-for-font-awesome') : __('Choose icon', 'block-for-font-awesome')),
                    el(TextControl, {
                        __nextHasNoMarginBottom: true,
                        __next40pxDefaultSize: true,
                        label: __('Accessible label', 'block-for-font-awesome'),
                        help: __('Describe what the icon means, e.g. "Call us". Leave empty if the icon is decorative.', 'block-for-font-awesome'),
                        value: attributes.label,
                        onChange: (label) => setAttributes({ label }),
                    }),
                    el(TextControl, {
                        __nextHasNoMarginBottom: true,
                        __next40pxDefaultSize: true,
                        type: 'url',
                        label: __('Link', 'block-for-font-awesome'),
                        placeholder: 'https://',
                        value: attributes.faLink,
                        onChange: (faLink) => setAttributes({ faLink }),
                    }),
                    el(ToggleControl, {
                        __nextHasNoMarginBottom: true,
                        label: __('Open link in new tab', 'block-for-font-awesome'),
                        checked: attributes.newTab,
                        onChange: (newTab) => setAttributes({ newTab }),
                    }),
                    el(ToggleControl, {
                        __nextHasNoMarginBottom: true,
                        label: __('Fixed width', 'block-for-font-awesome'),
                        checked: attributes.fixedWidth,
                        onChange: (fixedWidth) => setAttributes({ fixedWidth }),
                    }),
                    el(SelectControl, {
                        __nextHasNoMarginBottom: true,
                        __next40pxDefaultSize: true,
                        label: __('Alignment', 'block-for-font-awesome'),
                        value: attributes.faAlign,
                        options: [
                            { label: __('Left', 'block-for-font-awesome'), value: 'left' },
                            { label: __('Center', 'block-for-font-awesome'), value: 'center' },
                            { label: __('Right', 'block-for-font-awesome'), value: 'right' },
                        ],
                        onChange: (faAlign) => setAttributes({ faAlign }),
                    }),
                    el(SelectControl, {
                        __nextHasNoMarginBottom: true,
                        __next40pxDefaultSize: true,
                        label: __('Size', 'block-for-font-awesome'),
                        value: attributes.faSize,
                        options: [{ label: __('Default', 'block-for-font-awesome'), value: '' }, ...Array.from({ length: 10 }, (_, i) => ({ label: `${i + 1}x`, value: `fa-${i + 1}x` }))],
                        onChange: (faSize) => setAttributes({ faSize }),
                    }),
                    attributes.faColor && el(Button, { variant: 'link', onClick: () => setAttributes({ faColor: '' }) }, __('Remove legacy icon colour (use the Color panel instead)', 'block-for-font-awesome'))
                ),
                el(
                    PanelBody,
                    { title: __('Pro icons and kits', 'block-for-font-awesome'), initialOpen: !attributes.icon && !!attributes.faClass },
                    el(TextControl, {
                        __nextHasNoMarginBottom: true,
                        __next40pxDefaultSize: true,
                        label: __('Icon class', 'block-for-font-awesome'),
                        placeholder: 'fa-duotone fa-solid fa-house',
                        help: __('For Font Awesome Pro or kit icons, which need the Font Awesome script from the plugin settings. Free icons are output as SVG automatically.', 'block-for-font-awesome'),
                        value: attributes.faClass,
                        onChange: (faClass) => setAttributes({ faClass, icon: '' }),
                    })
                )
            ),
            el('div', blockProps, preview),
            isPicking && el(IconPicker, { onSelect: choose, onClose: () => setPicking(false) })
        );
    },

    save: () => null,

    transforms: {
        to: [
            {
                type: 'block',
                blocks: ['core/icon'],
                isMatch: ({ icon }) => !!icon,
                transform: ({ icon, label }) => createBlock('core/icon', { icon: `font-awesome/${icon.replace('/', '-')}`, ariaLabel: label || undefined }),
            },
        ],
        from: [
            {
                type: 'block',
                blocks: ['core/icon'],
                isMatch: ({ icon }) => /^font-awesome\/(solid|regular|brands)-/.test(icon ?? ''),
                transform: ({ icon, ariaLabel }) => createBlock('getbutterfly/font-awesome', {
                    icon: icon.replace(/^font-awesome\/(solid|regular|brands)-/, '$1/'),
                    label: ariaLabel ?? '',
                }),
            },
        ],
    },
});
