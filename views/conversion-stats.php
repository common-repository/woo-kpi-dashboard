<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Number of Sales Statistics
function wookpi_include_conversion( $current, $visitors_stats, $kpi_sales ) {
    if (!empty($visitors_stats) && !empty($kpi_sales)) {
        foreach ($visitors_stats["rows"] as $c_stats) {
            $kpi_conversion = $kpi_sales / $c_stats[0] * 100;
        }
    }else {
            $kpi_conversion = 0;
        }

        ?>

        <div id="kpi-conversion" class="kpi-carre">
        <div class="kpi-fenetre">
            <p class="kpi-titre-fenetre"><span><?php 
					_e('Conversion Rate','woo-kpi-dashboard');
				?></span></p>
            <p class="kpi-valeur"><?php echo round($kpi_conversion, 2); ?>%</p>
            <div class="kpi-image"></div>
        </div>
    </div>

<?php } ?>