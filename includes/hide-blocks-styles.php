<?php
    // load CSS on Blocks Plugin page
    function hide_blocks_styles( $hook ) {

        wp_register_style(
            'hide_blocks_admin',
            HIDEBLOCKS_URL . 'includes/hide-blocks-styles.php',
            [],
            time()
        );

        if ( 'toplevel_page_hide-blocks' == $hook ) {
            print_r('css should load');
            wp_enqueue_style( 'hide_blocks_admin' );
        }
    }

    add_action( 'wp_loaded', 'hide_blocks_styles', 120 );
?>