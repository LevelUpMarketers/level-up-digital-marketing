<?php

namespace TotalTheme\Header\Flex;

\defined( 'ABSPATH' ) || exit;

/**
 * Flex Header Aside.
 */
class Aside {

	/**
	 * Aside content.
	 */
	private static $content = null;

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Returns the wrapper class.
	 */
	public static function wrapper_class(): void {
		$header_style = totaltheme_call_static( 'Header\Core', 'style' );

		$class = [
			'wpex-flex',
			'wpex-items-center',
			'wpex-justify-end',
			'wpex-h-100',
		];

		if ( $header_style ) {
			$class[] = "header-{$header_style}-aside";
		}

		if ( $visibility = \get_theme_mod( 'header_flex_aside_visibility' ) ) {
			$class[] = \totaltheme_get_visibility_class( $visibility );
		}

		$class = \apply_filters( 'wpex_header_flex_aside_class', $class ); // @deprecated
		$class = (array) \apply_filters( 'totaltheme/header/flex/aside/wrapper_class', $class );

		if ( $class ) {
			echo 'class="' . \esc_attr( \implode( ' ', (array) $class ) ) . '"';
		}
	}

	/**
	 * Return header aside content.
	 */
	public static function get_content(): string {
		if ( null === self::$content ) {
			$content = \wpex_get_translated_theme_mod( 'header_flex_aside_content' );
			$content = \apply_filters( 'wpex_header_flex_aside_content', $content ); // @deprecated
			$content = (string) \apply_filters( 'totaltheme/header/flex/aside/content', $content );
			self::$content = $content;
		}
		return self::$content;
	}

	/**
	 * Render the header aside.
	 */
	public static function render(): void {
		$content = self::get_content();
		self::$content = null; // free up memory
		if ( ! $content ) {
			return;
		}
		$content_safe = do_shortcode( wp_kses_post( do_blocks( totaltheme_replace_vars( $content ) ) ) );
		if ( $content_safe ) {
			?>
			<div id="site-header-flex-aside" <?php self::wrapper_class(); ?>><div id="site-header-flex-aside-inner" class="header-aside-content wpex-flex wpex-flex-wrap wpex-items-center"><?php echo $content_safe; ?></div></div>
			<?php
		}
	}

	/**
	 * Checks if the header aside content has a search icon.
	 */
	public static function has_search_icon(): bool {
		return self::get_content() && str_contains( self::get_content(), '[header_search_icon' );
	}

	/**
	 * Checks if the header aside content has a cart icon.
	 */
	public static function has_cart_icon(): bool {
		return self::get_content() && str_contains( self::get_content(), '[header_cart_icon' );
	}

}
