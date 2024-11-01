<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Number of Sales Statistics
function kpi_include_basket( $kpi_basket ) {
    if (!empty($kpi_basket)) {
        $currency = get_woocommerce_currency_symbol();
    } else {
        $currency = '-';
        $kpi_basket = 0;
    }
        ?>

    <div id="kpi-panier" class="kpi-carre">
        <div class="kpi-fenetre">
            <p class="kpi-titre-fenetre"><span><?php 
					_e('Average basket','woo-kpi-dashboard');
				?></span></p>
            <p class="kpi-valeur"><?php 
				printf(
					__('%1$s'.'%2$s','woo-kpi-dashboard'),
					$currency,
					round($kpi_basket,2)
				);
			?></p>
            <div class="kpi-image"></div>
        </div>
    </div>

<?php } ?>