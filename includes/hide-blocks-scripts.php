<?php 

function plugin_scripts() {
    
    wp_enqueue_script(
        'get_variation_blocks',
        HIDEBLOCKS_URL . 'js/hide_variation_blocks.js',
        array( 'jquery', 'wp-blocks', 'wp-dom', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
        '1.0.0',
        true
    );
    
    // wp_enqueue_script(
    //     'hide_embed_blocks',
    //     HIDEBLOCKS_URL . 'hide_embed_blocks.js',
    //     array( 'wp-blocks', 'wp-dom', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
    //     '1.0.0',
    //     true
    // );

    // $nonce = wp_create_nonce( 'wp_rest' );

    // wp_add_inline_script( 
    //     'hide_embed_blocks', 
    //     'const BLOCKSDATA = ' . json_encode( array( 
    //         'ajaxUrl' => admin_url( 'admin-ajax.php' ),
    //         'blocks_array' => ['core/calendar', 'core/comments', 'core/post-date'],
    //         'nonce' => $nonce
    //     )), 'before' 
    // );

}

add_action( 'admin_enqueue_scripts', 'plugin_scripts' );

?>