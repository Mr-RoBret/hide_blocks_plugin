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

$result = get_option('blocks_settings_main');
echo json_encode($result);

    // $serial_value = json_encode($result[0]->option_value);
    // echo $serial_value;

?>