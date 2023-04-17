<?php 

function plugin_scripts() {

    wp_enqueue_script(
        'hide_main_blocks',
        // '/app/public/wp-content/plugins/hide_blocks_plugin/hide_main_blocks.js',
        HIDEBLOCKS_URL . 'hide_main_blocks.js',
        array( 'wp-blocks', 'wp-dom', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
        '1.0.0'
    );
    
    wp_enqueue_script(
        'hide_embed_blocks',
        // '/app/public/wp-content/plugins/hide_blocks_plugin/hide_embed_blocks.js',
        HIDEBLOCKS_URL . 'hide_embed_blocks.js',
        array( 'wp-blocks', 'wp-dom', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
        '1.0.0'
    );

}

add_action( 'admin_enqueue_scripts', 'plugin_scripts' );

// function wp_loaded_scripts() {
//     wp_enqueue_script(
//         'style_embed_blocks',
//         HIDEBLOCKS_URL . 'style_embed_blocks.js',
//         array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
//         time()
//     );
// }

// add_action( 'admin_enqueue_scripts', 'wp_loaded_scripts', 75 );

?>