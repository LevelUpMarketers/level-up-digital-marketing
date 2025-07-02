<?php
/**
 * The template for displaying all pages, single posts and attachments
 *
 * @package TotalTheme
 * @subpackage Templates
 * @version 5.5.1
 */

defined( 'ABSPATH' ) || exit;

get_header();

?>

	<div id="content-wrap" class="container wpex-clr">

		<?php wpex_hook_primary_before(); 

		if ( ( false === stripos( "$_SERVER[REQUEST_URI]", 'category') ) && ( false !== stripos( "$_SERVER[REQUEST_URI]", 'learn') ) ) {

			echo '<div class="lur-sitewide-hero-container">
				<section class="lur-sitewidehero-hero">
					<div class="lur-sitewidehero-container">
					  <div class="lur-sitewidehero-left-column">
					    <div class="lur-sitewidehero-small-fake-button">A Level Up Blog Post</div>
					    <p class="lur-sitewidehero-heading">';

					    echo get_the_title();

					    echo '</p>
					    <p class="lur-sitewidehero-paragraph">';

					    $excerpt = get_the_excerpt();

					    $split = explode(" ", $excerpt); //convert string to array
						$len = count($split); //get number of words
						$words_to_show_first = 25; //Word to be dsiplayed first
						if ($len > $words_to_show_first) { //check if it's longer the than first part

							$firsthalf = array_slice($split, 0, $words_to_show_first);
							$secondhalf = array_slice($split, $words_to_show_first, $len - 1);
							$output = '';
							$output .= implode(' ', $firsthalf) . '<span class="see-more-text">...</span>';
							/*
							$output .= '<span class="excerpt-full hide">';
							$output .= ' ' . implode(' ', $secondhalf);
							$output .= '</span>';
							*/
							//$output .= '</p>';
						} else {
							$output = $excerpt;
						}
						
						echo $output;
						//echo $excerpt;

					    echo '</p>
					    <div class="lur-sitewidehero-cta-container lur-sitewidehero-cta-container-contact-page">
					      <a href="#jre-scrollto-title" class="lur-button-1 lur-button-1-contact-special">Keep Reading...</a>
					      <a href="/contact" class="lur-button-3">Contact</a>
					    </div>
					  </div>
					  <div class="lur-sitewidehero-right-column">
					    <div class="lur-seo-screenshots-top-container">
					      <img alt="An illustrated image of icons depicting various digital marketing services" decoding="async" class="lur-seo-google-shot" id="lur-seo-google-shot-1" src="/wp-content/themes/total-child-theme/images/learn.png">
					    </div>
					  </div>
					</div>
				</section>
			</div>';

		} ?>

		<div id="primary" class="content-area wpex-clr">

			<?php wpex_hook_content_before(); ?>

			<div id="content" class="site-content wpex-clr">

				<?php wpex_hook_content_top(); ?>


				<?php
				// Display singular content unless there is a custom template defined
				if ( ! wpex_theme_do_location( 'single' ) ) :

					// Start loop
					while ( have_posts() ) : the_post();

						// Single Page
						if ( is_singular( 'page' ) ) {
							wpex_get_template_part( 'page_single_blocks' );
						}

						// Single posts
						elseif ( is_singular( 'post' ) ) {
							wpex_get_template_part( 'blog_single_blocks' );
						}

						// Portfolio Posts
						elseif ( is_singular( 'portfolio' ) && wpex_is_total_portfolio_enabled() ) {
							wpex_get_template_part( 'portfolio_single_blocks' );
						}

						// Staff Posts
						elseif ( is_singular( 'staff' ) && wpex_is_total_staff_enabled() ) {
							wpex_get_template_part( 'staff_single_blocks' );
						}

						// Testimonials Posts
						elseif ( is_singular( 'testimonials' ) && wpex_is_total_testimonials_enabled() ) {
							wpex_get_template_part( 'testimonials_single_blocks' );
						}

						/**
						 * All other post types.
						 */
						else {

							// Prevent issues with custom types named the same as core partial files.
							// @todo remove the $post_type paramater from wpex_get_template_part.
							$post_type = get_post_type();

							$excluded_types = array(
								'audio',
								'video',
								'gallery',
								'content',
								'comments',
								'media',
								'meta',
								'related',
								'share',
								'title'
							);

							if ( in_array( $post_type, $excluded_types ) ) {
								$post_type = null;
							}



							wpex_get_template_part( 'cpt_single_blocks', $post_type );

						}

					endwhile; ?>

				<?php endif; ?>


				<?php wpex_hook_content_bottom(); ?>

			</div>

			<?php wpex_hook_content_after(); ?>

		</div>

		<?php wpex_hook_primary_after(); ?>

	</div>

	<?php
	if ( ( false === stripos( "$_SERVER[REQUEST_URI]", 'category') ) && ( false !== stripos( "$_SERVER[REQUEST_URI]", 'learn') ) ) {

		echo '<div class="lur-abovefooter-sitewide-footer-contact-section" id="lur-abovefooter-sitewide-footer-contact-section">
		  <div class="lur-sitewide-footer-contact-right-column">
		    <div class="lur-expertise-column">
		      <p class="lur-sitewide-supertext-1">Let\'s Chat</p>
		      <h2 class="lur-div-h2-1">Reach Out Today</h2>
		      <p class="lur-expertise-paragraph-text" style="color:#013a51;">We understand that searching for the right partner to help with your Digital Marketing needs can be it\'s own part-time job! That\'s why we strive to make things as straight-forward and simple for our clients as possible. Do you have 5 minutes to decide if we\'re that right partner? That\'s all it takes. <a class="text-link" href="tel:8044898188">Give us a call at (804) 489-8188</a>.</p>
		      <div class="lur-expertise-button-container">
		        <a class="lur-button-1-smaller" href="tel:8044898188"><span class="lur-contactrow-span"><img src="/wp-content/themes/total-child-theme/images/roundphoneicon.svg" alt="Phone Symbol" class="lur-expertise-image"></span>(804) 489-8188</a>
		        <a class="lur-button-1-smaller" href="mailto:contact@leveluprichmond.com"><span class="lur-contactrow-span"><img src="/wp-content/themes/total-child-theme/images/roundmailicon.svg" alt="Email Symbol" class="lur-expertise-image"></span>contact@leveluprichmond.com</a>
		      </div>
		    </div>
		  </div>
		  <div class="lur-sitewide-footer-contact-left-column">';
		    echo do_shortcode('[contact-form-7 id="230" title="Contact form 1"]');
		  echo '</div>
		</div>';
	}
	?>

<?php
get_footer();