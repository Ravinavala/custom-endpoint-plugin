<?php

class Custom_Endpoint_Admin
{

    /**
     * The ID of this plugin.
     *
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct()
    {   
        $this->ensure_custom_endpoint();
        add_action('admin_menu', array($this, 'custom_admin_menu'));
    }

    public function custom_admin_menu()
    {
        add_menu_page('Custom endpoint settings', 'Custom endpoint settings', 'manage_options', 'custom_enpoint_settings', array(
            $this,
            'enpoint_settings_content',
        ));
    }
    private function ensure_custom_endpoint() {
        $existing_endpoint = get_option('inspyde_custom_endpoint');
        if (empty($existing_endpoint)) {
            update_option('inspyde_custom_endpoint', 'userlist-table');
        }
        flush_rewrite_rules();
    }

    public function enpoint_settings_content()
    {
        require CUSTOM_ENDOPOINT_USER_INCLUDE_ADMIN_HTML_PATH . 'custom-enpoint-settings-admin-page.php';
    }
}
