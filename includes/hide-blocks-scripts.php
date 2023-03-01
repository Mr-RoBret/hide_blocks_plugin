<?php 

function hide_blocks_scripts() {

    wp_enqueue_script(
        'hide-blocks-admin',
        HIDEBLOCKS_URL . 'hide_blocks_admin.js',
        array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
        time()
    );
}

add_action( 'admin_enqueue_scripts', 'hide_blocks_scripts', 100 );

?>