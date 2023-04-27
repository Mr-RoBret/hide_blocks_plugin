<?php
/*
Plugin Name: Hide Blocks Plugin
Description: Populates list of blocks with toggles for turning on & off in block selector
Version: 1.0.0
Contributors: bfarley01
Author: Bret Farley
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html 
Text Domain: multisite-settings
Network: true
*/

if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');
require( ABSPATH . '/wp-load.php' );

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    wp_die();
}

define( 'HIDEBLOCKS_URL', plugin_dir_url( __FILE__ ) );

include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-scripts.php' );
include( plugin_dir_path( __FILE__ ) . 'get_main_blocks.php' );

$main_blocks_settings_slug = 'blocks-settings-main';
$main_blocks_not_allowed = [];

add_action( 'network_admin_menu', 'add_submenu' );  
add_action( 'network_admin_edit_' . $main_blocks_settings_slug . '-update', 'update_network_setting' );

/**
 * function triggered by 'network_admin_menu' hook
 */
function add_submenu() {
    $main_blocks_settings_slug = $GLOBALS[ 'main_blocks_settings_slug' ];

    // Create the submenu and register the page creation function.
    add_submenu_page(
        'settings.php',
        __( 'Block Hider Plugin', 'multisite-settings' ),
        __( 'Block Hider', 'multisite-settings' ),
        'manage_network_options',
        $main_blocks_settings_slug,
        'create_page'
    );

    // Register a new section on the page.
    add_settings_section(
        'main-blocks-section',
        __( 'Blocks to Display in Block Inserter', 'multisite-settings' ),
        'add_instructions',
        $main_blocks_settings_slug
    );

    // Add a settings field for each one and check to see (in callback) 
    // if checked already in database
    $main_blocks_registry = get_all_blocks();
    debug_to_console( 'array of currently registered blocks is: ' . $main_blocks_registry );
   
    // callback function for add_settings_field
    function individual_settings_checkbox_callback( $key, $label ) {
        $main_blocks_settings_slug = $GLOBALS[ 'main_blocks_settings_slug' ];
    
        add_settings_field(
            'block_checkbox_' . $label,   // id of field
            __( '', 'multisite-settings' ),         // title of field to display
            'multisite_settings_checkbox_callback', // callback function
            $main_blocks_settings_slug,       // page to display it on
            'main-blocks-section',                  // section on page to display in
            [   
                'field_name'=>'block_checkbox_' . $label,
                'index' => $key,
                'label' => $label
            ]
        );
    }
    
    // callback function to create html for each option and check its status
    function multisite_settings_checkbox_callback( $args ) {
        $option = get_site_option( $args['field_name'] );
        $option_name = $args['field_name'];
        $block_name = $args['label'];
    
        $checkboxes_field = '';
        if( isset( $option[ 'checkboxes_field' ] ) ) {
            $checkboxes_field = esc_html( $option[ 'checkboxes_field' ] );
            // array_push($GLOBALS[ 'main_blocks_not_allowed' ], $block_name);
        }
        
        // create html for current option
        $html = '<input type="checkbox" 
            name="' . $option_name . '[checkboxes_field]" 
            id="block_checkbox_' . $args['label'] . '"
            "value="on"' . checked( "on", $checkboxes_field, false ) . '/>';
                
        $html .= '&nbsp;';
    
        $html .= '<label for="block_checkbox_' . $args['label'] . '">' . $args['label'] . '</label>';   
        
        echo $html;
    }

    // loop through every block in registry and add option if no option exists;
    // then send to callback functions to add settings field and create html. 
    foreach( $main_blocks_registry as $key=>$value ) {
        // If plugin settings don't exist, create them
        if( false == get_site_option( 'block_checkbox_' . $value ) ) {
            add_site_option( 'block_checkbox_' . $value, '' );
        }
        individual_settings_checkbox_callback( $key, $value );

        // register setting for newly created checkbox option
        register_setting( 'main-blocks-section', 'block_checkbox_' . $value );   
    }
}

/**
 * Html after the new section title.
 *
 * @return void
 */
function add_instructions() {
    esc_html_e( 'Please check the blocks you would like visible in the Block Inserter.', 'multisite-settings' );
}

/**
 * This creates the settings page itself.
 */
function create_page() {
    $main_blocks_settings_slug = $GLOBALS[ 'main_blocks_settings_slug' ];
    ?>
    <?php if ( isset( $_GET['updated'] ) ) : ?>
        <div id="message" class="updated notice is-dismissible">
            <p><?php esc_html_e( 'Options Saved', 'multisite-settings' ); ?></p>
        </div>
        <?php endif; ?>
        <div class="wrap">
            <h1><?php echo esc_attr( get_admin_page_title() ); ?></h1>   

            <form method="post" action="<?php echo add_query_arg( 'action', $main_blocks_settings_slug . '-update', 'edit.php' ) ?>">
                <?php
                    settings_fields( 'main-blocks-section' );
                    do_settings_sections( $main_blocks_settings_slug );
                    submit_button();
                ?>
            </form>
        
        </div>
    <?php  
}

/**
 * Handle updating the network settings for the plugin.
 * It's important to note that these settings update differently than for a single site installation.
 * In particular, note the redirect at the end of the method
 *
 * @return void
 */
function update_network_setting() {
    $main_blocks_settings_slug = $GLOBALS[ 'main_blocks_settings_slug' ];
    debug_to_console('Made it to update_network_setting');
    
    // get array of all registered blocks,
    $options_arr = get_all_blocks();

    // loop through array of all registered blocks and update site option with existing value
    foreach($options_arr as $key=>$value) { 
        if( isset( $_POST[ 'block_checkbox_' . $value ] ) ) {
            update_site_option( 'block_checkbox_' . $value, $_POST[ 'block_checkbox_' . $value ] );
        } else {
            array_push( $GLOBALS[ 'main_blocks_not_allowed' ], $value );
            update_site_option( 'block_checkbox_' . $value, '');
        } 
    }
    
    // nocache_headers();

    // redirect to settings page w/ 'updated' attribute, to trigger update
    $queryArgs = add_query_arg(
    [
        'page' => $main_blocks_settings_slug,
        'updated' => true,
    ],
    network_admin_url( 'settings.php' )
    );

    wp_safe_redirect($queryArgs);

    exit;
}

// get object of all registered blocks
function get_all_blocks() {
    $test_regex = "/[a-z]+\/[a-z]+-?[a-z]+$/";
    $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
    $block_names = array();

    // place key 'name' into $block_names array,
    foreach( $block_types as $key ) {
        $block_names[] = $key->name;
    }

    // create new array of block names that match the above regex and return
    $block_names_verified = array();
    foreach( $block_names as $name ) {
        $success = preg_match( $test_regex, $name, $match );
        if( $success ) {
            // preg_match( $prefix_regex, $name, $matches );
            array_push( $block_names_verified, $name ); // echo '<p>' . $matches[0] . ', </p></br>';
        }
    }


    return $block_names_verified;
}


/**
 * function to retrieve an array from options not selected 
 * and hide them from the block insterter // Somehow this needs to run ** later ** than it currently runs. 
 * 
 * @return array
 * 
 */
function stolaf_allowed_block_types( $blocks_not_allowed ) {
    debug_to_console( $blocks_not_allowed );
    return $blocks_not_allowed;

    // return array(
    //     'core/block', 'core/social-links', 'core/spacer', 'core/table', 'core/text-columns', 'core/widgets',
    // );
}

add_filter( 'allowed_block_types_all', 'stolaf_allowed_block_types' );
// add_action( 'plugins_loaded', 'stolaf_allowed_block_types' );

function filter_block_types() {
    $blocks_not_allowed = $GLOBALS[ 'main_blocks_not_allowed' ];
    $blocks_not_allowed = implode(  ", ", $blocks_not_allowed );
    apply_filters( 'allowed_block_types_all', $blocks_not_allowed, 25, 1 );
}

add_action( 'plugins_loaded', 'filter_block_types' );

/**
 * Simple helper to debug to the console
 *
 * @param $data object, array, string $data
 * @param $context string  Optional a description.
 *
 * @return string
 */
function debug_to_console($data, $context = 'Debug in Console') {

    // Buffering to solve problems frameworks, like header() in this and not a solid return.
    ob_start();

    $output  = 'console.info(\'' . $context . ':\');';
    $output .= 'console.log(' . json_encode($data) . ');';
    $output  = sprintf('<script>%s</script>', $output);

    echo $output;
}

?>
