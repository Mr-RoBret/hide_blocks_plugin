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
        'block_settings_checkbox',
        // field title
        __( 'Available Blocks', 'hideblocks' ),
        // callback for field markup
        'block_settings_checkbox_callback',
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


// calback function to enqueue js to return list of blocks registered
function blocks_admin_scripts() {

    wp_enqueue_script( 'settings-page', HIDEBLOCKS_URL . 'includes/show_all_blocks_admin.js', array('jquery'), true );
    wp_localize_script( 'show_all_blocks_admin', 'allBlocksAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

function show_all_blocks() {
    
}

add_action( 'admin_init', 'blocks_settings' );
add_action( 'admin_enqueue_scripts', 'blocks_admin_scripts' );
add_action( 'wp_ajax_show_all_blocks', 'show_all_blocks' );

// set up markup for settings field

function block_settings_checkbox_callback( $args ) {
    
    // gets jQuery data from show_all_blocks_admin.js...

    if ( isset( $_POST[ 'blocks' ] ) ) {
        // Checkbox Field(s)
        $blocks_array = json_decode( $_POST[ 'blocks' ] );
        $blocks_array = explode( ',', $blocks_array );  
        echo $blocks_array;  
        
        foreach ( $blocks_array as $block ) {
            echo $block;
        }
    } else {
        echo( '$_POST["blocks"] not set...' );
    }

    // gets options from database and turns them into checklist...

    $options = get_option( 'blocks_settings' );

    $checkbox = '';
    if ( isset( $options[ 'checkbox' ] ) ) {
        $checkbox = esc_html( $options[ 'checkbox' ] );
    }  
    
    $html = '<input type="checkbox" 
        id="block_settings_checkbox" 
        name="blocks_settings[checkbox]" 
        value="1"' . checked( 1, $checkbox, false ) . '/>';
    $html .= '&nbsp;';
    $html .= '<label for="block_settings_checkbox">' . $args['label'] . '</label>';

    echo $html;
    // esc_html_e( get_option( 'blocks_option' ) );    
}

?>
