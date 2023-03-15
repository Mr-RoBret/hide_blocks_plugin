<div class="wrap">

    <h1>
        <?php esc_html_e( get_admin_page_title() ) ?>
    </h1>

    <form method="post" action="options.php">

        <!-- Display necessary (security) hidden fields for settings -->
        <?php settings_fields( 'blocks_settings_main' ); ?>
        <?php settings_fields( 'blocks_settings_embed' ); ?>

        <!-- Display the settings sections for the page -->
        <?php do_settings_sections( 'hide-blocks' ); ?>

        <!-- Default submit button -->
        <?php submit_button(); ?>

    </form>

    

</div>