<?php
/*
 * Plugin Name: RC Google Analytics
 * Plugin URI: http://www.geoffreyrickaby.com
 * Description: RC Google Analytics is a simple plugin that integrates the Google Analytics tracking code into your Footer. It allows you to turn off and on the tracking code; insert a custom tracking code; and allows for the Display Advertising tracker.
 * Version: 1.0
 * Author: Geoffrey Rickaby
 * Author URI: http://www.geoffreyrickaby.com/
 * 
 * Patch Notes:
 * V 1.0 - Initial Release
 */
$version = '1.0';

add_action('admin_init', 'plugin_init');
add_action('admin_menu', 'option_menu');
add_action('wp_head', 'display_tracker', 10);


/** Register Settings */
function plugin_init() {
    register_setting('settings_group', 'rcga_options', 'rcga_validate');
}

/** Add WordPress Google Analytics to the Settings Menu * */
function option_menu() {
    add_options_page('RC Google Analytics', 'RC Google Analytics', 'manage_options', 'rc-google-analytics', 'plugin_options');
}

/** Plugin Options Page */
function plugin_options() {
    global $version;
	$options = get_option('rcga_options');
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2>RC Google Analytics Options</h2>
        <form method="post" action="options.php" style="float:left;width:400px;margin-right:20px;font-size:12px;">
            <?php settings_fields('settings_group'); ?>
            <br/>
            <label>Google Analytic ID:</label>
            <input name="rcga_options[tracker_id]" type="text" placeholder="UA-XXXXX-Y" value="<?php echo $options['tracker_id'] ?>" />
            <br/><br/>
            <input type="checkbox" name="rcga_options[default]" value="1" <?php checked('1', $options['default']); ?> />
            <label>Default Tracking Code</label>
            <br/>
            <input type="checkbox" name="rcga_options[display_advertising]" value="1" <?php checked('1', $options['display_advertising']); ?> />
            <label>Display Advertising Tracking Code</label>
            <br/>
            <br/>
            <label for="custom_traker">Custom Tracker Code</label><br/>
            <textarea name="rcga_options[custom_tracker]" style="width:400px;height:200px;"><?php echo $options['custom_tracker'] ?></textarea>
            <br/>
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </form>
        <div style="float:left;border:1px solid #000;background:#ebebeb;width:400px;padding:7px;">
            <font size="4"><strong>Option Definitions</strong></font>
            <p><strong>Google Analytic ID -</strong> This is a UA-XXXX-Y code that you get directly from Google Analytics. You must have something in this box for the tracker to work.</p>
            <p><strong>Default Tracking Code -</strong> This needs to be checked in order to display the Google Analytic tracking code. Without this box checked, Google Analytics will not be able to track your site stats. However if you have a custom tracking code, you can un-check this box.</p>
            <p><strong>Advertising Tracking Code -</strong> The Display Advertising code is used to enable Remarketing with Google Analytics or Google Display Network (GDN) Impression Reporting. For more information please read <a href="https://support.google.com/analytics/answer/2444872?hl=en&utm_id=ad" target="_blank">Google Help</a> topic on the subject.</p>
            <p><strong>Custom Code -</strong> The custom code box allows you to copy any Google Analytic code that they provide, and paste it into this box. This box is good for when you have eCommerce tracking, or Multi-Domain tracking</p>
            <p><font size="4"><strong>Support</strong></font></p>
            <p>Thank you for trying out my plugin! If you are having issues please <a href="http://www.GeoffreyRickaby.com/contact" target="_blank" >contact me</a> for support.</p>
            <p><font size="4"><strong>Help support future plugins like these!</strong></font></p>
            <p><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="7F55F6ULWRQ6E">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form></p>
            <p style="font-size:9px;">Version: <?php echo $version; ?></p>
        </div>
    </div>
<?php }

function rcga_validate($input) {
    $input['default'] = ( $input['default'] == 1 ? 1 : 0 );
    $input['display_advertising'] = ( $input['display_advertising'] == 1 ? 1 : 0 );

    return $input;}

/** Tracker Codes */
function display_tracker() {
	global $version;
	$options = get_option('rcga_options');
	
    if (isset($options['tracker_id']))
        switch ($options) {
            case $options['default'] == '1':
                ?>
<!-- RC Google Analytics Version <?php echo $version;?> -->
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', '<?php echo $options['tracker_id']; ?>']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script');
	ga.type = 'text/javascript';
	ga.async = true;
<?php if ($options['display_advertising'] == '1') { ?>
	ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
<?php } Else { ?>
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
<?php } ?>
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(ga, s);
	})();
</script>
<!-- End RC Google Analytics -->
<?php
break;
case isset($options['custom_tracker']) :
echo $options['custom_tracker'];
	}
}
