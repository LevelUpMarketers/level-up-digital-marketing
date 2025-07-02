<?php

defined( 'ABSPATH' ) || exit;

$html = '';

$content = get_the_content( null, false, $this->post_id );

if ( ! $content ) {
	return;
}

if ( WPEX_VC_ACTIVE && $wpb_style = totaltheme_get_instance_of( 'Integration\WPBakery\Shortcode_Inline_Style' ) ) {
	$html .= $wpb_style->get_style( $this->post_id );
}

$html .= apply_filters( 'wpex_the_content', $content );

return $html;
