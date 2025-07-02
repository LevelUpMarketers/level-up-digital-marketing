<?php

use TotalTheme\Footer\Bottom\Menu as Footer_Bottom_Menu;

/**
 * Footer bottom menu.
 *
 * @package TotalTheme
 * @subpackage Partials
 * @version 5.10
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TotalTheme\Footer\Bottom\Menu' ) ) {
	return;
}

// Get footer menu theme_location.
$menu_location = Footer_Bottom_Menu::get_theme_location();

// Menu is required.
if ( ! $menu_location || ! has_nav_menu( $menu_location ) ) {
	return;
}

?>

<nav id="footer-bottom-menu" <?php Footer_Bottom_Menu::wrapper_class(); ?><?php wpex_aria_label( 'footer_bottom_menu' ); ?>><?php

	wp_nav_menu( [
		'theme_location' => $menu_location,
		'sort_column'    => 'menu_order',
		'fallback_cb'    => false,
	] );

?></nav>
