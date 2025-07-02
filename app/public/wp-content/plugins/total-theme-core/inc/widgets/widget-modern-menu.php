<?php

namespace TotalThemeCore\Widgets;

defined( 'ABSPATH' ) || exit;

/**
 * Modern Menu widget.
 */
class Widget_Modern_Menu extends \TotalThemeCore\WidgetBuilder {

	/**
	 * Widget args.
	 */
	private $args;

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		$this->args = [
			'id_base' => 'wpex_modern_menu',
			'name'    => $this->branding() . \esc_html__( 'Modern Sidebar Menu', 'total-theme-core' ),
			'options' => [
				'customize_selective_refresh' => true,
			],
			'fields'  => [
				[
					'id'    => 'title',
					'label' => \esc_html__( 'Title', 'total-theme-core' ),
					'type'  => 'text',
				],
				[
					'id'      => 'nav_menu',
					'label'   => \esc_html__( 'Select Menu', 'total-theme-core' ),
					'type'    => 'select',
					'choices' => 'menus',
				],
			],
		];

		$this->create_widget( $this->args );
	}

	/**
	 * Front-end display of widget.
	 */
	public function widget( $args, $instance ) {
		\extract( $this->parse_instance( $instance ) );

		echo \wp_kses_post( $args['before_widget'] );

		$this->widget_title( $args, $instance );

		if ( $nav_menu ) {

			$menu_args = [
				'menu_class'  => 'modern-menu-widget wpex-m-0 wpex-border wpex-border-solid wpex-border-main wpex-rounded-sm wpex-last-border-none wpex-overflow-hidden',
				'fallback_cb' => '',
				'menu'        => $nav_menu,
			];

			if ( \function_exists( '\totaltheme_get_icon' ) ) {
				$link_before = '<span class="modern-menu-widget__link-text">';
				$link_after  = '</span>';

				$link_arrow = (string) \apply_filters( 'wpex_modern_menu_widget_link_icon', 'material-arrow-back-ios' );

				if ( $link_arrow ) {
					$icon_class = 'modern-menu-widget__link-icon';
					$layout = \function_exists( 'wpex_content_area_layout' ) ? \wpex_content_area_layout() : 'right-sidebar';

					if ( 'left-sidebar' === $layout ) {
						$icon_class .= ' wpex-ml-auto';
						if ( ! is_rtl() ) {
							$icon_class .= ' wpex-rotate-180';
						}
						$link_after = $link_after . \totaltheme_get_icon( $link_arrow, $icon_class );
					} else {
						$link_before = \totaltheme_get_icon( $link_arrow, $icon_class ) . $link_before;
					}
				}

				$menu_args['link_before'] = $link_before;
				$menu_args['link_after'] = $link_after;
			}

			// Add filters to modify the menu to add theme utility classes.
			\add_filter( 'nav_menu_css_class', [ $this, 'nav_menu_li_classes' ], 10, 4 );
			\add_filter( 'nav_menu_link_attributes', [ $this, 'nav_menu_link_attributes' ], 10, 4 );
			\add_filter( 'nav_menu_submenu_css_class', [ $this, 'nav_menu_submenu_css_class' ], 10, 3 );

			ob_start();
				\wp_nav_menu( $menu_args );
			echo ob_get_clean();

			// Remove filters that modify the menu to add theme utility classes.
			\remove_filter( 'nav_menu_css_class', [ $this, 'nav_menu_li_classes' ], 10, 4 );
			\remove_filter( 'nav_menu_link_attributes', [ $this, 'nav_menu_link_attributes' ], 10, 4 );
			\remove_filter( 'nav_menu_submenu_css_class', [ $this, 'nav_menu_submenu_css_class' ], 10, 3 );

		}

		echo \wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Modify the menu li attributes.
	 */
	public function nav_menu_li_classes( $classes, $menu_item, $args, $depth ) {
		$classes[] = 'wpex-border-b';
		$classes[] = 'wpex-border-solid';
		$classes[] = 'wpex-border-main';
		return $classes;
	}

	/**
	 * Modify the menu link attributes.
	 */
	public function nav_menu_link_attributes( $atts, $menu_item, $args, $depth ) {
		$class = [
			'wpex-flex',
			'wpex-gap-10',
			'wpex-items-center',
			'wpex-relative',
			'wpex-no-underline',
			'wpex-text-3',
			'wpex-duration-150',
			// We use ems in the CSS instead - @todo
		//	'wpex-py-5',
		//	'wpex-px-10',
		];

		if ( isset( $menu_item->current ) && (bool) $menu_item->current ) {
			$class[] = 'wpex-bg-accent';
			$class[] = 'wpex-on-accent';
		//	$clas[]  = '-wpex-mx-1'; // removed because it causes the item to shift and it's not really needed.
		} else {
			$class[] = 'wpex-hover-surface-2';
			$class[] = 'wpex-hover-text-3';
		}

		$class_string = implode( ' ', $class );

		if ( isset( $atts['class'] ) && is_scalar( $atts['class'] ) ) {
			$atts['class'] .= " {$class_string}";
		} else {
			$atts['class'] = $class_string;
		}

		return $atts;
	}

	/**
	 * Modify the submenu classes.
	 */
	public function nav_menu_submenu_css_class( $classes, $args, $depth ) {
		$classes[] = 'wpex-border-t';
		$classes[] = 'wpex-border-solid';
		$classes[] = 'wpex-border-main';
		$classes[] = 'wpex-last-border-none';
		return $classes;
	}

}
register_widget( 'TotalThemeCore\Widgets\Widget_Modern_Menu' );
