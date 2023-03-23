<?php
/*
Plugin Name: Hide Blocks Plugin
Description: Populates list of blocks with toggles for turning on & off in block selector
Version: 1.0.0
Contributors: bfarley01
Author: Bret Farley
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html 
Text Domain: hideblocks
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
define( 'HIDEBLOCKS_URL', plugin_dir_url( __FILE__ ) );

include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-scripts.php' );
// include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-fields.php' );
// include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-styles.php' );

class Settings_Page {

    /**
     * This will be used for the SubMenu URL in the settings page and to verify which variables to save.
     *
     * @var string
     */
    protected $settings_slug = 'custom-network-settings';

    /**
     * Class Constructor.
     */
    public function __construct() {
        add_site_option($this->settings_slug, false);
        add_site_option($this->settings_slug . '-sites', []);
        // Register function for settings page creation.
        // add_action( 'network_admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'network_admin_menu', array( $this, 'add_submenu' ) );        
    }
        
    public function add_submenu() {

        /**
         * Creates the sub-menu page and register the multisite settings.
         *
         * @return void
         */

        // Create the submenu and register the page creation function.
        add_submenu_page(
            'settings.php',
            __( 'Block Hider Plugin', 'multisite-settings' ),
            __( 'Block Hider', 'multisite-settings' ),
            'manage_network_options',
            $this->settings_slug . '-page',
            array( $this, 'create_page' ),
        );

        // Register a new section on the page.
        add_settings_section(
            'main-blocks-section',
            __( 'Main Blocks to Hide', 'multisite-settings' ),
            array( $this, 'add_instructions' ),
            $this->settings_slug . '-page'
        );

        // Register a variable and add a field to update it.
        register_setting( $this->settings_slug . '-page', 'blocks_settings_main' );

        add_settings_field(
            'text_input',
            __( 'Main Blocks Array', 'multisite-settings' ),
            array( $this, 'main_markup' ), // callback.
            $this->settings_slug . '-page', // page.
            'main-blocks-section' // section.

        );
    }
    
    /**
     * Html after the new section title.
     *
     * @return void
     */
    public function add_instructions() {
        esc_html_e( 'Add names of blocks you would like to hide from the Block Selector.', 'multisite-settings' );
    }

    
    /**
     * Creates and input field.
     *
     * @return void
     */
    public function main_markup() {
        $val = get_site_option( 'blocks_settings_main', '' );
        // echo '<p><strong>List of Blocks Currently Registered:</strong></p>';
        // echo '<br>';
        // echo '<div id="list-blocks">';
        //     $main_blocks_registry = get_all_blocks();
        //     foreach( $main_blocks_registry as $registered_block ) {
        //         echo '*' . $registered_block . '* ';
        //     }
        // echo '</div>';
        echo '<textarea name="blocks_settings_main" rows="5" cols="50" value="' . esc_attr( $val ) . '" />';
    }

    /**
     * This creates the settings page itself.
     */
    public function create_page() {
        ?>
        <?php if ( isset( $_GET['updated'] ) ) : ?>
        <div id="message" class="updated notice is-dismissible">
            <p><?php esc_html_e( 'Options Saved', 'multisite-settings' ); ?></p>
        </div>
        <?php endif; ?>
        <div class="wrap">
        <h1><?php echo esc_attr( get_admin_page_title() ); ?></h1>
        <form action="edit.php?action=<?php echo esc_attr( $this->settings_slug ); ?>-update" method="POST">
            <?php
                settings_fields( $this->settings_slug . '-page' );
                do_settings_sections( $this->settings_slug . '-page' );
                submit_button();
            ?>
        </form>
        </div>
    <?php  }

}
  
// Initialize the execution.
new Settings_Page();

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
            // print_r( $matches[0] );
        }
    }
    // print_r( $block_names_truncated );
    return $block_names_verified;
}


// callback function to call markup for settings page
// function blocks_settings_page() {
//     if ( !current_user_can('manage_options') ) {
//         return;
//     }

//     include( __DIR__ . '/templates/admin/settings-page.php' );
// }

// // add settings page to menu
// function add_settings_page() {
//     add_menu_page(
//         'Hide Blocks Plugin',
//         'Hide Blocks',
//         'manage_options',
//         'hide-blocks',
//         'blocks_settings_page', // callback to display plugin on page ('hide-blocks' slug)
//         'dashicons-hidden',
//         100
//     );
// }


// /*** ACTIONS ***/
// add_action( 'network_admin_menu', 'add_settings_page' );

/*** FILTERS ***/
// Add a link to the plugin's settings page in admin
function add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page="custom-network-settings">' . __( 'Settings', 'custom-network-settings' ) . '</a>';
    array_push( $links, $settings_link );
    return $links;
}

$filter_name = "plugin_action_links_" . plugin_basename( __FILE__ );
add_filter( $filter_name, 'add_settings_link' );

?>
