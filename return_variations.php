<?php 

// require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

add_action('rest_api_init', 'multisite_settings_register_api_routes');

function multisite_settings_hide_variation_blocks() {

    $networkID = get_current_network_id();
    $result = get_network_option( $networkID, 'blocks-settings-variation-sites', 'not_available' );
    return rest_ensure_response( array(
        'data' => $result
    ) );
}

function multisite_settings_register_api_routes() {

    register_rest_route( 'blocks-settings-main/v1', '/variation-blocks', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'multisite_settings_hide_variation_blocks',
        'permission_callback' => function() {
            return current_user_can( 'manage_network_options' );
        }
    ) );
}
?>