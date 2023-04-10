<?php 

require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

$networkID = get_current_network_id();
global $wpdb;
// $result = get_site_option( 'blocks-settings-main-sites' );
$result = get_network_option( $networkID, 'blocks-settings-main-sites', 'not_available' );

$data = json_encode( $result );

return rest_ensure_response( $data );

?>