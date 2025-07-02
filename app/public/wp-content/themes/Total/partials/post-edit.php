<?php

/**
 * Edit post link.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 6.0
 */

defined( 'ABSPATH' ) || exit;

wp_enqueue_style( 'wpex-post-edit' );
wp_enqueue_script( 'wpex-hide-edit-links' );

$class = 'post-edit wpex-my-40 wpex-print-hidden';

if ( 'full-screen' === wpex_get_post_layout() ) {
    $class .= ' container';
}

$extra_links = '<a href="#" class="hide-post-edit wpex-text-lg">' . totaltheme_get_icon( 'material-close', 'wpex-flex' ) . '<span class="screen-reader-text">' . esc_html__( 'Hide Post Edit Links', 'total' ) . '</span></a>';

if ( totaltheme_call_static( 'Theme_Builder\Post_Template', 'has_template' )
    && $template_id = (int) totaltheme_call_static( 'Theme_Builder\Post_Template', 'get_template_id' )
) {
    if ( $edit_template_link = get_edit_post_link( $template_id ) ) {
        $extra_links = '<a class="edit-template" href="' . esc_url( $edit_template_link ) . '">' . esc_html__( 'Edit Template', 'total' ) . '</a>' . $extra_links;
    }
}

edit_post_link( null, '<div class="' . esc_attr( $class ) . '">', ' ' . $extra_links . '</div>' );
