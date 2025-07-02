<?php

use TotalTheme\Mobile\Menu as Mobile_Menu;

/**
 * Elements used for the dynamic sidebar mobile menu which is generated via JS.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 6.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TotalTheme\Mobile\Menu' ) ) {
	return;
}

Mobile_Menu::search_form( [
    'submit_text'  => '',
    'form_class'   => 'wpex-relative',
    'input_class'  => 'wpex-unstyled-input wpex-outline-0 wpex-w-100',
    'submit_class' => 'wpex-unstyled-button wpex-block wpex-absolute wpex-top-50 wpex-text-right',
] );

?>

<template id="wpex-template-sidr-mobile-menu-top"><?php totaltheme_call_static( 'Mobile\Menu', 'render_top' ); ?></template>

<div class="wpex-sidr-overlay wpex-fixed wpex-inset-0 wpex-hidden wpex-z-backdrop wpex-bg-backdrop"></div>
