<?php 

require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

global $wpdb;
$result = $wpdb->get_results(
    "
        SELECT *
        FROM `wp_options`
        WHERE `option_name` = 'blocks_settings'
    "
);

// foreach($result as $row) {
    //print_r(json_encode($result));
    // echo json_encode($result);

    // echo json_encode($result[0]);

    // echo json_encode($result[0]->option_value);
    $serial_value = json_encode($result[0]->option_value);
    // $unserial_value = unserialize($ser_arr[0]);
    echo $serial_value;
    // echo $json_to_array->option_value;

// }

?>