<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function cm_render_edit_current_clients_tab() {

	global $wpdb;
	$table_name = $wpdb->prefix . 'clients';
	$clients = $wpdb->get_results( "SELECT * FROM $table_name" );
	var_dump(print_r($clients, true));


// Enqueue the media uploader script
	wp_enqueue_media();

	?>
	<script type="text/javascript">

		jQuery(document).ready(function($) {

			$('.cm-client-image-logo-headshot').on('click', function(e) {
				console.log('click');

				var clientid = $(this).attr('data-clientid');

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
					$('#cm_logo-' + clientid).val(attachment.url);
					$('#cm_logo-' + clientid).next().next().attr('src', attachment.url);
				});
				image_frame.open();
			});


			$('.cm-client-image-main-headshot').on('click', function(e) {
				console.log('click');

				var clientid = $(this).attr('data-clientid');

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
					$('#cm_main_poc_headshot-' + clientid).val(attachment.url);
					$('#cm_main_poc_headshot-' + clientid).next().next().attr('src', attachment.url);
				});
				image_frame.open();
			});


			$('.cm-client-image-poc-2-headshot').on('click', function(e) {
				console.log('click');

				var clientid = $(this).attr('data-clientid');

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
					$('#cm_poc_2_headshot-' + clientid).val(attachment.url);
					$('#cm_poc_2_headshot-' + clientid).next().next().attr('src', attachment.url);
				});
				image_frame.open();
			});

			$('.cm-client-image-poc-3-headshot').on('click', function(e) {
				console.log('click');

				var clientid = $(this).attr('data-clientid');

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
					$('#cm_poc_3_headshot-' + clientid).val(attachment.url);
					$('#cm_poc_3_headshot-' + clientid).next().next().attr('src', attachment.url);
				});
				image_frame.open();
			});
		});


	</script>
	<?php


	?>
	<h2><?php _e( 'Edit Current Clients', 'client-manager' ); ?></h2>
	<p><?php _e( 'This section will allow you to edit current clients.', 'client-manager' ); ?></p>
	<?php

	$html = '<div class="cm-top-client-holder">';
	foreach ($clients as $clientkey => $clientvalue) {
		
		$html = $html . 

		'<div class="cm-indiv-client-holder">
			<div class="cm-indiv-client-name">' . $clientvalue->business_name . '</div>
			<div class="cm-indiv-table-holder">
				<table class="form-table cm-edit-a-client-table">
					<tr valign="top">
						<th scope="row"><?php _e( "Business Name", "client-manager" ); ?>Business Name</th>
						<td><input value="' . $clientvalue->business_name . '"  type="text" id="cm_business_name" name="cm_business_name" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Client Since Date", "client-manager" ); ?>Client Since Date</th>
						<td><input value="' . $clientvalue->client_since_date . '" type="date" id="cm_client_since_date" name="cm_client_since_date" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Main Location Name", "client-manager" ); ?>Main Location Name</th>
						<td><input value="' . $clientvalue->main_location_name . '" type="text" id="cm_main_location_name" name="cm_main_location_name" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Main Location Street Address", "client-manager" ); ?>Main Location Street Address</th>
						<td><input value="' . $clientvalue->main_location_street_address . '" type="text" id="cm_main_location_street_address" name="cm_main_location_street_address" class="regular-text"></td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( "Main Location Address 2", "client-manager" ); ?>Main Location Address 2</th>
						<td><input value="' . $clientvalue->main_location_address_2 . '" type="text" id="cm_main_location_address_2" name="cm_main_location_address_2" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Main Location State", "client-manager" ); ?>Main Location State</th>
						<td><input value="' . $clientvalue->main_location_state . '" type="text" id="cm_main_location_state" name="cm_main_location_state" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Main Location City", "client-manager" ); ?>Main Location City</th>
						<td><input value="' . $clientvalue->main_location_city . '" type="text" id="cm_main_location_city" name="cm_main_location_city" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Main Location Zip", "client-manager" ); ?>Main Location Zip</th>
						<td><input value="' . $clientvalue->main_location_zip . '" type="text" id="cm_main_location_zip" name="cm_main_location_zip" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "2nd Location Name", "client-manager" ); ?>2nd Location Name</th>
						<td><input value="' . $clientvalue->second_location_name . '" type="text" id="cm_second_location_name" name="cm_second_location_name" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "2nd Location Street Address", "client-manager" ); ?>2nd Location Street Address</th>
						<td><input value="' . $clientvalue->second_location_street_address . '" type="text" id="cm_second_location_street_address" name="cm_second_location_street_address" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "2nd Location Address 2", "client-manager" ); ?>2nd Location Address 2</th>
						<td><input value="' . $clientvalue->second_location_address_2 . '" type="text" id="cm_second_location_address_2" name="cm_second_location_address_2" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "2nd Location State", "client-manager" ); ?>2nd Location State</th>
						<td><input value="' . $clientvalue->second_location_state . '" type="text" id="cm_second_location_state" name="cm_second_location_state" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "2nd Location City", "client-manager" ); ?>2nd Location City</th>
						<td><input value="' . $clientvalue->second_location_city . '" type="text" id="cm_second_location_city" name="cm_second_location_city" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "2nd Location Zip", "client-manager" ); ?>2nd Location Zip</th>
						<td><input value="' . $clientvalue->second_location_zip . '" type="text" id="cm_second_location_zip" name="cm_second_location_zip" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Main POC First Name", "client-manager" ); ?>Main POC First Name</th>
						<td><input value="' . $clientvalue->main_poc_first_name . '" type="text" id="cm_main_poc_first_name" name="main_poc_first_name" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Main POC Last Name", "client-manager" ); ?>Main POC Last Name</th>
						<td><input value="' . $clientvalue->main_poc_last_name . '" type="text" id="cm_main_poc_last_name" name="main_poc_last_name" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Main POC Title", "client-manager" ); ?>Main POC Title</th>
						<td><input value="' . $clientvalue->main_poc_title . '" type="text" id="cm_main_poc_title" name="main_poc_title" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Main POC Email", "client-manager" ); ?>Main POC Email</th>
						<td><input value="' . $clientvalue->main_poc_email . '" type="email" id="cm_main_poc_email" name="main_poc_email" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Main POC Phone", "client-manager" ); ?>Main POC Phone</th>
						<td><input value="' . $clientvalue->main_poc_phone . '" type="text" id="cm_main_poc_phone" name="main_poc_phone" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Second POC First Name", "client-manager" ); ?>Second POC First Name</th>
						<td><input value="' . $clientvalue->poc_2_first_name . '" type="text" id="cm_poc_2_first_name" name="cm_poc_2_first_name" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Second POC Last Name", "client-manager" ); ?>Second POC Last Name</th>
						<td><input value="' . $clientvalue->poc_2_last_name . '" type="text" id="cm_poc_2_last_name" name="cm_poc_2_last_name" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Second POC Title", "client-manager" ); ?>Second POC Title</th>
						<td><input value="' . $clientvalue->poc_2_title . '" type="text" id="cm_poc_2_title" name="cm_poc_2_title" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Second POC Email", "client-manager" ); ?>Second POC Email</th>
						<td><input value="' . $clientvalue->poc_2_email . '" type="email" id="cm_poc_2_email" name="cm_poc_2_email" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Second POC Phone", "client-manager" ); ?>Second POC Phone</th>
						<td><input value="' . $clientvalue->poc_2_phone . '" type="text" id="cm_poc_2_phone" name="cm_poc_2_phone" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Third POC First Name", "client-manager" ); ?>Third POC First Name</th>
						<td><input value="' . $clientvalue->poc_3_first_name . '" type="text" id="cm_poc_3_first_name" name="cm_poc_3_first_name" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Third POC Last Name", "client-manager" ); ?>Third POC Last Name</th>
						<td><input value="' . $clientvalue->poc_3_last_name . '" type="text" id="cm_poc_3_last_name" name="cm_poc_3_last_name" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Third POC Title", "client-manager" ); ?>Third POC Title</th>
						<td><input value="' . $clientvalue->poc_3_title . '" type="text" id="cm_poc_3_title" name="cm_poc_3_title" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Third POC Email", "client-manager" ); ?>Third POC Email</th>
						<td><input value="' . $clientvalue->poc_3_email . '" type="email" id="cm_poc_3_email" name="cm_poc_3_email" class="regular-text"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Third POC Phone", "client-manager" ); ?>Third POC Phone</th>
						<td><input value="' . $clientvalue->poc_3_phone . '" type="text" id="cm_poc_3_phone" name="cm_poc_3_phone" class="regular-text"></td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( "Main Site Analytics Property ID", "client-manager" ); ?>Main Site Analytics Property ID</th>
						<td><input value="' . $clientvalue->cm_main_analytics_prop_id . '" type="text" id="cm_main_analytics_prop_id" name="cm_main_analytics_prop_id" class="regular-text"></td>
					</tr>


					<tr style="display:block;"></tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Logo", "client-manager" ); ?>Logo</th>
						<td>
							<input value="' . $clientvalue->logo . '" type="text" id="cm_logo-' . $clientvalue->id . '" name="cm_logo" class="regular-text">
							<button class="cm-client-image-logo-headshot" data-clientid="' . $clientvalue->id . '" type="button" id="cm_logo_button-' . $clientvalue->id . '" class="button">Choose Image</button>
							<img class="cm_addnewclient_placeholder_logo" src="' . $clientvalue->logo . '"/>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Main POC Headshot", "client-manager" ); ?>Main POC Headshot</th>
						<td>
							<input value="' . $clientvalue->main_poc_headshot . '" type="text" id="cm_main_poc_headshot-' . $clientvalue->id . '" name="cm_main_poc_headshot" class="regular-text">
							<button class="cm-client-image-main-headshot" data-clientid="' . $clientvalue->id . '" type="button" id="cm_main_poc_button-' . $clientvalue->id . '" class="button">Choose Image</button>
							<img class="cm_addnewclient_placeholder_logo" src="' . $clientvalue->main_poc_headshot . '"/>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Second POC Headshot", "client-manager" ); ?>Second POC Headshot</th>
						<td>
							<input value="' . $clientvalue->poc_2_headshot . '" type="text" id="cm_poc_2_headshot-' . $clientvalue->id . '" name="cm_poc_2_headshot" class="regular-text">
							<button class="cm-client-image-poc-2-headshot" data-clientid="' . $clientvalue->id . '" type="button" id="cm_poc_2_button-' . $clientvalue->id . '" class="button">Choose Image</button>
							<img class="cm_addnewclient_placeholder_logo" src="' . $clientvalue->poc_2_headshot . '"/>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Third POC Headshot", "client-manager" ); ?>Third POC Headshot</th>
						<td>
							<input value="' . $clientvalue->poc_3_headshot . '" type="text" id="cm_poc_3_headshot-' . $clientvalue->id . '" name="cm_poc_3_headshot" class="regular-text">
							<button class="cm-client-image-poc-3-headshot" data-clientid="' . $clientvalue->id . '" type="button" id="cm_poc_3_button-' . $clientvalue->id . '" class="button">Choose Image</button>
							<img class="cm_addnewclient_placeholder_logo" src="' . $clientvalue->poc_3_headshot . '"/>
						</td>
					</tr>
				</table>
				<p class="submit">
					<button class="cm_edit_client_button" data-clientid="' . $clientvalue->id . '" type="button" id="cm_edit_client_button-' . $clientvalue->id . '" class="button button-primary"><?php _e( "Save Edits", "client-manager" ); ?>Save Edits</button>
				</p>
			</div>
		</div>';





	}

	$html = $html . '</div>';

	echo _e( $html, 'client-manager' );


}
