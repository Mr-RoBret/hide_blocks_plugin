<?php 

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

    add_settings_field(
        // unique identifier for field
        'blocks_settings_checklist',
        // field title
        'Available Blocks',
        // callback for field markup
        'populate_checklist',
        // page to place on
        'hide-blocks',
        //section to place option in
        'select_blocks_section'
    );

    register_setting(
        'blocks_settings', // group (correct name?)
        'blocks_settings'  // specific setting we are registering
    );
}

add_action( 'admin_init', 'blocks_settings' );

// set up markup for settings field

function populate_checklist() {
    // $options = get_options( 'blocks_settings' );


}

?>