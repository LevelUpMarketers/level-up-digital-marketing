<?php

namespace TotalThemeCore\Cards;

\defined( 'ABSPATH' ) || exit;

/**
 * Card Builder.
 */
class Builder {

	/**
	 * Card builder post type name.
	 */
	public const POST_TYPE = 'wpex_card';

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Init.
	 */
	public static function init() {
		if ( ! \defined( '\TOTAL_THEME_ACTIVE' ) ) {
			return; // Cards are an exclusive Total Theme function.
		}

		if ( \is_admin() ) {
			\add_action( 'admin_init', [ self::class, 'on_admin_init' ] );
			if ( \class_exists( '\Vc_Manager', false ) ) {
				if ( self::POST_TYPE === totalthemecore_call_static( 'WPBakery\Helpers', 'get_admin_post_type' ) ) {
					\add_action( 'admin_init', [ self::class, 'enable_post_type_for_wpb' ], 1 ); // must use priority of 1
				}
				\add_action( 'init', [ self::class, 'vc_print_scripts' ], 11 );
			}
		}

		\add_action( 'init', [ self::class, 'register_post_type' ] );
		\add_filter( 'wpex_card_styles', [ self::class, 'filter_card_styles' ] );
	}

	/**
	 * Admin init.
	 */
	public static function on_admin_init() {
		\add_action( 'admin_head-post.php', [ self::class, 'add_back_button' ] );

		if ( \class_exists( '\WPEX_Meta_Factory' ) ) {
			new \WPEX_Meta_Factory( self::get_metabox_settings() );
		}
	}

	/**
	 * Returns array of cards.
	 */
	public static function get_custom_cards(): array {
		if ( ! \post_type_exists( self::POST_TYPE ) ) {
			return [];
		}

		$custom_cards = new \WP_Query( [
			'posts_per_page'   => '200',
			'orderby'          => 'date',
			'order'            => 'ASC',
			'post_type'        => self::POST_TYPE,
			'post_status'      => 'publish',
			'fields'           => 'ids',
			'suppress_filters' => false, // note: When set to true it causes all translated cards to show up in the dropdown.
		] );

		return $custom_cards->posts ?? [];
	}

	/**
	 * Returns parent menu.
	 */
	protected static function get_parent_menu() {
		if ( \defined( 'WPEX_THEME_PANEL_SLUG' ) && \current_user_can( 'edit_theme_options' ) ) {
			return \WPEX_THEME_PANEL_SLUG;
		}
		return 'tools.php';
	}

	/**
	 * Add a back button to the Font Manager main page.
	 */
	public static function add_back_button() {
		global $current_screen;

		if ( ! empty( $current_screen->post_type ) && self::POST_TYPE !== $current_screen->post_type ) {
			return;
		}

		wp_enqueue_script( 'jQuery' );

		?>

		<script>
			jQuery( function() {
				jQuery( 'body.post-type-<?php echo \sanitize_html_class( self::POST_TYPE ); ?> .wrap h1' ).append( '<a href="<?php echo \esc_url( \admin_url( 'edit.php?post_type=' . self::POST_TYPE ) ); ?>" class="page-title-action" style="margin-left:20px"><?php esc_html_e( 'All Custom Cards', 'total-theme-core' ); ?></a> <a href="https://totalwptheme.com/features/cards/" class="page-title-action" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View Preset Cards', 'total-theme-core' ); ?> &#8599;</a>' );
			} );
		</script>

		<?php
	}

	/**
	 * Register post type.
	 */
	public static function register_post_type() {
		$args = [
			'labels' => [
				'name' => \esc_html__( 'Custom Cards', 'total-theme-core' ),
				'singular_name' => \esc_html__( 'Card', 'total-theme-core' ),
				'add_new' => \esc_html__( 'Add New Card' , 'total-theme-core' ),
				'add_new_item' => \esc_html__( 'Add New Card' , 'total-theme-core' ),
				'edit_item' => \esc_html__( 'Edit Card' , 'total-theme-core' ),
				'new_item' => \esc_html__( 'New Card' , 'total-theme-core' ),
				'view_item' => \esc_html__( 'View Card', 'total-theme-core' ),
				'search_items' => \esc_html__( 'Search Cards', 'total-theme-core' ),
				'not_found' => \esc_html__( 'No Cards found', 'total-theme-core' ),
				'not_found_in_trash' => \esc_html__( 'No Cards found in Trash', 'total-theme-core' ),
			],
			'public' => false,
			'has_archive' => false,
			'query_var' => true,
			'_builtin' => false,
			'show_ui' => true,
			'show_in_rest' => true, // enable Gutenberg.
			'show_in_menu' => self::get_parent_menu(),
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => false,
			'exclude_from_search' => true, // !! important !!
			'publicly_queryable' => false,
			'capability_type' => 'page',
			'hierarchical' => false,
			'menu_position' => null,
			'rewrite' => false,
			'supports' => [
				'title',
				'editor',
			],
			'menu_position' => null,
		];

		if ( totalthemecore_call_static( 'Elementor\Helpers', 'is_cpt_in_frontend_mode', self::POST_TYPE )
			|| totalthemecore_call_static( 'WPBakery\Helpers', 'is_cpt_in_frontend_mode', self::POST_TYPE )
		) {
			$args['public']             = true;
			$args['publicly_queryable'] = true;
		}

		\register_post_type( self::POST_TYPE, $args );

		\add_post_type_support( self::POST_TYPE, 'elementor' );
	}

	/**
	 * Makes sure that you can edit wpex_cards with WPBakery even though it's a private type.
	 */
	public static function enable_post_type_for_wpb() {
		\add_filter( 'vc_role_access_with_post_types_get_state', '__return_true' );
		\add_filter( 'vc_role_access_with_backend_editor_get_state', '__return_true' );
		\add_filter( 'vc_role_access_with_frontend_editor_get_state', '__return_true' );
		\add_filter( 'vc_check_post_type_validation', '__return_true' );
		\add_filter( 'vc_is_valid_post_type_be', '__return_true' );
		\add_filter( 'vc_is_valid_post_type_fe', '__return_true' );
	}

	/**
	 * Hooks into various places to print CSS for hiding vc elements.
	 */
	public static function vc_print_scripts(): void {
		if ( \function_exists( 'vc_is_page_editable' ) && \vc_is_page_editable() ) {
			\add_action( 'wp_head', [ self::class, 'vc_hide_elements_css' ] );
		} else {
			\add_action( 'admin_print_scripts-post.php', [ self::class, 'vc_hide_elements_css' ], 1 );
			\add_action( 'admin_print_scripts-post-new.php', [ self::class, 'vc_hide_elements_css' ], 1 );
		}
	}

	/**
	 * Disables certain elements so they can't be used when building cards.
	 */
	public static function vc_hide_elements_css(): void {
		if ( self::POST_TYPE !== totalthemecore_call_static( 'WPBakery\Helpers', 'get_admin_post_type' ) ) {
			return;
		}

		$not_list = '';

		$shortcodes_whitelist = [
			// VC
			'vc_row',
		//  'vc_row_inner',
			'vc_column',
		//  'vc_column_inner',
			'vc_column_text',
			'vc_raw_html',

			// Total
			'vcex_flex_container',
			'vcex_grid_container',
			'vcex_heading',
			'vcex_image',
			'vcex_image_grid',
			'vcex_image_swap',
			'vcex_button',
			'vcex_divider',
			'vcex_spacing',
			'vcex_custom_field',
			'vcex_image_ba',
			'vcex_image_banner',
			'vcex_font_icon',
			'vcex_shortcode',
			'vcex_skillbar',
			'vcex_divider_dots',
			'vcex_divider_multicolor',
			'vcex_social_share',
			'vcex_author_bio',
			'vcex_icon',
			'vcex_icon_box',
			'vcex_video',
			'vcex_social_links',
			'vcex_milestone',

			// Post based,
			'vcex_post_meta',
			'vcex_post_content',
			'vcex_post_excerpt',
			'vcex_page_title',
			'vcex_post_media',
			'vcex_post_terms',
			'vcex_star_rating',

			// Theme
			'staff_social',

			// Vendor
			'vcex_woocommerce_template',
			'vcex_tribe_event_data',
			'vcex_just_events_date',
			'vcex_just_events_time',
		];

		/**
		 * Filters the shortcodes allowed for use with the Card Builder.
		 */
		$shortcodes_whitelist = (array) \apply_filters( 'wpex_card_builder_allowed_shortcodes_list', $shortcodes_whitelist );

		foreach ( $shortcodes_whitelist as $shortcode ) {
			$not_list .= ':not([data-element="' . \esc_attr( $shortcode ) . '"])';
		}

		echo "<style>#vc_post-settings-button,.vc_ui-add-element-header-container > .vc_ui-panel-header > .vc_ui-panel-header-content,.wpb-layout-element-button{$not_list}{display:none!important;}</style>";
	}

	/**
	 * Adds the custom cards to the cards list.
	 */
	public static function filter_card_styles( $styles ) {
		$custom_cards = self::get_custom_cards();

		if ( ! \is_array( $custom_cards ) || ! $custom_cards ) {
			return $styles;
		}

		$new_styles = [];

		foreach ( $custom_cards as $card_id ) {
			$card_name = \the_title_attribute( [
				'echo' => false,
				'post' => \get_post( $card_id ),
			] );
			if ( $card_name ) {
				$card_name = \sprintf( \esc_attr__( 'Custom Card: %1$s', 'total-theme-core' ), $card_name );
				$new_styles[ "template_{$card_id}" ] = [
					'name' => $card_name,
				];
			}
		}

		if ( $new_styles ) {
			$styles = \array_merge( $new_styles, $styles );
		}

		return $styles;
	}

	/**
	 * Returns metabox settings.
	 */
	protected static function get_metabox_settings(): array {
		return [
			'id'       => 'wpex-card',
			'title'    => \esc_html__( 'Card Settings', 'total-theme-core' ),
			'screen'   => [ self::POST_TYPE ],
			'context'  => 'advanced',
			'priority' => 'default',
			'fields'   => [ self::class, 'get_metabox_fields' ],
			'scripts'  => [
				[
					'totaltheme-cards-builder-metabox',
					\totalthemecore_get_js_file( 'admin/cards-builder-metabox' ),
					[],
					TTC_VERSION,
					true
				],
			],
		];
	}

	/**
	 * Returns the metabox fields.
	 */
	public static function get_metabox_fields(): array {
		$fields = [
			[
				'name' => \esc_html__( 'Frontend Editor Width', 'total-theme-core' ),
				'id'   => 'preview_width',
				'type' => 'text',
				'desc' => \esc_html__( 'Enter a custom width to contain your card while editing in front-end mode. Leave empty to use the default site width.', 'total-theme-core' ),
			],
			[
				'name' => \esc_html__( 'Extra class name', 'total-theme-core' ),
				'id'   => 'el_class',
				'type' => 'text',
				'desc' => \esc_html__( 'Add extra classes to the card element.', 'total-theme-core' ),
			],
		];

		if ( \is_callable( '\WPEX_Card::get_link_types' ) ) {
			$fields[] = [
				'name'    => \esc_html__( 'Link Type', 'total-theme-core' ),
				'id'      => 'link_type',
				'type'    => 'select',
				'choices' =>  \WPEX_Card::get_link_types(),
				'desc'    => \esc_html__( 'By default custom cards will have a link around the entire card so any links inside the card will be stripped out. Select the "none" link type if you wish to add your own links within the card.', 'total-theme-core' ),
			];
			$fields[] = [
				'name' => \esc_html__( 'Link Custom Field Name', 'total-theme-core' ),
				'id'   => 'link_custom_field',
				'type' => 'text',
				'desc' => \esc_html__( 'Enter the name of a custom field to use for your card link.', 'total-theme-core' ),
			];
		}

		return $fields;
	}

}
