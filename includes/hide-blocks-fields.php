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

function add_settings_field_callback() {
    echo 'add_settings_field_callback here';
}

// callback function from add_settings_field to set up markup for settings fields
function show_all_blocks() {
    if ( isset( $_POST['blocks'] ) ) {

        $data = json_decode(file_get_contents("php://input"), true);

        echo "stringified data, " . $data["blocks"];
        echo PHP_EOL;
    }
    echo 'esc_html_e( get_option( "blocks_option" ) ) is: ';
    esc_html_e( get_option( 'blocks_option' ) );    

    // gets jQuery data from show_all_blocks_admin.js...
    // if ( isset( $_POST['blocks'] ) ) {

    //     // Checkbox Field(s)
    //     $blocks_array = ( $_POST[ 'blocks' ] );
    //     // $blocks_array = $_REQUEST[ 'blocks' ];
    //     echo $blocks_array;  
    //     $blocks_array = explode( ',', $blocks_array );  
        
    //     foreach ( $blocks_array as $block ) {
    //         echo $block;
    //     }
    // } else {
    //     echo( '$_POST["blocks"] not set...' );
    // }

    die();

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
    $html .= '<label for="block_settings_checkbox">' . $checkbox['label'] . '</label>';

    echo $html;
    // esc_html_e( get_option( 'blocks_option' ) );    
}

// calback function to enqueue js to return list of blocks registered
function blocks_admin_scripts() {
    // global $pagenow;

	// if ($pagenow != 'settings-page.php') {
	// 	return;
	// }
    wp_enqueue_script(
        'show-all-blocks-admin',
        HIDEBLOCKS_URL . 'includes/show_all_blocks_admin.js',
        array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
        time()
    );
}

add_action( 'admin_init', 'blocks_settings' );
add_action( 'admin_enqueue_scripts', 'blocks_admin_scripts' );
add_action( 'wp_ajax_show_all_blocks', 'show_all_blocks' );

?>
