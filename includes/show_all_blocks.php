<?php 

function hide_blocks_scripts() {
 global $pagenow;

	if ($pagenow != 'hide-blocks.php') {
		return;
	}
    wp_enqueue_script(
        'hide-main-blocks-admin',
        HIDEBLOCKS_URL . 'includes/hide_main_blocks.js',
        array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-i18n', 'wp-edit-post' ),
        time()
    );
}

add_action( 'admin_enqueue_scripts', 'hide_blocks_scripts', 100 );

?>