<?php
/*
Plugin Name: Hide Blocks Plugin
Description: Provides the list of all registered blocks, including Embed Block variations, with toggles for turning on & off in Block Inserter
Version: 0.1.5
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
include( plugin_dir_path( __FILE__ ) . 'includes/get_variation_blocks.php' );
// include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-styles.php' );

$main_blocks_settings_slug = 'blocks-settings-main';
$block_variations_settings_slug = 'blocks_settings-variation';
$whitelisted_blocks = 'block_checkbox_options';
$whitelisted_variations = 'variation_checkbox_options';

add_action( 'network_admin_menu', 'add_submenu' );  
add_action( 'network_admin_edit_' . $main_blocks_settings_slug . '-update', 'update_network_setting' );

/**
 * function triggered by 'network_admin_menu' hook
 */
function add_submenu() {
    $main_blocks_settings_slug = $GLOBALS[ 'main_blocks_settings_slug' ];
    // $block_variations_settings_slug = $GLOBALS[ 'block_variations_settings_slug' ];

    if( false == get_site_option( $GLOBALS[ 'whitelisted_blocks' ] ) ) {
        add_site_option( $GLOBALS[ 'whitelisted_blocks' ], '' );
    }
    if( false == get_site_option( $GLOBALS[ 'whitelisted_variations' ] ) ) {
        add_site_option( $GLOBALS[ 'whitelisted_variations' ], '' );
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

    // Register a new section on the page.
    add_settings_section(
        'variation-blocks-section',
        __( 'Variations to Display in Block Inserter', 'multisite-settings' ),
        'add_variation_instructions',
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
   
    // Add a settings field to house the whitelist array
    add_settings_field(
        $GLOBALS[ 'whitelisted_variations' ],   // id of field
        __( '', 'multisite-settings' ),         // title of field to display
        'multisite_settings_variations_callback', // callback function
        $main_blocks_settings_slug,       // page to display it on
        'variation-blocks-section'
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
        $unique_blocks_registry = array_unique($main_blocks_registry);
        sort($unique_blocks_registry);

        // then for each item in array, send to callback functions to add settings field and create html. 
        foreach( $unique_blocks_registry as $registry_block ) {
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
 * ************** VARIATION CODE BEGIN **************** *
 */
function multisite_settings_variations_callback() {
    // debug_to_console('We are at multisite_settings_variations_callback.');
    $variations_name = $GLOBALS[ 'whitelisted_variations' ];
    $variations_options = (get_site_option($variations_name));
    $variations_arr = [];
    // console_debug($variations_name);

    // check to see if whitelist is empty; if not grab listed options
    if( isset( $variations_options ) && ! empty( $variations_options )) {
        foreach( $variations_options as $var_name ) {
            array_push($variations_arr, $var_name);
        }
    }

    // callback function to check off checkboxes for options in whitelist
    function checkVariationName($block_name_wrapped, $whitelist_arr) {
        if (in_array($block_name_wrapped, $whitelist_arr)) {
          return 'checked';
        }
      }

    // callback function for add_settings_option; adds html for each block returned from registry
    function individual_variations_checkbox_callback( $registry_block, $variations_arr ) {

        $html = '<input type="checkbox" name="variation_checkbox_options['.$registry_block.']" 
            id="variation_checkbox_'.$registry_block.'" 
            value="'.$registry_block.'"'. checkVariationName($registry_block, $variations_arr).'>';
        $html .= '<label for="variation_checkbox_'. $registry_block .'">'.$registry_block.'</label>';
        $html .= '<br>';

        echo $html;
    }

    // get list of all registered variation blocks and add to array variable
    $variation_blocks_registry = get_all_variation_blocks();
    sort($variation_blocks_registry);
    // debug_to_console($variation_blocks_registry);

    // then for each item in array, send to callback functions to add settings field and create html. 
    foreach( $variation_blocks_registry as $registry_block ) {
        // debug_to_console($registry_block);
        individual_variations_checkbox_callback( $registry_block, $variations_arr );   
    }
    // register setting for newly created checkbox option
    register_setting( 'variation-blocks-section', $GLOBALS[ 'whitelisted_variations' ] );
}


/**
* Html after the new section title.
*
* @return void
*/
function add_variation_instructions() {
esc_html_e( 'Please check the block variations you would like visible in the Block Inserter.', 'multisite-settings' );
}

function variations_rest_get_request( $route ) {
    $request = new WP_REST_Request( 'GET', $route );
    // $request->set_query_params( $params );
    $response = rest_do_request( $request ); // get only one array from response here...

    return rest_get_server()->response_to_data( $response, false );
}

function get_all_variation_blocks() {
    $route = '/blocks-settings-main/v1/main-blocks';
    $request = variations_rest_get_request( $route );
    $variations_all = $request['all_variations'];
    return $variations_all;

}

/**
 * ************** VARIATION CODE END **************** *
 */


/**
 * This creates the settings page itself.
 */
function create_page() {
    $main_blocks_settings_slug = $GLOBALS[ 'main_blocks_settings_slug' ];
    $block_variations_settings_slug = $GLOBALS[ 'block_variations_settings_slug' ];
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
                    settings_fields( 'main-blocks-section', 'variation-blocks-section' );
                    do_settings_sections( $main_blocks_settings_slug, $block_variations_settings_slug );
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
    
    // get global names
    $database_option = $GLOBALS[ 'whitelisted_blocks' ];
    $variation_option = $GLOBALS[ 'whitelisted_variations' ];

    // update main options
    if( isset( $_POST[ $database_option ] ) ) {
        update_site_option( $database_option, $_POST[ $database_option ] );
    } else {
        update_site_option( $database_option, '');
    }

    // update variation options
    if( isset( $_POST[ $variation_option ] ) ) {
        update_site_option( $variation_option, $_POST[ $variation_option ] );
    } else {
        update_site_option( $variation_option, '');
    }
    
    nocache_headers();

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
    // $test_regex = "/[a-z]+\/[a-z]+-?[a-z]+$/";
    $test_regex = "/[a-z]+\/([a-z]+-?){1,}$/";
    $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();

    $block_names_verified = array();
    $block_names = array(
        'acf/st-olaf-accordion', 
        'acf/st-olaf-events', 
        'acf/st-olaf-events-main-site', 
        'acf/st-olaf-explore', 
        'acf/st-olaf-faculty-staff', 
        'acf/st-olaf-get-to-know', 
        'acf/st-olaf-hero', 
        'acf/st-olaf-image-overlay', 
        'acf/st-olaf-imagetext', 
        'acf/st-olaf-masonry-gallery', 
        'acf/st-olaf-micronavbutton', 
        'acf/st-olaf-query-loop-main-site', 
        'acf/st-olaf-query-loop-main-site',
        'acf/st-olaf-query-loop', 
        'acf/st-olaf-social', 
        'acf/st-olaf-tabs', 
        'acf/st-olaf-title', 
        'acf/st-olaf-video', 
        'acf/st-olaf-wysiwyg', 
        'acf/st-olaf-youtube'
    );

    function test_for_parent( $block_object ) {
        if( null == $block_object->parent ) {
            return true;
        }
    }

    foreach( $block_types as $block ) {

        if( test_for_parent( $block ) ) {
            // array_push($block_names, $block->name);
            $block_names[] = $block->name;
        }
    }
    
    // create new array of block names that match the above regex and return
    foreach( $block_names as $name ) {
        
        $success = preg_match( $test_regex, $name, $match, false );
        if( $success ) {
            array_push( $block_names_verified, $name ); // echo '<p>'
        }
    }
    // debug_to_console($block_names_verified);
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
    
    // get whitelist array from main blocks settings
    $options_name = $GLOBALS[ 'whitelisted_blocks' ];
    $whitelist_options = (get_site_option($options_name));
    $final_whitelist_array = [];
    
    if( isset( $whitelist_options ) && ! empty( $whitelist_options )) {
        foreach( $whitelist_options as $option_name ) {
            array_push($final_whitelist_array, $option_name);
        }
    }
    return $final_whitelist_array;
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
