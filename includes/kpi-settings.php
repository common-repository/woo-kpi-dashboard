<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
  $woo_kpi = new WooKPI();

  if (! function_exists( 'curl_init' ) ) {
      esc_html_e('This plugin requires the CURL PHP extension');
    return false;
  }

  if (! function_exists( 'json_decode' ) ) {
    esc_html_e('This plugin requires the JSON PHP extension');
    return false;
  }

  if (! function_exists( 'http_build_query' )) {
    esc_html_e('This plugin requires http_build_query()');
    return false;
  }


  $url = http_build_query( array(
                                'next'          =>  admin_url('admin.php?page=kpi-settings'),
                                'scope'         =>  'https://www.googleapis.com/auth/analytics',
                                'response_type' =>  'code',
                                'redirect_uri'  =>  'urn:ietf:wg:oauth:2.0:oob',
                                'client_id'     =>  '3325217403-fethticf8tf39lq1epcrsvj4rit7luee.apps.googleusercontent.com'
                                )
                          );

  // Save access code if current user is admin

  if ( isset( $_POST["save_code"]) and isset($_POST["access_code"]) && current_user_can('administrator') ) {
    if( $woo_kpi->wookpi_save_data( $_POST["access_code"] )){
        $update_message = '<div id="setting-error-settings_updated" class="updated settings-error below-h2"><p><strong>Access code saved.</strong></p></div>';
    }
  }

// Clear Authorization and other data
  if (isset($_POST[ "clear" ]) && current_user_can('administrator') ) {

    delete_option( 'access_code' );
    delete_option('access_token');
    $update_message = '<div id="setting-error-settings_updated" class="updated settings-error below-h2"> 
                        <p><strong>Authentication Cleared login again.</strong></p></div>';
  }
    //TODO : languages
    // Saving Profiles
  if (isset($_POST[ 'save_profile' ]) && current_user_can('administrator') ) {

    update_option( 'pt_webprofile_dashboard', $_POST[ 'webprofile_dashboard' ] );

    $update_message = '<div id="setting-error-settings_updated" class="updated settings-error below-h2"> 
                        <p><strong>Your Google Analytics Profile Saved.</strong></p></div>';
  }

?>

<div class="wrap">
  <h2 class='wookpi-title'><span id='icon-options-general' class='analytics-options'><img src="<?php echo plugins_url('woo-kpi-dashboard/images/kpi-logo.png');?>" alt=""></span>
    <?php _e( 'Woo-KPI Dashboard Pro', 'woo-kpi-dashboard'); ?>
    <?php _e( '<br /><span style="font-size:0.8;font-style:italic;">Google Analytics Integration</span>', 'woo-kpi-dashboard' ); ?>
  </h2>
  
  <?php
  if (isset($update_message)) echo $update_message;
  
  if ( isset ( $_GET['tab'] ) ) $woo_kpi->wookpi_settings_tabs($_GET['tab']);
  else $woo_kpi->wookpi_settings_tabs( 'authentication' );

  if ( isset ( $_GET['tab'] ) ) 
    $tab = $_GET['tab']; 
  else
    $tab = 'authentication';

    if (current_user_can('administrator'))  {

  // Authentication Tab section
  if( $tab == 'authentication' ) {
  ?>

  <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post" name="settings_form" id="settings_form">
    <table width="1004" class="form-table">
      <tbody>
      <?php if( get_option( 'access_token' ) ) { ?>
        <tr>
          <p><?php _e( 'Do you want to re-authenticate ? Click reset button and get your new Access code.', 'woo-kpi-dashboard' ) ?><p>
          
        </tr>
        <tr>
          <th><?php esc_html_e( 'Clear Authentication', 'woo-kpi-dashboard' ); ?></th>
          <td><input type="submit" class="button-primary" value="Reset" name="clear" /></td>
        </tr>
      <?php 
      }
      else { ?>
        <tr>
          <th width="115"><?php esc_html_e( 'Authentication:', 'woo-kpi-dashboard' )?></th>
              <td width="877">
                    <a target="_blank" href="javascript:void(0);" onclick="window.open('https://accounts.google.com/o/oauth2/auth?<?php echo $url ?>','activate','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');">
            <?php _e( 'Click here to authenticate', 'woo-kpi-dashboard' ) ?></a>
              </td>
        </tr>
        <tr>
              <th><?php esc_html_e('Your Access Code:', 'woo-kpi-dashboard')?> </th>
              <td>
                <input type="text" name="access_code" value="" style="width:450px;"/>
              </td>
        </tr>
        <tr>
          <th></th>
          <td>
            <p class="submit">
              <input type="submit" class="button-primary" value = "Save Changes" name = "save_code" />
            </p>
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </form>
  <?php
  } // endif
// Choose profiles for dashboard and posts at front/back.
if( $tab == 'profile' ){
  $profiles = $woo_kpi->kpi_get_analytics_accounts();
  if( isset( $profiles ) ) { ?>
    <p><?php esc_html_e( 'Select profile for dashboard data.', 'woo-kpi-dashboard' ); ?></p>

    <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
      <table width="1004" class="form-table">
        <tbody>
          <tr>
            <th width="115"><?php esc_html_e( 'Dashboard :', 'woo-kpi-dashboard' );?></th>
            <td width="877">
                <select name='webprofile_dashboard' id='webprofile-dashboard'>
                  <?php foreach ($profiles->items as $profile) { ?>
                  <option value="<?php echo $profile[ 'id' ];?>"
                              <?php selected( $profile[ 'id' ], get_option( 'pt_webprofile_dashboard' )); ?>
                              >
                              <?php echo $profile[ 'websiteUrl' ];?> - <?php echo $profile[ 'name' ];?>
                  </option>
                  <?php } ?>
                </select>
            </td>
          </tr>
          <tr>
            <th></th>
            <td>
              <p class="submit">
                <input type="submit" name="save_profile" value="Save Changes" class="button-primary">
              </p>
            </td>
          </tr>
          <?php } ?>
      </tbody>
    </table>
  </form>
<?php } 
    } else {
        //If user is not administrator
        ?>
       <div id="setting-error-settings_updated" class="updated settings-error below-h2"><p><strong><?php _e('Sorry, only administrators can change this settings.', 'woo-kpi-dashboard'); ?></strong></p> </div>
    
    <?php
        
        
    }
    ?>

</div>
