<?php

/**
 * vcex_term_description shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.8
 */

defined( 'ABSPATH' ) || exit;

if ( vcex_vc_is_inline() ) {
	$term_description = esc_html( 'Term description placeholder for the live builder.', 'total-theme-core' );
} else {
	$term_description = term_description();
}

if ( empty( $term_description ) ) {
	return;
}

// Define output.
$output = '';

// Default shortcode classes.
$shortcode_class = [
	'vcex-term-description',
	'vcex-module',
	'wpex-last-mb-0',
];

$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_term_description' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_term_description', $atts );

$output .= '<div class="' . esc_attr( trim( $shortcode_class ) ) . '">';

	$output .= do_shortcode( wp_kses_post( $term_description ) );

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;
