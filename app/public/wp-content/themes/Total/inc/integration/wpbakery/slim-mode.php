<?php

namespace TotalTheme\Integration\WPBakery;

\defined( 'ABSPATH' ) || exit;

class Slim_Mode {

	/**
	 * Used to prevent extra lookups when using is_enabled().
	 */
	protected static $is_enabled;

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Check if this functionality is enabled.
	 *
	 * Note: This method is static so we can call it without initializing our class.
	 */
	public static function is_enabled(): bool {
		if ( \is_null( self::$is_enabled ) ) {
			self::$is_enabled = (bool) \apply_filters( 'totaltheme/integration/wpbakery/slim_mode/is_enabled', \get_theme_mod( 'wpb_slim_mode_enable', false ) );
		}
		return self::$is_enabled;
	}

	/**
	 * Init.
	 *
	 * Note: We only switch the CSS on the front-end because for some reason
	 * WPBakery adds CSS for the editor in the js_composer_front css file.
	 */
	public static function init(): void {
		if ( ! \totaltheme_is_wpb_frontend_editor() ) {
			\add_action( 'wp_enqueue_scripts', [ self::class, 'remove_vc_css' ], 40 );
			\add_action( 'wp_footer', [ self::class, 'remove_vc_css' ] ); // vc loads their CSS for every shortcode (uff).
			\add_action( 'vc_load_iframe_jscss', [ self::class, 'remove_vc_css' ] );
		}

		\add_filter( 'vc_after_init', [ self::class, 'deprecate_elements' ] );
		\add_filter( 'totaltheme/integration/wpbakery/remove_elements/blacklist', [ self::class, 'remove_vc_elements' ] );
		\add_filter( 'vcex_shortcodes_list', [ self::class, 'remove_vcex_elements' ] );

		self::remove_grid_builder();

		// @todo need to remove the pixel icon selectors.

		\add_action( 'init', [ self::class, 'hide_dynamic_elements' ], 11 );

		// Remove element map hooks.
		\remove_action( 'init', 'vc_gutenberg_map' );
	}

	/**
	 * Remove the the vc CSS.
	 */
	public static function remove_vc_css(): void {
		\wp_dequeue_style( 'js_composer_front' );
		\wp_dequeue_style( 'js_composer_custom_css' );
	}

	/**
	 * Remove elements.
	 */
	public static function remove_vc_elements( array $list ): array {
		$new_items = [
			'vc_gutenberg',
			'vc_pie',
			'vc_empty_space',
			'vc_hoverbox',
			'vc_pinterest',
			'vc_tweetmeme',
			'vc_facebook',
			'vc_btn',
			'vc_flickr',
			'vc_progress_bar',
			'vc_cta',
			'vc_basic_grid',
			'vc_media_grid',
			'vc_masonry_grid',
			'vc_masonry_media_grid',
			'vc_separator',
			'vc_single_image',
			'vc_custom_heading',
			'vc_icon',
			'vc_wp_search',
			'vc_wp_recentcomments',
			'vc_wp_calendar',
			'vc_wp_tagcloud',
			'vc_wp_custommenu',
			'vc_wp_posts',
			'vc_wp_categories',
			'vc_wp_archives',
			'vc_wp_rss',
			'vc_toggle',
			'vc_tabs',
			'vc_tour',
			'vc_accordion',
			'vc_text_separator',
			'vc_message',
			'vc_zigzag',
			'vc_acf',
			'vc_pricing_table',
			'vc_tta_toggle',
			'vc_tta_pageable', // disabled because it requires a massive amount of CSS.

			// @note re-enabled in 5.10 - these don't require added CSS and are useful.
			//	'vc_round_chart',
			//	'vc_line_chart',
		];

		if ( ! apply_filters( 'totaltheme/integration/wpbakery/slim_mode/deprecate_elements', true ) ) {
			$new_items = array_merge( $new_items, self::deprecated_elements_list() );
		}

		return array_merge( $list, $new_items );
	}

	/**
	 * Remove elements.
	 */
	public static function remove_vcex_elements( array $elements ): array {
		$elements_to_remove = [
			'post_type_grid',
			'blog_grid',
			'blog_carousel',
			'portfolio_carousel',
			'portfolio_grid',
			'post_type_carousel',
			'post_type_slider',
			'post_type_archive',
			'staff_carousel',
			'staff_grid',
			'testimonials_carousel',
			'testimonials_grid',
		//	'testimonials_slider',
			'woocommerce_carousel',
			'woocommerce_loop_carousel',
			'image_galleryslider',
			'form_shortcode',
			'grid_item-post_excerpt',
			'grid_item-post_meta',
			'grid_item-post_terms',
			'grid_item-post_video',
		];

		return array_diff( $elements, $elements_to_remove );
	}

	/**
	 * Remove the the vc grid builder.
	 */
	public static function remove_grid_builder(): void {
		\remove_action( 'init', 'vc_grid_item_editor_create_post_type' );
		\remove_action( 'vc_after_init', 'vc_grid_item_editor_shortcodes' );
		\remove_action( 'wp_ajax_vc_gitem_preview', 'vc_grid_item_render_preview', 5 );
		if ( \is_admin() ) {
			\remove_action( 'admin_init', 'vc_grid_item_editor_init' );
			\remove_action( 'vc_ui-pointers-vc_grid_item', 'vc_grid_item_register_pointer' );
			\remove_action( 'admin_head', 'vc_gitem_menu_highlight' );
			\remove_action( 'wp_ajax_vc_edit_form', 'vc_gitem_set_mapper_check_access' );
		}
	}

	/**
	 * Deprecate elements.
	 */
	public static function deprecate_elements() {
		foreach ( self::deprecated_elements_list() as $element ) {
			vc_map_update( $element, [ 'deprecated' => true ] );
		}
	}

	/**
	 * Returns array of elements that will be "deprecated" rather then removed.
	 */
	protected static function deprecated_elements_list(): array {
		return [
			'vc_video',
		];
	}

	/**
	 * Hide dymamic elements.
	 */
	public static function hide_dynamic_elements(): void {
		if ( \function_exists( 'vc_is_page_editable' ) && \vc_is_page_editable() ) {
			\add_action( 'wp_head', [ self::class, 'vc_hide_elements_css' ] );
		} elseif ( is_admin() ) {
			\add_action( 'admin_print_scripts-post.php', [ self::class, 'vc_hide_elements_css' ], 1 );
			\add_action( 'admin_print_scripts-post-new.php', [ self::class, 'vc_hide_elements_css' ], 1 );
		}
	}

	/**
	 * Hide dymamic elements CSS.
	 */
	public static function vc_hide_elements_css(): void {
		$post_type = get_post_type();
		if ( empty( $post_type ) && function_exists( 'vc_get_param' ) ) {
			if ( \vc_get_param( 'post' ) ) {
				$post_type = \get_post_type( (int) \vc_get_param( 'post' ) );
			} elseif ( \vc_get_param( 'post_type' ) ) {
				$post_type = \vc_get_param( 'post_type' );
			}
		}

		if ( \in_array( $post_type, [ 'wpex_card', 'wpex_templates' ] ) ) {
			return;
		}

		$css = '';

		$elements_to_hide = [
			'vcex_post_content',
			'vcex_post_comments',
			'vcex_post_meta',
			'vcex_post_excerpt',
			'vcex_post_terms',
			'vcex_post_media',
			'vcex_post_next_prev',
			'vcex_post_series',
			'vcex_author_bio',
			'vcex_breadcrumbs',
			'staff_social',
			'vcex_page_title',
		];

		foreach ( $elements_to_hide as $element ) {
			$css .= '[data-element="' . \esc_attr( $element ) . '"],';
		}

		if ( $css ) {
			$css = \rtrim( $css, ',' );
			echo "<style>.wpb-layout-element-button:is({$css}){display:none!important;}</style>";
		}
	}

}
