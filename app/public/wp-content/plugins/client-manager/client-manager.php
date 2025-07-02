<?php
/*
Plugin Name: Client Manager
Description: A plugin to manage clients, projects, support tickets, activity tracker, and reporting & analytics.
Version: 1.1
Author: Your Name
Text Domain: client-manager
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Define constants
define( 'CM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files
require_once CM_PLUGIN_DIR . 'admin/admin-menu.php';
require_once CM_PLUGIN_DIR . 'admin/ajax-handler.php';

// Include the REST API endpoint file.
require_once CM_PLUGIN_DIR . 'api/class-activity-endpoint.php';


// Activation hook to create custom database table
register_activation_hook( __FILE__, 'cm_create_custom_table' );

function cm_create_custom_table() {

	global $wpdb;
	$table_name = $wpdb->prefix . 'client_reporting_info';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		client_id varchar(255) NOT NULL,
		remotecode varchar(255) NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	$table_name = $wpdb->prefix . 'clients';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		business_name varchar(255) NOT NULL,
		dburl varchar(255) NOT NULL,
		client_since_date date NOT NULL,
		logo varchar(255) NOT NULL,
		main_location_address_2 varchar(255) NOT NULL,
		main_location_name varchar(255) NOT NULL,
		main_location_state varchar(255) NOT NULL,
		main_location_city varchar(255) NOT NULL,
		main_location_street_address varchar(255) NOT NULL,
		main_location_zip varchar(255) NOT NULL,
		main_poc_email varchar(255) NOT NULL,
		main_poc_first_name varchar(255) NOT NULL,
		main_poc_headshot varchar(255) NOT NULL,
		main_poc_last_name varchar(255) NOT NULL,
		main_poc_phone varchar(255) NOT NULL,
		main_poc_title varchar(255) NOT NULL,
		poc_2_email varchar(255) NOT NULL,
		poc_2_first_name varchar(255) NOT NULL,
		poc_2_headshot varchar(255) NOT NULL,
		poc_2_last_name varchar(255) NOT NULL,
		poc_2_phone varchar(255) NOT NULL,
		poc_2_title varchar(255) NOT NULL,
		poc_3_email varchar(255) NOT NULL,
		poc_3_first_name varchar(255) NOT NULL,
		poc_3_headshot varchar(255) NOT NULL,
		poc_3_last_name varchar(255) NOT NULL,
		poc_3_phone varchar(255) NOT NULL,
		poc_3_title varchar(255) NOT NULL,
		second_location_address_2 varchar(255) NOT NULL,
		second_location_name varchar(255) NOT NULL,
		second_location_state varchar(255) NOT NULL,
		second_location_city varchar(255) NOT NULL,
		second_location_street_address varchar(255) NOT NULL,
		second_location_zip varchar(255) NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	$table_name = $wpdb->prefix . 'services_website_project';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		client_id varchar(255) NOT NULL,
		business_name varchar(255) NOT NULL,
		service_unique_name varchar(255) NOT NULL,
		project_start_date date NOT NULL,
		project_completion_date date NOT NULL,
		project_launch_date date NOT NULL,
		project_status varchar(255) NOT NULL,
		project_url varchar(255) NOT NULL,
		project_dev_url varchar(255) NOT NULL,
		project_total_investment varchar(255) NOT NULL,
		project_host varchar(255) NOT NULL,
		project_host_url varchar(255) NOT NULL,
		project_host_username varchar(255) NOT NULL,
		project_host_password varchar(255) NOT NULL,
		project_domain_responsibility varchar(255) NOT NULL,
		project_domain_registrar_url varchar(255) NOT NULL,
		project_domain_registrar_username varchar(255) NOT NULL,
		project_domain_registrar_password varchar(255) NOT NULL,
		project_creative_brief_id varchar(255) NOT NULL,
		project_launch_checklist_id varchar(255) NOT NULL,
		project_homepage_approval varchar(255) NOT NULL,
		project_full_site_approval varchar(255) NOT NULL,
		project_google_analytics_access varchar(255) NOT NULL,
		project_google_analytics_username varchar(255) NOT NULL,
		project_google_analytics_password varchar(255) NOT NULL,
		project_search_console_access varchar(255) NOT NULL,
		project_search_console_username varchar(255) NOT NULL,
		project_search_console_password varchar(255) NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	$table_name = $wpdb->prefix . 'website_launch_checklist';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		client_id varchar(255) NOT NULL,
		website_project_id varchar(255) NOT NULL,
		business_name varchar(255) NOT NULL,
		checklist_string varchar(255) NOT NULL,
		project_start_date date NOT NULL,
		project_completion_date date NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	$table_name = $wpdb->prefix . 'website_creative_brief';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		client_id varchar(255) NOT NULL,
		website_project_id varchar(255) NOT NULL,
		business_name varchar(255) NOT NULL,
		project_start_date date NOT NULL,
		project_completion_date date NOT NULL,
		generaldescription longtext NOT NULL,
		differentiators longtext NOT NULL,
		awardsandcerts longtext NOT NULL,
		awardsandcertsurl varchar(255) NOT NULL,
		competitorinfo longtext NOT NULL,
		competitorurl1 varchar(255) NOT NULL,
		competitorurl2 varchar(255) NOT NULL,
		competitorurl3 varchar(255) NOT NULL,
		services longtext NOT NULL,
		logobrandbookurl varchar(255) NOT NULL,
		logobrandbooknotes longtext NOT NULL,
		colornotes longtext NOT NULL,
		fontnotes longtext NOT NULL,
		taglinesmottos longtext NOT NULL,
		inspowebsiteurl1 varchar(255) NOT NULL,
		inspowebsiteurl2 varchar(255) NOT NULL,
		inspowebsiteurl3 varchar(255) NOT NULL,
		generaldesignnotes longtext NOT NULL,
		targetaudiencenotes longtext NOT NULL,
		currentwebsiteurl varchar(255) NOT NULL,
		currentwebsitelogin varchar(255) NOT NULL,
		currentwebsitepassword varchar(255) NOT NULL,
		currenthostingurl varchar(255) NOT NULL,
		currenthostinglogin varchar(255) NOT NULL,
		currenthostingpassword varchar(255) NOT NULL,
		currentdomainurl varchar(255) NOT NULL,
		currentdomainlogin varchar(255) NOT NULL,
		currentdomainpassword varchar(255) NOT NULL,
		socialfacebooklink varchar(255) NOT NULL,
		sociallinkedinlink varchar(255) NOT NULL,
		socialinstagramlink varchar(255) NOT NULL,
		socialyoutubelink varchar(255) NOT NULL,
		socialtwitterlink varchar(255) NOT NULL,
		socialtiktoklink varchar(255) NOT NULL,
		socialpinterestlink varchar(255) NOT NULL,
		lastthoughts longtext NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	$table_name = $wpdb->prefix . 'services_website_hosting';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		client_id varchar(255) NOT NULL,
		service_unique_name varchar(255) NOT NULL,
		business_name varchar(255) NOT NULL,
		hosting_start_date date NOT NULL,
		hosting_end_date date NOT NULL,
		hosting_website_url varchar(255) NOT NULL,
		hosting_url varchar(255) NOT NULL,
		hosting_monthly_investment varchar(255) NOT NULL,
		hosting_total_investment varchar(255) NOT NULL,
		hosting_host varchar(255) NOT NULL,
		hosting_host_username varchar(255) NOT NULL,
		hosting_host_password varchar(255) NOT NULL,
		hosting_domain_responsibility varchar(255) NOT NULL,
		hosting_domain_registrar_url varchar(255) NOT NULL,
		hosting_domain_registrar_username varchar(255) NOT NULL,
		hosting_domain_registrar_password varchar(255) NOT NULL,
		hosting_site_files_link varchar(255) NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	// Talbe for an individual entry for each item of maintenance performed, i.e., plugin upudate, theme update, WP update, new page/post created, etc.
	$table_name = $wpdb->prefix . 'services_website_maintenance';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		client_id varchar(255) NOT NULL,
		website_url varchar(255) NOT NULL,
		business_name varchar(255) NOT NULL,
		plugin_updates varchar(255) NOT NULL,
		core_file_updates varchar(255) NOT NULL,
		theme_file_updates varchar(255) NOT NULL,
		ssl_cert varchar(255) NOT NULL,
		support_hours_type varchar(255) NOT NULL,
		hours_accrue varchar(255) NOT NULL,
		accrue_limit varchar(255) NOT NULL,
		support_start_date varchar(255) NOT NULL,
		support_end_date varchar(255) NOT NULL,
		hourly_rate varchar(255) NOT NULL,
		bonus_hours_pool varchar(255) NOT NULL,
		total_bonus_hours_used varchar(255) NOT NULL,
		hours_initially_available varchar(255) NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	$table_name = $wpdb->prefix . 'services_logo_design';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		client_id varchar(255) NOT NULL,
		business_name varchar(255) NOT NULL,
		projectuniquename varchar(255) NOT NULL,
		currentlogourl varchar(255) NOT NULL,
		draft1url varchar(255) NOT NULL,
		draft1notes longtext NOT NULL,
		draft1colorcodes varchar(255) NOT NULL,
		draft1fonts longtext NOT NULL,
		draft2url varchar(255) NOT NULL,
		draft2notes longtext NOT NULL,
		draft2colorcodes varchar(255) NOT NULL,
		draft2fonts longtext NOT NULL,
		draft3url varchar(255) NOT NULL,
		draft3notes longtext NOT NULL,
		draft3colorcodes varchar(255) NOT NULL,
		draft3fonts longtext NOT NULL,
		finallogourl varchar(255) NOT NULL,
		finallogonotes longtext NOT NULL,
		finallogocolorcodes varchar(255) NOT NULL,
		finallogofonts longtext NOT NULL,
		finallogovarianturl1 varchar(255) NOT NULL,
		finallogovarianturl2 varchar(255) NOT NULL,
		finallogovarianturl3 varchar(255) NOT NULL,
		finalfavicon varchar(255) NOT NULL,
		zipdownloadurl varchar(255) NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	$table_name = $wpdb->prefix . 'services_social_media_management';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		client_id varchar(255) NOT NULL,
		service_unique_name varchar(255) NOT NULL,
		project_start_date date NOT NULL,
		project_completion_date date NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	$table_name = $wpdb->prefix . 'services_seo_related';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		client_id varchar(255) NOT NULL,
		service_unique_name varchar(255) NOT NULL,
		startdate date NOT NULL,
		enddate date NOT NULL,
		websiteurl varchar(255) NOT NULL,
		websitelogin varchar(255) NOT NULL,
		websitepassword varchar(255) NOT NULL,
		monthlyamount varchar(255) NOT NULL,
		gbplinkandaccess1 TEXT NOT NULL,
		gbplinkandaccess2 TEXT NOT NULL,
		gbplinkandaccess3 TEXT NOT NULL,
		gbplinkandaccess4 TEXT NOT NULL,
		gbplinkandaccess5 TEXT NOT NULL,
		registrarurl varchar(255) NOT NULL,
		registrarusername varchar(255) NOT NULL,
		registrarpassword varchar(255) NOT NULL,
		googleanalyticsaccess varchar(255) NOT NULL,
		googleanalyticsusername varchar(255) NOT NULL,
		googleanalyticspassword varchar(255) NOT NULL,
		searchconsoleaccess varchar(255) NOT NULL,
		searchconsoleusername varchar(255) NOT NULL,
		searchconsolepassword varchar(255) NOT NULL,
		hosturl varchar(255) NOT NULL,
		hostusername varchar(255) NOT NULL,
		hostpassword varchar(255) NOT NULL,
		bldsubmitted varchar(255) NOT NULL,
		bldcsvurl1 varchar(255) NOT NULL,
		bldcsvurl2 varchar(255) NOT NULL,
		bldcsvurl3 varchar(255) NOT NULL,
		bldcsvurl4 varchar(255) NOT NULL,
		bldcsvurl5 varchar(255) NOT NULL,
		periodcomplete varchar(255) NOT NULL,
		servicesdescription longtext NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	$table_name = $wpdb->prefix . 'services_paid_ads_related';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		client_id varchar(255) NOT NULL,
		service_unique_name varchar(255) NOT NULL,
		project_start_date date NOT NULL,
		project_completion_date date NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	$table_name = $wpdb->prefix . 'services_custom_development';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		client_id varchar(255) NOT NULL,
		service_unique_name varchar(255) NOT NULL,
		project_start_date date NOT NULL,
		project_completion_date date NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	$table_name = $wpdb->prefix . 'services_misc';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		client_id varchar(255) NOT NULL,
		service_unique_name varchar(255) NOT NULL,
		project_start_date date NOT NULL,
		project_completion_date date NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	$table_name = $wpdb->prefix . 'support_tickets';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
	    id mediumint(9) NOT NULL AUTO_INCREMENT,
	    creationdatetime TIMESTAMP NULL DEFAULT NULL,
	    startdatetime TIMESTAMP NULL DEFAULT NULL,
	    enddatetime TIMESTAMP NULL DEFAULT NULL,
	    status varchar(50) NOT NULL,
	    client_id int(11) NOT NULL,
	    websiteurl varchar(255) NOT NULL,
	    nocharge varchar(255) NOT NULL,
	    submitteremail varchar(255) NOT NULL,
	    submitterphone varchar(255) NOT NULL,
	    initialdescription text NOT NULL,
	    notes text NULL,
	    PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );


	$table_name = $wpdb->prefix . 'activity_tracker';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
	    id mediumint(9) NOT NULL AUTO_INCREMENT,
	    client_id varchar(255) NOT NULL,
	    activity_category varchar(255) NOT NULL,
	    activity_performed_by varchar(255) NOT NULL,
	    activity_start_daytime_timestamp DATETIME NOT NULL,
	    activity_end_daytime_timestamp DATETIME NOT NULL,
	    hide_from_frontend TINYINT(1) NOT NULL DEFAULT 0,
	    description longtext NOT NULL,
	    metadata longtext NOT NULL,
	    PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}

// Enqueue admin scripts
add_action( 'admin_enqueue_scripts', 'cm_enqueue_admin_scripts' );

function cm_enqueue_admin_scripts( $hook ) {
	if ( $hook !== 'toplevel_page_client_manager' ) {
		return;
	}

	// Enqueue the JavaScript file
    wp_enqueue_script( 'cm-admin-js', CM_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), '1.1', true );
    wp_localize_script('cm-admin-js', 'customAjax', [
	    'ajax_url' => admin_url('admin-ajax.php'),
	    'nonce'    => wp_create_nonce('global_ajax_nonce') // Use one global nonce
	]);

	// Enqueue CSS file
	wp_enqueue_style( 'cm-admin-css', CM_PLUGIN_URL . 'assets/css/admin.css', array(), '1.0', 'all' );
}
add_action( 'admin_enqueue_scripts', 'cm_enqueue_admin_scripts' );

// Register the shortcode
function register_client_insights_shortcode() {
    add_shortcode('output_insights_dashboard', 'client_insights_shortcode_function');
}
add_action('init', 'register_client_insights_shortcode');

// The function that handles the shortcode
function client_insights_shortcode_function($atts) {
    $atts = shortcode_atts(
        array(
            
        ),
        $atts,
        'output_insights_dashboard'
    );

    
    
    // Define the path to the HTML file
    $file_path = plugin_dir_path(__FILE__) . 'frontend/insights-output.php';

    if (file_exists($file_path)) {
        ob_start();
        include $file_path;
        $output = ob_get_clean();
        return $output;
    } else {
        return 'The specified file does not exist.';
    }
}





