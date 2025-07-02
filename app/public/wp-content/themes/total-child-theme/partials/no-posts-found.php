<?php
/**
 * No posts found.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.0
 */

defined( 'ABSPATH' ) || exit;

if ( is_search() ) {
	$text = '<h2>Whoops!</h2><p>Looks like there aren\'t any posts about this particular topic! Give us a call at <a class="text-link" href="tel:8044898188">(804) 489-8188</a> to have your digital marketing questions answered directly, or click on the buttons below to learn more about Level Up Digital Marketing and how we help businesses grow!</p><div class="services-page-anchor-buttons-div">
      <div>
        <a href="/learn" class="lur-button-2 local-scroll-link">Browse All Posts</a>
        <a href="/services" class="lur-button-2 local-scroll-link">Browse all Services</a>
        <a href="/contact" class="lur-button-2 local-scroll-link">Contact Us</a>
      </div>
    <!--  <div>
        <a href="/learn" class="lur-button-2">Browse All Posts</a>
        <a href="/services" class="lur-button-2">Browse all Services</a>
        <a href="/contact" class="lur-button-2">Contact Us</a>
      </div> -->
    </div>';//esc_html__( 'Whoops! Looks like there aren\'t any posts about this particular topic! ', 'total' );
} else {
	$text = esc_html__( 'No Posts found.', 'total' );
}

?>

<div class="wpex-no-posts-found wpex-text-md wpex-mb-20"><?php echo apply_filters( 'wpex_no_posts_found_text', $text ); ?></div>