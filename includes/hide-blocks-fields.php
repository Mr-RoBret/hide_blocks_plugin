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
    
    add_settings_field(
        // unique identifier for field
        'blocks_settings',
        // field title
        __( 'Available Blocks', 'hideblocks' ),
        // callback for field markup
        'show_all_blocks',
        // page to place on
        'hide-blocks',
        //section to place option in
        'select_blocks_section',
        [
            'label' => 'Available Blocks'
        ]
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
    // $block_names = json_encode($block_names);
    // print_r('...............................................................' . $block_names);
    
    
}

// callback function from add_settings_field to set up markup for settings fields
function show_all_blocks($block_names) {
    
    foreach( $block_names as $option_name ) {
        $option_label = $option_name;
        add_option( $option_label, $option_label );
    }
    // gets blocks list from array sent back from ajax function;
    if ( isset( $block_names ) ) {
        
        // esc_html_e( get_option( 'blocks_option' ) );  
    
        // gets options from database and turns them into checklist...
        $options = esc_html_e( get_option( 'blocks_settings' ));
    
        $checkbox = '';
        if ( isset( $options[ 'checkbox' ] ) ) {
            $checkbox = esc_html( $options[ 'checkbox' ] );
        }  
        
        $html = '<input type="checkbox" 
            id="block_settings_checkbox" 
            name="blocks_settings[checkbox]" 
            value="1"' . checked( 1, $checkbox, false ) . '/>';
        $html .= '&nbsp;';
    
        echo $html;
        // $html .= '<label for="block_settings_checkbox">' . $checkbox['label'] . '</label>';
        // wp_die();
        
     }


}

add_action( 'admin_init', 'blocks_settings' );
add_action( 'wp_loaded', 'get_all_blocks' );
add_action( 'admin_init', 'show_all_blocks' );

?>
