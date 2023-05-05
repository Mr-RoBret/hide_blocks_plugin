<?php 

function plugin_scripts() {
    
    wp_enqueue_script(
        'hide_variation_blocks',
        HIDEBLOCKS_URL . 'js/hide_variation_blocks.js',
        array( 'jquery', 'wp-blocks', 'wp-dom', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
        '1.0.0',
        true
    );
}

// function stolaf_deny_variation_blocks(){

//     wp_enqueue_script(
//         'hide_variation_blocks',
//         HIDEBLOCKS_URL . 'js/hide_variation_blocks.js',
//         array( 'jquery', 'wp-blocks', 'wp-dom', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
//         '1.0.0',
//         true
//     );
// }

add_action( 'admin_enqueue_scripts', 'plugin_scripts' );
// add_action( 'enqueue_block_editor_assets', 'stolaf_deny_variation_blocks' );


?>