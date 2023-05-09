<?php
    function hide_blocks_admin_styles( $hook_suffix ) {
        
        if( 'blocks-settings-main' !== $hook_suffix ) {
            return;
        }

        wp_register_style(
            'blocks-settings-main',
            HIDEBLOCKS_URL . 'css/plugin_styles.css', __FILE__ , 
            [], 
            '1.0.0'
        );
        wp_enqueue_style( 'blocks-settings-main' );
    }

    add_action( 'admin_enqueue_scripts', 'hide_blocks_admin_styles' );

?>