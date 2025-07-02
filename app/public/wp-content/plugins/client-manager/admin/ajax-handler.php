<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'wp_ajax_cm_add_client', 'cm_add_client' );
add_action( 'wp_ajax_cm_edit_client', 'cm_edit_client' );
add_action( 'wp_ajax_cm_new_project_get_creative_brief', 'cm_new_project_get_creative_brief' );
add_action( 'wp_ajax_cm_edit_services', 'cm_edit_services' );
add_action( 'wp_ajax_cm_new_project_get_launch_checklist', 'cm_new_project_get_launch_checklist' );
add_action( 'wp_ajax_cm_service_website_create', 'cm_service_website_create' );
add_action( 'wp_ajax_cm_service_website_edit', 'cm_service_website_edit' );
add_action( 'wp_ajax_cm_service_hosting_edit', 'cm_service_hosting_edit' );
add_action( 'wp_ajax_cm_service_seo_edit', 'cm_service_seo_edit' );
add_action( 'wp_ajax_cm_service_logo_edit', 'cm_service_logo_edit' );
add_action( 'wp_ajax_cm_service_maintenance_edit', 'cm_service_maintenance_edit' );
add_action( 'wp_ajax_cm_service_website_creative_brief_create', 'cm_service_website_creative_brief_create' );
add_action( 'wp_ajax_cm_service_website_launch_checklist_create', 'cm_service_website_launch_checklist_create' );
add_action( 'wp_ajax_cm_service_website_creative_brief_edit', 'cm_service_website_creative_brief_edit' );
add_action( 'wp_ajax_cm_service_website_launch_checklist_edit', 'cm_service_website_launch_checklist_edit' );
add_action( 'wp_ajax_cm_service_website_hosting_create', 'cm_service_website_hosting_create' );
add_action( 'wp_ajax_cm_service_seo_related_create', 'cm_service_seo_related_create' );
add_action( 'wp_ajax_cm_service_website_maintenance_create', 'cm_service_website_maintenance_create' );
add_action( 'wp_ajax_cm_service_logo_create', 'cm_service_logo_create' );
add_action( 'wp_ajax_cm_service_website_support_ticket_create', 'cm_service_website_support_ticket_create' );
add_action( 'wp_ajax_cm_get_existing_support_tickets', 'cm_get_existing_support_tickets' );
add_action( 'wp_ajax_cm_support_ticket_edit', 'cm_support_ticket_edit' );
add_action( 'wp_ajax_bld_csv_file_upload', 'bld_csv_file_upload');
add_action( 'wp_ajax_check_and_delete_file', 'check_and_delete_file');


function validate_ajax_nonce() {
    check_ajax_referer('global_ajax_nonce', 'nonce'); // Single function for nonce validation
}



function check_and_delete_file() {
    validate_ajax_nonce(); // Standardized nonce check

    if (!isset($_POST['file_name']) || !isset($_POST['business_name'])) {
        error_log('Missing file_name or business_name.');
        wp_send_json(['error' => 'Missing required fields.']);
        return;
    }

    $upload_dir = wp_upload_dir();
    $business_name = sanitize_text_field($_POST['business_name']);
    $file_name = sanitize_file_name($_POST['file_name']);

    // **Corrected file path to match the upload location**
    $file_path = trailingslashit($upload_dir['basedir']) . "client_files/{$business_name}/seo_assets/{$service_unique_name}/{$file_name}";

    // Debugging: Log file existence check
    error_log("Checking file path: " . $file_path);

    if (file_exists($file_path)) {
        error_log("File found: " . $file_path);
        if (unlink($file_path)) {
            error_log("File deleted: " . $file_path);
            wp_send_json(['deleted' => true]);
        } else {
            error_log("Failed to delete file: " . $file_path);
            wp_send_json(['error' => 'Unable to delete file.']);
        }
    } else {
        error_log("File not found: " . $file_path);
        wp_send_json(['not_found' => true]);
    }
}



function bld_csv_file_upload() {
    // Validate nonce
    check_ajax_referer('global_ajax_nonce', 'nonce');

    // Debugging: Log incoming data
    error_log('AJAX function "bld_csv_file_upload" triggered.');

    // Check if a file is being uploaded
    if (empty($_FILES['file']) || empty($_POST['business_name'])) {
        error_log('Missing file or business name.');
        wp_send_json(['error' => 'No file uploaded or business name missing.']);
    }

    $uploaded_file = $_FILES['file'];
    $business_name = sanitize_text_field($_POST['business_name']);
    $service_unique_name = sanitize_text_field($_POST['service_unique_name']);
    $upload_dir = wp_upload_dir();
    $custom_dir = trailingslashit($upload_dir['basedir']) . "client_files/{$business_name}/seo_assets/{$service_unique_name}/";

    // Ensure the directory exists
    if (!file_exists($custom_dir)) {
        if (!wp_mkdir_p($custom_dir)) {
            error_log("Failed to create directory: $custom_dir");
            wp_send_json(['error' => 'Failed to create upload directory.']);
        }
    }

    $filename = wp_unique_filename($custom_dir, $uploaded_file['name']);
    $file_path = $custom_dir . $filename;

    // Debugging: Log file move attempt
    error_log("Attempting to move file to: $file_path");

    if (move_uploaded_file($uploaded_file['tmp_name'], $file_path)) {
        $file_url = $upload_dir['baseurl'] . "/client_files/{$business_name}/seo_assets/{$service_unique_name}/{$filename}";
        error_log("File uploaded successfully: $file_url");
        wp_send_json(['url' => $file_url]);
    } else {
        error_log('File upload failed.');
        wp_send_json(['error' => 'Error moving the uploaded file.']);
    }

    wp_die(); // Ensure WordPress stops execution
}





function cm_add_client() {
	validate_ajax_nonce(); // Standardized nonce check

	global $wpdb;
	$table_name = $wpdb->prefix . 'clients';

	// Step 1: Prepare the data (without dburl initially)
	$data = array(
	    'business_name' => sanitize_text_field($_POST['business_name']),
	    'client_since_date' => sanitize_text_field($_POST['client_since_date']),
	    'logo' => sanitize_text_field($_POST['logo']),
	    'main_location_address_2' => sanitize_text_field($_POST['main_location_address_2']),
	    'main_location_name' => sanitize_text_field($_POST['main_location_name']),
	    'main_location_state' => sanitize_text_field($_POST['main_location_state']),
	    'main_location_city' => sanitize_text_field($_POST['main_location_city']),
	    'main_location_street_address' => sanitize_text_field($_POST['main_location_street_address']),
	    'main_location_zip' => sanitize_text_field($_POST['main_location_zip']),
	    'main_poc_email' => sanitize_email($_POST['main_poc_email']),
	    'main_poc_first_name' => sanitize_text_field($_POST['main_poc_first_name']),
	    'main_poc_headshot' => sanitize_text_field($_POST['main_poc_headshot']),
	    'main_poc_last_name' => sanitize_text_field($_POST['main_poc_last_name']),
	    'main_poc_phone' => sanitize_text_field($_POST['main_poc_phone']),
	    'main_poc_title' => sanitize_text_field($_POST['main_poc_title']),
	    'poc_2_email' => sanitize_email($_POST['poc_2_email']),
	    'poc_2_first_name' => sanitize_text_field($_POST['poc_2_first_name']),
	    'poc_2_headshot' => sanitize_text_field($_POST['poc_2_headshot']),
	    'poc_2_last_name' => sanitize_text_field($_POST['poc_2_last_name']),
	    'poc_2_phone' => sanitize_text_field($_POST['poc_2_phone']),
	    'poc_2_title' => sanitize_text_field($_POST['poc_2_title']),
	    'poc_3_email' => sanitize_email($_POST['poc_3_email']),
	    'poc_3_first_name' => sanitize_text_field($_POST['poc_3_first_name']),
	    'poc_3_headshot' => sanitize_text_field($_POST['poc_3_headshot']),
	    'poc_3_last_name' => sanitize_text_field($_POST['poc_3_last_name']),
	    'poc_3_phone' => sanitize_text_field($_POST['poc_3_phone']),
	    'cm_main_analytics_prop_id' => sanitize_text_field($_POST['cm_main_analytics_prop_id']),
	    'poc_3_title' => sanitize_text_field($_POST['poc_3_title']),
	    'second_location_address_2' => sanitize_text_field($_POST['second_location_address_2']),
	    'second_location_name' => sanitize_text_field($_POST['second_location_name']),
	    'second_location_state' => sanitize_text_field($_POST['second_location_state']),
	    'second_location_city' => sanitize_text_field($_POST['second_location_city']),
	    'second_location_street_address' => sanitize_text_field($_POST['second_location_street_address']),
	    'second_location_zip' => sanitize_text_field($_POST['second_location_zip'])
	);

	// Step 2: Insert client into database (without dburl)
	$wpdb->insert($table_name, $data);

	// Step 3: Get the newly inserted client ID
	$client_id = $wpdb->insert_id;

	// Ensure client ID is valid before proceeding
	if ($client_id) {
	    // Step 4: Generate unique dashboard URL including client ID
	    $sanitized_name = preg_replace('/[^a-zA-Z0-9-_]/', '', strtolower(str_replace(' ', '-', trim($_POST['business_name']))));
	    $unique_id = substr(md5(uniqid($sanitized_name, true)), 0, 8); // Short hash for uniqueness
	    $unique_slug = $sanitized_name . '-' . $unique_id . '-' . $client_id; // Include client ID
	    $dashboard_url = "/insights-dashboard?client=" . urlencode($unique_slug);

	    // Step 5: Update the client's row with the correct dburl
	    $wpdb->update(
	        $table_name,
	        array('dburl' => $dashboard_url),
	        array('id' => $client_id)
	    );


	    // Step 6: now generate and save the client's unique api key
        // Generate a 32-character hexadecimal API key
        $api_key = bin2hex(random_bytes(16));
        // Insert the API key into your custom table (client_reporting_info)
        $api_table = $wpdb->prefix . 'client_reporting_info';
        $wpdb->insert(
            $api_table,
            array(
                'client_id'  => sanitize_text_field($client_id),
                'remotecode' => sanitize_text_field($api_key)
            ),
            array('%s', '%s')
        );
        // Optionally, you could send the API key back in the response for administrative purposes:
        // $response_api_key = $api_key;
	}


	// Get the upload directory
    $upload_dir = wp_upload_dir();
    $client_dir = $upload_dir['basedir'] . '/client_files/' . $_POST['business_name'];
    // Create the client's directory if it doesn't exist
    if (!file_exists($client_dir)) {
        wp_mkdir_p($client_dir);
    }

    // Get the upload directory
    $client_dir = $upload_dir['basedir'] . '/client_files/' . $_POST['business_name'] . '/logo_brandbook/';
    // Create the client's directory if it doesn't exist
    if (!file_exists($client_dir)) {
        wp_mkdir_p($client_dir);
    }

    // Get the upload directory
    $client_dir = $upload_dir['basedir'] . '/client_files/' . $_POST['business_name'] . '/awards_certs/';
    // Create the client's directory if it doesn't exist
    if (!file_exists($client_dir)) {
        wp_mkdir_p($client_dir);
    }

    // Get the upload directory
    $client_dir = $upload_dir['basedir'] . '/client_files/' . $_POST['business_name'] . '/before_afters/';
    // Create the client's directory if it doesn't exist
    if (!file_exists($client_dir)) {
        wp_mkdir_p($client_dir);
    }

    // Get the upload directory
    $client_dir = $upload_dir['basedir'] . '/client_files/' . $_POST['business_name'] . '/work_media/';
    // Create the client's directory if it doesn't exist
    if (!file_exists($client_dir)) {
        wp_mkdir_p($client_dir);
    }

    // Get the upload directory
    $client_dir = $upload_dir['basedir'] . '/client_files/' . $_POST['business_name'] . '/seo_assets/';
    // Create the client's directory if it doesn't exist
    if (!file_exists($client_dir)) {
        wp_mkdir_p($client_dir);
    }

	wp_send_json_success( array( 'message' => __( 'Client added successfully', 'client-manager' ) ) );
}

function cm_edit_client() {
	//validate_ajax_nonce(); // Standardized nonce check
	validate_ajax_nonce(); // Standardized nonce check

	$clientid = sanitize_text_field( $_POST['client_id']);

	error_log( $_POST['poc_3_email'] );

	$data = array(
		'business_name' => sanitize_text_field( $_POST['business_name'] ),
		'client_since_date' => sanitize_text_field( $_POST['client_since_date'] ),
		'logo' => sanitize_text_field( $_POST['logo'] ),
		'main_location_address_2' => sanitize_text_field( $_POST['main_location_address_2'] ),
		'main_location_name' => sanitize_text_field( $_POST['main_location_name'] ),
		'main_location_state' => sanitize_text_field( $_POST['main_location_state'] ),
		'main_location_city' => sanitize_text_field( $_POST['main_location_city'] ),
		'main_location_street_address' => sanitize_text_field( $_POST['main_location_street_address'] ),
		'main_location_zip' => sanitize_text_field( $_POST['main_location_zip'] ),
		'main_poc_email' => sanitize_email( $_POST['main_poc_email'] ),
		'main_poc_first_name' => sanitize_text_field( $_POST['main_poc_first_name'] ),
		'main_poc_headshot' => sanitize_text_field( $_POST['main_poc_headshot'] ),
		'main_poc_last_name' => sanitize_text_field( $_POST['main_poc_last_name'] ),
		'main_poc_phone' => sanitize_text_field( $_POST['main_poc_phone'] ),
		'main_poc_title' => sanitize_text_field( $_POST['main_poc_title'] ),
		'poc_2_email' => sanitize_email( $_POST['poc_2_email'] ),
		'poc_2_first_name' => sanitize_text_field( $_POST['poc_2_first_name'] ),
		'poc_2_headshot' => sanitize_text_field( $_POST['poc_2_headshot'] ),
		'poc_2_last_name' => sanitize_text_field( $_POST['poc_2_last_name'] ),
		'poc_2_phone' => sanitize_text_field( $_POST['poc_2_phone'] ),
		'poc_2_title' => sanitize_text_field( $_POST['poc_2_title'] ),
		'poc_3_email' => sanitize_email( $_POST['poc_3_email'] ),
		'poc_3_first_name' => sanitize_text_field( $_POST['poc_3_first_name'] ),
		'poc_3_headshot' => sanitize_text_field( $_POST['poc_3_headshot'] ),
		'poc_3_last_name' => sanitize_text_field( $_POST['poc_3_last_name'] ),
		'poc_3_phone' => sanitize_text_field( $_POST['poc_3_phone'] ),
		'cm_main_analytics_prop_id' => sanitize_text_field( $_POST['cm_main_analytics_prop_id'] ),
		'poc_3_title' => sanitize_text_field( $_POST['poc_3_title'] ),
		'second_location_address_2' => sanitize_text_field( $_POST['second_location_address_2'] ),
		'second_location_name' => sanitize_text_field( $_POST['second_location_name'] ),
		'second_location_state' => sanitize_text_field( $_POST['second_location_state'] ),
		'second_location_city' => sanitize_text_field( $_POST['second_location_city'] ),
		'second_location_street_address' => sanitize_text_field( $_POST['second_location_street_address'] ),
		'second_location_zip' => sanitize_text_field( $_POST['second_location_zip'] )
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'clients';

	$where = array( 'id' => intval( $clientid ) );

	$wpdb->update(
		$table_name,
		$data,
		$where
	);
	wp_send_json_success( array( 'message' => __( 'Client edited successfully', 'client-manager' ) ) );
}




function cm_new_project_get_creative_brief() {
	validate_ajax_nonce(); // Standardized nonce check

	$clientid = sanitize_text_field( $_POST['client_id']);
	global $wpdb;
	$table_name = $wpdb->prefix . 'website_creative_brief'; // Replace 'your_table_name' with your actual table name

	error_log($clientid);

	$relevant_creative_briefs = $wpdb->get_results( 
	    $wpdb->prepare(
	        "SELECT * FROM $table_name WHERE client_id = %s", 
	        $clientid 
	    )
	);

	error_log(print_r($relevant_creative_briefs,true));

	// Return the results as JSON
    wp_send_json($relevant_creative_briefs);
}


function cm_get_existing_support_tickets() {
	validate_ajax_nonce(); // Standardized nonce check

	$clientid = sanitize_text_field( $_POST['clientid']);
	global $wpdb;
	$table_name = $wpdb->prefix . 'support_tickets'; // Replace 'your_table_name' with your actual table name

	error_log($clientid);

	$relevant_support_tickets = $wpdb->get_results( 
	    $wpdb->prepare(
	        "SELECT * FROM $table_name WHERE clientid = %s", 
	        $clientid 
	    )
	);

	error_log(print_r($relevant_support_tickets,true));

	$returnhtml = '<div class="indiv-top-edit-services-form-holder">';
	foreach ($relevant_support_tickets as $ticketkey => $ticketvalue) {

		$ticket_status_select = '';
		if ( 'Not Started' === $ticketvalue->status ) {
			$ticket_status_select = '<option selected>Not Started</option><option>In Progress</option><option>On Hold</option><option>On Hold - Waiting on 3rd Party</option><option>On Hold - Need Client Feedback</option><option>Completed</option>';
		} else if( 'In Progress' === $ticketvalue->status ) {
			$ticket_status_select = '<option>Not Started</option><option selected>In Progress</option><option>On Hold</option><option>On Hold - Waiting on 3rd Party</option><option>On Hold - Need Client Feedback</option><option>Completed</option>';
		}  else if( 'On Hold' === $ticketvalue->status ) {
			$ticket_status_select = '<option>Not Started</option><option>In Progress</option><option selected>On Hold</option><option>On Hold - Waiting on 3rd Party</option><option>On Hold - Need Client Feedback</option><option>Completed</option>';
		}  else if( 'On Hold - Waiting on 3rd Party' === $ticketvalue->status ) {
			$ticket_status_select = '<option>Not Started</option><option>In Progress</option><option>On Hold</option><option selected>On Hold - Waiting on 3rd Party</option><option>On Hold - Need Client Feedback</option><option>Completed</option>';
		}  else if( 'On Hold - Need Client Feedback' === $ticketvalue->status ) {
			$ticket_status_select = '<option>Not Started</option><option>In Progress</option><option>On Hold</option><option selected>On Hold - Waiting on 3rd Party</option><option selected>On Hold - Need Client Feedback</option><option>Completed</option>';
		}  else {
			$ticket_status_select = '<option>Not Started</option><option>In Progress</option><option>On Hold</option><option>On Hold - Waiting on 3rd Party</option><option>On Hold - Need Client Feedback</option><option selected>Completed</option>';
		}

		$ticket_charge_select = '';
		if ( 'No Charge' === $ticketvalue->nocharge ) {
			$ticket_charge_select = '<option selected>No Charge</option><option>Charge</option>';
		} else {
			$ticket_charge_select = '<option>No Charge</option><option selected>Charge</option>';
		}
		

		$returnhtml = $returnhtml . 
		'<div class="cm-indiv-client-holder">
			<div class="cm-indiv-client-name">Ticket #' . ( $ticketkey + 1 ) . ' (' . $ticketvalue->websiteurl . ')</div>
			<div class="cm-indiv-edit-service-table-holder">
				<div class="cm-new-project-form-entry-holder">
					<label>Work Start Date/Time</label>
					<input type="datetime-local" id="startdatetime" name="startdatetime" value="' . $ticketvalue->startdatetime . '">
				</div>
				<div class="cm-new-project-form-entry-holder">
					<label>Work End Date/Time</label>
					<input type="datetime-local" id="enddatetime" name="enddatetime" value="' . $ticketvalue->enddatetime . '">
				</div>
				<div class="cm-new-project-form-entry-holder">
					<label>Website URL</label>
					<input id="websiteurl" type="text" value="' . $ticketvalue->websiteurl . '"/>
				</div>
				<div class="cm-new-project-form-entry-holder">
					<label>Submitter Email</label>
					<input id="submitteremail" type="text" value="' . $ticketvalue->submitteremail . '"/>
				</div>
				<div class="cm-new-project-form-entry-holder">
					<label>Submitter Phone</label>
					<input id="submitterphone" type="text" value="' . $ticketvalue->submitterphone . '"/>
				</div>
				<div class="cm-new-project-form-entry-holder">
					<label>Ticket Status</label>
					<select id="status">
						' . $ticket_status_select . '
					</select>
				</div>
				<div class="cm-new-project-form-entry-holder">
					<label>Ticket Charge?</label>
					<select id="nocharge">
						' . $ticket_charge_select . '
					</select>
				</div>
				<div class="cm-new-project-form-entry-holder">
					<label>Issue Description</label>
					<textarea id="initialdescription">' . $ticketvalue->initialdescription . '</textarea>
				</div>
				<div class="cm-new-project-form-entry-holder">
					<label>Ongoing Issue Notes</label>
					<textarea id="notes">' . $ticketvalue->notes . '</textarea>
				</div>
				<button data-ticketid="' . $ticketvalue->id . '" class="cm-edit-service cm-edit-indiv-support-ticket">Edit Support Ticket</button>
			</div>
		</div>';
















	}
	$returnhtml = $returnhtml . '</div>';

	// Return the results as JSON
    wp_die($returnhtml);
}



function cm_edit_services() {
	validate_ajax_nonce(); // Standardized nonce check

	$clientid = sanitize_text_field( $_POST['client_id']);
	error_log( 'clientid: ' . $clientid );
	// Get all tables
	global $wpdb;
    $tables = $wpdb->get_results("SHOW TABLES LIKE '%services_%'");

    // Prepare an array to hold the results
    $results = array();

    // Loop through each table
    foreach ($tables as $table) {
        $table_name = array_values((array)$table)[0];

        error_log( 'Name of table: ' . $table_name);

        // Query to get entries where client_id equals the given clientid
        $entries = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name WHERE client_id = %d",
            $clientid
        ));

        // Add the results to the main results array
        if (!empty($entries)) {
        	error_log( 'Name of table this client is associated with: ' . $table_name);
            $results[$table_name] = $entries;
        }
    }


    // Loop through the response
	$output = '';
    foreach ($results as $key => $value) {
    	
        //$output .= '<div>Key: ' . $key . '</div>';

        // Check if the value is an array
        if (is_array($value)) {
            foreach ($value as $index => $item) {

            	if ( false !== stripos($key, 'custom_development') ){

            	} else if( false !== stripos($key, 'misc') ){

            	} else if( false !== stripos($key, 'logo') ){

            		// Now let's build the logo form...
					$output =  $output . 
					'<div class="cm-indiv-client-holder">
						<div class="cm-indiv-client-name">Logo Project (' . $item->project_unique_name . ')</div>
						<div class="cm-indiv-edit-service-table-holder">
							<div class="cm-new-project-form-entry-holder">
								<label>Logo Project Name</label>
								<input value="' . $item->project_unique_name . '" id="project_unique_name" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Current Logo URL</label>
								<input value="' . $item->currentlogourl . '" id="currentlogourl" class="jre-validateurl" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Draft 1 URL</label>
								<input value="' . $item->draft1url . '" id="draft1url" class="jre-validateurl" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Draft 1 Color Codes</label>
								<input value="' . $item->draft1colorcodes . '" id="draft1colorcodes" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Draft 1 Fonts</label>
								<input value="' . $item->draft1fonts . '" id="draft1fonts" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Draft 1 Notes</label>
								<textarea id="draft1notes">' . $item->draft1notes . '</textarea>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Draft 2 URL</label>
								<input value="' . $item->draft2url . '" id="draft2url" class="jre-validateurl" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Draft 2 Color Codes</label>
								<input value="' . $item->draft2colorcodes . '" id="draft2colorcodes" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Draft 2 Fonts</label>
								<input value="' . $item->draft2fonts . '" id="draft2fonts" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Draft 2 Notes</label>
								<textarea id="draft2notes">' . $item->draft2notes . '</textarea>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Draft 3 URL</label>
								<input value="' . $item->draft3url . '" id="draft3url" class="jre-validateurl" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Draft 3 Color Codes</label>
								<input value="' . $item->draft3colorcodes . '" id="draft3colorcodes" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Draft 3 Fonts</label>
								<input value="' . $item->draft3fonts . '" id="draft3fonts" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Draft 3 Notes</label>
								<textarea id="draft3notes">' . $item->draft3notes . '</textarea>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Final Logo URL</label>
								<input value="' . $item->finallogourl . '" id="finallogourl" class="jre-validateurl" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Final Logo Color Codes</label>
								<input value="' . $item->finallogocolorcodes . '" id="finallogocolorcodes" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Final Logo Fonts</label>
								<input value="' . $item->finallogofonts . '" id="finallogofonts" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Final Logo Variant URL 1</label>
								<input value="' . $item->finallogovarianturl1 . '" id="finallogovarianturl1" class="jre-validateurl" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Final Logo Variant URL 2</label>
								<input value="' . $item->finallogovarianturl2 . '" id="finallogovarianturl2" class="jre-validateurl" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Final Logo Variant URL 3</label>
								<input value="' . $item->finallogovarianturl3 . '" id="finallogovarianturl3" class="jre-validateurl" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Final Favicon</label>
								<input value="' . $item->finalfavicon . '" id="finalfavicon" class="jre-validateurl" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Zip Download URL</label>
								<input value="' . $item->zipdownloadurl . '" id="zipdownloadurl" class="jre-validateurl" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Final Logo Notes</label>
								<textarea id="finallogonotes">' . $item->finallogonotes . '</textarea>
							</div>
							<button data-clientid="' . $item->id . '" class="cm-edit-service cm-edit-service-logo-service">Edit Service</button>
						</div>
					</div>';

            	} else if( false !== stripos($key, 'paid_ads_related') ) {

            	} else if( false !== stripos($key, 'services_seo_related') ) {

            		//error_log(var_dump($item));

            		// Now let's build the SEO related services form...


					// Build the GBP parts...
					$gbplinkandaccess1 = $item->gbplinkandaccess1;
					if (strrpos($gbplinkandaccess1, ",") !== false) {
					    $lastCommaPos = strrpos($gbplinkandaccess1, ",");
					    $urlPart1 = substr($gbplinkandaccess1, 0, $lastCommaPos);
					    $additionalInfo1 = substr($gbplinkandaccess1, $lastCommaPos + 1);
					    $additionalInfo1 = trim($additionalInfo1);
					} else {
					    $urlPart1 = $gbplinkandaccess1;
					    $additionalInfo1 = ""; // No additional info
					}
					$gbp_select1 = '';
            		if ( 'Yes' === $additionalInfo1 ) {
            			$gbp_select1 = '<option selected>Yes</option><option>No</option><option>N/A</option>';
            		} else if( 'No' === $additionalInfo1 ) {
            			$gbp_select1 = '<option>Yes</option><option selected>No</option><option>N/A</option>';
            		}  else if( 'N/A' === $additionalInfo1 ) {
            			$gbp_select1 = '<option>Yes</option><option>No</option><option selected>N/A</option>';
            		}


					$gbplinkandaccess2 = $item->gbplinkandaccess2;
					if (strrpos($gbplinkandaccess2, ",") !== false) {
					    $lastCommaPos = strrpos($gbplinkandaccess2, ",");
					    $urlPart2 = substr($gbplinkandaccess2, 0, $lastCommaPos);
					    $additionalInfo2 = substr($gbplinkandaccess2, $lastCommaPos + 1);
					    $additionalInfo2 = trim($additionalInfo2);
					} else {
					    $urlPart2 = $gbplinkandaccess2;
					    $additionalInfo2 = ""; // No additional info
					}
					$gbp_select2 = '';
            		if ( 'Yes' === $additionalInfo2 ) {
            			$gbp_select2 = '<option selected>Yes</option><option>No</option><option>N/A</option>';
            		} else if( 'No' === $additionalInfo2 ) {
            			$gbp_select2 = '<option>Yes</option><option selected>No</option><option>N/A</option>';
            		}  else if( 'N/A' === $additionalInfo2 ) {
            			$gbp_select2 = '<option>Yes</option><option>No</option><option selected>N/A</option>';
            		}


            		$gbplinkandaccess3 = $item->gbplinkandaccess3;
					if (strrpos($gbplinkandaccess3, ",") !== false) {
					    $lastCommaPos = strrpos($gbplinkandaccess3, ",");
					    $urlPart3 = substr($gbplinkandaccess3, 0, $lastCommaPos);
					    $additionalInfo3 = substr($gbplinkandaccess3, $lastCommaPos + 1);
					    $additionalInfo3 = trim($additionalInfo3);
					} else {
					    $urlPart3 = $gbplinkandaccess3;
					    $additionalInfo3 = ""; // No additional info
					}
					$gbp_select3 = '';
            		if ( 'Yes' === $additionalInfo3 ) {
            			$gbp_select3 = '<option selected>Yes</option><option>No</option><option>N/A</option>';
            		} else if( 'No' === $additionalInfo3 ) {
            			$gbp_select3 = '<option>Yes</option><option selected>No</option><option>N/A</option>';
            		}  else if( 'N/A' === $additionalInfo3 ) {
            			$gbp_select3 = '<option>Yes</option><option>No</option><option selected>N/A</option>';
            		}

            		$gbplinkandaccess4 = $item->gbplinkandaccess4;
					if (strrpos($gbplinkandaccess4, ",") !== false) {
					    $lastCommaPos = strrpos($gbplinkandaccess4, ",");
					    $urlPart4 = substr($gbplinkandaccess4, 0, $lastCommaPos);
					    $additionalInfo4 = substr($gbplinkandaccess4, $lastCommaPos + 1);
					    $additionalInfo4 = trim($additionalInfo4);
					} else {
					    $urlPart4 = $gbplinkandaccess4;
					    $additionalInfo4 = ""; // No additional info
					}
					$gbp_select4 = '';
            		if ( 'Yes' === $additionalInfo4 ) {
            			$gbp_select4 = '<option selected>Yes</option><option>No</option><option>N/A</option>';
            		} else if( 'No' === $additionalInfo4 ) {
            			$gbp_select4 = '<option>Yes</option><option selected>No</option><option>N/A</option>';
            		}  else if( 'N/A' === $additionalInfo4 ) {
            			$gbp_select4 = '<option>Yes</option><option>No</option><option selected>N/A</option>';
            		}

            		$gbplinkandaccess5 = $item->gbplinkandaccess5;
					if (strrpos($gbplinkandaccess5, ",") !== false) {
					    $lastCommaPos = strrpos($gbplinkandaccess5, ",");
					    $urlPart5 = substr($gbplinkandaccess5, 0, $lastCommaPos);
					    $additionalInfo5 = substr($gbplinkandaccess5, $lastCommaPos + 1);
					    $additionalInfo5 = trim($additionalInfo5);
					} else {
					    $urlPart5 = $gbplinkandaccess5;
					    $additionalInfo5 = ""; // No additional info
					}
					$gbp_select5 = '';
            		if ( 'Yes' === $additionalInfo5 ) {
            			$gbp_select5 = '<option selected>Yes</option><option>No</option><option>N/A</option>';
            		} else if( 'No' === $additionalInfo5 ) {
            			$gbp_select5 = '<option>Yes</option><option selected>No</option><option>N/A</option>';
            		}  else if( 'N/A' === $additionalInfo5 ) {
            			$gbp_select5 = '<option>Yes</option><option>No</option><option selected>N/A</option>';
            		}

					$ga_seo_access_select = '';
            		if ( 'Not Yet' === $item->googleanalyticsaccess ) {
            			$ga_seo_access_select = '<option selected>Not Yet</option><option>Yes - Client Granted Access</option><option>Yes - Level Up Created Analytics Account</option><option>N/A</option>';
            		} else if( 'Yes - Client Granted Access' === $item->googleanalyticsaccess ) {
            			$ga_seo_access_select = '<option>Not Yet</option><option selected>Yes - Client Granted Access</option><option>Yes - Level Up Created Analytics Account</option><option>N/A</option>';
            		}  else if( 'Yes - Level Up Created Analytics Account' === $item->googleanalyticsaccess ) {
            			$ga_seo_access_select = '<option>Not Yet</option><option>Yes - Client Granted Access</option><option selected>Yes - Level Up Created Analytics Account</option><option>N/A</option>';
            		}  else if( 'N/A' === $item->googleanalyticsaccess ) {
            			$ga_seo_access_select = '<option>Not Yet</option><option>Yes - Client Granted Access</option><option>Yes - Level Up Created Analytics Account</option><option selected>N/A</option>';
            		}

            		$search_console_seo_access_select = '';
            		if ( 'Not Yet' === $item->searchconsoleaccess ) {
            			$search_console_seo_access_select = '<option selected>Not Yet</option><option>Yes - Client Granted Access</option><option>Yes - Level Up Created Analytics Account</option><option>N/A</option>';
            		} else if( 'Yes - Client Granted Access' === $item->searchconsoleaccess ) {
            			$search_console_seo_access_select = '<option>Not Yet</option><option selected>Yes - Client Granted Access</option><option>Yes - Level Up Created Analytics Account</option><option>N/A</option>';
            		}  else if( 'Yes - Level Up Created Analytics Account' === $item->searchconsoleaccess ) {
            			$search_console_seo_access_select = '<option>Not Yet</option><option>Yes - Client Granted Access</option><option selected>Yes - Level Up Created Analytics Account</option><option>N/A</option>';
            		}  else if( 'N/A' === $item->searchconsoleaccess ) {
            			$search_console_seo_access_select = '<option>Not Yet</option><option>Yes - Client Granted Access</option><option>Yes - Level Up Created Analytics Account</option><option selected>N/A</option>';
            		}

            		$bldsubmitted = '';
            		if ( 'Yes' === $item->bldsubmitted ) {
            			$bldsubmitted = '<option selected>Yes</option><option>No</option><option>Only Some Locations</option><option>N/A</option>';
            		} else if( 'No' === $item->bldsubmitted ) {
            			$bldsubmitted = '<option>Yes</option><option selected>No</option><option>Only Some Locations</option><option>N/A</option>';
            		}  else if( 'Only Some Locations' === $item->bldsubmitted ) {
            			$bldsubmitted = '<option>Yes</option><option>No</option><option selected>Only Some Locations</option><option>N/A</option>';
            		}  else if( 'N/A' === $item->bldsubmitted ) {
            			$bldsubmitted = '<option>Yes</option><option>No</option><option>Only Some Locations</option><option selected>N/A</option>';
            		}

            		$periodcomplete = '';
            		if ( 'Yes' === $item->periodcomplete ) {
            			$periodcomplete = '<option selected>Yes</option><option>No</option><option>N/A</option>';
            		} else if( 'No' === $item->periodcomplete ) {
            			$periodcomplete = '<option>Yes</option><option selected>No</option><option>N/A</option>';
            		}  else if( 'N/A' === $item->periodcomplete ) {
            			$periodcomplete = '<option>Yes</option><option>No</option><option selected>N/A</option>';
            		}









					$output =  $output . '

					<div class="cm-indiv-client-holder">
						<div class="cm-indiv-client-name">SEO-Related Services (' . $item->service_unique_name . ')</div>
						<div class="cm-indiv-edit-service-table-holder">
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Project Name</label>
						    <input value="' . $item->service_unique_name . '" id="service_unique_name" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>SEO Services Start Date</label>
						    <input value="' . $item->startdate . '" id="startdate" type="date" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>SEO Services End Date</label>
						    <input value="' . $item->enddate . '" id="enddate" type="date" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Monthly Amount</label>
						    <input value="' . $item->monthlyamount . '" id="monthlyamount" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Website URL</label>
						    <input value="' . $item->websiteurl . '" id="websiteurl" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Website Login</label>
						    <input value="' . $item->websitelogin . '" id="websitelogin" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Website password</label>
						    <input value="' . $item->websitepassword . '" id="websitepassword" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>GBP Link 1</label>
						    <input value="' . $urlPart1 . '" id="gbp1link" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>GBP 1 Access?</label>
						    <select id="gbp1access">
						      ' . $gbp_select1 . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>GBP Link 2</label>
						    <input value="' . $urlPart2 . '" id="gbp2link" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>GBP 2 Access?</label>
						    <select id="gbp2access">
						      ' . $gbp_select2 . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>GBP Link 3</label>
						    <input value="' . $urlPart3 . '" id="gbp3link" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>GBP 3 Access?</label>
						    <select id="gbp3access">
						      ' . $gbp_select3 . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>GBP Link 4</label>
						    <input value="' . $urlPart4 . '" id="gbp4link" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>GBP 4 Access?</label>
						    <select id="gbp4access">
						      ' . $gbp_select4 . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>GBP Link 5</label>
						    <input value="' . $urlPart5 . '" id="gbp5link" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>GBP 5 Access?</label>
						    <select id="gbp5access">
						      ' . $gbp_select5 . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Registrar URL</label>
						    <input value="' . $item->registrarurl . '" id="registrarurl" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Registrar Username</label>
						    <input value="' . $item->registrarusername . '" id="registrarusername" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Registrar Password</label>
						    <input value="' . $item->registrarpassword . '" id="registrarpassword" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Google Analytics Access?</label>
						    <select id="googleanalyticsaccess">
						      ' . $ga_seo_access_select . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Google Analytics Username</label>
						    <input value="' . $item->googleanalyticsusername . '" id="googleanalyticsusername" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Google Analytics Password</label>
						    <input value="' . $item->googleanalyticspassword . '" id="googleanalyticspassword" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Google Search Console Access?</label>
						    <select id="searchconsoleaccess">
						      ' . $search_console_seo_access_select . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Search Console Username</label>
						    <input value="' . $item->searchconsoleusername . '" id="searchconsoleusername" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Search Console Password</label>
						    <input value="' . $item->searchconsolepassword . '" id="searchconsolepassword" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Host URL</label>
						    <input value="' . $item->hosturl . '" id="hosturl" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Host Username	</label>
						    <input value="' . $item->hostusername . '" id="hostusername" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Host Password</label>
						    <input value="' . $item->hostpassword . '" id="hostpassword" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>BLDs Submitted?</label>
						    <select id="bldsubmitted">
						      ' . $bldsubmitted . '
						    </select>
						  </div>
						  <div class="cm-new-project-form-entry-holder">
							<label>BLD CSV URL 1</label>
							<input id="bldcsvurl1" type="text" value="' . $item->bldcsvurl1 . '"/>
							<input class="cm-bldcsvupload-input cm-class-upload-bldcsvurl1" type="file" id="file-upload-bldcsvurl1" name="file-upload-bldcsvurl1" value="" />
						  </div>
						  <div class="cm-new-project-form-entry-holder">
							<label>BLD CSV URL 2</label>
							<input id="bldcsvurl2" type="text" value="' . $item->bldcsvurl2 . '"/>
							<input class="cm-bldcsvupload-input cm-class-upload-bldcsvurl2" type="file" id="file-upload-bldcsvurl2" name="file-upload-bldcsvurl2" value="' . $item->bldcsvurl2 . '" />
						  </div>
						  <div class="cm-new-project-form-entry-holder">
							<label>BLD CSV URL 3</label>
							<input id="bldcsvurl3" type="text" value="' . $item->bldcsvurl3 . '"/>
							<input class="cm-bldcsvupload-input cm-class-upload-bldcsvurl3" type="file" id="file-upload-bldcsvurl3" name="file-upload-bldcsvurl3" value="' . $item->bldcsvurl3 . '" />
						  </div>
						  <div class="cm-new-project-form-entry-holder">
							<label>BLD CSV URL 4</label>
							<input id="bldcsvurl4" type="text" value="' . $item->bldcsvurl4 . '"/>
							<input class="cm-bldcsvupload-input cm-class-upload-bldcsvurl4" type="file" id="file-upload-bldcsvurl4" name="file-upload-bldcsvurl4" value="' . $item->bldcsvurl4 . '" />
						  </div>
						  <div class="cm-new-project-form-entry-holder">
							<label>BLD CSV URL 5</label>
							<input id="bldcsvurl5" type="text" value="' . $item->bldcsvurl5 . '"/>
							<input class="cm-bldcsvupload-input cm-class-upload-bldcsvurl5" type="file" id="file-upload-bldcsvurl5" name="file-upload-bldcsvurl5" value="' . $item->bldcsvurl5 . '" />
						  </div>
						  <br/>
						  <div class="cm-new-project-form-entry-holder">
							<label>Period Complete?</label>
							<select id="periodcomplete">
								' . $periodcomplete . '
							</select>
						  </div>
						  <div class="cm-new-project-form-entry-holder">
							<label>Service Description</label>
							<textarea id="servicesdescription">' . $item->servicesdescription . '</textarea>
						  </div>

						  <button data-clientid="' . $item->client_id . '" data-seoentryid="' . $item->id . '" class="cm-edit-service cm-edit-service-seo-service">Edit SEO Service</button>



						</div>


					</div>


					';


            	} else if( false !== stripos($key, 'social_media_management') ) {

            	} else if( false !== stripos($key, 'website_project') ) {

            		// Get additional DB tables associated with this Website Project
            		$table_name = $wpdb->prefix . 'website_launch_checklist';
            		$launch_checklist_db = $wpdb->get_row($wpdb->prepare(
			          "SELECT * FROM $table_name WHERE website_project_id = %d",
			          $item->id
			      ));

			      // Get additional DB tables associated with this Website Project
            		$table_name = $wpdb->prefix . 'website_creative_brief';
            		$creative_brief_db = $wpdb->get_row($wpdb->prepare(
			          "SELECT * FROM $table_name WHERE website_project_id = %d",
			          $item->id
			      ));

			      // Get additional DB tables associated with this Website Project
            		$table_name = $wpdb->prefix . 'website_launch_checklist';
            		$launch_checklist_db = $wpdb->get_row($wpdb->prepare(
			          "SELECT * FROM $table_name WHERE website_project_id = %d",
			          $item->id
			      ));



            		$website_project_status_select_value = '';

            		if ( 'Onboarding' === $item->project_status ){
            			$website_project_status_select_value = '<option selected>Onboarding</option><option>Awaiting Creative Brief Completion</option><option>Awaiting Kickoff Call</option><option>Homepage Design & Development in Progress</option><option>Awaiting Homepage Feedback</option><option>Implementing Homepage Feedback</option><option>Full Site Design & Development in Progress</option><option>Awaiting Full Site Feedback</option><option>Implementing Full Site Feedback</option><option>Website Launch Approved - QA Checklist in Progress</option><option>QA Checklist Completed - Awaiting Launch</option><option>Launched - Post-Launch QA Checklist in Progress</option><option>Launched</option><option>Completed</option>';

            		} else if( 'Awaiting Creative Brief Completion' === $item->project_status ){
            			$website_project_status_select_value = '<option>Onboarding</option><option selected>Awaiting Creative Brief Completion</option><option>Awaiting Kickoff Call</option><option>Homepage Design & Development in Progress</option><option>Awaiting Homepage Feedback</option><option>Implementing Homepage Feedback</option><option>Full Site Design & Development in Progress</option><option>Awaiting Full Site Feedback</option><option>Implementing Full Site Feedback</option><option>Website Launch Approved - QA Checklist in Progress</option><option>QA Checklist Completed - Awaiting Launch</option><option>Launched - Post-Launch QA Checklist in Progress</option><option>Launched</option><option>Completed</option>';
            		} else if ( 'Awaiting Kickoff Call' === $item->project_status ) {
						$website_project_status_select_value = '<option>Onboarding</option><option>Awaiting Creative Brief Completion</option><option selected>Awaiting Kickoff Call</option><option>Homepage Design & Development in Progress</option><option>Awaiting Homepage Feedback</option><option>Implementing Homepage Feedback</option><option>Full Site Design & Development in Progress</option><option>Awaiting Full Site Feedback</option><option>Implementing Full Site Feedback</option><option>Website Launch Approved - QA Checklist in Progress</option><option>QA Checklist Completed - Awaiting Launch</option><option>Launched - Post-Launch QA Checklist in Progress</option><option>Launched</option><option>Completed</option>';
            		} else if ( 'Homepage Design & Development in Progress' === $item->project_status ) {
						$website_project_status_select_value = '<option>Onboarding</option><option>Awaiting Creative Brief Completion</option><option>Awaiting Kickoff Call</option><option selected>Homepage Design & Development in Progress</option><option>Awaiting Homepage Feedback</option><option>Implementing Homepage Feedback</option><option>Full Site Design & Development in Progress</option><option>Awaiting Full Site Feedback</option><option>Implementing Full Site Feedback</option><option>Website Launch Approved - QA Checklist in Progress</option><option>QA Checklist Completed - Awaiting Launch</option><option>Launched - Post-Launch QA Checklist in Progress</option><option>Launched</option><option>Completed</option>';
            		} else if ( 'Implementing Homepage Feedback' === $item->project_status ) {
						$website_project_status_select_value = '<option>Onboarding</option><option>Awaiting Creative Brief Completion</option><option>Awaiting Kickoff Call</option><option>Homepage Design & Development in Progress</option><option>Awaiting Homepage Feedback</option><option selected>Implementing Homepage Feedback</option><option>Full Site Design & Development in Progress</option><option>Awaiting Full Site Feedback</option><option>Implementing Full Site Feedback</option><option>Website Launch Approved - QA Checklist in Progress</option><option>QA Checklist Completed - Awaiting Launch</option><option>Launched - Post-Launch QA Checklist in Progress</option><option>Launched</option><option>Completed</option>';
            		} else if ( 'Full Site Design & Development in Progress' === $item->project_status ) {
						$website_project_status_select_value = '<option>Onboarding</option><option>Awaiting Creative Brief Completion</option><option>Awaiting Kickoff Call</option><option>Homepage Design & Development in Progress</option><option>Awaiting Homepage Feedback</option><option>Implementing Homepage Feedback</option><option selected>Full Site Design & Development in Progress</option><option>Awaiting Full Site Feedback</option><option>Implementing Full Site Feedback</option><option>Website Launch Approved - QA Checklist in Progress</option><option>QA Checklist Completed - Awaiting Launch</option><option>Launched - Post-Launch QA Checklist in Progress</option><option>Launched</option><option>Completed</option>';
            		} else if ( 'Awaiting Full Site Feedback' === $item->project_status ) {
						$website_project_status_select_value = '<option>Onboarding</option><option>Awaiting Creative Brief Completion</option><option>Awaiting Kickoff Call</option><option>Homepage Design & Development in Progress</option><option>Awaiting Homepage Feedback</option><option>Implementing Homepage Feedback</option><option>Full Site Design & Development in Progress</option><option selected>Awaiting Full Site Feedback</option><option>Implementing Full Site Feedback</option><option>Website Launch Approved - QA Checklist in Progress</option><option>QA Checklist Completed - Awaiting Launch</option><option>Launched - Post-Launch QA Checklist in Progress</option><option>Launched</option><option>Completed</option>';
            		} else if ( 'Implementing Full Site Feedback' === $item->project_status ) {
						$website_project_status_select_value = '<option>Onboarding</option><option>Awaiting Creative Brief Completion</option><option>Awaiting Kickoff Call</option><option>Homepage Design & Development in Progress</option><option>Awaiting Homepage Feedback</option><option>Implementing Homepage Feedback</option><option>Full Site Design & Development in Progress</option><option>Awaiting Full Site Feedback</option><option selected>Implementing Full Site Feedback</option><option>Website Launch Approved - QA Checklist in Progress</option><option>QA Checklist Completed - Awaiting Launch</option><option>Launched - Post-Launch QA Checklist in Progress</option><option>Launched</option><option>Completed</option>';
            		} else if ( 'Website Launch Approved - QA Checklist in Progress' === $item->project_status ) {
						$website_project_status_select_value = '<option>Onboarding</option><option>Awaiting Creative Brief Completion</option><option>Awaiting Kickoff Call</option><option>Homepage Design & Development in Progress</option><option>Awaiting Homepage Feedback</option><option>Implementing Homepage Feedback</option><option>Full Site Design & Development in Progress</option><option>Awaiting Full Site Feedback</option><option>Implementing Full Site Feedback</option><option selected>Website Launch Approved - QA Checklist in Progress</option><option>QA Checklist Completed - Awaiting Launch</option><option>Launched - Post-Launch QA Checklist in Progress</option><option>Launched</option><option>Completed</option>';
            		} else if ( 'QA Checklist Completed - Awaiting Launch' === $item->project_status ) {
						$website_project_status_select_value = '<option>Onboarding</option><option>Awaiting Creative Brief Completion</option><option>Awaiting Kickoff Call</option><option>Homepage Design & Development in Progress</option><option>Awaiting Homepage Feedback</option><option>Implementing Homepage Feedback</option><option>Full Site Design & Development in Progress</option><option>Awaiting Full Site Feedback</option><option>Implementing Full Site Feedback</option><option>Website Launch Approved - QA Checklist in Progress</option><option selected>QA Checklist Completed - Awaiting Launch</option><option>Completed</option>';
            		} else if ( 'Completed' === $item->project_status ) {
						$website_project_status_select_value = '<option>Onboarding</option><option>Awaiting Creative Brief Completion</option><option>Awaiting Kickoff Call</option><option>Homepage Design & Development in Progress</option><option>Awaiting Homepage Feedback</option><option>Implementing Homepage Feedback</option><option>Full Site Design & Development in Progress</option><option>Awaiting Full Site Feedback</option><option>Implementing Full Site Feedback</option><option>Website Launch Approved - QA Checklist in Progress</option><option>QA Checklist Completed - Awaiting Launch</option><option>Launched - Post-Launch QA Checklist in Progress</option><option>Launched</option><option selected>Completed</option>';
            		} else if ( 'Awaiting Homepage Feedback' === $item->project_status ) {
						$website_project_status_select_value = '<option>Onboarding</option><option>Awaiting Creative Brief Completion</option><option>Awaiting Kickoff Call</option><option>Homepage Design & Development in Progress</option><option selected>Awaiting Homepage Feedback</option><option>Implementing Homepage Feedback</option><option>Full Site Design & Development in Progress</option><option>Awaiting Full Site Feedback</option><option>Implementing Full Site Feedback</option><option>Website Launch Approved - QA Checklist in Progress</option><option>QA Checklist Completed - Awaiting Launch</option><option>Launched - Post-Launch QA Checklist in Progress</option><option>Launched</option><option>Completed</option>';
            		}  else if ( 'Launched - Post-Launch QA Checklist in Progress' === $item->project_status ) {
						$website_project_status_select_value = '<option>Onboarding</option><option>Awaiting Creative Brief Completion</option><option>Awaiting Kickoff Call</option><option>Homepage Design & Development in Progress</option><option>Awaiting Homepage Feedback</option><option>Implementing Homepage Feedback</option><option>Full Site Design & Development in Progress</option><option>Awaiting Full Site Feedback</option><option>Implementing Full Site Feedback</option><option>Website Launch Approved - QA Checklist in Progress</option><option>QA Checklist Completed - Awaiting Launch</option><option selected>Launched - Post-Launch QA Checklist in Progress</option><option>Launched</option><option>Completed</option>';
            		} else if ( 'Launched' === $item->project_status ) {
						$website_project_status_select_value = '<option>Onboarding</option><option>Awaiting Creative Brief Completion</option><option>Awaiting Kickoff Call</option><option>Homepage Design & Development in Progress</option><option>Awaiting Homepage Feedback</option><option>Implementing Homepage Feedback</option><option>Full Site Design & Development in Progress</option><option>Awaiting Full Site Feedback</option><option>Implementing Full Site Feedback</option><option>Website Launch Approved - QA Checklist in Progress</option><option>QA Checklist Completed - Awaiting Launch</option><option>Launched - Post-Launch QA Checklist in Progress</option><option selected>Launched</option><option>Completed</option>';
            		}

            		$whohosts_select = '';
            		if ( 'Level Up' === $item->project_host ) {
            			$whohosts_select = '<option selected>Level Up</option><option>The Client</option>';
            		} else {
            			$whohosts_select = '<option>Level Up</option><option selected>The Client</option>';
            		}

            		$domainresponsibility_select = '';
            		if ( 'Level Up' === $item->project_domain_responsibility ) {
            			$domainresponsibility_select = '<option selected>Level Up</option><option>The Client</option>';
            		} else {
            			$domainresponsibility_select = '<option>Level Up</option><option selected>The Client</option>';
            		}

            		$homepage_approval_select = '';
            		if ( 'Not Yet' === $item->project_homepage_approval ) {
            			$homepage_approval_select = '<option selected>Not Yet</option><option>Yes - Client Clicked Approved Button</option><option>Email Approval</option><option>Verbal Approval</option>';
            		} else if( 'Yes - Client Clicked Approved Button' === $item->project_homepage_approval ) {
            			$homepage_approval_select = '<option>Not Yet</option><option selected>Yes - Client Clicked Approved Button</option><option>Email Approval</option><option>Verbal Approval</option>';
            		}  else if( 'Email Approval' === $item->project_homepage_approval ) {
            			$homepage_approval_select = '<option>Not Yet</option><option>Yes - Client Clicked Approved Button</option><option selected>Email Approval</option><option>Verbal Approval</option>';
            		}  else if( 'Verbal Approval' === $item->project_homepage_approval ) {
            			$homepage_approval_select = '<option>Not Yet</option><option>Yes - Client Clicked Approved Button</option><option>Email Approval</option><option selected>Verbal Approval</option>';
            		}

            		$full_siteapproval_select = '';
            		if ( 'Not Yet' === $item->project_full_site_approval ) {
            			$full_siteapproval_select = '<option selected>Not Yet</option><option>Yes - Client Clicked Approved Button</option><option>Email Approval</option><option>Verbal Approval</option>';
            		} else if( 'Yes - Client Clicked Approved Button' === $item->project_full_site_approval ) {
            			$full_siteapproval_select = '<option>Not Yet</option><option selected>Yes - Client Clicked Approved Button</option><option>Email Approval</option><option>Verbal Approval</option>';
            		}  else if( 'Email Approval' === $item->project_full_site_approval ) {
            			$full_siteapproval_select = '<option>Not Yet</option><option>Yes - Client Clicked Approved Button</option><option selected>Email Approval</option><option>Verbal Approval</option>';
            		}  else if( 'Verbal Approval' === $item->project_full_site_approval ) {
            			$full_siteapproval_select = '<option>Not Yet</option><option>Yes - Client Clicked Approved Button</option><option>Email Approval</option><option selected>Verbal Approval</option>';
            		}


            		$ga_access_select = '';
            		if ( 'Not Yet' === $item->project_google_analytics_access ) {
            			$ga_access_select = '<option selected>Not Yet</option><option>Yes - Client Granted Access</option><option>Yes - Level Up Created Analytics Account</option><option>N/A</option>';
            		} else if( 'Yes - Client Granted Access' === $item->project_google_analytics_access ) {
            			$ga_access_select = '<option>Not Yet</option><option>Yes - Client Granted Access</option><option selected>Yes - Level Up Created Analytics Account</option><option>N/A</option>';
            		}  else if( 'Yes - Level Up Created Analytics Account' === $item->project_google_analytics_access ) {
            			$ga_access_select = '<option>Not Yet</option><option>Yes - Client Granted Access</option><option selected>Yes - Level Up Created Analytics Account</option><option>N/A</option>';
            		}  else if( 'N/A' === $item->project_google_analytics_access ) {
            			$ga_access_select = '<option>Not Yet</option><option>Yes - Client Granted Access</option><option>Yes - Level Up Created Analytics Account</option><option selected>N/A</option>';
            		}

            		$search_console_access_select = '';
            		if ( 'Not Yet' === $item->project_search_console_access ) {
            			$search_console_access_select = '<option selected>Not Yet</option><option>Yes - Client Granted Access</option><option>Yes - Level Up Created Analytics Account</option><option>N/A</option>';
            		} else if( 'Yes - Client Granted Access' === $item->project_search_console_access ) {
            			$search_console_access_select = '<option>Not Yet</option><option selected>Yes - Client Granted Access</option><option>Yes - Level Up Created Analytics Account</option><option>N/A</option>';
            		}  else if( 'Yes - Level Up Created Analytics Account' === $item->project_search_console_access ) {
            			$search_console_access_select = '<option>Not Yet</option><option>Yes - Client Granted Access</option><option selected>Yes - Level Up Created Analytics Account</option><option>N/A</option>';
            		}  else if( 'N/A' === $item->project_search_console_access ) {
            			$search_console_access_select = '<option>Not Yet</option><option>Yes - Client Granted Access</option><option>Yes - Level Up Created Analytics Account</option><option selected>N/A</option>';
            		}

					$output .= 
					'<div class="cm-indiv-client-holder">
						<div class="cm-indiv-client-name">Website Project (' . $item->service_unique_name . ')</div>
						<div class="cm-indiv-edit-service-table-holder">
							<div class="cm-edit-service-form-entry-holder">
						    <label>Project Name</label>
						    <input value="' . $item->service_unique_name . '" id="service_unique_name" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Start Date</label>
						    <input value="' . $item->project_start_date . '" id="project_start_date" type="date" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Completion Date</label>
						    <input value="' . $item->project_completion_date . '" id="project_completion_date" type="date" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Launch Date</label>
						    <input value="' . $item->project_launch_date . '" id="project_launch_date" type="date" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Project Status</label>
						    <select id="project_status">
						      ' . $website_project_status_select_value . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Final Website URL</label>
						    <input value="' . $item->project_url . '" id="project_url" class="jre-validateurl" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Development URL</label>
						    <input value="' . $item->project_dev_url . '" id="project_dev_url" class="jre-validateurl" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Total Investment</label>
						    <input value="' . $item->project_total_investment . '" id="project_total_investment" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Who Hosts</label>
						    <select id="project_host">
						    	' . $whohosts_select . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Host URL</label>
						    <input value="' . $item->project_host_url . '" id="project_host_url" class="jre-validateurl" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Host Login Username</label>
						    <input value="' . $item->project_host_username . '" id="project_host_username" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Host Login Password</label>
						    <input value="' . $item->project_host_password . '" id="project_host_password" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Domain Responsibility</label>
						    <select id="project_domain_responsibility">
						      ' . $domainresponsibility_select . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Domain Registrar URL</label>
						    <input value="' . $item->project_domain_registrar_url . '" id="project_domain_registrar_url" class="jre-validateurl" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Domain Registrar Username</label>
						    <input value="' . $item->project_domain_registrar_username . '" id="project_domain_registrar_username" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Domain Registrar Password</label>
						    <input value="' . $item->project_domain_registrar_password . '" id="project_domain_registrar_password" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Client Approved Homepage?</label>
						    <select id="project_homepage_approval">
						      ' . $homepage_approval_select . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Client Approved Full Site?</label>
						    <select id="project_full_site_approval">
						      ' . $full_siteapproval_select . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Google Analytics Access?</label>
						    <select id="project_google_analytics_access">
						      ' . $ga_access_select . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Google Analytics Username</label>
						    <input value="' . $item->project_google_analytics_username . '" id="project_google_analytics_username" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Google Analytics Password</label>
						    <input value="' . $item->project_google_analytics_password . '" id="project_google_analytics_password" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Google Search Console Access?</label>
						    <select id="project_search_console_access">
						      ' . $search_console_access_select . '
						    </select>
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Search Console Username</label>
						    <input value="' . $item->project_search_console_username . '" id="project_search_console_username" type="text" />
						  </div>
						  <div class="cm-edit-service-form-entry-holder">
						    <label>Search Console Password</label>
						    <input value="' . $item->project_search_console_password . '" id="project_search_console_password" type="text" />
						  </div>
							<div class="cm-indiv-subtitle-inner-form cm-indiv-subtitle-inner-form-creative-brief">
								<div class="cm-indiv-subtitle-inner-form-title">Associated Creative Brief</div>
									<div class="cm-indiv-subtitle-inner-holder">
										<div class="cm-new-project-cb-form-entry-holder">
											<label>General Business Description</label>
											<textarea id="generaldescription">' . $creative_brief_db->generaldescription . '</textarea>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Differentiators</label>
											<textarea id="differentiators">' . $creative_brief_db->differentiators . '</textarea>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Awards, Certifications, Etc.</label>
											<textarea id="awardsandcerts">' . $creative_brief_db->awardsandcerts . '</textarea>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Competitor Information</label>
											<textarea id="competitorinfo">' . $creative_brief_db->competitorinfo . '</textarea>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Competitor #1 Website</label>
											<input value="' . $creative_brief_db->competitorurl1 . '" id="competitorurl1" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Competitor #2 Website</label>
											<input value="' . $creative_brief_db->competitorurl2 . '" id="competitorurl2" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Competitor #3 Website</label>
											<input value="' . $creative_brief_db->competitorurl3 . '" id="competitorurl3" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Services information</label>
											<textarea id="services">' . $creative_brief_db->services . '</textarea>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Logo & Branding Elements</label>
											<textarea id="logobrandbooknotes">' . $creative_brief_db->logobrandbooknotes . '</textarea>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Color Preferences</label>
											<textarea id="colornotes">' . $creative_brief_db->colornotes . '</textarea>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Font Preferences</label>
											<textarea id="fontnotes">' . $creative_brief_db->fontnotes . '</textarea>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Inspiration Website URL #1</label>
											<input value="' . $creative_brief_db->inspowebsiteurl1 . '" id="inspowebsiteurl1" class="jre-validateurl" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Inspiration Website URL #2</label>
											<input value="' . $creative_brief_db->inspowebsiteurl2 . '" id="inspowebsiteurl2" class="jre-validateurl" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Inspiration Website URL #3</label>
											<input value="' . $creative_brief_db->inspowebsiteurl3 . '" id="inspowebsiteurl3" class="jre-validateurl" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>General Design Notes</label>
											<textarea id="generaldesignnotes">' . $creative_brief_db->generaldesignnotes . '</textarea>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Taglines & Mottos</label>
											<textarea id="taglinesmottos">' . $creative_brief_db->taglinesmottos . '</textarea>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Target Audience Notes</label>
											<textarea id="targetaudiencenotes">' . $creative_brief_db->targetaudiencenotes . '</textarea>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Current Website URL</label>
											<input value="' . $creative_brief_db->currentwebsiteurl . '" id="currentwebsiteurl" class="jre-validateurl" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Current Website Username/Email Login</label>
											<input value="' . $creative_brief_db->currentwebsitelogin . '" id="currentwebsitelogin" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Current Website Password</label>
											<input value="' . $creative_brief_db->currentwebsitepassword . '" id="currentwebsitepassword" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Current Website Hosting URL</label>
											<input value="' . $creative_brief_db->currenthostingurl . '" id="currenthostingurl" class="jre-validateurl" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Current Website Hosting Username/Email Login</label>
											<input value="' . $creative_brief_db->currenthostinglogin . '" id="currenthostinglogin" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Current Website Hosting Password</label>
											<input value="' . $creative_brief_db->currenthostingpassword . '" id="currenthostingpassword" type="text"/>
										</div>

										<div class="cm-new-project-cb-form-entry-holder">
											<label>Current Domain URL</label>
											<input value="' . $creative_brief_db->currentdomainurl . '" id="currentdomainurl" class="jre-validateurl" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Current Domain Username/Email Login</label>
											<input value="' . $creative_brief_db->currentdomainlogin . '" id="currentdomainlogin" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Current Domain Password</label>
											<input value="' . $creative_brief_db->currentdomainpassword . '" id="currentdomainpassword" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Facebook Link</label>
											<input value="' . $creative_brief_db->socialfacebooklink . '" id="socialfacebooklink" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>LinkedIn Link</label>
											<input value="' . $creative_brief_db->sociallinkedinlink . '" id="sociallinkedinlink" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Instagram Link</label>
											<input value="' . $creative_brief_db->socialinstagramlink . '" id="socialinstagramlink" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>YouTube Link</label>
											<input value="' . $creative_brief_db->socialyoutubelink . '" id="socialyoutubelink" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Twitter/X Link</label>
											<input value="' . $creative_brief_db->socialtwitterlink . '" id="socialtwitterlink" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>TikTok</label>
											<input value="' . $creative_brief_db->socialtiktoklink . '" id="socialtiktoklink" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Pinterest</label>
											<input value="' . $creative_brief_db->socialpinterestlink . '" id="socialpinterestlink" type="text"/>
										</div>
										<div class="cm-new-project-cb-form-entry-holder">
											<label>Last Thoughts?</label>
											<textarea id="lastthoughts">' . $creative_brief_db->lastthoughts . '</textarea>
										</div>
									</div>
								</div>';

								// Now let's build the HTML for the Launch Checklist from an array...
								/*
									Not Completed = 0
									Completed = 1
									In Progress = 2
									N/A = 3
								*/
								$launch_checklist_form_html = '';
								$launch_checklist_array = array(
									'Check for placeholder text content',
									'Check for gramatical errors & typos',
									'Check for placeholder images',
									'Check for excessive image file sizes',
									'Check for broken links',
									'Check that links go to intended page(s)',
									'Create a custom 404 error page',
									'store an exact copy of the current website',
									'Create redirects as needed',
									'Add a Favicon',
									'Test all forms for submission emails to client',
									'Ensure all forms have autoresponder emails',
									'Ensure all forms redirect to thank-you page upon submission',
									'Ensure every page has a meta title and meta description',
									'Create a sitemap & ensure accuracy',
									'submit the sitemap to Google Search Console',
									'ensure all opengraph/social media elements are specified',
									'ensure all opengraph/social media elements are specified',
									'verify social media icons & links are correct',
									'create a privacy policy page and link in footer',
									'create a terms & conditions page and link in footer',
									'create & store a backup of current DNS zone files',
									'search database for development links & replace',
									'ensure all images have appropriate ALT tags',
									'connect google analytics',
									'clean up post & blog categories',
									'Implement reCaptcha on all forms',
									'activate spam reduction solution',
									'delete all development & temporary plugins',
									'delete all development & temporary pages',
									'delete all development & temporary posts',
									'delete all development & temporary user accounts',
									'change all user account passwords',
									'ensure the \'Discourage Search Engines\' box is unchecked',
									'create & configure robots.txt file as needed',
									'ensure phone numbers in header and footer are correct and linked',
									'ensure email addresses in header and footer are correct and linked',
									'Double-check headinng hierarchy on every page',
									'Ensure commennts are disabled',
									'Ensure mobile menu functionality',
									'Ensure general mobile functionality',
									'Set Timezone settings',
									'Ensure full SSL/HTTPS security on every page',
									'Implement limited login functionality and test',
									'Ensure automatic plugin updates are turned on',
									'Delete the wp-config-sample.php file',
									'Whitelist Level Up IP Addresses',
									'Change the default Login Page URL',
									'Disable File Editing - wp-config.php - define(\'DISALLOW_FILE_EDIT\', true);',
									'Change the default WordPress Database Prefix',
									'Automatically Log Out Idle Users',
									'Hide the WordPress Version',
								);

								$checklist_array =  explode(',', $launch_checklist_db->checklist_string);

								foreach ( $launch_checklist_array as $checklist_key => $checklist_value) {
									$launch_checklist_form_html = $launch_checklist_form_html .
									'<div class="cm-new-project-lcl-form-entry-holder">
										<label>' . ucfirst($checklist_value) . '</label>
										<select id="checklist_value_' . $checklist_key . '">';

											$notcompleted = '';
											$completed = '';
											$inprogress = '';
											$na = '';
											if ( '0' === $checklist_array[$checklist_key]){
												$notcompleted = 'selected';
											} elseif( '1' === $checklist_array[$checklist_key] ){
												$completed = 'selected';
											} elseif( '2' === $checklist_array[$checklist_key] ){
												$inprogress = 'selected';
											} else{
												$na = 'selected';
											}

											$launch_checklist_form_html  = $launch_checklist_form_html  . '<option ' . $notcompleted  . '>Not Completed</option>
											<option ' . $completed  . '>Completed</option>
											<option ' . $inprogress  . '>In Progress</option>
											<option ' . $na  . '>N/A</option>
										</select>
									</div>';	
								}

								$output =  $output . '<div class="cm-indiv-subtitle-inner-form cm-indiv-subtitle-inner-form-launch-checklist">
									<div class="cm-indiv-subtitle-inner-form-title">Associated Launch Checklist</div>
									<div class="cm-indiv-subtitle-inner-holder">
										' . $launch_checklist_form_html . '
									</div>
								</div>

						  <button data-websiteid="' . $item->id . '" data-cbiid="' . $creative_brief_db->id . '" data-lcid="' . $launch_checklist_db->id . '" class="cm-edit-service cm-edit-service-website-hosting-service">Edit Service</button>
						</div>
						</div>';


            	} else if( false !== stripos($key, 'website_maintenance') ) {

            		$plugin_updates_select = '';
            		if ( 'Yes' === $item->plugin_updates ) {
            			$plugin_updates_select = '<option selected>Yes</option><option>No</option><option>N/A</option>';
            		} else if( 'No' === $item->plugin_updates ) {
            			$plugin_updates_select = '<option>Yes</option><option selected>No</option><option>N/A</option>';
            		}  else {
            			$plugin_updates_select = '<option>Yes</option><option>No</option><option selected>N/A</option>';
            		}

            		$theme_file_updates_select = '';
            		if ( 'Yes' === $item->theme_file_updates ) {
            			$theme_file_updates_select = '<option selected>Yes</option><option>No</option><option>N/A</option>';
            		} else if( 'No' === $item->theme_file_updates ) {
            			$theme_file_updates_select = '<option>Yes</option><option selected>No</option><option>N/A</option>';
            		}  else {
            			$theme_file_updates_select = '<option>Yes</option><option>No</option><option selected>N/A</option>';
            		}

            		$core_file_updates_select = '';
            		if ( 'Yes' === $item->core_file_updates ) {
            			$core_file_updates_select = '<option selected>Yes</option><option>No</option><option>N/A</option>';
            		} else if( 'No' === $item->core_file_updates ) {
            			$core_file_updates_select = '<option>Yes</option><option selected>No</option><option>N/A</option>';
            		}  else {
            			$core_file_updates_select = '<option>Yes</option><option>No</option><option selected>N/A</option>';
            		}

            		$ssl_cert_select = '';
            		if ( 'Yes' === $item->ssl_cert ) {
            			$ssl_cert_select = '<option selected>Yes</option><option>No</option><option>N/A</option>';
            		} else if( 'No' === $item->ssl_cert ) {
            			$ssl_cert_select = '<option>Yes</option><option selected>No</option><option>N/A</option>';
            		}  else {
            			$ssl_cert_select = '<option>Yes</option><option>No</option><option selected>N/A</option>';
            		}

            		$support_hours_type_select = '';
            		if ( 'Monthly' === $item->support_hours_type ) {
            			$support_hours_type_select = '<option selected>Monthly</option><option>Contract Allotment</option><option>Ala-Carte</option><option>N/A</option>';
            		} else if( 'Contract Allotment' === $item->support_hours_type ) {
            			$support_hours_type_select = '<option>Monthly</option><option selected>Contract Allotment</option><option>Ala-Carte</option><option>N/A</option>';
            		}  else if( 'Ala-Carte' === $item->support_hours_type ) {
            			$support_hours_type_select = '<option>Monthly</option><option>Contract Allotment</option><option selected>Ala-Carte</option><option>N/A</option>';
            		} else {
            			$support_hours_type_select = '<option>Monthly</option><option>Contract Allotment</option><option>Ala-Carte</option><option selected>N/A</option>';
            		}

            		$hours_accrue_select = '';
            		if ( 'Yes' === $item->hours_accrue ) {
            			$hours_accrue_select = '<option selected>Yes</option><option>No</option><option>N/A</option>';
            		} else if( 'No' === $item->hours_accrue ) {
            			$hours_accrue_select = '<option>Yes</option><option selected>No</option><option>N/A</option>';
            		}  else {
            			$hours_accrue_select = '<option>Yes</option><option>No</option><option selected>N/A</option>';
            		}

            		// Now let's build the website maintenance form...
            		$output =  $output . 
					'<div class="cm-indiv-client-holder">
						<div class="cm-indiv-client-name">Website Maintenance (' . $item->website_url . ')</div>
						<div class="cm-indiv-edit-service-table-holder">
							<div class="cm-new-project-form-entry-holder">
								<label>Support Start Date</label>
								<input value="' . $item->support_start_date . '" id="support_start_date" type="date"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Support End Date</label>
								<input value="' . $item->support_end_date . '" id="support_end_date" type="date"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Website URL</label>
								<input value="' . $item->website_url . '" id="website_url" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Plugin Updates?</label>
								<select id="plugin_updates">
									' . $plugin_updates_select . '
								</select>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Theme File Updates?</label>
								<select id="theme_file_updates">
									' . $theme_file_updates_select . '
								</select>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Core File Updates?</label>
								<select id="core_file_updates">
									' . $core_file_updates_select . '
								</select>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>SSL Security Certificates?</label>
								<select id="ssl_cert">
									' . $ssl_cert_select . '
								</select>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Support Hours Type?</label>
								<select id="support_hours_type">
									' . $support_hours_type_select . '
								</select>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Hourly Rate</label>
								<input value="' . $item->hourly_rate . '" id="hourly_rate" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Hours Initially Available (total per month or contract)</label>
								<input value="' . $item->hours_initially_available . '" id="hours_initially_available" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Hours Accrue?</label>
								<select id="hours_accrue">
									' . $hours_accrue_select . '
								</select>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Hours Accrual Limit</label>
								<input value="' . $item->accrue_limit . '" id="accrue_limit" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Bonus Hours Pool</label>
								<input value="' . $item->bonus_hours_pool . '" id="bonus_hours_pool" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Total Bonus Hours Used</label>
								<input value="' . $item->total_bonus_hours_used . '" id="total_bonus_hours_used" type="text"/>
							</div>
							 <button data-maintenanceid="' . $item->id . '" class="cm-edit-service cm-edit-service-website-maintenance-service">Edit Service</button>
						</div>
					</div>';


            	} else if( false !== stripos($key, 'website_hosting') ) {

            		$whohosts_select = '';
            		if ( 'Level Up' === $item->hosting_host ) {
            			$whohosts_select = '<option selected>Level Up</option><option>The Client</option>';
            		} else {
            			$whohosts_select = '<option>Level Up</option><option selected>The Client</option>';
            		}

            		$domainresponsibility_select = '';
            		if ( 'Level Up' === $item->hosting_domain_responsibility ) {
            			$domainresponsibility_select = '<option selected>Level Up</option><option>The Client</option>';
            		} else {
            			$domainresponsibility_select = '<option>Level Up</option><option selected>The Client</option>';
            		}


            		// Now let's build the website hosting form...
					$output =  $output . 
					'<div class="cm-indiv-client-holder">
						<div class="cm-indiv-client-name">Website Hosting (' . $item->hosting_website_url . ')</div>
						<div class="cm-indiv-edit-service-table-holder">
							<div class="cm-new-project-form-entry-holder">
								<label>Hosting Start Date</label>
								<input value="' . $item->hosting_start_date . '" id="hosting_start_date" type="date"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Hosting End Date</label>
								<input value="' . $item->hosting_end_date . '" id="hosting_end_date" type="date"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Website URL</label>
								<input value="' . $item->hosting_website_url . '" id="hosting_website_url" class="jre-validateurl" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Who Hosts</label>
								<select id="hosting_host">
									' . $whohosts_select . '
								</select>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Host URL</label>
								<input value="' . $item->hosting_url . '" id="hosting_url" class="jre-validateurl" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Host Username</label>
								<input value="' . $item->hosting_host_username . '" id="hosting_host_username" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Host Password</label>
								<input value="' . $item->hosting_host_password . '" id="hosting_host_password" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Domain Responsibility</label>
								<select id="hosting_domain_responsibility">
									' . $domainresponsibility_select . '
								</select>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Domain Registrar URL</label>
								<input value="' . $item->hosting_domain_registrar_url . '" id="hosting_domain_registrar_url" class="jre-validateurl" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Domain Registrar Username</label>
								<input value="' . $item->hosting_domain_registrar_username . '" id="hosting_domain_registrar_username" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Domain Registrar Password</label>
								<input value="' . $item->hosting_domain_registrar_password . '" id="hosting_domain_registrar_password" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Hosting Site Files Link</label>
								<input value="' . $item->hosting_site_files_link . '" id="hosting_site_files_link" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Hosting Monthly Investment</label>
								<input value="' . $item->hosting_monthly_investment . '" id="hosting_monthly_investment" type="text"/>
							</div>
							<div class="cm-new-project-form-entry-holder">
								<label>Hosting Total Investment</label>
								<input value="' . $item->hosting_total_investment . '" id="hosting_total_investment" type="text"/>
							</div>
							 <button data-hostingid="' . $item->id . '" class="cm-edit-service cm-edit-service-website-hosting-service">Edit Service</button>
						</div>
					</div>';








            	} else {

            	}           
            }
        }
    }
	// Return the results as JSON
    wp_die($output);
}

function cm_new_project_get_launch_checklist() {
	validate_ajax_nonce(); // Standardized nonce check

	$clientid = sanitize_text_field( $_POST['client_id']);
	global $wpdb;
	$table_name = $wpdb->prefix . 'website_launch_checklist'; // Replace 'your_table_name' with your actual table name

	$relevant_launch_checklists = $wpdb->get_results( 
	    $wpdb->prepare(
	        "SELECT * FROM $table_name WHERE client_id = %s", 
	        $clientid 
	    )
	);

	// Return the results as JSON
    wp_send_json($relevant_launch_checklists);
}

function cm_service_website_create() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'client_id' => sanitize_text_field( $_POST['client_id'] ),
		'business_name' => sanitize_text_field( $_POST['business_name'] ),
		'service_unique_name' => sanitize_text_field( $_POST['service_unique_name'] ),
		'project_start_date' => sanitize_text_field( $_POST['project_start_date'] ),
		'project_completion_date' => sanitize_text_field( $_POST['project_completion_date'] ),
		'project_launch_date' => sanitize_text_field( $_POST['project_launch_date'] ),
		'project_status' => sanitize_text_field( $_POST['project_status'] ),
		'project_url' => sanitize_text_field( $_POST['project_url'] ),
		'project_dev_url' => sanitize_text_field( $_POST['project_dev_url'] ),
		'project_total_investment' => sanitize_text_field( $_POST['project_total_investment'] ),
		'project_host' => sanitize_text_field( $_POST['project_host'] ),
		'project_host_url' => sanitize_text_field( $_POST['project_host_url'] ),
		'project_host_username' => sanitize_text_field( $_POST['project_host_username'] ),
		'project_host_password' => sanitize_text_field( $_POST['project_host_password'] ),
		'project_domain_responsibility' => sanitize_text_field( $_POST['project_domain_responsibility'] ),
		'project_domain_registrar_url' => sanitize_text_field( $_POST['project_domain_registrar_url'] ),
		'project_domain_registrar_username' => sanitize_text_field( $_POST['project_domain_registrar_username'] ),
		'project_domain_registrar_password' => sanitize_text_field( $_POST['project_domain_registrar_password'] ),
		'project_homepage_approval' => sanitize_text_field( $_POST['project_homepage_approval'] ),
		'project_full_site_approval' => sanitize_text_field( $_POST['project_full_site_approval'] ),
		'project_google_analytics_access' => sanitize_text_field( $_POST['project_google_analytics_access'] ),
		'project_google_analytics_username' => sanitize_text_field( $_POST['project_google_analytics_username'] ),
		'project_google_analytics_password' => sanitize_text_field( $_POST['project_google_analytics_password'] ),
		'project_search_console_access' => sanitize_text_field( $_POST['project_search_console_access'] ),
		'project_search_console_username' => sanitize_text_field( $_POST['project_search_console_username'] ),
		'project_search_console_password' => sanitize_text_field( $_POST['project_search_console_password'] )
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'services_website_project';
	$result = $wpdb->insert(
		$table_name,
		$data
	);

	if ($wpdb->last_error) {
	    // Handle the error
	    echo 'Insert failed: ' . $wpdb->last_error;
	} else {
		// Website Project ID
		$websiteprojectid = $wpdb->insert_id;
		wp_send_json_success( $websiteprojectid );
	}
}

function cm_service_website_creative_brief_create() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'client_id' => sanitize_text_field( $_POST['client_id'] ),
		'business_name' => sanitize_text_field( $_POST['business_name'] ),
		'project_start_date' => sanitize_text_field( $_POST['project_start_date'] ),
		'website_project_id' => sanitize_text_field( $_POST['website_project_id'] ),
		'generaldescription' => sanitize_text_field( $_POST['generaldescription'] ),
		'differentiators' => sanitize_text_field( $_POST['differentiators'] ),
		'awardsandcerts' => sanitize_text_field( $_POST['awardsandcerts'] ),
		'competitorinfo' => sanitize_text_field( $_POST['competitorinfo'] ),
		'competitorurl1' => sanitize_text_field( $_POST['competitorurl1'] ),
		'competitorurl2' => sanitize_text_field( $_POST['competitorurl2'] ),
		'competitorurl3' => sanitize_text_field( $_POST['competitorurl3'] ),
		'services' => sanitize_text_field( $_POST['services'] ),
		'logobrandbookurl' => $upload_dir['basedir'] . '/client_files/' . $_POST['business_name'] . '/logo_brandbook/',
		'logobrandbooknotes' => sanitize_text_field( $_POST['logobrandbooknotes'] ),
		'colornotes' => sanitize_text_field( $_POST['colornotes'] ),
		'fontnotes' => sanitize_text_field( $_POST['fontnotes'] ),
		'taglinesmottos' => sanitize_text_field( $_POST['taglinesmottos'] ),
		'inspowebsiteurl1' => sanitize_text_field( $_POST['inspowebsiteurl1'] ),
		'inspowebsiteurl2' => sanitize_text_field( $_POST['inspowebsiteurl2'] ),
		'inspowebsiteurl3' => sanitize_text_field( $_POST['inspowebsiteurl3'] ),
		'generaldesignnotes' => sanitize_text_field( $_POST['generaldesignnotes'] ),
		'targetaudiencenotes' => sanitize_text_field( $_POST['targetaudiencenotes'] ),
		'currentwebsiteurl' => sanitize_text_field( $_POST['currentwebsiteurl'] ),
		'currentwebsitelogin' => sanitize_text_field( $_POST['currentwebsitelogin'] ),
		'currentwebsitepassword' => sanitize_text_field( $_POST['currentwebsitepassword'] ),
		'currenthostingurl' => sanitize_text_field( $_POST['currenthostingurl'] ),
		'currenthostinglogin' => sanitize_text_field( $_POST['currenthostinglogin'] ),
		'currenthostingpassword' => sanitize_text_field( $_POST['currenthostingpassword'] ),
		'currentdomainurl' => sanitize_text_field( $_POST['currentdomainurl'] ),
		'currentdomainlogin' => sanitize_text_field( $_POST['currentdomainlogin'] ),
		'currentdomainpassword' => sanitize_text_field( $_POST['currentdomainpassword'] ),
		'socialfacebooklink' => sanitize_text_field( $_POST['socialfacebooklink'] ),
		'sociallinkedinlink' => sanitize_text_field( $_POST['sociallinkedinlink'] ),
		'socialinstagramlink' => sanitize_text_field( $_POST['socialinstagramlink'] ),
		'socialyoutubelink' => sanitize_text_field( $_POST['socialyoutubelink'] ),
		'socialtwitterlink' => sanitize_text_field( $_POST['socialtwitterlink'] ),
		'socialtiktoklink' => sanitize_text_field( $_POST['socialtiktoklink'] ),
		'socialpinterestlink' => sanitize_text_field( $_POST['socialpinterestlink'] ),
		'lastthoughts' => sanitize_text_field( $_POST['lastthoughts'] ),
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'website_creative_brief';
	$result = $wpdb->insert(
		$table_name,
		$data
	);

	if ($wpdb->last_error) {
	    // Handle the error
	    echo 'Insert failed: ' . $wpdb->last_error;
	    error_log('Insert failed: ' . $wpdb->last_error);
	} else {
		// If successful, update the Website Project db entry with the ID of it's associated Creative Brief
		$data = array(
			'project_creative_brief_id' => $wpdb->insert_id,
		);
		$table_name = $wpdb->prefix . 'services_website_project';
		$where = array( 'id' => intval( sanitize_text_field( $_POST['website_project_id'] ) ) );
		$wpdb->update(
			$table_name,
			$data,
			$where
		);
		wp_send_json_success( array( 'message' => __( 'Client\'s Creative Brief added successfully', 'client-manager' ) ) );   
	}
	
}

function cm_service_website_launch_checklist_create() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'business_name' => sanitize_text_field( $_POST['business_name'] ),
		'project_start_date' => sanitize_text_field( $_POST['project_start_date'] ),
		'checklist_string' => sanitize_text_field( $_POST['checklist_string'] ),
		'website_project_id' => sanitize_text_field( $_POST['website_project_id'] ),
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'website_launch_checklist';

	$result = $wpdb->insert(
		$table_name,
		$data
	);

	if ($wpdb->last_error) {
	    // Handle the error
	    echo 'Insert failed: ' . $wpdb->last_error;
	    error_log('Insert failed: ' . $wpdb->last_error);
	} else {
		$data = array(
			'project_launch_checklist_id' => $wpdb->insert_id,
		);
		$table_name = $wpdb->prefix . 'services_website_project';
		$where = array( 'id' => intval( sanitize_text_field( $_POST['website_project_id'] ) ) );
		$wpdb->update(
			$table_name,
			$data,
			$where
		);
		wp_send_json_success( array( 'message' => __( 'Client\'s Launch Checklist added successfully', 'client-manager' ) ) );   
	}
}

function cm_service_website_edit() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'business_name' => sanitize_text_field( $_POST['business_name'] ),
		'service_unique_name' => sanitize_text_field( $_POST['service_unique_name'] ),
		'project_start_date' => sanitize_text_field( $_POST['project_start_date'] ),
		'project_completion_date' => sanitize_text_field( $_POST['project_completion_date'] ),
		'project_launch_date' => sanitize_text_field( $_POST['project_launch_date'] ),
		'project_status' => sanitize_text_field( $_POST['project_status'] ),
		'project_url' => sanitize_text_field( $_POST['project_url'] ),
		'project_dev_url' => sanitize_text_field( $_POST['project_dev_url'] ),
		'project_total_investment' => sanitize_text_field( $_POST['project_total_investment'] ),
		'project_host' => sanitize_text_field( $_POST['project_host'] ),
		'project_host_url' => sanitize_text_field( $_POST['project_host_url'] ),
		'project_host_username' => sanitize_text_field( $_POST['project_host_username'] ),
		'project_host_password' => sanitize_text_field( $_POST['project_host_password'] ),
		'project_domain_responsibility' => sanitize_text_field( $_POST['project_domain_responsibility'] ),
		'project_domain_registrar_url' => sanitize_text_field( $_POST['project_domain_registrar_url'] ),
		'project_domain_registrar_username' => sanitize_text_field( $_POST['project_domain_registrar_username'] ),
		'project_domain_registrar_password' => sanitize_text_field( $_POST['project_domain_registrar_password'] ),
		'project_homepage_approval' => sanitize_text_field( $_POST['project_homepage_approval'] ),
		'project_full_site_approval' => sanitize_text_field( $_POST['project_full_site_approval'] ),
		'project_google_analytics_access' => sanitize_text_field( $_POST['project_google_analytics_access'] ),
		'project_google_analytics_username' => sanitize_text_field( $_POST['project_google_analytics_username'] ),
		'project_google_analytics_password' => sanitize_text_field( $_POST['project_google_analytics_password'] ),
		'project_search_console_access' => sanitize_text_field( $_POST['project_search_console_access'] ),
		'project_search_console_username' => sanitize_text_field( $_POST['project_search_console_username'] ),
		'project_search_console_password' => sanitize_text_field( $_POST['project_search_console_password'] )
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'services_website_project';
	$where = array( 'id' => intval( sanitize_text_field( $_POST['websiteid'] ) ) );
	$wpdb->update(
		$table_name,
		$data,
		$where
	);

	if ($wpdb->last_error) {
	    // Handle the error
	    echo 'Insert failed: ' . $wpdb->last_error;
	} else {
		wp_die('success');
	}
}

function cm_service_hosting_edit() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'hosting_start_date' => sanitize_text_field( $_POST['hosting_start_date'] ),
		'hosting_end_date' => sanitize_text_field( $_POST['hosting_end_date'] ),
		'hosting_website_url' => sanitize_text_field( $_POST['hosting_website_url'] ),
		'hosting_host' => sanitize_text_field( $_POST['hosting_host'] ),
		'hosting_url' => sanitize_text_field( $_POST['hosting_url'] ),
		'hosting_host_username' => sanitize_text_field( $_POST['hosting_host_username'] ),
		'hosting_host_password' => sanitize_text_field( $_POST['hosting_host_password'] ),
		'hosting_domain_responsibility' => sanitize_text_field( $_POST['hosting_domain_responsibility'] ),
		'hosting_domain_registrar_url' => sanitize_text_field( $_POST['hosting_domain_registrar_url'] ),
		'hosting_domain_registrar_username' => sanitize_text_field( $_POST['hosting_domain_registrar_username'] ),
		'hosting_domain_registrar_password' => sanitize_text_field( $_POST['hosting_domain_registrar_password'] ),
		'hosting_site_files_link' => sanitize_text_field( $_POST['hosting_site_files_link'] ),
		'hosting_monthly_investment' => sanitize_text_field( $_POST['hosting_monthly_investment'] ),
		'hosting_total_investment' => sanitize_text_field( $_POST['hosting_total_investment'] ),
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'services_website_hosting';
	$where = array( 'id' => intval( sanitize_text_field( $_POST['hostingid'] ) ) );
	$wpdb->update(
		$table_name,
		$data,
		$where
	);

	if ($wpdb->last_error) {
	    // Handle the error
	    echo 'Insert failed: ' . $wpdb->last_error;
	} else {
		wp_die('success');
	}
}

function cm_service_seo_edit() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'service_unique_name' => sanitize_text_field( $_POST['service_unique_name'] ),
        'startdate' => sanitize_text_field( $_POST['startdate'] ),
        'enddate' => sanitize_text_field( $_POST['enddate'] ),
        'websiteurl' => sanitize_text_field( $_POST['websiteurl'] ),
        'websitelogin' => sanitize_text_field( $_POST['websitelogin'] ),
        'websitepassword' => sanitize_text_field( $_POST['websitepassword'] ),
        'monthlyamount' => sanitize_text_field( $_POST['monthlyamount'] ),
        'gbplinkandaccess1' => sanitize_text_field( $_POST['gbplinkandaccess1'] ),
        'gbplinkandaccess2' => sanitize_text_field( $_POST['gbplinkandaccess2'] ),
        'gbplinkandaccess3' => sanitize_text_field( $_POST['gbplinkandaccess3'] ),
        'gbplinkandaccess4' => sanitize_text_field( $_POST['gbplinkandaccess4'] ),
        'gbplinkandaccess5' => sanitize_text_field( $_POST['gbplinkandaccess5'] ),
        'registrarurl' => sanitize_text_field( $_POST['registrarurl'] ),
        'registrarusername' => sanitize_text_field( $_POST['registrarusername'] ),
        'registrarpassword' => sanitize_text_field( $_POST['registrarpassword'] ),
        'googleanalyticsaccess' => sanitize_text_field( $_POST['googleanalyticsaccess'] ),
        'googleanalyticsusername' => sanitize_text_field( $_POST['googleanalyticsusername'] ),
        'googleanalyticspassword' => sanitize_text_field( $_POST['googleanalyticspassword'] ),
        'searchconsoleaccess' => sanitize_text_field( $_POST['searchconsoleaccess'] ),
        'searchconsoleusername' => sanitize_text_field( $_POST['searchconsoleusername'] ),
        'searchconsolepassword' => sanitize_text_field( $_POST['searchconsolepassword'] ),
        'hosturl' => sanitize_text_field( $_POST['hosturl'] ),
        'hostusername' => sanitize_text_field( $_POST['hostusername'] ),
        'hostpassword' => sanitize_text_field( $_POST['hostpassword'] ),
        'bldsubmitted' => sanitize_text_field( $_POST['bldsubmitted'] ),
        'bldcsvurl1' => sanitize_text_field( $_POST['bldcsvurl1'] ),
        'bldcsvurl2' => sanitize_text_field( $_POST['bldcsvurl2'] ),
        'bldcsvurl3' => sanitize_text_field( $_POST['bldcsvurl3'] ),
        'bldcsvurl4' => sanitize_text_field( $_POST['bldcsvurl4'] ),
        'bldcsvurl5' => sanitize_text_field( $_POST['bldcsvurl5'] ),
        'periodcomplete' => sanitize_text_field( $_POST['periodcomplete'] ),
        'servicesdescription' => sanitize_text_field( $_POST['servicesdescription'] ),
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'services_seo_related';
	$where = array(
	    'client_id' => intval(sanitize_text_field($_POST['clientid'])),
	    'id' => sanitize_text_field($_POST['seoentryid'])
	);
	$wpdb->update(
	    $table_name,
	    $data,
	    $where
	);

	if ($wpdb->last_error) {
	    // Handle the error
	    wp_die( 'Insert failed: ' . $wpdb->last_error );
	} else {
		wp_die( 'success' );
	}

}

function cm_service_logo_edit() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'business_name' => sanitize_text_field( $_POST['business_name'] ),
		'project_unique_name' => sanitize_text_field( $_POST['project_unique_name'] ),
		'currentlogourl' => sanitize_text_field( $_POST['currentlogourl'] ),
		'draft1url' => sanitize_text_field( $_POST['draft1url'] ),
		'draft1colorcodes' => sanitize_text_field( $_POST['draft1colorcodes'] ),
		'draft1fonts' => sanitize_text_field( $_POST['draft1fonts'] ),
		'draft1notes' => sanitize_text_field( $_POST['draft1notes'] ),
		'draft2url' => sanitize_text_field( $_POST['draft2url'] ),
		'draft2colorcodes' => sanitize_text_field( $_POST['draft2colorcodes'] ),
		'draft2fonts' => sanitize_text_field( $_POST['draft2fonts'] ),
		'draft2notes' => sanitize_text_field( $_POST['draft2notes'] ),
		'draft3url' => sanitize_text_field( $_POST['draft3url'] ),
		'draft3colorcodes' => sanitize_text_field( $_POST['draft3colorcodes'] ),
		'draft3fonts' => sanitize_text_field( $_POST['draft3fonts'] ),
		'draft3notes' => sanitize_text_field( $_POST['draft3notes'] ),
		'finallogourl' => sanitize_text_field( $_POST['finallogourl'] ),
		'finallogocolorcodes' => sanitize_text_field( $_POST['finallogocolorcodes'] ),
		'finallogofonts' => sanitize_text_field( $_POST['finallogofonts'] ),
		'finallogonotes' => sanitize_text_field( $_POST['finallogonotes'] ),
		'finallogovarianturl1' => sanitize_text_field( $_POST['finallogovarianturl1'] ),
		'finallogovarianturl2' => sanitize_text_field( $_POST['finallogovarianturl2'] ),
		'finallogovarianturl3' => sanitize_text_field( $_POST['finallogovarianturl3'] ),
		'finalfavicon' => sanitize_text_field( $_POST['finalfavicon'] ),
		'zipdownloadurl' => sanitize_text_field( $_POST['zipdownloadurl'] ),
		'finallogonotes' => sanitize_text_field( $_POST['finallogonotes'] ),
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'services_logo_design';
	$where = array( 'id' => intval( sanitize_text_field( $_POST['clientid'] ) ) );
	$wpdb->update(
		$table_name,
		$data,
		$where
	);

	if ($wpdb->last_error) {
	    // Handle the error
	    echo 'Insert failed: ' . $wpdb->last_error;
	} else {
		wp_die('success');
	}
}

function cm_service_maintenance_edit() {
	validate_ajax_nonce(); // Standardized nonce check

	error_log("in the function");

	$data = array(
		'business_name' => sanitize_text_field( $_POST['business_name'] ),
		'support_start_date' => sanitize_text_field( $_POST['support_start_date'] ),
		'support_end_date' => sanitize_text_field( $_POST['support_end_date'] ),
		'website_url' => sanitize_text_field( $_POST['website_url'] ),
		'plugin_updates' => sanitize_text_field( $_POST['plugin_updates'] ),
		'core_file_updates' => sanitize_text_field( $_POST['core_file_updates'] ),
		'theme_file_updates' => sanitize_text_field( $_POST['theme_file_updates'] ),
		'ssl_cert' => sanitize_text_field( $_POST['ssl_cert'] ),
		'support_hours_type' => sanitize_text_field( $_POST['support_hours_type'] ),
		'hours_accrue' => sanitize_text_field( $_POST['hours_accrue'] ),
		'accrue_limit' => sanitize_text_field( $_POST['accrue_limit'] ),
		'hourly_rate' => sanitize_text_field( $_POST['hourly_rate'] ),
		'bonus_hours_pool' => sanitize_text_field( $_POST['bonus_hours_pool'] ),
		'total_bonus_hours_used' => sanitize_text_field( $_POST['total_bonus_hours_used'] ),
		'hours_initially_available' => sanitize_text_field( $_POST['hours_initially_available'] ),
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'services_website_maintenance';
	$where = array( 'id' => intval( sanitize_text_field( $_POST['maintenanceid'] ) ) );
	$wpdb->update(
		$table_name,
		$data,
		$where
	);

	if ($wpdb->last_error) {
	    // Handle the error
	    echo 'Insert failed: ' . $wpdb->last_error;
	    error_log($wpdb->last_error);
	} else {
		wp_die('success');
	}
}

function cm_service_website_creative_brief_edit() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'business_name' => sanitize_text_field( $_POST['business_name'] ),
		'project_start_date' => sanitize_text_field( $_POST['project_start_date'] ),
		'generaldescription' => sanitize_text_field( $_POST['generaldescription'] ),
		'differentiators' => sanitize_text_field( $_POST['differentiators'] ),
		'awardsandcerts' => sanitize_text_field( $_POST['awardsandcerts'] ),
		'competitorinfo' => sanitize_text_field( $_POST['competitorinfo'] ),
		'competitorurl1' => sanitize_text_field( $_POST['competitorurl1'] ),
		'competitorurl2' => sanitize_text_field( $_POST['competitorurl2'] ),
		'competitorurl3' => sanitize_text_field( $_POST['competitorurl3'] ),
		'services' => sanitize_text_field( $_POST['services'] ),
		'logobrandbookurl' => $upload_dir['basedir'] . '/client_files/' . $_POST['business_name'] . '/logo_brandbook/',
		'logobrandbooknotes' => sanitize_text_field( $_POST['logobrandbooknotes'] ),
		'colornotes' => sanitize_text_field( $_POST['colornotes'] ),
		'fontnotes' => sanitize_text_field( $_POST['fontnotes'] ),
		'taglinesmottos' => sanitize_text_field( $_POST['taglinesmottos'] ),
		'inspowebsiteurl1' => sanitize_text_field( $_POST['inspowebsiteurl1'] ),
		'inspowebsiteurl2' => sanitize_text_field( $_POST['inspowebsiteurl2'] ),
		'inspowebsiteurl3' => sanitize_text_field( $_POST['inspowebsiteurl3'] ),
		'generaldesignnotes' => sanitize_text_field( $_POST['generaldesignnotes'] ),
		'targetaudiencenotes' => sanitize_text_field( $_POST['targetaudiencenotes'] ),
		'currentwebsiteurl' => sanitize_text_field( $_POST['currentwebsiteurl'] ),
		'currentwebsitelogin' => sanitize_text_field( $_POST['currentwebsitelogin'] ),
		'currentwebsitepassword' => sanitize_text_field( $_POST['currentwebsitepassword'] ),
		'currenthostingurl' => sanitize_text_field( $_POST['currenthostingurl'] ),
		'currenthostinglogin' => sanitize_text_field( $_POST['currenthostinglogin'] ),
		'currenthostingpassword' => sanitize_text_field( $_POST['currenthostingpassword'] ),
		'currentdomainurl' => sanitize_text_field( $_POST['currentdomainurl'] ),
		'currentdomainlogin' => sanitize_text_field( $_POST['currentdomainlogin'] ),
		'currentdomainpassword' => sanitize_text_field( $_POST['currentdomainpassword'] ),
		'socialfacebooklink' => sanitize_text_field( $_POST['socialfacebooklink'] ),
		'sociallinkedinlink' => sanitize_text_field( $_POST['sociallinkedinlink'] ),
		'socialinstagramlink' => sanitize_text_field( $_POST['socialinstagramlink'] ),
		'socialyoutubelink' => sanitize_text_field( $_POST['socialyoutubelink'] ),
		'socialtwitterlink' => sanitize_text_field( $_POST['socialtwitterlink'] ),
		'socialtiktoklink' => sanitize_text_field( $_POST['socialtiktoklink'] ),
		'socialpinterestlink' => sanitize_text_field( $_POST['socialpinterestlink'] ),
		'lastthoughts' => sanitize_text_field( $_POST['lastthoughts'] ),
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'website_creative_brief';
	$where = array( 'website_project_id' => intval( sanitize_text_field( $_POST['websiteid'] ) ) );
	$wpdb->update(
		$table_name,
		$data,
		$where
	);

	if ($wpdb->last_error) {
	    // Handle the error
	    echo 'Insert failed: ' . $wpdb->last_error;
	} else {
		wp_die('success');
	}
}

function cm_service_website_launch_checklist_edit() {
	validate_ajax_nonce(); // Standardized nonce check

	// Initialize the string variable
	$values = "";

	// Loop through each $_POST item
	foreach ($_POST as $key => $value) {
	    // Check if the name of the item begins with "checklist_value_"
	    if (strpos($key, 'checklist_value_') === 0) {
	        // Sanitize the value to prevent XSS attacks
	        $sanitized_value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	        
	        // Convert specific values to corresponding integers
	        switch ($sanitized_value) {
	            case "Not Completed":
	                $sanitized_value = 0;
	                break;
	            case "Completed":
	                $sanitized_value = 1;
	                break;
	            case "In Progress":
	                $sanitized_value = 2;
	                break;
	            case "N/A":
	                $sanitized_value = 3;
	                break;
	            default:
	                // If the value does not match any of the predefined values, keep it as is
	                break;
	        }

	        // Append the sanitized and converted value to the string, followed by a comma
	        $values .= $sanitized_value . ",";
	    }
	}

	// Remove the trailing comma if the string is not empty
	if (!empty($values)) {
	    $values = rtrim($values, ',');
	}

	// Output the final string
	error_log( $values );

	$data = array(
		'checklist_string' => $values,
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'website_launch_checklist';
	$where = array( 'website_project_id' => intval( sanitize_text_field( $_POST['websiteid'] ) ) );
	$wpdb->update(
		$table_name,
		$data,
		$where
	);

	if ($wpdb->last_error) {
	    // Handle the error
	    echo 'Insert failed: ' . $wpdb->last_error;
	} else {
		wp_die('success');
	}
}

function cm_service_website_hosting_create() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'client_id' => sanitize_text_field( $_POST['client_id'] ),
		'business_name' => sanitize_text_field( $_POST['business_name'] ),
		'hosting_start_date' => sanitize_text_field( $_POST['hosting_start_date'] ),
		'hosting_end_date' => sanitize_text_field( $_POST['hosting_end_date'] ),
		'hosting_website_url' => sanitize_text_field( $_POST['hosting_website_url'] ),
		'hosting_monthly_investment' => sanitize_text_field( $_POST['hosting_monthly_investment'] ),
		'hosting_total_investment' => sanitize_text_field( $_POST['hosting_total_investment'] ),
		'hosting_host' => sanitize_text_field( $_POST['hosting_host'] ),
		'hosting_url' => sanitize_text_field( $_POST['hosting_url'] ),
		'hosting_host_username' => sanitize_text_field( $_POST['hosting_host_username'] ),
		'hosting_host_password' => sanitize_text_field( $_POST['hosting_host_password'] ),
		'hosting_domain_responsibility' => sanitize_text_field( $_POST['hosting_domain_responsibility'] ),
		'hosting_domain_registrar_url' => sanitize_text_field( $_POST['hosting_domain_registrar_url'] ),
		'hosting_domain_registrar_username' => sanitize_text_field( $_POST['hosting_domain_registrar_username'] ),
		'hosting_domain_registrar_password' => sanitize_text_field( $_POST['hosting_domain_registrar_password'] ),
		'hosting_site_files_link' => sanitize_text_field( $_POST['hosting_site_files_link'] ),
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'services_website_hosting';

	$wpdb->insert(
		$table_name,
		$data
	);

	wp_send_json_success( array( 'message' => __( 'Successfully saved website hosting service', 'client-manager' ) ) );
}

function cm_service_seo_related_create() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'client_id' => sanitize_text_field( $_POST['clientid'] ),
		'service_unique_name' => sanitize_text_field( $_POST['service_unique_name'] ),
        'startdate' => sanitize_text_field( $_POST['startdate'] ),
        'enddate' => sanitize_text_field( $_POST['enddate'] ),
        'websiteurl' => sanitize_text_field( $_POST['websiteurl'] ),
        'websitelogin' => sanitize_text_field( $_POST['websitelogin'] ),
        'websitepassword' => sanitize_text_field( $_POST['websitepassword'] ),
        'monthlyamount' => sanitize_text_field( $_POST['monthlyamount'] ),
        'gbplinkandaccess1' => sanitize_text_field( $_POST['gbplinkandaccess1'] ),
        'gbplinkandaccess2' => sanitize_text_field( $_POST['gbplinkandaccess2'] ),
        'gbplinkandaccess3' => sanitize_text_field( $_POST['gbplinkandaccess3'] ),
        'gbplinkandaccess4' => sanitize_text_field( $_POST['gbplinkandaccess4'] ),
        'gbplinkandaccess5' => sanitize_text_field( $_POST['gbplinkandaccess5'] ),
        'registrarurl' => sanitize_text_field( $_POST['registrarurl'] ),
        'registrarusername' => sanitize_text_field( $_POST['registrarusername'] ),
        'registrarpassword' => sanitize_text_field( $_POST['registrarpassword'] ),
        'googleanalyticsaccess' => sanitize_text_field( $_POST['googleanalyticsaccess'] ),
        'googleanalyticsusername' => sanitize_text_field( $_POST['googleanalyticsusername'] ),
        'googleanalyticspassword' => sanitize_text_field( $_POST['googleanalyticspassword'] ),
        'searchconsoleaccess' => sanitize_text_field( $_POST['searchconsoleaccess'] ),
        'searchconsoleusername' => sanitize_text_field( $_POST['searchconsoleusername'] ),
        'searchconsolepassword' => sanitize_text_field( $_POST['searchconsolepassword'] ),
        'hosturl' => sanitize_text_field( $_POST['hosturl'] ),
        'hostusername' => sanitize_text_field( $_POST['hostusername'] ),
        'hostpassword' => sanitize_text_field( $_POST['hostpassword'] ),
        'bldsubmitted' => sanitize_text_field( $_POST['bldsubmitted'] ),
        'bldcsvurl1' => sanitize_text_field( $_POST['bldcsvurl1'] ),
        'bldcsvurl2' => sanitize_text_field( $_POST['bldcsvurl2'] ),
        'bldcsvurl3' => sanitize_text_field( $_POST['bldcsvurl3'] ),
        'bldcsvurl4' => sanitize_text_field( $_POST['bldcsvurl4'] ),
        'bldcsvurl5' => sanitize_text_field( $_POST['bldcsvurl5'] ),
        'periodcomplete' => sanitize_text_field( $_POST['periodcomplete'] ),
        'servicesdescription' => sanitize_text_field( $_POST['servicesdescription'] ),
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'services_seo_related';

	$wpdb->insert(
		$table_name,
		$data
	);

	wp_send_json_success( array( 'message' => __( 'Successfully saved SEO related service', 'client-manager' ) ) );
}

function cm_service_logo_create() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'client_id' => sanitize_text_field( $_POST['client_id'] ),
		'business_name' => sanitize_text_field( $_POST['business_name'] ),
		'project_unique_name' => sanitize_text_field( $_POST['projectuniquename'] ),
		'currentlogourl' => sanitize_text_field( $_POST['currentlogourl'] ),
		'draft1url' => sanitize_text_field( $_POST['draft1url'] ),
		'draft1colorcodes' => sanitize_text_field( $_POST['draft1colorcodes'] ),
		'draft1fonts' => sanitize_text_field( $_POST['draft1fonts'] ),
		'draft1notes' => sanitize_text_field( $_POST['draft1notes'] ),
		'draft2url' => sanitize_text_field( $_POST['draft2url'] ),
		'draft2colorcodes' => sanitize_text_field( $_POST['draft2colorcodes'] ),
		'draft2fonts' => sanitize_text_field( $_POST['draft2fonts'] ),
		'draft2notes' => sanitize_text_field( $_POST['draft2notes'] ),
		'draft3url' => sanitize_text_field( $_POST['draft3url'] ),
		'draft3colorcodes' => sanitize_text_field( $_POST['draft3colorcodes'] ),
		'draft3fonts' => sanitize_text_field( $_POST['draft3fonts'] ),
		'draft3notes' => sanitize_text_field( $_POST['draft3notes'] ),
		'finallogourl' => sanitize_text_field( $_POST['finallogourl'] ),
		'finallogocolorcodes' => sanitize_text_field( $_POST['finallogocolorcodes'] ),
		'finallogofonts' => sanitize_text_field( $_POST['finallogofonts'] ),
		'finallogonotes' => sanitize_text_field( $_POST['finallogonotes'] ),
		'finallogovarianturl1' => sanitize_text_field( $_POST['finallogovarianturl1'] ),
		'finallogovarianturl2' => sanitize_text_field( $_POST['finallogovarianturl2'] ),
		'finallogovarianturl3' => sanitize_text_field( $_POST['finallogovarianturl3'] ),
		'finalfavicon' => sanitize_text_field( $_POST['finalfavicon'] ),
		'zipdownloadurl' => sanitize_text_field( $_POST['zipdownloadurl'] ),
		'finallogonotes' => sanitize_text_field( $_POST['finallogonotes'] ),
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'services_logo_design';

	$wpdb->insert(
		$table_name,
		$data
	);

	wp_send_json_success( array( 'message' => __( 'Successfully saved new logo service', 'client-manager' ) ) );
}

function cm_service_website_maintenance_create() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'client_id' => sanitize_text_field( $_POST['client_id'] ),
		'business_name' => sanitize_text_field( $_POST['business_name'] ),
		'support_start_date' => sanitize_text_field( $_POST['support_start_date'] ),
		'support_end_date' => sanitize_text_field( $_POST['support_end_date'] ),
		'website_url' => sanitize_text_field( $_POST['website_url'] ),
		'plugin_updates' => sanitize_text_field( $_POST['plugin_updates'] ),
		'core_file_updates' => sanitize_text_field( $_POST['core_file_updates'] ),
		'theme_file_updates' => sanitize_text_field( $_POST['theme_file_updates'] ),
		'ssl_cert' => sanitize_text_field( $_POST['ssl_cert'] ),
		'support_hours_type' => sanitize_text_field( $_POST['support_hours_type'] ),
		'hours_accrue' => sanitize_text_field( $_POST['hours_accrue'] ),
		'accrue_limit' => sanitize_text_field( $_POST['accrue_limit'] ),
		'hourly_rate' => sanitize_text_field( $_POST['hourly_rate'] ),
		'bonus_hours_pool' => sanitize_text_field( $_POST['bonus_hours_pool'] ),
		'total_bonus_hours_used' => sanitize_text_field( $_POST['total_bonus_hours_used'] ),
		'hours_initially_available' => sanitize_text_field( $_POST['hours_initially_available'] ),
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'services_website_maintenance';

	$wpdb->insert(
		$table_name,
		$data
	);

	wp_send_json_success( array( 'message' => __( 'Successfully saved website maintenance service', 'client-manager' ) ) );
}

function cm_service_website_support_ticket_create() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'creationdatetime' => date("Y-m-d\TH:i"),
		'startdatetime' => sanitize_text_field( $_POST['startdatetime'] ),
		'enddatetime' => sanitize_text_field( $_POST['enddatetime'] ),
		'status' => sanitize_text_field( $_POST['status'] ),
		'client_id' => sanitize_text_field( $_POST['clientid'] ),
		'websiteurl' => sanitize_text_field( $_POST['websiteurl'] ),
		'nocharge' => sanitize_text_field( $_POST['nocharge'] ),
		'submitteremail' => sanitize_text_field( $_POST['submitteremail'] ),
		'submitterphone' => sanitize_text_field( $_POST['submitterphone'] ),
		'initialdescription' => sanitize_text_field( $_POST['initialdescription'] ),
		'notes' => sanitize_text_field( $_POST['notes'] ),
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'support_tickets';

	$wpdb->insert(
		$table_name,
		$data
	);

	wp_send_json_success( array( 'message' => __( 'Successfully created a new Website Support Ticket!', 'client-manager' ) ) );
}

function cm_support_ticket_edit() {
	validate_ajax_nonce(); // Standardized nonce check

	$data = array(
		'startdatetime' => sanitize_text_field( $_POST['startdatetime'] ),
		'enddatetime' => sanitize_text_field( $_POST['enddatetime'] ),
		'status' => sanitize_text_field( $_POST['status'] ),
		'websiteurl' => sanitize_text_field( $_POST['websiteurl'] ),
		'nocharge' => sanitize_text_field( $_POST['nocharge'] ),
		'submitteremail' => sanitize_text_field( $_POST['submitteremail'] ),
		'submitterphone' => sanitize_text_field( $_POST['submitterphone'] ),
		'initialdescription' => sanitize_text_field( $_POST['initialdescription'] ),
		'notes' => sanitize_text_field( $_POST['notes'] ),
	);

	global $wpdb;
	$table_name = $wpdb->prefix . 'support_tickets';
	$where = array( 'id' => intval( sanitize_text_field( $_POST['ticketid'] ) ) );
	$wpdb->update(
		$table_name,
		$data,
		$where
	);

	if ($wpdb->last_error) {
	    // Handle the error
	    echo 'Insert failed: ' . $wpdb->last_error;
	} else {
		wp_die('success');
	}
}