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
define( 'HIDEBLOCKS_URL', plugin_dir_url( __FILE__ ) );

include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-scripts.php' );
// include( plugin_dir_path( __FILE__ ) . 'includes/hide-blocks-styles.php' );

class Settings_Page {

    /**
     * This will be used for the SubMenu URL in the settings page and to verify which variables to save.
     *
     * @var string
     */
    protected $settings_slug = 'custom-network-settings';
    protected $main_blocks_settings_slug = 'blocks-settings-main';

    /**
     * Class Constructor.
     */
    public function __construct() {

        $main_initial_value = array( 'array_text' => 'core/table, core/shortcode');
        // add_site_option($this->main_blocks_settings_slug, false);
        add_site_option($this->main_blocks_settings_slug . '-sites', [] );
        update_site_option($this->main_blocks_settings_slug, $main_initial_value );
        add_action( 'network_admin_menu', array( $this, 'add_submenu' ) );   
        add_action('network_admin_edit_' . $this->main_blocks_settings_slug, array( $this, 'updateNetworkSettings' ) );
        
        // add_site_option($this->settings_slug, false);
        // add_site_option($this->settings_slug . '-sites', []);
        
        // Register function for settings page creation.
        // add_action( 'network_admin_menu', array( $this, 'add_settings_page' ) );
        // add_action('network_admin_edit_' . $this->main_blocks_settings_slug, [$this, 'updateNetworkSettings']);
        
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

        // Register a variable and add a field to update it.
        register_setting( $this->main_blocks_settings_slug, $this->main_blocks_settings_slug . '-sites' );

        add_settings_field(
            'text_input',
            __( 'Main Blocks Array', 'multisite-settings' ),
            array( $this, 'main_markup' ), // callback.
            $this->main_blocks_settings_slug, // page.
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
        //$val = get_site_option( 'blocks-settings-main', '' );
        $options = get_site_option( $this->main_blocks_settings_slug . '-sites' );
        // $array_text = 'Place blocks array here...';

        $array_text = 'Place blocks array here...';
        if( isset( $options[ 'array_text' ] ) ) {
            $array_text = esc_html( $options['array_text'] );
        }

        debug_to_console('Made it to main_markup');

        echo '<p><strong>List of Blocks Currently Registered:</strong></p>';
        echo '<br>';
        echo '<div id="list-blocks">';
            $main_blocks_registry = get_all_blocks();
            foreach( $main_blocks_registry as $registered_block ) {
                echo '*' . $registered_block . '* ';
            }
        echo '</div><br>';
        echo '<textarea 
            id="multisite-settings_array_text"
            name="' . $this->main_blocks_settings_slug . '-sites[array_text]" 
            rows="5" 
            cols="50">' . $array_text . '</textarea>';
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
 
    debug_to_console('Made it to updateNetworkSettings');
    
    check_admin_referer( $this->main_blocks_settings_slug .'-options');

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
            // print_r( $matches[0] );
        }
    }
    // print_r( $block_names_truncated );
    return $block_names_verified;
}

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
