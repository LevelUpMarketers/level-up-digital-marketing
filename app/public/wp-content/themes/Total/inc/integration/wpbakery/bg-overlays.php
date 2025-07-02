<?php

namespace TotalTheme\Integration\WPBakery;

\defined( 'ABSPATH' ) || exit;

final class BG_Overlays {

	/**
	 * Instance.
	 */
	private static $instance = null;

	/**
	 * Shortcodes to add overlay settings to.
	 */
	private $shortcodes = [
		'vc_section',
		'vc_row',
		'vc_column',
	];

	/**
	 * Create or retrieve the class instance.
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new self();
		}
		return static::$instance;
	}

	/**
	 * Private constructor.
	 */
	private function __construct() {
		\add_action( 'vc_after_init', [ $this, 'add_params' ] );
		\add_filter( \VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, [ $this, 'add_classes' ], 10, 3 );
		\add_filter( 'vc_edit_form_fields_attributes_vc_row', [ $this, 'edit_form_fields' ] );

		foreach ( $this->shortcodes as $shortcode ) {
			\add_filter( $this->get_insert_hook( $shortcode ), [ $this, 'insert_overlay' ], 50, 2 ); // priority is important.
		}
	}

	/**
	 * Returns the hook for inserting the overlay.
	 */
	protected function get_insert_hook( $shortcode = '' ) {
		if ( 'vc_column' === $shortcode ) {
			$shortcode = 'vc_column_inner';
		}
		return "wpex_hook_{$shortcode}_top";
	}

	/**
	 * Hooks into "wpex_vc_attributes" to add new params.
	 */
	public function add_params() {
		if ( ! \function_exists( 'vc_add_params' ) ) {
			return;
		}

		foreach ( $this->shortcodes as $shortcode ) {
			\vc_add_params( $shortcode, $this->get_attributes() );
		}
	}

	/**
	 * Returns vc_map params.
	 */
	private function get_attributes() {
		return [
			[
				'type' => 'vcex_select',
				'heading' => \esc_html__( 'Overlay Style', 'total' ),
				'param_name' => 'wpex_bg_overlay',
				'group' => \esc_html__( 'Overlay', 'total' ),
				'choices' => [
					'' => \esc_html__( 'None', 'total' ),
					'color' => \esc_html__( 'Color', 'total' ),
					'dark' => \esc_html__( 'Dark', 'total' ),
					'dotted' => \esc_html__( 'Dotted', 'total' ),
					'dashed' => \esc_html__( 'Diagonal Lines', 'total' ),
					'custom' => \esc_html__( 'Custom', 'total' ),
				],
			],
			[
				'type' => 'vcex_select',
				'heading' => \esc_html__( 'Overlay Mix Blend Mode', 'total' ),
				'param_name' => 'wpex_bg_overlay_blend',
				'choices' => 'mix_blend_mode',
				'group' => \esc_html__( 'Overlay', 'total' ),
			],
			[
				'type' => 'vcex_colorpicker',
				'heading' => \esc_html__( 'Overlay Color', 'total' ),
				'param_name' => 'wpex_bg_overlay_color',
				'group' => \esc_html__( 'Overlay', 'total' ),
				'dependency' => [
					'element' => 'wpex_bg_overlay',
					'value' => [ 'color', 'dark', 'dotted', 'dashed', 'custom' ]
				],
			],
			[
				'type' => 'attach_image',
				'heading' => \esc_html__( 'Custom Overlay Pattern', 'total' ),
				'param_name' => 'wpex_bg_overlay_image',
				'group' => \esc_html__( 'Overlay', 'total' ),
				'dependency' => [ 'element' => 'wpex_bg_overlay', 'value' => 'custom' ],
			],
			[
				'type' => 'vcex_text',
				'heading' => \esc_html__( 'Overlay Opacity', 'total' ),
				'param_name' => 'wpex_bg_overlay_opacity',
				'placeholder' => '65%',
				'dependency' => [
					'element' => 'wpex_bg_overlay',
					'value' => [ 'color', 'dark', 'dotted', 'dashed', 'custom' ]
				],
				'group' => \esc_html__( 'Overlay', 'total' ),
			],
		];
	}

	/**
	 * Parses shortcode attributes when editing the shortcodes.
	 */
	public function edit_form_fields( $atts ) {
		if ( ! empty( $atts['video_bg_overlay'] ) && 'none' !== $atts['video_bg_overlay'] ) {
			$atts['wpex_bg_overlay'] = $atts['video_bg_overlay'];
			unset( $atts['video_bg_overlay'] );
		}
		return $atts;
	}

	/**
	 * Adds classes to shortcodes that have overlays.
	 */
	public function add_classes( $class_string, $tag, $atts ) {
		if ( \in_array( $tag, $this->shortcodes )
			&& ! empty( $atts['wpex_bg_overlay'] )
			&& 'none' !== $atts['wpex_bg_overlay']
		) {
			$class_string .= ' wpex-has-overlay';
			if ( ! \str_contains( $class_string, 'wpex-relative' ) && ! \str_contains( $class_string, 'wpex-sticky' ) ) {
				$class_string .= ' wpex-relative';
			}
		}
		return $class_string;
	}

	/**
	 * Inserts the overlay HTML into the shortcodes.
	 */
	public function insert_overlay( $content, $atts ) {
		if ( $overlay = $this->render_overlay( $atts ) ) {
			$content .= $overlay;
		}
		return $content;
	}

	/**
	 * Render the overlay.
	 */
	private function render_overlay( $atts ) {
		if ( empty( $atts['wpex_bg_overlay'] ) || 'none' === $atts['wpex_bg_overlay'] ) {
			return;
		}

		$overlay  = $atts['wpex_bg_overlay'];
		$bg_image = '';
		$style    = '';

		switch ( $overlay ) {
			case 'custom':
				$bg_image = ! empty( $atts['wpex_bg_overlay_image'] ) ? \wp_get_attachment_url( $atts['wpex_bg_overlay_image'] ) : '';
				break;
			case 'dotted':
				$bg_image = \wpex_asset_url( 'images/overlays/dotted.png' );
				break;
			case 'dashed':
				$bg_image = \wpex_asset_url( 'images/overlays/dashed.png' );
				break;
		}

		if ( $bg_image && $bg_image_safe = \esc_url( $bg_image ) ) {
			$style .= "background-image:url({$bg_image_safe});";
		}

		if ( ! empty( $atts['wpex_bg_overlay_color'] ) && $overlay_color_safe = \wpex_parse_color( $atts['wpex_bg_overlay_color'] ) ) {
			$style .= "background-color:{$overlay_color_safe};";
		}

		if ( ! empty( $atts['wpex_bg_overlay_opacity'] ) && $opacity_safe = sanitize_text_field( $atts['wpex_bg_overlay_opacity'] ) ) {
			if ( '1' !== $opacity_safe && \is_numeric( $opacity_safe ) && ! \str_contains( $opacity_safe, '.' ) ) {
				$opacity_safe = "{$opacity_safe}%";
			}
			$style .= "opacity:{$opacity_safe};";
		}

		$overlay_attributes = [
			'class' => [
				'wpex-bg-overlay',
				\sanitize_html_class( $overlay ),
				'wpex-absolute',
				'wpex-inset-0',
				'wpex-rounded-inherit',
			],
		];

		if ( ! in_array( $overlay, [ 'dotted', 'dashed' ] ) ) {
			$overlay_attributes['class'][] = 'wpex-opacity-60';
		}

		if ( $bg_image || in_array( $overlay, [ 'custom', 'dotted', 'dashed' ] ) ) {
			$overlay_attributes['class'][] = 'wpex-bg-transparent';
		} else {
			$overlay_attributes['class'][] = 'wpex-bg-black';
		}

		if ( ! empty( $atts['wpex_bg_overlay_blend'] ) ) {
			$overlay_attributes['class'][] = 'wpex-mix-blend-' . \sanitize_html_class( $atts['wpex_bg_overlay_blend'] );
		}

		if ( $style ) {
			$overlay_attributes['style'] = $style;
		}

		return '<div class="wpex-bg-overlay-wrap wpex-absolute wpex-inset-0 wpex-rounded-inherit">' . \wpex_parse_html( 'span', $overlay_attributes ) . '</div>';
	}

	/**
	 * Prevent cloning.
	 */
	private function __clone() {}

	/**
	 * Prevent unserializing.
	 */
	public function __wakeup() {
		\trigger_error( 'Cannot unserialize a Singleton.', \E_USER_WARNING);
	}

}
