<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPEX_Color_Palette' ) ) {

	/**
	 * WPEX Color Palette.
	 */
	final class WPEX_Color_Palette {

		/**
		 * Post type used to store the color palette.
		 */
		const POST_TYPE = 'wpex_color_palette';

		/**
		 * Is set to true as soon as the post type is registered.
		 */
		protected $is_post_type_registered = false;

		/**
		 * Check if we have queried registered colors.
		 */
		protected $get_registered_colors = false;

		/**
		 * Holds array of registered colors.
		 */
		protected $registered_colors = [];

		/**
		 * User capability that has access to the editor.
		 */
		protected $user_capability = 'edit_theme_options';

		/**
		 * Instance.
		 */
		private static $instance = null;

		/**
		 * Create or retrieve the instance of WPEX_Color_Palette.
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
			$this->global_hooks();
			if ( is_admin() ) {
				$this->admin_hooks();
			}
		}

		/**
		 * Global hooks.
		 */
		public function global_hooks() {
			add_action( 'init', [ $this, 'register_type' ] );
			add_filter( 'wpex_head_css', [ $this, 'head_css' ] );
		}

		/**
		 * Admin hooks.
		 */
		public function admin_hooks() {
			add_action( 'admin_head', [ $this, 'remove_admin_column_filter' ] );
			add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', [ $this, 'set_admin_columns' ] );
			add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', [ $this, 'show_admin_columns' ], 10, 2 );
			add_action( 'admin_head-post.php', [ $this, 'add_back_button' ] );
			add_action( 'admin_init', [ $this, 'register_metaboxes' ] );
			add_action( 'save_post_' . self::POST_TYPE, [ $this, '_on_save_post' ], 10, 3 );

			if ( class_exists( 'Vc_Manager' ) ) {
				add_filter( 'vc_is_valid_post_type_be', [ $this, 'disable_wpbakery' ], 10, 2 );
				add_filter( 'vc_show_button_fe', [ $this, 'remove_wpbakery_button_fe' ], 10, 3 );
			}

			if ( ! class_exists( 'TotalTheme\Color_Palette', true ) ) {
				add_filter( 'enqueue_block_assets', [ $this, 'gutenberg_editor_css' ] );
			}
		}

		/**
		 * Register wpex_color_palette type.
		 */
		public function register_type() {
			register_post_type( self::POST_TYPE, [
				'labels' => [
					'name'               => esc_html__( 'Color Palette', 'total-theme-core' ),
					'singular_name'      => esc_html__( 'Color', 'total-theme-core' ),
					'add_new'            => esc_html__( 'Add Color' , 'total-theme-core' ),
					'add_new_item'       => esc_html__( 'Add Color' , 'total-theme-core' ),
					'edit_item'          => esc_html__( 'Edit Color' , 'total-theme-core' ),
					'new_item'           => esc_html__( 'Color' , 'total-theme-core' ),
					'view_item'          => esc_html__( 'View Color', 'total-theme-core' ),
					'search_items'       => esc_html__( 'Search Colors', 'total-theme-core' ),
					'not_found'          => esc_html__( 'No Colors found', 'total-theme-core' ),
					'not_found_in_trash' => esc_html__( 'No Colors found in Trash', 'total-theme-core' ),
				],
				'public'          => false,
				'show_ui'         => true,
				'_builtin'        => false,
				'capability_type' => 'page',
				'hierarchical'    => false,
				'rewrite'         => false,
				'supports'        => [ 'title' ],
				'show_in_menu'    => defined( 'WPEX_THEME_PANEL_SLUG' ) ? WPEX_THEME_PANEL_SLUG : 'themes.php',
				// Admins only
				'capabilities' => [
					'edit_post'          => $this->user_capability,
					'read_post'          => $this->user_capability,
					'delete_post'        => $this->user_capability,
					'edit_posts'         => $this->user_capability,
					'edit_others_posts'  => $this->user_capability,
					'delete_posts'       => $this->user_capability,
					'publish_posts'      => $this->user_capability,
					'read_private_posts' => $this->user_capability,
				],
			] );
			$this->is_post_type_registered = true;
		}

		/**
		 * Remove the admin columns sort filter.
		 */
		public function remove_admin_column_filter() {
			$screen = get_current_screen();

			if ( self::POST_TYPE == $screen->post_type ) {
				add_filter( 'months_dropdown_results', '__return_empty_array' );
			}
		}

		/**
		 * Set admin columns.
		 */
		public function set_admin_columns( $columns ) {
			$columns['description'] = esc_html__( 'Description', 'total-theme-core' );
			$columns['color']       = esc_html__( 'Color', 'total-theme-core' );
			$columns['hex']         = esc_html__( 'Value', 'total-theme-core' );
			$columns['color_class'] = esc_html__( 'Color Class', 'total-theme-core' );
			$columns['bg_class']    = esc_html__( 'Background Class', 'total-theme-core' );
			$columns['css_var']     = esc_html__( 'CSS Variable', 'total-theme-core' );
			unset( $columns['date'] );
			return $columns;
		}

		/**
		 * Show admin columns.
		 */
		public function show_admin_columns( $column, $post_id ) {
			switch ( $column ) {
				case 'description':
					$description = esc_html( (string) get_post_meta( $post_id, 'description', true ) );
					if ( $description_safe = $description ) {
						echo "<i>{$description_safe}</i>";
					}
				break;
				case 'color':
					$color_safe = esc_attr( (string) get_post_meta( $post_id, 'color', true ) );
					if ( $color_safe ) {
						echo "<span style='position:relative;z-index:2;display:inline-block;background:{$color_safe};height:32px;width:32px;border-radius:32px;box-shadow:inset 0 0 0 1px rgba(0, 0, 0, 0.2);'></span>";
						if ( function_exists( 'totaltheme_call_static' ) && (bool) totaltheme_call_static( 'Dark_Mode', 'is_enabled' ) ) {
							$color_dark = (string) get_post_meta( $post_id, 'color_dark', true );
							if ( str_starts_with( $color_dark, 'palette-' ) ) {
								$color_dark = self::get_colors_list()[ $color_dark ]['color'] ?? $color_dark;
							}
							if ( $color_dark_safe = esc_attr( $color_dark ) ) {
								echo "<span style='display:inline-block;margin-left:-16px;background:{$color_dark_safe};height:32px;width:32px;border-radius:32px;box-shadow:inset 0 0 0 1px rgba(0, 0, 0, 0.2);'></span>";
							}
						}
					}
				break;
				case 'hex':
					$color_safe = esc_html( (string) get_post_meta( $post_id, 'color', true ) );
					if ( $color_safe ) {
						echo "<p style='margin:0;'><code>{$color_safe}</code></p>";
						if ( function_exists( 'totaltheme_call_static' ) && (bool) totaltheme_call_static( 'Dark_Mode', 'is_enabled' ) ) {
							$color_dark = (string) get_post_meta( $post_id, 'color_dark', true );
							if ( $color_dark_safe = esc_attr( $color_dark ) ) {
								echo "<p style='margin:10px 0 0;'><code>{$color_dark_safe}</code></p>";
							}
						}
					}
				break;
				case 'color_class':
					$slug_safe = esc_attr( (string) get_post_meta( $post_id, 'slug', true ) ) ?: '';
					echo "<input style='max-width:100%' type='text' onClick='this.select();'' value='has-{$slug_safe}-color'></p>";
					break;
				case 'bg_class':
					$slug_safe = esc_attr( (string) get_post_meta( $post_id, 'slug', true ) ) ?: '';
					echo "<input style='max-width:100%' type='text' onClick='this.select();'' value='has-{$slug_safe}-background-color'></p>";
					break;
				case 'css_var':
					$slug_safe = esc_attr( (string) get_post_meta( $post_id, 'slug', true ) ) ?: '';
					echo "<input style='max-width:100%' type='text' onClick='this.select();'' value='var(--wpex-{$slug_safe}-color)'></p>";
					break;
				case 'slug':
					$slug_safe = esc_html( (string) get_post_meta( $post_id, 'slug', true ) );
					if ( $slug ) {
						echo "<code>{$slug_safe}</code>";
					}
				break;
			}
		}

		/**
		 * Add a back button to the Color Palette admin page.
		 */
		public function add_back_button() {
			global $current_screen;

			if ( self::POST_TYPE !== $current_screen->post_type ) {
				return;
			}

			wp_enqueue_script( 'jQuery' );

			?>

			<script>
				jQuery( function() {
					jQuery( 'body.post-type-wpex_color_palette .wrap h1' ).append( '<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wpex_color_palette' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Color Palette', 'total-theme-core' ); ?></a>' );
				} );
			</script>
			<?php
		}

		/**
		 * Register metaboxes.
		 */
		public function register_metaboxes() {
			if ( class_exists( 'WPEX_Meta_Factory' ) ) {
				new WPEX_Meta_Factory( $this->metabox() );
			}
		}

		/**
		 * Save slug.
		 */
		public function _on_save_post( $post_id, $post, $update ) {
			if ( self::POST_TYPE !== $post->post_type || ! current_user_can( $this->user_capability, $post_id ) ) {
				return;
			}

			$slug = get_post_meta( $post_id, 'slug', true );

			if ( empty( $slug ) ) {
				$post_id_safe = trim( absint( $post_id ) );
				$slug = "palette-{$post_id_safe}";
				update_post_meta( $post_id, 'slug', $slug );
			}
		}

		/**
		 * General Metabox.
		 */
		protected function metabox(): array {
			return [
				'id'       => 'general',
				'title'    => esc_html__( 'Settings', 'total-theme-core' ),
				'screen'   => [ self::POST_TYPE ],
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => [ self::class, 'get_metabox_fields' ],
			];
		}

		/**
		 * Returns Metabox fields.
		 */
		public static function get_metabox_fields(): array {
			$fields = [];

			$fields[] = [
				'name'         => esc_html__( 'Color', 'total-theme-core' ),
				'id'           => 'color',
				'type'         => 'color',
				'allow_global' => false,
			];

			if ( function_exists( 'totaltheme_call_static' ) && (bool) totaltheme_call_static( 'Dark_Mode', 'is_enabled' ) ) {
				$fields[] = [
					'name'         => esc_html__( 'Dark Mode: Color', 'total-theme-core' ),
					'desc'         => esc_html__( 'Alternative color for use "Dark Mode".', 'total-theme-core' ),
					'id'           => 'color_dark',
					'type'         => 'color',
					'allow_global' => false,
				];
			}

			$fields[] = [
				'name' => esc_html__( 'Description', 'total-theme-core' ),
				'desc' => esc_html__( 'A useful description for your own referrance.', 'total-theme-core' ),
				'id'   => 'description',
				'type' => 'textarea',
			];
			
			return $fields;
		}

		/**
		 * Conditional check to see if we have registered colors.
		 */
		public function has_registered_colors(): bool {
			return ! empty( $this->get_registered_colors() );
		}

		/**
		 * Return all registered colors.
		 */
		public function get_registered_colors(): array {
			if ( $this->get_registered_colors && $this->is_post_type_registered ) {
				return $this->registered_colors;
			}

			$dark_mode_enabled = function_exists( 'totaltheme_call_static' ) && (bool) totaltheme_call_static( 'Dark_Mode', 'is_enabled' );

			$colors = (array) get_posts( [
				'numberposts' 	   => 50,
				'post_type' 	   => 'wpex_color_palette',
				'post_status'      => 'publish',
				'suppress_filters' => false,
				'fields'           => 'ids',
			] );

			foreach ( $colors as $post_id ) {
				$name  = get_the_title( $post_id );
				$color = get_post_meta( $post_id, 'color', true );
				$slug  = get_post_meta( $post_id, 'slug', true );
				$desc  = get_post_meta( $post_id, 'description', true );
				if ( $name && $color && $slug ) {
					$color_params = [
						'name'        => sanitize_text_field( $name ),
						'css_var'     => "--wpex-palette-{$post_id}-color",
						'color'       => sanitize_text_field( $color ),
						'slug'        => sanitize_text_field( $slug ),
						'description' => sanitize_text_field( $desc ?: $slug ),
					];
					if ( $dark_mode_enabled && $color_dark = get_post_meta( $post_id, 'color_dark', true ) ) {
						$color_params['color_dark'] = sanitize_text_field( $color_dark );
					}
					$this->registered_colors[] = $color_params;
				}
			}

			$this->get_registered_colors = true;

			return $this->registered_colors;
		}

		/**
		 * Return color palette colors list as an array.
		 */
		public static function get_colors_list(): array {
			$colors = [];
			foreach ( self::instance()->get_registered_colors() as $color ) {
				$colors[ $color['slug'] ] = $color;
			}
			return (array) apply_filters( 'wpex_color_palette', $colors );
		}

		/**
		 * Disable wpbakery builder from post type.
		 */
		public function disable_wpbakery( $check, $type ) {
			if ( self::POST_TYPE === $type ) {
				return false;
			}
			return $check;
		}

		/**
		 * Removes the edit with wpbakery button from the admin screen.
		 */
		public function remove_wpbakery_button_fe( $result, $post_id, $type ) {
			if ( self::POST_TYPE === $type ) {
				return false;
			}
			return $result;
		}

		/**
		 * Returns color palette CSS.
		 */
		protected function get_css() {
			$palette_css   = '';
			$root_css      = '';
			$dark_mode_css = '';

			foreach ( self::get_colors_list() as $slug => $color ) {
				$slug_escaped = sanitize_html_class( $slug );
				$color_escaped = esc_attr( $color['color'] );
				$css_property_value = $color_escaped;

				// Add css property.
				$root_css .= "--wpex-{$slug_escaped}-color:{$color_escaped};";
				$css_property_value = "var(--wpex-{$slug_escaped}-color)";

				// Bg color class.
				$palette_css .= ".has-{$slug_escaped}-background-color,.wp-block-button__link.has-{$slug_escaped}-background-color{background-color:{$css_property_value};}";

				// Border color class.
				$palette_css .= ".has-{$slug_escaped}-border-color,.wp-block-button__link.has-{$slug_escaped}-border-color{border-color:{$css_property_value};}";

				// Text color class.
				$palette_css .= ".has-{$slug_escaped}-color,.wp-block-button__link.has-{$slug_escaped}-color{color:{$css_property_value};}";

				// Dark mode color.
				if ( ! empty( $color['color_dark'] )
					&& $color_dark_escaped = esc_attr( $color['color_dark'] )
				) {
					$dark_mode_css .= "--wpex-{$slug_escaped}-color:{$color_dark_escaped};";
				}

			}

			if ( $root_css ) {
				$palette_css = ":root{{$root_css}}{$palette_css}";
			}
			
			if ( $dark_mode_css ) {
				$palette_css .= ".wpex-dark-mode{{$dark_mode_css}}";
			}

			return (string) apply_filters( 'wpex_color_palette_head_css', $palette_css );
		}

		/**
		 * Generate and output CSS on the front-end for each color.
		 */
		public function gutenberg_editor_css() {
			if ( $css = $this->get_css() ) {
				wp_register_style( 'wpex-color-palette', false, [], true, true );
				wp_add_inline_style( 'wpex-color-palette', $css );
				wp_enqueue_style( 'wpex-color-palette' );
			}
		}

		/**
		 * Generate and output CSS on the front-end for each color.
		 */
		public function head_css( $css ) {
			if ( $palette_css = $this->get_css() ) {
				$css .= "/*COLOR PALETTE*/{$palette_css}";
			}
			return $css;
		}

	}

	WPEX_Color_Palette::instance();

}
