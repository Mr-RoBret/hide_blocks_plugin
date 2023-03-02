<?php 

// callback function to add options
function blocks_options() {
    //$available_blocks = /* GET LIST OF ALL AVAILABLE BLOCKS */
    foreach ( $available_blocks as $block ) {
        add_option( 'hide_blocks_option', ''); // ADD OPTION SOMEHOW
        echo '<p>block here</p>';
    }
}

// add section to settings page
function blocks_settings() {
    add_settings_section(
        // unique identifier for section
        'select_blocks_section',
        // section title
        __( 'Select Blocks Section', 'hideblocks' ),
        // Callback for optional description
        'blocks_options',
        // Admin page to add section to
        'hide-blocks'
    );

    register_setting(
        'blocks_settings', 
        'blocks_settings'
    );
}

?>