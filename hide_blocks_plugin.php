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

include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-scripts.php' );
// include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-styles.php' );

class Settings_Page {

    /**
     * This will be used for the SubMenu URL in the settings page and to verify which variables to save.
     *
     * @var string
     */
    protected $main_blocks_settings_slug = 'blocks-settings-main';

    /**
     * Class Constructor.
     */
    public function __construct() {

        $main_initial_value = array( 'array_text' => 'core/table, core/shortcode');
        // add_site_option($this->main_blocks_settings_slug, false);
        add_site_option($this->main_blocks_settings_slug . '-sites', [] );
        update_site_option($this->main_blocks_settings_slug . '-sites', $main_initial_value );
        add_action( 'network_admin_menu', array( $this, 'add_submenu' ) );   
        add_action('network_admin_edit_' . $this->main_blocks_settings_slug, array( $this, 'updateNetworkSettings' ) );
        
    }
        
    public function add_submenu() {
        // If plugin settings don't exist, create them
        if( false == get_site_option( $this->main_blocks_settings_slug . '-sites' ) ) {
            add_site_option( $this->main_blocks_settings_slug . '-sites', '' );
        }
        // if( false == get_site_option( 'blocks_settings_embed' ) ) {
        //     add_site_option( 'blocks_settings_embed', '' );
        // }

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
            $this->main_blocks_settings_slug,
            array( $this, 'create_page')
        );

        // Register a new section on the page.
        add_settings_section(
            'main-blocks-section',
            __( 'Main Blocks to Hide', 'multisite-settings' ),
            array( $this, 'add_instructions' ),
            $this->main_blocks_settings_slug
        );

        // callback function for add_settings_field
        function multisite_settings_checkbox_callback( $args ) {
            $checkboxes_list = get_site_option( 'main-blocks-section' );
    
            $checkbox = '';
            if( isset( $checkboxes_list['checkbox'] ) ) {
                $checkbox = esc_html( $checkboxes_list['checkbox'] );
            }
    
            $html = '<input type="checkbox" 
                id="multisite_settings_checkbox_' . $args['label'] .'" 
                name="multisite_settings[checkbox]" value="1"' . checked( 1, $checkbox, false ) . '/>';
            $html .= '  ';
            $html .= '<label for="multisite_settings_checkbox_' . $args['label'] . '">' . $args['label'] . '</label>';   
            
            echo $html;
        }
    
        $main_blocks_registry = get_all_blocks();
        foreach( $main_blocks_registry as $key=>$value ) {

            add_settings_field(
                'multisite-settings_checkbox' . $key,
                __( '', 'multisite-settings' ),
                'multisite_settings_checkbox_callback',
                $this->main_blocks_settings_slug,
                'main-blocks-section',
                [
                    'label' => $value
                ]
            );
        }        
        register_setting( $this->main_blocks_settings_slug, $this->main_blocks_settings_slug . '-sites' );
    }
    
    /**
     * Html after the new section title.
     *
     * @return void
     */
    public function add_instructions() {
        esc_html_e( 'Please check the blocks you would like visible in the Block Inserter.', 'multisite-settings' );
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

            <form action="edit.php?action=<?php $this->main_blocks_settings_slug ?>-update" method="POST">
                <?php
                    settings_fields( $this->main_blocks_settings_slug );
                    do_settings_sections( $this->main_blocks_settings_slug );
                    submit_button();
                ?>
            </form>

        </div>
    <?php  }

    /**
   * Handle updating the network settings for the plugin.
   * It's important to note that these settings update differently than for a single site installation.
   * In particular, note the redirect at the end of the method
   *
   * @return void
   */
  public function updateNetworkSettings() {
    // debug_to_console('Made it to updateNetworkSettings');
    // check_admin_referer( $this->main_blocks_settings_slug .'-options');

    if (isset($_POST[$this->main_blocks_settings_slug . '-sites'])) {
        update_site_option($this->main_blocks_settings_slug . '-sites', $_POST[$this->main_blocks_settings_slug . '-sites']);
    } else {
        update_site_option($this->main_blocks_settings_slug . '-sites', '');
    }
    
    // nocache_headers();

    $queryArgs = add_query_arg(
      [
        'page' => $this->main_blocks_settings_slug,
        'updated' => true
      ],
      network_admin_url( 'settings.php' )
    );

    wp_safe_redirect($queryArgs);

    exit;
  }
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
