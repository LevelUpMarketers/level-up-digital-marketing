<?php

defined( 'ABSPATH' ) || exit;

/**
 * Leader Shortcode.
 */
if ( ! class_exists( 'VCEX_Leader_Shortcode' ) ) {

	class VCEX_Leader_Shortcode extends TotalThemeCore\Vcex\Shortcode_Abstract {

		/**
		 * Shortcode tag.
		 */
	   public const TAG = 'vcex_leader';

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
		   return esc_html__( 'Leader (Menu Items)', 'total-theme-core' );
	   }

	   /**
		* Shortcode description.
		*/
	   public static function get_description(): string {
		   return esc_html__( 'CSS dot or line leader (menu item)', 'total-theme-core' );
	   }

	   /**
		* Array of shortcode parameters.
		*/
	   public static function get_params_list(): array {
		   return array(
				array(
					'type' => 'param_group',
					'param_name' => 'leaders',
					'value' => urlencode( json_encode( array(
						array(
							'label' => esc_html__( 'One', 'total-theme-core' ),
							'value' => '$10',
						),
						array(
							'label' => esc_html__( 'Two', 'total-theme-core' ),
							'value' => '$20',
						),
					) ) ),
					'params' => array(
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Label', 'total-theme-core' ),
							'param_name' => 'label',
							'admin_label' => true,
						),
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Value', 'total-theme-core' ),
							'param_name' => 'value',
							'admin_label' => true,
						),
					),
				),
				array(
					'type' => 'textfield',
					'admin_label' => true,
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'param_name' => 'el_class',
					'description' => self::param_description( 'el_class' ),
				),
				vcex_vc_map_add_css_animation(),
				// Style
				array(
					'type' => 'vcex_select',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin', // can't name it margin_bottom due to WPBakery parsing issue
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Style', 'total-theme-core' ),
					'param_name' => 'style',
					'std' => 'dots',
					'choices' => array(
						'dots' => esc_html__( 'Dots', 'total-theme-core' ),
						'dashes' => esc_html__( 'Dashes', 'total-theme-core' ),
						'minimal' => esc_html__( 'Empty Space', 'total-theme-core' ),
					),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Responsive', 'total-theme-core' ),
					'param_name' => 'responsive',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select',
					'choices' => 'margin',
					'heading' => esc_html__( 'Space Between Items', 'total-theme-core' ),
					'param_name' => 'spacing',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background', 'total-theme-core' ),
					'param_name' => 'background',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_size',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'font_size',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				// Label
				array(
					'type' => 'vcex_colorpicker',
					'param_name' => 'label_color',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'group' => esc_html__( 'Label', 'total-theme-core' ),
				),
				array(
					'type'  => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'label_font_family',
					'group' => esc_html__( 'Label', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select',
					'choices' => 'font_weight',
					'param_name' => 'label_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'group' => esc_html__( 'Label', 'total-theme-core' ),
				),
				array(
					'heading' => esc_html__( 'Font Style', 'total-theme-core' ),
					'param_name' => 'label_font_style',
					'type' => 'vcex_select_buttons',
					'std' => '',
					'choices' => array(
						'' => esc_html__( 'Normal', 'total-theme-core' ),
						'italic' => esc_html__( 'Italic', 'total-theme-core' ),
					),
					'group' => esc_html__( 'Label', 'total-theme-core' ),
				),
				// Value
				array(
					'type' => 'vcex_colorpicker',
					'param_name' => 'value_color',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'group' => esc_html__( 'Value', 'total-theme-core' ),
				),
				array(
					'type'  => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'value_font_family',
					'group' => esc_html__( 'Value', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select',
					'choices' => 'font_weight',
					'param_name' => 'value_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'group' => esc_html__( 'Value', 'total-theme-core' ),
				),
				array(
					'heading' => esc_html__( 'Font Style', 'total-theme-core' ),
					'param_name' => 'value_font_style',
					'type' => 'vcex_select_buttons',
					'std' => '',
					'choices' => array(
						'' => esc_html__( 'Normal', 'total-theme-core' ),
						'italic' => esc_html__( 'Italic', 'total-theme-core' ),
					),
					'group' => esc_html__( 'Value', 'total-theme-core' ),
				),
			);
		}

	}

}

new VCEX_Leader_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Leader' ) ) {
	class WPBakeryShortCode_Vcex_Leader extends WPBakeryShortCode {}
}