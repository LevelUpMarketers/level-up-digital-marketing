<?php

/**
 * Blog entry media
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.10
 */

defined( 'ABSPATH' ) || exit;

if ( ! has_post_thumbnail() ) {
	return;
}

$overlay = totaltheme_call_static(
	'Overlays',
	'get_entry_image_overlay_style',
	'post'
);

?>

<?php
// Lightbox style entry
if ( get_theme_mod( 'blog_entry_image_lightbox', false ) ) :

	wpex_enqueue_lightbox_scripts();

	$lightbox_image = wpex_get_lightbox_image( get_post_thumbnail_id() );
	
	?>

	<a href="<?php echo esc_url( $lightbox_image ); ?>" title="<?php wpex_esc_title(); ?>" class="blog-entry-media-link wpex-lightbox">
		<?php echo wpex_get_blog_entry_thumbnail(); ?>
		<?php totaltheme_render_overlay( 'inside_link', $overlay, [
			'lightbox_link' => $lightbox_image,
		] ); ?>
		<?php wpex_entry_media_after( 'blog' ); ?>
	</a>

	<?php totaltheme_render_overlay( 'outside_link', $overlay, [
		'lightbox_link' => $lightbox_image,
	] ); ?>

<?php
// Standard link to post
else : ?>

	<a href="<?php wpex_permalink(); ?>" title="<?php wpex_esc_title(); ?>" class="blog-entry-media-link">
		<?php echo wpex_get_blog_entry_thumbnail(); ?>
		<?php wpex_entry_media_after( 'blog' ); ?>
		<?php totaltheme_render_overlay( 'inside_link', $overlay ); ?>
	</a>

	<?php totaltheme_render_overlay( 'outside_link', $overlay ); ?>

<?php
endif;
