<?php

namespace TotalThemeCore\Vcex\Walkers;

\defined( 'ABSPATH' ) || exit;

/**
 * Custom walker used to create mobile menus.
 */
class Nav_Menu_Off_Canvas extends \Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$class = 'vcex-off-canvas-menu-nav__sub wpex-list-none';

		if ( $args->vcex_atts['nav_centered'] ) {
			$class .= ' wpex-self-justify-center';
		}

		if ( $args->vcex_atts['nav_centered'] || $args->vcex_atts['sub_border_enable'] ) {
			if ( $args->vcex_atts['item_divider'] ) {
				$class .= ' wpex-mt-0 wpex-mx-0 wpex-mb-15';
			} else {
				$class .= ' wpex-m-0';
			}
		} else {
			if ( 0 === $depth ) {
				$side_margin = ! empty( $args->vcex_atts['sub_margin_start'] ) ? absint( $args->vcex_atts['sub_margin_start'] ) : 15;
			} else {
				$side_margin = ! empty( $args->vcex_atts['sub_sub_margin_start'] ) ? absint( $args->vcex_atts['sub_sub_margin_start'] ) : 15;
			}
			if ( $args->vcex_atts['item_divider'] ) {
				$class .= ' wpex-mt-0 wpex-mb-15';
			} else {
				$class .= ' wpex-my-0';
			}
			$class .= " wpex-mr-0 wpex-ml-{$side_margin}";
		}

		if ( $args->vcex_atts['sub_border_enable'] && ! $args->vcex_atts['nav_centered'] ) {
			$class .= ' wpex-border-l-2 wpex-border-solid wpex-border-surface-3 wpex-pl-20';
		}

		$atts = [];
		$atts['class'] = $class;
		$attributes = $this->build_atts( $atts );

		$output .= "<ul{$attributes}>";
	}

	/**
	 * Ends the list of after the elements are added.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul>';
		if ( 0 === $depth ) {
			$output .= '</details>';
		}
	}

	/**
	 * Starts the element output.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		// Restores the more descriptive, specific name for use within this method
		$item = $data_object;

		// Checks if the menu item has a description
		$has_description = ! empty( $item->description );

		// Check if menu item has a link
		$has_link = ! empty( $item->url ) && '#' !== $item->url;

		// Check if has details
		$has_details = 0 === $depth && ! empty( $this->has_children ) && ! $args->vcex_atts['sub_expanded'];

		// Get menu item icon
		$icon = ( $icon = \get_post_meta( $item->ID, '_menu_item_totaltheme_icon', true ) ) ? \sanitize_text_field( $icon ) : '';
		if ( $icon ) {
			$icon_class = 'vcex-off-canvas-menu-nav__icon wpex-flex-shrink-0 wpex-icon--w';
			if ( $args->vcex_atts['item_transition_duration'] ) {
				$icon_class .= " wpex-transition-{$args->vcex_atts['item_transition_duration']}";
			}
			$item_icon_html = \vcex_get_theme_icon_html( $icon, $icon_class );
		}

		// Li classes
		$classes = empty( $item->classes ) ? [] : (array) $item->classes;
		$classes[] = \esc_attr( "menu-item-{$item->ID}" );
		$classes = $this->parse_menu_item_classes( $classes, $args, $depth);

		if ( $args->vcex_atts['nav_centered'] ) {
			$classes[] = 'wpex-text-center';
		}

		// Add main li class to top of array
		\array_unshift( $classes, 'vcex-off-canvas-menu-nav__item' );
		// Generate li attributes
		$li_atts          = [];
		$li_atts['class'] = \implode( ' ', \array_filter( $classes ) );
		$li_attributes = $this->build_atts( $li_atts );

		// Open li
		$output .= '<li' . $li_attributes . '>';

		// Open menu item content
		$content_class = [
			'vcex-off-canvas-menu-nav__item-content',
			$args->vcex_atts['nav_centered'] ? 'wpex-inline-flex' : 'wpex-flex',
			'wpex-gap-5',
			'wpex-relative',
			'wpex-text-2',
			'wpex-hover-text-2',
			'wpex-no-underline',
		];

		// Justify content
		if ( ! empty( $args->vcex_atts['item_justify_content'] ) ) {
			$content_class[] = vcex_parse_justify_content_class( $args->vcex_atts['item_justify_content'] );
		}

		// Padding Y
		if ( empty( $args->vcex_atts['item_padding_block'] ) ) {
			$content_class[] = 0 === $depth ? 'wpex-py-15' : 'wpex-py-10';
		}

		// Transition duration
		if ( $args->vcex_atts['item_transition_duration'] ) {
			$content_class[] = "wpex-duration-{$args->vcex_atts['item_transition_duration']}";
		}

		// Turn content class insto string
		$content_class_string = implode( ' ', $content_class );

		if ( $has_details ) {
			$output .= '<details class="wpex-m-0"><summary class="' . \esc_attr( $content_class_string ) . ' wpex-list-none">';
		} elseif ( $has_link ) {
			$has_link = true;
			$link_atts = [
				'class' => \esc_attr( $content_class_string ),
			];
			$link_atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$link_atts['target'] = ! empty( $item->target ) ? $item->target : '';
			
			if ( '_blank' === $item->target && empty( $item->xfn ) ) {
				$link_atts['rel'] = 'noopener';
			} else {
				$link_atts['rel'] = $item->xfn;
			}

			if ( ! empty( $item->url ) ) {
				$link_atts['href'] = $item->url;
			} else {
				$link_atts['href'] = '';
			}

			$link_atts['aria-current'] = $item->current ? 'page' : '';

			$output .= '<a' . $this->build_atts( $link_atts ) . '>';
		} else {
			$has_link = false;
			$output .= '<div class="' . \esc_attr( $content_class_string ) . '">';
		}

		// Open icon wrapper
		if ( $icon && $item_icon_html ) {
			$icon_wrap_class = 'vcex-off-canvas-menu-nav__icon-wrap wpex-flex wpex-self-center';
			if ( $args->vcex_atts['nav_centered'] ) {
				$icon_wrap_class .= ' wpex-text-left';
			}
			if ( $has_description ) {
				$icon_wrap_class .= ' wpex-gap-15';
			} else {
				$icon_wrap_class .= ' wpex-gap-10';
			}
			$output .= '<div class="' . \esc_attr( $icon_wrap_class ) . '">' . $item_icon_html;
		}

		// Text element
		$text_class = [
			'vcex-off-canvas-menu-nav__item-text',
			'wpex-flex',
			'wpex-flex-col',
			'wpex-self-center',
		];

		$output .= '<div class="' . esc_attr( implode( ' ', $text_class ) ) . '">';

			// Menu item text wrapper
			if ( $has_description ) {
				$text_heading_class = 'vcex-off-canvas-menu-nav__item-heading wpex-bold wpex-mb-5';
			} else {
				$text_heading_class = ''; // must reset after each item
			}

			if ( $text_heading_class ) {
				$output .= '<div class="' . \esc_attr( $text_heading_class ) . '">' . wp_kses_post( do_shortcode( sanitize_text_field( $item->title ) ) ) . '</div>';
			} else {
				$output .= wp_kses_post( do_shortcode( sanitize_text_field( $item->title ) ) );
			}

			// Add menu item description
			if ( $has_description ) {
				$output .= '<p class="vcex-off-canvas-menu-nav__item-desc wpex-text-pretty wpex-text-sm wpex-m-0">' . \esc_html( (string) $item->description ) . '</p>';
			}

		// closes inner element
		$output .= '</div>';

		// Close icon wrap
		if ( $icon && $item_icon_html ) {
			$output .= '</div>';
		}

		// Close menu item content
		if ( $has_details ) {
			$output .= '</div>';
			if ( $args->vcex_atts['sub_arrow_enable'] ) {
				$output .= $this->_get_down_arrow( $args );
			}
			$output .= '</summary>';
		} else if ( $has_link ) {
			$output .= '</a>';
		} else {
			$output .= '</div>';
		}
	}

	/**
	 * Ends the element output, if needed.
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		if ( 0 === $depth & ! empty( $this->has_children ) ) {
			$output .= '</details>';
		}
		if ( $args->vcex_atts['item_divider'] && 0 === $depth ) {
			$output .= '<div class="vcex-off-canvas-menu-nav__item-divider wpex-h-1px wpex-surface-3"></div>';
		}
		$output .= '</li>';
	}


	/**
	 * Parses the menu item classes to remove/alter them.
	 */
	protected function parse_menu_item_classes( array $classes, $args = [] ): array {
		foreach ( $classes as $class_k => $class_v ) {
			if ( ! \is_string( $class_v ) ) {
				continue;
			}
			// Not allowed for the off canvas menu element.
			if ( 'flip-dropdown' === $class_v
				|| 'megamenu' === $class_v
				|| 'hide-headings' === $class_v
				|| 'megamenu' === $class_v
				|| \str_starts_with( $class_v, 'col-' )
			) {
				unset( $classes[ $class_k ] );
			}
		}
		return $classes;
	}

	/**
	 * Returns down arrow.
	 */
	private function _get_down_arrow( $args = [] ) {
		$icon = $args->vcex_atts['sub_arrow_icon'];
		return (string) \vcex_get_theme_icon_html( "{$icon}-down", 'vcex-off-canvas-menu-nav__arrow-icon wpex-flex wpex-items-center', 'xs', false );
	}

}
