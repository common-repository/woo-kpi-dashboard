<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// View of Country wise Statistics
function wookpi_include_visitors( $current, $visitors_stats )
{
    if (!empty($visitors_stats["rows"])) { ?>
        <div id="kpi-visiteurs" class="kpi-carre">
            <div class="kpi-fenetre">
                <p class="kpi-titre-fenetre"><span><?php 
					_e('Unique Visitors','woo-kpi-dashboard');
				?></span></p>
                 <?php foreach ($visitors_stats["rows"] as $c_stats){ ?>
                    <p class="kpi-valeur"><?php echo $c_stats[0]; ?></p>
                 <?php } ?>

                <div class="kpi-image"></div>
            </div>
        </div>
    <?php
    }
}
?>
