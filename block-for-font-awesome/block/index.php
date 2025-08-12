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
 * Enqueue block assets
 *
 * Enqueues block Javascript and dependencies

 * @return null
 */
function getbutterfly_fa_block_enqueue() {
    wp_enqueue_script(
        'getbutterfly-fa-block-script',
        plugins_url( 'font-awesome-block.js', __FILE__ ),
        [
            'wp-blocks',
            'wp-element',
            'wp-i18n',
            'wp-editor',
            'wp-components',
        ],
        GBFA_PLUGIN_VERSION,
        true
    );
}



/**
 * Render icon on both frontend and backend
 *
 * Supports:
 * - [fa class="fas fa-phone"] (classic)
 * - [icon name="phone-volume" prefix="fas"] → class="fas-phone-volume" (requested behavior)
 *
 * @param  array  $atts    Shortcode attributes
 * @param  string $content Shortcode content (unused)
 * @param  string $tag     Shortcode tag name ('fa' or 'icon')
 * @return string          Icon element
 */
function getbutterfly_fa_block_render( $atts, $content = '', $tag = '' ) {
    // Accept both classic and icon-style attributes
    $attributes = shortcode_atts(
        [
            'class'  => '',
            'name'   => '', // for [icon]
            'prefix' => '', // for [icon]
        ],
        $atts,
        $tag
    );

    // Initialize to avoid notices and allow fallbacks
    $class = '';

    if ( $tag === 'icon' ) {
        // Build class as prefix-dash-name when using [icon]
        $prefix = sanitize_html_class( trim( (string) $attributes['prefix'] ) );
        $name   = sanitize_html_class( trim( (string) $attributes['name'] ) );

        // If prefix is fas, then changed it to fa and add fa-solid
        $prefix = ( $prefix === 'fas' ) ? 'fa-solid fa' : $prefix;

        if ( $prefix !== '' && $name !== '' ) {
            // Single class per requirement (e.g., "fas-phone-volume")
            $class = $prefix . '-' . $name;
        }

        // If class is still empty and a plain class was provided, fallback to it
        if ( $class === '' && $attributes['class'] !== '' ) {
            $class = esc_attr( $attributes['class'] );
        }
    } else {
        // Default/legacy behavior for [fa] and others
        $class = esc_attr( $attributes['class'] );
    }

    return '<i class="' . $class . '"></i>';
}



/**
 * Initialize block
 *
 * @return function Block registration
 */
function getbutterfly_fa_block_init() {
    function getbutterfly_fa_render( $attributes, $content ) {
        $class = esc_attr( $attributes['faClass'] );
        $color = esc_attr( $attributes['faColor'] );

        $align = esc_attr( $attributes['faAlign'] );
        $align = ' has-text-align-' . $align;

        $link = sanitize_url( $attributes['faLink'] );
        $link = filter_var( $link, FILTER_SANITIZE_URL );

        // Fixed width
        $fixed_width = ( 1 !== (int) esc_attr( $attributes['fixedWidth'] ) ) ? '' : ' fa-fw';

        // Icon size
        $fa_size = ! empty( $attributes['faSize'] ) ? ' ' . esc_attr( $attributes['faSize'] ) : '';

        // New tab
        $new_tab = ( 1 !== (int) esc_attr( $attributes['newTab'] ) ) ? '' : 'target="_blank" rel="external noopener"';

        if ( ! empty( $link ) ) {
            return '<div class="' . $align . '">
                <a href="' . $link . '" ' . $new_tab . '><i class="' . $class . $fixed_width . $fa_size . '" style="color: ' . $color . ';"></i></a>
            </div>';
        }

        return '<div class="' . $align . '"><i class="' . $class . $fixed_width . $fa_size . '" style="color: ' . $color . ';"></i></div>';
    }

    register_block_type(
        'getbutterfly/font-awesome',
        [
            'render_callback' => 'getbutterfly_fa_render',
            'attributes'      => [
                'faClass'    => [
                    'type'    => 'string',
                    'default' => '',
                ],
                'faColor'    => [
                    'type'    => 'string',
                    'default' => '#000000',
                ],
                'fixedWidth' => [
                    'type'    => 'boolean',
                    'default' => false,
                ],
                'newTab'     => [
                    'type'    => 'boolean',
                    'default' => false,
                ],
                'faLink'     => [
                    'type'    => 'string',
                    'default' => '',
                ],
                'faAlign'    => [
                    'type'    => 'string',
                    'default' => 'left',
                ],
                'faSize'     => [
                    'type'    => 'string',
                    'default' => '',
                ],
            ],
        ]
    );
}
