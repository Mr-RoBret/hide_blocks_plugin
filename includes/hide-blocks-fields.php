<?php 

// add section to settings page
function blocks_settings() {
    // If plugin settings don't yet exist, create
    if( false == get_option( 'blocks_settings' ) ) {
        add_option( 'blocks_settings' );
    }
    
    add_settings_section(
        // unique identifier for section
        'select_blocks_section',
        // section title
        __( 'Select Blocks Section', 'hideblocks' ),
        // Callback for optional description
        '',
        // Admin page to add section to
        'hide-blocks'
    );

    
    register_setting(
        'blocks_settings', // group (correct name?)
        'blocks_settings'  // specific setting we are registering
    );
}

function get_all_blocks() {

    $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
    $block_names = array();
    foreach( $block_types as $key ) {
        $block_names[] = $key->name;
    }

    foreach( $block_names as $option_name ) {
        // if block field not present, add
        // if ( !in_array( $option_name, $block_names ) ) {
            add_block_field($option_name);
        // }
    }

}

function add_block_field($option_name) {

    $option_id = 'option-' . $option_name;

    add_settings_field(
        // unique identifier for field
        $option_id,
        // field title
        __( $option_name, 'hideblocks' ),
        // callback for field markup
        'option_markup',
        // page to place on
        'hide-blocks',
        //section to place option in
        'select_blocks_section',
        [
            'label' => $option_name
        ]
    );
    
}

// callback function from add_settings_field to set up markup for settings fields
function option_markup( $args ) {
    // gets blocks list from array sent back from ajax function;
    //if ( isset( $option_name ) ) {
        $options = get_option( 'blocks_settings' );

        $option_name = $args[ 'label' ];
        $checkbox = '';
        if ( isset( $options[ 'checkbox' ] ) ) {
            $checkbox = esc_html( $options[ 'checkbox' ] );
        }  
        
        // print_r('..................................' . $option_name);
        
        $html = '<input type="checkbox" 
        id="'. $option_name . '_checkbox" 
        name="blocks_settings[checkbox]" 
        value="1"' . checked( 1, $checkbox, false ) . '/>';
        $html .= '&nbsp;';
        $html .= '<label for="' . $option_name . '_checkbox">' . $option_name . '</label>';
        
        echo $html;

    //}
}

add_action( 'admin_init', 'blocks_settings' );
add_action( 'admin_init', 'get_all_blocks' );
// add_action( 'admin_init', 'show_all_blocks' );

?>
