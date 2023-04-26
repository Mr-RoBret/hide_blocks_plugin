<?php 

    function multisite_settings_get_main_blocks() {
        // $networkID = get_current_network_id();
        // $registered_blocks_arr = [];

        $test_regex = "/[a-z]+\/[a-z]+-?[a-z]+$/";
        $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
        $block_names = array();

        foreach( $block_types as $key ) {
            $block_names[] = $key->name;
        }

        $block_names_verified = array();

        foreach( $block_names as $name ) {
            $success = preg_match( $test_regex, $name, $match );
            if( $success ) {
                // preg_match( $prefix_regex, $name, $matches );
                $result = get_site_option( 'block_checkbox_' . $name, 'not_available' );
                array_push( $block_names_verified, $result ); // echo '<p>' . $matches[0] . ', </p></br>';
            }
        }
        
        //return array
        return rest_ensure_response( [ 'data' => $block_names_verified ] );

    }

    function get_permissions_callback() {
        if ( ! current_user_can( 'manage_options' )){
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
            'callback' => 'multisite_settings_get_main_blocks',
            'permission_callback' => 'get_permissions_callback'
        ) );
    }

    add_action('rest_api_init', 'multisite_settings_register_api_routes');