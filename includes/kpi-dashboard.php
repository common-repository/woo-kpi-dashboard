<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$woo_kpi = new WooKPI();
//Default dates
$month          =   date('m');
$year           =   date('Y');
$end_month      =   $month * 1 + 1;
$end_year = $year;
if ($end_month < 10) {
  $end_month = '0'.$end_month;
} else if ($end_month == 13) {
  $end_month = '01';
  $end_year = $year * 1 + 1;
}
//If the user choose another month
if (isset($_POST['monthStats'])) {
  $monthStats     = sanitize_text_field( wp_unslash( $_POST['monthStats'] ) );
  $monthYearStats = explode('-',$monthStats);
  $month          = $monthYearStats[1];
  $year           = $monthYearStats[0];
  $end_month      =   $month * 1 + 1;
  $end_year = $year;
  if ($end_month < 10) {
    $end_month = '0'.$end_month;
  } else if ($end_month == 13) {
    $end_month = '01';
    $end_year = $year * 1 + 1;
  }
}
$start_date     =  $year."-".$month."-01";
$end_date       =  $end_year."-".$end_month."-01";
?>
<div class="wrap">
  <h2 class='wookpi-title'><span id='icon-options-general' class='analytics-options'><img src="<?php echo plugins_url('woo-kpi-dashboard/images/kpi-logo.png');?>" alt="Woo-KPI Dashboard"></span><?php _e( 'Woo-KPI Dashboard', 'woo-kpi-dashboard' ); ?><?php _e( '<br /><span style="font-size:0.8;font-style:italic;">by <a href="http://logg-out.com/en">Logg</a></span>', 'woo-kpi-dashboard' ); ?>
  </h2>
  <?php
  $acces_token  = get_option( "access_code" );
  if ( isset($acces_token) && $acces_token != '' && class_exists( 'WooCommerce' ) && is_admin()) {
  ?>
    <div id="titleChart"><span style="color:green;"><?php _e('Objectives', 'woo-kpi-dashboard'); ?></span> vs <span style="color:#00adce;"><?php _e('Reality', 'woo-kpi-dashboard'); ?></span> <?php printf( __('for %s', 'woo-kpi-dashboard'), date('Y'));?></div>
    <div id="kpi-chartContainer" style="height: 300px; width: 100%;"></div>
        <div style="width:100%;text-align:right;"><span style="color:red;"><?php _e('conversion Rate', 'woocommerce-kpi');?></span>-<span style="color:yellow;background:#000;"><?php _e('Visitors', 'woo-kpi-dashboard');?></span>-<span style="color:blue;"><?php _e('Average Basket', 'woo-kpi-dashboard');?></span>-<span style="color:purple;"><?php _e('Fidelity Rate', 'woo-kpi-dashboard');?></span></div>
  <?php
                //No license
                echo "<div><h3 style='font-size:140%;'>";
                    _e("Don't miss the full <a href='http://logg-out.com/en/'>pro version</a> of Woo-KPI Dashboard!", "woo-kpi-dashboard");
                echo "</h3></div>";
                echo "<div><div style='float:right;margin-right:40px;'>";
                    $url = plugins_url();
                    echo "<img src='".$url."/woo-kpi-dashboard/images/";
                    _e('kpi-us.png', 'woo-kpi-dashboard');
                    echo "' />";
                echo "</div><div style='font-size:200%;height:400px;padding-top:100px;line-height:120%;'>";
                    _e('Your main Key Performance Indicators of actual month - or any chosen month - displayed to identify all keypoints to handle for a profitable company.', 'woo-kpi-dashboard');
                echo "</div></div>";
                echo "<div style='clear:both;'>";
                    echo "<div style='text-align:center;font-size:200%;padding:20px;line-height:120%;'>";
                        _e("Woo-KPI Dashboard will even display tips to increase your company's incomes, depending on your own KPI !<br /><br />  <a style='font-weight:bolder;' href='http://logg-out.com/en/'>Acquire Pro Version</a>", "woo-kpi-dashboard");                   
                    echo "</div>";
                    echo "<div style='text-align:center;'>";
                    $url = plugins_url();
                    echo "<img src='".$url."/woo-kpi-dashboard/images/";
                    _e('tips-us.jpg', 'woo-kpi-dashboard');
                    echo "' />";                
                    echo "</div>";
                echo "</div>";
                ?>
              <style>
                .ui-datepicker-calendar {
                  display: none;
                }
              </style>
              <script type="text/javascript">

                jQuery(function() {
                  jQuery('.date-picker').datepicker( {
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'yy-mm',
                    onClose: function(dateText, inst) {
                      jQuery(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                      jQuery('#calendarStats').submit();
                    }
                  });
                });

              </script>
              <?php
              include ROOT_PATH . '/views/kpi-chart.php';
              ?>

  <?php
  }
  else{
    print(_e( 'You must be authenticated on Woo KPI Settings, be an admin and Woocommerce has to be installed to see the Woocommerce KPI Dashboard.', 'woo-kpi-dashboard' ));
  }
  ?>
</div>
