<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//Function to display $ or € if it's one of this money in the settings. If not, it keeps the long name
$currency = get_woocommerce_currency_symbol();
if ($currency == '&euro;') {
    $currency = "€";
} else if ($currency == '&#36;') {
 $currency = '$';
}
?>
<script>
     jQuery(document).ready(function () {
         //Array of the objectives saved in DBB
        var objectives = [<?=esc_attr( get_option('kpi_january') );?>,<?=esc_attr( get_option('kpi_february') );?>,<?=esc_attr( get_option('kpi_march') );?>,<?=esc_attr( get_option('kpi_april') );?>,<?=esc_attr( get_option('kpi_may') );?>,<?=esc_attr( get_option('kpi_june') );?>,<?=esc_attr( get_option('kpi_july') );?>,<?=esc_attr( get_option('kpi_august') );?>,<?=esc_attr( get_option('kpi_september') );?>,<?=esc_attr( get_option('kpi_october') );?>,<?=esc_attr( get_option('kpi_november') );?>,<?=esc_attr( get_option('kpi_december') );?>] 
        //Below the function will order the objectives depending on the current month
        var s1 = <?php echo display_objectives(); ?>;
        //This function will reunite chart datas except those for objectives and fidelity rate
        <?=chart_datas();?>
        var s6 = [<?=fidelity_rate();?>];
        plot1 =  jQuery.jqplot("kpi-chartContainer", [s2, s1, s3, s4, s5, s6], {
            // Turns on animatino for all series in this plot.
            animate: true,
            // Will animate plot on calls to plot1.replot({resetAxes:true})
            animateReplot: true,
            cursor: {
                show: true,
                zoom: true,
                looseZoom: true,
                showTooltip: false
            },
            series:[
                {
                    label: "<?php 
						_e('Income','woo-kpi-dashboard'); 
					?>",
                    pointLabels: {
                        show: true
                    },
                    renderer:  jQuery.jqplot.BarRenderer,
                    showHighlight: true,
                    yaxis: 'yaxis',
                    rendererOptions: {
                        // Speed up the animation a little bit.
                        // This is a number of milliseconds.  
                        // Default for bar series is 3000.  
                        animation: {
                            speed: 2500
                        },
                        barWidth: 15,
                        barPadding: -15,
                        barMargin: 0,
                        highlightMouseOver: true
                    },
                    color: '#00adce'

                },
                {
                    rendererOptions: {
                        // speed up the animation a little bit.
                        // This is a number of milliseconds.
                        // Default for a line series is 2500.
                        animation: {
                            speed: 2000
                        }
                    },
                    color:'green',
                    lines: {
                        lineWidth:4
                    }
                },
                {
                    rendererOptions: {
                        // speed up the animation a little bit.
                        // This is a number of milliseconds.
                        // Default for a line series is 2500.
                        animation: {
                            speed: 3000
                        }
                    },
                    yaxis: 'y2axis',
                    color:'yellow'
                },
                {
                    rendererOptions: {
                        // speed up the animation a little bit.
                        // This is a number of milliseconds.
                        // Default for a line series is 2500.
                        animation: {
                            speed: 3000
                        }
                    },
                    color:'red',
                    yaxis: 'y3axis'
                },
                {
                    rendererOptions: {
                        // speed up the animation a little bit.
                        // This is a number of milliseconds.
                        // Default for a line series is 2500.
                        animation: {
                            speed: 3000
                        }
                    },
                    color:'blue',
                    yaxis: 'y4axis'
                },
                {
                    rendererOptions: {
                        // speed up the animation a little bit.
                        // This is a number of milliseconds.
                        // Default for a line series is 2500.
                        animation: {
                            speed: 3000
                        }
                    },
                    color:'purple',
                    yaxis: 'y5axis'
                }

            ],
            axesDefaults: {
                pad: 0
            },
            axes: {
                // These options will set up the x axis like a category axis.
                xaxis: {
                    numberTicks: 12,
                    ticks:ticks,
                    rendererOptions: {
                        tickInset: 0.5,
                        minorTicks: 1
                        
                    }
                },
                yaxis: {
                    tickOptions: {
                        formatString: " %'d <?=$currency;?>"
                    },
                    rendererOptions: {
                        forceTickAt0: true
                    },
                    label: "<?php 
						_e('Income and Objective ','woo-kpi-dashboard'); 
					?>",
                    labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer
                },
                y2axis: {
                    tickOptions: {
                        formatString: " %'d vis."
                    },
                    rendererOptions: {
                        // align the ticks on the y2 axis with the y axis.
                        alignTicks: true,
                        forceTickAt0: true
                    },
                    label: "<?php 
						_e('Visitors ','woo-kpi-dashboard'); 
					?>",
                    labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer,
                },
                y3axis: {
                    showTicks : false,
                    tickOptions: {
                        formatString: "<?php 
						_e('Conversion Rate ','woo-kpi-dashboard'); 
					?>: %.2f %",
                        show:false
                    },
                    rendererOptions: {
                        // align the ticks on the y3 axis with the y axis.
                        alignTicks: true,
                        forceTickAt0: true,
                        drawBaseline: false
                    }
                    //,
                    //label: "conversion & Fidelity Rate",
                    //labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer
                },
                y4axis: {
                    showTicks : false,
                    tickOptions: {
                        formatString: "<?php 
						_e('Average Basket ','woo-kpi-dashboard'); 
					?>:  %.2f <?=$currency;?>",
                        show: false
                    },
                    rendererOptions: {
                        // align the ticks on the y4 axis with the y axis.
                        alignTicks: true,
                        drawBaseline: false,
                        forceTickAt0: true
                    }
                    //,
                    //label: "Average Basket",
                    //labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer,
                    //labelOptions: {textColor: 'blue'}
                },
                y5axis: {
                    showTicks : false,
                    tickOptions: {
                        formatString: "<?php 
						_e('Fidelity Rate ','woo-kpi-dashboard'); 
					?>: %.2f %",
                        show:false
                    },
                    rendererOptions: {
                        // align the ticks on the y3 axis with the y axis.
                        alignTicks: true,
                        forceTickAt0: true,
                        drawBaseline: false
                    }
                    //,
                    //label: "conversion & Fidelity Rate",
                    //labelRenderer: jQuery.jqplot.CanvasAxisLabelRenderer
                }
            },
            highlighter: {
                show: true,
                showLabel: true,
                tooltipAxes: 'y',
                sizeAdjust: 7.5 , tooltipLocation : 'ne'
            }
        });

    });
</script>
<?php
function actual_month() {
    $actual_month = date('m');
    $actual_month = $actual_month * 1;
    return $actual_month;
}

function display_month($month) {
    if ($month < 1) {
        $month = $month + 12;    
    }
    return $month;
}
function display_objectives() {
    //We begin the string for the array
    $tableau = "[";
    //$j is the number in the array
    $j = 1;
    //We do a loop from farthest month from actual month to actual month 
    for ($i = 11; $i >= 0; $i--) {
        //The actual array element begins
        //The month to take the datas from is the actual month - xx monthes
        $month = actual_month() - $i;
        //Of course, if actual month is less than $i, the result will be bad : so this function add 12 to results less than 1
        $month = display_month($month);   
        //as it's a number in an array, we remove 1 to the resulted month
        $position = $month - 1;
        //Here we take the result in previously declared array, in the right cell calculated by the process
        $tableau .= "objectives[".$position."]";
        //We increment the number defining actual json array
        $j++;
        //We add a comma only if it's not the last array in the array
        if ($i > 0) {
            $tableau .= ",";    
        }
    }
    //We close general array
    $tableau .= "]";
    //and we return the result from the function
    return $tableau;
}

function fidelity_rate() {
    global $wpdb;
    $year = date('Y');
    $actual_month = actual_month();
    //The fidelity is calculated by  quarter to date
    $s6 = "";
    for ($x = 11; $x >= 0; $x--) {
        $i++;
        $j = $x-1;
        $month_name = date("F", strtotime( date( 'Y-m-01' )." -$x months"));
        $end_date = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$j months"));
        //Nombre de clients ayant commandé dans le trimestre
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
        //Clients with more than 1 order in the quarter
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
        $s6 .= $fidelity_rate;
        if ($i < 12) {
            $s6 .= ",";
        }
    }
    return $s6;
}
function chart_datas () {
    //We will use Wordpress DB class to take the informations in the DDB
    global $wpdb;
    //We will use the class from Woo-LPI Dahboard plugin
    $woo_kpi = new WooKPI();
    //We are declaring the different strings for the chart datas
    $s2 = "";
    $s3 = "";
    $s4 = "";
    $s5 = "";
    //We are declaring the arrays used to store the datas before they are in the chart strings
    $kpi_sale = array();
    $monthly_visits = array();
    $month_name = array();
    $kpi_basket = array();
    $i = 0;
    for ($x = 11; $x >= 0; $x--) {
        $i++;
        $start_date = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$x months"));
        $month_name[$i] = date("F", strtotime( date( 'Y-m-01' )." -$x months"));
        $j = $x-1;
        $end_date = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$j months"));
        //Income
        $order_totals = apply_filters( 'woocommerce_reports_sales_overview_order_totals', $wpdb->get_row( "
          SELECT SUM(meta.meta_value) AS total_sales, COUNT(posts.ID) AS total_orders FROM {$wpdb->posts} AS posts
          LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
          WHERE meta.meta_key = '_order_total'
        AND posts.post_type = 'shop_order'
        AND posts.post_date >= '".$start_date."' AND posts.post_date < '".$end_date."'
        AND posts.post_status != 'wc-failed'
        AND posts.post_status != 'wc-refunded'
        AND posts.post_status != 'wc-cancelled'
        AND posts.post_status != 'trash'
        " ) );
        $kpi_income = $order_totals->total_sales;

        //We save the number of sales
        $kpi_sale[$i] = $order_totals->total_orders;

        //Tax
        $taxes = apply_filters( 'woocommerce_reports_sales_overview_order_totals', $wpdb->get_row( "
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
        $kpi_tax = $taxes->tax + $shipping_taxes->shipping_tax;

        //shipping
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

        $kpi_shipping = ($shipping_costs->shipping);

        //Income net
        $income_ht_hp = $kpi_income - $kpi_tax - $kpi_shipping;

        //We have the income, we calculate the average basket
        if ($income_ht_hp > 0 && $kpi_sale[$i] > 0) {
            $kpi_basket[$i] = $income_ht_hp / $kpi_sale[$i];

        } else {

            $kpi_basket[$i] = 0;
        }
        $kpi_basket[$i] = round($kpi_basket[$i],2);


        //Visitors
        $visitors_monthes = $woo_kpi->wookpi_get_analytics_dashboard( 'ga:visitors', $start_date, $end_date);
        $monthly_visits[$i] = 0;
        if ( isset( $visitors_monthes->totalsForAllResults ) && isset($visitors_monthes["rows"])) {
            foreach ($visitors_monthes["rows"] as $c_stats){
                $monthly_visits[$i] = $c_stats[0];
            }
        }

        $s2 .= $income_ht_hp;
        if ($i < 12) {
            $s2 .= ",";
        }

    }

    $chart = " var s2 = [".$s2."];";

    //We construct datas s3 : visitors
    $ticks = "[";
    for ($i = 1;$i<=12;$i++) {
        $s3 .= $monthly_visits[$i];

        if ($i < 12) {
            $s3 .= ",";
        }
        //BTW we create string for ticks
        $ticks .= "[".$i.",'".$month_name[$i]."']";
         if ($i < 12) {
            $ticks .= ",";
        }
    }
    $ticks .= "]";


    //We add datas s3 to chart
    $chart .= " var s3 = [".$s3."];";

    //We construct datas s4 : conversion rate
    for ($i = 1;$i<=12;$i++) {
        if ($monthly_visits[$i] > 0) {
            $kpi_conversion = $kpi_sale[$i] / $monthly_visits[$i] * 100;

        } else {

            $kpi_conversion = 0;
        }
        $kpi_conversion = round($kpi_conversion,2);
        $s4 .= $kpi_conversion;

        if ($i < 12) {
            $s4 .= ",";
        }

    }


    //we add datas s4 to chart

    $chart .= "var s4 = [".$s4."];";


    //We construct dats s5 : average basket
    for ($i = 1;$i<=12;$i++) {

        $s5 .= $kpi_basket[$i];

        if ($i < 12) {
            $s5 .= ",";
        }

    }
    //we add datas s5 to chart

    $chart .= "var s5 = [".$s5."];";
    $chart .= "var ticks = ".$ticks.";";

    return $chart;


}
?>