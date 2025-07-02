<?php

namespace TotalThemeCore\Vcex\Elementor\Widgets;

use VCEX_Image_Grid as Shortcode;
use TotalThemeCore\Vcex\Elementor;
use TotalThemeCore\Vcex\Elementor\Widget_Settings;
use Elementor\Widget_Base;

\defined( 'ABSPATH' ) || exit;

class Image_Grid extends Widget_Base {

	public function get_name() {
		return Shortcode::TAG;
	}

	public function get_title() {
		return \esc_html__( 'Image Grid', 'total-theme-core' ) . '  (Total)';
	}

	public function get_icon() {
		return 'eicon-photo-library';
	}

	public function get_custom_help_url() {
		// none yet.
	}

	public function get_categories() {
		return [ Elementor::CATEGORY_ID ];
	}

	public function get_keywords() {
		return [ 'image', 'gallery', 'images', 'grid' ];
	}

	public function get_script_depends() {
		if ( isset( $_GET['elementor-preview'] ) ) {
			return [
				'imagesloaded',
				'isotope',
				'vcex-isotope-grids',
				'justifiedGallery',
				'vcex-justified-gallery',
			];
		}
		return [];
	}

	public function get_style_depends() {
		if ( isset( $_GET['elementor-preview'] ) ) {
			return [
				'vcex-justified-gallery'
			];
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
		$atts['columns_responsive_settings'] = '';
		if ( ! empty( $atts['columns_tablet'] ) && is_numeric( $atts['columns_tablet'] ) ) {
			$atts['columns_responsive_settings'] .= '|tp:' . $atts['columns_tablet'];
		}
		if ( ! empty( $atts['columns_mobile'] ) && is_numeric( $atts['columns_mobile'] ) ) {
			$atts['columns_responsive_settings'] .= '|pl:' . $atts['columns_mobile'];
		}
		echo Shortcode::output( $atts );
	}

}
