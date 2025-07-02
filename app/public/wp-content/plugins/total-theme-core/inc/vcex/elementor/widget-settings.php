<?php

namespace TotalThemeCore\Vcex\Elementor;

use Elementor\Controls_Manager as Elementor_Controls_Manager;

\defined( 'ABSPATH' ) || exit;

final class Widget_Settings {

	/**
	 * Params.
	 */
	protected $params = [];

	/**
	 * Sections.
	 */
	public $sections = [];

	/**
	 * Takes vcex params array converts them into a readable array for Elementor.
	 */
	public function __construct( $params ) {
		$this->params = $params;
		$this->sections = [];
		$this->parse_params();
	}

	/**
	 * Takes vcex params array converts them into a readable array for Elementor.
	 */
	protected function parse_params() {
		foreach ( $this->params as $param_k => $param_args ) {
			if ( ! isset( $param_args['editors'] ) || ! \is_array( $param_args['editors'] ) || ! \in_array( 'elementor', $param_args['editors'], true ) ) {
				continue; // not an elementor param.
			}

			$type = $param_args['elementor']['type'] ?? $param_args['type'] ?? 'textfield';

			if ( 'hidden' === $type ) {
				continue;
			}

			$section    = $param_args['elementor']['group'] ?? $param_args['group'] ?? \esc_html__( 'General', 'total-theme-core' );
			$heading    = $param_args['heading'] ?? $param_args['label'] ?? null;
			$name       = $param_args['elementor']['name'] ?? $param_args['param_name'] ?? null;
			$default    = $param_args['default'] ?? $param_args['std'] ?? null;
			$value      = $param_args['value'] ?? null;
			$dependency = $param_args['elementor']['condition'] ?? $param_args['dependency'] ?? false;

			// Checks.
			if ( ! $heading || ! $name ) {
				continue;
			}

			$section_key = \sanitize_key( $section );

			// Add new section to array.
			if ( ! \array_key_exists( $section_key, $this->sections ) ) {
				$this->add_new_section( $section );
			}

			// Add main params.
			$this->add_setting_param( $section_key, $name, 'label', $heading );

			if ( ! empty( $param_args['description'] ) ) {
				$this->add_setting_param( $section_key, $name, 'description', $param_args['description'] );
			}

			if ( ! empty( $param_args['placeholder'] ) ) {
				$this->add_setting_param( $section_key, $name, 'placeholder', $param_args['placeholder'] );
			}

			// Get default param.
			if ( ! $default ) {
				if ( 'vcex_ofswitch' === $type ) {
					$default = $param_args['vcex']['on'] ?? 'true';
				}
				if ( $value ) {
					if ( \is_array( $value ) ) {
						$default = reset( $value );
					} elseif ( \is_string( $value ) ) {
						$default = $value;
					}
				}
			}

			// Type tweaks.
			switch ( $type ) {
				case 'vcex_select_callback_function':
				case 'vcex_custom_field':
					$type = 'text';
					break;
				case 'vcex_sorter':
					if ( ! empty( $param_args['choices'] ) ) {
						$this->add_setting_param( $section_key, $name, 'multiple', true );
						$this->add_setting_param( $section_key, $name, 'options', $param_args['choices'] );
						$type = 'select2';
						unset( $param_args['choices'] ); // prevents first option from being selected by default.
					}
					break;
				case 'posttypes':
					$type = 'select2';
					$this->add_setting_param( $section_key, $name, 'label_block', true );
					$this->add_setting_param( $section_key, $name, 'options', $this->choices_get_post_types() );
					$this->add_setting_param( $section_key, $name, 'multiple', true );
					break;
				case 'checkbox':
					if ( $value && \is_array( $value ) ) {
						$type = 'select2';
						$choices = \array_flip( $param_args['value'] );
						if ( $default && \is_string( $default ) ) {
							$default = explode(',', $default);
						}
						$this->add_setting_param( $section_key, $name, 'options', $choices );
						$this->add_setting_param( $section_key, $name, 'multiple', true );
					} else {
						$type = 'switch';
					}
					break;
				case 'vcex_select':
				case 'vcex_select_buttons':
					$type = 'dropdown';
					if ( ! empty( $param_args['choices_callback'] ) ) {
						$param_args['choices'] = is_callable( $param_args['choices_callback'] ) ? call_user_func( $param_args['choices_callback'] ) : [];
					} else {
						$param_args['choices'] = $param_args['choices'] ?? $name;
					}
					break;
				case 'vcex_text_align':
					$type = 'dropdown';
					$param_args['choices'] = \totalthemecore_call_static( 'WPBakery\Params\Text_Align', 'get_choices', $param_args );
					break;
				case 'vcex_hover_animations':
				case 'vcex_button_colors':
				case 'vcex_button_styles':
				case 'vcex_image_sizes';
				case 'vcex_image_crop_locations':
				case 'vcex_image_filters':
				case 'vcex_image_hovers':
					$param_args['choices'] = \str_replace( 'vcex_', '', $type );
					$type = 'dropdown';
					break;
				case 'vcex_preset_textfield':
					if ( 'icon_size' === $name ) {
						$type = 'text';
					}
					$param_args['choices'] = $param_args['choices'] ?? $name;
					break;
				case 'exploded_textarea':
					$type = 'textarea';
				//	$this->add_setting_param( $section_key, $name, 'repeater', $param_args['elementor']['repeater'] );
					break;
				case 'vc_link':
					// @note Elementor is bugged and the url options display anyway.
					if ( empty( $param_args['elementor']['url_options'] ) ) {
						$this->add_setting_param( $section_key, $name, 'options', false );
					}
					break;
				case 'vcex_grid_columns':
					$this->add_setting_param( $section_key, $name, 'responsive', true );
					$type = 'select';
					$param_args['choices'] = 'grid_columns';
					break;
				case 'vcex_notice':
					$type = 'raw_html';
					$this->add_setting_param( $section_key, $name, 'raw', $param_args['text'] );
					$this->add_setting_param( $section_key, $name, 'content_classes', 'vcex-elementor-control-notice' );
					break;
				case 'iconpicker':
				case 'vcex_select_icon':
					$type = 'icon';
					$dependency = false;
					if ( ! empty( $param_args['choices'] ) ) {
						$param_args['choices'] = \array_combine( $param_args['choices'], $param_args['choices'] );
					}
					if ( $default ) {
						$default = [ 'value'  => "ticon-{$default}", 'library' => 'ticon' ];
					}
					break;
				case 'vcex_wpex_card_select':
					$type = 'select2';
					$param_args['choices'] = \function_exists( 'wpex_choices_card_styles' ) ? \wpex_choices_card_styles() : [];
					break;
				case 'dropdown':
					if ( $value && \is_array( $value ) ) {
						$this->add_setting_param( $section_key, $name, 'options', \array_flip( $value ) );
					}
					break;
				case 'vcex_ofswitch':
					$type = 'switcher';
					$return_value = $param_args['vcex']['on'] ?? 'true';
					if ( $default && 'false' === $default ) {
						$default = ''; // if the default is "false" it causes issues with conditional checks in the editor.
					}
					$this->add_setting_param( $section_key, $name, 'label_on', \esc_html__( 'On', 'total-theme-core' ) );
					$this->add_setting_param( $section_key, $name, 'label_off', \esc_html__( 'Off', 'total-theme-core' ) );
					$this->add_setting_param( $section_key, $name, 'return_value', $return_value );
					break;
			}

			// Add custom settings.
			if ( ! empty( $param_args['elementor'] ) ) {
				foreach ( $param_args['elementor'] as $el_param_k => $el_param_v ) {
					if ( 'name' === $el_param_k || 'type' === $el_param_k || 'group' === $el_param_k ) {
						continue;
					}
					$this->add_setting_param( $section_key, $name, $el_param_k, $el_param_v );
				}
			}

			// Add dependency.
			if ( $dependency ) {
				$condition = $this->parse_dependency( $dependency );
				if ( $condition ) {
					$this->add_setting_param( $section_key, $name, 'condition', $condition );
				}
			}

			$elementor_args = [
				'show_label',
				'label_block',
				'separator',
			];

			foreach ( $elementor_args as $elementor_arg ) {
				if ( ! empty( $param_args[$elementor_arg] ) ) {
					$this->add_setting_param( $section_key, $name, $elementor_arg, $param_args[$elementor_arg] );
				}
			}

			$elementor_groups = [
				'typography',
			//	'vcex_grid_columns_responsive',
			];

			foreach ( $elementor_groups as $elementor_group ) {
				if ( $elementor_group === $type ) {
					$group_params = $this->get_group_params( $type, $param_args );
					if ( $group_params ) {
						$this->add_setting_param( $section_key, $name, 'group', $group_params );
					}
				}
			}

			// !!! These should always be last !!!
			if ( ! empty( $param_args['choices'] ) && empty( $param_args['elementor']['options'] ) ) {
				$options = $this->parse_options( $param_args['choices'], $param_args );
				if ( $options ) {
					$this->add_setting_param( $section_key, $name, 'options', $options );
					if ( ! $default ) {
						$default = array_key_first( $options );
					}
				}
			}

			// Add param type.
			$this->add_setting_param( $section_key, $name, 'type', $this->parse_param_type( $type ) );

			// Add default.
			if ( $default ) {
				$this->add_setting_param( $section_key, $name, 'default', $default );
			}
		}

		// Restore keys.
		$this->params = \array_values( $this->params );
	}

	/**
	 * Adds new section.
	 */
	protected function add_new_section( $section ) {
		$this->sections[ \sanitize_key( $section ) ] = [
			'label'    => $section,
			'settings' => [],
		];
	}

	/**
	 * Adds new section setting param.
	 */
	protected function add_setting_param( $section, $setting_name, $param_name, $param_value ) {
		$this->sections[ $section ]['settings'][ $setting_name ][ $param_name ] = $param_value;
	}

	/**
	 * Parses options before sending to elementor.
	 */
	protected function parse_options( $options, $param_args ) {
		if ( ! \is_array( $options ) && \class_exists( 'TotalThemeCore\Vcex\Setting_Choices' ) ) {
			return (new \TotalThemeCore\Vcex\Setting_Choices( $options, $param_args, 'elementor' ))->get_choices();
		}
		return $options;
	}

	/**
	 * Returns correct args for elementor conditional control from dependency param.
	 */
	protected function parse_dependency( $dependency ) {
		if ( ! \is_array( $dependency ) || empty( $dependency['element'] ) ) {
			return;
		}
		$element = $dependency['element'];
		$element_exists = false;
		$target_type = '';
		foreach ( $this->params as $param_k => $param_args ) {
			$name = $param_args['param_name'] ?? null;
			if ( ! $name ) {
				continue;
			}
			if ( $name === $element ) {
				$element_exists = true;
				$target_type = $param_args['type'] ?? 'text';
				break;
			}
		}
		if ( ! $element_exists ) {
			return;
		}
		$equality = '';
		if ( ! empty( $dependency['value'] ) ) {
			if ( 'false' === $dependency['value'] && 'vcex_ofswitch' === $target_type ) {
				$check = '';
			} else {
				$check = $dependency['value'];
			}
		} elseif ( ! empty( $dependency['is_empty'] ) ) {
			$check = '';
		} elseif ( ! empty( $dependency['not_empty'] ) ) {
			$equality = '!';
			$check = '';
		} elseif( ! empty( $dependency['value_not_equal_to'] ) ) {
			$equality = '!';
			$check = $dependency['value_not_equal_to'];
		}
		if ( ! isset( $check ) ) {
			return;
		}
		$condition = [
			$element . $equality => $check
		];
		return $condition;
	}

	/**
	 * Parses the param type to return an Elementor compatible type.
	 */
	protected function parse_param_type( $type ) {
		$controls = [
			'textarea'              => Elementor_Controls_Manager::TEXTAREA,
			'textarea_safe'         => Elementor_Controls_Manager::TEXTAREA,
			'select2'               => Elementor_Controls_Manager::SELECT2,
			'repeater'              => Elementor_Controls_Manager::REPEATER,
			'vcex_trbl'             => Elementor_Controls_Manager::DIMENSIONS,
			'raw_html'              => Elementor_Controls_Manager::CODE,
			'textarea_raw_html'     => Elementor_Controls_Manager::CODE,
			'attach_image'          => Elementor_Controls_Manager::MEDIA,
			'attach_images'         => Elementor_Controls_Manager::GALLERY,
			'vc_link'               => Elementor_Controls_Manager::URL,
			'vcex_font_size'        => Elementor_Controls_Manager::TEXT,
			'textfield'             => Elementor_Controls_Manager::TEXT,
			'text'                  => Elementor_Controls_Manager::TEXT,
			'vcex_text'             => Elementor_Controls_Manager::TEXT,
			'textarea_html'         => Elementor_Controls_Manager::WYSIWYG,
			'vcex_colorpicker'      => Elementor_Controls_Manager::COLOR,
			'colorpicker'           => Elementor_Controls_Manager::COLOR,
			'vcex_preset_textfield' => Elementor_Controls_Manager::SELECT,
			'dropdown'              => Elementor_Controls_Manager::SELECT,
			'switcher'              => Elementor_Controls_Manager::SWITCHER,
			'icon'                  => Elementor_Controls_Manager::ICONS,
		];
		return $controls[ $type ] ?? $type;
	}

	/**
	 * Get group params.
	 */
	protected function get_group_params( $group_type, $param_args ) {
		$group_args = [
			'id'   => '',
			'args' => [],
		];
		switch ( $group_type ) {
			case 'typography':
				if ( isset( $param_args['selector'] ) ) {
					$group_args['id']                 = \Elementor\Group_Control_Typography::get_type();
					$group_args['args']['selector'] = '{{WRAPPER}} ' . $param_args['selector'];
					$group_args['args']['label']    = $param_args['heading'] ?? $param_args['label'] ?? '';
					$group_args['args']['name']     = $param_args['param_name'] ?? $param_args['name'] ?? '';
				}
				break;
		}
		return $group_args;
	}

	/**
	 * Returns post type options.
	 */
	protected function choices_get_post_types() {
		$post_types_list = [];
		$post_types = \get_post_types( [
			'public' => true,
		] );
		if ( $post_types ) {
			foreach ( $post_types as $post_type ) {
				if ( 'revision' === $post_type
					|| 'nav_menu_item' === $post_type
					|| 'attachment' === $post_type
					|| 'elementor_library' === $post_type
				) {
					continue;
				}
				$post_types_list[$post_type] = \get_post_type_object( $post_type )->labels->name;
			}
		}
		return $post_types_list;
	}

}
