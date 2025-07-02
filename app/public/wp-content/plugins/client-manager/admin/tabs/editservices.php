<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function cm_render_projects_tab() {
	?>
	<h2><?php _e( 'Edit Services', 'client-manager' ); ?></h2>
	<?php

	// all required DB calls and building of HTML:
	global $wpdb;
	$table_name = $wpdb->prefix . 'clients';
	$clients = $wpdb->get_results( "SELECT * FROM $table_name" );

	$select_a_client_dropdown_html = '<option selected disabled>Select a Client...</option>';
	foreach ($clients as $clientkey => $clientvalue) {
		$select_a_client_dropdown_html = $select_a_client_dropdown_html . '<option>' . $clientvalue->business_name . ' - ' . $clientvalue->id . '</option>';
	}

	

	$project_html_open = '
	<div class="cm-top-project-holder">
		<div class="cm-top-project-new-holder">
			<div class="cm-top-project-new-which-client">
				<p>Select A Client:</p>
				<select id="cm-edit-services-select-a-client">
					' . $select_a_client_dropdown_html . '
				</select>
			</div>';

			$new_project_html_open = '
			<div class="indiv-top-edit-services-form-holder">


			</div>';


		$new_project_html_close = '
		</div>';

	$project_html_close = '
	</div>';




	echo $project_html_open . $new_project_html_open . $new_project_html_close . $project_html_close;





	
}
