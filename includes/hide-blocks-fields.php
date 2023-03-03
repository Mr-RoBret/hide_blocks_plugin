<?php 

// include( plugin_dir_path( __FILE__ ) . 'hide-blocks-options.php' );

// add section to settings page
function blocks_settings() {
    // If plugin settings don't yet exist, create
    if( !get_option( 'blocks_settings' ) ) {
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
    
    // Checkbox Field
    add_settings_field(
        // unique identifier for field
        'blocks_settings_checkbox',
        // field title
        __( 'Available Blocks', 'hideblocks' ),
        // callback for field markup
        'block_settings_checkbox_callback',
        // page to place on
        'hide-blocks',
        //section to place option in
        'select_blocks_section',
        [
            'label' => 'Checkbox Label'
        ]
    );
    
    register_setting(
        'blocks_settings', // group (correct name?)
        'blocks_settings'  // specific setting we are registering
    );
}

add_action( 'admin_init', 'blocks_settings' );

// set up markup for settings field

function block_settings_checkbox_callback( $args ) {
    
    $checkbox = '';
    if ( isset( $options[ 'checkbox' ] ) ) {
        $checkbox = esc_html( $options[ 'checkbox' ] );
    }  
    
    $html = '<input type="checkbox" 
        id="hide_blocks_option" 
        name="blocks_settings[checkbox]" 
        value="1"' . checked( 1, $checkbox, false ) . '/>';
    $html .= '&nbsp;';
    $html .= '<label for="block_settings_checkbox">' . $args['label'] . '</label>';

    echo $html;
    
    esc_html_e( get_option( 'blocks_option' ) );    
}

?>
