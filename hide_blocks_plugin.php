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

        // $main_initial_value = array( 'array_text' => 'core/table, core/shortcode');
        // add_site_option($this->main_blocks_settings_slug, false);

        //add_site_option($this->main_blocks_settings_slug . '-sites', [] );
        //update_site_option($this->main_blocks_settings_slug . '-sites', [] );
        add_action( 'network_admin_menu', array( $this, 'add_submenu' ) );   
        add_action('network_admin_edit_' . $this->main_blocks_settings_slug . '-update', array( $this, 'updateNetworkSettings' ) );
        
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
            __( 'Blocks to Display in Block Inserter', 'multisite-settings' ),
            array( $this, 'add_instructions' ),
            $this->main_blocks_settings_slug
        );

        // callback function for add_settings_field
        function multisite_settings_checkbox_callback( $args ) {
            
            $main_option = get_site_option( 'blocks-settings-main-sites' );
            debug_to_console($main_option);
            // $current_option = $main_option[ $args['index'] ];
            // debug_to_console($current_option);

            // if current_option is checked
            // $checkbox = '';
            // if( isset( $current_option['checkbox'] ) ) {
                
            //     debug_to_console('multisite-settings_checkbox_' . $args['label'] . ' was checked');
            //     $checkbox = esc_html( $current_option['checkbox'] );
            // }

            $checkboxes_field = isset( $main_option['blocks-settings-main-sites'] )
                ? (array) $main_option['blocks-settings-main-sites'] : [];

            $html = '<input type="checkbox" 
                name="blocks-settings-main-sites[checkboxes_field][]"' . checked( in_array($args['label'], $checkboxes_field ), 1 ) . 
                'value="' . $args['label'] . '"';
                // id="multisite_settings_checkbox_' . $args['index'] .'" 
                // name="multisite_settings_checkbox[checkbox]" value="1"' . checked( 1, $checkbox, false ) . '/>';
            $html .= '&nbsp;';
            $html .= '<label for="blocks-settings-main-sites[checkboxes_field][]">' . $args['label'] . '</label>';   
            
            echo $html;
        }
    
        // Register new option for each block in list of all blocks available
        // Add a settings field for each one and check to see (in callback) 
        // if checked already in database
        $main_blocks_registry = get_all_blocks();

        
        foreach( $main_blocks_registry as $key=>$value ) {
            // debug_to_console($value);
            add_settings_field(
                'multisite-settings_checkbox_' . $key,   // id of field
                __( '', 'multisite-settings' ),         // title of field to display
                'multisite_settings_checkbox_callback', // callback function
                $this->main_blocks_settings_slug,       // page to display it on
                'main-blocks-section',                  // section on page to display in
                [
                    'index' => $key,
                    'label' => $value
                ]
            );
            // debug_to_console( 'multisite-settings_checkbox_' . $key );
        }        
        register_setting( 'main-blocks-section', $this->main_blocks_settings_slug . '-sites' );
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

                <form method="post" action="<?php echo add_query_arg( 'action', $this->main_blocks_settings_slug . '-update', 'edit.php' ) ?>">
                    <?php
                        settings_fields( 'main-blocks-section' );
                        do_settings_sections( $this->main_blocks_settings_slug );
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
    public function updateNetworkSettings() {
        debug_to_console('Made it to updateNetworkSettings');
        // check_admin_referer( $this->main_blocks_settings_slug .'-options');

        // get array of checked options in list,
        $options_arr = get_site_option( $this->main_blocks_settings_slug . '-sites' );
        foreach($options_arr as $option) { 

             if(isset($_POST[$this->main_blocks_settings_slug . '-sites'[$option] ])) {
                 update_site_option($this->main_blocks_settings_slug . '-sites'[$option], $_POST[$this->main_blocks_settings_slug . '-sites'[$option] ]);
             } else {
                update_site_option($this->main_blocks_settings_slug . '-sites'[$option], '');
            } 
        }   
        // if (isset($_POST[$this->main_blocks_settings_slug . '-sites'])) {
        //     update_site_option($this->main_blocks_settings_slug . '-sites', $_POST[$this->main_blocks_settings_slug . '-sites']);
        // } else {
        //     update_site_option($this->main_blocks_settings_slug . '-sites', '');
        // }
        
        // nocache_headers();

        $queryArgs = add_query_arg(
        [
            'page' => $this->main_blocks_settings_slug,
            'updated' => true,
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
