<?php

namespace TotalThemeCore\WPBakery;

use TotalTheme\Integration\WPBakery\Slim_Mode;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery helper functions.
 */
class Helpers {

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Check if currently editing the page using the vc frontend editor.
	 *
	 * @note checking is_admin() doesn't work.
	 */
	public static function is_frontend_edit_mode(): bool {
		return \function_exists( 'vc_is_inline' ) && \vc_is_inline();
	}

	/**
	 * Check if currently editing a specific post type in front-end mode.
	 */
	public static function is_cpt_in_frontend_mode( string $post_type = '' ): bool {
	   return ( \function_exists( 'vc_get_param' )
			&& 'true' === \vc_get_param( 'vc_editable' )
			&& $post_type === \get_post_type( vc_get_param( 'vc_post_id' ) )
		);
	}

	/**
	 * Checks if slim mode is enabled.
	 */
	public static function is_slim_mode_enabled(): bool {
		return \is_callable( '\TotalTheme\Integration\WPBakery\Slim_Mode::is_enabled' ) && Slim_Mode::is_enabled();
	}

	/**
	 * Returns the current post type we are editing in the admin.
	 */
	public static function get_admin_post_type(): string {
		$post_type = \get_post_type();
		if ( empty( $post_type ) ) {
			if ( $post = \vc_get_param( 'post' ) ) {
				$post_type = \get_post_type( (int) $post );
			} else {
				$post_type = \vc_get_param( 'post_type' ) ?: '';
			}
		}
		return (string) $post_type;
	}

}
