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

// echo network_admin_url( 'edit.php');
// print_r( plugin_dir_url( __FILE__ ) );
define( 'HIDEBLOCKS_URL', plugin_dir_url( __FILE__ ) );

// include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-scripts.php' );
// include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-styles.php' );

$main_blocks_settings_slug = 'blocks-settings-main';

    /**
     * Class Constructor.
     */

        add_action( 'network_admin_menu', 'add_submenu' );  
        add_action('network_admin_edit_' . $main_blocks_settings_slug . '-update', 'update_network_setting' );
    
    /**
     * function triggered by 'network_admin_menu' hook
     */
    function add_submenu() {
        $main_blocks_settings_slug = 'blocks-settings-main';
        // If plugin settings don't exist, create them
        if( false == get_site_option( $main_blocks_settings_slug . '-NEW' ) ) {
            add_site_option( $main_blocks_settings_slug . '-NEW', '');
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

        // callback function for add_settings_field
        function multisite_settings_checkbox_callback( $args ) {
            // debug_to_console('current label is ' . $args['label']);
            
            $main_option = get_site_option( 'blocks-settings-main-NEW' );
            // debug_to_console('args index is: ' . $main_option[$args['index']]);

            $checkboxes_field = '';
            if( isset( $main_option[ 'checkboxes_field' ] ) ) {
                $checkboxes_field = esc_html( $main_option[ 'checkboxes_field' ] );
            }

            // $checkboxes_field = isset( $main_option[$args['index']] )
            //     ? (array) $main_option[$args['index']] : [];
            // debug_to_console( 'checkboxes_field is" ' . $checkboxes_field['label'] );

            $html = '<input type="checkbox" 
                name="blocks-settings-main-NEW[checkboxes_field]" 
                id="multisite_settings_checkbox"
                "value="on"' . checked( "on", $checkboxes_field, false ) . '/>';
                 
            $html .= '&nbsp;';

            $html .= '<label for="blocks-settings-main-NEW">' . $args['label'] . '</label>';   
            
            echo $html;
        }

        // // callback function for add_settings_field
        // function multisite_settings_checkbox_callback( $args ) {
        //     // debug_to_console('current label is ' . $args['label']);
            
        //     $main_option = get_site_option( 'blocks-settings-main-NEW' );
        //     // debug_to_console('args index is: ' . $main_option[$args['index']]);

        //     $checkboxes_field = '';
        //     if( isset( $main_option[ 'checkboxes_field' ] ) ) {
        //         $checkboxes_field = esc_html( $main_option[ 'checkboxes_field' ] );
        //     }

        //     // $checkboxes_field = isset( $main_option[$args['index']] )
        //     //     ? (array) $main_option[$args['index']] : [];
        //     // debug_to_console( 'checkboxes_field is" ' . $checkboxes_field['label'] );

        //     $html = '<input type="checkbox" 
        //         name="blocks-settings-main-NEW[checkboxes_field]" 
        //         id="multisite_settings_checkbox_' . $args['index'] . '
        //         "value="1"' . checked( 1, $checkboxes_field, false ) . '/>';
                 
        //     $html .= '&nbsp;';

        //     $html .= '<label for="blocks-settings-main-NEW[checkboxes_field]">' . $args['label'] . '</label>';   
            
        //     echo $html;
        // }
    
        // Register new option for each block in list of all blocks available
        // Add a settings field for each one and check to see (in callback) 
        // if checked already in database
        $main_blocks_registry = get_all_blocks();

        add_settings_field(
            'multisite-settings_checkbox',   // id of field
            __( '', 'multisite-settings' ),         // title of field to display
            'multisite_settings_checkbox_callback', // callback function
            $main_blocks_settings_slug,       // page to display it on
            'main-blocks-section',                  // section on page to display in
            [
                'label' => 'Test Checkbox Field'
            ]
        );

        // foreach( $main_blocks_registry as $key=>$value ) {
        //     // debug_to_console($value);
        //     add_settings_field(
        //         'multisite-settings_checkbox_' . $key,   // id of field
        //         __( '', 'multisite-settings' ),         // title of field to display
        //         'multisite_settings_checkbox_callback', // callback function
        //         $main_blocks_settings_slug,       // page to display it on
        //         'main-blocks-section',                  // section on page to display in
        //         [
        //             'index' => $key,
        //             'label' => $value
        //         ]
        //     );
            // debug_to_console( 'multisite-settings_checkbox_' . $key );
        // }        
        register_setting( 'main-blocks-section', $main_blocks_settings_slug . '-NEW' );
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
        $main_blocks_settings_slug = 'blocks-settings-main';
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
        debug_to_console('page created');
    }

    /**
     * Handle updating the network settings for the plugin.
     * It's important to note that these settings update differently than for a single site installation.
     * In particular, note the redirect at the end of the method
     *
     * @return void
     */
    function update_network_setting() {
        $main_blocks_settings_slug = 'blocks-settings-main';
        debug_to_console('Made it to update_network_setting');
        // check_admin_referer( $main_blocks_settings_slug .'-options');

        // get array of checked options in list,
        // $options_arr = get_site_option( $main_blocks_settings_slug . '-NEW' );
        // foreach($options_arr as $option) { 

        //      if(isset($_POST[$main_blocks_settings_slug . '-NEW'[$option] ])) {
        //          update_site_option($main_blocks_settings_slug . '-NEW'[$option], $_POST[$main_blocks_settings_slug . '-NEW'[$option] ]);
        //      } else {
        //         update_site_option($main_blocks_settings_slug . '-NEW'[$option], '');
        //     } 
        // }
        
        // update_site_option(  $main_blocks_settings_slug . '-NEW', $_POST[ $main_blocks_settings_slug . '-NEW' ] );

        if (isset($_POST[$main_blocks_settings_slug . '-NEW'])) {
            update_site_option($main_blocks_settings_slug . '-NEW', $_POST[$main_blocks_settings_slug . '-NEW']);
        } else {
            update_site_option($main_blocks_settings_slug . '-NEW', '');
        }
        
        // nocache_headers();

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
  
// Initialize the execution.
// new Settings_Page();

function get_all_blocks() {
    $test_regex = "/[a-z]+\/[a-z]+-?[a-z]+$/";
    // $prefix_regex = "/[a-z]+\-?[a-z]+$/";
    $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
    $block_names = array();
    foreach( $block_types as $key ) {
        $block_names[] = $key->name;
    }
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
    return array(
        'core/paragraph',
        'core/heading',
        'core/list',
    );
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
