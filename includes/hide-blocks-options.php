<?php  
// Function for displaying, setting, and updating options,
// which are populated based on all available blocks in database

function blocks_options() {

    if ( !get_option( 'blocks_option' ) ) {
        add_option( 'blocks_option', 'Fresh List of Options' );
        // add_option( 'blocks_option', WP_Block_Type_Registry::get_instance()->get_all_registered());
    }
    delete_option( 'block_option' );
    // update_option( 'blocks_option', 'New New Updated Option' );
    
    $available_blocks_arr = array_keys(WP_Block_Type_Registry::get_instance()->get_all_registered());
    // add_option( 'blocks_option', $available_blocks[$block_type_name]);
    // echo('$available_blocks_arr $available_blocks_arr ');

    foreach ( $available_blocks_arr as $block ) {
        // echo($block);
        delete_option('blocks_option');

        add_option( 'blocks_option', $block ); // ADD OPTION SOMEHOW
    }
}

add_action( 'admin_init', 'blocks_options');

?>
