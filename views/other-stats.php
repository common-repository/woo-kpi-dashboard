<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Number of Sales Statistics
function wookpi_include_other( $current, $fidelity, $cpc_visits, $sales = 0, $income = 0, $adword ) {
    if ( !empty($visits) ) {
        foreach ($visits["rows"] as $c_stats) {
            $kpi_visits = $c_stats[0];
        }
    } else {
        $kpi_visits = 0;
    }
    if ( !empty($cpc_visits) ) {
        foreach ($cpc_visits["rows"] as $c_stats) {
            $kpi_cpc_visits = $c_stats[1];
        }
    } else {
        $kpi_cpc_visits = 0;
    }
    if ( !empty($adword) ) {
        foreach ($adword["rows"] as $c_stats) {
            $kpi_adwords = $c_stats[0];
        }
    } else {
        $kpi_adwords = 0;
    }
    $currency = get_woocommerce_currency_symbol( );
    ?>
    <div id="kpi-autres" class="kpi-carre">
        <div class="kpi-fenetre">
            <p class="kpi-titre-fenetre"><span><?php 
				_e('Other Informations','woo-kpi-dashboard');
			?></span></p>
            <p class="kpi-valeur">
                <span><?php 
					_e('Sales','woo-kpi-dashboard');
				?></span> <?php echo round($sales, 0); ?><br/>
                <span> <?php
					_e('Net Income','woo-kpi-dashboard');
				?></span> <?php 
					printf(
						__('%1$s'.'%2$s','woo-kpi-dashboard'),
						$currency,
						round($income, 0)
					);
				?><br/>
                <span> <?php
					_e('Adwords Expenses','woo-kpi-dashboard');
				?></span> <?php 
					printf(
						__('%1$s'.'%2$s','woo-kpi-dashboard'),
						$currency,
						round($kpi_adwords, 0)
					);
				?><br/>
                <span> <?php
					_e('Adwords Visits','woo-kpi-dashboard');
				?></span> <?php echo $kpi_cpc_visits; ?><br/>
                <span><?php 
					_e('Fidelity Rate','woo-kpi-dashboard');
				?></span> <?php echo $fidelity; ?>%<br/></p>
        </div>
    </div>
<?php } ?>