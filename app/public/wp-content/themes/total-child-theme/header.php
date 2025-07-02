<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package TotalTheme
 * @subpackage Templates
 * @version 5.8.0
 */

defined( 'ABSPATH' ) || exit;

?><!DOCTYPE html>
<html <?php language_attributes(); ?><?php wpex_schema_markup( 'html' ); ?> <?php wpex_html_class(); ?>>
<head>

<?php   

// Conditionally display meta descriptions
$ID = $wp_query->post->ID;


	// Main Blog page
	if ( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
	    ?>
		<meta name="description" content="Level Up your online presence with our expert digital marketing services. Specializing in professional website design, SEO optimization, targeted paid advertising & effective content marketing. Drive traffic, generate leads, and grow your business. Contact us now for personalized solutions." charset="<?php bloginfo( 'charset' ); ?>">
		<?php
	}



	// Homepage meta description
	if( 16 === $ID ){
		?>
		<meta name="description" content="Level Up your online presence with our expert digital marketing services. Specializing in professional website design, SEO optimization, targeted paid advertising & effective content marketing. Drive traffic, generate leads, and grow your business. Contact us now for personalized solutions." charset="<?php bloginfo( 'charset' ); ?>">
		<?php
	}

	// Website Design Page
	if( 248 === $ID ){
		?>
		<meta name="description" content="Level up your online presence with our professional web design services. We create visually stunning, high-performing websites that engage and convert visitors. Elevate your brand today!." charset="<?php bloginfo( 'charset' ); ?>">
		<?php
	}


	// SEO Page
	if( 250 === $ID ){
		?>
		<meta name="description" content="Unlock your website's potential with expert SEO services. Our tailored strategies, keyword research, and on-page optimizations boost rankings. Drive organic traffic and achieve online success today - contact us now for effective SEO solutions." charset="<?php bloginfo( 'charset' ); ?>">
		<?php
	}

	// Contact Page
	if( 19 === $ID ){
		?>
		<meta name="description" content="Reach new heights with Level Up Digital Marketing. Contact us for personalized solutions that drive growth, boost visibility, and connect with your audience. Elevate your brand today!" charset="<?php bloginfo( 'charset' ); ?>">
		<?php
	}

	// Services Page
	if( 21 === $ID ){
		?>
		<meta name="description" content="Level up your business today with our expert website design, SEO, paid search, and content marketing services." charset="<?php bloginfo( 'charset' ); ?>">
		<?php
	}
	?>


<link rel="profile" href="http://gmpg.org/xfn/11">



<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php wp_body_open(); ?>

	<?php wpex_hook_after_body_tag(); // Added before wp_body_open was introduced ?>

	<?php wpex_hook_outer_wrap_before(); ?>

	<div id="outer-wrap" class="wpex-overflow-clip">

		<?php wpex_hook_wrap_before(); ?>

		<div id="wrap" class="wpex-clr">

			<?php wpex_hook_wrap_top(); ?>

			<?php wpex_hook_main_before(); ?>

			<main id="main" class="site-main wpex-clr"<?php wpex_schema_markup( 'main' ); ?><?php wpex_aria_landmark( 'main' ); ?>>

				<?php wpex_hook_main_top(); ?>

				