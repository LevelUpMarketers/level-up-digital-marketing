<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package TotalTheme
 * @subpackage Templates
 * @version 5.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

?>

	<div id="content-wrap" class="container wpex-clr">

		<?php wpex_hook_primary_before(); ?>

		<?php

		if ( ( "https://$_SERVER[HTTP_HOST]" . '/learn/' === "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ) || ( false !== stripos( "$_SERVER[REQUEST_URI]", '?s=') ) ) {

			echo '<div class="lur-sitewide-hero-container">
				<section class="lur-sitewidehero-hero">
					<div class="lur-sitewidehero-container">
					  <div class="lur-sitewidehero-left-column">
					    <div class="lur-sitewidehero-small-fake-button">Search Results</div>
					    <h1 class="lur-sitewidehero-heading">';

					    $search = get_search_query();
					    echo ucwords($search);

					    echo '</h1>
					    <p class="lur-sitewidehero-paragraph">Digital Marketing can be confusing, so we strive to educate our clients in the clearest way possible. Read on to learn how services like Website Design, Search Engine Optimization, Paid Search, and Content Marketing help you grow.</p>
					    <div class="lur-sitewidehero-cta-container lur-sitewidehero-cta-container-contact-page">
					      <a href="/learn" class="lur-button-1 lur-button-1-contact-special">Learn More</a>
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

		}

		?>

		<div id="primary" class="content-area wpex-clr">

			<?php wpex_hook_content_before(); ?>

			<div id="content" class="site-content wpex-clr">

				<?php wpex_hook_content_top(); ?>

				<?php
				// Display default theme layout if elementor template not defined
				if ( ! wpex_theme_do_location( 'archive' ) ) :

					// Display posts if there are in fact posts to display
					if ( have_posts() ) :

						// Get index loop type
						$loop_type = wpex_get_index_loop_type();

						// Get loop top
						get_template_part( 'partials/loop/loop-top', $loop_type );

							// Set the loop counter which is used for clearing floats
							wpex_set_loop_counter();

							// Loop through posts
							while ( have_posts() ) : the_post();

								// Add to running count
								wpex_increment_loop_running_count();

								// Before entry hook
								wpex_hook_archive_loop_before_entry();

								// Get content template part (entry content)
								get_template_part( 'partials/loop/loop', $loop_type );

								// After entry hook
								wpex_hook_archive_loop_after_entry();

							// End loop
							endwhile;

						// Get loop bottom
						get_template_part( 'partials/loop/loop-bottom', $loop_type );

						// Display pagination
						wpex_loop_pagination( $loop_type );

						// Reset query vars
						wpex_reset_loop_query_vars();

						?>

					<?php
					// Show message because there aren't any posts
					else :

						get_template_part( 'partials/no-posts-found' );

					endif;

				endif; ?>

				<?php wpex_hook_content_bottom(); ?>

			</div>

		<?php wpex_hook_content_after(); ?>

		</div>

		<?php wpex_hook_primary_after(); ?>

	</div>

	<?php
	if ( ( "https://$_SERVER[HTTP_HOST]" . '/learn/' === "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ) || ( false !== stripos( "$_SERVER[REQUEST_URI]", '?s=') ) || ( false !== stripos( "$_SERVER[REQUEST_URI]", 'category/') ) ) {

		echo '<div class="lur-abovefooter-sitewide-footer-contact-section" id="lur-abovefooter-sitewide-footer-contact-section">
		  <div class="lur-sitewide-footer-contact-right-column">
		    <div class="lur-expertise-column">
		      <p class="lur-sitewide-supertext-1">Let\'s Chat</p>
		      <h2 class="lur-div-h2-1">Reach Out Today</h2>
		      <p class="lur-expertise-paragraph-text" style="color:#013a51;">We understand that searching for the right partner to help with your Digital Marketing needs can be it\'s own part-time job! That\'s why we strive to make things as straight-forward and simple for our clients as possible. Do you have 5 minutes to decide if we\'re that right partner? That\'s all it takes. <a class="text-link" href="tel:8044898188">Give us a call at (804) 489-8188</a>.</p>
		      <div class="lur-expertise-button-container">
		        <a class="lur-button-1-smaller" href="tel:8044898188"><span class="lur-contactrow-span"><img src="/wp-content/themes/total-child-theme/images/roundphoneicon.svg" alt="Phone Symbol" class="lur-expertise-image"></span>(804) 489-8188</a>
                        <a class="lur-button-1-smaller" href="mailto:contact@levelupmarketers.com"><span class="lur-contactrow-span"><img src="/wp-content/themes/total-child-theme/images/roundmailicon.svg" alt="Email Symbol" class="lur-expertise-image"></span>contact@levelupmarketers.com</a>
                      </div>
                      <p class="lur-expertise-paragraph-text" style="color:#013a51;">1011 E Main St Suite 222, Richmond, VA 23219</p>
                    </div>
                  </div>
		  <div class="lur-sitewide-footer-contact-left-column">';
		    echo do_shortcode('[contact-form-7 id="230" title="Contact form 1"]');
		  echo '</div>
		</div>';
	}
	?>

<?php get_footer(); ?>