<?php 

    add_action('rest_api_init', 'multisite_settings_register_api_routes');

    function multisite_settings_get_main_blocks() {

        echo '..................... working this far.';

        $networkID = get_current_network_id();
        $result = get_network_option( $networkID, 'blocks-settings-main-sites', 'not_available' );
        return rest_ensure_response( array(
            'data' => $result
        ) );
    }

    function multisite_settings_register_api_routes() {

        register_rest_route( 'hide_blocks_plugin/v1', '/main-blocks', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => 'multisite_settings_get_main_blocks',
            'permission_callback' => function() {
                return current_user_can( 'manage_network_options' );
            }
        ) );
    }

?>