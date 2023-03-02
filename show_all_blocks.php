<?php 

function populate_list_script() {

    wp_enqueue_script(
        'show-all-blocks-admin',
        HIDEBLOCKS_URL . 'show_all_blocks_admin.js',
        array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
        time()
    );
}

add_action( 'admin_enqueue_scripts', 'populate_list_script', 100 );

?>