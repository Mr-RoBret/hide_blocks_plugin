<?php 
    $whitelisted_variations = 'variation_checkbox_options';

    function multisite_settings_get_variations() {

        $allowed_variations = get_site_option($GLOBALS [ 'whitelisted_variations' ]);
        
        //return allowed array
        return rest_ensure_response( 
            [ 
            'allowed_variations' => $allowed_variations
            ] 
        );
    }

    function get_permissions_callback() {
        if ( ! current_user_can( 'manage_network_options' ) ) {
            return new WP_Error( 'rest_forbidden ', 
            esc_html__( 'OMG you cannot view private data.', 
            'multisite-settings' ), 
            array( 'status' => 401 ) );
        }
        return true;
    }

    function multisite_settings_register_api_routes() {

        register_rest_route( 'blocks-settings-main/v1', '/main-blocks', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => 'multisite_settings_get_variations',
            // 'permission_callback' => 'get_permissions_callback'
        ) );

    }

    add_action('rest_api_init', 'multisite_settings_register_api_routes');