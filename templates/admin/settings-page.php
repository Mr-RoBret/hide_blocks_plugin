<div class="wrap">

    <h1>
        <?php esc_html_e( get_admin_page_title() ) ?>
    </h1>

    <form method="post" action="options.php">

        <!-- Display necessary hidden fields for settings -->
        <?php settings_fields( 'hide_blocks' ); ?>

        <!-- Display the settings sections for the page -->
        <?php do_settings_sections( 'hide_blocks_settings' ); ?>
        
        <!-- Default submit button -->
        <?php submit_button(); ?>
        
    </form>

</div>