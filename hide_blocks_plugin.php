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

// include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-scripts.php' );
// include( plugin_dir_path( __FILE__ ) . 'get_main_blocks.php' );

$main_blocks_settings_slug = 'blocks-settings-main';
$whitelisted_blocks = 'block_checkbox_options';

add_action( 'network_admin_menu', 'add_submenu' );  
add_action( 'network_admin_edit_' . $main_blocks_settings_slug . '-update', 'update_network_setting' );

/**
 * function triggered by 'network_admin_menu' hook
 */
function add_submenu() {
    $main_blocks_settings_slug = $GLOBALS[ 'main_blocks_settings_slug' ];

    if( false == get_site_option( $GLOBALS[ 'whitelisted_blocks' ] ) ) {
        add_site_option( $GLOBALS[ 'whitelisted_blocks' ], '' );
    }

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

    // Add a settings field to house the whitelist array
    add_settings_field(
        $GLOBALS[ 'whitelisted_blocks' ],   // id of field
        __( '', 'multisite-settings' ),         // title of field to display
        'multisite_settings_checkbox_callback', // callback function
        $main_blocks_settings_slug,       // page to display it on
        'main-blocks-section'
    );
   
    // callback function for settings field (main site option) 
    function multisite_settings_checkbox_callback() {

        $options_name = $GLOBALS[ 'whitelisted_blocks' ];
        $whitelist_options = (get_site_option($options_name));
        $names_arr = [];

        // check to see if whitelist is empty; if not grab listed options
        if( isset( $whitelist_options ) && ! empty( $whitelist_options )) {
            foreach( $whitelist_options as $option_name ) {
                array_push($names_arr, $option_name);
            }
        }

        // callback function to check off checkboxes for options in whitelist
        function checkName($block_name_wrapped, $whitelist_arr) {
            if (in_array($block_name_wrapped, $whitelist_arr)) {
              return 'checked';
            }
          }

        // callback function for add_settings_option; adds html for each block returned from registry
        function individual_settings_checkbox_callback( $registry_block, $names_arr ) {

            $html = '<input type="checkbox" name="block_checkbox_options['.$registry_block.']" 
                id="block_checkbox_'.$registry_block.'" 
                value="'.$registry_block.'"'. checkName($registry_block, $names_arr).'>';
            $html .= '<label for="block_checkbox_'. $registry_block .'">'.$registry_block.'</label>';
            $html .= '<br>';

            echo $html;
        }

        // get list of all registered blocks and add to variable array variable
        $main_blocks_registry = get_all_blocks();

        // then for each item in array, send to callback functions to add settings field and create html. 
        foreach( $main_blocks_registry as $registry_block ) {
            individual_settings_checkbox_callback( $registry_block, $names_arr );   
        }
    }
    // register setting for newly created checkbox option
    register_setting( 'main-blocks-section', $GLOBALS[ 'whitelisted_blocks' ] );
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
    
    // get site option array and put in variable ($checked_options)
    $database_option = $GLOBALS[ 'whitelisted_blocks' ];

    if( isset( $_POST[ $database_option ] ) ) {
        update_site_option( $database_option, $_POST[ $database_option ] );
    } else {
        update_site_option( $database_option, '');
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
 * and hide them from the block insterter 
 * 
 * @return array
 * 
 */
function stolaf_allowed_block_types() {
    
    $options_name = $GLOBALS[ 'whitelisted_blocks' ];
        $whitelist_options = (get_site_option($options_name));
        $names_arr = [];

        if( isset( $whitelist_options ) && ! empty( $whitelist_options )) {
            foreach( $whitelist_options as $option_name ) {
                array_push($names_arr, $option_name);
            }
        }

    return $names_arr;

    // return array(
    //     'core/block', 'core/social-links', 'core/spacer', 'core/table', 'core/text-columns', 'core/widgets',
    // );
}

add_filter( 'allowed_block_types_all', 'stolaf_allowed_block_types' );

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
