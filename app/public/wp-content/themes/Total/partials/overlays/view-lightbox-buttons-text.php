<?php

/**
 * Overlay: Lightbox Buttons + Text.
 *
 * @package TotalTheme
 * @subpackage Partials
 * @version 6.0
 */

defined( 'ABSPATH' ) || exit;

if ( 'outside_link' !== $position ) {
	return;
}

wpex_enqueue_lightbox_scripts();

// Lightbox.
$lightbox_link = ! empty( $args['lightbox_link'] ) ? $args['lightbox_link'] : wpex_get_lightbox_image();
$lightbox_data = '';
if ( ! empty( $args['lightbox_data'] ) ) {
	$lightbox_data = is_array( $args['lightbox_data'] ) ? ' ' . implode( ' ', $args['lightbox_data'] ) : $args['lightbox_data'];
}
$lightbox_class = 'wpex-lightbox'; // can't use galleries in this overlay style due to duplicate links

// Custom Link.
$link = $args['overlay_link'] ?? $args['post_permalink'] ?? wpex_get_permalink();

// Define link target.
$target = '';
if ( isset( $args['link_target'] ) && ( 'blank' === $args['link_target'] || '_blank' === $args['link_target'] ) ) {
    $target = 'blank';
}

// Apply filters.
$link   = apply_filters( 'wpex_lightbox_buttons_button_overlay_link', $link, $args );
$target = apply_filters( 'wpex_button_overlay_target', $target, $args );

// Sanitize Data.
$link          = esc_url( $link );
$lightbox_link = esc_url( $lightbox_link );

?>

<div class="overlay-view-lightbox-text theme-overlay overlay-hide wpex-absolute wpex-inset-0 wpex-transition-all wpex-duration-<?php echo totaltheme_get_overlay_speed(); ?> wpex-flex wpex-items-center wpex-justify-center">
	<span class="overlay-bg wpex-bg-<?php echo totaltheme_get_overlay_bg_color(); ?> wpex-block wpex-absolute wpex-inset-0 wpex-opacity-<?php echo totaltheme_get_overlay_opacity(); ?>"></span>
	<div class="overlay-content wpex-relative wpex-font-semibold wpex-uppercase wpex-text-xs wpex-tracking-widest wpex-clr"><?php
		$button_class = [
			'wpex-inline-block',
			'wpex-relative',
			'wpex-text-white',
			'wpex-hover-text-black',
			'wpex-hover-bg-white',
			'wpex-border-2',
			'wpex-border-solid',
			'wpex-border-white',
			'wpex-leading-snug',
			'wpex-no-underline',
			'wpex-px-10',
			'wpex-py-5',
			'wpex-rounded-sm',
			'wpex-transition-all',
			'wpex-duration-200',
		];

		if ( $lightbox_link ) {

			$button1_class   = $button_class;
			$button1_class[] = $lightbox_class;
			$button1_class[] = 'wpex-mr-5';

			$button_one_attrs = array(
				'href'  => $lightbox_link,
				'class' => $button1_class,
				'data'  => $lightbox_data,
			); ?>

			<a <?php echo wpex_parse_attrs( $button_one_attrs ); ?>><?php esc_html_e( 'Zoom', 'total' ); ?><?php echo totaltheme_get_icon( 'search', 'wpex-ml-5' ); ?></a>

			<?php

		}

		$button2_class = $button_class;
		$button2_class[] = 'view-post';

		$button_two_attrs = [
			'href'   => $link,
			'class'  => $button2_class,
			'target' => $target,
		];
		?>
		<a <?php echo wpex_parse_attrs( $button_two_attrs ); ?>><?php esc_html_e( 'View', 'total' ); ?><?php echo totaltheme_get_icon( 'arrow-right', 'wpex-ml-5' ); ?></a>
	</div>

</div>
