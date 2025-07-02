<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function cm_render_support_tickets_tab() {
	?>
	<h2><?php _e( 'Support Tickets', 'client-manager' ); ?></h2>
	<?php



	// all required DB calls and building of HTML:
	global $wpdb;
	$table_name = $wpdb->prefix . 'clients';
	$clients = $wpdb->get_results( "SELECT * FROM $table_name" );

	$select_a_client_dropdown_html = '<option selected disabled>Select a Client...</option>';
	foreach ($clients as $clientkey => $clientvalue) {
		$select_a_client_dropdown_html = $select_a_client_dropdown_html . '<option>' . $clientvalue->business_name . ' - ' . $clientvalue->id . '</option>';
	}


	// Now let's build individual Project/Service Forms
	$website_new_ticket_form = 
	'<div class="cm-new-support-ticket-top-holder">
		<div class="cm-form-title">Create a New Support Ticket</div>
		<div class="cm-new-project-form-actual-inner-holder">
			<div class="cm-new-project-form-entry-holder">
				<label>Work Start Date/Time</label>
				<input type="datetime-local" id="startdatetime" name="startdatetime">
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Work End Date/Time</label>
				<input type="datetime-local" id="enddatetime" name="enddatetime">
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Website URL</label>
				<input id="websiteurl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Submitter Email</label>
				<input id="submitteremail" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Submitter Phone</label>
				<input id="submitterphone" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Ticket Status</label>
				<select id="status">
					<option>Not Started</option>
					<option>In Progress</option>
					<option>On Hold</option>
					<option>On Hold - Waiting on 3rd Party</option>
					<option>On Hold - Need Client Feedback</option>
					<option>Completed</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Ticket Charge?</label>
				<select id="nocharge">
					<option>No Charge</option>
					<option>Charge</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Issue Description</label>
				<textarea id="initialdescription"></textarea>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Ongoing Issue Notes</label>
				<textarea id="notes"></textarea>
			</div>
		</div>
		<button class="cm-new-project-create cm-create-new-website-support-ticket">Save & Create New Support Ticket</button>
	</div>';

	var_dump(print_r($clients, true));

	$project_html_open = '
	<div class="cm-top-project-holder">
		<div class="cm-top-project-new-holder">
			<div class="cm-top-project-new-which-client">
				<p>Select A Client:</p>
				<select id="cm-new-support-ticket-select-a-client">
					' . $select_a_client_dropdown_html . '
				</select>
			</div>';

			$new_project_html_open = '
			<div class="indiv-new-project-form-holder">

				' . $website_new_ticket_form . '

			</div>';


		$new_project_html_close = '
		</div>';

	$project_html_close = '
	</div>';




	echo $project_html_open . $new_project_html_open . $new_project_html_close . $project_html_close;

}
