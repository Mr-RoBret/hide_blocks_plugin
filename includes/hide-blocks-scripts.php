<?php 

function plugin_scripts() {
    
    $dependencies = array( 'wp-blocks', 'wp-dom-ready' );
    
    if( !function_exists( 'get_current_screen' ) ) {
        require_once(ABSPATH . 'wp-admin/includes/screen.php');
    } 

        if( get_current_screen()->id == 'page' ) {
            // var_dump($current_screen);
            array_push($dependencies, 'page');
        } elseif( get_current_screen()->id == 'widgets' ) {
            array_push($dependencies, 'wp-edit-widgets');
        } else {
            array_push($dependencies, 'wp-edit-post');
        }
    
    wp_enqueue_script(
        'hide_variation_blocks', 
        HIDEBLOCKS_URL . 'js/hide_variation_blocks.js', __FILE__ , 
        $dependencies, 
        1, 
        false 
    );
}

add_action( 'enqueue_block_editor_assets', 'plugin_scripts' );


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

add_action( 'enqueue_block_editor_assets', 'inserter_scripts' )

?>