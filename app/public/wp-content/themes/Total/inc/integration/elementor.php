<?php

namespace TotalTheme\Integration;

\defined( 'ABSPATH' ) || exit;

/**
 * Elementor Configuration Class
 */
final class Elementor {

	/**
	 * The theme font group name.
	 */
	public const FONT_GROUP_ID = 'total';

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Init.
	 */
	public static function init(): void {
		\add_action( 'wp_enqueue_scripts', [ self::class, 'front_css' ] );
		\add_action( 'elementor/theme/register_locations', [ self::class, 'register_locations' ] );
		\add_action( 'elementor/frontend/after_enqueue_scripts', [ self::class, 'editor_scripts' ] );
	//	\add_action( 'elementor/editor/after_enqueue_styles', [ self::class, 'editor_styles' ] );
	//	\add_action( 'elementor/preview/enqueue_styles', [ self::class, 'preview_styles' ] );

		// Custom fonts suppport.
		\add_action( 'elementor/fonts/groups',  [ self::class, 'font_groups' ] );
		\add_action( 'elementor/fonts/additional_fonts', [ self::class, 'additional_fonts' ] );
		\add_action( 'elementor/fonts/print_font_links/' . self::FONT_GROUP_ID, [ self::class, 'print_fonts' ] );

		// Theme Icons support.
		if ( \totaltheme_call_static( 'Theme_Icons', 'is_enabled' ) ) {
			\add_action( 'elementor/icons_manager/additional_tabs', [ self::class, 'icons_manager_additional_tabs' ] );
		}
	}

	/**
	 * Enqueues front-end CSS.
	 */
	public static function front_css(): void {
		\wp_enqueue_style(
			'wpex-elementor',
			\totaltheme_get_css_file( 'frontend/elementor' ),
			[ 'elementor-frontend' ],
			\WPEX_THEME_VERSION
		);
	}

	/**
	 * Registers Elementor locations.
	 */
	public static function register_locations( $elementor_theme_manager ): void {

		/**
		 * Filters whether the theme should register all core elementor locations via
		 * $elementor_theme_manager->register_all_core_location().
		 */
		$register_core_locations = (bool) \apply_filters( 'total_register_elementor_locations', true );

		if ( $register_core_locations ) {
			$elementor_theme_manager->register_all_core_location();
		}

		$elementor_theme_manager->register_location( 'togglebar', [
			'label'           => \esc_html__( 'Togglebar', 'total' ),
			'multiple'        => true,
			'edit_in_content' => false,
		] );

		$elementor_theme_manager->register_location( 'topbar', [
			'label'           => \esc_html__( 'Top Bar', 'total' ),
			'multiple'        => true,
			'edit_in_content' => false,
		] );

		$elementor_theme_manager->register_location( 'page_header', [
			'label'           => \esc_html__( 'Page Header', 'total' ),
			'multiple'        => true,
			'edit_in_content' => false,
		] );

		$elementor_theme_manager->register_location( 'footer_callout', [
			'label'           => \esc_html__( 'Footer Callout', 'total' ),
			'multiple'        => true,
			'edit_in_content' => false,
		] );

		$elementor_theme_manager->register_location( 'footer_bottom', [
			'label'           => \esc_html__( 'Footer Bottom', 'total' ),
			'multiple'        => true,
			'edit_in_content' => false,
		] );

	}

	/**
	 * Add Theme Font Group.
	 */
	public static function font_groups( array $groups ): array {
		if ( \wpex_get_registered_fonts() ) {
			$groups[ self::FONT_GROUP_ID ] = 'Total';
		}
		return $groups;
	}

	/**
	 * Add Theme Font Options.
	 */
	public static function additional_fonts( array $additional_fonts ): array {
		foreach ( \wpex_get_registered_fonts() as $font_name => $font_args ) {
			$additional_fonts[ $font_name ] = self::FONT_GROUP_ID;
		}
		return $additional_fonts;
	}

	/**
	 * Enqueue fonts.
	 */
	public static function print_fonts( $font ): void {
		if ( $font ) {
			\wpex_enqueue_font( $font, 'registered' );
		}
	}

	/**
	 * Enqueue JS in the editor.
	 */
	public static function editor_scripts(): void {
		if ( ! \defined( 'VCEX_ELEMENTOR_INTEGRATION' )
			|| ! \class_exists( '\Elementor\Plugin' )
			|| ! \Elementor\Plugin::$instance->preview->is_preview_mode()
		) {
			return;
		}

		\wp_enqueue_script(
			'totaltheme-admin-elementor-preview',
			\totaltheme_get_js_file( 'admin/elementor/preview' ),
			[],
			\WPEX_THEME_VERSION,
			true
		);
	}

	/**
	 * Enqueue CSS in the Editor.
	 */
	public static function editor_styles() {}

	/**
	 * Enqueue CSS for the preview panel.
	 */
	public static function preview_styles() {}

	/**
	 * Adds Theme Icons tab to Icons Manager.
	 */
	public static function icons_manager_additional_tabs( array $tabs ): array {
		$tabs['ticon'] = [
			'name'            => 'ticon',
			'label'           => \esc_html__( 'Theme Icons', 'total' ),
			'url'             => \totaltheme_call_static( 'Theme_Icons', 'get_css_url' ),
			'prefix'          => 'ticon-',
			'displayPrefix'   => 'ticon',
			'labelIcon'       => 'ticon ticon-totaltheme',
			'ver'             => \WPEX_THEME_VERSION,
			'fetchJson'       => \WPEX_THEME_URI . '/assets/icons/list-elementor.json',
			'native'          => true,
			'render_callback' => [ self::class, 'render_theme_icon' ],
		];
		return $tabs;
	}

	/**
	 * Callback function for rendering theme icons inside elementor.
	 */
	public static function render_theme_icon( $icon = [], $attributes = [], $tag = 'i' ) {
		if ( empty( $icon['value'] ) || empty( $icon['library'] ) || 'ticon' !== $icon['library'] ) {
			return;
		}
		return \totaltheme_call_static( 'Theme_Icons', 'get_icon', $icon['value'], $attributes );
	}

	/**
	 * Check if a location has a template.
	 */
	public static function location_exists( string $location ): bool {
		return \function_exists( '\elementor_location_exits' ) && \totaltheme_is_integration_active( 'elementor' ) && \elementor_location_exits( $location, true );
	}
}