<?php

namespace TotalThemeCore\Vcex\Elementor\Widgets;

use TotalThemeCore\Vcex\Elementor;
use TotalThemeCore\Vcex\Elementor\Widget_Settings;
use TotalThemeCore\Vcex\Carousel\Core as Vcex_Carousel;
use Wpex_Post_Cards_Shortcode as Shortcode;
use Elementor\Widget_Base;


\defined( 'ABSPATH' ) || exit;

class Post_Cards extends Widget_Base {

	public function get_name() {
		return Shortcode::TAG;
	}

	public function get_title() {
		return \esc_html__( 'Post Cards', 'total-theme-core' ) . '  (Total)';
	}

	public function get_icon() {
		return 'eicon-archive-posts';
	}

	public function get_custom_help_url() {
		// none yet.
	}

	public function get_categories() {
		return [ Elementor::CATEGORY_ID ];
	}

	public function get_keywords() {
		return [ 'posts', 'cards', 'post cards' ];
	}

	public function get_script_depends() {
		if ( isset( $_GET['elementor-preview'] ) ) {
			$scripts = [
				'imagesloaded',
				'isotope',
				'wpex-isotope',
			];
			return array_merge( $scripts, Vcex_Carousel::get_script_depends() );
		}
		return [];
	}

	public function get_style_depends() {
		if ( isset( $_GET['elementor-preview'] ) ) {
			return Vcex_Carousel::get_style_depends();
		}
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
		$atts['grid_columns_responsive_settings'] = '';
		if ( ! empty( $atts['grid_columns_tablet'] ) && is_numeric( $atts['grid_columns_tablet'] ) ) {
			$atts['grid_columns_responsive_settings'] .= '|tp:' . $atts['grid_columns_tablet'];
		}
		if ( ! empty( $atts['grid_columns_mobile'] ) && is_numeric( $atts['grid_columns_mobile'] ) ) {
			$atts['grid_columns_responsive_settings'] .= '|pl:' . $atts['grid_columns_mobile'];
		}
		echo Shortcode::output( $atts );
	}

}
