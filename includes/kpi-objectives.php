<div class="wrap">
    <h2 class='wookpi-title'><span id='icon-options-general' class='analytics-options'><img src="<?php echo plugins_url('woo-kpi-dashboard/images/kpi-logo.png');?>" alt="WooKPI Dashboard"></span>
        <?php _e( 'Woo-KPI Dashboard', 'woo-kpi-dashboard'); ?>
        <?php _e( '<br /><span style="font-size:0.8;font-style:italic;">Set Net Income Objectives</span>', 'woo-kpi-dashboard' ); ?>
    </h2>
    <div><?php _e( 'Your projected monthly incomes, tax and shipping cost not included.', 'woo-kpi-dashboard' ); ?></div>
    <?php
        if ( current_user_can('administrator') ) {
    ?>
    <div id="objectives_form">
        <form method="post" action="options.php">
            <?php settings_fields( 'objectives-settings-group' ); ?>
            <?php do_settings_sections( 'objectives-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top" class="table-element">
					<th scope="row"><?php _e('January', 'woo-kpi-dashboard'); ?></th>
					<td><input type="number" name="kpi_january" value="<?php echo esc_attr( get_option('kpi_january') ); if (is_null(get_option('kpi_january'))) echo '0'; ?>" /></td>
				</tr>
                <tr valign="top" class="table-element">
					<th scope="row"><?php _e('February', 'woo-kpi-dashboard'); ?></th>
					<td><input type="number" name="kpi_february" value="<?php echo esc_attr( get_option('kpi_february') ); if (is_null(get_option('kpi_february'))) echo '0'; ?>" /></td>
				</tr>
                <tr valign="top" class="table-element">
                    <th scope="row"><?php _e('March', 'woo-kpi-dashboard'); ?></th>
                    <td><input type="number" name="kpi_march" value="<?php echo esc_attr( get_option('kpi_march') ); if (is_null(get_option('kpi_march'))) echo '0'; ?>" /></td>
                </tr>
                <tr valign="top" class="table-element">
                    <th scope="row"><?php _e('April', 'woo-kpi-dashboard'); ?></th>
                    <td><input type="number" name="kpi_april" value="<?php echo esc_attr( get_option('kpi_april') ); if (is_null(get_option('kpi_april'))) echo '0'; ?>" /></td>
                </tr>
                <tr valign="top" class="table-element">
                    <th scope="row"><?php _e('May', 'woo-kpi-dashboard'); ?></th>
                    <td><input type="number" name="kpi_may" value="<?php echo esc_attr( get_option('kpi_may') ); if (is_null(get_option('kpi_may'))) echo '0'; ?>" /></td>
                </tr>
                <tr valign="top" class="table-element">
                    <th scope="row"><?php _e('June', 'woo-kpi-dashboard'); ?></th>
                    <td><input type="number" name="kpi_june" value="<?php echo esc_attr( get_option('kpi_june') ); if (is_null(get_option('kpi_june'))) echo '0'; ?>" /></td>
                </tr>
                <tr valign="top" class="table-element">
                    <th scope="row"><?php _e('July', 'woo-kpi-dashboard'); ?></th>
                    <td><input type="number" name="kpi_july" value="<?php echo esc_attr( get_option('kpi_july') ); if (is_null(get_option('kpi_january'))) echo '0'; ?>" /></td>
                </tr>
                <tr valign="top" class="table-element">
                    <th scope="row"><?php _e('August', 'woo-kpi-dashboard'); ?></th>
                    <td><input type="number" name="kpi_august" value="<?php echo esc_attr( get_option('kpi_august') );  if (is_null(get_option('kpi_august'))) echo '0'; ?>" /></td>
                </tr>
                <tr valign="top" class="table-element">
                    <th scope="row"><?php _e('September', 'woo-kpi-dashboard'); ?></th>
                    <td><input type="number" name="kpi_september" value="<?php echo esc_attr( get_option('kpi_september') ); if (is_null(get_option('kpi_september'))) echo '0'; ?>" /></td>
                </tr>
                <tr valign="top" class="table-element">
                    <th scope="row"><?php _e('October', 'woo-kpi-dashboard'); ?></th>
                    <td><input type="number" name="kpi_october" value="<?php echo esc_attr( get_option('kpi_october') ); if (is_null(get_option('kpi_october'))) echo '0'; ?>" /></td>
                </tr>
                <tr valign="top" class="table-element">
                    <th scope="row"><?php _e('November', 'woo-kpi-dashboard'); ?></th>
                    <td><input type="number" name="kpi_november" value="<?php echo esc_attr( get_option('kpi_november') ); if (is_null(get_option('kpi_november'))) echo '0'; ?>" /></td>
                </tr>
                <tr valign="top" class="table-element">
                    <th scope="row"><?php _e('December', 'woo-kpi-dashboard'); ?></th>
                    <input type="hidden"  name="kpi_license_key" value="<?php if(isset($license_key)) echo $license_key; ?>" />
                    <td><input type="number" name="kpi_december" value="<?php echo esc_attr( get_option('kpi_december') ); if (is_null(get_option('kpi_december'))) echo '0'; ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php 
    } else {
        //If user is not administrator
        ?>
       <div id="setting-error-settings_updated" class="updated settings-error below-h2"><p><strong><?php _e('Sorry, only administrators can change this settings.', 'woo-kpi-dashboard'); ?></strong></p> </div>
    
    <?php
        
        
    }
    ?>
</div>