<?php

namespace TotalThemeCore\Vcex\Elementor\Widgets;

use Vcex_WooCommerce_Template_Shortcode as Shortcode;
use TotalThemeCore\Vcex\Elementor;
use TotalThemeCore\Vcex\Elementor\Widget_Settings;
use Elementor\Widget_Base;

\defined( 'ABSPATH' ) || exit;

class WooCommerce_Template extends Widget_Base {

	public function get_name() {
		return Shortcode::TAG;
	}

	public function get_title() {
		return Shortcode::get_title();
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_custom_help_url() {
		// none yet.
	}

	public function get_categories() {
		return [ Elementor::WOO_CATEGORY_ID ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product' ];
	}

	public function get_script_depends() {
		return [];
	}

	public function get_style_depends() {
		return [];
	}

	protected function register_controls() {
		$settings = new Widget_Settings( Shortcode::get_params() );

		if ( empty( $settings->sections ) ) {
			return;
		}

		include TTC_PLUGIN_DIR_PATH . 'inc/vcex/elementor/register-controls.php';
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		$atts['is_elementor_widget'] = true;
		echo Shortcode::output( $atts );
	}

}
