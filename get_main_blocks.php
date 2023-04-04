<?php 

require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

global $wpdb;
// $result = $wpdb->get_results(
//     "
//         SELECT *
//         FROM `wp_sitemeta`
//         WHERE `option_name` = 'blocks-settings-main-sites'
//     "
// );

$result = get_site_option( 'blocks-settings-main-sites' );
echo json_encode( $result );

    // $serial_value = json_encode($result[0]->option_value);
    // echo $serial_value;

?>