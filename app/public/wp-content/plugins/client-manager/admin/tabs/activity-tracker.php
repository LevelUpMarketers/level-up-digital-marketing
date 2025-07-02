<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function cm_render_activity_tracker_tab() {
	?>
	<h2><?php _e( 'Activity Tracker', 'client-manager' ); ?></h2>
	<p><?php _e( 'This section will allow you to track activities.', 'client-manager' ); ?></p>
	<?php
}
