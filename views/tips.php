<?php
// Number of Sales Statistics
function wookpi_include_tips( $woo_kpi, $income_ht_hp, $month, $year, $cpc_visits, $adword, $visitors, $basket = 0, $sales ) {
    $actual_year = date('Y');
    $actual_month = date('m');
    $actual_day = date('d');
    $days_in_month = $woo_kpi->wookpi_numberOfDaysInMonth($actual_month, $month);
    $advices = "N/A";
    //variable for advices
    $h = 1;
    $month = ($month * 1) - 1;
    $currency = get_woocommerce_currency_symbol();
    $month_table = array('kpi_january','kpi_february','kpi_march','kpi_april','kpi_may','kpi_june','kpi_july','kpi_august','kpi_september','kpi_october','kpi_november','kpi_december'); 
    $objective = get_option($month_table[$month]);
    $daily_income_for_objective = round($objective / $days_in_month, 0);
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
    if ( !empty($visitors) ) {
        foreach ($visitors["rows"] as $c_stats) {
            $kpi_visitors = $c_stats[0];
        }
    } else {
        $kpi_visitors = 0;
    }
    if ($kpi_visitors < 1) {
        $kpi_visitors = 0;
    }
    if ($kpi_visitors > 0 && !empty($sales)) {
        $kpi_conversion = round($sales / $kpi_visitors * 100, 2);
    }else {
        $kpi_conversion = 0;
    }
    //daily net income
    $daily_income = round($income_ht_hp / $days_in_month, 0);
    //revenue by visitor
    if ($kpi_visitors > 0) {
        $revenue_by_visitor = round($income_ht_hp / $kpi_visitors, 2);
    } else {
        $revenue_by_visitor = 0;
    }
    ?>
    <div>
        <div id="kpi-conseils" class="kpi-rectangle">
            <div class="kpi-fenetre-relative">
                <p class="kpi-titre-fenetre"><span>Conseils</span></p>
                <p class="kpi-valeur">

    <?php
    //average basket
    $basket = round($basket, 2);
    echo "<div class='conseil-texte'>";
    echo "<div class='target-illustration'></div>";
    printf(
		__('You had <b>%1$s</b> unique visitor in the period, for an average net income (excluding taxes and shipping costs) of <b>%3$s%2$s</b> per visitor.<br />','woo-kpi-dashboard'),
		$kpi_visitors,
		$revenue_by_visitor,
		$currency
	);
	if (!isset($objective) || $objective == 0) {
        _e("You have not set any objective for this month. Please <a href='?page=kpi-objectives'>set an objective</a> to have corresponding tips.",'woo-kpi-dashboard');
    } else {
		printf(
			__('<br/>Your net income objective for the period is <b>%2$s%1$s</b>.<br />','woo-kpi-dashboard'),
			$objective,
			$currency
        );
		//Are objectives reached ?
        if ($objective > $income_ht_hp) {
            $missed = $objective - $income_ht_hp;
            //If no, is it the current month to finish ?
            if ($actual_year == $year && (($actual_month * 1) - 1) == $month) {
                //Are we on the last day of the month ?
                if ($actual_day < $days_in_month) {
                    $remaining_days = $days_in_month - $actual_day;
                    _e("<br/>You have not reached your objective yet. ",'woo-kpi-dashboard');
                    printf(
						__('You are missing <b>%2$s%1$s</b> to reach it.<br />','woo-kpi-dashboard'),
						round($missed, 0),
						$currency
					);
                    $reamining_amount_daily = round($missed / $remaining_days, 0);
					printf(
						__('<br/>To reach your objective, you would need to achieve a net income of <b>%2$s%1$s</b> everyday until the end of the month.<br />','woo-kpi-dashboard'),
						$reamining_amount_daily,
						$currency
					);
					//It can't be serious to give advice linked to actual month income if we are on the very first days of the month
                    if (($actual_day * 1) > 3) {
                        //is it possible to realize this amount daily ?
                        $daily_income = round($income_ht_hp / $actual_day, 0);
                        if ($reamining_amount_daily > ($daily_income * 1.5)) {
                            //More than 50% more than other days
							printf(
								__('<br/>This might be difficult since, until now, this month, your net income is <b>%2$s%1$s</b>.<br />','woo-kpi-dashboard'),
								$daily_income,
								$currency
							);
						} else {
                            printf(
                                __('<br/>This objective is achievable: until now, this month, your daily net income is <b>%2$s%1$s</b>.<br />','woo-kpi-dashboard'),
                                $daily_income,
                                $currency
                            );
                        }
                    }

                } else {
                    //It's the last day of the month : difficult to have objective reached
                    _e("<br/>You have not reached your objective yet. ",'woo-kpi-dashboard');
					printf(
						__('You are missing <b>%2$s%1$s</b> to reach it.','woo-kpi-dashboard'),
						round($missed,0),
						$currency
					);
                    if ($missed < $daily_income * 2 && date("H") < 12) {
                        _e('<br/>You can achieve your monthly objective! Pour your heart in it. Be active on social networks, present your new products on there, advertise... Act to end the month in a blaze of glory!','woo-kpi-dashboard');
                    }
                }
            } else {
                //Objective not reached if past month
                _e("<br/>Unfortunately, you have not reached your objective.",'woo-kpi-dashboard');
                printf(
					__('<br/>Your average daily net income was<b> %2$s%1$s.</b><br />','woo-kpi-dashboard'),
					$daily_income,
					$currency
				);
            }
			printf(
				__('<br/>Your objective means an average daily net income of <b>%2$s%1$s.</b><br />','woo-kpi-dashboard'),
				$daily_income_for_objective,
				$currency
			);
        } else {
            //Objective reached
            _e("<br/><strong>Bravo!</strong> You have reached your objective!<br/>","woo-kpi-dashboard");
            $missed = 0;
        }
        if ($kpi_adwords > 0 && $kpi_cpc_visits > 0 && $kpi_visitors > 0 && $income_ht_hp > 0) {
            //We have visits by Adwords
            //How many percent of visitors ?
            $percentage_visitors_cpc = round($kpi_cpc_visits / $kpi_visitors * 100, 2);
            $ratio_adwords = round($kpi_adwords / $income_ht_hp * 100, 2);
            $visitor_cost = round($kpi_adwords / $kpi_cpc_visits, 2);
            $all_visitors_cost = round($kpi_adwords/$kpi_visitors,2);
            printf(
				__('<br/>With an invested Adwords budget of <b> %2$s%1$s</b>, you have drawn in<b> %3$s</b> visitors, meaning <b>%4$s&#37;</b> of your visitors who came from your Google CPC campaigns, and a cost for each of those visitors of <b>%2$s%5$s.</b> (<b>%2$s%6$s</b> if averaged to all visitors)<br />','woo-kpi-dashboard'),
				$kpi_adwords,
				$currency,
				$kpi_cpc_visits,
				$percentage_visitors_cpc,
				$visitor_cost,
                $all_visitors_cost

            );
			printf(
				__('<br/>This represents a ratio between your Google Adwords investment and your net income of <b>%s</b>&#37; : <a href=\'https://itunes.apple.com/us/app/pro-calcul-edition-complete/id432321030?mt=8\'>Check your margins</a> to make sure of this investment\'s profitability.<br />','woo-kpi-dashboard'),
				$ratio_adwords
			);
			$revenue_by_adwords = round($kpi_cpc_visits * $revenue_by_visitor, 2);
			printf(
				__('<br/>By rounding average basket and conversion rate on the totality of visitors, we can estimate the net income part coming from the Adwords visits at <b>%2$s%1$s.</b><br/>','woo-kpi-dashboard'),
				round($revenue_by_adwords,0),
				$currency
			);
		}
        echo "</div>";
        //Here we will include tips for all situations based on KPI
        //Increase the average basket
        //upsell and cross-sell
        //
        if ($basket > 0) {
            //Calcul exemple montant frais de port gratuits
            $example_fdp = ceil((($basket * 1.2) + 25) / 10) * 10;
            echo '<div id="conseil-panier" class="hypothese">';
                printf(
                __('<div class="conseil-title">Hypothesis %s to increase the net income: Increase the Average Basket</div>','woo-kpi-dashboard'),
                $h
            );
                echo "<div class='conseil-texte'>";
                echo "<div class='conseil-illustration'></div>";
                printf(
                    __('The average basket excluding tax and shipping on your shop is <b>%2$s%1$s</b>. Study it, as well as your prices. Isn\'t there points to improve?
                             Aren\'t your prices lower than the market\'s? Try increasing lightly the price of some of your products and see the effect it has on sales.<br />
                             <br/>Do you use cross-selling techniques? Do you encourage the purchase of complementary products when in the cart?<br />
                             <br/>Do you use upmarket strategies? Do you propose, while choosing the product, a product of superior quality?<br />
                             <br/>Do you propose extras? Such as a warranty? An insurance? An additional license? A gift-wrapping?<br />
                             <br/>Do you propose decreasing prices per quantity? Or, for exemple 3 for the price 2?<br />
                             <br/>Do you offer something after a certain purchase amount? Such as free shipping cost after <b>%2$s%3$s</b> as a mean to increase your average basket?<br />
                             Or even offer a gift, Ou même offrir un cadeau, per purchase segments, like some companies on the internet do?<br />
                             <br/>Can you propose grouped purchases for your products? eg: The shirt + the jacket + the trousers at a preferential price?<br/><br/>','woo-kpi-dashboard'),
                    $basket,
                    $currency,
                    $example_fdp
                );
                $improved_basket = round($basket + ($basket * 10 / 100), 2);
                $incomes_with_improved_basket = round($sales * $improved_basket, 0);
                printf(
                    __('Increasing the basket by 10 &#37;, or <b>%2$s%1$s</b> would have allowed to change your net income from <b>%2$s%4$s</b> to <b>%2$s%3$s</b>.<br/>','woo-kpi-dashboard'),
                    round($improved_basket,2),
                    $currency,
                    round($incomes_with_improved_basket, 0),
                    round($income_ht_hp, 0)
                );
                echo '</div>';
            echo '</div>';
            $h++;
        }

        //Increase conversion Rate
        //Verify the conversion funnel like a customer, ask friends or anyone using your site, asking them to choose a product and make an order see what you can improve
        //Perhaps, if you have not, you can remove the promo code form and replace it by a link ?
        //Are you sure your campaigns are detailed enough and visitors are finding what was announced ?
        if ($kpi_conversion > 0) {
            $improved_conversion = $kpi_conversion + 0.5;
            $improved_conversion_result = round(($kpi_visitors * $improved_conversion / 100) * $basket, 0);
            echo '<div id="conseil-conversion" class="hypothese">';
            printf(
                __('<div class="conseil-title">Hypothesis %s to increase the net income: Increase the Conversion Rate</div>','woo-kpi-dashboard'),
                $h
            );
            echo "<div class='conseil-texte'>";
            echo "<div class='conseil-illustration'></div>";
            _e('Seriously test your conversion funnel:<br/><br/>Home Page &#8594; Category &#8594; Product &#8594; Cart &#8594; Order Confirmation &#8594; Payment &#8594; Thanks.<br />','woo-kpi-dashboard');
            _e('<br/>Ask some friends, acquaintance and/or test customers to visit, in front of you, your shop with the intent of buying, until the finalization of the order (that you can cancel). Quietly place yourself behind them and, <strong>without ever intervening</strong>, take notes of the hesitations, misunderstandings, doubts and mistakes to then correct them on your shop.<br />','woo-kpi-dashboard');
            _e('<br/>Are your CPC marketing campaigns, partnerships and affiliations accurate? Do the prospects easily find, in a clear way, the answer to the promise made by your campaigns? Do the prices correspond? Are your arguments accurate enough as to not disappoint and lose a prospect who might have become a customer?<br />','woo-kpi-dashboard');
            _e('<br/>According to <a href=\'https://blog.kissmetrics.com/the-one-little-box/\'>some studies</a>, the presence of the field for discount codes would cause a loss in sales. If you use it, study other solutions, such as <a href=\'https://github.com/cedaro/woocommerce-coupon-links\'>replacing it with a clickable link</a>.<br />','woo-kpi-dashboard');
            printf(
                __('<br/>Increasing you conversion rate by 0.5 &#37; would have allowed to change your net income to <b>%2$s%1$s</b>.<br />','woo-kpi-dashboard'),
                $improved_conversion_result,
                $currency
            );
            echo "</div>";
            echo '</div>';
            $h++;
        }
        if ($missed == 0) { //Objective reached
            //Increase natural visits
            //Be active on social networks
            //Be efficient on your SEO
            echo '<div id="conseil-visiteurs" class="hypothese">';
            printf(
                __('<div class="conseil-title">Hypothesis %s to increase the net income: Increase the Number of Visitors</div>','woo-kpi-dashboard'),
                $h
            );
            echo "<div class='conseil-texte'>";
            echo "<div class='conseil-illustration'></div>";
            _e('Awesome: you have reached your fixed objectives! But perhaps you could go even higher?<br /> Do you better and control your onpage SEO? You can use plugins such as YOAST SEO or others to help optimizing your pages. Train yourself, exchange with colleagues, better your online presence...<br />','woo-kpi-dashboard');
            _e('<br/>Be active on social networks, put up a real strategy, create a community. Even be proactive, help and give advice to this community\'s members.<br />','woo-kpi-dashboard');
            _e('<br/>Propose partnerships with blogs, organize contests, be on forums, in blogs\' comments, in Facebook groups... If you do B-to-B, be active on networks such as Linkedin. Mark your presence on the web.<br />','woo-kpi-dashboard');
            echo "</div>";
            echo '</div>';
            $h++;

        } else {
            //Increase natural visits
            //Be active on social networks
            //Be efficient on your SEO
            if ($kpi_visitors > 0 && isset($improved_basket) && $income_ht_hp > 0) {
                $necessary_sales = $objective / $improved_basket;
                $improved_visitors = round($necessary_sales * (100 / $improved_conversion),0);
                $percentage_more_visitors = round($improved_visitors / $kpi_visitors * 100, 0);
				echo '<div id="conseil-visiteurs" class="hypothese">';
				printf(
					__('<div class="conseil-title">Hypothesis %s to increase the net income: Increase the Number of Visitors</div>','woo-kpi-dashboard'),
					$h
                );
                echo "<div class='conseil-texte'>";
                echo "<div class='conseil-illustration'></div>";
                _e('Do you improve and control your onpage SEO? You can use plugins such as YOAST SEO or others to help optimizing your pages. Train yourself, exchange with colleagues, better your online presence...<br />','woo-kpi-dashboard');
				_e('<br/>Be active on social networks, put up a real strategy, create a community. Even be proactive, help and give advice to this community\'s members.<br />','woo-kpi-dashboard');
				_e('<br/>Propose partnerships with blogs, organize contests, be on forums, in blogs\' comments, in Facebook groups... If you do B-to-B, be active on networks such as Linkedin. Mark your presence on the web.<br />','woo-kpi-dashboard');
				printf(
					__('<br/>You currently have <b>%1$s</b> visitors. If you have managed to increase your average basket by 10&#37; and your conversion rate by 0,5&#37;, you would need to have <b>%2$s</b> or <b>%3$s&#37;</b> of the current number to reach your objective.<br />','woo-kpi-dashboard'),
					$kpi_visitors,
					$improved_visitors,
					$percentage_more_visitors
				);
                $necessary_visitors = ($objective / $basket) * (100 / $kpi_conversion);
                printf(
                    __('<br/>With no change on average basket and conversion rate, you need to reach <b>%1$s</b> visitors to realize your objective.<br />','woo-kpi-dashboard'),
                    round($necessary_visitors, 0)
                );
                if ($actual_year == $year && (($actual_month * 1) - 1) == $month) {
                    $day_visitors = $kpi_visitors / $actual_day;
                    $estimated_visitors = $day_visitors * $days_in_month;

                    printf(
                        __('<br/>Please note that a linear evolution of your current number of visitors - for an average of <b>%1$s</b> visitors per day - would lead to a number of visitors of <b>%2$s</b> at the end of the month.<br />','woo-kpi-dashboard'),
                        round($day_visitors,0),
                        round($estimated_visitors,0)
                    );
                }
                echo "</div>";
				echo '</div>';
				$h++;
            }
            //Tips based on Adwords budget
            if ($kpi_adwords > 0 && $kpi_cpc_visits > 0 && $kpi_visitors > 0 && isset($revenue_by_adwords) && isset($visitor_cost)) {
                //How many Adwords budget to reach it (if cost by visitor is less than revenue)
                if ($revenue_by_adwords > $visitor_cost) {
                    //How many visits are missing to reach objective ?
                    $visits_for_objective = $missed / $revenue_by_visitor;
                    $cost_for_visits_objective = round($visits_for_objective * $visitor_cost, 0);
                    $breakeven_adwords = $missed / (($revenue_by_visitor - $visitor_cost)/$revenue_by_visitor);
					echo '<div id="conseil-adwords" class="hypothese">';
                    if ($revenue_by_visitor > $visitor_cost) {
                        printf(
                            __('<div class="conseil-title">Hypothesis %s to increase the net income: Increase the Adwords budget</div>', 'woo-kpi-dashboard'),
                            $h
                        );
                        echo "<div class='conseil-texte'>";
                        echo "<div class='conseil-illustration'></div>";
                        _e('The cost per Adwords visitor being lower than the gain per visitor, by carefully increasing the Google Adwords budget, you could increase your income.<br /><br /><u>Be careful:</u> There are tresholds where the campaign\'s effectiveness decreases, progress by segments by keeping watch of the effects on your budget and your income. Like stock market, CPC campaigns can be tricky and require a constant and strict surveillance.<br/>', 'woo-kpi-dashboard');
                        printf(
                            __('<br/>In absolute, proportionally to the current results (but the proportionality may not verify itself, this is why we advise you proceed by tresholds), dedicating an additional Adwords budget of <b>%1$s%2$s</b> could lead to your objectives. Of course, your objectives could then need to be redetermined to take CPC marketing costs into account.<br/>', 'woo-kpi-dashboard'),
                            $cost_for_visits_objective,
                            $currency
                        );
                        printf(
                            __('<br/>For the Adwords investment made to obtain these additional CPC visits to yield enough to reach your objectives AND amortize this additional Adwords investment, you would need this additional budget to be of <b>%2$s%1$s</b>! Provided that, once again, the campaign\'s effectiveness does not diminish with the increase of the budget.<br />', 'woo-kpi-dashboard'),
                            round($breakeven_adwords, 0),
                            $currency
                        );
                    } else {
                        printf(
                            __('<div class="conseil-title">Hypothesis %s : Correct your marketing strategy</div>', 'woo-kpi-dashboard'),
                            $h
                        );
                        echo "<div class='conseil-texte'>";
                        echo "<div class='conseil-illustration'></div>";
                        printf(
                            __( '<span style="color:red;"><u>WARNING:</u> Your marketing expenses per visitor are HIGHER than your incomes! Each visitor acquired costs you <b>%2$s%1$s</b> and only brings you <b>%2$s%3$s</b>!! Unless this is a voluntary strategy (brand awareness building , market occupancy, high cost per customer), quickly correct your strategy.</span>', 'woo-kpi-dashboard'),
                                round($visitor_cost, 2),
                                $currency,
                                round($revenue_by_visitor, 2)
                            );
                    }
                    echo "</div>";
					echo '</div>';
					$h++;
                }
            }

        }
    }
    ?>
                </p>
            </div>
        </div>
    </div>
<?php } ?>