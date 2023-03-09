<div class="wrap">

    <h1>
        <?php esc_html_e( get_admin_page_title() ) ?>
    </h1>

    <form method="post" action="options.php">

        <!-- Display necessary (security) hidden fields for settings -->
        <?php settings_fields( 'blocks_settings' ); ?>

        <!-- Display the settings sections for the page -->
        <?php do_settings_sections( 'hide-blocks' ); ?>
        
        <!-- Display Options -->
        <?php //esc_html_e( get_option( 'blocks_option' ) ); ?>

        <!-- Default submit button -->
        <?php submit_button(); ?>

    </form>

    

</div>