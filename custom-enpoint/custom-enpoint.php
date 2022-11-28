<?php
/*
  Plugin Name: Custom Endpoint User listing
  Plugin URI: https://www.simform.com/
  Description: This is a plugins to create custom edpoint, dispaly user lsit from 3rd party API with custom endpoint, generate cache with data to reduce API calls.
  Author: Ravina Vala
  Version: 1.0
  Author URI: https://www.simform.com/
  Text Domain: custom-endpoint-user-list
 */

$PLUGINVERSION = '1.0.0';

if (!defined('CUSTOM_ENDPOINT_USER_LISTING_VERSION')) {
    define('CUSTOM_ENDPOINT_USER_LISTING_VERSION', 'inspyde-user-list');
}

if (!defined('CUSTOM_ENDPOINT_USER_PLUGIN_URL')) {
    define('CUSTOM_ENDPOINT_USER_PLUGIN_URL', plugin_dir_url(__FILE__));
}

if (!defined('CUSTOM_ENDPOINT_USER_PLUGIN_PATH')) {
    define('CUSTOM_ENDPOINT_USER_PLUGIN_PATH', plugin_dir_path(__FILE__));
}

if (!defined('CUSTOM_ENDPOINT_USER_PLUGIN_BASENAME')) {
    define('CUSTOM_ENDPOINT_USER_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

define('CUSTOM_ENDPOINT_USER_INCLUDE_PATH', CUSTOM_ENDPOINT_USER_PLUGIN_PATH);
// Define admin html folder Path
define('CUSTOM_ENDOPOINT_USER_INCLUDE_ADMIN_HTML_PATH', CUSTOM_ENDPOINT_USER_INCLUDE_PATH . 'admin/html/');

/**
 * The code that runs during plugin activation (but not during updates).
 */
function activate_custom_endpoint()
{
    if (version_compare(phpversion(), '5.4', '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('Requires PHP version 5.4 or higher. Plugin was deactivated.');
    }
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_custom_endpoint() {
    delete_option('inspyde_custom_endpoint');
}

register_activation_hook(__FILE__, 'activate_custom_endpoint');
register_deactivation_hook(__FILE__, 'deactivate_custom_endpoint');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require plugin_dir_path(__FILE__) . 'includes/class-custom-endpoint.php';

$admin = new Custom_Endpoint_Admin();