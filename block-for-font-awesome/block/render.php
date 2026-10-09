<?php
/**
 * Server render for getbutterfly/font-awesome.
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$gbfa_extra = trim( ( $attributes['fixedWidth'] ? 'fa-fw ' : '' ) . sanitize_html_class( $attributes['faSize'] ) );
$gbfa_label = sanitize_text_field( $attributes['label'] );
$gbfa_icon  = '';

if ( $attributes['icon'] !== '' ) {
    $gbfa_icon = getbutterfly_fa_svg( $attributes['icon'], $gbfa_extra, $gbfa_label );
} elseif ( $attributes['faClass'] !== '' ) {
    $gbfa_parsed = getbutterfly_fa_parse_class( $attributes['faClass'] );
    $gbfa_icon   = $gbfa_parsed
        ? getbutterfly_fa_svg( $gbfa_parsed['icon'], trim( $gbfa_parsed['class'] . ' ' . $gbfa_extra ), $gbfa_label )
        : '<i class="' . esc_attr( trim( $attributes['faClass'] . ' ' . $gbfa_extra ) ) . '"' . ( $gbfa_label !== '' ? ' role="img" aria-label="' . esc_attr( $gbfa_label ) . '"' : ' aria-hidden="true"' ) . '></i>';
}

if ( $gbfa_icon === '' ) {
    return;
}

$gbfa_style = '';

// Legacy colour picker value; the Color panel (block supports) takes precedence.
if ( $attributes['faColor'] !== '' && empty( $attributes['textColor'] ) && empty( $attributes['style']['color']['text'] ) && sanitize_hex_color( $attributes['faColor'] ) ) {
    $gbfa_style = 'color:' . sanitize_hex_color( $attributes['faColor'] ) . ';';
}

$gbfa_link = esc_url( $attributes['faLink'] );

if ( $gbfa_link !== '' ) {
    $gbfa_icon = '<a href="' . $gbfa_link . '"' . ( $attributes['newTab'] ? ' target="_blank" rel="noopener"' : '' ) . '>' . $gbfa_icon . '</a>';
}

printf(
    '<div %s>%s</div>',
    get_block_wrapper_attributes(
        [
            'class' => 'has-text-align-' . sanitize_html_class( $attributes['faAlign'] ),
            'style' => $gbfa_style,
        ]
    ),
    $gbfa_icon // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG from bundled files, attributes set via WP_HTML_Tag_Processor.
);
