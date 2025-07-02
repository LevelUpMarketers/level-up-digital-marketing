<?php
/**
 * Used to insert content to the top/bottom of the sidr mobile menu.
 *
 * @package TotalTheme
 * @subpackage Partials
 * @version 5.8.0
 */

defined( 'ABSPATH' ) || exit;

?>

<?php
// Mobile Menu Top Hook.
if ( has_action( 'wpex_mobile_menu_top' ) || has_action( 'wpex_hook_mobile_menu_top' ) ) { ?>

	<div class="wpex-mobile-menu-top wpex-hidden wpex-px-20 wpex-pb-20"><?php
        wpex_hook_mobile_menu_top();
    ?></div>

<?php } ?>

<?php
// Mobile Menu Bottom Hook.
if ( has_action( 'wpex_mobile_menu_bottom' ) || has_action( 'wpex_hook_mobile_menu_bottom' ) ) { ?>

	<div class="wpex-mobile-menu-bottom wpex-hidden wpex-px-20 wpex-pt-20"><?php
        wpex_hook_mobile_menu_bottom();
    ?></div>

<?php } ?>
