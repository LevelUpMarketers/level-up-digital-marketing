<?php

namespace TotalThemeCore\Meta;

\defined( 'ABSPATH' ) || exit;

/**
 * Class for adding term meta settings.
 */
class Term_Settings {

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Add hooks.
	 */
	public static function init() {
		\add_action( 'admin_init', [ self::class, 'on_admin_init' ], 40 );
	}

	/**
	 * Checks if we should add default fields.
	 */
	protected static function add_default_fields_check() {
		return \get_theme_mod( 'term_meta_enable', true );
	}

	/**
	 * Array of meta options.
	 */
	protected static function get_options( $taxonomy = '' ): array {
		if ( self::add_default_fields_check() ) {
			$options = [
				// Card style
				'wpex_entry_card_style' => [
					'label' => \esc_html__( 'Entry Card Style', 'total-theme-core' ),
					'type' => 'select',
					'choices' => 'wpex_choices_card_styles',
					'args' => [
						'type' => 'string',
						'single' => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
				// Redirect
				'wpex_redirect' => [
					'label' => \esc_html__( 'Redirect', 'total-theme-core' ),
					'type' => 'wp_dropdown_pages',
					'args' => [
						'type' => 'integer',
						'single' => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
				// Sidebar select
				'wpex_sidebar' => [
					'label' => \esc_html__( 'Sidebar', 'total-theme-core' ),
					'type' => 'select',
					'choices'  => 'wpex_choices_widget_areas',
					'args' => [
						'type' => 'string',
						'single' => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			];
		} else {
			$options = [];
		}

		/**
		 * Filters the term meta options array.
		 *
		 * @param array $options.
		 * @param string $taxonomy.
		 */
		$options = \apply_filters( 'wpex_term_meta_options', $options, $taxonomy );

		return (array) $options;
	}

	/**
	 * Parses meta options.
	 */
	protected static function parse_options( $options, $taxonomy ): array {
		foreach ( $options as $k => $v ) {
			if ( ! self::maybe_add_option_to_taxonomy( $v, $taxonomy ) ) {
				unset( $options[ $k ] );
			}
		}
		return (array) $options;
	}

	/**
	 * Add meta form fields.
	 */
	public static function on_admin_init() {
		$taxonomies = (array) \apply_filters( 'wpex_term_meta_taxonomies', get_taxonomies( [
			'public' => true,
		] ) );

		if ( ! $taxonomies ) {
			return;
		}

		foreach ( $taxonomies as $taxonomy ) {
			\add_action( "{$taxonomy}_add_form_fields", [ self::class, 'add_form_fields' ] );
			\add_action( "{$taxonomy}_edit_form", [ self::class, 'edit_form_fields' ] );
			\add_action( "created_{$taxonomy}", [ self::class, 'save_forms' ] );
			\add_action( "edited_{$taxonomy}", [ self::class, 'save_forms' ] );
			\add_filter( "manage_edit-{$taxonomy}_columns", [ self::class, 'admin_columns' ] );
			\add_filter( "manage_{$taxonomy}_custom_column", [ self::class, 'admin_column' ], 10, 3 );
		}
	}

	/**
	 * Adds new category fields.
	 */
	public static function add_form_fields( $taxonomy ) {
		$has_fields = false;

		// Get term options.
		$meta_options = self::get_options( $taxonomy );

		// Make sure options aren't empty/disabled.
		if ( ! empty( $meta_options ) && \is_array( $meta_options ) ) {

			// Loop through options.
			foreach ( $meta_options as $key => $val ) {

				if ( empty( $val['show_on_create'] ) ) {
					continue;
				}

				if ( false === $has_fields ) {
					$has_fields = true;
				}

				$label = $val['label'] ?? '';

				if ( ! self::maybe_add_option_to_taxonomy( $val, $taxonomy ) ) {
					continue;
				}

				?>

				<div class="form-field">
					<label for="<?php echo \esc_attr( $key ); ?>"><?php echo \esc_html( $label ); ?></label>
					<?php self::meta_form_field( $key, $val, '' ); ?>
				</div>

			<?php }

			// Add security nonce only if fields are to be added.
			if ( $has_fields ) {
				\wp_nonce_field( 'wpex_term_meta_nonce', 'wpex_term_meta_nonce' );
			}

		}
	}

	/**
	 * Enqueue scripts.
	 */
	protected static function enqueue_scripts() {
		\wp_enqueue_style(
			'totalthemecore-admin-term-settings',
			\totalthemecore_get_css_file( 'admin/term-settings' ),
			false,
			TTC_VERSION
		);
		if ( \wp_script_is( 'totaltheme-components', 'registered' ) ) {
			wp_enqueue_script( 'totaltheme-components' );
			wp_enqueue_style( 'totaltheme-components' );
		}
	}

	/**
	 * Adds new category fields.
	 */
	public static function edit_form_fields( $term ) {
		$taxonomy     = $term->taxonomy;
		$meta_options = self::parse_options( self::get_options(), $taxonomy );

		if ( ! $meta_options
			&& ! \has_action( "wpex_{$taxonomy}_form_fields_top" ) 
			&& ! \has_action( "wpex_{$taxonomy}_form_fields_bottom" )
		) {
			return;
		}

		self::enqueue_scripts();
		?>

		<div class="postbox wpex-term-settings-postbox">
			<div class="postbox-header">
				<h2 style="font-size:14px;"><?php \esc_html_e( 'Theme Settings', 'total-theme-core' ); ?></h2>
			</div>
			<div class="inside">
				<table class="form-table"><?php
				 	\do_action( "wpex_{$taxonomy}_form_fields_top", $term );
					\do_action( 'wpex_term_meta_options_form_fields_top', $term ); // @deprecated since 2.0
					foreach ( $meta_options as $key => $val ) {
						?>
						<tr class="form-field">
							<th scope="row" valign="top"><?php self::meta_form_label( $key, $val, $term ); ?></th>
							<td><?php self::meta_form_field( $key, $val, $term ); ?></td>
						</tr>
						<?php
					}
					\do_action( "wpex_{$taxonomy}_form_fields_bottom", $term );
					\do_action( 'wpex_term_meta_options_form_fields_bottom', $term ); // @deprecated since 2.0
					\wp_nonce_field( 'wpex_term_meta_nonce', 'wpex_term_meta_nonce' );
				?></table>
			</div>
		</div>

		<?php
	}

	/**
	 * Saves meta fields.
	 */
	public static function save_forms( $term_id ) {
		if ( ! \array_key_exists( 'wpex_term_meta_nonce', $_POST )
			|| ! \array_key_exists( 'wpex_term_settings', $_POST )
			|| ! \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['wpex_term_meta_nonce'] ) ), 'wpex_term_meta_nonce' )
		) {
			return;
		}

		$post_data = $_POST['wpex_term_settings'];

		// Get options.
		$meta_options = self::get_options();

		// Make sure options aren't empty/disabled.
		if ( ! empty( $meta_options ) && is_array( $meta_options ) ) {

			// Loop through options.
			foreach ( $meta_options as $key => $val ) {

				/**
				 * Skip any option that isn't in $post_data.
				 * this way we aren't deleting meta that could be temporarily hidden.
				 */
				if ( ! \array_key_exists( $key, $post_data ) ) {
					continue;
				}

				// Check option value.
				$value = $post_data[ $key ];

				// Save setting.
				if ( $value ) {
					if ( isset( $val['args']['sanitize_callback'] ) && \is_callable( $val['args']['sanitize_callback'] ) ) {
						$safe_value = \call_user_func( $val['args']['sanitize_callback'], $value );
					} else {
						$safe_value = \sanitize_text_field( $value );
					}
					\update_term_meta( $term_id, $key, $safe_value );
				}

				// Delete setting.
				else {
					\delete_term_meta( $term_id, $key );
				}

			}

		}
	}

	/**
	 * Add new admin columns for specific fields.
	 */
	public static function admin_columns( $columns ) {
		$meta_options = self::get_options();
		if ( ! empty( $meta_options ) && \is_array( $meta_options ) ) {
			foreach ( $meta_options as $key => $option ) {
				if ( ! empty( $option['has_admin_col'] ) ) {
					if ( isset( $option['taxonomies'] ) && \is_array( $option['taxonomies'] ) ) {
						$current_tax = get_current_screen()->taxonomy ?? '';
						if ( $current_tax && is_string( $current_tax ) && ! in_array( $current_tax, $option['taxonomies'] ) ) {
							continue;
						}
					}
					$columns[ $key ] = \esc_html( $option['label'] );
				}
			}
		}
		return $columns;
	}

	/**
	 * Display certain field vals in admin columns.
	 */
	public static function admin_column( $columns, $column, $term_id ) {
		$meta_options = self::get_options();
		if ( ! empty( $meta_options[ $column ] ) && ! empty( $meta_options[ $column ]['has_admin_col'] ) ) {
			$value = \get_term_meta( $term_id, $column, true );
			if ( $value ) {
				$field_type = $meta_options[ $column ]['type'];
				switch ( $field_type ) {
					case 'color':
						if ( $value && \is_string( $value ) ) {
							$value = sanitize_text_field( $value );
							if ( \str_starts_with( $value, 'palette-' ) ) {
								$value = "var(--wpex-{$value}-color)";
							}
							$columns .= '<span style="background:' . \esc_attr( $value ) . ';width:20px;height:20px;display:inline-block;border-radius:20px;box-shadow:inset 0 0 0 1px rgba(0, 0, 0, 0.2);"></span>';
						}
						break;
					default:
						$columns .= \esc_html( $value );
						break;
				}
			} else {
				$columns .= '&#8212;';
			}
		}
		return $columns;
	}

	/**
	 * Outputs the form field.
	 */
	private static function meta_form_label(  $key, $val, $term = '' ) {
		$label      = $val['label'] ?? '';
		$field_key  = \sanitize_key( $key );
		$field_name = "wpex_term_settings[{$field_key}]";
		$field_id   = "wpex_term_setting-{$field_key}";
		?>
		<label for="<?php echo \esc_attr( $field_id ); ?>"><?php echo \esc_html( $label ); ?></label>
	<?php }

	/**
	 * Outputs the form field.
	 */
	private static function meta_form_field( $key, $val, $term = '' ) {
		$type       = $val['type'] ?? 'text';
		$field_key  = \sanitize_key( $key );
		$term_id    = ( ! empty( $term ) && \is_object( $term ) ) ? $term->term_id : '';
		$value      = \get_term_meta( $term_id, $field_key, true );
		$field_name = "wpex_term_settings[{$field_key}]";
		$field_id   = "wpex_term_setting-{$field_key}";

		switch ( $type ) {
			case 'select':
				$choices = ! empty( $val['choices'] ) ? $val['choices'] : false;
				if ( $choices ) {
					if ( \is_string( $choices ) && \function_exists( $choices ) ) {
						$choices = \call_user_func( $choices );
					}
					$select_has_value = false;
					?>
					<select id="<?php echo \esc_attr( $field_id ); ?>" name="<?php echo \esc_attr( $field_name ); ?>">
						<?php foreach ( $choices as $key => $val ) :
							if ( $value && ! $select_has_value && $value === $key ) {
								$select_has_value = true;
							}
							?>
							<option value="<?php echo \esc_attr( $key ); ?>" <?php \selected( $value, $key ) ?>><?php echo \esc_html( $val ); ?></option>
						<?php endforeach; ?>
						<?php
						// Fix for dynamic template selects if an option was selected that isn't part of the choices.
						if ( $value
							&& \is_numeric( $value )
							&& \in_array( $field_key, [ 'wpex_archive_template', 'wpex_post_template' ] )
							&& 'publish' === \get_post_status( $value )
							&& ! $select_has_value
						) { ?>
							<option value="<?php echo \esc_attr( $value ); ?>" selected="selected"><?php echo \esc_html( \get_the_title( $value ) ); ?></option>
						<?php } ?>
					</select>
				<?php
				}
				break;
			case 'template_select':
				// @todo implement.
				break;
			case 'color':
				if ( \function_exists( '\totaltheme_component' ) ) {
					\totaltheme_component( 'color', [
						'id'           => $field_id,
						'input_name'   => $field_name,
						'value'        => $value,
						'allow_global' => true,
						'exclude'      => $val['exclude'] ?? '',
						'include'      => $val['include'] ?? '',
					] );
				} else {
					\wp_enqueue_style( 'wp-color-picker' );
					\wp_enqueue_script( 'wp-color-picker' );

					\wp_enqueue_script(
						'totalthemecore-module-color-picker-field',
						\totalthemecore_get_js_file( 'module/color-picker-field' ),
						[ 'jquery', 'wp-color-picker' ],
						true
					);
					?>
						<input id="<?php echo \esc_attr( $field_id ); ?>" type="text" name="<?php echo \esc_attr( $field_name ); ?>" value="<?php echo \esc_attr( $value ); ?>" class="wpex-color-field">
					<?php
				}
				break;
			case 'wp_dropdown_pages':
				\wp_dropdown_pages( [
					'id'               => $field_id,
					'name'             => $field_name,
					'selected'         => $value,
					'show_option_none' => \esc_html__( 'None', 'total-theme-core' )
				] );
				break;
			case 'text':
			default: ?>
				<input id="<?php echo \esc_attr( $field_id ); ?>" type="text" name="<?php echo \esc_attr( $field_name ); ?>" value="<?php echo \esc_attr( $value ); ?>"></td>
			<?php
			break;

		} // end switch type.
	}

	/**
	 * Checks if a specific option should be added to the taxonomy.
	 */
	private static function maybe_add_option_to_taxonomy( $option, $taxonomy ): bool {
		return empty( $option['taxonomies'] ) || ! \is_array( $option['taxonomies'] ) || \in_array( $taxonomy, $option['taxonomies'], true );
	}

}
