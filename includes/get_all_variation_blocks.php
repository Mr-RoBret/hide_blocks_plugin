<?php 
    $whitelisted_variations = 'variation_checkbox_options';

    function multisite_get_all_variations() {

        // Retrieve the result from the JavaScript
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $data['result'];
        
        // Perform any processing with the result
        // ...
        
        // Return a response back to JavaScript
        $response = 'Processed result: ' . $result;
        echo $response;
        $all_variations = $response;
        
        //return both 'all' and 'allowed' arrays
        return rest_ensure_response( 
            [ 
            'all_variations' => $all_variations,
            ] );
    }

    // function permissions_callback() {
    //     if ( ! current_user_can( 'manage_network_options' ) ) {
    //         return new WP_Error( 'rest_forbidden ', 
    //         esc_html__( 'OMG you cannot view private data.', 
    //         'multisite-settings' ), 
    //         array( 'status' => 401 ) );
    //     }
    //     return true;
    // }

    function multisite_settings_register_api_route() {

        register_rest_route( 'blocks-settings-main/v1', '/variation-blocks', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => 'multisite_get_all_variations',
            // 'permission_callback' => 'permissions_callback'
        ) );

    }

    add_action('rest_api_init', 'multisite_settings_register_api_route');