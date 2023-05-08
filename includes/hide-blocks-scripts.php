<?php 

// function plugin_scripts() {
    
//     wp_enqueue_script(
//         'hide_variation_blocks',
//         HIDEBLOCKS_URL . 'js/hide_variation_blocks.js',
//         // array( 'jquery', 'wp-blocks', 'wp-dom', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
//         array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-site' ),
//         '1.0.0',
//         true
//     );
// }

// function stolaf_deny_variation_blocks(){

//     wp_enqueue_script(
//         'hide_variation_blocks',
//         HIDEBLOCKS_URL . 'js/hide_variation_blocks.js',
//         array( 'jquery', 'wp-blocks', 'wp-dom', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
//         '1.0.0',
//         true
//     );
// }

// add_action( 'admin_enqueue_scripts', 'plugin_scripts' );
// add_action( 'enqueue_block_editor_assets', 'plugin_scripts' );

function plugin_scripts() {
    
    $dependencies = array( 'wp-blocks', 'wp-dom-ready' );
    
    if( !function_exists( 'get_current_screen' ) ) {
        require_once(ABSPATH . 'wp-admin/includes/screen.php');
    } 
        $current_screen = get_current_screen();
        // var_dump($current_screen);
        // var_dump(get_current_screen()->id);

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

?>