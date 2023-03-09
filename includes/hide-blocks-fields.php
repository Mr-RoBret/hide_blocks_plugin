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

// callback function from add_settings_field to set up markup for settings fields
function show_all_blocks() {
    
    // gets blocks list from array sent back from ajax function;
    if ( isset( $_POST['blocks'] ) ) {

        $data = ( $_POST[ 'blocks' ] );
        var_dump( $data );
        echo "stringified data, " . $data;
        echo PHP_EOL;
     }

     // esc_html_e( get_option( 'blocks_option' ) );  

    // // gets options from database and turns them into checklist...
    // $options = get_option( 'blocks_settings' );

    // $checkbox = '';
    // if ( isset( $options[ 'checkbox' ] ) ) {
    //     $checkbox = esc_html( $options[ 'checkbox' ] );
    // }  
    
    // $html = '<input type="checkbox" 
    //     id="block_settings_checkbox" 
    //     name="blocks_settings[checkbox]" 
    //     value="1"' . checked( 1, $checkbox, false ) . '/>';
    // $html .= '&nbsp;';

    // echo $html;
    // // $html .= '<label for="block_settings_checkbox">' . $checkbox['label'] . '</label>';
    // wp_die();

}

add_action( 'wp_ajax_show_all_blocks', 'show_all_blocks' );
add_action( 'admin_init', 'blocks_settings' );

?>
