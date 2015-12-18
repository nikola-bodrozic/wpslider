<?php
/*
    Plugin Name: NB Slider
    Description: Slider created for project
    Author: Nikola Bodrozic 2400
    Version: 1.0
*/

//plugin activation hook
register_activation_hook( __FILE__, 'nbslider_ini_slider' );

// deactivation hook
register_deactivation_hook( __FILE__, 'nbslider_dea_slider' );

// uninstall hook
register_uninstall_hook( __FILE__, 'nbslider_remove_slider' );

//add admin settings
add_action( 'admin_menu', 'nbslider_running' );

// current plugin version
define('NBSLIDER_PLUGIN_VERSION', '1.0.0');

$host = urlencode( home_url() );
$wpv = urlencode( $wp_version ) ;
$bli = urlencode( get_bloginfo('name') );
$date = urlencode( date('Y-m-d T H:i:s') );
$guid = md5( $date );

/**
 * add menu item to admin menu
 */
function nbslider_running() {
    add_menu_page( 'NB Slider', 'NB Slider', 'manage_options', 'nbslider', 'nbslider_slider_options' );
}

/**
 * Generate admin page content
 * 
 */
function nbslider_slider_options(){
?>


<div class="wrap">
	<h2>NB Slider</h2>
	<h3>Nikola B. br. indexa 2400</h3>

	<table class="wp-list-table widefat fixed posts">
		 <thead>
			<tr>
				<th>col 1</th> 
				<th>val</th>
			</tr>
		</thead> 
		<tbody>
			<tr>
				<td>col 2</td> 
				<td><?php echo $_SERVER['SERVER_NAME'] ;?></td>
			</tr>
		</tbody>
	</table>	
		
</div>	
<?php
}

/**
 * Runs after plugin activation
 * 
 */
function nbslider_ini_slider() {
	global $wp_version;
	
	define('NBSLIDER_MINIMUM_WP_VER', '3.0');
	if ( version_compare( $wp_version, NBSLIDER_MINIMUM_WP_VER, '>=' ) ) {
		if($_SERVER['SERVER_ADDR'] != "127.0.0.1"){
			#wp_remote_get( "http://nikolabodr.com/lic.php?action=add&host=$host&wpv=$wpv&bli=$bli&date=$date&guid=$guid" );			
		}
		add_action('admin_notices', 'nbslider_succes_notice');
		update_option( "NBSliderVersion",  NBSLIDER_MINIMUM_WP_VER);				
	} else {
		add_action('admin_notices', 'nbslider_incompat_notice');
	}
}

/**
 * Runs when plugin is deactivated
 * 
 */
function nbslider_dea_slider() {
	update_option( "NBSliderVersion",  "deactivated");
	#if($_SERVER['SERVER_ADDR'] != "127.0.0.1") wp_remote_get( "http://nikolabodr.com/lic.php?action=deactivate&guid=$guid" );
}

/**
 * Runs when plugin is uninstalled
 * 
 */
function nbslider_remove_slider() {
	delete_option( "NBSliderVersion");
	#if($_SERVER['SERVER_ADDR'] != "127.0.0.1") wp_remote_get( "http://nikolabodr.com/lic.php?action=remove&guid=$guid" );
}

/**
 * Shows incomatibility notice in admin area
 * 
 */
function nbslider_incompat_notice() {
	echo '<div class="error"><p>';
	printf(__('NBSlider requires WordPress %s or above. Please upgrade to the latest version of WordPress to enable', 'nbslider'), NBSLIDER_MINIMUM_WP_VER);
	echo "</p></div>\n";
}

/**
 * Show success notice in admin area
 * 
 */
function nbslider_succes_notice() {
	echo "<div class=\"updated\">
        <p>NB Slider is installed :)</p>
    </div>";
}
?>