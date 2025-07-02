<?php

namespace TotalThemeCore\Vcex;

\defined( 'ABSPATH' ) || exit;

class Term_Query {

	/**
	 * Term_Query Args.
	 */
	protected $args = [];

	/**
	 * Constructor.
	 */
	public function __construct( array $shortcode_atts = [], string $shortcode_tag = '' ) {
		$this->args = $this->get_args( $shortcode_atts );
	
		/**
		 * Filters query arguments.
		 *
		 * @param array $args
		 * @param array $shortcode_atts
		 * @param string $shortcode_tag
		 */
		$this->args = (array) \apply_filters( 'totalthemecore/vcex/term_query/args', $this->args, $shortcode_atts, $shortcode_tag );
	}

	/**
	 * Return Terms.
	 */
	private function get_args( array $atts ): array {
		$query_type = $atts['query_type' ] ?? '';

		// Get current Taxonomy.
		if ( ( 'tax_children' === $query_type || 'tax_parent' === $query_type ) && \is_tax() ) {
			$taxonomy = \get_query_var( 'taxonomy' );
		} else {
			$taxonomy = $atts['taxonomy'] ?? [];
		}

		// Convert taxonomy to array if there is more than 1 selected.
		if ( \is_string( $taxonomy ) && \str_contains( $taxonomy, ',' ) ) {
			$taxonomy = \explode( ',', $taxonomy );
		}

		// Taxonomy is required.
		if ( ! $taxonomy ) {
			return [];
		}
		
		// Add args.
		$args = [
			'taxonomy'   => $taxonomy,
			'hide_empty' => \vcex_validate_att_boolean( 'hide_empty', $atts, true ),
		];

		if ( ! empty( $atts['order'] ) ) {
			$args['order'] = $atts['order'];
		}

		if ( ! empty( $atts['orderby'] ) ) {
			$args['orderby'] = \sanitize_sql_orderby( $atts['orderby'] );
		}

		/**
		 * @note can't use vcex_validate_att_boolean because we need to fix
		 * inconsistency with this setting being abled on one element but not the other.
		 */
		if ( isset( $atts['parent_terms'] ) && \vcex_validate_boolean( $atts['parent_terms'] ) ) {
			$args['parent'] = 0;
		}

		if ( ! empty( $atts['child_of'] ) && \is_string( $taxonomy ) ) {
			$child_of_type = \is_numeric( $atts['child_of'] ) ? 'term_id' : 'slug';
			$child_of = get_term_by( $child_of_type, $atts['child_of'], $taxonomy );
			if ( $child_of && ! is_wp_error( $child_of ) ) {
				$args['child_of'] = $child_of->term_id;
				unset( $args['parent'] );
			}
		}

		// Add arguments based on query_type.
		switch ( $query_type ) {
			case 'post_terms':
				if ( ! vcex_get_template_edit_mode() ) {
					$args['object_ids'] = \vcex_get_the_ID();
				}
				break;
			case 'tax_children':
				if ( \is_tax() ) {
					$args['child_of'] = \get_queried_object_id();
					unset( $args['parent'] );
				}
				break;
			case 'tax_parent':
				if ( \is_tax() ) {
					$args['parent'] = \get_queried_object_id();
				}
				break;
		}

		/*** deprecated ***/
		$args = \apply_filters( 'vcex_terms_grid_query_args', $args, $atts );
		
		return (array) $args;
	}

	/**
	 * Return Terms.
	 */
	public function get_terms() {
		return $this->args ? \get_terms( $this->args ) : false;
	}

}
