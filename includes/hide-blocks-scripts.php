<?php 

function plugin_scripts() {

    wp_enqueue_script(
        'hide-blocks-admin',
        HIDEBLOCKS_URL . 'hide_embed_blocks.js',
        array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
        time()
    );
}

add_action( 'admin_enqueue_scripts', 'plugin_scripts', 90 );

?>