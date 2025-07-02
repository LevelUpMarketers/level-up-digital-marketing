<?php

namespace TotalThemeCore\Vcex\Elementor\Widgets;

use VCEX_Icon_Box_Shortcode;
use TotalThemeCore\Vcex\Elementor;
use TotalThemeCore\Vcex\Elementor\Widget_Settings;
use Elementor\Widget_Base;

\defined( 'ABSPATH' ) || exit;

class Icon_Box extends Widget_Base {

	public function get_name() {
		return VCEX_Icon_Box_Shortcode::TAG;
	}

	public function get_title() {
		return \esc_html__( 'Icon Box', 'total-theme-core' ) . '  (Total)';
	}

	public function get_icon() {
		return 'eicon-icon-box';
	}

	public function get_custom_help_url() {
		// none yet.
	}

	public function get_categories() {
		return [ Elementor::CATEGORY_ID ];
	}

	public function get_keywords() {
		return [ 'icon', 'icon box', 'blurb' ];
	}

	public function get_script_depends() {
		return [];
	}

	public function get_style_depends() {
		return [];
	}

	protected function register_controls() {
		$settings = new Widget_Settings( VCEX_Icon_Box_Shortcode::get_params() );

		if ( empty( $settings->sections ) ) {
			return;
		}

		include TTC_PLUGIN_DIR_PATH . 'inc/vcex/elementor/register-controls.php';
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		$atts['is_elementor_widget'] = true;
		$content = $atts['content'] ?? null;
		echo VCEX_Icon_Box_Shortcode::output( $atts, $content );
	}

}
