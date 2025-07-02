<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function cm_render_add_new_client_tab() {
	?>
	<h2><?php _e( 'Add a New Client', 'client-manager' ); ?></h2>
	<table class="form-table cm-add-a-new-client-table">
		<tr valign="top">
			<th scope="row"><?php _e( 'Business Name', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_business_name" name="cm_business_name" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Client Since Date', 'client-manager' ); ?></th>
			<td><input type="date" id="cm_client_since_date" name="cm_client_since_date" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Main Location Name', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_main_location_name" name="cm_main_location_name" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Main Location Street Address', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_main_location_street_address" name="cm_main_location_street_address" class="regular-text"></td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e( 'Main Location Address 2', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_main_location_address_2" name="cm_main_location_address_2" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Main Location State', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_main_location_state" name="cm_main_location_state" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Main Location City', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_main_location_city" name="cm_main_location_city" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Main Location Zip', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_main_location_zip" name="cm_main_location_zip" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( '2nd Location Name', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_second_location_name" name="cm_second_location_name" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( '2nd Location Street Address', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_second_location_street_address" name="cm_second_location_street_address" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( '2nd Location Address 2', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_second_location_address_2" name="cm_second_location_address_2" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( '2nd Location State', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_second_location_state" name="cm_second_location_state" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( '2nd Location City', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_second_location_city" name="cm_second_location_city" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( '2nd Location Zip', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_second_location_zip" name="cm_second_location_zip" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Main POC First Name', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_main_poc_first_name" name="cm_main_poc_first_name" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Main POC Last Name', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_main_poc_last_name" name="cm_main_poc_last_name" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Main POC Title', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_main_poc_title" name="cm_main_poc_title" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Main POC Email', 'client-manager' ); ?></th>
			<td><input type="email" id="cm_main_poc_email" name="cm_main_poc_email" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Main POC Phone', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_main_poc_phone" name="cm_main_poc_phone" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Second POC First Name', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_poc_2_first_name" name="cm_poc_2_first_name" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Second POC Last Name', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_poc_2_last_name" name="cm_poc_2_last_name" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Second POC Title', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_poc_2_title" name="cm_poc_2_title" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Second POC Email', 'client-manager' ); ?></th>
			<td><input type="email" id="cm_poc_2_email" name="cm_poc_2_email" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Second POC Phone', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_poc_2_phone" name="cm_poc_2_phone" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Third POC First Name', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_poc_3_first_name" name="cm_poc_3_first_name" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Third POC Last Name', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_poc_3_last_name" name="cm_poc_3_last_name" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Third POC Title', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_poc_3_title" name="cm_poc_3_title" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Third POC Email', 'client-manager' ); ?></th>
			<td><input type="email" id="cm_poc_3_email" name="cm_poc_3_email" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Third POC Phone', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_poc_3_phone" name="cm_poc_3_phone" class="regular-text"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Main Site Analytics Property ID', 'client-manager' ); ?></th>
			<td><input type="text" id="cm_main_analytics_prop_id" name="cm_main_analytics_prop_id" class="regular-text"></td>
		</tr>
		<tr style="display:block;"></tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Logo', 'client-manager' ); ?></th>
			<td>
				<input type="text" id="cm_logo" name="cm_logo" class="regular-text">
				<button type="button" id="cm_logo_button" class="button"><?php _e( 'Choose Image', 'client-manager' ); ?></button>
				<img class="cm_addnewclient_placeholder_logo" src="/wp-content/uploads/2024/05/logo-placeholder-image-300x300-1.png"/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Main POC Headshot', 'client-manager' ); ?></th>
			<td>
				<input type="text" id="cm_main_poc_headshot" name="cm_main_poc_headshot" class="regular-text">
				<button type="button" id="cm_main_poc_button" class="button"><?php _e( 'Choose Image', 'client-manager' ); ?></button>
				<img class="cm_addnewclient_placeholder_logo" src="/wp-content/uploads/2024/05/logo-placeholder-image-300x300-1.png"/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Second POC Headshot', 'client-manager' ); ?></th>
			<td>
				<input type="text" id="cm_poc_2_headshot" name="cm_poc_2_headshot" class="regular-text">
				<button type="button" id="cm_poc_2_button" class="button"><?php _e( 'Choose Image', 'client-manager' ); ?></button>
				<img class="cm_addnewclient_placeholder_logo" src="/wp-content/uploads/2024/05/logo-placeholder-image-300x300-1.png"/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e( 'Third POC Headshot', 'client-manager' ); ?></th>
			<td>
				<input type="text" id="cm_poc_3_headshot" name="cm_poc_3_headshot" class="regular-text">
				<button type="button" id="cm_poc_3_button" class="button"><?php _e( 'Choose Image', 'client-manager' ); ?></button>
				<img class="cm_addnewclient_placeholder_logo" src="/wp-content/uploads/2024/05/logo-placeholder-image-300x300-1.png"/>
			</td>
		</tr>
	</table>
	<p class="submit">
		<button type="button" id="cm_add_client_button" class="button button-primary"><?php _e( 'Add Client', 'client-manager' ); ?></button>
	</p>
	<?php
	// Enqueue the media uploader script
	wp_enqueue_media();
	?>
	<script type="text/javascript">

		jQuery(document).ready(function($) {
			$('#cm_logo_button').on('click', function(e) {
				e.preventDefault();
				var image_frame;
				if (image_frame) {
					image_frame.open();
				}
				// Define image_frame as wp.media object
				image_frame = wp.media({
					title: '<?php _e( 'Select Logo', 'client-manager' ); ?>',
					multiple: false,
					library: {
						type: 'image',
					}
				});
				image_frame.on('select', function() {
					var attachment = image_frame.state().get('selection').first().toJSON();
					$('#cm_logo').val(attachment.url);
					$('#cm_logo').next().next().attr('src', attachment.url);
				});
				image_frame.open();
			});


			$('#cm_main_poc_button').on('click', function(e) {
				e.preventDefault();
				var image_frame;
				if (image_frame) {
					image_frame.open();
				}
				// Define image_frame as wp.media object
				image_frame = wp.media({
					title: '<?php _e( 'Select Image', 'client-manager' ); ?>',
					multiple: false,
					library: {
						type: 'image',
					}
				});
				image_frame.on('select', function() {
					var attachment = image_frame.state().get('selection').first().toJSON();
					$('#cm_main_poc_headshot').val(attachment.url);
					$('#cm_main_poc_headshot').next().next().attr('src', attachment.url);
				});
				image_frame.open();
			});


			$('#cm_poc_2_button').on('click', function(e) {
				e.preventDefault();
				var image_frame;
				if (image_frame) {
					image_frame.open();
				}
				// Define image_frame as wp.media object
				image_frame = wp.media({
					title: '<?php _e( 'Select Image', 'client-manager' ); ?>',
					multiple: false,
					library: {
						type: 'image',
					}
				});
				image_frame.on('select', function() {
					var attachment = image_frame.state().get('selection').first().toJSON();
					$('#cm_poc_2_headshot').val(attachment.url);
					$('#cm_poc_2_headshot').next().next().attr('src', attachment.url);
				});
				image_frame.open();
			});

			$('#cm_poc_3_button').on('click', function(e) {
				e.preventDefault();
				var image_frame;
				if (image_frame) {
					image_frame.open();
				}
				// Define image_frame as wp.media object
				image_frame = wp.media({
					title: '<?php _e( 'Select Image', 'client-manager' ); ?>',
					multiple: false,
					library: {
						type: 'image',
					}
				});
				image_frame.on('select', function() {
					var attachment = image_frame.state().get('selection').first().toJSON();
					$('#cm_poc_3_headshot').val(attachment.url);
					$('#cm_poc_3_headshot').next().next().attr('src', attachment.url);
				});
				image_frame.open();
			});
		});


	</script>
	<?php
}
