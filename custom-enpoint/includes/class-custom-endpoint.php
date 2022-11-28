<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://customendpoint.com
 * @since      3.0
 *
 * @package    Cusotmendpoint
 * @subpackage Cusotmendpoint/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 */
class Custom_Endpoint {

    const USERDATA_API_BASE = 'https://jsonplaceholder.typicode.com';

    /**     * The unique identifier of this plugin. */
    protected $inspyde;

    /*     * * The current version of the plugin */
    protected $version;
    protected $customendpoint;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, and set the hooks for the admin area and the public-facing
     * side of the site.
     */
    public function __construct() {
        $this->load_dependencies();

        add_action('wp_enqueue_scripts', array($this, 'print_script'));
        add_action('template_include', array($this, 'redirectTemplate'));
        add_filter('query_vars', array($this, 'getQueryvar'));
        add_action('init', array($this, 'setRewriteRule'));
        add_action('wp_ajax_fetch_user_details', array($this, 'fetch_user_details_callback'));
        add_action('wp_ajax_nopriv_fetch_user_details', array($this, 'fetch_user_details_callback'));

        $this->customendpoint = get_option('inspyde_custom_endpoint');
    }

    /*     * * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin: */

    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-custom-endpoint-admin.php';
    }

    public function print_script() {
        wp_enqueue_style('inspyde-user-table-css', CUSTOM_ENDPOINT_USER_PLUGIN_URL . '/assets/css/table.css', 5645645, true);
        wp_enqueue_script(array('jquery'));
        wp_enqueue_script('userlisting-script', CUSTOM_ENDPOINT_USER_PLUGIN_URL . '/assets/js/userlist.js', array(), $this->version, true);
        wp_localize_script('userlisting-script', 'inspyde_user_listing_ajax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('_wpnonce')
        ));
    }
    
}

$Custom_Endpoint = new Custom_Endpoint();
