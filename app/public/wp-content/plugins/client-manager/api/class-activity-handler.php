<?php
// File: client-manager/api/class-activity-handler.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LevelUp_Activity_Handler {

    /**
     * Process the incoming event.
     *
     * @param array  $data         The JSON-decoded request data.
     * @param object $client_data  The client data retrieved during authentication from the client_reporting_info table.
     * @return array|WP_Error      Response array or error.
     */
    public static function process_event( $data, $client_data ) {
        // If no source is provided, assume admin.
        if ( empty( $data['source'] ) ) {
            $data['source'] = 'admin';
        }
        // For any event that is not entered manually, override performed_by with "remote".
        if ( $data['source'] !== 'admin' ) {
            $data['performed_by'] = 'remote';
        }
        
        // Ensure an event type is provided.
        if ( empty( $data['event'] ) ) {
            return new WP_Error( 'missing_event', esc_html__( 'Missing event type.', 'client-manager' ), array( 'status' => 400 ) );
        }
        
        // Route the event to the appropriate handler.
        switch ( $data['event'] ) {
            case 'new_page_creation':
                return self::handle_new_page_creation( $data, $client_data );
            case 'page_update':
                return self::handle_page_update( $data, $client_data );
            case 'new_blog_post':
                return self::handle_new_blog_post( $data, $client_data );
            case 'blog_post_update':
                return self::handle_blog_post_update( $data, $client_data );
            case 'plugin_update':
                return self::handle_plugin_update( $data, $client_data );
            case 'plugin_installation_removal':
                return self::handle_plugin_installation_removal( $data, $client_data );
            case 'plugin_activation':
                return self::handle_plugin_activation( $data, $client_data );
            case 'plugin_deactivation':
                return self::handle_plugin_deactivation( $data, $client_data );
            case 'theme_update':
                return self::handle_theme_update( $data, $client_data );
            case 'wp_core_update':
                return self::handle_wp_core_update( $data, $client_data );
            case 'user_login':
                return self::handle_user_login( $data, $client_data );
            case 'failed_login_attempt':
                return self::handle_failed_login_attempt( $data, $client_data );
            case 'menu_widget_change':
                return self::handle_menu_widget_change( $data, $client_data );
            case 'db_backup_optimization':
                return self::handle_db_backup_optimization( $data, $client_data );
            case 'cpt_update':
                return self::handle_cpt_update( $data, $client_data );
            case 'cron_job_execution':
                return self::handle_cron_job_execution( $data, $client_data );
            case 'cache_clearance':
                return self::handle_cache_clearance( $data, $client_data );
            case 'user_role_change':
                return self::handle_user_role_change( $data, $client_data );
            default:
                return new WP_Error( 'unknown_event', esc_html__( 'Unknown event type.', 'client-manager' ), array( 'status' => 400 ) );
        }
    }

    /**
     * Save the activity record in the database.
     *
     * @param string $client_id                  The client ID.
     * @param string $activity_category          The type of activity.
     * @param string $activity_performed_by      Who performed the activity.
     * @param string $start_timestamp            Start timestamp (YYYY-MM-DD HH:MM:SS).
     * @param string $end_timestamp              End timestamp (YYYY-MM-DD HH:MM:SS).
     * @param int    $hide_from_frontend         Whether to hide the activity (0 or 1).
     * @param string $description                A description of the activity.
     * @param string $metadata                   Additional metadata as a JSON-encoded string.
     * @return true|WP_Error                   True on success, WP_Error on failure.
     */
    private static function save_activity( $client_id, $activity_category, $activity_performed_by, $start_timestamp, $end_timestamp, $hide_from_frontend, $description, $metadata = '' ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'activity_tracker';

        $data = array(
            'client_id'                        => sanitize_text_field( $client_id ),
            'activity_category'                => sanitize_text_field( $activity_category ),
            'activity_performed_by'            => sanitize_text_field( $activity_performed_by ),
            'activity_start_daytime_timestamp' => sanitize_text_field( $start_timestamp ),
            'activity_end_daytime_timestamp'   => sanitize_text_field( $end_timestamp ),
            'hide_from_frontend'               => intval( $hide_from_frontend ),
            'description'                      => sanitize_textarea_field( $description ),
            'metadata'                         => sanitize_textarea_field( $metadata )
        );

        $format = array( '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s' );

        $result = $wpdb->insert( $table_name, $data, $format );
        if ( false === $result ) {
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( 'Activity insert error: ' . $wpdb->last_error );
            }
            return new WP_Error( 'db_error', esc_html__( 'Failed to save activity.', 'client-manager' ) );
        }
        return true;
    }

    // 1. New Page Creation
    private static function handle_new_page_creation( $data, $client_data ) {
        $performed_by   = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $page_title     = ! empty( $data['page_title'] ) ? $data['page_title'] : 'Untitled Page';
        $page_url       = ! empty( $data['page_url'] ) ? $data['page_url'] : 'unknown';
        $creation_time  = ! empty( $data['creation_time'] ) ? $data['creation_time'] : current_time( 'Y-m-d H:i:s' );
        $description    = "New page created: {$page_title} ({$page_url}).";
        $metadata       = json_encode( $data );
        $save_result    = self::save_activity( $client_data->client_id, 'new_page_creation', $performed_by, $creation_time, $creation_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'New page creation event processed and saved.', 'client-manager' ) );
    }

    // 2. Page Update
    private static function handle_page_update( $data, $client_data ) {
        $performed_by   = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $page_title     = ! empty( $data['page_title'] ) ? $data['page_title'] : 'Untitled Page';
        $page_url       = ! empty( $data['page_url'] ) ? $data['page_url'] : 'unknown';
        $update_time    = ! empty( $data['update_time'] ) ? $data['update_time'] : current_time( 'Y-m-d H:i:s' );
        $details        = ! empty( $data['details'] ) ? $data['details'] : '';
        $description    = "Page updated: {$page_title} ({$page_url}). Changes: {$details}";
        $metadata       = json_encode( $data );
        $save_result    = self::save_activity( $client_data->client_id, 'page_update', $performed_by, $update_time, $update_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'Page update event processed and saved.', 'client-manager' ) );
    }

    // 3. New Blog Post Creation
    private static function handle_new_blog_post( $data, $client_data ) {
        $performed_by   = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $post_title     = ! empty( $data['post_title'] ) ? $data['post_title'] : 'Untitled Post';
        $post_url       = ! empty( $data['post_url'] ) ? $data['post_url'] : 'unknown';
        $creation_time  = ! empty( $data['creation_time'] ) ? $data['creation_time'] : current_time( 'Y-m-d H:i:s' );
        $description    = "New blog post created: {$post_title} ({$post_url}).";
        $metadata       = json_encode( $data );
        $save_result    = self::save_activity( $client_data->client_id, 'new_blog_post', $performed_by, $creation_time, $creation_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'New blog post event processed and saved.', 'client-manager' ) );
    }

    // 4. Blog Post Update
    private static function handle_blog_post_update( $data, $client_data ) {
        $performed_by   = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $post_title     = ! empty( $data['post_title'] ) ? $data['post_title'] : 'Untitled Post';
        $post_url       = ! empty( $data['post_url'] ) ? $data['post_url'] : 'unknown';
        $update_time    = ! empty( $data['update_time'] ) ? $data['update_time'] : current_time( 'Y-m-d H:i:s' );
        $details        = ! empty( $data['details'] ) ? $data['details'] : '';
        $description    = "Blog post updated: {$post_title} ({$post_url}). Changes: {$details}";
        $metadata       = json_encode( $data );
        $save_result    = self::save_activity( $client_data->client_id, 'blog_post_update', $performed_by, $update_time, $update_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'Blog post update event processed and saved.', 'client-manager' ) );
    }

    // 5. Plugin Update
    private static function handle_plugin_update( $data, $client_data ) {
        $performed_by   = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $plugin_name    = ! empty( $data['plugin_name'] ) ? $data['plugin_name'] : 'Unknown Plugin';
        $prev_version   = ! empty( $data['previous_version'] ) ? $data['previous_version'] : 'unknown';
        $new_version    = ! empty( $data['new_version'] ) ? $data['new_version'] : 'unknown';
        $update_time    = ! empty( $data['update_time'] ) ? $data['update_time'] : current_time( 'Y-m-d H:i:s' );
        $description    = "Plugin updated: {$plugin_name} from version {$prev_version} to {$new_version}.";
        $metadata       = json_encode( $data );
        $save_result    = self::save_activity( $client_data->client_id, 'plugin_update', $performed_by, $update_time, $update_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'Plugin update event processed and saved.', 'client-manager' ) );
    }

    // 6. Plugin Installation/Removal
    private static function handle_plugin_installation_removal( $data, $client_data ) {
        $performed_by   = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $plugin_name    = ! empty( $data['plugin_name'] ) ? $data['plugin_name'] : 'Unknown Plugin';
        $action         = ! empty( $data['action'] ) ? $data['action'] : 'installation';
        $version        = ! empty( $data['version'] ) ? $data['version'] : 'unknown';
        $time           = ! empty( $data['time'] ) ? $data['time'] : current_time( 'Y-m-d H:i:s' );
        $description    = "Plugin {$action}: {$plugin_name} (version: {$version}).";
        $metadata       = json_encode( $data );
        $save_result    = self::save_activity( $client_data->client_id, 'plugin_installation_removal', $performed_by, $time, $time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'Plugin installation/removal event processed and saved.', 'client-manager' ) );
    }

    // 7. Plugin Activation
    private static function handle_plugin_activation( $data, $client_data ) {
        $performed_by   = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $plugin         = ! empty( $data['plugin'] ) ? $data['plugin'] : 'unknown';
        $activation_time = ! empty( $data['activation_time'] ) ? $data['activation_time'] : current_time( 'Y-m-d H:i:s' );
        $description    = "Plugin activated: {$plugin} at {$activation_time}.";
        $metadata       = json_encode( $data );
        $save_result    = self::save_activity( $client_data->client_id, 'plugin_activation', $performed_by, $activation_time, $activation_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'Plugin activation event processed and saved.', 'client-manager' ) );
    }

    // 8. Plugin Deactivation
    private static function handle_plugin_deactivation( $data, $client_data ) {
        $performed_by    = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $plugin          = ! empty( $data['plugin'] ) ? $data['plugin'] : 'unknown';
        $deactivation_time = ! empty( $data['deactivation_time'] ) ? $data['deactivation_time'] : current_time( 'Y-m-d H:i:s' );
        $description     = "Plugin deactivated: {$plugin} at {$deactivation_time}.";
        $metadata        = json_encode( $data );
        $save_result     = self::save_activity( $client_data->client_id, 'plugin_deactivation', $performed_by, $deactivation_time, $deactivation_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'Plugin deactivation event processed and saved.', 'client-manager' ) );
    }

    // 9. Theme Update
    private static function handle_theme_update( $data, $client_data ) {
        $performed_by   = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $theme_name     = ! empty( $data['theme_name'] ) ? $data['theme_name'] : 'Unknown Theme';
        $prev_version   = ! empty( $data['previous_version'] ) ? $data['previous_version'] : 'unknown';
        $new_version    = ! empty( $data['new_version'] ) ? $data['new_version'] : 'unknown';
        $update_time    = ! empty( $data['update_time'] ) ? $data['update_time'] : current_time( 'Y-m-d H:i:s' );
        $description    = "Theme updated: {$theme_name} from version {$prev_version} to {$new_version}.";
        $metadata       = json_encode( $data );
        $save_result    = self::save_activity( $client_data->client_id, 'theme_update', $performed_by, $update_time, $update_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'Theme update event processed and saved.', 'client-manager' ) );
    }

    // 10. WordPress Core Update
    private static function handle_wp_core_update( $data, $client_data ) {
        $performed_by   = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $prev_version   = ! empty( $data['previous_version'] ) ? $data['previous_version'] : 'unknown';
        $new_version    = ! empty( $data['new_version'] ) ? $data['new_version'] : 'unknown';
        $update_time    = ! empty( $data['update_time'] ) ? $data['update_time'] : current_time( 'Y-m-d H:i:s' );
        $description    = "WordPress core updated from version {$prev_version} to {$new_version}.";
        $metadata       = json_encode( $data );
        $save_result    = self::save_activity( $client_data->client_id, 'wp_core_update', $performed_by, $update_time, $update_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'WordPress core update event processed and saved.', 'client-manager' ) );
    }

    // 11. User Login
    private static function handle_user_login( $data, $client_data ) {
        $performed_by   = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $user           = ! empty( $data['user'] ) ? $data['user'] : 'unknown';
        $login_time     = ! empty( $data['login_time'] ) ? $data['login_time'] : current_time( 'Y-m-d H:i:s' );
        $ip_address     = ! empty( $data['ip_address'] ) ? $data['ip_address'] : 'unknown';
        $description    = "User login: {$user} at {$login_time} from IP {$ip_address}.";
        $metadata       = json_encode( $data );
        $save_result    = self::save_activity( $client_data->client_id, 'user_login', $performed_by, $login_time, $login_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'User login event processed and saved.', 'client-manager' ) );
    }

    // 12. Failed Login Attempt
    private static function handle_failed_login_attempt( $data, $client_data ) {
        $performed_by    = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $attempted_user  = ! empty( $data['attempted_user'] ) ? $data['attempted_user'] : 'unknown';
        $attempt_time    = ! empty( $data['attempt_time'] ) ? $data['attempt_time'] : current_time( 'Y-m-d H:i:s' );
        $ip_address      = ! empty( $data['ip_address'] ) ? $data['ip_address'] : 'unknown';
        $reason          = ! empty( $data['reason'] ) ? $data['reason'] : '';
        $description     = "Failed login attempt for {$attempted_user} at {$attempt_time} from IP {$ip_address}. Reason: {$reason}";
        $metadata        = json_encode( $data );
        $save_result     = self::save_activity( $client_data->client_id, 'failed_login_attempt', $performed_by, $attempt_time, $attempt_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'Failed login attempt event processed and saved.', 'client-manager' ) );
    }

    // 13. Menu/Widget Change
    private static function handle_menu_widget_change( $data, $client_data ) {
        $performed_by = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $change_type  = ! empty( $data['change_type'] ) ? $data['change_type'] : 'unknown';
        $details      = ! empty( $data['details'] ) ? $data['details'] : '';
        $time         = ! empty( $data['time'] ) ? $data['time'] : current_time( 'Y-m-d H:i:s' );
        $description  = "Menu/Widget change ({$change_type}) at {$time}. Details: {$details}";
        $metadata     = json_encode( $data );
        $save_result  = self::save_activity( $client_data->client_id, 'menu_widget_change', $performed_by, $time, $time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'Menu/widget change event processed and saved.', 'client-manager' ) );
    }

    // 14. Database Backup/Optimization
    private static function handle_db_backup_optimization( $data, $client_data ) {
        $performed_by = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $action       = ! empty( $data['action'] ) ? $data['action'] : 'backup';
        $time         = ! empty( $data['time'] ) ? $data['time'] : current_time( 'Y-m-d H:i:s' );
        $details      = ! empty( $data['details'] ) ? $data['details'] : '';
        $description  = "Database {$action} performed at {$time}. Details: {$details}";
        $metadata     = json_encode( $data );
        $save_result  = self::save_activity( $client_data->client_id, 'db_backup_optimization', $performed_by, $time, $time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'Database backup/optimization event processed and saved.', 'client-manager' ) );
    }

    // 15. Custom Post Type Update
    private static function handle_cpt_update( $data, $client_data ) {
        $performed_by = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $cpt_type     = ! empty( $data['cpt_type'] ) ? $data['cpt_type'] : 'custom';
        $title        = ! empty( $data['title'] ) ? $data['title'] : 'Untitled';
        $url          = ! empty( $data['url'] ) ? $data['url'] : 'unknown';
        $update_time  = ! empty( $data['update_time'] ) ? $data['update_time'] : current_time( 'Y-m-d H:i:s' );
        $details      = ! empty( $data['details'] ) ? $data['details'] : '';
        $description  = "Custom Post Type update: {$cpt_type} '{$title}' updated at {$update_time}. URL: {$url}. Details: {$details}";
        $metadata     = json_encode( $data );
        $save_result  = self::save_activity( $client_data->client_id, 'cpt_update', $performed_by, $update_time, $update_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'Custom post type update event processed and saved.', 'client-manager' ) );
    }

    // 16. Cron Job Execution
    private static function handle_cron_job_execution( $data, $client_data ) {
        $performed_by   = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $job_name       = ! empty( $data['job_name'] ) ? $data['job_name'] : 'Unnamed Job';
        $execution_time = ! empty( $data['execution_time'] ) ? $data['execution_time'] : current_time( 'Y-m-d H:i:s' );
        $details        = ! empty( $data['details'] ) ? $data['details'] : '';
        $description    = "Cron job executed: {$job_name} at {$execution_time}. Details: {$details}";
        $metadata       = json_encode( $data );
        $save_result    = self::save_activity( $client_data->client_id, 'cron_job_execution', $performed_by, $execution_time, $execution_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'Cron job execution event processed and saved.', 'client-manager' ) );
    }

    // 17. Cache Clearance
    private static function handle_cache_clearance( $data, $client_data ) {
        $performed_by   = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $cache_type     = ! empty( $data['cache_type'] ) ? $data['cache_type'] : 'general';
        $clearance_time = ! empty( $data['clearance_time'] ) ? $data['clearance_time'] : current_time( 'Y-m-d H:i:s' );
        $details        = ! empty( $data['details'] ) ? $data['details'] : '';
        $description    = "Cache clearance: {$cache_type} cache cleared at {$clearance_time}. Details: {$details}";
        $metadata       = json_encode( $data );
        $save_result    = self::save_activity( $client_data->client_id, 'cache_clearance', $performed_by, $clearance_time, $clearance_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'Cache clearance event processed and saved.', 'client-manager' ) );
    }

    // 18. User Role Change
    private static function handle_user_role_change( $data, $client_data ) {
        $performed_by = ! empty( $data['performed_by'] ) ? $data['performed_by'] : 'unknown';
        $user         = ! empty( $data['user'] ) ? $data['user'] : 'unknown';
        $prev_role    = ! empty( $data['previous_role'] ) ? $data['previous_role'] : 'unknown';
        $new_role     = ! empty( $data['new_role'] ) ? $data['new_role'] : 'unknown';
        $change_time  = ! empty( $data['change_time'] ) ? $data['change_time'] : current_time( 'Y-m-d H:i:s' );
        $description  = "User role changed for {$user} from {$prev_role} to {$new_role} at {$change_time}.";
        $metadata     = json_encode( $data );
        $save_result  = self::save_activity( $client_data->client_id, 'user_role_change', $performed_by, $change_time, $change_time, 0, $description, $metadata );
        if ( is_wp_error( $save_result ) ) {
            return $save_result;
        }
        return array( 'success' => true, 'message' => esc_html__( 'User role change event processed and saved.', 'client-manager' ) );
    }
}
