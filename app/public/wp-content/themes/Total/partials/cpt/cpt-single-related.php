<?php

/**
 * CPT single related.
 *
 * @package TotalTheme
 * @subpackage Partials
 * @version 6.0
 */

defined( 'ABSPATH' ) || exit;

$wpex_related_query = wpex_cpt_single_related_query();

if ( $wpex_related_query && ! is_wp_error( $wpex_related_query ) && $wpex_related_query->have_posts() ) :

	// Check if embeds are enabled.
	$show_embeds = apply_filters( 'wpex_cpt_single_related_embeds', false ); ?>

	<div <?php wpex_cpt_single_related_class(); ?>>

		<?php
		// Display related heading.
		wpex_heading( [
			'tag'           => get_theme_mod( 'related_heading_tag' ) ?: 'h3',
			'classes'		=> [ 'related-posts-title' ],
			'apply_filters'	=> 'cpt_single_related',
			'content'		=> sprintf(
				esc_html__( 'Related %s', 'total' ),
				get_post_type_object( get_post_type() )->labels->name ?? esc_html( 'Items', 'total' )
			),
		] );
		?>

		<div <?php wpex_cpt_single_related_row_class(); ?>>

			<?php
			// Set loop instance.
			wpex_set_loop_instance( 'related' );

			// Set counter var.
			wpex_set_loop_counter();

			// Loop through items.
			foreach ( $wpex_related_query->posts as $post ) : setup_postdata( $post );

				// Add to running count.
				wpex_increment_loop_running_count();

				// Add to counter.
				wpex_increment_loop_counter();

				?>

				<article <?php wpex_cpt_single_related_entry_class(); ?>>

					<?php if ( ! wpex_cpt_entry_card() ) { ?>

						<div class="related-post-inner wpex-flex-grow">

							<?php
							// Display post video.
							if ( $show_embeds && 'video' === $format && $video = wpex_get_post_video() ) : ?>

								<div class="related-post-video wpex-mb-15"><?php echo wpex_get_post_video_html( $video ); ?></div>

							<?php
							// Display post audio.
							elseif ( $show_embeds && 'audio' === $format && $audio = wpex_get_post_audio() ) : ?>

								<div class="related-post-video wpex-mb-15"><?php echo wpex_get_post_audio_html( $audio ); ?></div>

							<?php
							// Display post thumbnail.
							elseif ( has_post_thumbnail() && apply_filters( 'wpex_cpt_single_related_has_thumbnails', true ) ) :
								$related_img_class = 'related-post-figure wpex-mb-15 wpex-relative';

								// Overlay style.
								$overlay = wpex_cpt_entry_overlay_style();
								
								if ( $overlay ) {
									$overlay_class = (string) totaltheme_call_static(
										'Overlays',
										'get_parent_class',
										(string) $overlay
									);
									if ( $overlay_class ) {
										$related_img_class .= ' ' . trim( $overlay_class );
									}
								}

								?>

								<figure class="<?php echo esc_attr( $related_img_class ); ?>">
									<a href="<?php the_permalink(); ?>" title="<?php wpex_esc_title(); ?>" class="related-post-thumb<?php wpex_entry_image_animation_classes(); ?>">
										<?php echo wpex_get_post_thumbnail( array(
											'size'  => "{$post_type}_single_related",
											'class' => 'wpex-align-middle',
										) ); ?>
										<?php wpex_entry_media_after( 'cpt_single_related' ); ?>
										<?php totaltheme_render_overlay( 'inside_link', $overlay ); ?>
									</a>
									<?php totaltheme_render_overlay( 'outside_link', $overlay ); ?>
								</figure>

							<?php endif; ?>

							<?php
							// Display post excerpt.
							if ( apply_filters( 'wpex_cpt_single_related_excerpts', true ) ) : ?>

								<div class="related-post-content wpex-clr">

									<div class="related-post-title entry-title <?php echo totaltheme_has_classic_styles() ? 'wpex-mb-5' : 'wpex-mb-10'; ?>">
										<a href="<?php wpex_permalink(); ?>"><?php the_title(); ?></a>
									</div>

									<div class="related-post-excerpt wpex-leading-normal wpex-last-mb-0 wpex-clr"><?php
										echo totaltheme_get_post_excerpt( [
											'length' => wpex_cpt_entry_excerpt_length(),
										] );
									?></div>

								</div>

							<?php endif; ?>

						</div>

					<?php } // end card check ?>

				</article>

				<?php
				// Reset counter.
				wpex_maybe_reset_loop_counter( wpex_get_array_first_value( wpex_cpt_single_related_columns() ) );

			endforeach;

			?>

		</div>

	</div>

	<?php
	// Reset data.
	wpex_reset_loop_query_vars();
	wp_reset_postdata();

// End have_posts check.
endif;
