<?php 

require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

global $wpdb;
// $result = $wpdb->get_results(
//     "
//         SELECT *
//         FROM `wp_options`
//         WHERE `option_name` = 'blocks_settings_main'
//     "
// );

$result = get_site_option( 'blocks_settings_main-sites' );
// print_r( $result );
// print_r( json_encode( $result ) );
echo json_encode( $result );

    // $serial_value = json_encode($result[0]->option_value);
    // echo $serial_value;

?>