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

        $this->customendpoint = get_option('inspyde_custom_endpoint');
    }

    /*     * * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin: */

    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-custom-endpoint-admin.php';
    }

    public function get_customendpoint() {
        return $this->customendpoint;
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

     /** set rewrite rule for userlist-table */
     public function setRewriterule() {
        add_rewrite_rule($this->get_customendpoint() . '/?', 'index.php?' . $this->get_customendpoint() . '=parent-xml-page', 'top');
    }

    /** Whitelist specifc query param * */
    public function getQueryvar($query_vars) {
        $query_vars[] = $this->get_customendpoint();
        return $query_vars;
    }

    /** Check query param and redirect it to template file * */
    public function redirectTemplate($template) {

        if (get_query_var($this->get_customendpoint()) == false || get_query_var($this->get_customendpoint()) == '') {
            return $template;
        }

        return CUSTOM_ENDPOINT_USER_INCLUDE_PATH . 'includes/userlist-template.php';
    }

    /* Define API url base on request Check for cache data and time if it's expired call user API */

    public function requestUserApi($apiUrl) {
        $curl = curl_init();
        $userData = "";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: 41dcec34-442e-0320-b354-fbea618cbbcd"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $userData = $response;
        }
        return $userData;
    }

     /* Define API url base on request, Checking for cache data and expiration time that set to 6 hours if it's expired user API will call and cached the data */

     public function getUsers($userId = "") {
        $cache_path = CUSTOM_ENDPOINT_USER_INCLUDE_PATH . 'cache/';
        $id = 'userdatalist';
        $apiUrl = Custom_Endpoint::USERDATA_API_BASE . "/users";
        if (!empty($userId)) {
            $id = 'user_id' . $userId;
            $apiUrl = Custom_Endpoint::USERDATA_API_BASE . "/users/$userId";
        }
        $filename = $cache_path . $id;
        if (file_exists($filename)) {
            if ((time() - 21600 < filemtime($filename))) {
                unlink($filename);
                $response_data = $this->requestUserApi($apiUrl);
                file_put_contents($filename, $response_data);
            }
            $response_data = file_get_contents($filename);
        } else {
            $response_data = $this->requestUserApi($apiUrl);
            file_put_contents($filename, $response_data);
        }
        return json_decode($response_data, true);
    }
    
}

$Custom_Endpoint = new Custom_Endpoint();
