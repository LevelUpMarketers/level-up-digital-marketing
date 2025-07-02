<?php

namespace TotalTheme\Footer\Bottom;

\defined( 'ABSPATH' ) || exit;

/**
 * Footer Menu.
 */
class Menu {

	/**
	 * Returns theme location for the footer bottom menu.
	 */
	public static function get_theme_location(): string {
		$location = 'footer_menu';
		$location = \apply_filters( 'wpex_footer_menu_location', $location ); // @deprecated
		return (string) \apply_filters( 'totaltheme/footer/bottom/menu/theme_location', $location );
	}

	/**
	 * Output wrapper class.
	 */
	public static function wrapper_class(): void {
		$classes = [
			'wpex-mt-10',
		];
		$align = \get_theme_mod( 'bottom_footer_text_align' );
		if ( ! $align || ! \in_array( $align, [ 'left', 'center', 'right' ] ) ) {
			$classes[] = 'wpex-md-mt-0';
		}
		$classes = \apply_filters( 'wpex_footer_bottom_menu_class', $classes ); // @deprecated
		$classes = (array) \apply_filters( 'totaltheme/footer/bottom/menu/wrapper_class', $classes );
		if ( $classes ) {
			echo 'class="' . \esc_attr( \implode( ' ', \array_unique( $classes ) ) ) . '"';
		}
	}

}
