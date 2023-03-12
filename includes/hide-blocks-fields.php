<?php 

// add section to settings page
function blocks_settings() {
    
    add_settings_section(
        // unique identifier for section
        'blocks_settings',
        // section title
        __( 'Select Blocks Section', 'hideblocks' ),
        // Callback for optional description
        'add_plugin_description',
        // Admin page to add section to
        'hide-blocks'
    );

    function add_plugin_description() {
        echo esc_html_e( 'Select the plugins you would like hidden (unregistered) from the blocks selector.', 'hide-blocks' );
    }

    // adds option to db for blocks not found in db options
    function add_block_field($option_name) {

        $option_id = 'option-' . $option_name;

        add_settings_field(
            // unique identifier for field
            $option_name,
            // field title
            __( '', 'hideblocks' ),
            // callback for field markup
            'option_markup',
            // page to place on
            'hide-blocks',
            //section to place option in
            'blocks_settings',
            [
                'label' => $option_name
            ]
        );
        
    }
}

// gets all Registered blocks from Registry
function get_all_blocks() {

    $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered(); // gets all registered blocks as Registry object
    $block_types = json_decode(json_encode($block_types), true) ; // converts nested Registry obj into array

    $block_names = array(); // create new array

    // for each block type in converted array...
    foreach( $block_types as $key ) {
        // find non-variation block types (not variations of other blocks) and add to block_names
        if ( empty($key['parent']) ) {
            // add block name to $block_names array
            $block_names[] = $key['name'];
        }
    };
    
    // for each block in block_names array, 
    foreach( $block_names as $block_name ) {
        // if block type does not yet exist as option in db, add option
        if ( false == get_option( $block_name ) ) {
            add_option( $block_name, $block_name );
            register_setting( 'hideblocks_select_blocks_section', $block_name ); // group, specific setting we are registering
            //print_r('option ' . $block_name . ' added.');
        }  
        update_option( $block_name, $block_name );
        sort($block_names);
        
    };

    // somehow, options should be loaded automatically here, so they aren't overwritten by following code

    // get list of options and add fields for them;
    $options = wp_load_alloptions();
    $all_block_options = array_intersect( $options, $block_names );
    // print_r($all_block_options);

    foreach( $all_block_options as $option_name ) {
        // if ( false == get_option( $block_name ) ) {
            add_block_field( $option_name );  
        // }
        // update_option( $block_name, $block_name );
    };

}

// callback function from add_settings_field to set up markup for settings fields
function option_markup( $args ) {
    
        $option_name = $args[ 'label' ];
        
        $option = get_option( $option_name );
        // print_r($option);

        $checkbox = '';
        if ( isset( $option[ 'checkbox' ] ) ) {
            $checkbox = esc_html( $option[ 'checkbox' ] );
        }  

        $option_id = $option_name . '_checkbox';
        $html = '<input type="checkbox" id="'. $option_id . '" name="' . $option_name . '[checkbox]" value="1"' . checked( 1, $checkbox, false ) . '/>';
        $html .= '&nbsp;';
        $html .= '<label for="' . $option_id . '">' . $option_name . '</label>';
        
        echo $html;

}

add_action( 'admin_init', 'blocks_settings' );
add_action( 'admin_init', 'get_all_blocks' );
// add_action( 'admin_init', 'show_all_blocks' );

?>
