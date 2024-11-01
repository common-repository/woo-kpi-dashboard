<?php
/*
  Plugin Name: Woo-KPI Dashboard
  Plugin URI: http://logg-out.com/en/
  Description: Identify your KPI : Visitors, Average Basket, Conversion Rate, Fidelity Rate... and raise your Company's income!
  Version: 1.0.8
  Author: Logg - Patrice Khal
  Author URI: http://logg-out.com/en/
  License: GPLv2+
  Text Domain: woo-kpi-dashboard
  Domain Path: /languages/
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//Languages loaded
add_action('plugins_loaded', 'wookpi_load_textdomain');
function wookpi_load_textdomain() {
    load_plugin_textdomain( 'woo-kpi-dashboard', false, basename(dirname(__FILE__) ) . '/languages/' );
}
//We include path for libs Google Analytics
ini_set( 'include_path', dirname(__FILE__) . '/lib/google-api-php-client-master/' );
//We define the path for files
define( 'ROOT_PATH', dirname(__FILE__) );
class WooKPI{
    // Constructor
    function __construct() {
        if ( !class_exists( 'Google_Client' ) ) {
            require_once dirname(__FILE__) . '/lib/google-api-php-client-master/src/Google/Client.php';
            require_once dirname(__FILE__) . '/lib/google-api-php-client-master/src/Google/Service/Analytics.php';
        }
        //API Tokens
        $this->client = new Google_Client();
        $this->client->setApprovalPrompt( 'force' );
        $this->client->setAccessType( 'offline' );
        $this->client->setClientId( '3325217403-fethticf8tf39lq1epcrsvj4rit7luee.apps.googleusercontent.com' );
        $this->client->setClientSecret( 'NIG4gBZm3CLSVpzZcnvpPRYL' );
        $this->client->setRedirectUri( 'urn:ietf:wg:oauth:2.0:oob' );
        $this->client->setScopes( 'https://www.googleapis.com/auth/analytics' );
        $this->client->setDeveloperKey( 'AIzaSyCrHkaso5G7zTOUrHKK3VZP_u4D2GCuA9I' );
        try{
            $this->service = new Google_Service_Analytics( $this->client );
            $this->wookpi_connect();
        }
        catch ( Google_Service_Exception $e ) {

        }
        add_action( 'admin_menu', array( $this, 'wookpi_add_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'wookpi_styles') );
        //register_activation_hook( __FILE__, array( $this, 'wookpi_install' ) );
        //register_deactivation_hook( __FILE__, array( $this, 'wookpi_uninstall' ) );
    }

    /*
      * Actions performed at loading of admin menu
      */
    function wookpi_add_menu() {
        $option = '';
        add_menu_page( 'Woo-KPI', 'Woo-KPI', 'manage_options', 'kpi-dashboard', array(
                          __CLASS__,
                         'wookpi_page_file_path'
                        ), plugins_url('images/kpi.png', __FILE__),'2.2.9');

        add_submenu_page( 'kpi-dashboard', 'Woo-KPI' . ' Dashboard', ' Dashboard', 'manage_options', 'kpi-dashboard', array(
                              __CLASS__,
                             'wookpi_page_file_path'
                            ));
        //Is Google Analytics Token set ?
        $kpi_access_token = get_option('access_token');
        if (!empty( $kpi_access_token )) {
            $option = 'Analytics';
        } else {
            $option ='<b style="color:red;">Analytics</b>';
        }

        add_submenu_page( 'kpi-dashboard', 'Woo-KPI' . ' Analytics', $option, 'manage_options', 'kpi-settings', array(
                              __CLASS__,
                             'wookpi_page_file_path'
                            ));

        $kpi_objective_january = get_option('kpi_january');
        if (!is_null( $kpi_objective_january )) {
            $option = 'Objectives';
        } else {
            $option ='<b style="color:yellow;">Objectives</b>';
        }
        add_submenu_page( 'kpi-dashboard', 'Woo-KPI' . ' Objectives', $option, 'manage_options', 'kpi-objectives', array(
            __CLASS__,
            'wookpi_page_file_path'
        ));
        //call register settings function
        add_action('admin_init', array( $this, 'register_woo_kpi_dashboard_settings' ));
    }
    function register_woo_kpi_dashboard_settings() {
        //register our settings
        register_setting( 'objectives-settings-group', 'kpi_january' );
        register_setting( 'objectives-settings-group', 'kpi_february' );
        register_setting( 'objectives-settings-group', 'kpi_march' );
        register_setting( 'objectives-settings-group', 'kpi_april' );
        register_setting( 'objectives-settings-group', 'kpi_may' );
        register_setting( 'objectives-settings-group', 'kpi_june' );
        register_setting( 'objectives-settings-group', 'kpi_july' );
        register_setting( 'objectives-settings-group', 'kpi_august' );
        register_setting( 'objectives-settings-group', 'kpi_september' );
        register_setting( 'objectives-settings-group', 'kpi_october' );
        register_setting( 'objectives-settings-group', 'kpi_november' );
        register_setting( 'objectives-settings-group', 'kpi_december' );
        register_setting( 'objectives-settings-group', 'kpi_license_key' );
    }
    static function wookpi_page_file_path() {
        $page = get_current_screen();
        if ( strpos( $page->base, 'kpi-settings' ) !== false ) {
            include( dirname(__FILE__) . '/includes/kpi-settings.php' );
        } 
        else   if ( strpos( $page->base, 'kpi-objectives' ) !== false ) {
            include( dirname(__FILE__) . '/includes/kpi-objectives.php' );
        } else {
            include( dirname(__FILE__) . '/includes/kpi-dashboard.php' );
        }
    }
    public function wookpi_settings_tabs( $current = 'authentication' ) {
        $tabs = array(
            'authentication' =>  'Google Analytics',
            'profile'       =>  'Website'
        );
        echo '<div class="left-area">';
        echo '<h2 class="nav-tab-wrapper">';
        foreach( $tabs as $tab => $name ) {
            $class = ( $tab == $current ) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=kpi-settings&tab=$tab'>$name</a>";
        }
        echo '</h2>';
    }
    public function wookpi_save_data( $access_code ) {
        update_option( 'access_code', $access_code );
        $this->wookpi_connect();
        return true;
    }
    public function wookpi_connect() {
        $kpi_access_token = get_option('access_token');
        if (! empty( $kpi_access_token )) {
            $this->client->setAccessToken( $kpi_access_token );
        } 
        else{
            $authCode = get_option( 'access_code' );
            if ( empty( $authCode ) ) return false;
            try {
                $accessToken = $this->client->authenticate( $authCode );
            }
            catch ( Exception $e ) {
                print_r($e->getMessage());
                return false;
            }
            if ( $accessToken ) {
                $this->client->setAccessToken( $accessToken );
                update_option( 'access_token', $accessToken );
                return true;
            }
            else {
                return false;
            }
        }
        $this->token = json_decode($this->client->getAccessToken());
        return true;
    }
    /**
     * Get view profiles from Google Analytics.
     */
    public function kpi_get_analytics_accounts() {
        try {
            if( get_option( 'access_token' ) !='' ) {
                $profiles = $this->service->management_profiles->listManagementProfiles( "~all", "~all" );
                return $profiles;
            }
            else {
                echo '<br /><p class="description">' . __( 'You must be <a href="?page=kpi-settings">connected to Google Analytics</a> to choose the profile of your ecommerce.', 'woo-kpi-dashboard' ) . '</p>';
            }

        }
        catch (Exception $e) {
            die('An error occured: ' . $e->getMessage() . '\n');
        }
    }
    /*
     * Datas grabbed from Google Analytics
     */
    
    public function wookpi_get_analytics_dashboard($metrics, $startDate, $endDate, $dimensions = false, $sort = false, $filter = false, $limit = false){
        try{

            $this->service = new Google_Service_Analytics($this->client);
            $params        = array();

            if ($dimensions){
                $params['dimensions'] = $dimensions;
            }
            if ($sort){
                $params['sort'] = $sort;
            }
            if ($filter){
                $params['filters'] = $filter;
            }
            if ($limit){
                $params['max-results'] = $limit;
            }

            $profile_id = get_option("pt_webprofile_dashboard");
            if (!$profile_id){
                return false;
            }
            return $this->service->data_ga->get('ga:' . $profile_id, $startDate, $endDate, $metrics, $params);
        }
        catch ( Google_Service_Exception $e ) {

            // Show error message only for logged in users.
            if ( is_user_logged_in() ) echo $e->getMessage();
        }
    }
    public function wookpi_get_sales($start_date = null, $end_date = null, $return = 'CA') {
        global $wpdb;
        //number of sales
        $order_totals = apply_filters( 'woocommerce_reports_sales_overview_order_totals', $wpdb->get_row( "
          SELECT COUNT(posts.ID) AS total_orders FROM {$wpdb->posts} AS posts
          LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
          WHERE meta.meta_key = '_order_total'
        AND posts.post_type = 'shop_order'
        AND posts.post_date >= '".$start_date."' AND posts.post_date < '".$end_date."'
            AND posts.post_status != 'wc-failed'
            AND posts.post_status != 'wc-refunded'
            AND posts.post_status != 'wc-cancelled'
            AND posts.post_status != 'trash'
        " ) );
        return absint( $order_totals->total_orders);

    }
    public function wookpi_get_income($start_date = null, $end_date = null, $return = 'CA') {
        global $wpdb;
        //Income
        $order_totals = apply_filters( 'woocommerce_reports_sales_overview_order_totals', $wpdb->get_row( "
          SELECT SUM(meta.meta_value) AS total_sales FROM {$wpdb->posts} AS posts
          LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
          WHERE meta.meta_key = '_order_total'
        AND posts.post_type = 'shop_order'
        AND posts.post_date >= '".$start_date."' AND posts.post_date < '".$end_date."'
            AND posts.post_status != 'wc-failed'
            AND posts.post_status != 'wc-refunded'
            AND posts.post_status != 'wc-cancelled'
            AND posts.post_status != 'trash'
        " ) );
        return $order_totals->total_sales;

    }
    public function wookpi_get_tax($start_date = null, $end_date = null, $return = 'CA') {
        global $wpdb;
        //Taxes
        $taxes = apply_filters( 'woocommerce_reports_sales_overview_tax', $wpdb->get_row( "
          SELECT SUM(meta.meta_value) AS tax FROM {$wpdb->posts} AS posts
          LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
          WHERE meta.meta_key = '_order_tax'
        AND posts.post_type = 'shop_order'
        AND posts.post_date >= '".$start_date."' AND posts.post_date < '".$end_date."'
            AND posts.post_status != 'wc-failed'
            AND posts.post_status != 'wc-refunded'
            AND posts.post_status != 'wc-cancelled'
            AND posts.post_status != 'trash'
        " ) );
        //Shipping taxes
        $shipping_taxes = apply_filters( 'woocommerce_reports_sales_overview_shipping_tax', $wpdb->get_row( "
          SELECT SUM(meta.meta_value) AS shipping_tax FROM {$wpdb->posts} AS posts
          LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
          WHERE meta.meta_key = '_order_shipping_tax'
        AND posts.post_type = 'shop_order'
        AND posts.post_date >= '".$start_date."' AND posts.post_date < '".$end_date."'
            AND posts.post_status != 'wc-failed'
            AND posts.post_status != 'wc-refunded'
            AND posts.post_status != 'wc-cancelled'
            AND posts.post_status != 'trash'
        " ) );
        $tax = $taxes->tax + $shipping_taxes->shipping_tax;
        return $tax;

    }
    public function wookpi_get_shipping($start_date = null, $end_date = null, $return = 'CA') {
        global $wpdb;
        //Shipping fees
        $shipping_costs = apply_filters( 'woocommerce_reports_sales_overview_shipping_total', $wpdb->get_row( "
          SELECT SUM(meta.meta_value) AS shipping FROM {$wpdb->posts} AS posts
          LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
          WHERE meta.meta_key = '_order_shipping'
        AND posts.post_type = 'shop_order'
        AND posts.post_date >= '".$start_date."' AND posts.post_date < '".$end_date."'
            AND posts.post_status != 'wc-failed'
            AND posts.post_status != 'wc-refunded'
            AND posts.post_status != 'wc-cancelled'
            AND posts.post_status != 'trash'
        " ) );
        return $shipping_costs->shipping;
    }

    /*
     * Obtains the fidelity rate for the given period
     */
    public function wookpi_get_fidelity_rate($end_date = null) {
        global $wpdb;
        //How many customers in the quarter before the end date
        $customers_totals = apply_filters( 'woocommerce_reports_sales_overview_customers', $wpdb->get_row( "
            SELECT COUNT(byuser) AS total_customers FROM (
            SELECT COUNT(DISTINCT ID) AS byuser from {$wpdb->postmeta} AS meta INNER JOIN {$wpdb->posts} AS posts ON posts.ID = meta.post_id
            where meta_key = '_customer_user' AND posts.post_type = 'shop_order'
            AND posts.post_status != 'wc-failed'
            AND posts.post_status != 'wc-refunded'
            AND posts.post_status != 'wc-cancelled'
            AND posts.post_status != 'trash'
            AND posts.post_date >=  '".$end_date."' - INTERVAL 3 MONTH
            AND posts.post_date <  '".$end_date."'
            group by meta_value) AS counting

        " ) );
        //Clients with more than 1 order in this quarter
        $fidel_customers = apply_filters( 'woocommerce_reports_sales_overview_fidelity', $wpdb->get_row( "
          SELECT COUNT(byuser) AS total_fidelity FROM (
            SELECT COUNT(DISTINCT ID) AS byuser from {$wpdb->postmeta} AS meta INNER JOIN {$wpdb->posts} AS posts ON posts.ID = meta.post_id
            where meta_key = '_customer_user' AND posts.post_type = 'shop_order'
            AND posts.post_status != 'wc-failed'
            AND posts.post_status != 'wc-refunded'
            AND posts.post_status != 'wc-cancelled'
            AND posts.post_status != 'trash'
            AND posts.post_date >=  '".$end_date."' - INTERVAL 3 MONTH
            AND posts.post_date <  '".$end_date."'
            group by meta_value HAVING 1 < count(DISTINCT ID)) AS counting

        " ) );
        //We calculate the fidelity rate
        $customers = absint( $customers_totals->total_customers);
        $fidels = absint( $fidel_customers->total_fidelity);
        if ($customers > 0 && $fidels > 0) {
            $fidelity_rate = round($fidels / $customers * 100, 2);
        } else {
            $fidelity_rate = 0;
        }
        return $fidelity_rate;
    }

    /**
     * Styles and Scripts
     */
    public function wookpi_styles( $page ) {

        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style( 'wp-kpi-jquery', plugins_url('css/jquery-ui.css', __FILE__));
        wp_enqueue_script( 'wp-kpi-js', plugins_url('js/wookpi.js', __FILE__));
        wp_enqueue_style( 'wp-kpi-style', plugins_url('css/wp-kpi-style.css', __FILE__));
        wp_enqueue_script( 'wp-kpi-plot', plugins_url('js/jquery.jqplot.min.js', __FILE__));
        wp_enqueue_script( 'wp-kpi-plot-bar', plugins_url('js/plugins/jqplot.barRenderer.js', __FILE__));
        wp_enqueue_script( 'wp-kpi-plot-highlighter', plugins_url('js/plugins/jqplot.highlighter.js', __FILE__));
        wp_enqueue_script( 'wp-kpi-plot-cursor', plugins_url('js/plugins/jqplot.cursor.js', __FILE__));
        wp_enqueue_script( 'wp-kpi-plot-pointLabels', plugins_url('js/plugins/jqplot.pointLabels.js', __FILE__));
        wp_enqueue_script( 'wp-kpi-plot-canvasText', plugins_url('js/plugins/jqplot.canvasTextRenderer.js', __FILE__));
        wp_enqueue_script( 'wp-kpi-plot-canvasAxis', plugins_url('js/plugins/jqplot.canvasAxisLabelRenderer.js', __FILE__));
        wp_enqueue_style( 'wp-kpi-plot-style', plugins_url('js/jquery.jqplot.min.css', __FILE__));
    }
    /*
     * Actions performed on plugin's install
     */
    //function wookpi_install() {
        //Add if necessary
    //}

    /*
     * Actions performed on plungin's uninstall
     */
    //function wookpi_uninstall() {
        //Add if necessary
    //}

    //Calculates the number of days in the given month
    public function wookpi_numberOfDaysInMonth($month, $year)
    {
        // calculate number of days in a month
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }
}
new WooKPI();
?>