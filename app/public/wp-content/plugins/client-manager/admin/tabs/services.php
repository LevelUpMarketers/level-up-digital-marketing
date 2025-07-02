<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function cm_render_projects_tab() {
	?>
	<h2><?php _e( 'Services', 'client-manager' ); ?></h2>
	<?php

	// all required DB calls and building of HTML:
	global $wpdb;
	$table_name = $wpdb->prefix . 'clients';
	$clients = $wpdb->get_results( "SELECT * FROM $table_name" );

	$select_a_client_dropdown_html = '<option selected disabled>Select a Client...</option>';
	foreach ($clients as $clientkey => $clientvalue) {
		$select_a_client_dropdown_html = $select_a_client_dropdown_html . '<option>' . $clientvalue->business_name . ' - ' . $clientvalue->id . '</option>';
	}

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

	foreach ( $launch_checklist_array as $checklist_key => $checklist_value) {
		$launch_checklist_form_html = $launch_checklist_form_html .
		'<div class="cm-new-project-lcl-form-entry-holder">
			<label>' . ucfirst($checklist_value) . '</label>
			<select id="checklist_value_' . $checklist_key . '">
				<option>Not Completed</option>
				<option>Completed</option>
				<option>In Progress</option>
				<option>N/A</option>
			</select>
		</div>';	
	}



	// Now let's build individual Project/Service Forms
	$website_design_development_form = 
	'<div class="cm-new-project-form-actual-top-holder cm-website-design-development-form">
		<div class="cm-form-title">Website Design & Development</div>
		<div class="cm-new-project-form-actual-inner-holder">
			<div class="cm-new-project-form-entry-holder">
				<label>Project Name</label>
				<input id="service_unique_name" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Start Date</label>
				<input id="project_start_date" type="date"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Completion Date</label>
				<input id="project_completion_date" type="date"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Launch Date</label>
				<input id="project_launch_date" type="date"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Project Status</label>
				<select id="project_status">
					<option>Onboarding</option>
					<option>Awaiting Creative Brief Completion</option>
					<option>Awaiting Kickoff Call</option>
					<option>Homepage Design & Development in Progress</option>
					<option>Awaiting Homepage Feedback</option>
					<option>Implementing Homepage Feedback</option>
					<option>Full Site Design & Development in Progress</option>
					<option>Awaiting Full Site Feedback</option>
					<option>Implementing Full Site Feedback</option>
					<option>Website Launch Approved - QA Checklist in Progress</option>
					<option>QA Checklist Completed - Awaiting Launch</option>
					<option>Launched - Post-Launch QA Checklist in Progress</option>
					<option>Launched</option>
					<option>Completed</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Final Website URL</label>
				<input id="project_url" class="jre-validateurl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Development URL</label>
				<input id="project_dev_url" class="jre-validateurl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Total Investment</label>
				<input id="project_total_investment" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Who Hosts</label>
				<select id="project_host">
					<option>Level Up</option>
					<option>The Client</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Host URL</label>
				<input id="project_host_url" class="jre-validateurl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Host Login Username</label>
				<input id="project_host_username" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Host Login Password</label>
				<input id="project_host_password" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Domain Responsibility</label>
				<select id="project_domain_responsibility">
					<option>Level Up</option>
					<option>The Client</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Domain Registrar URL</label>
				<input id="project_domain_registrar_url" class="jre-validateurl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Domain Registrar Username</label>
				<input id="project_domain_registrar_username" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Domain Registrar Password</label>
				<input id="project_domain_registrar_password" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Client Approved Homepage?</label>
				<select id="project_homepage_approval">
					<option>Not Yet</option>
					<option>Yes - Client Clicked Approved Button</option>
					<option>Email Approval</option>
					<option>Verbal Approval</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Client Approved Full Site?</label>
				<select id="project_full_site_approval">
					<option>Not Yet</option>
					<option>Yes - Client Clicked Approved Button</option>
					<option>Email Approval</option>
					<option>Verbal Approval</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Google Analytics Access?</label>
				<select id="project_google_analytics_access">
					<option>Not Yet</option>
					<option>Yes - Client Granted Access</option>
					<option>Yes - Level Up Created Analytics Account</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Google Analytics Username</label>
				<input id="project_google_analytics_username" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Google Analytics Password</label>
				<input id="project_google_analytics_password" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Google Search Console Access?</label>
				<select id="project_search_console_access">
					<option>Not Yet</option>
					<option>Yes - Client Granted Access</option>
					<option>Yes - Level Up Created Account</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Search Console Username</label>
				<input id="project_search_console_username" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Search Console Password</label>
				<input id="project_search_console_password" type="text"/>
			</div>
			<div style="width:95%;margin-left:auto;margin-right: auto;" class="cm-indiv-subtitle-inner-form">
				<div class="cm-indiv-subtitle-inner-form-title">Creative Brief</div>
				<div class="cm-indiv-subtitle-inner-holder">
					<div class="cm-new-project-cb-form-entry-holder">
						<label>General Business Description</label>
						<textarea id="generaldescription"></textarea>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Differentiators</label>
						<textarea id="differentiators"></textarea>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Awards, Certifications, Etc.</label>
						<textarea id="awardsandcerts"></textarea>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Competitor Information</label>
						<textarea id="competitorinfo"></textarea>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Competitor #1 Website</label>
						<input id="competitorurl1" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Competitor #2 Website</label>
						<input id="competitorurl2" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Competitor #3 Website</label>
						<input id="competitorurl3" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Services information</label>
						<textarea id="services"></textarea>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Logo & Branding Elements</label>
						<textarea id="logobrandbooknotes"></textarea>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Color Preferences</label>
						<textarea id="colornotes"></textarea>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Font Preferences</label>
						<textarea id="fontnotes"></textarea>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Inspiration Website URL #1</label>
						<input id="inspowebsiteurl1" class="jre-validateurl" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Inspiration Website URL #2</label>
						<input id="inspowebsiteurl2" class="jre-validateurl" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Inspiration Website URL #3</label>
						<input id="inspowebsiteurl3" class="jre-validateurl" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>General Design Notes</label>
						<textarea id="generaldesignnotes"></textarea>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Taglines & Mottos</label>
						<textarea id="taglinesmottos"></textarea>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Target Audience Notes</label>
						<textarea id="targetaudiencenotes"></textarea>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Current Website URL</label>
						<input id="currentwebsiteurl" class="jre-validateurl" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Current Website Username/Email Login</label>
						<input id="currentwebsitelogin" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Current Website Password</label>
						<input id="currentwebsitepassword" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Current Website Hosting URL</label>
						<input id="currenthostingurl" class="jre-validateurl" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Current Website Hosting Username/Email Login</label>
						<input id="currenthostinglogin" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Current Website Hosting Password</label>
						<input id="currenthostingpassword" type="text"/>
					</div>

					<div class="cm-new-project-cb-form-entry-holder">
						<label>Current Domain URL</label>
						<input id="currentdomainurl" class="jre-validateurl" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Current Domain Username/Email Login</label>
						<input id="currentdomainlogin" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Current Domain Password</label>
						<input id="currentdomainpassword" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Facebook Link</label>
						<input id="socialfacebooklink" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>LinkedIn Link</label>
						<input id="sociallinkedinlink" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Instagram Link</label>
						<input id="socialinstagramlink" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>YouTube Link</label>
						<input id="socialyoutubelink" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Twitter/X Link</label>
						<input id="socialtwitterlink" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>TikTok</label>
						<input id="socialtiktoklink" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Pinterest</label>
						<input id="socialpinterestlink" type="text"/>
					</div>
					<div class="cm-new-project-cb-form-entry-holder">
						<label>Last Thoughts?</label>
						<textarea id="lastthoughts"></textarea>
					</div>
				</div>
			</div>
			<div style="width:95%;margin-left:auto;margin-right: auto;" class="cm-indiv-subtitle-inner-form cm-indiv-subtitle-inner-launch-checklist-form">
				<div class="cm-indiv-subtitle-inner-form-title">Launch Checklist</div>
				<div class="cm-indiv-subtitle-inner-holder">
					' . $launch_checklist_form_html . '
				</div>
			</div>
		</div>
		<button class="cm-new-project-create cm-create-new-website-service">Save & Create New Service</button>
	</div>';

	// Now let's build individual Project/Service Forms...
	$website_hosting_form = 
	'<div class="cm-new-project-form-actual-top-holder cm-website-hosting-form">
		<div class="cm-form-title">Website Hosting</div>
		<div class="cm-new-project-form-actual-inner-holder">
			<div class="cm-new-project-form-entry-holder">
				<label>Hosting Start Date</label>
				<input id="hosting_start_date" type="date"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Hosting End Date</label>
				<input id="hosting_end_date" type="date"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Website URL</label>
				<input id="hosting_website_url" class="jre-validateurl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Who Hosts</label>
				<select id="hosting_host">
					<option>Level Up</option>
					<option>The Client</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Host URL</label>
				<input id="hosting_url" class="jre-validateurl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Host Username</label>
				<input id="hosting_host_username" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Host Password</label>
				<input id="hosting_host_password" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Domain Responsibility</label>
				<select id="hosting_domain_responsibility">
					<option>Level Up</option>
					<option>The Client</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Domain Registrar URL</label>
				<input id="hosting_domain_registrar_url" class="jre-validateurl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Domain Registrar Username</label>
				<input id="hosting_domain_registrar_username" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Domain Registrar Password</label>
				<input id="hosting_domain_registrar_password" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Hosting Site Files Link</label>
				<input id="hosting_site_files_link" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Hosting Monthly Investment</label>
				<input id="hosting_monthly_investment" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Hosting Total Investmennt</label>
				<input id="hosting_total_investment" type="text"/>
			</div>
		</div>
		<button class="cm-new-project-create cm-create-new-hosting-service">Save & Create New Service</button>
	</div>';

	// Now let's build the Website Maintenance & Support Form...
	$website_maintenance_form = 
	'<div class="cm-new-project-form-actual-top-holder cm-website-maintenance-form">
		<div class="cm-form-title">Website Maintenance & Support</div>
		<div class="cm-new-project-form-actual-inner-holder">
			<div class="cm-new-project-form-entry-holder">
				<label>Support Start Date</label>
				<input id="support_start_date" type="date"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Support End Date</label>
				<input id="support_end_date" type="date"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Website URL</label>
				<input id="website_url" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Plugin Updates?</label>
				<select id="plugin_updates">
					<option>Yes</option>
					<option>No</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Theme File Updates?</label>
				<select id="theme_file_updates">
					<option>Yes</option>
					<option>No</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Core File Updates?</label>
				<select id="core_file_updates">
					<option>Yes</option>
					<option>No</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>SSL Security Certificates?</label>
				<select id="ssl_cert">
					<option>Yes</option>
					<option>No</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Support Hours Type?</label>
				<select id="support_hours_type">
					<option>Monthly</option>
					<option>Contract Allotment</option>
					<option>Ala-Carte</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Hourly Rate</label>
				<input id="hourly_rate" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Hours Initially Available (total per month or contract)</label>
				<input id="hours_initially_available" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Hours Accrue?</label>
				<select id="hours_accrue">
					<option>Yes</option>
					<option>No</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Hours Accrual Limit</label>
				<input id="accrue_limit" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Bonus Hours Pool</label>
				<input id="bonus_hours_pool" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Total Bonus Hours Used</label>
				<input id="total_bonus_hours_used" type="text"/>
			</div>
		</div>
		<button class="cm-new-project-create cm-create-new-maintenance-service">Save & Create New Service</button>
	</div>';

	// Now let's build the Logo Form...
	$logo_service_form = 
	'<div class="cm-new-project-form-actual-top-holder cm-logo-form">
		<div class="cm-form-title">Logo Project</div>
		<div class="cm-new-project-form-actual-inner-holder">
			<div class="cm-new-project-form-entry-holder">
				<label>Logo Project Name</label>
				<input id="projectuniquename" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Current Logo URL</label>
				<input id="currentlogourl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Draft 1 URL</label>
				<input id="draft1url" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Draft 1 Color Codes</label>
				<input id="draft1colorcodes" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Draft 1 Fonts</label>
				<input id="draft1fonts" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Draft 1 Notes</label>
				<textarea id="draft1notes"></textarea>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Draft 2 URL</label>
				<input id="draft2url" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Draft 2 Color Codes</label>
				<input id="draft2colorcodes" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Draft 2 Fonts</label>
				<input id="draft2fonts" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Draft 2 Notes</label>
				<textarea id="draft2notes"></textarea>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Draft 3 URL</label>
				<input id="draft3url" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Draft 3 Color Codes</label>
				<input id="draft3colorcodes" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Draft 3 Fonts</label>
				<input id="draft3fonts" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Draft 3 Notes</label>
				<textarea id="draft3notes"></textarea>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Final Logo URL</label>
				<input id="finallogourl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Final Logo Color Codes</label>
				<input id="finallogocolorcodes" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Final Logo Fonts</label>
				<input id="finallogofonts" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Final Logo Variant 1</label>
				<input id="finallogovarianturl1" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Final Logo Variant 2</label>
				<input id="finallogovarianturl2" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Final Logo Variant 3</label>
				<input id="finallogovarianturl3" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Final Favicon</label>
				<input id="finalfavicon" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Final Files Zip URL</label>
				<input id="zipdownloadurl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Final Logo Notes</label>
				<textarea id="finallogonotes"></textarea>
			</div>
		</div>
		<button class="cm-new-project-create cm-create-new-logo-service">Save & Create New Service</button>
	</div>';

	// Now let's build individual Project/Service Forms...
	$seo_services_form = 
	'<div class="cm-new-project-form-actual-top-holder cm-seo-services-form">
		<div class="cm-form-title">SEO Related Services</div>
		<div class="cm-new-project-form-actual-inner-holder">
			<div class="cm-new-project-form-entry-holder">
		    	<label>Project Name</label>
		    	<input id="service_unique_name" type="text">
		    </div>
			<div class="cm-new-project-form-entry-holder">
				<label>SEO Services Start Date</label>
				<input id="startdate" type="date"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>SEO Services End Date</label>
				<input id="enddate" type="date"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Monthly Amount</label>
				<input id="monthlyamount" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Website URL</label>
				<input id="websiteurl" class="jre-validateurl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Website Login</label>
				<input id="websitelogin" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Website Password</label>
				<input id="websitepassword" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>GBP 1 Link</label>
				<input id="gbp1link" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>GBP 1 Access</label>
				<select id="gbp1access">
					<option>Yes</option>
					<option>No</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>GBP 2 Link</label>
				<input id="gbp2link" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>GBP 2 Access</label>
				<select id="gbp2access">
					<option>Yes</option>
					<option>No</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>GBP 3 Link</label>
				<input id="gbp3link" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>GBP 3 Access</label>
				<select id="gbp3access">
					<option>Yes</option>
					<option>No</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>GBP 4 Link</label>
				<input id="gbp4link" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>GBP 4 Access</label>
				<select id="gbp4access">
					<option>Yes</option>
					<option>No</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>GBP 5 Link</label>
				<input id="gbp5link" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>GBP 5 Access</label>
				<select id="gbp5access">
					<option>Yes</option>
					<option>No</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Registrar URL</label>
				<input id="registrarurl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Registrar Username</label>
				<input id="registrarusername" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Registrar Password</label>
				<input id="registrarpassword" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Google Analytics Access</label>
				<select id="googleanalyticsaccess">
					<option>Not Yet</option>
					<option>Yes - Client Granted Access</option>
					<option>Yes - Level Up Created Analytics Account</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Google Analytics Username</label>
				<input id="googleanalyticsusername" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Google Analytics Password</label>
				<input id="googleanalyticspassword" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Search Console Access</label>
				<select id="searchconsoleaccess">
					<option>Not Yet</option>
					<option>Yes - Client Granted Access</option>
					<option>Yes - Level Up Created Analytics Account</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Search Console Username</label>
				<input id="searchconsoleusername" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Search Console Password</label>
				<input id="searchconsolepassword" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Host URL</label>
				<input id="hosturl" class="jre-validateurl" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Host Login Username</label>
				<input id="hostusername" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Host Login Password</label>
				<input id="hostpassword" type="text"/>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>BLDs Submitted</label>
				<select id="bldsubmitted">
					<option>Yes</option>
					<option>No</option>
					<option>Only Some Locations</option>
					<option>N/A</option>
				</select>
			</div>
			<br/>
			<div class="cm-new-project-form-entry-holder">
				<label>BLD CSV URL 1</label>
				<input id="bldcsvurl1" type="text"/>
				<input class="cm-bldcsvupload-input cm-class-upload-bldcsvurl1" type="file" id="file-upload-bldcsvurl1" name="file-upload-bldcsvurl1" />
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>BLD CSV URL 2</label>
				<input id="bldcsvurl2" type="text"/>
				<input class="cm-bldcsvupload-input cm-class-upload-bldcsvurl2" type="file" id="file-upload-bldcsvurl2" name="file-upload-bldcsvurl2" />
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>BLD CSV URL 3</label>
				<input id="bldcsvurl3" type="text"/>
				<input class="cm-bldcsvupload-input cm-class-upload-bldcsvurl3" type="file" id="file-upload-bldcsvurl3" name="file-upload-bldcsvurl3" />
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>BLD CSV URL 4</label>
				<input id="bldcsvurl4" type="text"/>
				<input class="cm-bldcsvupload-input cm-class-upload-bldcsvurl4" type="file" id="file-upload-bldcsvurl4" name="file-upload-bldcsvurl4" />
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>BLD CSV URL 5</label>
				<input id="bldcsvurl5" type="text"/>
				<input class="cm-bldcsvupload-input cm-class-upload-bldcsvurl5" type="file" id="file-upload-bldcsvurl5" name="file-upload-bldcsvurl5" />
			</div>
			<br/>
			<div class="cm-new-project-form-entry-holder">
				<label>Period Complete?</label>
				<select id="periodcomplete">
					<option>Yes</option>
					<option>No</option>
					<option>N/A</option>
				</select>
			</div>
			<div class="cm-new-project-form-entry-holder">
				<label>Service Description</label>
				<textarea id="servicesdescription"></textarea>
			</div>
		</div>
		<button class="cm-new-project-create cm-create-new-seo-related-service">Save & Create New SEO Related Service</button>
	</div>';

	var_dump(print_r($clients, true));

	$project_html_open = '
	<div class="cm-top-project-holder">
		<div class="cm-top-project-new-holder">
			<div class="cm-top-project-new-type">
				<p>Select a Service:</p>
				<select id="cm-new-project-select-a-project-type">
					<option selected default disabled>Select a Service...</option>
					<option>Website Design & Development</option>
					<option>Website Hosting</option>
					<option>Website Maintenance & Support</option>
					<option>Logo</option>
					<option>SEO Related Services</option>
					<option>Content Marketing Related Services</option>
					<option>Social Media Management Services</option>
					<option>Miscellaneous Website Edits, Modifications, & Enhancements</option>
					<option>Miscellaneous Project or Service</option>
					<option>Custom Development</option>
				</select>
			</div>
			<div class="cm-top-project-new-which-client">
				<p>Select A Client:</p>
				<select id="cm-new-project-select-a-client">
					' . $select_a_client_dropdown_html . '
				</select>
			</div>';

			$new_project_html_open = '
			<div class="indiv-new-project-form-holder">

				' . $website_design_development_form . $website_hosting_form . $website_maintenance_form . $logo_service_form . $seo_services_form . '

			</div>';


		$new_project_html_close = '
		</div>';

	$project_html_close = '
	</div>';




	echo $project_html_open . $new_project_html_open . $new_project_html_close . $project_html_close;





	
}
