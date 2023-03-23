<div class="wrap">

    <h1>
        <?php esc_html_e( get_admin_page_title() ) ?>
    </h1>

    <!-- <form method="POST" action="options.php"> -->
    <form method="POST" action="edit.php?action=hide-blocks">
    
        <!-- Display necessary (security) hidden fields for settings -->
        <?php settings_fields( 'settings_both_blocks' ); ?>

        <!-- Display the settings sections for the page -->
        <?php do_settings_sections( 'hide-blocks' ); ?>

        <!-- Default submit button -->
        <?php submit_button(); ?>

    </form>

</div>

<?php 



?>