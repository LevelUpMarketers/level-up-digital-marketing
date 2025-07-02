<?php

defined( 'ABSPATH' ) || exit;

/**
 * Get shortcode attributes.
 */
function vcex_shortcode_atts( $shortcode = '', $atts = '', $class = null ) {
	// Parse deprecated attributes (must run first).
	if ( $class && is_callable( array( $class, 'parse_deprecated_attributes' ) ) ) {
		$atts = $class::parse_deprecated_attributes( $atts );
	}

	// Fix inline shortcodes - @see WPBakeryShortCode => prepareAtts().
	if ( is_array( $atts ) ) {
		foreach ( $atts as $key => $val ) {
			if ( ! is_string( $val ) ) {
				continue;
			}
			$atts[ $key ] = str_replace( [
				'`{`',
				'`}`',
				'``',
			], [
				'[',
				']',
				'"',
			], $val );
		}
	}

	// Check attribute before parsince.
	$is_elementor_widget = isset( $atts['is_elementor_widget'] ) ? $atts['is_elementor_widget'] : false;

	if ( $is_elementor_widget && isset( $atts['__globals__'] ) && is_array( $atts['__globals__'] ) ) {
		$elementor_globals = $atts['__globals__'];
	}

	// Parse shortcodes.
	if ( function_exists( 'vc_map_get_attributes' ) ) {
		$atts = vc_map_get_attributes( $shortcode, $atts ); // !important!! must use WPBakery function to support vc_add_param
	} else {
		$atts = shortcode_atts( vcex_shortcode_class_attrs( $class ), $atts, $shortcode );
		$atts = (array) apply_filters( 'vc_map_get_attributes', $atts, $shortcode );
	}

	// Add elementor globals.
	if ( isset( $elementor_globals ) ) {
		foreach ( $elementor_globals as $eg_k => $eg_v ) {
			if ( isset( $atts[ $eg_k ] ) && ! $atts[ $eg_k ] && str_starts_with( $eg_v, 'globals/colors' ) ) {
				$atts[ $eg_k ] = vcex_get_elementor_global_color( str_replace( 'globals/colors?id=', '', $eg_v ) );
			}
		}
	}

	// Add attributes after parsing.
	$atts['is_elementor_widget'] = $is_elementor_widget;

	return (array) apply_filters( 'vcex_shortcode_atts', $atts, $shortcode );
}

/**
 * Returns all shortcode atts and default values.
 */
function vcex_shortcode_class_attrs( $class ) {
	$atts = [];

	if ( is_callable( [ $class, 'get_params' ] ) ) {
		$params = $class::get_params();
	} elseif ( is_object( $class ) && is_callable( [ $class, 'map' ] ) ) {
		$map = $class->map();
		$params = $map['params'] ?? null;
	}

	if ( isset( $params ) && is_array( $params ) ) {
		foreach ( $params as $param ) {
			if ( isset( $param['param_name'] ) && 'content' !== $param['param_name'] ) {
				$value = '';
				if ( isset( $param['std'] ) ) {
					$value = $param['std'];
				} elseif ( isset( $param['value'] ) ) {
					if ( is_array( $param['value'] ) ) {
						$value = current( $param['value'] );
						if ( is_array( $value ) ) {
							// in case if two-dimensional array provided (vc_basic_grid)
							$value = current( $value );
						}
						// return first value from array (by default)
					} else {
						$value = $param['value'];
					}
				}
				if ( function_exists( 'vc_map_get_attributes' ) ) {
					$atts[ $param['param_name'] ] = apply_filters( 'vc_map_get_param_defaults', $value, $param );
				} else {
					$atts[ $param['param_name'] ] = $value;
				}
			}
		}
	}

	return $atts;
}

/**
 * Helper function returns a shortcode attribute with a fallback.
 */
function vcex_shortcode_att( $atts, $att, $default = '' ) {
	return $atts[ $att ] ?? $default;
}
