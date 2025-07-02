<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'admin_menu', 'cm_admin_menu' );

function cm_admin_menu() {
	add_menu_page(
		__( 'Client Manager', 'client-manager' ),
		__( 'Client Manager', 'client-manager' ),
		'manage_options',
		'client_manager',
		'cm_render_admin_page',
		'dashicons-admin-users',
		6
	);
}

function cm_render_admin_page() {
	?>
	<div class="wrap">
		<h1><?php _e( 'Client Manager', 'client-manager' ); ?></h1>
		<h2 class="nav-tab-wrapper">
			<a href="?page=client_manager&tab=add_new_client" class="nav-tab"><?php _e( 'Add a New Client', 'client-manager' ); ?></a>
			<a href="?page=client_manager&tab=edit_current_clients" class="nav-tab"><?php _e( 'Edit Current Clients', 'client-manager' ); ?></a>
			<a href="?page=client_manager&tab=services" class="nav-tab"><?php _e( 'Create a Service', 'client-manager' ); ?></a>
			<a href="?page=client_manager&tab=editservices" class="nav-tab"><?php _e( 'Edit Services', 'client-manager' ); ?></a>
			<a href="?page=client_manager&tab=support_tickets" class="nav-tab"><?php _e( 'Support Tickets', 'client-manager' ); ?></a>
			<a href="?page=client_manager&tab=activity_tracker" class="nav-tab"><?php _e( 'Activity Tracker', 'client-manager' ); ?></a>
			<a href="?page=client_manager&tab=reporting_analytics" class="nav-tab"><?php _e( 'Reporting & Analytics', 'client-manager' ); ?></a>
		</h2>
		<?php
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'add_new_client';
		switch ( $tab ) {
			case 'add_new_client':
				require_once CM_PLUGIN_DIR . 'admin/tabs/add-new-client.php';
				cm_render_add_new_client_tab();
				break;
			case 'edit_current_clients':
				require_once CM_PLUGIN_DIR . 'admin/tabs/edit-current-clients.php';
				cm_render_edit_current_clients_tab();
				break;
			case 'services':
				require_once CM_PLUGIN_DIR . 'admin/tabs/services.php';
				cm_render_projects_tab();
				break;
			case 'editservices':
				require_once CM_PLUGIN_DIR . 'admin/tabs/editservices.php';
				cm_render_projects_tab();
				break;
			case 'support_tickets':
				require_once CM_PLUGIN_DIR . 'admin/tabs/support-tickets.php';
				cm_render_support_tickets_tab();
				break;
			case 'activity_tracker':
				require_once CM_PLUGIN_DIR . 'admin/tabs/activity-tracker.php';
				cm_render_activity_tracker_tab();
				break;
			case 'reporting_analytics':
				require_once CM_PLUGIN_DIR . 'admin/tabs/reporting-analytics.php';
				cm_render_reporting_analytics_tab();
				break;
			default:
				require_once CM_PLUGIN_DIR . 'admin/tabs/add-new-client.php';
				cm_render_add_new_client_tab();
				break;
		}
		?>
	</div>
	<?php
}
