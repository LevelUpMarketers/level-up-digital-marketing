<?php

namespace TotalTheme\Footer\Bottom;

\defined( 'ABSPATH' ) || exit;

/**
 * Footer Bottom.
 */
class Core {

	/**
	 * Header is enabled or not.
	 */
	protected static $is_enabled;

	/**
	 * Checks if the header is enabled or not.
	 */
	public static function is_enabled(): bool {
		if ( ! \is_null( self::$is_enabled ) ) {
			return self::$is_enabled;
		}

		$post_id = \wpex_get_current_post_id();

		if ( \totaltheme_call_static( 'Integration\Elementor', 'location_exists', 'footer_bottom' ) ) {
			$check = true;
		} elseif ( totaltheme_call_static( 'Footer\Core', 'is_custom' ) ) {
			// @todo rename to be same as default.
			$check = \get_theme_mod( 'footer_builder_footer_bottom', false );
		} else {
			$check = \get_theme_mod( 'footer_bottom', true );
		}

		if ( $post_id ) {
			$meta = \get_post_meta( $post_id, 'wpex_footer_bottom', true );
			if ( 'on' === $meta ) {
				$check = true;
			} elseif ( 'off' === $meta ) {
				$check = false;
			}
		}

		$check = \apply_filters( 'wpex_has_footer_bottom', $check, $post_id ); // @deprecated

		self::$is_enabled = (bool)\apply_filters( 'totaltheme/footer/bottom/is_enabled', $check, $post_id );

		return self::$is_enabled;
	}

	/**
	 * Output wrapper class.
	 */
	public static function wrapper_class(): void {
		$class = [
			'wpex-py-20',
		];

		if ( totaltheme_has_classic_styles() ) {
			$class[] = 'wpex-text-sm';
		}

		if ( \get_theme_mod( 'footer_bottom_dark_surface', true ) ) {
			$class[] = 'wpex-surface-dark';
			$class[] = 'wpex-bg-gray-900';
		}

		$align = \get_theme_mod( 'bottom_footer_text_align' );

		if ( $align && \in_array( $align, [ 'left', 'center', 'right' ], true ) ) {
			$class[] = "wpex-text-{$align}";
		} else {
			$class[] = 'wpex-text-center wpex-md-text-left';
		}

		$class[] = 'wpex-print-hidden';

		$class = \apply_filters( 'wpex_footer_bottom_classes', $class ); // @deprecated
		$class = (array) \apply_filters( 'totaltheme/footer/bottom/wrapper_class', $class );

		if ( $class ) {
			echo 'class="' . \esc_attr( \implode( ' ', $class ) ) . '"';
		}
	}

	/**
	 * Output inner class.
	 */
	public static function inner_class(): void {
		$class = [
			'container',
		];
		$class = \apply_filters( 'wpex_footer_bottom_inner_class', $class ); // @deprecated
		$class = (array) \apply_filters( 'totaltheme/footer/bottom/inner_class', $class );
		if ( $class ) {
			echo 'class="' . \esc_attr( \implode( ' ', $class ) ) . '"';
		}
	}

}
