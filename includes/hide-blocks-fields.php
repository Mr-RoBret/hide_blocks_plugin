<?php 
function option_markup() {
   $options = get_option( 'blocks_settings' );

   $array_text = '';
   if( isset( $options[ 'array_text' ] ) ) {
    $array_text = esc_html( $options[ 'array_text' ] );
   }

   echo '<input type="text" id="hideblocks_array_text" name="blocks_settings[array_text]" value="' . $array_text . '" />';
}

function blocks_settings() {
    // If plugin settings don't exist, create them
    if( false == get_option( 'blocks_settings' ) ) {
        add_option( 'blocks_settings' );
    }
    
    add_settings_section(
        // unique identifier for section
        'select_blocks_section',
        // section title
        __( 'Blocks to Include', 'hideblocks' ),
        // Callback for optional description
        '',
        // Admin page to add section to
        'hide-blocks'
    );

    add_settings_field(
        // unique identifier for field
        'text-field-id',
        // field title
        __( 'Blocks Array', 'hideblocks' ),
        // callback for field markup
        'option_markup',
        // page to place on
        'hide-blocks',
        //section to place option in
        'select_blocks_section',
    );

    register_setting(
        'blocks_settings', // group (correct name?)
        'blocks_settings'  // specific setting we are registering
    );
}

// gets all blocks and places them in array by name
function get_all_blocks() {
    $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
    $block_names = array();
    foreach( $block_types as $key ) {
        $block_names[] = $key->name;
    }
    // print_r($block_names);
}

add_action( 'admin_init', 'blocks_settings' );
add_action( 'wp_loaded', 'get_all_blocks' );

?>
