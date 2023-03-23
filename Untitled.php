<?php

namespace UConnMaintenanceMode\Admin\Settings;

/**
 * Multisite installations have a slightly different API for creating network-level settings
 */
class MultiSite {
  public function __construct()
  {
    add_site_option($this->settingsSlug, false);
    add_site_option($this->settingsSlug . '-sites', []);
    add_action('network_admin_menu', [ $this, 'maintenanceModeSettings']);
    add_action('network_admin_edit_' . $this->settingsSlug, [$this, 'updateNetworkSettings']);
  }

  /**
   * Create a network-level settings page, sections, and settings
   *
   * @return void
   */
  public function maintenanceModeSettings() {

    if (!is_super_admin()) {
      return;
    }

    $this->addSubmenuPage('settings.php', 'edit.php?action=' . $this->settingsSlug);

  }

 
  /**
   * Handle updating the network settings for the plugin.
   * It's important to note that these settings update differently than for a single site installation.
   * In particular, note the redirect at the end of the method
   *
   * @return void
   */
  public function updateNetworkSettings() {

    if (isset($_POST[$this->settingsSlug . '-hidden']) && !isset($_POST[$this->settingsSlug])  ) {
      update_site_option($this->settingsSlug, 0);
    } else {
      update_site_option($this->settingsSlug, $_POST[$this->settingsSlug]);
    }

    if (isset($_POST[$this->settingsSlug . '-sites'])) {
      update_site_option($this->settingsSlug . '-sites', $_POST[$this->settingsSlug . '-sites']);
    } else {
      update_site_option($this->settingsSlug . '-sites', []);
    }

    nocache_headers();

    $queryArgs = add_query_arg(
      [
        'page' => $this->settingsSlug,
        'updated' => true
      ],
      network_admin_url('settings.php')
    );

    wp_redirect($queryArgs);

    exit;
  }
}


/**
 * Multisite installations have a slightly different API for creating network-level settings
 */
class Settings_Page {

  protected $settings_slug = 'hide-blocks';

  public function __construct()
  {
      add_site_option($this->settings_slug, false);
      add_site_option($this->settings_slug . '-sites', []);
      add_action('network_admin_menu', [ $this, 'maintenanceModeSettings']);
      add_action('network_admin_edit_' . $this->settings_slug, [$this, 'updateNetworkSettings']);

  }
  
  /**
   * Create a network-level settings page, sections, and settings
   *
   * @return void
   */
  public function maintenanceModeSettings() {

    if (!is_super_admin()) {
      return;
    }

    $this->add_settings_page('settings.php', 'edit.php?action=' . $this->settings_slug);

  }

  // callback function to call markup for settings page
  function blocks_settings_page() {
      if ( !current_user_can('manage_network_options') ) {
          return;
      }

      include( __DIR__ . '/templates/admin/settings-page.php' );
  }

  // add settings page to menu
  function add_settings_page($settings_page, $settings_url) {
      add_submenu_page(
          $settings_page,
          __( 'Hide Blocks Plugin', 'hideblocks' ),
          __( 'Hide Blocks', 'hideblocks' ),
          'manage_network_options',
          $this->settings_slug,
          array( $this, 'create_page', $settings_url )
      );

      // add_menu_page(
      //     'Hide Blocks Plugin',
      //     'Hide Blocks',
      //     'manage_options',
      //     'hide-blocks',
      //     'blocks_settings_page', // callback to display plugin on page ('hide-blocks' slug)
      //     'dashicons-hidden',
      //     100
      // );
  }

  public function create_page($settings_url) {
      if ( isset( $_GET['updated'] ) ) : ?>
        <div id="message" class="updated notice is-dismissible">
          <p><?php esc_html_e( 'Options Saved', 'hideblocks' ); ?></p>
        </div>
      <?php endif; ?>
      <div class="wrap">
        <h1><?php echo esc_attr( get_admin_page_title() ); ?></h1>
        <form action="edit.php?action=<?php $settings_url ?>-update" method="POST">
          <?php
              settings_fields( $this->settings_slug . '-sites' );
              do_settings_sections( $this->settings_slug . '-sites' );
              submit_button();
          ?>
        </form>
      </div>
      <?php  }

  /**
   * Handle updating the network settings for the plugin.
   * It's important to note that these settings update differently than for a single site installation.
   * In particular, note the redirect at the end of the method
   *
   * @return void
   */
  public function updateNetworkSettings() {

    if (isset($_POST[$this->settings_slug . '-hidden']) && !isset($_POST[$this->settings_slug])  ) {
      update_site_option($this->settings_slug, 0);
    } else {
      update_site_option($this->settings_slug, $_POST[$this->settings_slug]);
    }

    if (isset($_POST[$this->settings_slug . '-sites'])) {
      update_site_option($this->settings_slug . '-sites', $_POST[$this->settings_slug . '-sites']);
    } else {
      update_site_option($this->settings_slug . '-sites', []);
    }

    nocache_headers();

    $queryArgs = add_query_arg(
      [
        'page' => $this->settings_slug,
        'updated' => true
      ],
      network_admin_url('settings.php')
    );

    wp_redirect($queryArgs);

    exit;
  }
}

new Settings_Page();
