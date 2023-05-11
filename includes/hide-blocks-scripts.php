<?php 

// function plugin_scripts() {
    function my_enqueue_script() {
        global $pagenow;
        
        if ( is_admin() && $pagenow === 'post.php' ) {
            write_to_console($pagenow);

            wp_enqueue_script( 
                'hide_variation_blocks', 
                HIDEBLOCKS_URL . 'js/hide_variation_blocks.js', __FILE__ , 
                array( 'wp-blocks' ), '1.0', true );
                wp_script_add_data( 'my-script', 'groups', array( 'wp-blocks' ) );
                wp_script_add_data( 'my-script', 'position', 10 
            );
        }
    }

    add_action( 'admin_enqueue_scripts', 'my_enqueue_script' );


function inserter_scripts() {
        
    $inserter_dependencies = array( 'jquery', 'wp-blocks', 'wp-dom-ready' );
        
    wp_enqueue_script(
        'get_show_in_inserter', 
        HIDEBLOCKS_URL . 'js/get_show_in_inserter.js', __FILE__ , 
        $inserter_dependencies, 
        1, 
        false 
        );
    }

add_action( 'enqueue_block_editor_assets', 'inserter_scripts' );


/**
 * Simple helper to debug to the console
 *
 * @param $data object, array, string $data
 * @param $context string  Optional a description.
 *
 * @return string
 */
function write_to_console($data, $context = 'Debug in Console') {

    // Buffering to solve problems frameworks, like header() in this and not a solid return.
    ob_start();

    $output  = 'console.info(\'' . $context . ':\');';
    $output .= 'console.log(' . json_encode($data) . ');';
    $output  = sprintf('<script>%s</script>', $output);

    echo $output;

}

?>