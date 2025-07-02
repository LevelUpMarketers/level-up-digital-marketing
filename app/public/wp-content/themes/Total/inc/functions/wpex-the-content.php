<?php

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'totaltheme_remove_empty_p_tags' ) ) {
	add_filter( 'wpex_the_content', 'totaltheme_remove_empty_p_tags' );
}

if ( function_exists( 'totaltheme_replace_vars' ) ) {
	add_filter( 'wpex_the_content', 'totaltheme_replace_vars' );
}

if ( function_exists( 'do_blocks' ) ) {
	add_filter( 'wpex_the_content', 'do_blocks', 9 );
}

if ( function_exists( 'wptexturize' ) ) {
	add_filter( 'wpex_the_content', 'wptexturize' );
}

if ( function_exists( 'convert_chars' ) ) {
	add_filter( 'wpex_the_content', 'convert_chars' );
}

if ( function_exists( 'wpautop' ) ) {
	add_filter( 'wpex_the_content', 'wpautop' );
}

if ( function_exists( 'shortcode_unautop' ) ) {
	add_filter( 'wpex_the_content', 'shortcode_unautop' );
}

if ( function_exists( 'totaltheme_shortcode_unautop' ) ) {
	add_filter( 'wpex_the_content', 'totaltheme_shortcode_unautop' );
}

if ( function_exists( 'wp_filter_content_tags' ) ) {
	add_filter( 'wpex_the_content', 'wp_filter_content_tags' );
}

if ( function_exists( 'wp_replace_insecure_home_url' ) ) {
	add_filter( 'wpex_the_content', 'wp_replace_insecure_home_url' );
}

if ( function_exists( 'do_shortcode' ) ) {
	add_filter( 'wpex_the_content', 'do_shortcode', 11 ); // AFTER wpautop().
}

if ( function_exists( 'convert_smilies' ) && ! get_theme_mod( 'remove_emoji_scripts_enable', true ) ) {
	add_filter( 'wpex_the_content', 'convert_smilies', 20 );
}

/**
 * Helper function similar to get_the_content but without potential plugin conflicts.
 */
function wpex_the_content( $raw_string = '', $context = '' ) {
	if ( $raw_string ) {
		return apply_filters( 'wpex_the_content', wp_kses_post( $raw_string ), $context );
	}
}
