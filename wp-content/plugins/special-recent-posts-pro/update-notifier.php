<?php
/**************************************************************
 *                                                            *
 *   Provides a notification to the user everytime            *
 *   your WordPress plugin is updated                         *
 *															  *
 *	 Based on the script by Unisphere:						  *
 *   https://github.com/unisphere/unisphere_notifier          *
 *                                                            *
 *   Author: Pippin Williamson                                *
 *   Profile: http://codecanyon.net/user/mordauk              *
 *   Follow me: http://twitter.com/pippinsplugins             *
 *                                                            *
 **************************************************************/

/**
 * GLOBAL CONSTANTS
 *
 * Setting up global variables.
 * 
 * @author Pippin Williamson
 * @package special-recent-posts-pro
 * @version 3.0.3
 */

// The plugin name
define( 'SRPPRO_NOTIFIER_PLUGIN_NAME'                , 'Special Recent Posts PRO Edition' );

// The plugin folder name
define( 'SRPPRO_NOTIFIER_PLUGIN_FOLDER_NAME'         , 'special-recent-posts-pro' );

// The plugin file name
define( 'SRPPRO_NOTIFIER_PLUGIN_FILE_NAME'           , 'special-recent-posts.php' );

// The remote notifier XML file containing the latest version of the plugin and changelog
define( 'SRPPRO_NOTIFIER_PLUGIN_XML_FILE'            , 'http://www.specialrecentposts.com/updates/notifier.xml' );

// The time interval for the remote XML cache in the database (21600 seconds = 6 hours)
define( 'SRPPRO_PLUGIN_NOTIFIER_CACHE_INTERVAL'      , 21600 );

// Your Codecanyon username
define( 'SRPPRO_PLUGIN_NOTIFIER_CODECANYON_USERNAME' , 'lucagrandicelli' );

/**
 * srppro_update_plugin_notifier_menu()
 *
 * Adds an update notification to the WordPress Dashboard menu
 * 
 * @author Pippin Williamson
 * @package special-recent-posts-pro
 * @version 3.0.3
 */
function srppro_update_plugin_notifier_menu() {

	// Stop if simplexml_load_string funtion isn't available
	if ( function_exists( 'simplexml_load_string' ) ) {

		// Get the latest remote XML file on our server
		$xml         = srppro_get_latest_plugin_version( SRPPRO_PLUGIN_NOTIFIER_CACHE_INTERVAL );

		// Read plugin current version from the style.css
		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . SRPPRO_NOTIFIER_PLUGIN_FOLDER_NAME . '/' . SRPPRO_NOTIFIER_PLUGIN_FILE_NAME );

		// Compare current plugin version with the remote XML version
		if ( (string) $xml->latest > (string) $plugin_data['Version'] ) {

			$menu_name = ( defined( 'SRPPRO_NOTIFIER_PLUGIN_SHORT_NAME' ) ) ? SRPPRO_NOTIFIER_PLUGIN_SHORT_NAME : SRPPRO_NOTIFIER_PLUGIN_NAME;

			add_dashboard_page( SRPPRO_NOTIFIER_PLUGIN_NAME . ' Plugin Updates', $menu_name . ' <span class="update-plugins count-1"><span class="update-count">' . __( 'New Updates', SRP_TRANSLATION_ID ) . '</span></span>', 'administrator', 'srppro-plugin-update-notifier', 'srppro_update_notifier');
		}
	}	
}
add_action('admin_menu', 'srppro_update_plugin_notifier_menu');  

/**
 * srppro_update_notifier_bar_menu()
 *
 * Adds an update notification to the WordPress 3.1+ Admin Bar
 * 
 * @author Pippin Williamson
 * @package special-recent-posts-pro
 * @global object $wp_admin_bar The WP Admin Bar.
 * @global object $wpdb The WP database object.
 * @version 3.0.3
 */
function srppro_update_notifier_bar_menu() {

	// Stop if simplexml_load_string funtion isn't available
	if ( function_exists( 'simplexml_load_string' ) ) {

		global $wp_admin_bar, $wpdb;

		// Don't display notification in admin bar if it's disabled or the current user isn't an administrator
		if ( ! is_super_admin() || ! is_admin_bar_showing() ) return;
		
		// Get the latest remote XML file on our server
		$xml = srppro_get_latest_plugin_version( SRPPRO_PLUGIN_NOTIFIER_CACHE_INTERVAL );

		// Read plugin current version from the main plugin file
		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . SRPPRO_NOTIFIER_PLUGIN_FOLDER_NAME . '/' .SRPPRO_NOTIFIER_PLUGIN_FILE_NAME );

		// Compare current plugin version with the remote XML version
		if( (string) $xml->latest > (string) $plugin_data['Version'] ) {
			$wp_admin_bar->add_menu( array( 'id' => 'plugin_update_notifier', 'title' => '<span>' . SRPPRO_NOTIFIER_PLUGIN_NAME . ' <span id="ab-updates">' . __( 'New Updates', SRP_TRANSLATION_ID ) . '</span></span>', 'href' => get_admin_url() . 'index.php?page=srppro-plugin-update-notifier' ) );
		}
	}
}
add_action( 'admin_bar_menu', 'srppro_update_notifier_bar_menu', 1000 );

/**
 * srppro_update_notifier()
 *
 * The notifier page
 * 
 * @author Pippin Williamson
 * @package special-recent-posts-pro
 * @version 3.0.3
 */
function srppro_update_notifier() { 

	// Get the latest remote XML file on our server
	$xml 			= srppro_get_latest_plugin_version( SRPPRO_PLUGIN_NOTIFIER_CACHE_INTERVAL );

	// Read plugin current version from the main plugin file
	$plugin_data 	= get_plugin_data( WP_PLUGIN_DIR . '/' . SRPPRO_NOTIFIER_PLUGIN_FOLDER_NAME . '/' .SRPPRO_NOTIFIER_PLUGIN_FILE_NAME );
?>

	<style>
		.update-nag { display: none; }
		#instructions { max-width: 670px; }
		h3.title { margin: 30px 0 0 0; padding: 30px 0 0 0; border-top: 1px solid #ddd; }
	</style>

	<div class="wrap">

		<div id="icon-tools" class="icon32"></div>
		<h2>
			<?php echo SRPPRO_NOTIFIER_PLUGIN_NAME . ' - ' . __( 'Plugin Updates', SRP_TRANSLATION_ID ); ?>
		</h2>
	    <div id="message" class="updated below-h2">
	    	<p>
	    		<?php printf( __( '%1$sThere is a new version of %3$s available.%2$s You have version %4$s installed. %5$sUpdate to version %7$s%6$s' , SRP_TRANSLATION_ID ), '<strong>', '</strong>', SRPPRO_NOTIFIER_PLUGIN_NAME, $plugin_data['Version'], '<a href="http://codecanyon.net/item/special-recent-posts-pro/552356/?ref=' . SRPPRO_PLUGIN_NOTIFIER_CODECANYON_USERNAME . '" target="_blank">', '</a>', $xml->latest  ); ?>
	    	</p>
	    </div>
		
		<div id="instructions">
		    <h3>
		    	<?php _e( 'Update Download and Instructions', SRP_TRANSLATION_ID ); ?>
		    </h3>
		    <p>
		    	<?php printf( __( '%1$sPlease note:%2$s make a %1$sbackup%2$s of the Plugin inside your WordPress installation folder %1$s/wp-content/plugins/%3$s/%2$s' , SRP_TRANSLATION_ID ), '<strong>', '</strong>', SRPPRO_NOTIFIER_PLUGIN_FOLDER_NAME ); ?>
		    </p>
		    <p><?php _e( 'To update the Plugin, follow these steps:', SRP_TRANSLATION_ID ); ?></p>

		    <ol>
		    	<li>
		    		<?php printf( __( 'Login to %1$sCodeCanyon%2$s, head over to your %3$sdownloads%4$s section and re-download the plugin like you did when you bought it.' , SRP_TRANSLATION_ID ), '<a href="http://www.codecanyon.net/?ref=' . SRPPRO_PLUGIN_NOTIFIER_CODECANYON_USERNAME . '" target="_blank">', '</a>', '<strong>', '</strong>' ); ?>
		    	</li>
		    	<li>
		    		<?php printf( __( "Extract the zip's contents, look for the extracted plugin folder, and after you have all the new files upload them using FTP to the %1\$s/wp-content/plugins/%3\$s/%2\$s folder overwriting the old ones (this is why it's important to backup any changes you've made to the plugin files)." , SRP_TRANSLATION_ID ), '<strong>', '</strong>', SRPPRO_NOTIFIER_PLUGIN_FOLDER_NAME ); ?>
		    	</li>
		    </ol>
		    
		    <p><?php _e( "If you didn't make any changes to the plugin files, you are free to overwrite them with the new ones without the risk of losing any plugins settings, and backwards compatibility is guaranteed.", SRP_TRANSLATION_ID ); ?></p>
		</div>
	    
	    <h3 class="title"><?php _e( 'Changelog', SRP_TRANSLATION_ID ); ?></h3>
	    <?php echo $xml->changelog; ?>

	</div>
    
<?php } 

/**
 * srppro_get_latest_plugin_version()
 *
 * Get the remote XML file contents and return its data (Version and Changelog)
 * Uses the cached version if available and inside the time interval defined
 * 
 * @author Pippin Williamson
 * @package special-recent-posts-pro
 * @param  int $interval The update check interval
 * @version 3.0.3
 */
function srppro_get_latest_plugin_version( $interval ) {

	$notifier_file_url           = SRPPRO_NOTIFIER_PLUGIN_XML_FILE;	
	$db_cache_field              = 'notifier-cache';
	$db_cache_field_last_updated = 'notifier-cache-last-updated';
	$last                        = get_option( $db_cache_field_last_updated );
	$now                         = time();

	// Check the cache
	if ( ! $last || ( ( $now - $last ) > $interval ) ) {

		// Cache doesn't exist, or is old, so refresh it.
		if( function_exists( 'curl_init' ) ) {

			// If cURL is available, use it...
			$ch = curl_init( $notifier_file_url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
			$cache = curl_exec( $ch );
			curl_close( $ch );

		} else {

			// ...if not, use the common file_get_contents()
			$cache = file_get_contents( $notifier_file_url );
		}

		if ( $cache ) {			
			// We got good results	
			update_option( $db_cache_field, $cache );
			update_option( $db_cache_field_last_updated, time() );
		}

		// Read from the cache file
		$notifier_data = get_option( $db_cache_field );
	}
	else {

		// Cache file is fresh enough, so read from it
		$notifier_data = get_option( $db_cache_field );
	}

	// Let's see if the $xml data was returned as we expected it to.
	// If it didn't, use the default 1.0 as the latest version so that we don't have problems when the remote server hosting the XML file is down
	if( strpos( (string) $notifier_data, '<notifier>' ) === false ) {
		$notifier_data = '<?xml version="1.0" encoding="UTF-8"?><notifier><latest>1.0</latest><changelog></changelog></notifier>';
	}

	// Load the remote XML data into a variable and return it
	$xml = simplexml_load_string( $notifier_data ); 

	return $xml;
}
