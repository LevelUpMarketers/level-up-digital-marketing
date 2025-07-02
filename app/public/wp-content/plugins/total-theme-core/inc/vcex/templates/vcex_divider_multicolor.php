<?php
/**
 * vcex_divider_multicolor shortcode output
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.7.1
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $atts['colors'] ) ) {
	return;
}

$colors = (array) vcex_vc_param_group_parse_atts( $atts['colors'] );

if ( ! $colors ) {
	return;
}

$count = count( $colors );

$wrap_classes = [
	'vcex-divider-multicolor',
	'vcex-module',
	'wpex-flex',
	'wpex-w-100',
];

if ( ! empty( $atts['bottom_margin'] ) ) {
	$wrap_classes[] = vcex_parse_margin_class( $atts['bottom_margin'], 'bottom' );
}

if ( ! empty( $atts['el_class'] ) ) {
	$wrap_classes[] = vcex_get_extra_class( $atts['el_class'] );
}

if ( ! empty( $atts['visibility'] ) ) {
	$wrap_classes[] = vcex_parse_visibility_class( $atts['visibility'] );
}

if ( ! empty( $atts['align'] ) && ( 'left' === $atts['align'] || 'right' === $atts['align'] ) ) {
	$wrap_classes[] = vcex_parse_align_class( $atts['align'] );
} else {
	$wrap_classes[] = 'wpex-m-auto';
}

$wrap_classes = vcex_parse_shortcode_classes( $wrap_classes, 'vcex_divider_multicolor', $atts );

// Output.
$output = '<div class="' . esc_attr( $wrap_classes ) . '">';

	foreach ( $colors as $color ) {

		$inline_style = vcex_inline_style( array(
			'background' => $color['value'] ?? '',
		), true );

		$output .= '<span class="vcex-divider-multicolor__item wpex-flex-grow"' . $inline_style . '></span>';

	}

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;
