<?php

defined( 'ABSPATH' ) || exit;

$html = '';

$tagged = get_post_type( $this->post_id );

if ( 'post' === $tagged && $primary_term = totaltheme_get_post_primary_term( $this->post_id ) ) {
	$tagged = $primary_term->name ?? $tagged;
}

$html .= $this->get_element( [
	'class' => 'wpex-card-tags wpex-text-xs wpex-opacity-60 wpex-uppercase wpex-tracking-wider wpex-font-medium',
	'content' => '<span>' . esc_html( $tagged ) . '</span>',
] );

$html .= $this->get_title( [
	'class' => 'wpex-heading wpex-text-lg wpex-m-0',
] );

$html .= $this->get_excerpt( [
	'class' => 'wpex-mt-5'
] );

return $html;
