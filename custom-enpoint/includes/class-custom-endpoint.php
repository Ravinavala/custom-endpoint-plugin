<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://inspyde.com
 * @since      3.0
 *
 * @package    Inspyde
 * @subpackage Inspyde/includes
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
    protected $customendpoit;

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
            /*Check if file time if it's more than 6 hours old delete and regeenrate new file */
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

    /* Ajax call back function, check for userid
     * and return user data */

    public function fetch_user_details_callback() {
        check_ajax_referer('_wpnonce', 'security');
        $output = "";
        if (!empty($_POST)) {
            $userId = $_POST['userid'];
            $user_detail = $this->getUsers($userId);
            if ($user_detail) {
                $output .= '<div class="user_detail_info">';
                $output .= '<h4> User Id:  ' . $userId . '</h4>';
                $output .= '<div class="user_heading">
                    <div class="table-left">
                        <label>Name:</label>
                        <label>Username:</label>
                        <label>Email: </label>
                        <label>Address:</label>
                        <label>City:</label>
                        <label>Zipcode:</label>
                        <label>Geo:</label>
                        <label>Phone: </label>
                        <label>Website:</label>
                        <label>Company:</label>
                        <label>Company Phrase:</label>
                        <label>Company Bs:</label>
                    </div>';
                $output .= '<div class="table-right"> 
                    <label>' . $user_detail['name'] . '</label>
                    <label>' . $user_detail['username'] . '</label>
                    <label>' . $user_detail['email'] . '</label>
                    <label>' . $user_detail['address']['street'] . ', ' . $user_detail['address']['suite'] . '</label>
                    <label>' . $user_detail['address']['city'] . ' </label>
                    <label>' . $user_detail['address']['zipcode'] . ' </label>
                    <label>lat: ' . $user_detail['address']['geo']['lng'] . ' lng: ' . $user_detail['address']['geo']['lng'] . ' </label>
                    <label>' . $user_detail['phone'] . ' </label>
                    <label>' . $user_detail['website'] . ' </label>
                    <label>' . $user_detail['company']['name'] . ' </label>
                    <label>' . $user_detail['company']['catchPhrase'] . ' </label>
                    <label>' . $user_detail['company']['bs'] . ' </label>
                </div>
                </div>
            </div>';
            } else {
                $output .= '<div calss="error">Something went wrong! can you please try again later</div>';
            }
        } else {
            $output .= '<div calss="error">Something went wrong! can you please try again later</div>';
        }
        echo $output;
        exit();
    }

}

$Custom_Endpoint = new Custom_Endpoint();
