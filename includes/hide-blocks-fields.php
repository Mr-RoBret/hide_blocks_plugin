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

        echo $_POST['blocks'];
        $data = (json_decode($_POST['blocks']));
        // var_dump(json_decode($data['blocks']));
        echo "stringified data, " . $data;
        echo PHP_EOL;
    }
    echo esc_html_e( get_option( 'blocks_option' ) );
    // esc_html_e( get_option( 'blocks_option' ) );  

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
    // $html .= '<label for="block_settings_checkbox">' . $checkbox['label'] . '</label>';

    echo $html;

      

    wp_die();
    // esc_html_e( get_option( 'blocks_option' ) );    
}

// calback function to enqueue js to return list of blocks registered
function blocks_admin_scripts() {
    // global $pagenow;

	// if ($pagenow != 'hide-blocks') {
	// 	return;
	// }
    wp_register_style( 'show-all-blocks-admin', 
        HIDEBLOCKS_URL . 'includes/show_all_blocks_admin.js',
        array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
        '1.0' );
    wp_enqueue_script( 'show-all-blocks-admin' );
}

add_action( 'wp_ajax_show_all_blocks', 'show_all_blocks' );
add_action( 'admin_enqueue_scripts', 'blocks_admin_scripts' );
add_action( 'admin_init', 'blocks_settings' );

?>
