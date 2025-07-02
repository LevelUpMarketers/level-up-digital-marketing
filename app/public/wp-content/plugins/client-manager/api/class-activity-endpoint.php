<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LevelUp_Activity_Endpoint {

    public static function init() {
        add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
    }

    public static function register_routes() {
        register_rest_route( 'levelup/v1', '/activity', array(
            'methods'             => 'POST',
            'callback'            => array( __CLASS__, 'handle_activity' ),
            'permission_callback' => array( __CLASS__, 'check_permissions' ),
        ));
    }

    /**
     * Permission callback with unique API key validation using a custom table.
     *
     * Expects the API key in the "X-LevelUp-API-Key" header.
     *
     * @param WP_REST_Request $request
     * @return bool|WP_Error
     */
    public static function check_permissions( $request ) {
        $api_key = $request->get_header( 'X-LevelUp-API-Key' );
        if ( empty( $api_key ) ) {
            return new WP_Error( 'rest_forbidden', esc_html__( 'Missing API key.', 'client-manager' ), array( 'status' => 401 ) );
        }

        // Validate the API key using the custom table.
        $client_data = self::get_client_by_api_key( $api_key );
        if ( ! $client_data ) {
            return new WP_Error( 'rest_forbidden', esc_html__( 'Invalid API key.', 'client-manager' ), array( 'status' => 401 ) );
        }

        // Optionally, attach the client data (from the custom table) to the request for further use.
        $request->set_param( 'client_data', $client_data );
        return true;
    }

    /**
     * Look up a client record by API key from the custom table.
     *
     * @param string $api_key
     * @return object|false  Returns the row object if found, false otherwise.
     */
    public static function get_client_by_api_key( $api_key ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'client_reporting_info';

        $client_data = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE remotecode = %s LIMIT 1",
                $api_key
            )
        );

        return $client_data ? $client_data : false;
    }

    /**
     * Handle incoming activity data.
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public static function handle_activity( $request ) {
        $data = $request->get_json_params();

        if ( empty( $data ) ) {
            return new WP_Error( 'no_data', esc_html__( 'No data received', 'client-manager' ), array( 'status' => 400 ) );
        }

        // Retrieve the client data attached during authentication.
        $client_data = $request->get_param( 'client_data' );

        // Log the received data for debugging if WP_DEBUG is enabled.
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( print_r( $data, true ) );
        }

        // Include the activity handler file.
        require_once plugin_dir_path( __FILE__ ) . 'class-activity-handler.php';

        // Process the event using the handler.
        $result = LevelUp_Activity_Handler::process_event( $data, $client_data );
        if ( is_wp_error( $result ) ) {
            return $result;
        }

        return rest_ensure_response( $result );
    }
}

// Initialize the endpoint.
LevelUp_Activity_Endpoint::init();
