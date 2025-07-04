<?php

defined( 'ABSPATH' ) || exit;

?>

<div class="wrap totaltheme-demo-importer-no-demos">
	<h1><?php esc_html_e( 'Demo Importer', 'total-theme-core' ); ?></h1>
	<div class="totaltheme-demo-importer-warning"><p><?php
		if ( ! empty( $this->get_demos_list_error ) ) {
			echo esc_html( $this->get_demos_list_error );
		} else {
			esc_html_e( 'We could not locate any demos. Most likely your server is blocking outgoing connections so the demos can not be accessed from your site. Please double check your server settings with your web host. If outgoing connections are enabled but you are still getting this error please wait a few minutes and refresh the page to try again.', 'total-theme-core' );
		}
	?></p></div>
</div>
