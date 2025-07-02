<?php
/**
 * vcex_post_series shortcode output
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.7.1
 */

defined( 'ABSPATH' ) || exit;

$wrap_class = [
    'vcex-module',
    'vcex-post-series',
];

if ( ! empty( $atts['max_width'] ) ) {
    $wrap_class[] = vcex_parse_align_class( ! empty( $atts['align'] ) ? $atts['align'] : 'center' );
}

$wrap_class = vcex_parse_shortcode_classes( $wrap_class, 'vcex_post_series', $atts );

$output = '<div class="' . esc_attr( $wrap_class ) . '">';
    if ( function_exists( 'wpex_get_template_part' ) ) {
        ob_start();
            wpex_get_template_part( 'post_series' );
        $output .= ob_get_clean();
    }
$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;
