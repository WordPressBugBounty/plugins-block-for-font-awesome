<?php
// Prevent direct access to file
defined( 'ABSPATH' ) || die();

/**
 * Create new block category
 *
 * Creates a new block category with a specific name for future additional blocks
 *
 * @param  array  $categories Current block categories
 * @param  object $post       Post object
 * @return array
 */
function getbutterfly_block_categories( $categories, $post ) {
    return array_merge(
        $categories,
        [
            [
                'slug'  => 'getbutterfly',
                'title' => 'getButterfly',
                'icon'  => 'star-filled',
            ],
        ]
    );
}



/**
 * Look up a Font Awesome Free icon ("solid/house") in the bundled sprite for its style.
 *
 * @param  string $icon Style and name, e.g. "solid/house".
 * @return array{0: string, 1: string}|null [ viewBox, inner SVG ], or null if the icon isn't bundled.
 */
function getbutterfly_fa_symbol( $icon ) {
    static $sprites = [];

    if ( ! preg_match( '~^(solid|regular|brands)/([a-z0-9-]+)$~', (string) $icon, $match ) ) {
        return null;
    }

    $sprites[ $match[1] ] ??= (string) file_get_contents( dirname( __DIR__ ) . '/assets/sprites/' . $match[1] . '.svg' );

    // A plain strpos on the sprite is much cheaper than parsing every symbol.
    $start = strpos( $sprites[ $match[1] ], '<symbol id="' . $match[2] . '"' );

    if ( $start === false ) {
        return null;
    }

    $end    = strpos( $sprites[ $match[1] ], '</symbol>', $start );
    $symbol = substr( $sprites[ $match[1] ], $start, $end - $start );

    if ( ! preg_match( '~viewBox="([^"]+)">(.*)$~s', $symbol, $parts ) ) {
        return null;
    }

    return [ $parts[1], trim( $parts[2] ) ];
}

/**
 * Inline SVG markup for a bundled icon.
 *
 * @param  string $icon  Style and name, e.g. "solid/house".
 * @param  string $class Extra classes for the <svg>.
 * @param  string $label Accessible label; empty means decorative.
 * @return string
 */
function getbutterfly_fa_svg( $icon, $class = '', $label = '' ) {
    $symbol = getbutterfly_fa_symbol( $icon );

    if ( ! $symbol ) {
        return '';
    }

    $a11y = $label !== '' ? ' role="img" aria-label="' . esc_attr( $label ) . '"' : ' aria-hidden="true"';

    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="' . esc_attr( $symbol[0] ) . '" class="' . esc_attr( trim( 'gbfa ' . $class ) ) . '" focusable="false"' . $a11y . '>' . $symbol[1] . '</svg>';
}

/**
 * Map a Font Awesome class string ("fa-solid fa-house fa-fw") to a bundled icon.
 *
 * @param  string $class Class attribute value.
 * @return array{icon: string, class: string}|null Null when the icon is not a bundled Free icon (Pro, kits, custom).
 */
function getbutterfly_fa_parse_class( $class ) {
    $styles = [
        'fa-solid'   => 'solid',
        'fas'        => 'solid',
        'fa-regular' => 'regular',
        'far'        => 'regular',
        'fa-brands'  => 'brands',
        'fab'        => 'brands',
    ];
    $tokens = preg_split( '/\s+/', trim( (string) $class ), -1, PREG_SPLIT_NO_EMPTY );
    $style  = '';
    $icon   = '';
    $extra  = [];

    // Pro families and kits need the Font Awesome script, so keep them as <i>.
    if ( array_intersect( $tokens, [ 'fa-duotone', 'fa-light', 'fa-thin', 'fa-sharp', 'fa-sharp-duotone', 'fa-semibold', 'fad', 'fal', 'fat', 'fass', 'fak', 'fa-kit' ] ) ) {
        return null;
    }

    foreach ( $tokens as $token ) {
        if ( isset( $styles[ $token ] ) ) {
            $style = $styles[ $token ];
        } elseif ( $token === 'fa' ) {
            $style = $style ?: 'solid';
        } elseif ( $icon === '' && str_starts_with( $token, 'fa-' ) && ( getbutterfly_fa_symbol( 'solid/' . substr( $token, 3 ) ) || getbutterfly_fa_symbol( 'regular/' . substr( $token, 3 ) ) || getbutterfly_fa_symbol( 'brands/' . substr( $token, 3 ) ) ) ) {
            $icon = substr( $token, 3 );
        } else {
            $extra[] = $token;
        }
    }

    if ( $style === '' || $icon === '' || ! getbutterfly_fa_symbol( $style . '/' . $icon ) ) {
        return null;
    }

    return [
        'icon'  => $style . '/' . $icon,
        'class' => implode( ' ', array_map( 'sanitize_html_class', $extra ) ),
    ];
}

/**
 * Render icon shortcodes
 *
 * Supports:
 * - [fa class="fas fa-phone"] (classic)
 * - [icon name="phone-volume" prefix="fas"]
 *
 * Free icons are output as inline SVG; anything else (Pro, kits, custom classes) stays an <i> element.
 *
 * @param  array  $atts    Shortcode attributes
 * @param  string $content Shortcode content (unused)
 * @param  string $tag     Shortcode tag name ('fa' or 'icon')
 * @return string          Icon element
 */
function getbutterfly_fa_block_render( $atts, $content = '', $tag = '' ) {
    $attributes = shortcode_atts(
        [
            'class'  => '',
            'name'   => '', // for [icon]
            'prefix' => '', // for [icon]
            'label'  => '',
        ],
        $atts,
        $tag
    );

    $class = (string) $attributes['class'];

    if ( $tag === 'icon' && $attributes['prefix'] !== '' && $attributes['name'] !== '' ) {
        $prefix = sanitize_html_class( trim( (string) $attributes['prefix'] ) );
        $name   = sanitize_html_class( trim( (string) $attributes['name'] ) );
        $class  = ( $prefix === 'fas' ? 'fa-solid' : $prefix ) . ' fa-' . $name;
    }

    $parsed = getbutterfly_fa_parse_class( $class );

    if ( $parsed ) {
        wp_enqueue_style( 'getbutterfly-font-awesome-style' );

        return getbutterfly_fa_svg( $parsed['icon'], $parsed['class'], sanitize_text_field( $attributes['label'] ) );
    }

    return '<i class="' . esc_attr( $class ) . '"></i>';
}



/**
 * Register Font Awesome icons with the core icon registry.
 *
 * @param string[]|null $only Icon names ("solid/house") to register, or null for all.
 */
function getbutterfly_fa_register_core_icons( $only = null ) {
    static $done = [];

    $styles = [
        'solid'   => __( 'Solid', 'block-for-font-awesome' ),
        'regular' => __( 'Regular', 'block-for-font-awesome' ),
        'brands'  => __( 'Brand', 'block-for-font-awesome' ),
    ];

    foreach ( require dirname( __DIR__ ) . '/assets/icons.php' as [ $icon, $label ] ) {
        if ( isset( $done[ $icon ] ) || ( $only !== null && ! in_array( $icon, $only, true ) ) ) {
            continue;
        }

        $done[ $icon ] = true;

        wp_register_icon(
            'font-awesome/' . str_replace( '/', '-', $icon ),
            [
                'label'   => $label . ' (' . $styles[ strstr( $icon, '/', true ) ] . ')',
                'content' => getbutterfly_fa_svg( $icon ),
            ]
        );
    }
}

/**
 * Icons have no individual files, so register them only when core needs them:
 * the icons REST routes (editor picker) and core/icon blocks being rendered.
 */
function getbutterfly_fa_icons_for_rest( $result, $server, $request ) {
    if ( str_starts_with( $request->get_route(), '/wp/v2/icons' ) ) {
        getbutterfly_fa_register_core_icons();
    }

    return $result;
}

function getbutterfly_fa_icon_for_block( $parsed_block ) {
    $icon = (string) ( $parsed_block['attrs']['icon'] ?? '' );

    if ( $parsed_block['blockName'] === 'core/icon' && preg_match( '~^font-awesome/(solid|regular|brands)-(.+)$~', $icon, $match ) ) {
        getbutterfly_fa_register_core_icons( [ $match[1] . '/' . $match[2] ] );
    }

    return $parsed_block;
}

/**
 * Initialize block, Core icon collection and patterns
 */
function getbutterfly_fa_block_init() {
    register_block_type( dirname( __DIR__ ) );

    if ( (int) get_option( 'fa_core_icons', 1 ) === 1 ) {
        wp_register_icon_collection(
            'font-awesome',
            [
                'label'       => __( 'Font Awesome Free', 'block-for-font-awesome' ),
                'description' => __( 'Font Awesome Free icons by Fonticons, Inc. (CC BY 4.0).', 'block-for-font-awesome' ),
            ]
        );

        add_filter( 'rest_pre_dispatch', 'getbutterfly_fa_icons_for_rest', 10, 3 );
        add_filter( 'render_block_data', 'getbutterfly_fa_icon_for_block' );
    }

    register_block_pattern(
        'getbutterfly/font-awesome-feature-list',
        [
            'title'      => __( 'Icon feature list', 'block-for-font-awesome' ),
            'categories' => [ 'features' ],
            'content'    => '<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:getbutterfly/font-awesome {"icon":"solid/bolt","faSize":"fa-2x"} /-->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Fast</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Describe the first benefit in one or two short sentences.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:getbutterfly/font-awesome {"icon":"solid/shield-halved","faSize":"fa-2x"} /-->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Secure</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Describe the second benefit in one or two short sentences.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:getbutterfly/font-awesome {"icon":"solid/heart","faSize":"fa-2x"} /-->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Loved</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Describe the third benefit in one or two short sentences.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->',
        ]
    );

    $social = '';

    foreach ( [ 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'x-twitter' => 'X', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube' ] as $network => $label ) {
        $social .= '<!-- wp:column {"width":"auto"} -->
<div class="wp-block-column" style="flex-basis:auto"><!-- wp:getbutterfly/font-awesome {"icon":"brands/' . $network . '","label":"' . $label . '","faLink":"https://","newTab":true,"faSize":"fa-2x"} /--></div>
<!-- /wp:column -->
';
    }

    register_block_pattern(
        'getbutterfly/font-awesome-social-row',
        [
            'title'      => __( 'Social icons row', 'block-for-font-awesome' ),
            'categories' => [ 'call-to-action' ],
            'content'    => '<!-- wp:columns {"isStackedOnMobile":false} -->
<div class="wp-block-columns is-not-stacked-on-mobile">' . $social . '</div>
<!-- /wp:columns -->',
        ]
    );
}
