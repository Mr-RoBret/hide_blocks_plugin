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
include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-fields.php' );

// callback function to call markup for settings page
function blocks_settings_page() {
    if ( !current_user_can('manage_options') ) {
        return;
    }
    include( __DIR__ . '/templates/admin/settings-page.php' );
}

// add settings page to menu
function add_settings_page() {
    add_menu_page(
        'Hide Blocks Plugin',
        'Hide Blocks',
        'manage_options',
        'hide-blocks',
        'blocks_settings_page', // callback to display plugin on page ('hide-blocks' slug)
        'dashicons-hidden',
        100
    );
}


/*** ACTIONS ***/
add_action( 'admin_menu', 'add_settings_page' );

/*** FILTERS ***/
// Add a link to the plugin's settings page in admin
function add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=hide-blocks">' . __( 'Settings', 'hideblocks' ) . '</a>';
    array_push( $links, $settings_link );
    return $links;
}

$filter_name = "plugin_action_links_" . plugin_basename( __FILE__ );
add_filter( $filter_name, 'add_settings_link' );

?>
