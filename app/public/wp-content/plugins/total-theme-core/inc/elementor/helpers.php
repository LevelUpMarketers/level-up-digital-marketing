<?php

namespace TotalThemeCore\Elementor;

\defined( 'ABSPATH' ) || exit;

/**
 * Elementor helper functions.
 */
class Helpers {

    /**
	 * Static-only class.
	 */
	private function __construct() {}

    /**
     * Check if currently editing the page using Elementor.
     */
    public static function is_edit_mode(): bool {
        if ( ! \class_exists( '\Elementor\Plugin' ) ) {
            return false;
        }

        // This method is not always reliable for some reason.
        if ( \is_object( \Elementor\Plugin::$instance->preview )
            && \is_callable( [ \Elementor\Plugin::$instance->preview, 'is_preview_mode' ] )
            && \Elementor\Plugin::$instance->preview->is_preview_mode()
        ) {
            return true;
        }

        // Additional check incase the previous fails.
        if ( \is_admin() && isset( $_POST['action'] ) && 'elementor_ajax' === $_POST['action'] ) {
            return true;
        }

        return false;
    }

    /**
     * Check if currently editing a specific post type with Elementor.
     */
    public static function is_cpt_in_frontend_mode( string $post_type = '' ): bool {
       return ( did_action( 'elementor/loaded' )
            && isset( $_GET['elementor-preview'] )
            && isset( $_GET[ $post_type ] )
        );
    }

}
