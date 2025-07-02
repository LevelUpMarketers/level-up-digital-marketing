<?php

use TotalTheme\Demo_Importer\Helpers;

defined( 'ABSPATH' ) || exit;

$available_plugins = [];

if ( ! empty( $this->demos ) && is_array( $this->demos ) ) {
	$plugins = array_column( $this->demos, 'plugins' );
	if ( $plugins ) {
		$available_plugins = array_unique( call_user_func_array( 'array_merge', $plugins ) );
	}
}

?>

<div class="totaltheme-demo-importer wrap">

	<?php
	// Max execution warning.
	$max_execute = ini_get( 'max_execution_time' );
	if ( $max_execute > 0 && $max_execute < 300 ) { ?>
		<div class="totaltheme-demo-importer-error">
			<p><?php echo wp_kses_post( sprintf( __( '<strong>Important:</strong> Your server\'s max_execution_time is set to %d but some demos may require more time to import, especially on shared hosting plans. We highly recommend increasing your server\'s max_execution_time value to at least 300. This can be done via your cPanel or by contacting your hosting company.', 'total-theme-core' ), $max_execute ) ); ?></p>
		</div>
	<?php } ?>

	<?php
	// Old data removal.
	if ( current_user_can( 'delete_posts' ) ) { ?>
		<div class="totaltheme-demo-importer-delete-old-data-notice totaltheme-demo-importer-error<?php echo Helpers::get_imported_data_list() ? '' : ' hidden'; ?>">
			<h2><?php esc_html_e( 'Previously Imported Data Found', 'total-theme-core' ); ?></h2>
			<p><?php esc_html_e( 'It looks like demo content was previously imported, did you want to remove the old demo data?', 'total-theme-core' ); ?></p>
			<p><?php echo wp_kses_post( __( '<strong class="totaltheme-red">Important:</strong> All previously imported data including images, posts, categories, widgets, terms and pages will be deleted REGARDLESS if they have been modified since the import.', 'total-theme-core' ) ); ?></p>
			<div class="totaltheme-demo-importer-deleting-data hidden"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg><?php esc_html_e( 'Deleting data, please be patient it may take a while!', 'total-theme-core' ); ?></div>
			<div class="totaltheme-demo-importer-deleted-data-results hidden"><?php esc_html_e( 'All done!', 'total-theme-core' ); ?></div>
			<div class="totaltheme-demo-importer-deleted-data-error hidden"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg><?php esc_html_e( 'There was an error. Most likely the server timed out during the process. Please refresh the page and try again to delete remaining data.', 'total-theme-core' ); ?></div>
			<button class="button button-secondary totaltheme-demo-importer-remove-old-data-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'totaltheme_demo_importer_delete_imported_data' ) ); ?>" data-confirm="<?php esc_attr_e( 'Please confirm that you want to delete previously imported data. If this is not a fresh installation it\'s strongly recommended that you first make sure you have a site backup.', 'total-theme-core' ); ?>"><?php esc_html_e( 'Delete Previously Imported Content', 'total-theme-core' ); ?></button>
		</div>
	<?php } ?>

	<div class="totaltheme-demo-importer__top">
		<?php if ( ! empty( $this->categories ) && is_array( $this->categories ) ) : ?>
			<div class="totaltheme-demo-importer-filter">
				<div class="totaltheme-demo-importer-filter__categories">
					<select><?php
						echo '<option value="all">' . esc_html__( 'Filter by Category', 'total-theme-core' ) . '</option>';
						if ( isset( $this->categories[ 'other' ] ) ) {
							$value = $this->categories[ 'other' ];
							unset( $this->categories[ 'other' ] );
							$this->categories[ 'other' ] = $value;
						}
						foreach ( $this->categories as $category_key => $category_value ) {
							echo '<option value="' . esc_attr( $category_key ) . '">' . esc_html( $category_value ) . '</option>';
						}
					?></select>
				</div>
				<?php /* <div class="totaltheme-demo-importer-filter__builders">
					<select>
						<option value="all"><?php esc_html_e( 'Filter by Page Builder', 'total-theme-core' ); ?></option>
						<?php if ( in_array( 'WPBakery Page Builder', $available_plugins ) ) { ?>
							<option value="wpbakery"><?php esc_html_e( 'WPBakery', 'total-theme-core' ); ?></option>
						<?php } ?>
						<?php if ( in_array( 'Elementor', $available_plugins ) ) { ?>
							<option value="elementor"><?php esc_html_e( 'Elementor', 'total-theme-core' ); ?></option>
						<?php } ?>
						<?php if ( in_array( 'Gutenberg', $available_plugins ) ) { ?>
							<option value="gutenberg"><?php esc_html_e( 'Gutenberg', 'total-theme-core' ); ?></option>
						<?php } ?>
						<option value="none"><?php esc_html_e( 'None', 'total-theme-core' ); ?></option>
					</select>
				</div>
				<?php */ ?>
				<input class="totaltheme-demo-importer-filter__search" type="text" placeholder="<?php esc_attr_e( 'Search demos...', 'total-theme-core' ); ?>"></input>
				<button class="totaltheme-demo-importer-filter__clear button button-secondary"><?php esc_attr_e( 'Clear', 'total-theme-core' ); ?></button>
			</div>
		<?php endif; ?>
		<button class="totaltheme-demo-importer-refresh-btn button button-primary" data-nonce="<?php echo esc_attr( wp_create_nonce( 'totaltheme_demo_importer_refresh_list' ) ); ?>"><?php
			esc_attr_e( 'Refresh List', 'total-theme-core' );
		?><span class="dashicons dashicons-update-alt" aria-hidden="true"></span></button>

	</div>

	<div class="totaltheme-demo-importer-refresh-notice"><svg viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg" height="18" width="18"><circle cx="18" cy="18" r="18" fill="#a2a2a2" fill-opacity=".5"/><circle cx="18" cy="8" r="4" fill="#fff"><animateTransform attributeName="transform" dur="1100ms" from="0 18 18" repeatCount="indefinite" to="360 18 18" type="rotate"/></circle></svg><?php esc_html_e( 'Your page will refresh momentarily.', 'total-theme-core' ); ?></div>

	<?php
	$warning_is_dismissed = wp_validate_boolean( get_option( 'totaltheme_demo_importer_warning_dismiss' ), false );
	$warning_dismiss = ! empty( $_GET['totaltheme_demo_importer_warning_dismiss'] ) ? sanitize_text_field( $_GET['totaltheme_demo_importer_warning_dismiss'] ) : '';

	if ( ! $warning_is_dismissed && $warning_dismiss && '1' === $warning_dismiss ) {
		$warning_is_dismissed = update_option( 'totaltheme_demo_importer_warning_dismiss', 1, false );
	}

	if ( ! $warning_is_dismissed ) { ?>
		<div class="totaltheme-demo-importer-warning">
			<h2><?php esc_html_e( 'Important Notice:', 'total-theme-core' ); ?></h2>
			<p><?php echo wp_kses_post( sprintf(
				__( 'For your site to look exactly like the selected demo it should be imported on a clean (blank) installation of WordPress to prevent conflicts with existing content (WordPress GUID\'s). You can use the <a href="%s">Advanced WordPress Reset</a> to reset your site back to it\'s original state. If you are working with an existing live website we do not recommend importing any sample data. Instead you can use the <a href="%s">Demo Inspector Tool</a> or <a href="%s">Patterns</a> to build your pages.', 'total-theme-core' ),
				'https://wordpress.org/plugins/advanced-wp-reset/',
				'https://totalwptheme.com/docs/demo-page-inspector/',
				'https://totalwptheme.com/docs/section-templates/'
				)
			); ?></p>
			<a class="button button-secondary" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'totaltheme_demo_importer_warning_dismiss', '1' ), 'totaltheme_demo_importer_warning_dismiss' ) ); ?>" target="_parent"><?php esc_html_e( 'Dismiss Warning', 'total-theme-core' ); ?></a>
		</div>
	<?php } ?>

	<div class="totaltheme-demo-importer-grid"><?php

		if ( ! empty( $this->demos ) && is_array( $this->demos ) ) {

			foreach ( $this->demos as $demo_key => $demo_data ) {

				$categories = '';

				if ( array_key_exists( 'categories', $demo_data ) && is_array( $demo_data['categories'] ) ) {
					$categories = implode( ',', array_keys( $demo_data['categories'] ) );
				}

				$builder = 'none';
				$plugins = $demo_data['plugins'] ?? [];

				if ( is_array( $plugins ) ) {
					if ( in_array( 'WPBakery Page Builder', $plugins ) ) {
						$builder = 'WPBakery';
					} elseif ( in_array( 'Elementor', $plugins ) ) {
						$builder = 'Elementor';
					} elseif ( in_array( 'Gutenberg', $plugins ) ) {
						$builder = 'Gutenberg';
					}
				}

				?>

				<div class="totaltheme-demo-importer-grid-item" data-demo="<?php echo esc_attr( $demo_key ); ?>" data-categories="<?php echo esc_attr( $categories ); ?>" data-builder="<?php echo sanitize_html_class( strtolower( $builder ) ); ?>" tabindex="0">

					<?php if ( 'none' !== $builder ) { ?>
						<div class="totaltheme-demo-importer-grid-item__builder-tag"><?php echo esc_html( $builder ); ?></div>
					<?php } ?>

					<div class="totaltheme-demo-importer-grid-item__screenshot">
						<?php Helpers::render_demo_screenshot( $demo_key, $demo_data['name'] ); ?>
						<span class="spinner totaltheme-demo-importer-spinner"></span>
					</div>

					<h3 class="totaltheme-demo-importer-grid-item__name">
						<span><?php echo esc_html( $demo_data['name'] ); ?></span>
						<?php
						// Get preview URL
						$demo_preview = '';
						if ( ! empty( $demo_data['demo_url'] ) ) {
							$demo_preview = $demo_data['demo_url'];
						} else {
							$demo_preview = "http://totalwpthemedemo.com/{$demo_key}/";
						} ?>
						<div class="totaltheme-demo-importer-grid-item__actions">
							<a href="<?php echo esc_url( $demo_preview ); ?>" class="totaltheme-demo-importer-grid-item__button button button-primary" target="_blank"><?php esc_html_e( 'Live Preview', 'total-theme-core' ); ?></a>
						</div>
					</h3>

				</div>

			<?php } ?>

		<?php } ?>

	</div>

	<div class="totaltheme-demo-importer-selected-modal">
		<a href="#" class="totaltheme-demo-importer-selected-modal__close"><span class="screen-reader-text"><?php esc_html_e( 'Close selected demo', 'total-theme-core' ); ?></span><span class="dashicons dashicons-no-alt"></span></a>
		<div class="totaltheme-demo-importer-selected-modal__inner">
			<div class="totaltheme-demo-importer-selected-modal__content"></div>
		</div>
	</div>

</div>
