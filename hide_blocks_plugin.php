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

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

define( 'HIDEBLOCKS_URL', plugin_dir_url( __FILE__ ) );

include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-scripts.php' );

/*** CALLBACK FUNCTIONS ***/

// function to call markup for settings page
function hide_blocks_settings_page() {
    if ( !current_user_can('manage_options') ) {
        return;
    }
    include( __DIR__ . '/templates/admin/settings-page.php' );
}
function hide_blocks_options() {
    $available_blocks = getBlockVariations();
    foreach ( $available_blocks as $block ) {
        add_option( 'hide_blocks_option', ''); // add option somehow
        echo '<p>block here</p>';
    }
}

/*** ACTION FUNCTIONS ***/

// add settings page to menu
function add_settings_page() {
    add_menu_page(
        'Hide Blocks Plugin',
        'Hide Blocks',
        'manage_options',
        'hide-blocks',
        'hide_blocks_settings_page', // callback to display plugin on page ('hide-blocks' slug)
        'dashicons-hidden',
        100
    );
}

// add section to settings page
function hide_blocks_settings() {
    add_settings_section(
        // unique identifier for section
        'select_blocks_section',
        // section title
        __( 'Select Blocks Section', 'hideblocks' ),
        // Callback for optional description
        'hide_blocks_options',
        // Admin page to add section to
        'hide-blocks'
    );

    register_setting(
        'hide_blocks_settings', 
        'hide_blocks_settings'
    );
}

/*** ACTIONS ***/

add_action( 'admin_menu', 'add_settings_page' );
add_action( 'admin_init', 'hide_blocks_settings' );

/*** FILTERS ***/

// Add a link to the plugin's settings page in admin
function add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=hide-blocks">' . __( 'Settings', 'hideblocks' ) . '</a>';
    array_push( $links, $settings_link );
    return $links;
}

$filter_name = "plugin_action_links_" . plugin_basename( __FILE__ );
add_filter( $filter_name, 'add_settings_link' );

// include( plugin_dir_path ( 'add_js' ) . 'includes/js_function.php');
?>
