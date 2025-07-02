<?php

defined( 'ABSPATH' ) || exit;

/**
 * Post Series Shortcode.
 */
if ( ! class_exists( 'VCEX_Post_Series_Shortcode' ) ) {

	class VCEX_Post_Series_Shortcode extends TotalThemeCore\Vcex\Shortcode_Abstract {

		/**
		 * Shortcode tag.
		 */
		public const TAG = 'vcex_post_series';

		/**
		 * Main constructor.
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * Shortcode title.
		 */
		public static function get_title(): string {
			return esc_html__( 'Post Series', 'total-theme-core' );
		}

		/**
		 * Shortcode description.
		 */
		public static function get_description(): string {
			return esc_html__( 'Display your post series', 'total-theme-core' );
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params_list(): array {
			return [
				[
					'type' => 'vcex_notice',
					'param_name' => 'main_notice',
					'text' => esc_html__( 'Go to Appearance > Customize > General Theme Options > Post Series to customize this global element.', 'total-theme-core' ),
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Max Width', 'total-theme-core' ),
					'param_name' => 'max_width',
					'css' => true,
					'description' => self::param_description( 'width' ),
				],
				[
					'type' => 'vcex_text_align',
					'heading' => esc_html__( 'Aligment', 'total-theme-core' ),
					'param_name' => 'align',
					'std' => 'center',
					'dependency' => [ 'element' => 'max_width', 'not_empty' => true ],
				],
				[
					'type' => 'typography',
					'heading' => esc_html__( 'Typography', 'total-theme-core' ),
					'param_name' => 'typography',
					'selector' => '.wpex-post-series-toc',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
					'editors' => [ 'elementor' ],
				],
			];
		}

	}

}

new VCEX_Post_Series_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Post_Series' ) ) {
	class WPBakeryShortCode_Vcex_Post_Series extends WPBakeryShortCode {}
}
