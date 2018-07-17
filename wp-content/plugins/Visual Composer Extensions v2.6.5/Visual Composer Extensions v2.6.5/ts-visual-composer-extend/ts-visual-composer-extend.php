<?php
/*
Plugin Name:    Visual Composer Extensions
Plugin URI:     http://codecanyon.net/item/visual-composer-extensions/7190695
Description:    A plugin to add new premium content elements, custom post types and icon fonts to Visual Composer
Author:         Tekanewa Scripts
Author URI:     http://www.tekanewascripts.info/composer/?preview
Version:        2.6.5
Text Domain:    ts_visual_composer_extend
Domain Path:	/locale
*/
if (!defined('ABSPATH')) exit;

// Check for Visual Composer
// -------------------------
if (!defined('__VC_EXTENSIONS__')){
	define('__VC_EXTENSIONS__', dirname(__FILE__));
}


// Main Class for Visual Composer Extensions
// -----------------------------------------
if (!class_exists('VISUAL_COMPOSER_EXTENSIONS')) {
	add_action('admin_init', 					'TS_VCSC_Init_Addon');
	function TS_VCSC_Init_Addon() {
		$required_vc 	= '3.9.9';
		if (defined('WPB_VC_VERSION')){
			if (version_compare($required_vc, WPB_VC_VERSION, '>')) {
			//if (TS_VCSC_VersionCompare(WPB_VC_VERSION, '3.9.9') >= 0) {
				add_action('admin_notices', 	'TS_VCSC_Admin_Notice_Version');
			}
		} else {
			add_action('admin_notices', 		'TS_VCSC_Admin_Notice_Activation');
		}
	}
	function TS_VCSC_Admin_Notice_Version() {
		echo '<div class="updated"><p>The <strong>Visual Composer Extensions</strong> plugin requires <strong>Visual Composer</strong> version 4.0.0 or greater.</p></div>';	
	}
	function TS_VCSC_Admin_Notice_Activation() {
		echo '<div class="updated"><p>The <strong>Visual Composer Extensions</strong> plugin requires <strong>Visual Composer</strong> Plugin installed and activated.</p></div>';
	}
	function TS_VCSC_Admin_Notice_Network() {
		echo '<div class="updated"><p>The <strong>Visual Composer Extensions</strong> plugin can not be activated network-wide but only on individual sub-sites.</p></div>';
	}
	
	
	// WordPres Register Hooks
	// -----------------------
	register_activation_hook(__FILE__, 		array('VISUAL_COMPOSER_EXTENSIONS', 	'TS_VCSC_On_Activation'));
	register_deactivation_hook(__FILE__, 	array('VISUAL_COMPOSER_EXTENSIONS', 	'TS_VCSC_On_Deactivation'));
	register_uninstall_hook(__FILE__, 		array('VISUAL_COMPOSER_EXTENSIONS', 	'TS_VCSC_On_Uninstall'));
	
	
	
	// Create Plugin Class
	// -------------------
	class VISUAL_COMPOSER_EXTENSIONS {
		// Functions for Plugin Activation / Deactivation / Uninstall
		// ----------------------------------------------------------
		public static function TS_VCSC_On_Activation($network_wide) {
			if (!current_user_can('activate_plugins')) {
				return;
			}
			if (get_option('ts_vcsc_extend_settings_extended', 0) == 0) {
				//$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
				//check_admin_referer( "activate-plugin_{$plugin}" );
				if ($network_wide) {
					deactivate_plugins(plugin_basename( __FILE__ ), TRUE, TRUE);
					wp_die('<strong>Visual Composer Extensions</strong><br/><br/>This plugin can not be activated network wide.<br/>
						   Please activate only on the sub-site the plugin will actually be used on.<br/><br/>
						   <a href="' . network_admin_url( 'plugins.php?deactivate=true') . '">Back to Plugins</a>');
					//header( 'Location: ' . network_admin_url( 'plugins.php?deactivate=true'));
					//exit;
				}
			}			
			// Options for License Data
			add_option('ts_vcsc_extend_settings_demo', 					            1);
			add_option('ts_vcsc_extend_settings_updated', 				            0);
			add_option('ts_vcsc_extend_settings_created', 				            0);
			add_option('ts_vcsc_extend_settings_deleted', 				            0);
			add_option('ts_vcsc_extend_settings_license', 				            '');
			add_option('ts_vcsc_extend_settings_licenseUpdate',						0);
			add_option('ts_vcsc_extend_settings_licenseInfo',						'');
			add_option('ts_vcsc_extend_settings_licenseKeyed',						'emptydelimiterfix');
			add_option('ts_vcsc_extend_settings_licenseValid',						0);
			add_option('ts_vcsc_extend_settings_extended', 				            0);
			// Options for Update Data
			add_option('ts_vcsc_extend_settings_versionCurrent', 				    '');
			add_option('ts_vcsc_extend_settings_versionLatest', 				    '');
			add_option('ts_vcsc_extend_settings_updateAvailable', 				    0);
			// Options for Theme Authors
			add_option('ts_vcsc_extend_settings_posttypes', 				        1);
			add_option('ts_vcsc_extend_settings_posttypeTeam',						1);
			add_option('ts_vcsc_extend_settings_posttypeTestimonial',				1);
			add_option('ts_vcsc_extend_settings_posttypeLogo', 						1);
			add_option('ts_vcsc_extend_settings_posttypeSkillset',					1);
			add_option('ts_vcsc_extend_settings_additions', 				        1);
			add_option('ts_vcsc_extend_settings_codeeditors', 				        1);
			add_option('ts_vcsc_extend_settings_fontimport', 				        1);
			add_option('ts_vcsc_extend_settings_iconicum', 				        	1);
			add_option('ts_vcsc_extend_settings_dashboard', 						1);
			// Options for Custom CSS/JS Editor
			add_option('ts_vcsc_extend_settings_customCSS',							'/* Welcome to the Custom CSS Editor! Please add all your Custom CSS here. */');
			add_option('ts_vcsc_extend_settings_customJS', 				            '/* Welcome to the Custom JS Editor! Please add all your Custom JS here. */');
			// Other Options
			add_option('ts_vcsc_extend_settings_buffering', 						1);
			add_option('ts_vcsc_extend_settings_mainmenu', 							1);
			add_option('ts_vcsc_extend_settings_translationsDomain', 				1);
			// Font Active / Inactive
			add_option('ts_vcsc_extend_settings_tinymceMedia',						1);
			add_option('ts_vcsc_extend_settings_tinymceIcon',						1);
			add_option('ts_vcsc_extend_settings_tinymceAwesome',					1);
			add_option('ts_vcsc_extend_settings_tinymceBrankic',					0);
			add_option('ts_vcsc_extend_settings_tinymceCountricons',				0);
			add_option('ts_vcsc_extend_settings_tinymceCurrencies',					0);
			add_option('ts_vcsc_extend_settings_tinymceElegant',					0);
			add_option('ts_vcsc_extend_settings_tinymceEntypo',						0);
			add_option('ts_vcsc_extend_settings_tinymceFoundation',					0);
			add_option('ts_vcsc_extend_settings_tinymceGenericons',					0);
			add_option('ts_vcsc_extend_settings_tinymceIcoMoon',					0);
			add_option('ts_vcsc_extend_settings_tinymceMonuments',					0);
			add_option('ts_vcsc_extend_settings_tinymceSocialMedia',				0);
			add_option('ts_vcsc_extend_settings_tinymceTypicons',					0);
			// Custom Font Data
			add_option('ts_vcsc_extend_settings_IconFontSettings',					'');
			add_option('ts_vcsc_extend_settings_tinymceCustom',						0);
			add_option('ts_vcsc_extend_settings_tinymceCustomArray',				'');
			add_option('ts_vcsc_extend_settings_tinymceCustomJSON',					'');
			add_option('ts_vcsc_extend_settings_tinymceCustomPath',					'');
			add_option('ts_vcsc_extend_settings_tinymceCustomPHP', 					'');
			add_option('ts_vcsc_extend_settings_tinymceCustomName',					'Custom User Font');
			add_option('ts_vcsc_extend_settings_tinymceCustomAuthor',				'Custom User');
			add_option('ts_vcsc_extend_settings_tinymceCustomCount',				0);
			add_option('ts_vcsc_extend_settings_tinymceCustomDate',					'');
			add_option('ts_vcsc_extend_settings_tinymceCustomDirectory',			'');
			// Row + Column Extensions
			add_option('ts_vcsc_extend_settings_additionsRows',						0);
			add_option('ts_vcsc_extend_settings_additionsColumns',					0);
			add_option('ts_vcsc_extend_settings_additionsSmoothScroll',				0);
			add_option('ts_vcsc_extend_settings_additionsSmoothSpeed',				'1500');
			// Custom Post Types
			add_option('ts_vcsc_extend_settings_customTeam',						0);
			add_option('ts_vcsc_extend_settings_customTestimonial',					0);
			add_option('ts_vcsc_extend_settings_customLogo', 						0);
			add_option('ts_vcsc_extend_settings_customSkillset',					0);
			// tinyMCE Icon Shortcode Generator
			add_option('ts_vcsc_extend_settings_useIconGenerator',					0);
			add_option('ts_vcsc_extend_settings_useTinyMCEMedia', 					1);
			// Standard Elements
			add_option('ts_vcsc_extend_settings_StandardElements',					'');
			// WooCommerce Elements
			add_option('ts_vcsc_extend_settings_WooCommerceUse',					0);
			add_option('ts_vcsc_extend_settings_WooCommerceElements',				'');
			// Options for External Files
			add_option('ts_vcsc_extend_settings_loadForcable',						0);
			add_option('ts_vcsc_extend_settings_loadLightbox', 						0);
			add_option('ts_vcsc_extend_settings_loadTooltip', 						0);
			add_option('ts_vcsc_extend_settings_loadFonts', 						0);
			add_option('ts_vcsc_extend_settings_loadEnqueue',						1);
			add_option('ts_vcsc_extend_settings_loadHeader',						0);
			add_option('ts_vcsc_extend_settings_loadjQuery', 						0);
			add_option('ts_vcsc_extend_settings_loadModernizr',						1);
			add_option('ts_vcsc_extend_settings_loadWaypoints', 					1);
			add_option('ts_vcsc_extend_settings_loadCountTo', 						1);
			add_option('ts_vcsc_extend_settings_loadDetector', 						0);
			// Language Settings: Countdown
			$TS_VCSC_Countdown_Language_Defaults_Init = array(
				'DayPlural'                     => 'Days',
				'DaySingular'                   => 'Day',
				'HourPlural'                    => 'Hours',
				'HourSingular'                  => 'Hour',
				'MinutePlural'                  => 'Minutes',
				'MinuteSingular'                => 'Minute',
				'SecondPlural'                  => 'Seconds',
				'SecondSingular'                => 'Second',
			);
			add_option('ts_vcsc_extend_settings_translationsCountdown', 			$TS_VCSC_Countdown_Language_Defaults_Init);
			// Language Settings: Google Map
			$TS_VCSC_Google_Map_Language_Defaults_Init = array(
				'TextCalcShow'              	=> 'Show Address Input',
				'TextCalcHide'                  => 'Hide Address Input',
				'TextDirectionShow'             => 'Show Directions',
				'TextDirectionHide'             => 'Hide Directions',
				'TextResetMap'                  => 'Reset Map',
				'PrintRouteText' 			    => 'Print Route',
				'TextViewOnGoogle'              => 'View on Google',
				'TextButtonCalc'                => 'Show Route',
				'TextSetTarget'                 => 'Please enter your Start Address:',
				'TextTravelMode'                => 'Travel Mode',
				'TextDriving'                   => 'Driving',
				'TextWalking'                   => 'Walking',
				'TextBicy'                      => 'Bicycling',
				'TextWP'                        => 'Optimize Waypoints',
				'TextButtonAdd'                 => 'Add Stop on the Way',
				'TextDistance'                  => 'Total Distance:',
				'TextMapHome'                   => 'Home',
				'TextMapBikes'                  => 'Bicycle Trails',
				'TextMapTraffic'                => 'Traffic',
				'TextMapSpeedMiles'             => 'Miles Per Hour',
				'TextMapSpeedKM'                => 'Kilometers Per Hour',
				'TextMapNoData'                 => 'No Data Available!',
				'TextMapMiles'                  => 'Miles',
				'TextMapKilometes'              => 'Kilometers',
			);
			add_option('ts_vcsc_extend_settings_translationsGoogleMap', 			$TS_VCSC_Google_Map_Language_Defaults_Init);
			// Language Settings: Isotope Posts
			$TS_VCSC_Isotope_Posts_Language_Defaults_Init = array(
				'ButtonFilter'		            => 'Filter Posts',
				'ButtonLayout'		            => 'Change Layout',
				'ButtonSort'		            => 'Sort Criteria',
				'SeeAll'			            => 'See All',
				'Timeline' 			            => 'Timeline',
				'Masonry' 			            => 'Centered Masonry',
				'FitRows'			            => 'Fit Rows',
				'StraightDown' 		            => 'Straigt Down',
				'Date' 				            => 'Post Date',
				'Modified' 			            => 'Post Modified',
				'Title' 			            => 'Post Title',
				'Author' 			            => 'Post Author',
				'PostID' 			            => 'Post ID',
				'Comments' 			            => 'Number of Comments',
				'Categories'                    => 'Categories',
				'Tags'                          => 'Tags',
			);
			add_option('ts_vcsc_extend_settings_translationsIsotopePosts', 			$TS_VCSC_Isotope_Posts_Language_Defaults_Init);
			// Options for Lightbox Settings
			$TS_VCSC_Lightbox_Setting_Defaults_Init = array(
				'thumbs'                        => 'bottom',
				'thumbsize'                     => 50,
				'animation'                     => 'random',
				'captions'                      => 'data-title',
				'duration'                      => 5000,
				'share'                         => 1, // true/false
				'social' 	                    => 'fb,tw,gp,pin',
				'notouch'                       => 1, // true/false
				'bgclose'			            => 1, // true/false
				'nohashes'			            => 1, // true/false
				'keyboard'			            => 1, // 0/1
				'fullscreen'		            => 1, // 0/1
				'zoom'				            => 1, // 0/1
				'fxspeed'			            => 300,
				'scheme'			            => 'dark',
				'removelight'               	=> 0,
				'backlight' 		            => '#ffffff',
				'usecolor' 		                => 0, // true/false
			);
			add_option('ts_vcsc_extend_settings_defaultLightboxSettings',			$TS_VCSC_Lightbox_Setting_Defaults_Init);
			// Options for Envato Sales Data
			add_option('ts_vcsc_extend_settings_envatoInfo', 					    '');
			add_option('ts_vcsc_extend_settings_envatoLink', 					    '');
			add_option('ts_vcsc_extend_settings_envatoPrice', 					    '');
			add_option('ts_vcsc_extend_settings_envatoRating', 					    '');
			add_option('ts_vcsc_extend_settings_envatoSales', 					    '');
			$roles = get_editable_roles();
			foreach ($GLOBALS['wp_roles']->role_objects as $key => $role) {
				if (isset($roles[$key]) && $role->has_cap('edit_pages') && !$role->has_cap('ts_vcsc_extend')) {
					$role->add_cap('ts_vcsc_extend');
				}
			}
		}
		public static function TS_VCSC_On_Deactivation() {
			if (!current_user_can( 'activate_plugins')) {
				return;
			}
			if (get_option('ts_vcsc_extend_settings_extended', 0) == 0) {
				//$plugin = isset($_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
				//check_admin_referer("deactivate-plugin_{$plugin}");
			}
			$roles = get_editable_roles();
			foreach ($GLOBALS['wp_roles']->role_objects as $key => $role) {
				if (isset($roles[$key]) && $role->has_cap('ts_vcsc_extend')) {
					$role->remove_cap('ts_vcsc_extend');
				}
			}
		}
		public static function TS_VCSC_On_Uninstall() {
			if (!current_user_can( 'activate_plugins')) {
				return;
			}
			if (get_option('ts_vcsc_extend_settings_extended', 0) == 0) {
				//check_admin_referer('bulk-plugins');
			}
			if ( __FILE__ != WP_UNINSTALL_PLUGIN) {
				return;
			}
			TS_VCSC_DeleteOptionsPrefixed('ts_vcsc_extend_settings_');
			unregister_setting('ts_vcsc_extend_custom_css', 	'ts_vcsc_extend_custom_css', 		array($this, 	'TS_VCSC_CustomCSS_Validation'));
			unregister_setting('ts_vcsc_extend_custom_js', 		'ts_vcsc_extend_custom_js', 		array($this, 	'TS_VCSC_CustomJS_Validation'));
			delete_option("ts_vcsc_extend_custom_css");
			delete_option("ts_vcsc_extend_custom_js");
			$roles = get_editable_roles();
			foreach ($GLOBALS['wp_roles']->role_objects as $key => $role) {
				if (isset($roles[$key]) && $role->has_cap('ts_vcsc_extend')) {
					$role->remove_cap('ts_vcsc_extend');
				}
			}
		}

		public $TS_VCSC_List_Icons_Awesome			= array();
		public $TS_VCSC_List_Icons_Brankic			= array();
		public $TS_VCSC_List_Icons_Countricons		= array();
		public $TS_VCSC_List_Icons_Currencies		= array();
		public $TS_VCSC_List_Icons_Elegant			= array();
		public $TS_VCSC_List_Icons_Entypo			= array();
		public $TS_VCSC_List_Icons_Foundation		= array();
		public $TS_VCSC_List_Icons_Genericons		= array();
		public $TS_VCSC_List_Icons_IcoMoon			= array();
		public $TS_VCSC_List_Icons_Ionicons			= array();
		public $TS_VCSC_List_Icons_Metrize			= array();
		public $TS_VCSC_List_Icons_Monuments		= array();
		public $TS_VCSC_List_Icons_SocialMedia		= array();
		public $TS_VCSC_List_Icons_Themify			= array();
		public $TS_VCSC_List_Icons_Typicons			= array();
		public $TS_VCSC_List_Icons_Custom			= array();
		
		public $TS_VCSC_VisualComposer_Version		= '';
		
		public $TS_VCSC_tinymceAwesomeCount			= '';
		public $TS_VCSC_tinymceBrankicCount			= '';
		public $TS_VCSC_tinymceCountriconsCount		= '';
		public $TS_VCSC_tinymceCurrenciesCount		= '';
		public $TS_VCSC_tinymceElegantCount			= '';
		public $TS_VCSC_tinymceEntypoCount			= '';
		public $TS_VCSC_tinymceFoundationCount		= '';
		public $TS_VCSC_tinymceGenericonsCount		= '';
		public $TS_VCSC_tinymceIcoMoonCount			= '';
		public $TS_VCSC_tinymceIoniconsCount		= '';
		public $TS_VCSC_tinymceMetrizeCount			= '';
		public $TS_VCSC_tinymceMonumentsCount		= '';
		public $TS_VCSC_tinymceSocialMediaCount		= '';
		public $TS_VCSC_tinymceThemifyCount			= '';
		public $TS_VCSC_tinymceTypiconsCount		= '';
		public $TS_VCSC_tinymceCustomCount			= '';
		
		public $TS_VCSC_LoadFrontEndForcable		= "false";
		public $TS_VCSC_LoadFrontEndJQuery			= "false";
		public $TS_VCSC_LoadFrontEndWaypoints		= "true";
		public $TS_VCSC_LoadFrontEndModernizr		= "true";
		public $TS_VCSC_LoadFrontEndCountTo			= "true";
		public $TS_VCSC_LoadFrontEndCountUp			= "true";
		public $TS_VCSC_LoadFrontEndLightbox		= "false";
		public $TS_VCSC_LoadFrontEndTooltips		= "false";
		
		public $TS_VCSC_CustomPostTypesCheckup		= "true";
		public $TS_VCSC_CustomPostTypesTeam			= "true";
		public $TS_VCSC_CustomPostTypesTestimonial	= "true";
		public $TS_VCSC_CustomPostTypesLogo			= "true";
		public $TS_VCSC_CustomPostTypesSkillset		= "true";
		
		public $TS_VCSC_UserDeviceType				= "Desktop";
		public $TS_VCSC_tinymceFontsAll				= "false";
		public $TS_VCSC_VCFrontEditMode				= "false";
		public $TS_VCSC_WooCommerceActive			= "false";
		public $TS_VCSC_WooCommerceVersion			= "";
		public $TS_VCSC_IconicumStandard			= "false";

		function __construct() {
			$this->assets_js 		= plugin_dir_path( __FILE__ ).'js/';
			$this->assets_css 		= plugin_dir_path( __FILE__ ).'css/';
			$this->assets_dir 		= plugin_dir_path( __FILE__ ).'assets/';
			$this->classes_dir 		= plugin_dir_path( __FILE__ ).'classes/';
			$this->elements_dir 	= plugin_dir_path( __FILE__ ).'elements/';
			$this->shortcode_dir 	= plugin_dir_path( __FILE__ ).'shortcodes/';
			$this->plugins_dir 		= plugin_dir_path( __FILE__ ).'plugins/';
			$this->woocommerce_dir 	= plugin_dir_path( __FILE__ ).'woocommerce/';
			$this->posttypes_dir 	= plugin_dir_path( __FILE__ ).'posttypes/';
			$this->templates_dir 	= plugin_dir_path( __FILE__ ).'templates/';
			$this->images_dir 		= plugin_dir_path( __FILE__ ).'images/';
			$this->fonts_dir 		= plugin_dir_path( __FILE__ ).'fonts/';
			$this->widgets_dir 		= plugin_dir_path( __FILE__ ).'widgets/';
			$this->detector_dir 	= plugin_dir_path( __FILE__ ).'detector/';
			
			// Check and Store VC Version
			// --------------------------
			if (defined('WPB_VC_VERSION')){
				$this->TS_VCSC_VisualComposer_Version 	= WPB_VC_VERSION;
			} else {
				$this->TS_VCSC_VisualComposer_Version 	= '';
			}
			
			// Load Public Arrays that Define Element Settings
			// -----------------------------------------------
			require_once($this->assets_dir . 'ts_vcsc_arrays_public.php');
			
			//ksort($this->TS_VCSC_Visual_Composer_Elements);
			
			// Status of WooCommerce Elements
			// ------------------------------
			if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
				$this->TS_VCSC_WooCommerceVersion 		= $this->TS_VCSC_WooCommerceVersion();
				$this->TS_VCSC_WooCommerceActive 		= "true";
			} else {
				$this->TS_VCSC_WooCommerceVersion 		= "";
				$this->TS_VCSC_WooCommerceActive 		= "false";
			}
			
			// Check for Standalone Iconicum Plugin
			// ------------------------------------
			if ((in_array('ts-iconicum-icon-fonts/ts-iconicum-icon-fonts.php', apply_filters('active_plugins', get_option('active_plugins')))) || (class_exists('ICONICUM_ICON_FONTS'))) {
				$this->TS_VCSC_IconicumStandard			= "true";
			} else {
				$this->TS_VCSC_IconicumStandard			= "false";
			}
			
			// Load Class for Mobile / Desktop Detection
			// -----------------------------------------
			if ((!class_exists('TS_Mobile_Detect')) && (get_option('ts_vcsc_extend_settings_loadDetector', 0) == 1)) {
				require_once($this->detector_dir . 'ts_mobile_detect.php');
			}
			if ((class_exists('TS_Mobile_Detect')) && (get_option('ts_vcsc_extend_settings_loadDetector', 0) == 1)) {
				$TS_VCSC_Detector_Class         		= new TS_Mobile_Detect;
				$this->TS_VCSC_UserDeviceType 			= ($TS_VCSC_Detector_Class->isMobile() ? ($TS_VCSC_Detector_Class->isTablet() ? "Tablet" : "Mobile") : "Desktop");
				unset($TS_VCSC_Detector_Class);
			}
			
			// Load and Initialize the Auto-Update Class
			// -----------------------------------------
			if ((get_option('ts_vcsc_extend_settings_demo', 1) == 0) && (get_option('ts_vcsc_extend_settings_extended', 0) == 0) && ((strpos(get_option('ts_vcsc_extend_settings_licenseInfo', ''), get_option('ts_vcsc_extend_settings_licenseKeyed', 'emptydelimiterfix')) != FALSE))) {
				add_action('admin_init', 				array($this, 'TS_VCSC_ActivateAutoUpdate'));
			}

			// Load Arrays of Font Settings
			// ----------------------------
			add_action('init', 							array($this, 	'TS_VCSC_IconFontArrays'), 1);
			
			// Load Language / Translation Files
			// ---------------------------------
			if (get_option('ts_vcsc_extend_settings_translationsDomain', 1) == 1) {
				add_action('init',						array($this, 	'TS_VCSC_LoadTextDomains'), 9);
			}
			
			// Load Arrays of Other Selection Items
			// ------------------------------------
			require_once($this->assets_dir.'ts_vcsc_arrays_other.php');
			
			$plugin = plugin_basename( __FILE__ );
			add_filter("plugin_action_links_$plugin", 	array($this, 	"TS_VCSC_PluginAddSettingsLink"));
			if (((get_option('ts_vcsc_extend_settings_licenseValid', 0) == 1) && ((strpos(get_option('ts_vcsc_extend_settings_licenseInfo', ''), get_option('ts_vcsc_extend_settings_licenseKeyed', 'emptydelimiterfix')) != FALSE))) || (get_option('ts_vcsc_extend_settings_extended', 0) == 1)) {
				update_option('ts_vcsc_extend_settings_demo', 0);
			} else {
				update_option('ts_vcsc_extend_settings_demo', 1);
			}
			
			// Register Custom CSS and JS Inputs
			// ---------------------------------
			if (get_option('ts_vcsc_extend_settings_codeeditors', 1) == 1) {
				add_action('admin_init', 				array($this, 	'TS_VCSC_RegisterCustomCSS_Setting'));
				add_action('admin_init', 				array($this, 	'TS_VCSC_RegisterCustomJS_Setting'));
			}
			
			// Register Scripts and Styles
			// ---------------------------
			add_action('init', 							array($this, 	'TS_VCSC_Extensions_Registrations'));
			
			// Create Custom Admin Menu for Plugin
			// -----------------------------------
			add_action('admin_menu', 					array($this, 	'TS_VCSC_SyncMenu'));
			
			// Function to load External Files on Back-End
			// -------------------------------------------
			add_action('admin_enqueue_scripts', 		array($this, 	'TS_VCSC_Extensions_Admin_Files'));
			add_action('admin_head', 					array($this, 	'TS_VCSC_Extensions_Admin_Variables'));
			
			// Function to load External Files on Front-End
			// --------------------------------------------
			add_action('wp_enqueue_scripts', 			array($this, 	'TS_VCSC_Extensions_Front_Main'), 		999999999999999999999999999);
			add_action('wp_head', 						array($this, 	'TS_VCSC_Extensions_Front_Head'), 		8888);
			add_action('wp_footer', 					array($this, 	'TS_VCSC_Extensions_Front_Footer'), 	8888);
			
			// Add Dashboard Widget
			// --------------------
			if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_dashboard', 1) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0))) {
				add_action('wp_dashboard_setup', 		array($this, 	'TS_VCSC_DashboardHelpWidget'));
			}    
			
			// Create Custom Post Types
			// ------------------------
			if ((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypes', 1) == 1)) {
				if ((get_option('ts_vcsc_extend_settings_posttypeTeam', 1) == 0) && (get_option('ts_vcsc_extend_settings_posttypeTestimonial', 1) == 0) && (get_option('ts_vcsc_extend_settings_posttypeLogo', 1) == 0) && (get_option('ts_vcsc_extend_settings_posttypeSkillset', 1) == 0)) {
					update_option('ts_vcsc_extend_settings_posttypes', 0);
				}
			}
			if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypes', 1) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0))) {
				$this->TS_VCSC_CustomPostTypesCheckup			= "true";
				if (((get_option('ts_vcsc_extend_settings_extended', 0) == 0) && (get_option('ts_vcsc_extend_settings_customTeam', 0) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypeTeam', 1) == 1)  && (get_option('ts_vcsc_extend_settings_customTeam', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypes', 1) == 1))) {
					$this->TS_VCSC_CustomPostTypesTeam 			= "true";
				} else {
					$this->TS_VCSC_CustomPostTypesTeam 			= "false";
				}
				if (((get_option('ts_vcsc_extend_settings_extended', 0) == 0) && (get_option('ts_vcsc_extend_settings_customTestimonial', 0) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypeTestimonial', 1) == 1) && (get_option('ts_vcsc_extend_settings_customTestimonial', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypes', 1) == 1))) {
					$this->TS_VCSC_CustomPostTypesTestimonial 	= "true";
				} else {
					$this->TS_VCSC_CustomPostTypesTestimonial 	= "false";
				}
				if (((get_option('ts_vcsc_extend_settings_extended', 0) == 0) && (get_option('ts_vcsc_extend_settings_customLogo', 0) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypeLogo', 1) == 1) && (get_option('ts_vcsc_extend_settings_customLogo', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypes', 1) == 1))) {
					$this->TS_VCSC_CustomPostTypesLogo 			= "true";
				} else {
					$this->TS_VCSC_CustomPostTypesLogo 			= "false";
				}
				if (((get_option('ts_vcsc_extend_settings_extended', 0) == 0) && (get_option('ts_vcsc_extend_settings_customSkillset', 0) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypeSkillset', 1) == 1) && (get_option('ts_vcsc_extend_settings_customSkillset', 0) == 1) && (get_option('ts_vcsc_extend_settings_posttypes', 1) == 1))) {
					$this->TS_VCSC_CustomPostTypesSkillset 		= "true";
				} else {
					$this->TS_VCSC_CustomPostTypesSkillset 		= "false";
				}
			} else {				
				$this->TS_VCSC_CustomPostTypesCheckup			= "false";
				$this->TS_VCSC_CustomPostTypesTeam 				= "false";
				$this->TS_VCSC_CustomPostTypesTestimonial 		= "false";
				$this->TS_VCSC_CustomPostTypesLogo 				= "false";
				$this->TS_VCSC_CustomPostTypesSkillset 			= "false";
			}
			if (($this->TS_VCSC_CustomPostTypesTeam == "true") || ($this->TS_VCSC_CustomPostTypesTestimonial == "true") || ($this->TS_VCSC_CustomPostTypesLogo == "true") || ($this->TS_VCSC_CustomPostTypesSkillset == "true")) {
				require_once($this->posttypes_dir.'ts_vcsc_custom_post_registration.php');
				add_action('init', 'TS_VCSC_CMBMetaBoxes', 9999);
				if ($this->TS_VCSC_CustomPostTypesTeam == "true") {
					require_once($this->posttypes_dir.'ts_vcsc_custom_post_team.php');
					add_action('admin_menu', 					array($this, 'TS_VCSC_Remove_MetaBoxes_Teams'));
				}
				if ($this->TS_VCSC_CustomPostTypesTestimonial == "true") {
					require_once($this->posttypes_dir.'ts_vcsc_custom_post_testimonials.php');
					add_action('admin_menu', 					array($this, 'TS_VCSC_Remove_MetaBoxes_Testimonials'));
				}
				if ($this->TS_VCSC_CustomPostTypesLogo == "true") {
					require_once($this->posttypes_dir.'ts_vcsc_custom_post_logos.php');
					add_action('admin_menu', 					array($this, 'TS_VCSC_Remove_MetaBoxes_Logos'));
				}
				if ($this->TS_VCSC_CustomPostTypesSkillset == "true") {
					require_once($this->posttypes_dir.'ts_vcsc_custom_post_skillsets.php');
					add_action('admin_menu', 					array($this, 'TS_VCSC_Remove_MetaBoxes_Skillsets'));
				}
			}
			
			// Load Shortcode Definitions
			// --------------------------
			add_action('init', 							array($this, 'TS_VCSC_RegisterAllShortcodes'), 			888888888);
			
			// Load Icon Shortcode Generator
			// -----------------------------
			if ($this->TS_VCSC_IconicumStandard == "false") {
				if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_iconicum', 1) == 1) && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0) && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1) && (get_option('ts_vcsc_extend_settings_demo', 1) == 0))) {
					require_once($this->assets_dir.'ts_vcsc_editor_button.php');
				}
			}

			// Load Composer Elements
			// ----------------------
			add_action('init',							array($this, 'TS_VCSC_RegisterWithComposer'), 			999999999);
			
			add_action('admin_init',					array($this, 'TS_VCSC_ChangeDownloadsUploadDirectory'), 999);
			add_action('admin_notices',					array($this, 'TS_VCSC_CustomPackInstalledError'));
			add_action('wp_ajax_ts_delete_custom_pack',	array($this, 'TS_VCSC_DeleteCustomPack_Ajax'));			
			add_action('wp_ajax_ts_savepostmetadata',	array($this, 'TS_VCSC_SavePostMetaData'));
			
			// Allow Shortcodes in Widgets / Sidebar
			// -------------------------------------
			add_filter('widget_text', 'do_shortcode');
			
			// Check Language Default Arrays
			// -----------------------------
			add_action('admin_init', 					array($this, 'TS_VCSC_CheckDefaultOptions'), 			888888888);
			
			// Failed Login Redirect
			// ---------------------
			//add_action('wp_login_failed', 			array($this, 'TS_VCSC_FrontEndLoginFail'));
		}

		
		// Failed Frontend Login Redirect
		// ------------------------------
		function TS_VCSC_FrontEndLoginFail($username) {
			$referrer 		= $_SERVER['HTTP_REFERER'];
			$url			= strtok($_SERVER["HTTP_REFERER"], '?');
			if (!empty($referrer) && !strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin')) {
				if (!strstr($referrer, '?login=failed')) { 
					wp_redirect($url . '?login=failed');
				} else {
					wp_redirect($referrer);
				}
				exit;
			}
		}
		
		// Check Language Default Arrays
		// -----------------------------
		function TS_VCSC_CheckDefaultOptions() {
			// Language Settings for "TS Countdown"
			$TS_VCSC_Countdown_Language 								= get_option('ts_vcsc_extend_settings_translationsCountdown', '');
			$TS_VCSC_Setting_Options_Invalid							= 0;
			if (($TS_VCSC_Countdown_Language == false) || (empty($TS_VCSC_Countdown_Language))) {
				$TS_VCSC_Setting_Options_Invalid++;
				$TS_VCSC_Countdown_Language 							= $this->TS_VCSC_Countdown_Language_Defaults;
				foreach($this->TS_VCSC_Countdown_Language_Defaults as $key => $value ) {
					if( $existing = get_option('ts_vcsc_extend_settings_language' . $key)) {
						$TS_VCSC_Countdown_Language[$key] = $existing;
						delete_option('ts_vcsc_extend_settings_language' . $key);
					}
				}
			} else {
				foreach($this->TS_VCSC_Countdown_Language_Defaults as $key => $value) {
					if (!array_key_exists($key, $TS_VCSC_Countdown_Language)) {
						$TS_VCSC_Setting_Options_Invalid++;
						$TS_VCSC_Countdown_Language[$key] 				= $value;
					}
				}
			}
			if ($TS_VCSC_Setting_Options_Invalid > 0) {
				update_option('ts_vcsc_extend_settings_translationsCountdown', $TS_VCSC_Countdown_Language);
			}
			unset($TS_VCSC_Countdown_Language);
			// Language Settings for "TS Google Map"
			$TS_VCSC_Google_Map_Language 								= get_option('ts_vcsc_extend_settings_translationsGoogleMap', '');
			$TS_VCSC_Setting_Options_Invalid							= 0;
			if (($TS_VCSC_Google_Map_Language == false) || (empty($TS_VCSC_Google_Map_Language))) {
				$TS_VCSC_Setting_Options_Invalid++;
				$TS_VCSC_Google_Map_Language 							= $this->TS_VCSC_Google_Map_Language_Defaults;
				foreach($this->TS_VCSC_Google_Map_Language_Defaults as $key => $value ) {
					if( $existing = get_option('ts_vcsc_extend_settings_language' . $key)) {
						$TS_VCSC_Google_Map_Language[$key] = $existing;
						delete_option('ts_vcsc_extend_settings_language' . $key);
					}
				}
			} else {
				foreach($this->TS_VCSC_Google_Map_Language_Defaults as $key => $value) {
					if (!array_key_exists($key, $TS_VCSC_Google_Map_Language)) {
						$TS_VCSC_Setting_Options_Invalid++;
						$TS_VCSC_Google_Map_Language[$key] 				= $value;
					}
				}
			}
			if ($TS_VCSC_Setting_Options_Invalid > 0) {
				update_option('ts_vcsc_extend_settings_translationsGoogleMap', $TS_VCSC_Google_Map_Language);
			}
			unset($TS_VCSC_Google_Map_Language);
			// Language Settings for "TS Isotope Posts"
			$TS_VCSC_Isotope_Posts_Language 							= get_option('ts_vcsc_extend_settings_translationsIsotopePosts', '');
			$TS_VCSC_Setting_Options_Invalid							= 0;
			if (($TS_VCSC_Isotope_Posts_Language == false) || (empty($TS_VCSC_Isotope_Posts_Language))) {
				$TS_VCSC_Setting_Options_Invalid++;
				$TS_VCSC_Isotope_Posts_Language 						= $this->TS_VCSC_Isotope_Posts_Language_Defaults;
			} else {
				foreach($this->TS_VCSC_Isotope_Posts_Language_Defaults as $key => $value) {
					if (!array_key_exists($key, $TS_VCSC_Isotope_Posts_Language)) {
						$TS_VCSC_Setting_Options_Invalid++;
						$TS_VCSC_Isotope_Posts_Language[$key] 			= $value;
					}
				}
			}
			if ($TS_VCSC_Setting_Options_Invalid > 0) {
				update_option('ts_vcsc_extend_settings_translationsIsotopePosts', $TS_VCSC_Isotope_Posts_Language);
			}
			unset($TS_VCSC_Isotope_Posts_Language);
			// Lightbox Default Settings
			$TS_VCSC_Lightbox_Defaults 									= get_option('ts_vcsc_extend_settings_defaultLightboxSettings', '');
			$TS_VCSC_Setting_Options_Invalid							= 0;
			if (($TS_VCSC_Lightbox_Defaults == false) || (empty($TS_VCSC_Lightbox_Defaults))) {
				$TS_VCSC_Setting_Options_Invalid++;
				$TS_VCSC_Lightbox_Defaults								= $this->TS_VCSC_Lightbox_Setting_Defaults;
			} else {
				foreach($this->TS_VCSC_Lightbox_Setting_Defaults as $key => $value) {
					if (!array_key_exists($key, $TS_VCSC_Lightbox_Defaults)) {
						$TS_VCSC_Setting_Options_Invalid++;
						$TS_VCSC_Lightbox_Defaults[$key] 				= $value;
					}
				}
			}
			if ($TS_VCSC_Setting_Options_Invalid > 0) {
				update_option('ts_vcsc_extend_settings_defaultLightboxSettings', $TS_VCSC_Lightbox_Defaults);
			}
			// Unset Counter Variable
			unset($TS_VCSC_Setting_Options_Invalid);
		}
		
		// Load Language Domain
		// --------------------
		function TS_VCSC_LoadTextDomains(){
			load_plugin_textdomain('ts_visual_composer_extend', false, dirname(plugin_basename( __FILE__ )) . '/locale');
		}

		
		// Remove Metaboxes from Custom Post Types
		// ---------------------------------------
		function TS_VCSC_Remove_MetaBoxes_Teams($category) {
			remove_meta_box('commentstatusdiv', 	'ts_team', 				'normal');
			remove_meta_box('commentsdiv', 			'ts_team', 				'normal');
		}
		function TS_VCSC_Remove_MetaBoxes_Testimonials($category) {
			remove_meta_box('commentstatusdiv', 	'ts_testimonials', 		'normal');
			remove_meta_box('commentsdiv', 			'ts_testimonials', 		'normal');
		}
		function TS_VCSC_Remove_MetaBoxes_Logos($category) {
			remove_meta_box('commentstatusdiv', 	'ts_logos', 			'normal');
			remove_meta_box('commentsdiv', 			'ts_logos', 			'normal');
		}
		function TS_VCSC_Remove_MetaBoxes_Skillsets($category) {
			remove_meta_box('commentstatusdiv', 	'ts_skillsets', 		'normal');
			remove_meta_box('commentsdiv', 			'ts_skillsets', 		'normal');
		}
		

		// Load and Initialize the Auto-Update Class
		// -----------------------------------------
		function TS_VCSC_ActivateAutoUpdate() {
			if (is_admin() && (strlen(get_option('ts_vcsc_extend_settings_license')) != 0) && (function_exists('get_plugin_data'))) {
				if ((get_option('ts_vcsc_extend_settings_licenseValid', 0) == 1) && (get_option('ts_vcsc_extend_settings_extended', 0) == 0) && ((strpos(get_option('ts_vcsc_extend_settings_licenseInfo', ''), get_option('ts_vcsc_extend_settings_licenseKeyed', 'emptydelimiterfix')) != FALSE))) {
					if (!class_exists('TS_VCSC_AutoUpdate')) {
						require_once ('assets/ts_vcsc_autoupdate.php');
					}
					// Define Path and Base File for Plugin
					$ts_vcsc_extend_plugin_slug 					= plugin_basename(__FILE__);
					// Get the current version
					$ts_vcsc_extend_plugin_current_version	        = TS_VCSC_GetPluginVersion();
					// Define Path to Remote Update File
					$ts_vcsc_extend_plugin_remote_path 		        = 'http://www.tekanewascripts.info/Updates/ts-update-vc-extensions-wp.php';
					// Initialize Update Check
					$ts_vcsc_extend_plugin_class 					= new TS_VCSC_AutoUpdate($ts_vcsc_extend_plugin_current_version, $ts_vcsc_extend_plugin_remote_path, $ts_vcsc_extend_plugin_slug);
					// Retrieve Newest Plugin Version Number
					$ts_vcsc_extend_plugin_latest_version 	        = $ts_vcsc_extend_plugin_class->getRemote_version();
					// Save Current and Latest Version in WordPress Options
					update_option('ts_vcsc_extend_settings_versionCurrent', 		$ts_vcsc_extend_plugin_current_version);
					update_option('ts_vcsc_extend_settings_versionLatest', 			$ts_vcsc_extend_plugin_latest_version);
				}
			}
		}

		
		// Declare Arrays with Icon Font Data
		// ----------------------------------
		function TS_VCSC_IconFontArrays() {
			// Define Arrays for Font Icons
			// ----------------------------
			$this->TS_VCSC_Active_Icon_Fonts          	= 0;
			$this->TS_VCSC_Active_Icon_Count          	= 0;
			$this->TS_VCSC_Total_Icon_Count           	= 0;
			$this->TS_VCSC_Default_Icon_Fonts         	= "";

			// Define Global Font Arrays
			$this->TS_VCSC_Icons_Blank = array(
				''                              		=> '',
			);
			$this->TS_VCSC_Fonts_Blank = array(
				'All Fonts'                     		=> '',
			);

			$this->TS_VCSC_List_Icons_Full            	= $this->TS_VCSC_Icons_Blank;
			
			$this->TS_VCSC_List_Active_Fonts          	= array();
			$this->TS_VCSC_List_Select_Fonts          	= $this->TS_VCSC_Fonts_Blank;
			
			$this->TS_VCSC_List_Initial_Icons         	= $this->TS_VCSC_Icons_Blank;
			
			$this->TS_VCSC_Name_Initial_Font          	= "";
			$this->TS_VCSC_Class_Initial_Font         	= "";
			
			$TS_VCSC_IconFont_Settings 					= get_option('ts_vcsc_extend_settings_IconFontSettings', '');
			
			foreach ($this->TS_VCSC_Icon_Font_Settings as $Icon_Font => $iconfont) {
				if ($iconfont['setting'] != 'Custom') {
					// Check if Font is enabled
					$default = ($iconfont['default'] == "true" ? 1 : 0);
					$this->{'TS_VCSC_tinymce' . $iconfont['setting'] . ''}              = get_option('ts_vcsc_extend_settings_tinymce' . $iconfont['setting'], $default);
					// Load Font Arrays
					if (!isset($this->{'TS_VCSC_Icons_' . $iconfont['setting'] . ''})) {
						require_once($this->assets_dir.('ts_vcsc_font_' . strtolower($iconfont['setting']) . '.php'));
					}
					// Count Icons in Font
					$this->{'TS_VCSC_tinymce' . $iconfont['setting'] . 'Count'}			= count(array_unique($this->{'TS_VCSC_Icons_' . $iconfont['setting'] . ''}));
					$this->TS_VCSC_Icon_Font_Settings[$Icon_Font]['count'] 				= $this->{'TS_VCSC_tinymce' . $iconfont['setting'] . 'Count'};
					// Add Font Icons to Global Arrays					
					if (($this->{'TS_VCSC_tinymce' . $iconfont['setting'] . ''} == 0) && ($this->TS_VCSC_tinymceFontsAll == "false")) {
						$this->{'TS_VCSC_List_Icons_' . $iconfont['setting'] . ''}		= array();
						$this->TS_VCSC_Icon_Font_Settings[$Icon_Font]['active'] 		= "false";
					} else {
						$this->TS_VCSC_Active_Icon_Fonts++;
						$this->TS_VCSC_List_Active_Fonts[$Icon_Font] = $iconfont['setting'];
						$this->{'TS_VCSC_List_Icons_' . $iconfont['setting'] . ''}		= $this->{'TS_VCSC_Icons_' . $iconfont['setting'] . ''};
						$this->TS_VCSC_Icon_Font_Settings[$Icon_Font]['active'] 		= "true";
						uksort($this->{'TS_VCSC_List_Icons_' . $iconfont['setting'] . ''}, "TS_VCSC_CaseInsensitiveSort");
						$this->TS_VCSC_Active_Icon_Count  = $this->TS_VCSC_Active_Icon_Count + $this->{'TS_VCSC_tinymce' . $iconfont['setting'] . 'Count'};
						if ($this->TS_VCSC_Active_Icon_Fonts == 1) {
							$this->TS_VCSC_List_Initial_Icons 	= $this->TS_VCSC_List_Initial_Icons + $this->{'TS_VCSC_List_Icons_' . $iconfont['setting'] . ''};
							$this->TS_VCSC_Name_Initial_Font 	= $Icon_Font;
							$this->TS_VCSC_Class_Initial_Font 	= $iconfont['setting'];
						}
					}					
					$this->TS_VCSC_List_Icons_Full        		= $this->TS_VCSC_List_Icons_Full + $this->{'TS_VCSC_List_Icons_' . $iconfont['setting'] . ''};
					$this->TS_VCSC_Total_Icon_Count       		= $this->TS_VCSC_Total_Icon_Count + $this->{'TS_VCSC_tinymce' . $iconfont['setting'] . 'Count'};
				}
			}
			
			// Add Custom Font Icons to Global Arrays (if enabled)
			if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_fontimport', 1) == 1)) || (get_option('ts_vcsc_extend_settings_extended', 0) == 0)) {
				if ((get_option('ts_vcsc_extend_settings_tinymceCustom', 0) == 1) && (get_option('ts_vcsc_extend_settings_tinymceCustomArray', '') != '') && (get_option('ts_vcsc_extend_settings_tinymceCustomCount', 0) > 0)) {
					$this->TS_VCSC_Icons_Custom           		= get_option('ts_vcsc_extend_settings_tinymceCustomArray');
				} else {
					$this->TS_VCSC_Icons_Custom          		= array();
				}
				$font_directory									= get_option('ts_vcsc_extend_settings_tinymceCustomDirectory', '');
				if (file_exists($font_directory . '/style.css')) {
					$font_files_style							= true;
				} else {
					$font_files_style							= false;
				}
				if ((get_option('ts_vcsc_extend_settings_tinymceCustom', 0) == 1) && ($font_files_style == true)) {
					$this->TS_VCSC_Active_Icon_Fonts++;
					$this->TS_VCSC_List_Active_Fonts['Custom User Font'] = 'Custom';
					$this->TS_VCSC_List_Icons_Custom          	= $this->TS_VCSC_Icons_Custom;
					if (count(($this->TS_VCSC_Icons_Custom)) > 1) {
						if (is_array($this->TS_VCSC_Icons_Custom)) {
							$this->TS_VCSC_tinymceCustomCount	= count(array_unique($this->TS_VCSC_Icons_Custom));
						} else {
							$this->TS_VCSC_tinymceCustomCount	= count($this->TS_VCSC_Icons_Custom);
						}
					} else {
						$this->TS_VCSC_tinymceCustomCount       = count($this->TS_VCSC_Icons_Custom);
					}
					$this->TS_VCSC_Icon_Font_Settings['Custom User Font']['count'] = $this->TS_VCSC_tinymceCustomCount;
					$this->TS_VCSC_Total_Icon_Count           	= $this->TS_VCSC_Total_Icon_Count + $this->TS_VCSC_tinymceCustomCount;
					$this->TS_VCSC_Active_Icon_Count          	= $this->TS_VCSC_Active_Icon_Count + $this->TS_VCSC_tinymceCustomCount;
					if ($this->TS_VCSC_Active_Icon_Fonts == 1) {
						$this->TS_VCSC_List_Initial_Icons     	= $this->TS_VCSC_List_Initial_Icons + $this->TS_VCSC_List_Icons_Custom;
						$this->TS_VCSC_Name_Initial_Font      	= 'Custom User Font';
						$this->TS_VCSC_Class_Initial_Font     	= 'Custom';
					}
				} else if ($font_files_style == false) {
					TS_VCSC_ResetCustomFont();
				}
			} else {
				$this->TS_VCSC_DeleteCustomPack_Ajax();
			}
			
			$this->TS_VCSC_List_Select_Fonts          			= $this->TS_VCSC_List_Select_Fonts + $this->TS_VCSC_List_Active_Fonts;
		}
		
		
		// Add additional "Settings" Link to Plugin Listing Page
		// -----------------------------------------------------
		function TS_VCSC_PluginAddSettingsLink($links) {
			$settings_link = '<a href="admin.php?page=TS_VCSC_Extender">Settings</a>';
			array_push($links, $settings_link);
			return $links;
		}
		
		
		// Register Custom CSS and JS Inputs
		// ---------------------------------
		function TS_VCSC_RegisterCustomCSS_Setting() {
			register_setting('ts_vcsc_extend_custom_css', 	'ts_vcsc_extend_custom_css', 	    	array($this, 'TS_VCSC_CustomCSS_Validation'));
		}
		function TS_VCSC_RegisterCustomJS_Setting() {
			register_setting('ts_vcsc_extend_custom_js', 	'ts_vcsc_extend_custom_js',          	array($this, 'TS_VCSC_CustomJS_Validation'));
		}
		function TS_VCSC_CustomCSS_Validation($input) {
			if (!empty($input['ts_vcsc_extend_custom_css'])) {
				$input['ts_vcsc_extend_custom_css'] = trim( $input['ts_vcsc_extend_custom_css'] );
			}
			return $input;
		}
		function TS_VCSC_CustomJS_Validation($input) {
			if (!empty($input['ts_vcsc_extend_custom_js'])) {
				$input['ts_vcsc_extend_custom_js'] = trim( $input['ts_vcsc_extend_custom_js'] );
			}
			return $input;
		}
		function TS_VCSC_DisplayCustomCSS() {
			if ((get_option('ts_vcsc_extend_settings_extended', 0) == 0) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_codeeditors', 1) == 1))) {
				$ts_vcsc_extend_custom_css = 				get_option('ts_vcsc_extend_custom_css');
				$ts_vcsc_extend_custom_css_default =		get_option('ts_vcsc_extend_settings_customCSS');
				if ((!empty($ts_vcsc_extend_custom_css)) && ($ts_vcsc_extend_custom_css != $ts_vcsc_extend_custom_css_default)) {
					echo '<style type="text/css" media="all">' . "\n";
						echo '/* Custom CSS for Visual Composer Extensions WP */' . "\n";
						echo $ts_vcsc_extend_custom_css . "\n";
					echo '</style>' . "\n";
				}
			}
		}
		function TS_VCSC_DisplayCustomJS() {
			if ((get_option('ts_vcsc_extend_settings_extended', 0) == 0) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_codeeditors', 1) == 1))) {
				$ts_vcsc_extend_custom_js = 				get_option('ts_vcsc_extend_custom_js');
				$ts_vcsc_extend_custom_js_default = 		get_option('ts_vcsc_extend_settings_customJS');
				if ((!empty($ts_vcsc_extend_custom_js)) && ($ts_vcsc_extend_custom_js != $ts_vcsc_extend_custom_js_default)) {
					echo '<script type="text/javascript">' . "\n";
						echo '(function ($) {' . "\n";
							echo '/* Custom JS for Visual Composer Extensions WP */' . "\n";
							echo $ts_vcsc_extend_custom_js . "\n";
						echo '})(jQuery);' . "\n";
					echo '</script>' . "\n";
				}
			}
		}
		
		
		// Create Custom Admin Menu for Plugin
		function TS_VCSC_SyncMenu() {
			global $ts_vcsc_main_page;
			global $ts_vcsc_settings_page;
			global $ts_vcsc_upload_page;
			global $ts_vcsc_preview_page;
			global $ts_vcsc_generator_page;
			global $ts_vcsc_customCSS_page;
			global $ts_vcsc_customJS_page;
			global $ts_vcsc_license_page;
			global $ts_vcsc_team_page;
			add_action('admin_enqueue_scripts', array($this, 'TS_VCSC_AdminScripts'));
			if (get_option('ts_vcsc_extend_settings_mainmenu', 1) == 1) {
				$ts_vcsc_main_page = 		        add_menu_page( 		                        "VC Extensions",    "VC Extensions",    	'ts_vcsc_extend', 	'TS_VCSC_Extender', 	array($this, 'TS_VCSC_PageExtend'), 	    TS_VCSC_GetResourceURL('images/logos/ts_vcsc_menu_icon_16x16.png'));
			} else {
				$ts_vcsc_main_page = 		        add_options_page( 		                    "VC Extensions",    "VC Extensions",    	'ts_vcsc_extend', 	'TS_VCSC_Extender', 	array($this, 'TS_VCSC_PageExtend'));
			}
			$ts_vcsc_settings_page = 				add_submenu_page( 	'TS_VCSC_Extender', 	"Settings",         "Settings",         	'ts_vcsc_extend', 	'TS_VCSC_Extender', 	array($this, 'TS_VCSC_PageExtend'));
			if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_fontimport', 1) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0))) {
				$ts_vcsc_upload_page = 				add_submenu_page( 	'TS_VCSC_Extender', 	"Import Font",      "Import Font",      	'ts_vcsc_extend', 	'TS_VCSC_Uploader', 	array($this, 'TS_VCSC_PageUpload'));
			}
			$ts_vcsc_preview_page = 				add_submenu_page( 	'TS_VCSC_Extender', 	"Icon Previews",    "Icon Previews",    	'ts_vcsc_extend', 	'TS_VCSC_Previews', 	array($this, 'TS_VCSC_PagePreview'));
			if ($this->TS_VCSC_IconicumStandard == "false") {
				if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_iconicum', 1) == 1) && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0) && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1) && (get_option('ts_vcsc_extend_settings_demo', 1) == 0))) {
					$ts_vcsc_generator_page = 			add_submenu_page( 	'TS_VCSC_Extender', 	"Icon Generator",   "Icon Generator",	'ts_vcsc_extend', 	'TS_VCSC_Generator', 	array($this, 'TS_VCSC_PageGenerator'));
				}
			}
			if (current_user_can('manage_options')) {
				if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_codeeditors', 1) == 1)) || (get_option('ts_vcsc_extend_settings_extended', 0) == 0)) {
					$ts_vcsc_customCSS_page =		add_submenu_page( 	'TS_VCSC_Extender', 	"Custom CSS", 	    "Custom CSS",       	'ts_vcsc_extend', 	'TS_VCSC_CSS', 			array($this, 'TS_VCSC_PageCustomCSS'));
					$ts_vcsc_customJS_page =		add_submenu_page( 	'TS_VCSC_Extender', 	"Custom JS", 	    "Custom JS",        	'ts_vcsc_extend', 	'TS_VCSC_JS', 			array($this, 'TS_VCSC_PageCustomJS'));
				}
				if (get_option('ts_vcsc_extend_settings_extended', 0) == 0) {
					$ts_vcsc_license_page =			add_submenu_page( 	'TS_VCSC_Extender', 	"License Key", 	    "License Key",      	'ts_vcsc_extend', 	'TS_VCSC_License', 		array($this, 'TS_VCSC_PageLicense'));
				}
			}
			// Define Position of Plugin Menu
			if (get_option('ts_vcsc_extend_settings_mainmenu', 1) == 1) {
				$this->settingsLink = "admin.php?page=TS_VCSC_Extender";
			} else {
				$this->settingsLink = "options-general.php?page=TS_VCSC_Extender";
			}
		}
		function TS_VCSC_AdminScripts($hook_suffix) {  
			global $ts_vcsc_main_page;
			global $ts_vcsc_settings_page;
			global $ts_vcsc_upload_page;
			global $ts_vcsc_preview_page;
			global $ts_vcsc_generator_page;
			global $ts_vcsc_customCSS_page;
			global $ts_vcsc_customJS_page;
			global $ts_vcsc_license_page;
			$url = plugin_dir_url( __FILE__ );
			if (( $ts_vcsc_main_page == $hook_suffix ) || ( $ts_vcsc_settings_page == $hook_suffix ) || ( $ts_vcsc_upload_page == $hook_suffix ) || ( $ts_vcsc_preview_page == $hook_suffix ) || ( $ts_vcsc_customCSS_page == $hook_suffix ) || ( $ts_vcsc_customJS_page == $hook_suffix ) || ( $ts_vcsc_license_page == $hook_suffix )) {
				if (!wp_script_is('jquery')) {
					wp_enqueue_script('jquery');
				}
				if (($ts_vcsc_main_page == $hook_suffix) || ($ts_vcsc_settings_page == $hook_suffix)) {
					wp_enqueue_script('ts-extend-dragsort');
					wp_enqueue_style('ts-visual-composer-extend-admin');
					wp_enqueue_script('ts-extend-switch');
					wp_enqueue_script('ts-extend-isotope');
				}
				if ($ts_vcsc_upload_page == $hook_suffix) {
					if (get_option('ts_vcsc_extend_settings_tinymceCustomPath', '') != '') {
						wp_enqueue_style('ts-font-customvcsc');
					}
					wp_enqueue_style('ts-visual-composer-extend-admin');
					wp_enqueue_script('ts-visual-composer-extend-admin');
				}
				if (($ts_vcsc_upload_page == $hook_suffix) || ($ts_vcsc_preview_page == $hook_suffix)) {
					wp_enqueue_style('ts-extend-dropdown');
					wp_enqueue_script('ts-extend-dropdown');
					wp_enqueue_script('ts-extend-freewall');
				}
				wp_enqueue_style('ts-vcsc-extend');
				wp_enqueue_style('ts-extend-messi');
				wp_enqueue_script('ts-extend-messi');
				wp_enqueue_style('ts-extend-uitotop');
				wp_enqueue_script('ts-extend-uitotop');
				wp_enqueue_script('jquery-easing');
				wp_enqueue_script('ts-vcsc-extend');
				wp_enqueue_script('validation-engine');
				wp_enqueue_style('validation-engine');
				wp_enqueue_script('validation-engine-en');
			}
			if (($ts_vcsc_generator_page == $hook_suffix) && ($this->TS_VCSC_IconicumStandard == "false")) {
				foreach ($this->TS_VCSC_Installed_Icon_Fonts as $Icon_Font => $iconfont) {
					if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1) && ($iconfont != "Custom")) {
						wp_enqueue_style('ts-font-' . strtolower($iconfont));
					} else if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1) && ($iconfont == "Custom")) {
						$Custom_Font_CSS = get_option('ts_vcsc_extend_settings_tinymceCustomPath', '');
						wp_enqueue_style('ts-font-' . strtolower($iconfont) . 'vcsc');
					}
				}
				wp_enqueue_style('ts-vcsc-extend');
				wp_enqueue_style('ts-extend-uitotop');
				wp_enqueue_script('ts-extend-uitotop');
				wp_enqueue_script('jquery-easing');
				wp_enqueue_style('ts-extend-nouislider');
				wp_enqueue_script('ts-extend-nouislider');
				wp_enqueue_script('ts-extend-switch');
				wp_enqueue_script('ts-extend-rainbow');
				wp_enqueue_script('ts-extend-zclip');
				wp_enqueue_style('wp-color-picker');
				wp_enqueue_style('ts-extend-simptip');
				wp_enqueue_style('ts-extend-animations');
				wp_enqueue_style('ts-visual-composer-extend-generator');
				wp_enqueue_script('ts-visual-composer-extend-generator');
			}
			if (($ts_vcsc_preview_page == $hook_suffix)) {
				foreach ($this->TS_VCSC_Installed_Icon_Fonts as $Icon_Font => $iconfont) {
					if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1) && ($iconfont != "Custom")) {
						wp_enqueue_style('ts-font-' . strtolower($iconfont));
					} else if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1) && ($iconfont == "Custom")) {
						$Custom_Font_CSS = get_option('ts_vcsc_extend_settings_tinymceCustomPath', '');
						wp_enqueue_style('ts-font-' . strtolower($iconfont) . 'vcsc');
					}
				}
				wp_enqueue_style('ts-visual-composer-extend-admin');
				wp_enqueue_script('ts-visual-composer-extend-admin');
			}
			if (($ts_vcsc_customCSS_page == $hook_suffix) || ($ts_vcsc_customJS_page == $hook_suffix)) {
				wp_enqueue_script('ace_code_highlighter_js', 	                $url.'assets/ACE/ace.js', '', false, true );
			}
			if ($ts_vcsc_customCSS_page == $hook_suffix) {
				wp_enqueue_script('ace_mode_css',                               $url.'assets/ACE/mode-css.js', array('ace_code_highlighter_js'), false, true );
				wp_enqueue_script('custom_css_js', 		                		$url.'assets/ACE/custom-css.js', array( 'jquery', 'ace_code_highlighter_js' ), false, true );
			}
			if ($ts_vcsc_customJS_page == $hook_suffix) {
				wp_enqueue_script('ace_mode_js',                                $url.'assets/ACE/mode-javascript.js', array('ace_code_highlighter_js'), false, true );
				wp_enqueue_script('custom_js_js',                               $url.'assets/ACE/custom-js.js', array( 'jquery', 'ace_code_highlighter_js' ), false, true );
			}
		}
		// Function to register Scripts and Stylesheets
		// --------------------------------------------
		function TS_VCSC_Extensions_Registrations() {
			$url = plugin_dir_url( __FILE__ );
			// Check if files should be loaded in HEAD or BODY
			if ((get_option('ts_vcsc_extend_settings_loadHeader', 0) == 0)) 	{ $FOOTER = true; } 									else { $FOOTER = false; }
			// Check if All Files should be loaded
			if (get_option('ts_vcsc_extend_settings_loadForcable', 0) == 0) 	{ $this->TS_VCSC_LoadFrontEndForcable = "false"; } 		else { $this->TS_VCSC_LoadFrontEndForcable = "true"; }
			// Check if Waypoints should be loaded
			if (get_option('ts_vcsc_extend_settings_loadWaypoints', 1) == 1) 	{ $this->TS_VCSC_LoadFrontEndWaypoints = "true"; } 		else { $this->TS_VCSC_LoadFrontEndWaypoints = "false"; }
			// Check if Modernizr should be loaded
			if (get_option('ts_vcsc_extend_settings_loadModernizr', 1) == 1) 	{ $this->TS_VCSC_LoadFrontEndModernizr = "true"; } 		else { $this->TS_VCSC_LoadFrontEndModernizr = "false"; }
			// Check if CountTo should be loaded
			if (get_option('ts_vcsc_extend_settings_loadCountTo', 1) == 1) 		{ $this->TS_VCSC_LoadFrontEndCountTo = "true"; } 		else { $this->TS_VCSC_LoadFrontEndCountTo = "false"; }
			// Check if CountUp should be loaded
			if (get_option('ts_vcsc_extend_settings_loadCountUp', 1) == 1) 		{ $this->TS_VCSC_LoadFrontEndCountUp = "true"; } 		else { $this->TS_VCSC_LoadFrontEndCountUp = "false"; }
			// Check if Lightbox should be loaded
			if (get_option('ts_vcsc_extend_settings_loadLightbox', 0) == 1) 	{ $this->TS_VCSC_LoadFrontEndLightbox = "true"; } 		else { $this->TS_VCSC_LoadFrontEndLightbox = "false"; }
			// Check if Tooltips should be loaded
			if (get_option('ts_vcsc_extend_settings_loadTooltip', 0) == 1) 		{ $this->TS_VCSC_LoadFrontEndTooltips = "true"; } 		else { $this->TS_VCSC_LoadFrontEndTooltips = "false"; }
			// Check if ForceLoad of jQuery
			if (get_option('ts_vcsc_extend_settings_loadjQuery', 0) == 1) 		{ $this->TS_VCSC_LoadFrontEndJQuery = "true"; } 		else { $this->TS_VCSC_LoadFrontEndJQuery = "false"; }
			
			// Internal Files
			// --------------
			// Front-End Files
			wp_register_style('ts-visual-composer-extend-front',						$url.'css/ts-visual-composer-extend-front.min.css', null, false, 'all');
			wp_register_script('ts-visual-composer-extend-front',						$url.'js/ts-visual-composer-extend-front.min.js', array('jquery'), false, $FOOTER);
			// General Animations Files
			wp_register_style('ts-extend-animations',                 					$url.'css/ts-visual-composer-extend-animations.min.css', null, false, 'all');
			// General Settings Files
			wp_register_style('ts-vcsc-extend',                              			$url.'css/ts-visual-composer-extend-settings.min.css', null, false, 'all');
			wp_register_script('ts-vcsc-extend', 										$url.'js/ts-visual-composer-extend-settings.min.js', array('jquery'), '3.8', true);
			// Post Type Settings Files
			wp_register_script('ts-extend-posttypes', 									$url.'js/ts-visual-composer-extend-posttypes.min.js', array('jquery',), false, true);
			wp_register_style('ts-extend-posttypes',									$url.'css/ts-visual-composer-extend-posttypes.min.css', null, false, 'all');
			// Plugin Admin Files
			wp_register_style('ts-visual-composer-extend-admin',             			$url.'css/ts-visual-composer-extend-admin.min.css', null, false, 'all');
			wp_register_script('ts-visual-composer-extend-admin',            			$url.'js/ts-visual-composer-extend-admin.min.js', array('jquery'), false, true);
			// Iconicum Generator Files
			wp_register_style('ts-visual-composer-extend-generator',					$url.'css/ts-visual-composer-extend-generator.min.css', null, false, 'all');
			wp_register_script('ts-visual-composer-extend-generator',					$url.'js/ts-visual-composer-extend-generator.min.js', array('wp-color-picker'), false, true);
			// Textillate Animations Files
			wp_register_style('ts-extend-textillate',                 					$url.'css/ts-visual-composer-extend-textillate.min.css', null, false, 'all');
			// E-Commerce Font
			wp_register_style('ts-font-ecommerce',                 						$url.'css/ts-font-ecommerce.css', null, false, 'all');
			// Teammate Font
			wp_register_style('ts-font-teammates',                 						$url.'css/ts-font-teammates.css', null, false, 'all');
			// Icon Font Files
			foreach ($this->TS_VCSC_Installed_Icon_Fonts as $Icon_Font => $iconfont) {
				if ($iconfont != "Custom") {
					wp_register_style('ts-font-' . strtolower($iconfont),				$url.'css/ts-font-' . strtolower($iconfont) . '.css', null, false, 'all');
				} else if ($iconfont == "Custom") {
					$Custom_Font_CSS = get_option('ts_vcsc_extend_settings_tinymceCustomPath', '');
					wp_register_style('ts-font-' . strtolower($iconfont) . 'vcsc', 		$Custom_Font_CSS, null, false, 'all');
				}
			}
			
			// 3rd Party Files
			// ---------------
			// Lightbox
			wp_register_style('ts-extend-nacho',										$url.'css/jquery.vcsc.nchlightbox.min.css', null, false, 'all');
			wp_register_script('ts-extend-hammer', 										$url.'js/jquery.vcsc.hammer.min.js', array('jquery'), false, $FOOTER);
			wp_register_script('ts-extend-nacho', 										$url.'js/jquery.vcsc.nchlightbox.min.js', array('jquery'), false, $FOOTER);
			// Textillate
			wp_register_script('ts-extend-textillate',									$url.'js/jquery.vcsc.textillate.min.js', array('jquery'), false, $FOOTER);
			// Simptip Tooltips
			wp_register_style('ts-extend-simptip',                 						$url.'css/jquery.vcsc.simptip.min.css', null, false, 'all');
			// Hint Tooltips
			wp_register_style('ts-extend-hint',                 						$url.'css/jquery.vcsc.hint.min.css', null, false, 'all');
			// iHover Effects
			wp_register_style('ts-extend-ihover',                 						$url.'css/jquery.vcsc.ihover.min.css', null, false, 'all');
			wp_register_script('ts-extend-ihover',										$url.'js/jquery.vcsc.ihover.min.js', array('jquery'), false, $FOOTER);
			// Google Charts API
			wp_register_script('ts-extend-google-charts',								'https://www.google.com/jsapi', array('jquery'), false, false);
			// Google Maps API
			wp_register_script('ts-extend-mapapi-none',									'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places', array(), false, false);
			wp_register_script('ts-extend-mapapi-geo',									'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places', array(), false, false);
			// Custom Google Map Scripts
			wp_register_script('ts-extend-infobox', 									$url.'js/jquery.vcsc.infobox.min.js', array('jquery'), false, $FOOTER);
			wp_register_script('ts-extend-googlemap', 									$url.'js/jquery.vcsc.googlemap.min.js', array('jquery'), false, $FOOTER);
			// Modernizr
			wp_register_script('ts-extend-modernizr',                					$url.'js/jquery.vcsc.modernizr.min.js', array('jquery'), false, false);
			// Waypoints
			wp_register_script('ts-extend-waypoints',									$url.'js/jquery.vcsc.waypoints.min.js', array('jquery'), false, $FOOTER);
			// qTip2 Tooltips
			wp_register_style('ts-extend-qtip2',                 						$url.'css/jquery.vcsc.qtip.min.css', null, false, 'all');
			wp_register_script('ts-extend-qtip2',										$url.'js/jquery.vcsc.qtip.min.js', array('jquery'), false, $FOOTER);
			// YouTube Player
			wp_register_style('ts-extend-ytplayer',										$url.'css/jquery.vcsc.mb.ytplayer.min.css', null, false, 'all');
			wp_register_script('ts-extend-ytplayer',									$url.'js/jquery.vcsc.mb.ytplayer.min.js', array('jquery'), false, false);
			// CountUp Counter
			wp_register_script('ts-extend-countup',										$url.'js/jquery.vcsc.countup.min.js', array('jquery'), false, $FOOTER);
			// CountTo Counter
			wp_register_script('ts-extend-countto',										$url.'js/jquery.vcsc.countto.min.js', array('jquery'), false, $FOOTER);
			// Circliful Counter
			wp_register_script('ts-extend-circliful', 									$url.'js/jquery.vcsc.circliful.min.js', array('jquery'), false, $FOOTER);
			// Countdown Script
			wp_register_style('ts-extend-countdown',									$url.'css/jquery.vcsc.counteverest.min.css', null, false, 'all');
			wp_register_script('ts-extend-countdown',									$url.'js/jquery.vcsc.counteverest.min.js', array('jquery'), false, $FOOTER);
			wp_register_style('ts-extend-font-roboto',									'http://fonts.googleapis.com/css?family=Roboto:400', null, false, 'all');
			wp_register_style('ts-extend-font-unica',									'http://fonts.googleapis.com/css?family=Unica+One', null, false, 'all');
			// Buttons CSS
			wp_register_style('ts-extend-buttons',                 						$url.'css/jquery.vcsc.buttons.min.css', null, false, 'all');
			// Badonkatrunc Shortener
			wp_register_script('ts-extend-badonkatrunc',								$url.'js/jquery.vcsc.badonkatrunc.min.js', array('jquery'), false, $FOOTER);
			// QR-Code Maker
			wp_register_script('ts-extend-qrcode',										$url.'js/jquery.vcsc.qrcode.min.js', array('jquery'), false, $FOOTER);
			// Image Adipoli
			wp_register_script('ts-extend-adipoli', 									$url.'js/jquery.vcsc.adipoli.min.js', array('jquery'), false, $FOOTER);
			// Image Caman
			wp_register_script('ts-extend-caman', 										$url.'js/jquery.vcsc.caman.full.min.js', array('jquery'), false, $FOOTER);
			// Owl Carousel 2
            wp_register_style('ts-extend-owlcarousel2',				        			$url.'css/jquery.vcsc.owl.carousel.min.css', null, false, 'all');
            wp_register_script('ts-extend-owlcarousel2',			            		$url.'js/jquery.vcsc.owl.carousel.min.js', array('jquery'), false, $FOOTER);
			// Stack Slider
			wp_register_style('ts-extend-stackslider',				        			$url.'css/jquery.vcsc.stackslider.min.css', null, false, 'all');
			wp_register_script('ts-extend-stackslider',			            			$url.'js/jquery.vcsc.stackslider.min.js', array('jquery'), false, $FOOTER);
			// DropDown Script
			wp_register_style('ts-extend-dropdown', 									$url.'css/jquery.vcsc.dropdown.min.css', null, false, 'all');
			wp_register_script('ts-extend-dropdown', 									$url.'js/jquery.vcsc.dropdown.min.js', array('jquery'), false, true);
			// Isotope Script
			wp_register_script('ts-extend-isotope',										$url.'js/jquery.vcsc.isotope.min.js', array('jquery'), false, $FOOTER);
			// NewsTicker
			wp_register_script('ts-extend-newsticker',			            			$url.'js/jquery.vcsc.newsticker.min.js', array('jquery'), false, $FOOTER);
			
			// Back-End Files
			// --------------
			// NoUiSlider
			wp_register_style('ts-extend-nouislider',									$url.'css/jquery.vcsc.nouislider.min.css', null, false, 'all');
			wp_register_script('ts-extend-nouislider',									$url.'js/jquery.vcsc.nouislider.min.js', array('jquery'), false, true);
			// MultiSelect
			wp_register_style('ts-extend-multiselect',									$url.'css/jquery.vcsc.multi.select.min.css', null, false, 'all');
			wp_register_script('ts-extend-multiselect',									$url.'js/jquery.vcsc.multi.select.min.js', array('jquery'), false, true);
			// Toggles / Switch
			wp_register_script('ts-extend-switch',										$url.'js/jquery.vcsc.toggles.min.js', array('jquery'), false, true);
			// Freewall
			wp_register_script('ts-extend-freewall', 									$url.'js/jquery.vcsc.freewall.min.js', array('jquery'), '3.8', true);
			// Date & Time Picker
			wp_register_script('ts-extend-picker',										$url.'js/jquery.vcsc.datetimepicker.min.js', array('jquery'), false, true);
			// Lightbox Me
			wp_register_script('ts-extend-lightboxme',									$url.'js/jquery.vcsc.lightboxme.min.js', array('jquery', 'wp-color-picker'), false, true);
			// ZeroClipboard
			wp_register_script('ts-extend-zclip',										$url.'js/jquery.vcsc.zeroclipboard.min.js', array('jquery'), false, true);
			// Rainbow Syntax
			wp_register_script('ts-extend-rainbow',										$url.'js/jquery.vcsc.rainbow.min.js', array('jquery'), false, true);
			// Messi Popup
			wp_register_style('ts-extend-messi', 				        				$url.'css/jquery.vcsc.messi.min.css', null, false, 'all');
			wp_register_script('ts-extend-messi',                            			$url.'js/jquery.vcsc.messi.min.js', array('jquery'), false, true);
			// DragSort
			wp_register_script('ts-extend-dragsort',									$url.'js/jquery.vcsc.dragsort.min.js', array('jquery'), false, true);
			// ToTop Scroller
			wp_register_style('ts-extend-uitotop', 										$url.'css/jquery.vcsc.ui.totop.min.css', null, false, 'all');
			wp_register_script('ts-extend-uitotop', 									$url.'js/jquery.vcsc.ui.totop.min.js', array('jquery'), false, true);
			// jQuery Easing
			wp_register_script('jquery-easing', 										$url.'js/jquery.vcsc.easing.min.js', array('jquery'), false, true);
			// Select 2
			wp_register_style('ts-extend-select2',										$url.'css/jquery.vcsc.select2.min.css', null, false, 'all');
			wp_register_script('ts-extend-select2',										$url.'js/jquery.vcsc.select2.min.js', array('jquery'), false, true);
			// Validation Engine
			wp_register_script('validation-engine', 									$url.'js/jquery.vcsc.validationengine.min.js', array('jquery'), false, true);
			wp_register_style('validation-engine',										$url.'css/jquery.vcsc.validationengine.min.css', null, false, 'all');
			wp_register_script('validation-engine-en', 									$url.'js/jquery.vcsc.validationengine.en.min.js', array('jquery'), false, true);
			
			// Visual Composer Backbone
			wp_register_script('ts-vcsc-backend-rows',									$url.'js/backend/ts-vcsc-backend-rows.min.js', array('jquery'), false, true);
			wp_register_script('ts-vcsc-backend-other',									$url.'js/backend/ts-vcsc-backend-other.min.js', array('jquery'), false, true);
			// Visual Composer Styling
			if (defined('WPB_VC_VERSION')){
				if (TS_VCSC_VersionCompare(WPB_VC_VERSION, '4.3.0') >= 0) {
					wp_register_style('ts-visual-composer-extend-composer',				$url.'css/ts-visual-composer-extend-composer-new.min.css', null, false, 'all');
				} else {
					wp_register_style('ts-visual-composer-extend-composer',				$url.'css/ts-visual-composer-extend-composer-old.min.css', null, false, 'all');
				}
			} else {
				wp_register_style('ts-visual-composer-extend-composer',					$url.'css/ts-visual-composer-extend-composer-old.min.css', null, false, 'all');
			}
		}
		// Function to load External Files on Back-End when Editing
		// --------------------------------------------------------
		function TS_VCSC_Extensions_Admin_Files() {
			global $pagenow, $typenow;
			$screen = get_current_screen();
			if (empty($typenow) && !empty($_GET['post'])) {
				$post 		= get_post($_GET['post']);
				$typenow 	= $post->post_type;
			}
			$url = plugin_dir_url( __FILE__ );
			if (TS_VCSC_IsEditPagePost()) {
				foreach ($this->TS_VCSC_Installed_Icon_Fonts as $Icon_Font => $iconfont) {
					if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1) && ($iconfont != "Custom")) {
						wp_enqueue_style('ts-font-' . strtolower($iconfont));
					} else if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1) && ($iconfont == "Custom")) {
						$Custom_Font_CSS = get_option('ts_vcsc_extend_settings_tinymceCustomPath', '');
						wp_enqueue_style('ts-font-' . strtolower($iconfont) . 'vcsc');
					}
				}
				wp_enqueue_style('ts-visual-composer-extend-composer');
				wp_enqueue_style('ts-extend-nouislider');
				wp_enqueue_style('ts-extend-multiselect');
				wp_enqueue_script('ts-extend-nouislider');
				wp_enqueue_script('ts-extend-multiselect');
				wp_enqueue_script('ts-extend-switch');
				wp_enqueue_script('ts-extend-picker');
				wp_enqueue_style('ts-visual-composer-extend-admin');
				wp_enqueue_script('ts-visual-composer-extend-admin');
				if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_additions', 1) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0))) {
					if ($this->TS_VCSC_VCFrontEditMode 	== "false") {
						// Load Custom Backbone View for Rows
						if (get_option('ts_vcsc_extend_settings_additionsRows', 0) == 1) {
							wp_enqueue_script('ts-vcsc-backend-rows');
						}
					}
				}
				if ($this->TS_VCSC_VCFrontEditMode 	== "false") {
					// Load Custom Backbone View for Other Elements
					wp_enqueue_script('ts-vcsc-backend-other');
				}
				if ($this->TS_VCSC_IconicumStandard == "false") {
					if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_iconicum', 1) == 1) && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0) && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1) && (get_option('ts_vcsc_extend_settings_demo', 1) == 0))) {
						wp_enqueue_style('wp-color-picker');
						wp_enqueue_script('ts-extend-lightboxme');
						wp_enqueue_script('ts-extend-zclip');
						wp_enqueue_script('ts-extend-rainbow');
						wp_enqueue_style('ts-visual-composer-extend-generator');
					}
				}
			} else {
				wp_enqueue_style('ts-visual-composer-extend-admin');
			}
		}
		function TS_VCSC_Extensions_Admin_Variables() {
			$TS_VCSC_Lightbox_Defaults 			= get_option('ts_vcsc_extend_settings_defaultLightboxSettings', '');
			$TS_VCSC_Countdown_Language			= get_option('ts_vcsc_extend_settings_translationsCountdown', '');
			if (($TS_VCSC_Countdown_Language == false) || (empty($TS_VCSC_Countdown_Language))) {
				$TS_VCSC_Countdown_Language		= $this->TS_VCSC_Countdown_Language_Defaults;
			}
			$TS_VCSC_Google_Map_Language 		= get_option('ts_vcsc_extend_settings_translationsGoogleMap', '');
			if (($TS_VCSC_Google_Map_Language == false) || (empty($TS_VCSC_Google_Map_Language))) {
				$TS_VCSC_Google_Map_Language	= $this->TS_VCSC_Google_Map_Language_Defaults;
			}
			echo '<script type="text/javascript">' . "\r\n";
				// Lightbox Settings
				echo '// Add Global Lightbox Settings for VC Extensions' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_Thumbs = "' . $TS_VCSC_Lightbox_Defaults['thumbs'] . '";' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_Thumbsize = ' . $TS_VCSC_Lightbox_Defaults['thumbsize'] . ';' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_Animation = "' . $TS_VCSC_Lightbox_Defaults['animation'] . '";' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_Captions = "' . $TS_VCSC_Lightbox_Defaults['captions'] . '";' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_Durations = ' . $TS_VCSC_Lightbox_Defaults['duration'] . ';' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_Share = ' . ($TS_VCSC_Lightbox_Defaults['share'] == 1 ? 'true' : 'false') . ';' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_Social = "' . $TS_VCSC_Lightbox_Defaults['social'] . '";' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_NoTouch = ' . ($TS_VCSC_Lightbox_Defaults['notouch'] == 1 ? 'false' : 'true') . ';' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_BGClose = ' . ($TS_VCSC_Lightbox_Defaults['bgclose'] == 1 ? 'true' : 'false') . ';' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_NoHashes = ' . ($TS_VCSC_Lightbox_Defaults['nohashes'] == 1 ? 'true' : 'false') . ';' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_Keyboard = ' . ($TS_VCSC_Lightbox_Defaults['keyboard'] == 1 ? 'true' : 'false') . ';' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_FullScreen = ' . ($TS_VCSC_Lightbox_Defaults['fullscreen'] == 1 ? 'true' : 'false') . ';' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_Zoom = ' . ($TS_VCSC_Lightbox_Defaults['zoom'] == 1 ? 'true' : 'false') . ';' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_FXSpeed = ' . $TS_VCSC_Lightbox_Defaults['fxspeed'] . ';' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_Scheme = "' . $TS_VCSC_Lightbox_Defaults['scheme'] . '";' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_Backlight = "' . $TS_VCSC_Lightbox_Defaults['backlight'] . '";' . "\r\n";
				echo 'var $TS_VCSC_Lightbox_UseColor = ' . ($TS_VCSC_Lightbox_Defaults['usecolor'] == 1 ? 'true' : 'false') . ';' . "\r\n";
				// Language Settings for Countdown
				echo '// Add Global Translation Strings for VC Extensions' . "\r\n";
				echo 'var $TS_VCSC_Countdown_DaysLabel = "' . $TS_VCSC_Countdown_Language['DayPlural'] . '";' . "\r\n";
				echo 'var $TS_VCSC_Countdown_DayLabel = "' . $TS_VCSC_Countdown_Language['DaySingular'] . '";' . "\r\n";
				echo 'var $TS_VCSC_Countdown_HoursLabel = "' . $TS_VCSC_Countdown_Language['HourPlural'] . '";' . "\r\n";
				echo 'var $TS_VCSC_Countdown_HourLabel = "' . $TS_VCSC_Countdown_Language['HourSingular'] . '";' . "\r\n";
				echo 'var $TS_VCSC_Countdown_MinutesLabel = "' . $TS_VCSC_Countdown_Language['MinutePlural'] . '";' . "\r\n";
				echo 'var $TS_VCSC_Countdown_MinuteLabel = "' . $TS_VCSC_Countdown_Language['MinuteSingular'] . '";' . "\r\n";
				echo 'var $TS_VCSC_Countdown_SecondsLabel = "' . $TS_VCSC_Countdown_Language['SecondPlural'] . '";' . "\r\n";
				echo 'var $TS_VCSC_Countdown_SecondLabel = "' . $TS_VCSC_Countdown_Language['SecondSingular'] . '";' . "\r\n";
				// Language Settings for Google Map
				echo 'var $TS_VCSC_GoogleMap_TextCalcShow = "' . $TS_VCSC_Google_Map_Language['TextCalcShow'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextCalcHide = "' . $TS_VCSC_Google_Map_Language['TextCalcHide'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextDirectionShow = "' . $TS_VCSC_Google_Map_Language['TextDirectionShow'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextDirectionHide = "' . $TS_VCSC_Google_Map_Language['TextDirectionHide'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextResetMap = "' . $TS_VCSC_Google_Map_Language['TextResetMap'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_PrintRouteText = "' . $TS_VCSC_Google_Map_Language['PrintRouteText'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextDistance = "' . $TS_VCSC_Google_Map_Language['TextDistance'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextViewOnGoogle = "' . $TS_VCSC_Google_Map_Language['TextViewOnGoogle'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextButtonCalc = "' . $TS_VCSC_Google_Map_Language['TextButtonCalc'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextSetTarget = "' . $TS_VCSC_Google_Map_Language['TextSetTarget'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextGeoLocation = "' . $TS_VCSC_Google_Map_Language['TextGeoLocation'] . '";' . "\r\n";	
				echo 'var $TS_VCSC_GoogleMap_TextTravelMode = "' . $TS_VCSC_Google_Map_Language['TextTravelMode'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextDriving = "' . $TS_VCSC_Google_Map_Language['TextDriving'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextWalking = "' . $TS_VCSC_Google_Map_Language['TextWalking'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextBicy = "' . $TS_VCSC_Google_Map_Language['TextBicy'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextWP = "' . $TS_VCSC_Google_Map_Language['TextWP'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextButtonAdd = "' . $TS_VCSC_Google_Map_Language['TextButtonAdd'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextMapHome = "' . $TS_VCSC_Google_Map_Language['TextMapHome'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextMapBikes = "' . $TS_VCSC_Google_Map_Language['TextMapBikes'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextMapTraffic = "' . $TS_VCSC_Google_Map_Language['TextMapTraffic'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextMapSpeedMiles = "' . $TS_VCSC_Google_Map_Language['TextMapSpeedMiles'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextMapSpeedKM = "' . $TS_VCSC_Google_Map_Language['TextMapSpeedKM'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextMapNoData = "' . $TS_VCSC_Google_Map_Language['TextMapNoData'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextMapMiles = "' . $TS_VCSC_Google_Map_Language['TextMapMiles'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextMapKilometes = "' . $TS_VCSC_Google_Map_Language['TextMapKilometes'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextActivate = "' . $TS_VCSC_Google_Map_Language['TextMapActivate'] . '";' . "\r\n";
				echo 'var $TS_VCSC_GoogleMap_TextDeactivate = "' . $TS_VCSC_Google_Map_Language['TextMapDeactivate'] . '";' . "\r\n";
				// Smooth Scroll Settings
				echo '// Add Global SmoothScroll Settings for VC Extensions' . "\r\n";
				if (get_option('ts_vcsc_extend_settings_additionsSmoothScroll', 0) == 1) {
					echo 'var $TS_VCSC_SmoothScrollActive = true;' . "\r\n";
					echo 'var $TS_VCSC_SmoothScrollSpeed = ' . get_option('ts_vcsc_extend_settings_additionsSmoothSpeed', 1500) . ';' . "\r\n";
				} else {
					echo 'var $TS_VCSC_SmoothScrollActive = false;' . "\r\n";
					echo 'var $TS_VCSC_SmoothScrollSpeed = ' . get_option('ts_vcsc_extend_settings_additionsSmoothSpeed', 1500) . ';' . "\r\n";
				}
			echo '</script>';
		}
		function TS_VCSC_PageExtend() {
			if ((current_user_can('ts_vcsc_extend')) || (current_user_can('manage_options'))) {
				echo '<div class="wrap ts-settings" id="ts_vcsc_extend_frame">' . "\n";
					echo '<div id="ts_vcsc_extend_icon_settings" class="ts_vcsc_extend_icon"></div><h2>Visual Composer Extensions - Settings</h2>' . "\n";
					include('assets/ts_vcsc_settings_main.php');
					echo '</div>' . "\n";
				echo '</div>' . "\n";
			} else {
				wp_die('You do not have sufficient permissions to access this page.');
			}
		}
		function TS_VCSC_PageUpload() {
			if ((current_user_can('ts_vcsc_extend')) || (current_user_can('manage_options'))) {
				echo '<div class="wrap ts-settings" id="ts_vcsc_extend_frame">' . "\n";
					echo '<div id="ts_vcsc_extend_icon_settings" class="ts_vcsc_extend_icon"></div><h2>Visual Composer Extensions - Upload Font</h2>' . "\n";
					include('assets/ts_vcsc_upload.php');
					echo '</div>' . "\n";
				echo '</div>' . "\n";
			} else {
				wp_die('You do not have sufficient permissions to access this page.');
			}
		}
		function TS_VCSC_PagePreview() {
			if ((current_user_can('ts_vcsc_extend')) || (current_user_can('manage_options'))) {
				echo '<div class="wrap ts-settings" id="ts_vcsc_extend_frame">' . "\n";
					echo '<div id="ts_vcsc_extend_icon_settings" class="ts_vcsc_extend_icon"></div><h2>Visual Composer Extensions - Icon Previews</h2>' . "\n";
					include('assets/ts_vcsc_previews.php');
					echo '</div>' . "\n";
				echo '</div>' . "\n";
			} else {
				wp_die('You do not have sufficient permissions to access this page.');
			}
		}
		function TS_VCSC_PageGenerator() {
			if ((current_user_can('ts_vcsc_extend')) || (current_user_can('manage_options'))) {
				echo '<div class="wrap ts-settings" id="ts_vcsc_extend_frame">' . "\n";
					echo '<div id="ts_vcsc_extend_icon_settings" class="ts_vcsc_extend_icon"></div><h2>Visual Composer Extensions - Font Icon Generator</h2>' . "\n";
					include('assets/ts_vcsc_generator.php');
					echo '</div>' . "\n";
				echo '</div>' . "\n";
			} else {
				wp_die('You do not have sufficient permissions to access this page.');
			}
		}
		function TS_VCSC_PageCustomCSS() {
			if ((current_user_can('ts_vcsc_extend')) || (current_user_can('manage_options'))) {
				// Get Stored Custom CSS; otherwise assign Default Message
				$ts_vcsc_extend_custom_css_default = get_option('ts_vcsc_extend_settings_customCSS');
				$ts_vcsc_extend_custom_css 	= get_option('ts_vcsc_extend_custom_css', $ts_vcsc_extend_custom_css_default);
				if (empty($ts_vcsc_extend_custom_css)) {
					$ts_vcsc_extend_custom_css	= $ts_vcsc_extend_custom_css_default;
				}
				if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
					echo "\n";
					echo "<script type='text/javascript'>" . "\n";
						echo 'var VC_Extension_Demo = false;' . "\n";
						echo "var CustomCSSAdded = true;" . "\n";
					echo "</script>" . "\n";
				} else {
					echo '<script type="text/javascript">' . "\n";
						echo 'var VC_Extension_Demo = false;' . "\n";
						echo 'var CustomCSSAdded = false;' . "\n";
					echo '</script>' . "\n";
				}
				?>
				<div class="wrap ts-settings" id="ts_vcsc_extend_frame">
					<div id="ts_vcsc_extend_icon_css" class="ts_vcsc_extend_icon"></div><h2>Visual Composer Extensions - Custom CSS</h2>
					
					<a class="button-secondary" style="width: 200px; margin: 20px auto 10px auto; text-align: center;" href="<?php echo $this->settingsLink; ?>" target="_parent"><img src="<?php echo TS_VCSC_GetResourceURL('images/logos/ts_vcsc_menu_icon_16x16.png'); ?>" style="width: 16px; height: 16px; margin-right: 10px;">Back to Plugin Settings</a>
					
					<table>
						<tr style="height: 75px; width: 100%;">
							<td>
								<p style="text-align: justify;">In order to adjust the Gallery CSS to your Theme, please use the Custom CSS Editor below and do not change the CSS file that comes with the
								plugin. Direct changes to the CSS file will be lost after each update but the Custom CSS entered here will be stored in the WordPress Database and will remain after each
								update.</p>
								<p style="text-align: justify; font-weight: bold;">While the Editor will do some basic checking, you are responsible to ensure that all code has been entered correctly.</p>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td colspan="2"><p style="margin-top: 0px;">The plugin will automatically wrap the code in ...<br/></td>
						</tr>
						<tr>
							<td style="width: 80px;"><img style='float: left; width: 75px; height: 75px;' src='<?php echo TS_VCSC_GetResourceURL('images/other/settings-custom-css.png'); ?>' height='75' width='75'></td>
							<td>
								<p>
									<code style="text-align: left;">
										&#60;style&#62;<br/>
											<span style="margin-left: 20px;">... Your Custom CSS ...</span><br/>
										&#60;/style&#62;
									</code>
								</p>
							</td>
						</tr>
						<tr>
							<td colspan="2"><p style="margin-top: 0px;">... so please don't add these lines to the editor; otherwise your code will fail.</td>
						</tr>
					</table>

					<hr class='style-two' style='margin: 0px auto;'>

					<form id="ts_vcsc_extend_custom_css_form" method="post" action="options.php" style="margin-top: 15px;">
						<span class="submit">
							<input style="margin: 0px; width: 200px;" title="Click here to save Custom CSS Settings" class="button-primary ButtonSubmit" type="submit" name="Submit" value="<?php _e('Save Custom CSS', 'ts_visual_composer_extend') ?>" />
						</span>
						<?php settings_fields('ts_vcsc_extend_custom_css'); ?>
						<div id="ts_fb_custom_css_container">
							<div id="ts_vcsc_extend_custom_css" name="ts_vcsc_extend_custom_css"></div>
						</div>
						<textarea id="ts_vcsc_extend_custom_css_textarea" name="ts_vcsc_extend_custom_css" style="display: none;"><?php echo $ts_vcsc_extend_custom_css; ?></textarea>
						<span class="submit">
							<input style="margin: 0px; width: 200px;" title="Click here to save Custom CSS Settings" class="button-primary ButtonSubmit" type="submit" name="Submit" value="<?php _e('Save Custom CSS', 'ts_visual_composer_extend') ?>" />
						</span>
					</form>
					
					<div id="ts-settings-summary" style="display: none;" data-summary="<?php echo get_option('ts_vcsc_extend_settings_licenseKeyed', 'emptydelimiterfix'); ?>"><?php echo get_option('ts_vcsc_extend_settings_licenseInfo', ''); ?></div>
				</div>
				<?php
			} else {
				wp_die('You do not have sufficient permissions to access this page.');
			}
		}
		function TS_VCSC_PageCustomJS() {
			if ((current_user_can('ts_vcsc_extend')) || (current_user_can('manage_options'))) {
				// Get Stored Custom JS; otherwise assign Default Message
				$ts_vcsc_extend_custom_js_default = get_option('ts_vcsc_extend_settings_customJS');
				$ts_vcsc_extend_custom_js = get_option('ts_vcsc_extend_custom_js', $ts_vcsc_extend_custom_js_default);
				if (empty($ts_vcsc_extend_custom_js)) {
					$ts_vcsc_extend_custom_js	= $ts_vcsc_extend_custom_js_default;
				}
				if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
					echo "\n";
					echo "<script type='text/javascript'>" . "\n";
						echo 'var VC_Extension_Demo = false;' . "\n";
						echo "var CustomJSAdded = true;" . "\n";
					echo "</script>" . "\n";
				} else {
					echo '<script type="text/javascript">' . "\n";
						echo 'var VC_Extension_Demo = false;' . "\n";
						echo 'var CustomJSAdded = false;' . "\n";
					echo '</script>' . "\n";
				}
				?>
				<div class="wrap ts-settings" id="ts_vcsc_extend_frame">
					<div id="ts_vcsc_extend_icon_js" class="ts_vcsc_extend_icon"></div><h2>Visual Composer Extensions - Custom JS</h2>
					
					<a class="button-secondary" style="width: 200px; margin: 20px auto 10px auto; text-align: center;" href="<?php echo $this->settingsLink; ?>" target="_parent"><img src="<?php echo TS_VCSC_GetResourceURL('images/logos/ts_vcsc_menu_icon_16x16.png'); ?>" style="width: 16px; height: 16px; margin-right: 10px;">Back to Plugin Settings</a>
				
					<table>
						<tr>
							<td style="height: 75px; width: 100%;">
								<p style="text-align: justify;">In order to add some custom JavaScript Code to the Gallery (i.e. for custom lightbox, etc.), please use the Custom JS Editor below and do not
								change the JS files that comes with the plugin. Direct changes to the JS files will be lost after each update but the Custom JS entered here will be stored in the WordPress
								Database and will remain after each update.</p>
								<p style="text-align: justify; font-weight: bold;">While the Editor will do some basic checking, you are responsible to ensure that all code has been entered correctly.</p>
							</td>
						</tr>
					</table>
					<table>
						<tr>
							<td colspan="2"><p style="margin-top: 0px;">The plugin will automatically wrap the code in ...<br/></td>
						</tr>
						<tr>
							<td style="width: 80px;"><img style='float: left; width: 75px; height: 75px;' src='<?php echo TS_VCSC_GetResourceURL('images/other/settings-custom-js.png'); ?>' height='75' width='75'></td>
							<td>
								<p>
									<code style="text-align: left;">
										&#60;script type="text/javascript"&#62;<br/>
										<span style="margin-left: 20px;">(function ($) {</span><br/>
											<span style="margin-left: 40px;">... Your Custom JS ...</span><br/>
										<span style="margin-left: 20px;">})(jQuery);</span><br/>
										&#60;/script&#62;
									</code>
								</p>
							</td>
						</tr>
						<tr>
							<td colspan="2"><p style="margin-top: 0px;">... so please don't add these lines to the editor; otherwise your code will fail. You can also use jQuery for your custom code.</td>
						</tr>
					</table>
	
					<hr class='style-two' style='margin: 0px auto;'>

					<form id="ts_vcsc_extend_custom_js_form" method="post" action="options.php" style="margin-top: 15px;">
						<span class="submit">
							<input style="margin: 0px; width: 200px;" title="Click here to save Custom JS Settings" class="button-primary ButtonSubmit" type="submit" name="Submit" value="<?php _e('Save Custom JS', 'ts_visual_composer_extend') ?>" />
						</span>
						<?php settings_fields('ts_vcsc_extend_custom_js'); ?>
						<div id="ts_vcsc_extend_custom_js_container">
							<div id="ts_vcsc_extend_custom_js" name="ts_vcsc_extend_custom_js"></div>
						</div>
						<textarea id="ts_vcsc_extend_custom_js_textarea" name="ts_vcsc_extend_custom_js" style="display: none;"><?php echo $ts_vcsc_extend_custom_js; ?></textarea>
						<span class="submit">
							<input style="margin: 0px; width: 200px;" title="Click here to save Custom JS Settings" class="button-primary ButtonSubmit" type="submit" name="Submit" value="<?php _e('Save Custom JS', 'ts_visual_composer_extend') ?>" />
						</span>
					</form>
					
					<div id="ts-settings-summary" style="display: none;" data-summary="<?php echo get_option('ts_vcsc_extend_settings_licenseKeyed', 'emptydelimiterfix'); ?>"><?php echo get_option('ts_vcsc_extend_settings_licenseInfo', ''); ?></div>
				</div>
				<?php
			} else {
				wp_die('You do not have sufficient permissions to access this page.');
			}
		}
		function TS_VCSC_PageLicense() {
			if ((current_user_can('ts_vcsc_extend')) || (current_user_can('manage_options'))) {
				echo '<div class="wrap ts-settings" id="ts_vcsc_extend_frame">' . "\n";
				echo '<div id="ts_vcsc_extend_icon_license" class="ts_vcsc_extend_icon"></div><h2 style="margin-bottom: 20px;">Visual Composer Extensions - Envato License Status</h2>' . "\n";
				include('assets/ts_vcsc_license.php');
				echo '</div>' . "\n";
			} else {
				wp_die('You do not have sufficient permissions to access this page.');
			}
		}
		
		
		// Function to load External Files on Front-End
		// --------------------------------------------
		function TS_VCSC_Extensions_Front_Main() {
			global $post;
			$url = plugin_dir_url( __FILE__ );
			// Load Scripts As Needed
			if (!empty($post)){
				if ($this->TS_VCSC_LoadFrontEndLightbox == "true") {
					wp_enqueue_script('ts-extend-hammer');
					wp_enqueue_script('ts-extend-nacho');
					wp_enqueue_style('ts-extend-nacho');
				}
				if ($this->TS_VCSC_LoadFrontEndTooltips == "true") {
					wp_enqueue_style('ts-extend-simptip');
				}
				if (get_option('ts_vcsc_extend_settings_loadFonts', 0) == 1) {
					// Add CSS for each enabled Font to WordPress Admin BackEnd
					foreach ($this->TS_VCSC_Installed_Icon_Fonts as $Icon_Font => $iconfont) {
						if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1) && (get_option('ts_vcsc_extend_settings_load' . $iconfont, 0) == 1) && ($iconfont != "Custom")) {
							wp_enqueue_style('ts-font-' . strtolower($iconfont));
						} else if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1) && (get_option('ts_vcsc_extend_settings_load' . $iconfont, 0) == 1) && ($iconfont == "Custom")) {
							$Custom_Font_CSS = get_option('ts_vcsc_extend_settings_tinymceCustomPath', '');
							wp_enqueue_style('ts-font-' . strtolower($iconfont) . 'vcsc');
						}
					}
				}
				if (((stripos($post->post_content, '[TS-VCSC-') !== FALSE) || (stripos($post->post_content, '[TS_VCSC_') !== FALSE)) && (get_option('ts_vcsc_extend_settings_loadForcable', 0) == 0)) { 
					// Load jQuery (if not already loaded)
					if (($this->TS_VCSC_LoadFrontEndJQuery == "true") && (!wp_script_is('jquery'))) {
						wp_enqueue_script('jquery');
					}
					// Load Google Charts API
					if (TS_VCSC_CheckShortcode('TS-VCSC-Google-Charts') == "true") {
						wp_enqueue_script('ts-extend-google-charts');
					}
					// Add CSS for each enabled Icon Font to Page
					foreach ($this->TS_VCSC_Installed_Icon_Fonts as $Icon_Font => $iconfont) {
						if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1)  && ($iconfont != "Custom")) {
							wp_enqueue_style('ts-font-' . strtolower($iconfont));
						} else if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1) && ($iconfont == "Custom")) {
							$Custom_Font_CSS = get_option('ts_vcsc_extend_settings_tinymceCustomPath', '');
							wp_enqueue_style('ts-font-' . strtolower($iconfont) . 'vcsc');
						}
					}
					// Load Modernizr
					if ($this->TS_VCSC_LoadFrontEndModernizr == "true") {
						wp_enqueue_script('ts-extend-modernizr');						
					}
					// Load Waypoints
					if ($this->TS_VCSC_LoadFrontEndWaypoints == "true") {
						if (wp_script_is('waypoints', $list = 'registered')) {
							wp_enqueue_script('waypoints');
						} else {
							wp_enqueue_script('ts-extend-waypoints');
						}
					}
					// Add Custom CSS / JS to Page
					if (get_option('ts_vcsc_extend_settings_codeeditors', 1) == 1) {
						add_action('wp_head', 		array($this, 'TS_VCSC_DisplayCustomCSS'));
						add_action('wp_footer', 	array($this, 'TS_VCSC_DisplayCustomJS'), 9999);
					}
				} else if (get_option('ts_vcsc_extend_settings_loadForcable', 0) == 1) {
					// Load Google Charts API
					if (TS_VCSC_CheckShortcode('TS-VCSC-Google-Charts') == "true") {
						wp_enqueue_script('ts-extend-google-charts');
					}
					// Load Modernizr
					if ($this->TS_VCSC_LoadFrontEndModernizr == "true") {
						wp_enqueue_script('ts-extend-modernizr');
					}
					// Load Waypoints
					if ($this->TS_VCSC_LoadFrontEndWaypoints == "true") {
						if (wp_script_is('waypoints', $list = 'registered')) {
							wp_enqueue_script('waypoints');
						} else {
							wp_enqueue_script('ts-extend-waypoints');
						}
					}
					wp_enqueue_style('dashicons');			
					// Add CSS for each enabled Icon Font to Page
					foreach ($this->TS_VCSC_Installed_Icon_Fonts as $Icon_Font => $iconfont) {
						if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1)  && ($iconfont != "Custom")) {
							wp_enqueue_style('ts-font-' . strtolower($iconfont));
						} else if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1) && ($iconfont == "Custom")) {
							$Custom_Font_CSS = get_option('ts_vcsc_extend_settings_tinymceCustomPath', '');
							wp_enqueue_style('ts-font-' . strtolower($iconfont) . 'vcsc');
						}
					}
					// Add custom CSS / JS to Page
					if (get_option('ts_vcsc_extend_settings_codeeditors', 1) == 1) {
						add_action('wp_head', 		array($this, 'TS_VCSC_DisplayCustomCSS'));
						add_action('wp_footer', 	array($this, 'TS_VCSC_DisplayCustomJS'), 9999);
					}
				} else {
					// Add CSS for each enabled Font to WordPress Admin BackEnd
					foreach ($this->TS_VCSC_Installed_Icon_Fonts as $Icon_Font => $iconfont) {
						if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1) && (get_option('ts_vcsc_extend_settings_load' . $iconfont, 0) == 1) && ($iconfont != "Custom")) {
							wp_enqueue_style('ts-font-' . strtolower($iconfont));
						} else if ((get_option('ts_vcsc_extend_settings_tinymce' . $iconfont, 0) == 1) && (get_option('ts_vcsc_extend_settings_load' . $iconfont, 0) == 1) && ($iconfont == "Custom")) {
							$Custom_Font_CSS = get_option('ts_vcsc_extend_settings_tinymceCustomPath', '');
							wp_enqueue_style('ts-font-' . strtolower($iconfont) . 'vcsc');
						}
					}
				}
			}
		}
		function TS_VCSC_Extensions_Front_Head() {
			global $post;
			if ((!empty($post)) || (get_option('ts_vcsc_extend_settings_loadForcable', 0) == 1)){
				if ((stripos($post->post_content, '[TS-VCSC-') !== FALSE) || (stripos($post->post_content, '[TS_VCSC_') !== FALSE) || (get_option('ts_vcsc_extend_settings_loadForcable', 0) == 1)) { 
					$url 		= plugin_dir_url( __FILE__ );
					$includes 	= includes_url();
					if (get_option('ts_vcsc_extend_settings_loadjQuery', 0) == 1) {
						echo '<script data-cfasync="false" type="text/javascript" src="' . $includes . 'js/jquery/jquery.js"></script>';
						echo '<script data-cfasync="false" type="text/javascript" src="' . $includes . 'js/jquery/jquery-migrate.min.js"></script>';
					}
					if (get_option('ts_vcsc_extend_settings_loadEnqueue', 1) == 0) {
						echo '<link rel="stylesheet" id="ts-extend-simptip"  href="' .							$url . 'css/jquery.vcsc.simptip.css" type="text/css" media="all" />';
						echo '<link rel="stylesheet" id="ts-extend-animations"  href="' .						$url . 'css/ts-visual-composer-extend-animations.min.css" type="text/css" media="all" />';
						echo '<link rel="stylesheet" id="ts-visual-composer-extend-front"  href="' .			$url . 'css/ts-visual-composer-extend-front.min.css" type="text/css" media="all" />';
						if (get_option('ts_vcsc_extend_settings_loadHeader', 0) == 1) {
							echo '<script data-cfasync="false" type="text/javascript" src="' .					$url . 'js/jquery.vcsc.adipoli.min.js"></script>';
							if (get_option('ts_vcsc_extend_settings_loadModernizr', 1) == 1) {
								echo '<script data-cfasync="false" type="text/javascript" src="' .				$url . 'js/jquery.vcsc.modernizr.min.js"></script>';
							}
							echo '<script data-cfasync="false" type="text/javascript" src="' .					$url . 'js/ts-visual-composer-extend-front.min.js"></script>';
						}
					}
				}
				$TS_VCSC_Lightbox_Defaults = get_option('ts_vcsc_extend_settings_defaultLightboxSettings', '');
				$TS_VCSC_Countdown_Language			= get_option('ts_vcsc_extend_settings_translationsCountdown', '');
				if (($TS_VCSC_Countdown_Language == false) || (empty($TS_VCSC_Countdown_Language))) {
					$TS_VCSC_Countdown_Language		= $this->TS_VCSC_Countdown_Language_Defaults;
				}
				$TS_VCSC_Google_Map_Language 		= get_option('ts_vcsc_extend_settings_translationsGoogleMap', '');
				if (($TS_VCSC_Google_Map_Language == false) || (empty($TS_VCSC_Google_Map_Language))) {
					$TS_VCSC_Google_Map_Language	= $this->TS_VCSC_Google_Map_Language_Defaults;
				}	
				echo '<script type="text/javascript">' . "\r\n";
					// Lightbox Settings
					echo '// Add Global Lightbox Settings for VC Extensions' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_Thumbs = "' . $TS_VCSC_Lightbox_Defaults['thumbs'] . '";' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_Thumbsize = ' . $TS_VCSC_Lightbox_Defaults['thumbsize'] . ';' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_Animation = "' . $TS_VCSC_Lightbox_Defaults['animation'] . '";' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_Captions = "' . $TS_VCSC_Lightbox_Defaults['captions'] . '";' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_Durations = ' . $TS_VCSC_Lightbox_Defaults['duration'] . ';' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_Share = ' . ($TS_VCSC_Lightbox_Defaults['share'] == 1 ? 'true' : 'false') . ';' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_Social = "' . $TS_VCSC_Lightbox_Defaults['social'] . '";' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_NoTouch = ' . ($TS_VCSC_Lightbox_Defaults['notouch'] == 1 ? 'false' : 'true') . ';' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_BGClose = ' . ($TS_VCSC_Lightbox_Defaults['bgclose'] == 1 ? 'true' : 'false') . ';' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_NoHashes = ' . ($TS_VCSC_Lightbox_Defaults['nohashes'] == 1 ? 'true' : 'false') . ';' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_Keyboard = ' . ($TS_VCSC_Lightbox_Defaults['keyboard'] == 1 ? 'true' : 'false') . ';' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_FullScreen = ' . ($TS_VCSC_Lightbox_Defaults['fullscreen'] == 1 ? 'true' : 'false') . ';' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_Zoom = ' . ($TS_VCSC_Lightbox_Defaults['zoom'] == 1 ? 'true' : 'false') . ';' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_FXSpeed = ' . $TS_VCSC_Lightbox_Defaults['fxspeed'] . ';' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_Scheme = "' . $TS_VCSC_Lightbox_Defaults['scheme'] . '";' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_Backlight = "' . $TS_VCSC_Lightbox_Defaults['backlight'] . '";' . "\r\n";
					echo 'var $TS_VCSC_Lightbox_UseColor = ' . ($TS_VCSC_Lightbox_Defaults['usecolor'] == 1 ? 'true' : 'false') . ';' . "\r\n";
					// Language Settings for Countdown
					echo '// Add Global Translation Strings for VC Extensions' . "\r\n";
					echo 'var $TS_VCSC_Countdown_DaysLabel = "' . $TS_VCSC_Countdown_Language['DayPlural'] . '";' . "\r\n";
					echo 'var $TS_VCSC_Countdown_DayLabel = "' . $TS_VCSC_Countdown_Language['DaySingular'] . '";' . "\r\n";
					echo 'var $TS_VCSC_Countdown_HoursLabel = "' . $TS_VCSC_Countdown_Language['HourPlural'] . '";' . "\r\n";
					echo 'var $TS_VCSC_Countdown_HourLabel = "' . $TS_VCSC_Countdown_Language['HourSingular'] . '";' . "\r\n";
					echo 'var $TS_VCSC_Countdown_MinutesLabel = "' . $TS_VCSC_Countdown_Language['MinutePlural'] . '";' . "\r\n";
					echo 'var $TS_VCSC_Countdown_MinuteLabel = "' . $TS_VCSC_Countdown_Language['MinuteSingular'] . '";' . "\r\n";
					echo 'var $TS_VCSC_Countdown_SecondsLabel = "' . $TS_VCSC_Countdown_Language['SecondPlural'] . '";' . "\r\n";
					echo 'var $TS_VCSC_Countdown_SecondLabel = "' . $TS_VCSC_Countdown_Language['SecondSingular'] . '";' . "\r\n";
					// Language Settings for Google Map
					echo 'var $TS_VCSC_GoogleMap_TextCalcShow = "' . $TS_VCSC_Google_Map_Language['TextCalcShow'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextCalcHide = "' . $TS_VCSC_Google_Map_Language['TextCalcHide'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextDirectionShow = "' . $TS_VCSC_Google_Map_Language['TextDirectionShow'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextDirectionHide = "' . $TS_VCSC_Google_Map_Language['TextDirectionHide'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextResetMap = "' . $TS_VCSC_Google_Map_Language['TextResetMap'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_PrintRouteText = "' . $TS_VCSC_Google_Map_Language['PrintRouteText'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextDistance = "' . $TS_VCSC_Google_Map_Language['TextDistance'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextViewOnGoogle = "' . $TS_VCSC_Google_Map_Language['TextViewOnGoogle'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextButtonCalc = "' . $TS_VCSC_Google_Map_Language['TextButtonCalc'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextSetTarget = "' . $TS_VCSC_Google_Map_Language['TextSetTarget'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextGeoLocation = "' . $TS_VCSC_Google_Map_Language['TextGeoLocation'] . '";' . "\r\n";	
					echo 'var $TS_VCSC_GoogleMap_TextTravelMode = "' . $TS_VCSC_Google_Map_Language['TextTravelMode'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextDriving = "' . $TS_VCSC_Google_Map_Language['TextDriving'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextWalking = "' . $TS_VCSC_Google_Map_Language['TextWalking'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextBicy = "' . $TS_VCSC_Google_Map_Language['TextBicy'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextWP = "' . $TS_VCSC_Google_Map_Language['TextWP'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextButtonAdd = "' . $TS_VCSC_Google_Map_Language['TextButtonAdd'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextMapHome = "' . $TS_VCSC_Google_Map_Language['TextMapHome'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextMapBikes = "' . $TS_VCSC_Google_Map_Language['TextMapBikes'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextMapTraffic = "' . $TS_VCSC_Google_Map_Language['TextMapTraffic'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextMapSpeedMiles = "' . $TS_VCSC_Google_Map_Language['TextMapSpeedMiles'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextMapSpeedKM = "' . $TS_VCSC_Google_Map_Language['TextMapSpeedKM'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextMapNoData = "' . $TS_VCSC_Google_Map_Language['TextMapNoData'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextMapMiles = "' . $TS_VCSC_Google_Map_Language['TextMapMiles'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextMapKilometes = "' . $TS_VCSC_Google_Map_Language['TextMapKilometes'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextActivate = "' . $TS_VCSC_Google_Map_Language['TextMapActivate'] . '";' . "\r\n";
					echo 'var $TS_VCSC_GoogleMap_TextDeactivate = "' . $TS_VCSC_Google_Map_Language['TextMapDeactivate'] . '";' . "\r\n";
					// Smooth Scroll Settings
					echo '// Add Global SmoothScroll Settings for VC Extensions' . "\r\n";
					if (get_option('ts_vcsc_extend_settings_additionsSmoothScroll', 0) == 1) {
						echo 'var $TS_VCSC_SmoothScrollActive = true;' . "\r\n";
						echo 'var $TS_VCSC_SmoothScrollSpeed = ' . get_option('ts_vcsc_extend_settings_additionsSmoothSpeed', 1500) . ';' . "\r\n";
					} else {
						echo 'var $TS_VCSC_SmoothScrollActive = false;' . "\r\n";
						echo 'var $TS_VCSC_SmoothScrollSpeed = ' . get_option('ts_vcsc_extend_settings_additionsSmoothSpeed', 1500) . ';' . "\r\n";
					}
				echo '</script>' . "\r\n";
			}
		}
		function TS_VCSC_Extensions_Front_Footer() {
			global $post;
			$url 		= plugin_dir_url( __FILE__ );
			$includes 	= includes_url();
			if ((!empty($post)) || (get_option('ts_vcsc_extend_settings_loadForcable', 0) == 1)){
				if ((stripos($post->post_content, '[TS-VCSC-') !== FALSE) || (stripos($post->post_content, '[TS_VCSC_') !== FALSE) || (get_option('ts_vcsc_extend_settings_loadForcable', 0) == 1)) { 
					if (get_option('ts_vcsc_extend_settings_loadEnqueue', 1) == 0) {
						if (get_option('ts_vcsc_extend_settings_loadHeader', 0) == 0) {
							echo '<script data-cfasync="false" type="text/javascript" src="' .					$url . 'js/jquery.vcsc.adipoli.min.js"></script>';
							if (get_option('ts_vcsc_extend_settings_loadModernizr', 1) == 1) {
								echo '<script data-cfasync="false" type="text/javascript" src="' .				$url . 'js/jquery.vcsc.modernizr.min.js"></script>';
							}
							echo '<script data-cfasync="false" type="text/javascript" src="' .					$url . 'js/ts-visual-composer-extend-front.min.js"></script>';
						}
					}
				}
			}
		}
		
		
		// Add Dashboard Widget
		// --------------------
		function TS_VCSC_DashboardHelpWidget() {
			global $wp_meta_boxes;
			wp_add_dashboard_widget('TS_VCSC_DashboardHelp', 'Visual Composer Extensions', array($this, 'TS_VCSC_DashboardHelpContent'));
		}
		function TS_VCSC_DashboardHelpContent() {
			$output = '';
			$output .= '<p><strong>Welcome to "Visual Composer Extensions"!</strong></p>';
			if ((function_exists('get_plugin_data'))) {
				$output .= '<p>Current Version: ' . TS_VCSC_GetPluginVersion();
			}
			if (get_option('ts_vcsc_extend_settings_tinymceCustomArray', '') != '') {
				$output .= '<p>Installed Fonts: ' . count($this->TS_VCSC_Installed_Icon_Fonts) . ' / Active Fonts: ' . $this->TS_VCSC_Active_Icon_Fonts . '</p>';
			} else {
				$output .= '<p>Installed Fonts: ' . (count($this->TS_VCSC_Installed_Icon_Fonts) - 1) . ' / Active Fonts: ' . $this->TS_VCSC_Active_Icon_Fonts . '</p>';
			}
			$output .= '<p>Installed Icons: ' . $this->TS_VCSC_Total_Icon_Count . ' / Active Icons: ' . $this->TS_VCSC_Active_Icon_Count . '</p>';
			if (get_option('ts_vcsc_extend_settings_extended', 0) == 1) {
				$output .= '<p style="text-align: justify;">Need help? Please contact the developer of your theme as it includes the plugin via extended license.<br/><br/>';
			} else {
				$output .= '<p style="text-align: justify;">Need help? Contact the developer at:<br/><a href="mailto:tekanewascripts@yahoo.com">tekanewascripts@yahoo.com</a><br/><br/>';
			}
			$output .= 'You will find the manual here:<br/><a href="http://tekanewascripts.info/composer/manual/" target="_blank">http://tekanewascripts.info/composer/manual/</a></p>';
			echo $output;
		}

		
		// Create custom Paramater Types for Visual Composer
		// -------------------------------------------------
		// Function to generate param type "standardpostcat"
		function standardpostcat_settings_field($settings, $value) {
			$dependency     	= vc_generate_dependencies_attributes($settings);
			$param_name     	= isset($settings['param_name']) ? $settings['param_name'] : '';
			$posttype			= isset($settings['posttype']) ? $settings['posttype'] : '';
			$posttaxonomy		= isset($settings['posttaxonomy']) ? $settings['posttaxonomy'] : '';
			$postsingle			= isset($settings['postsingle']) ? $settings['postsingle'] : '';
			$postplural			= isset($settings['postplural']) ? $settings['postplural'] : '';
			$postclass			= isset($settings['postclass']) ? $settings['postclass'] : '';
			$type           	= isset($settings['type']) ? $settings['type'] : '';
			$url            	= plugin_dir_url( __FILE__ );
			$output         	= '';
			$posts_fields 		= array();
			$categories			= '';
			$category_fields 	= array();
			$categories_count	= 0;
			$terms_slugs 		= array();
			$value_arr 			= $value;
			if (!is_array($value_arr)) {
				$value_arr = array_map('trim', explode(',', $value_arr));
			}
			// Categories for Standard Post Type
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 1,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false 
			);
			$categories = get_categories($args);
			$output .= '<div class="ts-standardpost-categories-holder">';
				$output .= '<textarea name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="display: none;">' . $value . '</textarea >';
				$output .= '<select multiple="multiple" name="' . $param_name . '_multiple" id="' . $param_name . '_multiple" data-holder="' . $param_name . '" class="ts-standardpost-categories-selector wpb-input wpb-select dropdown ' . $param_name . '_multiple" value=" ' . $value . '" style="margin-bottom: 20px;" data-selectable="' . __( "Included Categories:", "ts_visual_composer_extend" ) . '" data-selection="' . __( "Excluded Categories:", "ts_visual_composer_extend" ) . '">';
					foreach($categories as $category) { 
						$output .= '<option id="" class="" name="" data-id="" data-author="" value="' . $category->slug . '" ' . selected(in_array($category->slug, $value_arr), true, false) . '>' . $category->name . ' (' . $category->count . ')</option>';
					}
				$output .= '</select>';
				$output .= '<span style="font-size: 10px; margin-bottom: 20px; width: 100%; display: block; text-align: justify;">' . __( "Click on 'Included Categories' to exclude that category; click on 'Excluded Categories' to include a category again.", "ts_visual_composer_extend" ) . '</span>';
			$output .= '</div>';
			return $output;
		}
		// Function to generate param type "custompost"
		function custompost_settings_field($settings, $value) {
			$dependency     	= vc_generate_dependencies_attributes($settings);
			$param_name     	= isset($settings['param_name']) ? $settings['param_name'] : '';
			$posttype			= isset($settings['posttype']) ? $settings['posttype'] : '';
			$posttaxonomy		= isset($settings['posttaxonomy']) ? $settings['posttaxonomy'] : '';
			$postsingle			= isset($settings['postsingle']) ? $settings['postsingle'] : '';
			$postplural			= isset($settings['postplural']) ? $settings['postplural'] : '';
			$postclass			= isset($settings['postclass']) ? $settings['postclass'] : '';
			$type           	= isset($settings['type']) ? $settings['type'] : '';
			$url            	= plugin_dir_url( __FILE__ );
			$output         	= '';
			$posts_fields 		= array();
			$categories			= '';
			$category_fields 	= array();
			$categories_count	= 0;
			$terms_slugs 		= array();
			$value_arr 			= $value;
			if (!is_array($value_arr)) {
				$value_arr = array_map('trim', explode(',', $value_arr));
			}
			if (!empty($settings['posttype']) ) {
				$args = array(
					'no_found_rows' 		=> 1,
					'ignore_sticky_posts' 	=> 1,
					'posts_per_page' 		=> -1,
					'post_type' 			=> $posttype,
					'post_status' 			=> 'publish',
					'orderby' 				=> 'title',
					'order' 				=> 'ASC',
				);
				$custompost_nocategory			= 0;
				$custompost_query = new WP_Query($args);
				if ($custompost_query->have_posts()) {
					foreach($custompost_query->posts as $p) {
						$categories = TS_VCSC_GetTheCategoryByTax($p->ID, $posttaxonomy);
						if ($categories && !is_wp_error($categories)) {
							$category_slugs_arr = array();
							foreach ($categories as $category) {
								$category_slugs_arr[] = $category->slug;
								$category_data = array(
									'slug'		=> $category->slug,
									'name'		=> $category->cat_name,
									'count'		=> $category->count,
								);
								$category_fields[] = $category_data;
							}
							$categories_slug_str = join(",", $category_slugs_arr);
						} else {
							$custompost_nocategory++;
							$categories_slug_str = '';
						};
						$posts_fields[] = sprintf(
							'<option id="%s" class="%s" name="%s" value="%s" data-filter="false" data-id="%s" data-categories="%s" %s>%s (ID: %s)</option>',
							$settings['param_name'] . '-' . $p->ID,
							$settings['param_name'] . ' ' . $type,
							$settings['param_name'] . '-' . $p->ID,
							$p->ID,
							$p->post_title,
							$categories_slug_str,
							selected(in_array($p->ID, $value_arr), true, false),
							$p->post_title,
							$p->ID
						);
					}
				}
				wp_reset_postdata();
			}
			$category_fields = array_map("unserialize", array_unique(array_map("serialize", $category_fields)));
			$output .= '<div class="ts-custompost-selector-parent" data-selectable="' . __( "Available Categories:", "ts_visual_composer_extend" ) . '" data-selection="' . __( "Filtered By:", "ts_visual_composer_extend" ) . '">';
				if (count($category_fields) > 1) {
					$output .= '<div class="wpb_element_label">' . __( "Filter Controls", "ts_visual_composer_extend" ) . '</div>';
					$output .= '<div class="ts-switch-button ts-composer-switch" data-value="false" data-width="80" data-style="select" data-on="' . __( "Show", "ts_visual_composer_extend" ) . '" data-off="' . __( "Hide", "ts_visual_composer_extend" ) . '">';
						$output .= '<input type="checkbox" style="display: none;" class="toggle-input ts-custompost-filter-switch" value="false" id="ts-custompost-filter-switch" name="ts-custompost-filter-switch"/>';
						$output .= '<div class="toggle toggle-light" style="width: 80px; height: 20px;">';
							$output .= '<div class="toggle-slide">';
								$output .= '<div class="toggle-inner">';
									$output .= '<div class="toggle-on">'. __( "Show", "ts_visual_composer_extend" ) . '</div>';
									$output .= '<div class="toggle-blob"></div>';
									$output .= '<div class="toggle-off active">' . __( "Hide", "ts_visual_composer_extend" ) . '</div>';
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
					$output .= '<span class="description clear">' . __( "Switch the toggle if you want to show controls to filter available post types by categories.", "ts_visual_composer_extend" ) . '</span>';
					$output .= '<div class="ts-custom-post-filter-frame" style="display: none; margin-top: 20px;">';
						$output .= '<span style="font-size: 12px; margin-bottom: 10px; width: 100%; display: block;">' . __( "Filter by Category:", "ts_visual_composer_extend" ) . '</span>';
						$output .= '<select multiple="multiple" id="' . $param_name . '_filter" data-selector="' . $param_name . '" class="ts-' . $postclass . '-selector-filter ts-custompost-selector-filter">';
							if ($custompost_nocategory > 0) {
								$output .= '<option id="" class="" name="" data-id="" data-author="" data-category="ts-custompost-none-applied" value="ts-custompost-none-applied">' . __( "No Category", "ts_visual_composer_extend" ) . ' (' . $custompost_nocategory . ')</option>';
							}
							foreach ($category_fields as $index => $array) {
								$output .= '<option id="" class="" name="" data-id="" data-author="" data-category="' . $category_fields[$index]['slug'] . '" value="' . $category_fields[$index]['slug'] . '">' . $category_fields[$index]['name'] . ' (' . $category_fields[$index]['count'] . ')</option>';
							}
						$output .= '</select>';
						$output .= '<span style="font-size: 10px; margin-bottom: 20px; width: 100%; display: block; text-align: justify;">' . __( "Click on 'Available Categories' to filter by category; click on 'Flitered By' to remove from filter.", "ts_visual_composer_extend" ) . '</span>';
					$output .= '</div>';
				}
				$output .= '<select name="ts-custompost-selector-mirror" id="ts-custompost-selector-mirror" class="ts-custompost-selector-mirror dropdown" value="" style="display: none !important;">';
					$output .= implode( $posts_fields );
				$output .= '</select>';
				
				$output .= '<span style="font-size: 12px; margin-top: 20px; margin-bottom: 10px; width: 100%; display: block;">' . __( "Select", "ts_visual_composer_extend" ) . ' ' . $postsingle . ':</span>';
				$output .= '<select name="' . $param_name . '" id="' . $param_name . '" class="ts-' . $postclass . '-selector ts-custompost-selector wpb-input wpb-select dropdown wpb_vc_param_value ' . $param_name . ' ' . $type . '" value=" ' . $value . '" style="">';
					$output .= '<option id="" class="placeholder" name="" value="" data-filter="false" data-id="" data-categories="">' . __( "Select", "ts_visual_composer_extend" ) . ' ' . $postsingle . '</option>';
					$output .= implode( $posts_fields );
				$output .= '</select>';
			$output .= '</div>';
			return $output;
		}
		// Function to generate param type "custompostcat"
		function custompostcat_settings_field($settings, $value) {
			$dependency     	= vc_generate_dependencies_attributes($settings);
			$param_name     	= isset($settings['param_name']) ? $settings['param_name'] : '';
			$posttype			= isset($settings['posttype']) ? $settings['posttype'] : '';
			$posttaxonomy		= isset($settings['posttaxonomy']) ? $settings['posttaxonomy'] : '';
			$postsingle			= isset($settings['postsingle']) ? $settings['postsingle'] : '';
			$postplural			= isset($settings['postplural']) ? $settings['postplural'] : '';
			$postclass			= isset($settings['postclass']) ? $settings['postclass'] : '';
			$type           	= isset($settings['type']) ? $settings['type'] : '';
			$url            	= plugin_dir_url( __FILE__ );
			$output         	= '';
			$posts_fields 		= array();
			$categories			= '';
			$category_fields 	= array();
			$categories_count	= 0;
			$terms_slugs 		= array();
			$value_arr 			= $value;
			if (!is_array($value_arr)) {
				$value_arr = array_map('trim', explode(',', $value_arr));
			}			
			if (!empty($settings['posttype']) ) {
				$args = array(
					'no_found_rows' 			=> 1,
					'ignore_sticky_posts' 		=> 1,
					'posts_per_page' 			=> -1,
					'post_type' 				=> $posttype,
					'post_status' 				=> 'publish',
					'orderby' 					=> 'title',
					'order' 					=> 'ASC',
				);
				$custompost_nocategory_count	= 0;
				$custompost_nocategory_name		= 'ts-' . $postclass . '-none-applied';
				$custompost_query = new WP_Query($args);
				if ($custompost_query->have_posts()) {
					foreach($custompost_query->posts as $p) {
						$categories = TS_VCSC_GetTheCategoryByTax($p->ID, $posttaxonomy);
						if ($categories && !is_wp_error($categories)) {
							$category_slugs_arr = array();
							foreach ($categories as $category) {
								$category_slugs_arr[] = $category->slug;
								$category_data = array(
									'slug'		=> $category->slug,
									'name'		=> $category->cat_name,
									'count'		=> $category->count,
								);
								$category_fields[] = $category_data;
							}
							$categories_slug_str = join(",", $category_slugs_arr);
						} else {
							$custompost_nocategory_count++;
						}
					}
				}
				wp_reset_postdata();
			}
			$category_fields = array_map("unserialize", array_unique(array_map("serialize", $category_fields)));
			$output .= '<div class="ts-custompost-categories-holder">';
				$output .= '<textarea name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="display: none;">' . $value . '</textarea >';
				$output .= '<select multiple="multiple" name="' . $param_name . '_multiple" id="' . $param_name . '_multiple" data-holder="' . $param_name . '" class="ts-custompost-categories-selector wpb-input wpb-select dropdown ' . $param_name . '_multiple" value=" ' . $value . '" style="margin-bottom: 20px;" data-selectable="' . __( "Available Categories:", "ts_visual_composer_extend" ) . '" data-selection="' . __( "Applied Categories:", "ts_visual_composer_extend" ) . '">';
					if ($custompost_nocategory_count > 0) {
						$output .= '<option id="" class="" name="" data-id="" data-author="" value="ts-' . $postclass . '-none-applied" ' . selected(in_array($custompost_nocategory_name, $value_arr), true, false) . '>' . __( "No Category", "ts_visual_composer_extend" ) . ' (' . $custompost_nocategory_count . ')</option>';
					}
					foreach ($category_fields as $index => $array) {
						$output .= '<option id="" class="" name="" data-id="" data-author="" value="' . $category_fields[$index]['slug'] . '" ' . selected(in_array($category_fields[$index]['slug'], $value_arr), true, false) . '>' . $category_fields[$index]['name'] . ' (' . $category_fields[$index]['count'] . ')</option>';
					}
				$output .= '</select>';
				$output .= '<span style="font-size: 10px; margin-bottom: 20px; width: 100%; display: block; text-align: justify;">' . __( "Click on a name in 'Available Categories' to add category to slider; click on a name in 'Applied Categories' to remove from slider.", "ts_visual_composer_extend" ) . '</span>';
			$output .= '</div>';
			return $output;
		}
		// Function to generate param type "gopricing"
		function gopricing_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$radios         = isset($settings['options']) ? $settings['options'] : '';
			$url            = plugin_dir_url( __FILE__ );
			$output         = '';
			$pricing_tables = get_option('go_pricing_tables');
			if (!empty($pricing_tables)) {
				$output .= '<select name="' . $param_name . '" id="' . $param_name . '" class="ts-go-pricing-tables wpb-input wpb-select dropdown wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="margin-bottom: 20px;">';
					foreach ($pricing_tables as $pricing_table) {
						$tableID 	= $pricing_table['table-id'];
						$tableName	= $pricing_table['table-name'];
						if ($value == $tableID) {
							$output 	.= '<option class="" value="' . $tableID . '" selected>' . $tableName . '</option>';
						} else {
							$output 	.= '<option class="" value="' . $tableID . '">' . $tableName . '</option>';
						}
					}
				$output .= '</select>';
			} else {
				$output .= '<select name="' . $param_name . '" id="' . $param_name . '" class="ts-go-pricing-tables wpb-input wpb-select dropdown wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="margin-bottom: 20px;">';
					$output 	.= '<option class="" value="None">No Tables could be found!</option>';
				$output .= '</select>';
			}
			return $output;
		}
		// Function to generate param type "quform"
		function quform_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$radios         = isset($settings['options']) ? $settings['options'] : '';
			$url            = plugin_dir_url( __FILE__ );
			$output         = '';
			if (function_exists('iphorm_get_all_forms')) {
				$quforms_forms 	= iphorm_get_all_forms();
				if (count($quforms_forms)) {
					$output .= '<select name="' . $param_name . '" id="' . $param_name . '" class="ts-quform-selector wpb-input wpb-select dropdown wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="margin-bottom: 20px;">';
					foreach ($quforms_forms as $form) {
						$formID 	= $form['id'];
						$formName	= $form['name'];
						$formStatus	= $form['active'];
						if ($formStatus == 0) {
							if ($value == $formID) {
								$output .= '<option data-name="' . $formName . '" class="" value="' . $formID . '" selected>' . $formName . ' (inactive)</option>';
							} else {
								$output .= '<option data-name="' . $formName . '" class="" value="' . $formID . '">' . $formName . ' (inactive)</option>';
							}
						} else {
							if ($value == $formID) {
								$output .= '<option data-name="' . $formName . '" class="" value="' . $formID . '" selected>' . $formName . '</option>';
							} else {
								$output .= '<option data-name="' . $formName . '" class="" value="' . $formID . '">' . $formName . '</option>';
							}
						}
					}
					$output .= '</select>';
				} else {
					printf(esc_html__('No forms found, %sclick here to create one%s.', 'ts_visual_composer_extend'), '<a href="' . admin_url('admin.php?page=iphorm_form_builder') . '">', '</a>');
				}
			}
			return $output;
		}
		// Function to generate param type "css3animations"
		function css3animations_settings_field($settings, $value){
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name 	= isset($settings['param_name']) ? $settings['param_name'] : '';
			$type 			= isset($settings['type']) ? $settings['type'] : '';
			$class 			= isset($settings['class']) ? $settings['class'] : '';
			$noneselect		= isset($settings['noneselect']) ? $settings['noneselect'] : 'false';
			$standard		= isset($settings['standard']) ? $settings['standard'] : 'true';
			$prefix			= isset($settings['prefix']) ? $settings['prefix'] : '';
			$default		= isset($settings['default']) ? $settings['default'] : '';
			$connector		= isset($settings['connector']) ? $settings['connector'] : '';
			$effectgroups	= array();
			$selectedclass	= '';
			$selectedgroup	= '';
			$output 		= '';
			if (empty($value)) {
				$value		= $prefix . $default;
			}
			if ($noneselect == 'true') {
				$css3animations .= '<option class="" value="" data-name=""data-group="" data-prefix="" data-value="">' . __( "None", "ts_visual_composer_extend" ) . '</option>';
			}
			foreach ($this->TS_VCSC_CSS_Animations_Array as $Animation_Class => $animations) {
				if ($animations) {
					if (!in_array($animations['group'], $effectgroups)) {
						if ((($animations['group'] == 'Standard Visual Composer') && ($standard == 'true')) || ($animations['group'] != 'Standard Visual Composer')) {
							array_push($effectgroups, $animations['group']);
							$css3animations .= '<optgroup label="' . $animations['group'] . '">';
						}
					}
					if ($value == $prefix . $animations['class']) {
						if ((($animations['group'] == 'Standard Visual Composer') && ($standard == 'true')) || ($animations['group'] != 'Standard Visual Composer')) {
							$css3animations .= '<option class="' . $animations['class'] . '" value="' . $prefix . $animations['class'] . '" data-name="' . $Animation_Class . '" data-group="' . $animations['group'] . '" data-prefix="' . $prefix . '" data-value="' . $animations['class'] . '" selected="selected">' . $Animation_Class . '</option>';
							$selectedgroup 	= $animations['group'];
							if ($selectedgroup == 'Standard Visual Composer') {
								$selectedclass	= 'wpb_hover_animation wpb_' . $animations['class'];
							} else {
								$selectedclass	= 'ts-animation-frame ts-hover-css-' . $animations['class'];
							}
						}
					} else {
						if ((($animations['group'] == 'Standard Visual Composer') && ($standard == 'true')) || ($animations['group'] != 'Standard Visual Composer')) {
							$css3animations .= '<option class="' . $animations['class'] . '" value="' . $prefix . $animations['class'] . '" data-name="' . $Animation_Class . '"data-group="' . $animations['group'] . '" data-prefix="' . $prefix . '" data-value="' . $animations['class'] . '">' . $Animation_Class . '</option>';
						}
					}
				}
			}
			unset($effectgroups);
			$output .= '<div class="ts-css3-animations-wrapper" style="width: 100%; display: block;" data-connector="' . $connector . '" data-prefix="' . $prefix . '">';
				$output .= '<div class="ts-css3-animations-selector" style="width: 50%; float: left; margin-bottom: 10px;">';
					$output .= '<select name="' . $param_name . '" class="wpb_vc_param_value wpb-input wpb-select dropdown ' . $param_name . ' ' . $type . ' ' . $class . ' ' . $value . '" data-class="' . $class . '" data-type="' . $type . '" data-name="' . $param_name . '" data-option="' . $value . '" value="' . $value . '">';
						$output .= $css3animations;
					$output .= '</select>';
				$output .= '</div>';
				$output .= '<div class="ts-css3-animations-preview" style="padding: 0px; width: 40%; float: right; text-align: right; margin-left: 10%;">';
					$output .= '<span class="' . $selectedclass . '" style="padding: 10px; background: #C60000; color: #FFFFFF; border: 1px solid #dddddd; display: inline-block;">' . __( "Animation Preview", "ts_visual_composer_extend" ) . '</span>';
				$output .= '</div>';
			$output .= '</div>';
			return $output;
		}
		// Function to generate param type "seperator"
		function seperator_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$seperator		= isset($settings['seperator']) ? $settings['seperator'] : '';
			$url            = plugin_dir_url( __FILE__ );
			$output         = '';
			if ($seperator != '') {
				$output		.= '<div id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" name="' . $param_name . '" style="border-bottom: 2px solid #DDDDDD; margin-bottom: 10px; margin-top: 10px; padding-bottom: 10px; font-size: 20px; font-weight: bold; color: #BFBFBF;">' . $seperator . '</div>';
			} else {
				$output		.= '<div id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" name="' . $param_name . '" style="border-bottom: 2px solid #DDDDDD; margin-bottom: 10px; margin-top: 10px; padding-bottom: 10px; font-size: 20px; font-weight: bold; color: #BFBFBF;">' . $value . '</div>';
			}
			return $output;
		}
		// Function to generate param type "messenger"
		function messenger_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$message        = isset($settings['message']) ? $settings['message'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$suffix         = isset($settings['suffix']) ? $settings['suffix'] : '';
			$class          = isset($settings['class']) ? $settings['class'] : '';
			$color			= isset($settings['color']) ? $settings['color'] : '#000000';
			$weight			= isset($settings['weight']) ? $settings['weight'] : 'normal';
			$size			= isset($settings['size']) ? $settings['size'] : '12';
			$url            = plugin_dir_url( __FILE__ );
			$output         = '';
			if ($message != '') {
				$output 	.= '<div class="' . $param_name . ' ' . $type . '" name="' . $param_name . '" style="text-align: justify; border-top: 1px solid #dddddd; border-bottom: 1px solid #dddddd; color: ' . $color . '; margin: 10px 0; padding: 10px 0; font-size: ' . $size . 'px; font-weight: ' . $weight . ';">' . $message . '</div>';
			} else {
				$output 	.= '<div class="' . $param_name . ' ' . $type . '" name="' . $param_name . '" style="text-align: justify; border-top: 1px solid #dddddd; border-bottom: 1px solid #dddddd; color: ' . $color . '; margin: 10px 0; padding: 10px 0; font-size: ' . $size . 'px; font-weight: ' . $weight . ';">' . $value . '</div>';
			}
			return $output;
		}
		// Function to generate param type "videoselect"
		function videoselect_settings_field( $settings, $value ) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$video_format	= isset($settings['video_format']) ? $settings['video_format'] : 'mp4';			
			$video_format	= explode(',', $video_format);		
			$args = array(
				'post_type' 		=> 'attachment',
				'post_mime_type' 	=> 'video',
				'post_status' 		=> 'inherit',
				'posts_per_page' 	=> -1,
			);			
			if ($value != '') {
				$metadata			= wp_get_attachment_metadata($value);
				$disabled			= '';
				$visible			= 'display: block;';
				$query_videos 		= new WP_Query($args);
				if ($query_videos->have_posts()) {
					foreach ($query_videos->posts as $video) {
						if ($video->ID == $value) {
							$video_id 		= $value;
							$video_title 	= $video->post_title;
							$video_width	= $metadata['width'];
							$video_height	= $metadata['height'];
							$video_length	= $metadata['length_formatted'];
							break;
						}
					}
				}
			} else {
				$metadata			= array();
				$disabled			= 'disabled="disabled"';
				$visible			= 'display: none;';
				$video_id			= '';
				$video_title 		= '';
				$video_url			= '';
				$video_width		= '';
				$video_height		= '';
				$video_length		= '';
			}			
			$output 	.= '<div class="ts_vcsc_video_select_block" data-format="' . implode(',', $video_format) . '">';			
				$output 	.= '<input style="display: none;" name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput video_value ' . $param_name . ' ' . $type . '_field" type="text" value="' . $value . '" ' . $dependency . '/>';
				$output 	.= '<input type="button" class="video_select button" value="' . __( 'Select Video', 'ts_visual_composer_extend' ) . '" style="width: 150px; text-align: center;">';
				$output 	.= '<input type="button" class="video_remove button" value="' . __( 'Remove Video', 'ts_visual_composer_extend' ) . '" style="width: 150px; text-align: center; color: red; margin-left: 20px;" ' . $disabled . '>';
				$output		.= '<div class="video_metadata_frame" style="width: 100%; margin-top: 20px; ' .$visible . '">';
					$output		.= '<div style="float: left; width: 92px; margin-right: 10px;">';
						if (in_array("mp4", $video_format)) {
							$output		.= '<img src="' . TS_VCSC_GetResourceURL('images/mediatypes/mp4_video.jpg') . '" style="width: 90px; height: auto; border: 1px solid #ededed;">';
						} else if ((in_array("ogg", $video_format)) || (in_array("ogv", $video_format))) {
							$output		.= '<img src="' . TS_VCSC_GetResourceURL('images/mediatypes/ogg_video.jpg') . '" style="width: 90px; height: auto; border: 1px solid #ededed;">';
						} else if (in_array("webm", $video_format)) {
							$output		.= '<img src="' . TS_VCSC_GetResourceURL('images/mediatypes/webm_video.jpg') . '" style="width: 90px; height: auto; border: 1px solid #ededed;">';
						}
					$output 	.= '</div>';
					$output		.= '<div style="float: left;">';
						$output		.= '<div style=""><span style="">' . __( 'Video ID', 'ts_visual_composer_extend' ) . ': </span><span class="video_metadata video_id">' . $video_id . '</span></div>';
						$output		.= '<div style=""><span style="">' . __( 'Video Name', 'ts_visual_composer_extend' ) . ': </span><span class="video_metadata video_name">' . $video_title . '</span></div>';
						$output		.= '<div style=""><span style="">' . __( 'Video Duration', 'ts_visual_composer_extend' ) . ': </span><span class="video_metadata video_duration">' . ($video_length != '' ? $video_length : 'N/A') . '</span></div>';
						$output		.= '<div style=""><span style="">' . __( 'Video Width', 'ts_visual_composer_extend' ) . ': </span><span class="video_metadata video_width">' . ($video_width != '' ? $video_width : 'N/A') . '</span></div>';
						$output		.= '<div style=""><span style="">' . __( 'Video Height', 'ts_visual_composer_extend' ) . ': </span><span class="video_metadata video_height">' . ($video_height != '' ? $video_height : 'N/A') . '</span></div>';
					$output 	.= '</div>';
				$output 	.= '</div>';				
			$output 	.= '</div>';
			return $output;
		}
		// Function to generate param type "audioselect"
		function audioselect_settings_field( $settings, $value ) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$audio_format	= isset($settings['audio_format']) ? $settings['audio_format'] : 'mpeg';			
			$audio_format	= explode(',', $audio_format);		
			$args = array(
				'post_type' 		=> 'attachment',
				'post_mime_type' 	=> 'audio',
				'post_status' 		=> 'inherit',
				'posts_per_page' 	=> -1,
			);			
			if ($value != '') {
				$metadata			= wp_get_attachment_metadata($value);
				$disabled			= '';
				$visible			= 'display: block;';
				$query_audios 		= new WP_Query($args);
				if ($query_audios->have_posts()) {
					foreach ($query_audios->posts as $audio) {
						if ($audio->ID == $value) {
							$audio_id 		= $value;
							$audio_title 	= $audio->post_title;
							$audio_length	= $metadata['length_formatted'];
							break;
						}
					}
				}
			} else {
				$metadata			= array();
				$disabled			= 'disabled="disabled"';
				$visible			= 'display: none;';
				$audio_id			= '';
				$audio_title 		= '';
				$audio_url			= '';
				$audio_length		= '';
			}			
			$output 	.= '<div class="ts_vcsc_audio_select_block" data-format="' . implode(',', $audio_format) . '">';			
				$output 	.= '<input style="display: none;" name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput audio_value ' . $param_name . ' ' . $type . '_field" type="text" value="' . $value . '" ' . $dependency . '/>';
				$output 	.= '<input type="button" class="audio_select button" value="' . __( 'Select Audio', 'ts_visual_composer_extend' ) . '" style="width: 150px; text-align: center;">';
				$output 	.= '<input type="button" class="audio_remove button" value="' . __( 'Remove Audio', 'ts_visual_composer_extend' ) . '" style="width: 150px; text-align: center; color: red; margin-left: 20px;" ' . $disabled . '>';
				$output		.= '<div class="audio_metadata_frame" style="width: 100%; margin-top: 20px; ' .$visible . '">';
					$output		.= '<div style="float: left; width: 92px; margin-right: 10px;">';
						if ((in_array("mp3", $audio_format)) || (in_array("mpeg", $audio_format)) ){
							$output		.= '<img src="' . TS_VCSC_GetResourceURL('images/mediatypes/mp3_audio.jpg') . '" style="width: 90px; height: auto; border: 1px solid #ededed;">';
						} else if ((in_array("ogg", $audio_format)) || (in_array("ogv", $audio_format))) {
							$output		.= '<img src="' . TS_VCSC_GetResourceURL('images/mediatypes/ogg_audio.jpg') . '" style="width: 90px; height: auto; border: 1px solid #ededed;">';
						}
					$output 	.= '</div>';
					$output		.= '<div style="float: left;">';
						$output		.= '<div style=""><span style="">' . __( 'Audio ID', 'ts_visual_composer_extend' ) . ': </span><span class="audio_metadata audio_id">' . $audio_id . '</span></div>';
						$output		.= '<div style=""><span style="">' . __( 'Audio Name', 'ts_visual_composer_extend' ) . ': </span><span class="audio_metadata audio_name">' . $audio_title . '</span></div>';
						$output		.= '<div style=""><span style="">' . __( 'Audio Duration', 'ts_visual_composer_extend' ) . ': </span><span class="audio_metadata audio_duration">' . ($audio_length != '' ? $audio_length : 'N/A') . '</span></div>';
					$output 	.= '</div>';
				$output 	.= '</div>';				
			$output 	.= '</div>';
			return $output;
		}		
		// Function to generate param type "icons panel"
		function iconspanel_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$icon_select    = isset($settings['value']) ? $settings['value'] : '';
			$url            = plugin_dir_url( __FILE__ );
			$output         = '';
			
			$output .= '<div class="ts-font-icons-selector-parent">';
				if ($this->TS_VCSC_Active_Icon_Fonts > 1 ) {
					$output .= __( "Filter by Font:", "ts_visual_composer_extend" );
				}
				$output .= '<select name="ts-font-icons-fonts" id="ts-font-icons-fonts" class="ts-font-icons-fonts wpb_vc_param_value wpb-input wpb-select dropdown" style="margin-bottom: 20px; ' . ($this->TS_VCSC_Active_Icon_Fonts > 1 ? "display: block;" : "display: none;") . '">';
					foreach ($this->TS_VCSC_List_Select_Fonts as $Icon_Font => $iconfont) {
						if (strlen($iconfont) != 0) {
							$font = explode('-', $iconfont);
							$output .= '<option class="" value="font-' . $font[0] . '">' . $Icon_Font . '</option>';
						} else {
							$output .= '<option class="" value="">' . $Icon_Font . '</option>';
						}
					}
				$output .= '</select>';
				
				$output .= __( "Filter by Icon:", "ts_visual_composer_extend" );
				$output .= '<input name="ts-font-icons-search" id="ts-font-icons-search" class="ts-font-icons-search" type="text" placeholder="' . __( "Search ...", "ts_visual_composer_extend" ) . '" />';
				
				$output .= '<div id="ts-font-icons-count" class="ts-font-icons-count" data-count="' . $this->TS_VCSC_Active_Icon_Count . '" style="margin-top: 10px; font-size: 10px;">' . __( "Icons Found:", "ts_visual_composer_extend" ) . ' <span id="ts-font-icons-found" class="ts-font-icons-found">' . $this->TS_VCSC_Active_Icon_Count . '</span> / ' . $this->TS_VCSC_Active_Icon_Count . '</div>';
				
				$output .= '<div id="ts-font-icons-preview" class="ts-font-icons-preview" style="border: 1px solid #ededed; float: left; width: 100%; display: block; padding: 0; margin: 10px auto; background: #ededed; ' . ((empty($value) && $value != "transparent") ? "display: none;" : "") . '">';
					$output .= '<div style="float: left; text-align: left;">';
						$output .= '<span style="font-weight: bold; width: 100%; display: block; margin: 10px; padding: 0;">' . __( "Selected Icon:", "ts_visual_composer_extend" ) . '</span>';
						$output .= '<span style="width: 100%; display: block; margin: 10px; padding: 0;">' . __( "Class Name:", "ts_visual_composer_extend" ) . ' <span class="ts-font-icons-preview-class">' . $value . '</span></span>';
					$output .= '</div>';
					$output .= '<div style="float: right;">';
						$output .= '<i class="' . $value . '" style="display: inline-flex; font-size: 50px; height: 50px; width: 50px; line-height: 50px; color: #B24040; margin: 10px;"></i>';
					$output .= '</div>';
				$output .= '</div>';
				
				$output .= '<div class="ts-visual-selector ts-font-icons-wrapper">';
					$output .= '<input name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '"/>';
				
					foreach ($icon_select as $key => $option) {
						$font = explode('-', $key);
						if ($key) {
							if ($value == 'ts-' . $key) {
								$output .= '<a class="TS_VCSC_Icon_Taken TS_VCSC_Icon_Link current" href="#" title="' . __( "Icon Name:", "ts_visual_composer_extend" ) . ' ts-' . $key . '" data-filter="false" data-font="font-' . $font[0] . '" data-icon="ts-' . $key . '" rel="ts-' . $key . '"><i style="" class="ts-' . $key . '"></i><div class="selector-tick"></div></a>';
							} else {
								$output .= '<a class="TS_VCSC_Icon_Taken TS_VCSC_Icon_Link" href="#" title="' . __( "Icon Name:", "ts_visual_composer_extend" ) . ' ts-' . $key . '" data-filter="false" data-font="font-' . $font[0] . '" data-icon="ts-' . $key . '" rel="ts-' . $key . '"><i style="" class="ts-' . $key . '"></i></a>';
							}
						} else {
							if ($value == 'transparent') {
								$output .= '<a class="TS_VCSC_Icon_Empty TS_VCSC_Icon_Link ts-no-icon current" href="#" title="' . __( "Icon Name:", "ts_visual_composer_extend" ) . ' ' . __( "No Icon", "ts_visual_composer_extend" ) . '" rel="transparent">r<div class="selector-tick"></div></a>';
							} else {
								$output .= '<a class="TS_VCSC_Icon_Empty TS_VCSC_Icon_Link ts-no-icon" href="#" title="' . __( "Icon Name:", "ts_visual_composer_extend" ) . ' ' . __( "No Icon", "ts_visual_composer_extend" ) . '" rel="transparent">r</a>';
							}
						}
					}
					
					if (get_option('ts_vcsc_extend_settings_tinymceCustom', 0) == 1) {
						foreach ($this->TS_VCSC_Icons_Custom as $key => $option) {
							$font = explode('-', $key);
							if ($value == $key) {
								$output .= '<a class="TS_VCSC_Icon_Taken TS_VCSC_Icon_Link current" href="#" title="' . __( "Icon Name:", "ts_visual_composer_extend" ) . ' ' . $key . '" data-filter="false" data-font="font-custom" data-icon="' . $key . '" rel="' . $key . '"><i style="" class="' . $key . '"></i><div class="selector-tick"></div></a>';
							} else {
								$output .= '<a class="TS_VCSC_Icon_Taken TS_VCSC_Icon_Link" href="#" title="' . __( "Icon Name:", "ts_visual_composer_extend" ) . ' ' . $key . '" data-filter="false" data-font="font-custom" data-icon="' . $key . '" rel="' . $key . '"><i style="" class="' . $key . '"></i></a>';
							}
						}
					}
			
				$output .= '</div>';
			$output .= '</div>';
			return $output;
		}
		// Function to generate param type "backgrounds panel"
		function background_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$pattern_select	= isset($settings['value']) ? $settings['value'] : '';
			$encoding       = isset($settings['encoding']) ? $settings['encoding'] : '';
			$url            = plugin_dir_url( __FILE__ );
			$output         = '';
			$output .= '<div class="ts-visual-selector ts-font-background-wrapper">';
			$output .= '<input name="'.$param_name.'" id="'.$param_name.'" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '"/>';
			if ($encoding == 'true') {
				foreach ($pattern_select as $key => $option ) {
					if ($key) {
						if ($value == $key) {
							$output .= '<a class="TS_VCSC_Back_Link current" href="#" title="' . __( "Background Name:", "ts_visual_composer_extend" ) . ': ts-vcsc-' . $key . '" rel="' . $key . '"><img src="' . $url.$option . '" style="width: 34px; height: 34px;"><div class="selector-tick"></div></a>';
						} else {
							$output .= '<a class="TS_VCSC_Back_Link" href="#" title="' . __( "Background Name:", "ts_visual_composer_extend" ) . ': ts-vcsc-' . $key . '" rel="' . $key . '"><img src="' . $url.$option . '" style="width: 34px; height: 34px;"></a>';
						}
					} else {
						if ($value == 'transparent') {
							$output .= '<a class="TS_VCSC_Back_Link ts-no-background current" href="#" title="' . __( "Background Name:", "ts_visual_composer_extend" ) . ': ts-vcsc-transparent" rel="transparent">r<div class="selector-tick"></div></a>';
						} else {
							$output .= '<a class="TS_VCSC_Back_Link ts-no-background" href="#" title="' . __( "Background Name:", "ts_visual_composer_extend" ) . ': ts-vcsc-transparent" rel="transparent">r</a>';
						}
					}
				}
			} else {
				foreach ($pattern_select as $key => $option) {
					if ($key) {
						if ($value == $url.$option) {
							$output .= '<a class="TS_VCSC_Back_Link current" href="#" title="' . __( "Background Name:", "ts_visual_composer_extend" ) . ': ts-vcsc-' . $key . '" rel="' . $url.$option . '"><img src="' . $url.$option . '" style="width: 34px; height: 34px;"><div class="selector-tick"></div></a>';
						} else {
							$output .= '<a class="TS_VCSC_Back_Link" href="#" title="' . __( "Background Name:", "ts_visual_composer_extend" ) . ': ts-vcsc-' . $key . '" rel="' . $url.$option . '"><img src="' . $url.$option . '" style="width: 34px; height: 34px;"></a>';
						}
					} else {
						if ($value == 'transparent') {
							$output .= '<a class="TS_VCSC_Back_Link ts-no-background current" href="#" title="' . __( "Background Name:", "ts_visual_composer_extend" ) . ': ts-vcsc-transparent" rel="transparent">r<div class="selector-tick"></div></a>';
						} else {
							$output .= '<a class="TS_VCSC_Back_Link ts-no-background" href="#" title="' . __( "Background Name:", "ts_visual_composer_extend" ) . ': ts-vcsc-transparent" rel="transparent">r</a>';
						}
					}
				}
			}
			$output .= '</div>'; 
			return $output;
		}		
		// Function to generate param type "ratingicon panel"
		function ratingicon_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$icon_select    = isset($settings['value']) ? $settings['value'] : '';
			$url            = plugin_dir_url( __FILE__ );
			$output         = '';			
			$icon_classes = array(
				'ecommerce-starfull1',
				'ecommerce-starfull2',
				'ecommerce-starfull3',
				'ecommerce-starfull4',
				'ecommerce-heartfull',
				'ecommerce-heart',
				'ecommerce-thumbsup',
				'ecommerce-ribbon4',
			);
			if ($value == '') {
				$value = 'ts-ecommerce-starfull1';
			}	
			
			$output .= '<div class="ts-font-icons-selector-parent">';
				$output .= '<div id="ts-font-icons-count" class="ts-font-icons-count" data-count="' . count($icon_classes) . '" style="margin-top: 0px; font-size: 0px; height: 0px; line-height: 0px;">' . __( "Icons Found:", "ts_visual_composer_extend" ) . ' <span id="ts-font-icons-found" class="ts-font-icons-found">' . count($icon_classes) . '</span> / ' . count($icon_classes) . '</div>';
				$output .= '<div id="ts-font-icons-preview" class="ts-font-icons-preview" style="border: 1px solid #ededed; float: left; width: 100%; display: block; padding: 0; margin: 10px auto; background: #ededed; ' . ((empty($value) && $value != "transparent") ? "display: none;" : "") . '">';
					$output .= '<div style="float: left; text-align: left;">';
						$output .= '<span style="font-weight: bold; width: 100%; display: block; margin: 10px; padding: 0;">' . __( "Selected Icon:", "ts_visual_composer_extend" ) . '</span>';
						$output .= '<span style="width: 100%; display: block; margin: 10px; padding: 0;">' . __( "Class Name:", "ts_visual_composer_extend" ) . ' <span class="ts-font-icons-preview-class">' . $value . '</span></span>';
					$output .= '</div>';
					$output .= '<div style="float: right;">';
						$output .= '<i class="' . $value . '" style="display: inline-flex; font-size: 50px; height: 50px; width: 50px; line-height: 50px; color: #B24040; margin: 10px;"></i>';
					$output .= '</div>';
				$output .= '</div>';
				
				$output .= '<div class="ts-visual-selector ts-font-icons-wrapper" style="height: 60px;">';
					$output .= '<input name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '"/>';				
					foreach ($icon_classes as $key) {
						if ($key) {
							if ($value == 'ts-' . $key) {
								$output .= '<a class="TS_VCSC_Icon_Taken TS_VCSC_Icon_Link current" href="#" title="' . __( "Icon Name:", "ts_visual_composer_extend" ) . ' ts-' . $key . '" data-filter="false" data-font="font-ecommerce" data-icon="ts-' . $key . '" rel="ts-' . $key . '" style="height: 40px; width: 40px; margin: 9px 9px 0 0;">';
									$output .= '<i class="ts-' . $key . '" style="font-size: 40px; line-height: 40px; height: 40px; width: 40px;"></i><div class="selector-tick"></div>';
								$output .= '</a>';
							} else {
								$output .= '<a class="TS_VCSC_Icon_Taken TS_VCSC_Icon_Link" href="#" title="' . __( "Icon Name:", "ts_visual_composer_extend" ) . ' ts-' . $key . '" data-filter="false" data-font="font-ecommerce" data-icon="ts-' . $key . '" rel="ts-' . $key . '" style="height: 40px; width: 40px; margin: 9px 9px 0 0;">';
									$output .= '<i class="ts-' . $key . '" style="font-size: 40px; line-height: 40px; height: 40px; width: 40px;"></i>';
								$output .= '</a>';
							}
						}
					}			
				$output .= '</div>';
			$output .= '</div>';
			
			return $output;
		}
		// Function to generate param type "map marker panel"
		function mapmarker_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$pattern_select	= isset($settings['value']) ? $settings['value'] : '';
			$encoding       = isset($settings['encoding']) ? $settings['encoding'] : '';
			$url            = plugin_dir_url( __FILE__ );
			$dir 			= plugin_dir_path( __FILE__ );
			$output         = '';
			$output 		.= __( "Search for Marker:", "ts_visual_composer_extend" );
			$output 		.= '<input name="ts-font-marker-search" id="ts-font-marker-search" class="ts-font-marker-search" type="text" placeholder="Search ..." />';
			$output 		.= '<div class="ts-visual-selector ts-font-marker-wrapper">';
				$output		.= '<input name="'.$param_name.'" id="'.$param_name.'" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '"/>';
				$markerpath 	= $dir . 'images/marker/';
				$images 		= glob($markerpath . "*.png");
				foreach($images as $img)     {
					$markername	= basename($img);
					if ($value == $markername) {
						$output 	.= '<a class="TS_VCSC_Marker_Link current" href="#" title="' . __( "Marker Name:", "ts_visual_composer_extend" ) . ': ' . $markername . '" rel="' . $markername . '"><img src="' . TS_VCSC_GetResourceURL('images/marker/') . $markername . '" style="height: 37px; width: 32px;"><div class="selector-tick"></div></a>';
					} else {
						$output 	.= '<a class="TS_VCSC_Marker_Link" href="#" title="' . __( "Marker Name:", "ts_visual_composer_extend" ) . ': ' . $markername . '" rel="' . $markername . '"><img src="' . TS_VCSC_GetResourceURL('images/marker/') . $markername . '" style="height: 37px; width: 32px;"></a>';
					}
				}			
			$output .= '</div>'; 
			return $output;
		}
		// Function to generate param type "switch"
		function switch_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$on            	= isset($settings['on']) ? $settings['on'] : __( "On", "ts_visual_composer_extend" );
			$off            = isset($settings['off']) ? $settings['off'] : __( "Off", "ts_visual_composer_extend" );
			$style			= isset($settings['style']) ? $settings['style'] : 'select'; 			// 'compact' or 'select'
			$design			= isset($settings['design']) ? $settings['design'] : 'toggle-light'; 	// 'toggle-light', 'toggle-modern' or 'toggle'soft'
			$width			= isset($settings['width']) ? $settings['width'] : '80';
			$suffix         = isset($settings['suffix']) ? $settings['suffix'] : '';
			$class          = isset($settings['class']) ? $settings['class'] : '';
			$url            = plugin_dir_url( __FILE__ );
			$output         = '';
			$output .= '<div class="ts-switch-button ts-composer-switch" data-value="' . $value . '" data-width="' . $width . '" data-style="' . $style . '" data-on="' . $on . '" data-off="' . $off . '">';
				$output .= '<input type="hidden" style="display: none; " class="toggle-input wpb_vc_param_value ' . $param_name . ' ' . $type . '" value="' . $value . '" name="' . $param_name . '"/>';
				$output .= '<div class="toggle ' . $design . '" style="width: ' . $width . 'px; height: 20px;">';
					$output .= '<div class="toggle-slide">';
						$output .= '<div class="toggle-inner">';
							$output .= '<div class="toggle-on ' . ($value == 'true' ? 'active' : '') . '">' . $on . '</div>';
							$output .= '<div class="toggle-blob"></div>';
							$output .= '<div class="toggle-off ' . ($value == 'false' ? 'active' : '') . '">' . $off . '</div>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
			return $output;
		}
		// Function to generate param type "nouislider"
		function nouislider_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$min            = isset($settings['min']) ? $settings['min'] : '';
			$max            = isset($settings['max']) ? $settings['max'] : '';
			$step           = isset($settings['step']) ? $settings['step'] : '';
			$unit           = isset($settings['unit']) ? $settings['unit'] : '';
			$decimals		= isset($settings['decimals']) ? $settings['decimals'] : 0;
			$suffix         = isset($settings['suffix']) ? $settings['suffix'] : '';
			$class          = isset($settings['class']) ? $settings['class'] : '';
			$url            = plugin_dir_url( __FILE__ );
			$output         = '';
				$output 		.= '<div class="ts-nouislider-input-slider">';
				$output 		.= '<input style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="' . $param_name . '"  class="ts-nouislider-serial nouislider-input-selector nouislider-input-composer wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="text" value="' . $value . '"/>';
					$output 		.= '<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit">' . $unit . '</span>';
				$output 		.= '<div class="ts-nouislider-input ts-nouislider-input-element" data-value="' . $value . '" data-min="' . $min . '" data-max="' . $max . '" data-decimals="' . $decimals . '" data-step="' . $step . '" style="width: 250px; float: left; margin-top: 10px;"></div>';
			$output 		.= '</div>';
			return $output;
		}		
		// Function to generate param type "imagehotspot"
		function imagehotspot_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$min            = isset($settings['min']) ? $settings['min'] : '';
			$max            = isset($settings['max']) ? $settings['max'] : '';
			$step           = isset($settings['step']) ? $settings['step'] : '';
			$unit           = isset($settings['unit']) ? $settings['unit'] : '';
			$decimals		= isset($settings['decimals']) ? $settings['decimals'] : 0;
			$suffix         = isset($settings['suffix']) ? $settings['suffix'] : '';
			$class          = isset($settings['class']) ? $settings['class'] : '';
			$url            = plugin_dir_url( __FILE__ );
			$coordinates	= explode(",", $value);
			$output         = '';			
			if (version_compare($this->TS_VCSC_VisualComposer_Version, "4.3.0", '>=')) {
				// Hotspot Image Preview
				$output 		.= '<div class="ts-image-hotspot-container-preview" style="margin-top: 30px;">';
					$output 		.= '<img class="ts-image-hotspot-image-preview" data-default="' . TS_VCSC_GetResourceURL('images/other/hotspot_raster.jpg') . '" src="">';
					$output 		.= '<div class="ts-image-hotspot-holder-preview">';				
						$output 		.= '<div class="ts-image-hotspot-single-preview" style="left: ' . $coordinates[0] . '%; top: ' . $coordinates[1] . '%;">';					
							$output 		.= '<div class="ts-image-hotspot-trigger-preview"><div class="ts-image-hotspot-trigger-pulse"></div><div class="ts-image-hotspot-trigger-dot"></div></div>';
						$output 		.= '</div>';				
					$output			.= '</div>';
				$output 		.= '</div>';	
				$output 		.= '<div class="vc_clearfix"></div>';
				// Message
				$output			.= '<div class="" style="text-align: justify; margin-top: 30px; font-size: 13px; font-style: italic; color: #999999;">' . __( "Use the sliders below or use your mouse to drag the hotspot to its desired spot on the image.", "ts_visual_composer_extend" ) . '</div>';
			} else {
				// Message
				$output			.= '<div class="" style="text-align: justify; margin-top: 0px; font-size: 13px; font-style: italic; color: #999999;">' . __( "Use the sliders below to position the hotspot on its desired spot on the image.", "ts_visual_composer_extend" ) . '</div>';
			}
			// Hidden Input
			$output 		.= '<input name="' . $param_name . '" id="' . $param_name . '" class="ts-nouislider-hotspot-value wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '"/>';	
			// X-Position Slider
			$output 		.= '<div class="ts-nouislider-hotspot-slider" style="width: 100%; margin-top: 20px;">';
				$output			.= '<div class="" style="font-weight: bold;">' . __( "Horizontal Position (X)", "ts_visual_composer_extend" ) . '</div>';
				$output 		.= '<input id="ts-input-hotspot-horizontal" style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="" class="ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="text" value="' . $coordinates[0] . '"/>';
					$output 		.= '<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit">' . $unit . '</span>';
				$output 		.= '<div id="ts-nouislider-hotspot-horizontal" class="ts-nouislider-input ts-nouislider-hotspot-element" data-position="horizontal" data-value="' . $coordinates[0] . '" data-min="' . $min . '" data-max="' . $max . '" data-decimals="' . $decimals . '" data-step="' . $step . '" style="width: 250px; float: left; margin-top: 10px;"></div>';
			$output 		.= '</div>';
			$output 		.= '<div class="vc_clearfix"></div>';
			// Y-Position Slider
			$output 		.= '<div class="ts-nouislider-hotspot-slider" style="width: 100%; margin-top: 20px;">';
				$output			.= '<div class="" style="font-weight: bold;">' . __( "Vertical Position (Y)", "ts_visual_composer_extend" ) . '</div>';
				$output 		.= '<input id="ts-input-hotspot-vertical" style="width: 100px; float: left; margin-left: 0px; margin-right: 10px;" name="" class="ts-nouislider-serial nouislider-input-selector nouislider-input-composer" type="text" value="' . $coordinates[1] . '"/>';
					$output 		.= '<span style="float: left; margin-right: 30px; margin-top: 10px;" class="unit">' . $unit . '</span>';
				$output 		.= '<div id="ts-nouislider-hotspot-vertical" class="ts-nouislider-input ts-nouislider-hotspot-element" data-position="vertical" data-value="' . $coordinates[1] . '" data-min="' . $min . '" data-max="' . $max . '" data-decimals="' . $decimals . '" data-step="' . $step . '" style="width: 250px; float: left; margin-top: 10px;"></div>';
			$output 		.= '</div>';
			return $output;
		}		
		// Function to generate param type "fonts"
		function fonts_setting_field($settings, $value){
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$radios         = isset($settings['options']) ? $settings['options'] : '';
			$url            = plugin_dir_url( __FILE__ );
			$output = '<div class="ts-font-selector">';
				$output .= '<input name="' . $param_name . '" id="' . $param_name . '" class="ts-font-selector-list wpb-select wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="text" value="' . $value . '" data-holder="' . __( "Select a Font", "ts_visual_composer_extend" ) . '"/>';
			$output .= '</div>';
			return $output;
		}
		// Function to generate param type "hidden_input"
		function hiddeninput_setting_field($settings, $value){
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$radios         = isset($settings['options']) ? $settings['options'] : '';
			$output 		= '';
			$output .= '<input name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ts_shortcode_hidden ' . $param_name . ' '.$type.'" type="hidden" value="' . $value . '"/>';
			return $output;
		}
		// Function to generate param type "hidden_textarea"
		function hiddentextarea_setting_field($settings, $value){
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$radios         = isset($settings['options']) ? $settings['options'] : '';
			$output 		= '';
			$output .= '<textarea name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ts_shortcode_hidden ' . $param_name . ' '.$type.'" style="display: none !important;">' . $value . '</textarea>';
			return $output;
		}
		// Function to generate param type "load JS file"
		function loadfile_setting_field($settings, $value){
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$file_type      = isset($settings['file_type']) ? $settings['file_type'] : '';
			$file_id      	= isset($settings['file_id']) ? $settings['file_id'] : '';
			$file_path      = isset($settings['file_path']) ? $settings['file_path'] : '';
			$url            = plugin_dir_url( __FILE__ );
			$output         = '';
			if (!empty($file_path)) {
				if ($file_type == "js") {
					$output .= '<script type="text/javascript" src="' . $url.$file_path . '"></script>';
				} else if ($file_type == "css") {
					$output .= '<link rel="stylesheet" id="' . $file_id . '" type="text/css" href="' . $url.$file_path . '" media="all">';
				}
			}
			return $output;
		}
		// Function to generate param type "datetime_picker"
		function datetimepicker_setting_field($settings, $value){
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$radios         = isset($settings['options']) ? $settings['options'] : '';
			$period         = isset($settings['period']) ? $settings['period'] : '';
			$output 		= '';
			if ($period == "datetime") {
				$output 		.= '<div class="ts-datetime-picker-element">';
					$output 	.= '<input name="' . $param_name . '" id="' . $param_name . '" class="ts-datetimepicker-value wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '"/>';
					//$output		.= '<input class="ts-datetimepicker-clear" type="button" value="Clear" style="width: 150px; text-align: center; display: block; height: 30px; padding: 5px; font-size: 12px; line-height: 12px; margin-bottom: 10px;">';
					$output 	.= '<input class="ts-datetimepicker" type="text" placeholder="" value="' . $value . '"/>';
				$output 		.= '</div>';
			} else if ($period == "date") {
				$output 		.= '<div class="ts-date-picker-element">';
					$output 	.= '<input name="' . $param_name . '" id="' . $param_name . '" class="ts-datepicker-value wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '"/>';
					//$output		.= '<input class="ts-datetimepicker-clear" type="button" value="Clear" style="width: 150px; text-align: center; display: block; height: 30px; padding: 5px; font-size: 12px; line-height: 12px; margin-bottom: 10px;">';
					$output 	.= '<input class="ts-datepicker" type="text" placeholder="" value="' . $value . '"/>';
				$output 		.= '</div>';
			} else if ($period == "time") {
				$output 		.= '<div class="ts-time-picker-element">';
					$output 	.= '<input name="' . $param_name . '" id="' . $param_name . '" class="ts-timepicker-value wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="hidden" value="' . $value . '"/>';
					//$output		.= '<input class="ts-datetimepicker-clear" type="button" value="Clear" style="width: 150px; text-align: center; display: block; height: 30px; padding: 5px; font-size: 12px; line-height: 12px; margin-bottom: 10px;">';
					$output 	.= '<input class="ts-timepicker" type="text" placeholder="" value="' . $value . '"/>';
				$output 		.= '</div>';
			}
			return $output;
		}
		
		// Create custom Paramater Types for WooCommerce Elements
		// ------------------------------------------------------
		// Function to generate param type "wc_single_product"
		function wc_single_product_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$attr 			= array("post_type" => "product", "orderby" => "name", "order" => "asc", 'posts_per_page' => -1);
			$categories 	= get_posts($attr); 
			$output = '<select name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '">';
			foreach($categories as $category) {
				$selected 	= '';
				if ($value!=='' && $category->ID === $value) {
					$selected = ' selected="selected"';
				}
				$output .= '<option class="' . $category->ID . '" value="' . $category->ID . '" data-name="' . $category->post_title . '" ' . $selected . '>' . $category->post_title . ' (ID: ' . $category->ID . ')</option>';
			}
			$output .= '</select>';
			return $output;
		}
		// Function to generate param type "wc_multiple_products"
		function wc_multiple_products_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$value_arr 		= $value;
			if (!is_array($value_arr)) {
				$value_arr = array_map('trim', explode(',', $value_arr));
			}
			
			$attr 			= array("post_type" => "product", "orderby" => "name", "order" => "asc", 'posts_per_page' => -1);
			$categories 	= get_posts($attr);
			$output .= '<div class="ts-woocommerce-products-holder">';
				$output .= '<textarea name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="display: none;">' . $value . '</textarea >';
				$output .= '<select multiple="multiple" name="' . $param_name . '_multiple" id="' . $param_name . '_multiple" data-holder="' . $param_name . '" class="ts-woocommerce-products-selector wpb-input wpb-select dropdown ' . $param_name . '_multiple" value=" ' . $value . '" style="margin-bottom: 20px;" data-selectable="' . __( "Available Products:", "ts_visual_composer_extend" ) . '" data-selection="' . __( "Selected Products:", "ts_visual_composer_extend" ) . '">';
					foreach($categories as $category) { 
						$output .= '<option id="" class="" name="" data-id="" data-author="" value="' . $category->ID . '" ' . selected(in_array($category->ID, $value_arr), true, false) . '>' . $category->post_title . ' (ID: ' . $category->ID . ')</option>';
					}
				$output .= '</select>';
				$output .= '<span style="font-size: 10px; margin-bottom: 10px; width: 100%; display: block; text-align: justify;">' . __( "Click on 'Available Products' to add that product; click on 'Selected Products' to remove a product from selection.", "ts_visual_composer_extend" ) . '</span>';
			$output .= '</div>';
			
			return $output;
		}
		// Function to generate param type "wc_single_product_category"
		function wc_single_product_category_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$categories 	= get_terms('product_cat'); 
			$output = '<select name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-input wpb-select '.$settings['param_name'].' '.$settings['type'].'">';
			foreach ($categories as $category) {
				$selected 	= '';
				if ($value!=='' && $category->slug === $value) {
					$selected = ' selected="selected"';
				}
				$output .= '<option class="' . $category->slug . '" value="' . $category->slug . '" data-name="' . $category->name . '" ' . $selected . '>' . $category->name . '</option>';
			}
			$output .= '</select>';
			return $output;
		}
		// Function to generate param type "wc_multiple_product_categories"
		function wc_multiple_product_categories_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$value_arr 		= $value;
			if (!is_array($value_arr)) {
				$value_arr = array_map('trim', explode(',', $value_arr));
			}			
			$categories 	= get_terms('product_cat');
			$output .= '<div class="ts-woocommerce-categories-holder">';
				$output .= '<textarea name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="display: none;">' . $value . '</textarea >';
				$output .= '<select multiple="multiple" name="' . $param_name . '_multiple" id="' . $param_name . '_multiple" data-holder="' . $param_name . '" class="ts-woocommerce-categories-selector wpb-input wpb-select dropdown ' . $param_name . '_multiple" value=" ' . $value . '" style="margin-bottom: 20px;" data-selectable="' . __( "Available Categories:", "ts_visual_composer_extend" ) . '" data-selection="' . __( "Selected Categories:", "ts_visual_composer_extend" ) . '">';
					foreach($categories as $category) { 
						$output .= '<option id="" class="' . $category->slug . '" data-id="' . $category->term_id . '" data-count="' . $category->count . '" data-parent="' . $category->parent . '" value="' . $category->term_id . '" ' . selected(in_array($category->term_id, $value_arr), true, false) . '>' . $category->name . ' (&Sigma; ' . $category->count . ')</option>';
					}
				$output .= '</select>';
				$output .= '<span style="font-size: 10px; margin-bottom: 10px; width: 100%; display: block; text-align: justify;">' . __( "Click on 'Available Categories' to add that category; click on 'Selected Categories' to remove a category from selection.", "ts_visual_composer_extend" ) . '</span>';
			$output .= '</div>';
			
			return $output;
		}
		// Function to generate param type "wc_product_attributes"
		function wc_product_attributes_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$taxonomies 	= wc_get_attribute_taxonomies();
			$output = '<select name="'.$settings['param_name'].'" data-connector="ts-woocommerce-terms-selector" class="wpb_vc_param_value wpb-input wpb-select '.$settings['param_name'].' '.$settings['type'].'">';
			foreach ($taxonomies as $taxonomy) {
				$selected = '';
				if ($value!=='' && $taxonomy->attribute_name === $value) {
					$selected = ' selected="selected"';
				}
				$output .= '<option class="' . $taxonomy->attribute_name . '" data-taxonomy="pa_' . $taxonomy->attribute_name . '" value="' . $taxonomy->attribute_name . '"' . $selected . '>' . $taxonomy->attribute_label . '</option>';
			}
			$output .= '</select>';
			return $output;
		}
		// Function to generate param type "wc_product_terms"
		function wc_product_terms_settings_field($settings, $value) {
			$dependency     = vc_generate_dependencies_attributes($settings);
			$param_name     = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type           = isset($settings['type']) ? $settings['type'] : '';
			$value_arr 		= $value;
			if (!is_array($value_arr)) {
				$value_arr 	= array_map('trim', explode(',', $value_arr));
			}
			$taxonomies 	= wc_get_attribute_taxonomies();
			$taxonomy_terms = array();
			if ($taxonomies) {
				foreach ($taxonomies as $taxonomy) {
					if (taxonomy_exists(wc_attribute_taxonomy_name($taxonomy->attribute_name))) {
						$taxonomy_terms[$taxonomy->attribute_name] = get_terms(wc_attribute_taxonomy_name($taxonomy->attribute_name), 'orderby=name&hide_empty=0');
					}
				};
			};
			$output .= '<div class="ts-woocommerce-terms-holder">';
				$output .= '<textarea name="' . $param_name . '" id="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" style="display: none;">' . $value . '</textarea >';
				$output .= '<select multiple="multiple" name="' . $param_name . '_multiple" id="' . $param_name . '_multiple" data-holder="' . $param_name . '" class="ts-woocommerce-terms-selector wpb-input wpb-select dropdown ' . $param_name . '_multiple" value=" ' . $value . '" style="margin-bottom: 20px;" data-selectable="' . __( "Available Terms:", "ts_visual_composer_extend" ) . '" data-selection="' . __( "Selected Terms:", "ts_visual_composer_extend" ) . '">';
					foreach ($taxonomy_terms as $taxonomy_term) {
						foreach ($taxonomy_term as $term) {
							if (intval($term->count) > 0) {
								$output .= '<option id="" class="' . $term->slug . '" data-id="' . $term->term_id . '" data-taxonomy="' . $term->taxonomy . '" data-term="' . $term->slug . '" value="' . $term->slug . '" ' . selected(in_array($term->slug, $value_arr), true, false) . '>' . $term->name . ' (&Sigma; ' . $term->count . ')</option>';
							}
						}
					}
				$output .= '</select>';
				$output .= '<span style="font-size: 10px; margin-bottom: 10px; width: 100%; display: block; text-align: justify;">' . __( "Click on 'Available Terms' to add that term; click on 'Selected Terms' to remove a term from selection.", "ts_visual_composer_extend" ) . '</span>';
			$output .= '</div>';
			return $output;
		}
		
		
		// Load Composer Shortcodes + Elements + Add Custom Parameters
		function TS_VCSC_RegisterAllShortcodes() {
			if (function_exists('vc_is_inline')){
				if (vc_is_inline()) {
					$this->TS_VCSC_VCFrontEditMode 	= "true";
				} else {
					$this->TS_VCSC_VCFrontEditMode 	= "false";
				}
			} else {
				$this->TS_VCSC_VCFrontEditMode 		= "false";
			}
			// Standard Element Settings
			$TS_VCSC_Extension_Elements = get_option('ts_vcsc_extend_settings_StandardElements', '');
			if ($TS_VCSC_Extension_Elements == '') {
				$TS_VCSC_Options_Check 			= "true";
			} else {
				$TS_VCSC_Options_Check 			= "false";
			}
			foreach ($this->TS_VCSC_Visual_Composer_Elements as $ElementName => $element) {
				$defaultstatus 	= ($element['default'] == "true" ? 1 : 0);
				$key 			= $element['setting'];
				if ($element['type'] == 'demos') {
					$this->TS_VCSC_Visual_Composer_Elements[$ElementName]['active'] = "true";
					if ($this->TS_VCSC_VCFrontEditMode == "true") {
						require_once($this->shortcode_dir.'ts_vcsc_shortcode_' . $element['file'] . '.php');
					} else if (is_admin() == false) {
						require_once($this->shortcode_dir.'ts_vcsc_shortcode_' . $element['file'] . '.php');
					}
				} else if ($TS_VCSC_Options_Check == "true") {
					// Maintain backwards compatibility to settings stored prior to v2.5.0
					if (get_option('ts_vcsc_extend_settings_custom' . $element['setting'],	$defaultstatus) == 1) {
						$this->TS_VCSC_Visual_Composer_Elements[$ElementName]['active'] = "true";
						if ($element['type'] == 'internal') {
							if ($this->TS_VCSC_VCFrontEditMode == "true") {
								require_once($this->shortcode_dir.'ts_vcsc_shortcode_' . $element['file'] . '.php');
							} else if (is_admin() == false) {
								require_once($this->shortcode_dir.'ts_vcsc_shortcode_' . $element['file'] . '.php');
							}
						}
					} else {
						$this->TS_VCSC_Visual_Composer_Elements[$ElementName]['active'] = "false";
					}
				} else if ($TS_VCSC_Options_Check == "false") {
					// Retrieve settings stored since or after v2.5.0
					if (array_key_exists($key, $TS_VCSC_Extension_Elements)) {
						if ($TS_VCSC_Extension_Elements[$key] == "1") {
							$this->TS_VCSC_Visual_Composer_Elements[$ElementName]['active'] = "true";
						} else {
							$this->TS_VCSC_Visual_Composer_Elements[$ElementName]['active'] = "false";
						}
					} else {
						$this->TS_VCSC_Visual_Composer_Elements[$ElementName]['active'] = $defaultstatus;
					}
					if ($this->TS_VCSC_Visual_Composer_Elements[$ElementName]['active'] == "true") {
						if ($element['type'] == 'internal') {
							if ($this->TS_VCSC_VCFrontEditMode == "true") {
								require_once($this->shortcode_dir.'ts_vcsc_shortcode_' . $element['file'] . '.php');
							} else if (is_admin() == false) {
								require_once($this->shortcode_dir.'ts_vcsc_shortcode_' . $element['file'] . '.php');
							}
						}
					}
				} else {
					$this->TS_VCSC_Visual_Composer_Elements[$ElementName]['active'] = "false";
				}
			}
			// WooCommerce Elements Settings
			if ($this->TS_VCSC_WooCommerceActive == "true") {
				$TS_VCSC_WooCommerce_Elements = get_option('ts_vcsc_extend_settings_WooCommerceElements', '');
				if ((!is_array($TS_VCSC_WooCommerce_Elements)) || ($TS_VCSC_WooCommerce_Elements == '')) {
					$TS_VCSC_WooCommerce_Elements = array();
				}
				foreach ($this->TS_VCSC_WooCommerce_Elements as $ElementName => $element) {
					$defaultstatus 	= ($element['default'] == "true" ? "true" : "false");
					$key 			= $element['setting'];
					if (array_key_exists($key, $TS_VCSC_WooCommerce_Elements)) {
						if ($TS_VCSC_WooCommerce_Elements[$key] == "1") {
							$this->TS_VCSC_WooCommerce_Elements[$ElementName]['active'] = "true";
						} else {
							$this->TS_VCSC_WooCommerce_Elements[$ElementName]['active'] = "false";
						}
					} else {
						$this->TS_VCSC_WooCommerce_Elements[$ElementName]['active'] = $defaultstatus;
					}
				}
			}
			// Iconicum Settings
			if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_iconicum', 1) == 1) && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0) && (get_option('ts_vcsc_extend_settings_useIconGenerator', 0) == 1) && (get_option('ts_vcsc_extend_settings_demo', 1) == 0))) {
				require_once($this->shortcode_dir.'ts_vcsc_shortcode_icon_generator.php');
			}
		}
		function TS_VCSC_RegisterWithComposer() {
			if (function_exists('vc_is_inline')){
				if ((vc_is_inline()) || (is_admin())) {
					$this->TS_VCSC_AddParametersToComposer();
					$this->TS_VCSC_AddElementsToComposer();
				} else {
					$this->TS_VCSC_LoadClassElements();
				}
			} else if (is_admin()) {
				$this->TS_VCSC_AddParametersToComposer();
				$this->TS_VCSC_AddElementsToComposer();
			} else {
				$this->TS_VCSC_LoadClassElements();
			}
		}
		function TS_VCSC_AddParametersToComposer() {
			if (function_exists('add_shortcode_param')) {
				// Generate param type "custompost" + "custompostcat"
				if ($this->TS_VCSC_CustomPostTypesCheckup == "true") {
					if (($this->TS_VCSC_CustomPostTypesTeam == true) || ($this->TS_VCSC_CustomPostTypesTestimonial == true) || ($this->TS_VCSC_CustomPostTypesLogo == true) || ($this->TS_VCSC_CustomPostTypesSkillset == true)) {
						add_shortcode_param('custompost',		array($this, 'custompost_settings_field'));
						add_shortcode_param('custompostcat',	array($this, 'custompostcat_settings_field'));
					}
				}
				// Generate param type "standardpostcat"
				add_shortcode_param('standardpostcat',		array($this, 'standardpostcat_settings_field'));
				// Generate param type "seperator"
				add_shortcode_param('seperator',        	array($this, 'seperator_settings_field'));
				// Generate param type "messenger"
				add_shortcode_param('messenger',        	array($this, 'messenger_settings_field'));
				// Generate param type "videoselect"
				add_shortcode_param( 'videoselect', 		array($this, 'videoselect_settings_field'));
				// Generate param type "audioselect"
				add_shortcode_param( 'audioselect', 		array($this, 'audioselect_settings_field'));
				// Generate param type "icon-panel"
				add_shortcode_param('icons_panel',      	array($this, 'iconspanel_settings_field'));
				// Generate param type "ratingicon-panel"
				add_shortcode_param('ratingicon',			array($this, 'ratingicon_settings_field'));
				// Generate param type "background-panel"
				add_shortcode_param('background',       	array($this, 'background_settings_field'));
				// Generate param type "mapmarker-panel"
				add_shortcode_param('mapmarker',       		array($this, 'mapmarker_settings_field'));
				// Generate param type "switch"
				add_shortcode_param('switch',           	array($this, 'switch_settings_field'));
				// Generate param type "nouislider"
				add_shortcode_param('nouislider',       	array($this, 'nouislider_settings_field'));
				// Generate param type "imagehotspot"
				add_shortcode_param('imagehotspot',       	array($this, 'imagehotspot_settings_field'));
				// Generate param type "fonts"
				add_shortcode_param('fonts',            	array($this, 'fonts_setting_field'));
				// Generate param type "hidden_input"
				add_shortcode_param('hidden_input',     	array($this, 'hiddeninput_setting_field'));
				// Generate param type "hidden_textarea"
				add_shortcode_param('hidden_textarea',		array($this, 'hiddentextarea_setting_field'));
				// Generate param type "load_file"
				add_shortcode_param('load_file',        	array($this, 'loadfile_setting_field'));
				// Generate param type "datetime_picker"
				add_shortcode_param('datetime_picker',		array($this, 'datetimepicker_setting_field'));
				// Generate param type "css3animations"
				add_shortcode_param('css3animations',		array($this, 'css3animations_settings_field'));
				// Generate param type "gopricing"
				add_shortcode_param('gopricing',			array($this, 'gopricing_settings_field'));
				// Generate param type "quform"
				add_shortcode_param('quform',				array($this, 'quform_settings_field'));
				if ($this->TS_VCSC_WooCommerceActive == "true") {
					// Generate param type "wc_single_product"
					add_shortcode_param('wc_single_product', 				array($this, 'wc_single_product_settings_field'));
					// Generate param type "wc_multiple_products"
					add_shortcode_param('wc_multiple_products', 			array($this, 'wc_multiple_products_settings_field'));
					// Generate param type "wc_single_product_category"
					add_shortcode_param('wc_single_product_category', 		array($this, 'wc_single_product_category_settings_field'));
					// Generate param type "wc_multiple_product_categories"
					add_shortcode_param('wc_multiple_product_categories', 	array($this, 'wc_multiple_product_categories_settings_field'));
					// Generate param type "wc_product_attributes"
					add_shortcode_param('wc_product_attributes', 			array($this, 'wc_product_attributes_settings_field'));
					// Generate param type "wc_product_terms"
					add_shortcode_param('wc_product_terms', 				array($this, 'wc_product_terms_settings_field'));
				}
			}
		}
		function TS_VCSC_AddElementsToComposer() {
			foreach ($this->TS_VCSC_Visual_Composer_Elements as $ElementName => $element) {
				if ($element['active'] == "true") {
					if ($element['type'] == 'internal') {
						require_once($this->elements_dir.'ts_vcsc_element_' . $element['file'] . '.php');
					} else if ($element['type'] == 'class') {
						require_once($this->classes_dir.'ts_vcsc_class_' . $element['file'] . '.php');
					} else if ($element['type'] == 'external') {
						require_once($this->plugins_dir.'ts_vcsc_element_' . $element['file'] . '.php');
					}
				}
			}
			// Load WooCommerce Elements
			if ($this->TS_VCSC_WooCommerceActive == "true") {
				foreach ($this->TS_VCSC_WooCommerce_Elements as $ElementName => $element) {
					if ($element['active'] == "true") {
						if ($element['type'] == 'internal') {
							require_once($this->woocommerce_dir.'ts_vcsc_woocommerce_' . $element['file'] . '.php');
						} else if ($element['type'] == 'class') {
							require_once($this->woocommerce_dir.'ts_vcsc_woocommerce_' . $element['file'] . '.php');
						}
					}
				}
			}
			if ($this->TS_VCSC_CustomPostTypesCheckup == "true") {
				// Load Teammate Settings
				if ($this->TS_VCSC_CustomPostTypesTeam == "true") {
					require_once($this->classes_dir.'ts_vcsc_class_teammates.php');
					require_once($this->classes_dir.'ts_vcsc_class_teampage.php');
				}
				// Load Testimonial Settings
				if ($this->TS_VCSC_CustomPostTypesTestimonial == "true") {
					require_once($this->classes_dir.'ts_vcsc_class_testimonials.php');
				}
				// Load Logo Settings
				if ($this->TS_VCSC_CustomPostTypesLogo == "true") {
					require_once($this->classes_dir.'ts_vcsc_class_logos.php');
				}
				// Load Skillset Settings
				if ($this->TS_VCSC_CustomPostTypesSkillset == "true") {
					require_once($this->classes_dir.'ts_vcsc_class_skillsets.php');
				}
			}
			if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_additions', 1) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0))) {
				// Load Extended Row Settings
				if (get_option('ts_vcsc_extend_settings_additionsRows', 0) == 1) {
					require_once($this->elements_dir.'ts_vcsc_element_row.php');
				}
				// Load Extended Column Settings
				if (get_option('ts_vcsc_extend_settings_additionsColumns', 0) == 1) {
					require_once($this->elements_dir.'ts_vcsc_element_column.php');
				}
			}
		}
		function TS_VCSC_LoadClassElements() {
			// Load Elements with Class Definitions
			foreach ($this->TS_VCSC_Visual_Composer_Elements as $ElementName => $element) {
				if ($element['active'] == "true") {
					if ($element['type'] == 'class') {
						require_once($this->classes_dir.'/ts_vcsc_class_' . $element['file'] . '.php');
					}
				}
			}
			// Load WooCommerce Elements
			if ($this->TS_VCSC_WooCommerceActive == "true") {
				foreach ($this->TS_VCSC_WooCommerce_Elements as $ElementName => $element) {
					if ($element['active'] == "true") {
						if ($element['type'] == 'class') {
							require_once($this->woocommerce_dir.'ts_vcsc_woocommerce_' . $element['file'] . '.php');
						}
					}
				}
			}
			if ($this->TS_VCSC_CustomPostTypesCheckup == "true") {
				// Load Teammate Settings
				if ($this->TS_VCSC_CustomPostTypesTeam == "true") {
					require_once($this->classes_dir.'ts_vcsc_class_teammates.php');
					require_once($this->classes_dir.'ts_vcsc_class_teampage.php');
				}
				// Load Testimonial Settings
				if ($this->TS_VCSC_CustomPostTypesTestimonial == "true") {
					require_once($this->classes_dir.'ts_vcsc_class_testimonials.php');
				}
				// Load Logo Settings
				if ($this->TS_VCSC_CustomPostTypesLogo == "true") {
					require_once($this->classes_dir.'ts_vcsc_class_logos.php');
				}
				// Load Skillset Settings
				if ($this->TS_VCSC_CustomPostTypesSkillset == "true") {
					require_once($this->classes_dir.'ts_vcsc_class_skillsets.php');
				}
			}
			if (((get_option('ts_vcsc_extend_settings_extended', 0) == 1) && (get_option('ts_vcsc_extend_settings_additions', 1) == 1)) || ((get_option('ts_vcsc_extend_settings_extended', 0) == 0))) {
				// Load Extended Row Settings
				if (get_option('ts_vcsc_extend_settings_additionsRows', 0) == 1) {
					require_once($this->elements_dir.'ts_vcsc_element_row.php');
				}
				// Load Extended Column Settings
				if (get_option('ts_vcsc_extend_settings_additionsColumns', 0) == 1) {
					require_once($this->elements_dir.'ts_vcsc_element_column.php');
				}
			}
		}
		
		
		/* Functions for Custom Font Upload */
		/* -------------------------------- */
		
		// Sets path to wp-content/uploads/ts-vcsc-icons/custom-pack
		function TS_VCSC_SetUploadDirectory($upload) {
			$upload['subdir'] 	= '/ts-vcsc-icons/custom-pack';
			$upload['path'] 	= $upload['basedir'] . $upload['subdir'];
			$upload['url']   	= $upload['baseurl'] . $upload['subdir'];
			return $upload;
		}
		// If you are on the Upload a Custom Icon Pack Page => set custom path for all uploads to wp-content/uploads/ts-vcsc-icons/custom-pack
		function TS_VCSC_ChangeDownloadsUploadDirectory() {
			$actual_link 		= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$actual_link 		= explode('/', $actual_link);
			$urlBasename 		= array_pop($actual_link);
			$upload_directory 	= wp_upload_dir();
			$font_directory		= $upload_directory['basedir'] . '/ts-vcsc-icons/custom-pack';
			update_option('ts_vcsc_extend_settings_tinymceCustomDirectory', $font_directory);
			if ($urlBasename == 'admin.php?page=TS_VCSC_Uploader') {
				add_filter('upload_dir', array($this, 'TS_VCSC_SetUploadDirectory'));
			} 
		}
		// Register custom pack already installed error
		function TS_VCSC_CustomPackInstalledError(){
			//$TS_VCSC_Icons_Custom 			= get_option('ts_vcsc_extend_settings_tinymceCustomArray', '');
			//$TS_VCSC_tinymceCustomCount		= get_option('ts_vcsc_extend_settings_tinymceCustomCount', 0);
			if ((ini_get('allow_url_fopen') == '1') || (TS_VCSC_cURLcheckBasicFunctions() == true)) {
				$RemoteFileAccess = true;
			} else {
				$RemoteFileAccess = false;
			}
			$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$actual_link = explode('/', $actual_link);
			$urlBasename = array_pop($actual_link);
			if ($urlBasename == 'admin.php?page=TS_VCSC_Uploader' ) {
				$dest = wp_upload_dir();
				$dest_path = $dest['path'];
				// If a file exists display included icons
				if ((file_exists($dest_path.'/ts-vcsc-custom-pack.zip')) && ($RemoteFileAccess == true) && (get_option('ts_vcsc_extend_settings_tinymceCustomArray', '') != '')) {
					// Disable File Upload Field if custom font pack exists or system requirements are not met
					echo '<script>
						jQuery(document).ready(function() {
							jQuery(".ts-vcsc-custom-pack-preloader").hide();
							jQuery(".preview-icon-code-box").show();
							jQuery(".dropDownDownload").removeAttr("disabled");
							jQuery("#ts_vcsc_custom_pack_field").attr("disabled", "disabled");
							jQuery("input[value=Import]").attr("disabled", "disabled");
						});
					</script>';
				} else if ($RemoteFileAccess == false) {
					TS_VCSC_ResetCustomFont();
					echo '<script>
						jQuery(document).ready(function() {
							jQuery(".ts-vcsc-custom-pack-preloader").hide();
							jQuery(".preview-icon-code-box").hide();
							jQuery("#ts_vcsc_custom_pack_field").attr("disabled", "disabled");
							jQuery("#uninstall-pack-button").attr("disabled", "disabled");
							jQuery(".dropDownDownload").attr("disabled", "disabled");
							jQuery("input[value=Import]").attr("disabled", "disabled");
							jQuery(".ts-vcsc-custom-pack-buttons").after("<div class=error><p class=fontPackUploadedError>Your system does not fulfill the requirements to import a custom font.</p></div>");
						});
					</script>';	
				}
				if (($RemoteFileAccess == true) && (file_exists( $dest_path.'/ts-vcsc-custom-pack.json' )) && (file_exists($dest_path.'/style.css')) && (get_option('ts_vcsc_extend_settings_tinymceCustomArray', '') != '')) {
					// Create Preview of Imported Icons
					$output = "";
					$output .= "<div id='ts-vcsc-extend-preview' class=''>";
						$output .="<div id='ts-vcsc-extend-preview-name'>Font Name: " . 		get_option('ts_vcsc_extend_settings_tinymceCustomName', 'Custom User Font') . "</div>";
						$output .="<div id='ts-vcsc-extend-preview-author'>Font Author: " . 	get_option('ts_vcsc_extend_settings_tinymceCustomAuthor', 'Custom User') . "</div>";
						$output .="<div id='ts-vcsc-extend-preview-count'>Icon Count: " . 		get_option('ts_vcsc_extend_settings_tinymceCustomCount', 0) . "</div>";
						$output .="<div id='ts-vcsc-extend-preview-date'>Uploaded: " . 			get_option('ts_vcsc_extend_settings_tinymceCustomDate', '') . "</div>";
						$output .= "<div id='ts-vcsc-extend-preview-list' class=''>";
						$icon_counter = 0;
						foreach (get_option('ts_vcsc_extend_settings_tinymceCustomArray', '') as $key => $option ) {
							$font = explode('-', $key);
							$output .= "<div class='ts-vcsc-icon-preview' data-name='" . $key . "' data-code='" . $option . "' data-font='Custom' data-count='" . $icon_counter . "' rel='" . $key . "'><span class='ts-vcsc-icon-preview-icon'><i class='" . $key . "'></i></span><span class='ts-vcsc-icon-preview-name'>" . $key . "</span></div>";
							$icon_counter = $icon_counter + 1;
						}
						$output .= "</div>";
					$output .= "</div>";
					echo '<script>
						jQuery(document).ready(function() {
							jQuery("#current-font-pack-preview").html("' . $output. '");
						});
					</script>';
				} else if ((file_exists($dest_path.'/ts-vcsc-custom-pack.zip')) && ($RemoteFileAccess == true) && (get_option('ts_vcsc_extend_settings_tinymceCustom', 0) == 0) && (get_option('ts_vcsc_extend_settings_tinymceCustomArray', '') == '')) {
					TS_VCSC_ResetCustomFont();
					echo '<script>
						jQuery(document).ready(function() {
							jQuery("#ts_vcsc_custom_pack_field").attr("disabled", "disabled");
							jQuery("input[value=Import]").attr("disabled", "disabled");
							jQuery(".ts-vcsc-custom-pack-preloader").hide();
							jQuery(".preview-icon-code-box").hide();
							jQuery("#ts_vcsc_custom_pack_field").attr("disabled", "disabled");
							jQuery("#uninstall-pack-button").removeAttr("disabled").addClass("uninstallnow");
							jQuery("#dropDownDownload").attr("disabled", "disabled");
							jQuery(".ts-vcsc-custom-pack-buttons").after("<div class=error><p class=fontPackUploadedError>Hi there, something went wrong during your last font import. Please uninstall the current font package and try importing again (with a valid font package).</p></div>");
						});
					</script>';
				} else {
					TS_VCSC_ResetCustomFont();
					echo '<script>
						jQuery(document).ready(function() {
							jQuery(".ts-vcsc-custom-pack-preloader").hide();
							jQuery(".preview-icon-code-box").hide();
							jQuery("#uninstall-pack-button").attr("disabled", "disabled");
							jQuery("#ts_vcsc_custom_pack_field").removeAttr("disabled");
							jQuery("#dropDownDownload").attr("disabled", "disabled");
						});
					</script>';
				}
			}	
		}
		// Function that handles the ajax request of deleting files
		function TS_VCSC_DeleteCustomPack_Ajax() {
			$dest 					= wp_upload_dir();
			$dest_path 				= $dest['path'];	
			$this_year 				= date('Y');
			$this_month 			= date('m');
			$the_date_string 		= $this_year . '/' . $this_month.'/';
			$customFontPackPath 	= $dest_path . '/ts-vcsc-icons/custom-pack/';
			$newCustomFontPackPath 	= str_replace($the_date_string, '', $customFontPackPath);
			$fileName = 'ts-vcsc-custom-pack.zip';
			$deleteZip = TS_VCSC_RemoveDirectory($newCustomFontPackPath, false);
			TS_VCSC_RemoveDirectory($newCustomFontPackPath, false);
			TS_VCSC_ResetCustomFont();
			$this->TS_VCSC_tinymceCustomCount 	= 0;
			$this->TS_VCSC_Icons_Custom 		= array();
		}
	
		// Function to retrieve WooCommerce Version
		function TS_VCSC_WooCommerceVersion() {
			// If get_plugins() isn't available, require it
			if (!function_exists('get_plugins')) {
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}
			// Create the plugins folder and file variables
			$plugin_folder 	= get_plugins('/' . 'woocommerce');
			$plugin_file 	= 'woocommerce.php';
			// If the plugin version number is set, return it 
			if (isset($plugin_folder[$plugin_file]['Version'])) {
				return $plugin_folder[$plugin_file]['Version'];
			} else {
				return NULL;
			}
		}
	}
}
if (class_exists('VISUAL_COMPOSER_EXTENSIONS')) {
	$VISUAL_COMPOSER_EXTENSIONS = new VISUAL_COMPOSER_EXTENSIONS;
}


// Add Category Filters to Custom Post Types
// -----------------------------------------
if (!class_exists('TS_VCSC_Tax_CTP_Filter')){
    class TS_VCSC_Tax_CTP_Filter {
        /**
         * __construct 
         * @author Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @param array $cpt [description]
         */
        function __construct($cpt = array()){
            $this->cpt = $cpt;
            // Adding a Taxonomy Filter to Admin List for a Custom Post Type
            add_action( 'restrict_manage_posts', array($this, 'TS_VCSC_My_Restrict_Manage_Posts' ));
        }
        /**
         * TS_VCSC_My_Restrict_Manage_Posts  add the slelect dropdown per taxonomy
         * @author Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @return void
         */
        public function TS_VCSC_My_Restrict_Manage_Posts() {
            // only display these taxonomy filters on desired custom post_type listings
            global $typenow;
            $types = array_keys($this->cpt);
            if (in_array($typenow, $types)) {
                // create an array of taxonomy slugs you want to filter by - if you want to retrieve all taxonomies, could use get_taxonomies() to build the list
                $filters = $this->cpt[$typenow];
                foreach ($filters as $tax_slug) {
                    // retrieve the taxonomy object
                    $tax_obj = get_taxonomy($tax_slug);
                    $tax_name = $tax_obj->labels->name;
                    // output html for taxonomy dropdown filter
                    echo "<select name='".strtolower($tax_slug)."' id='".strtolower($tax_slug)."' class='postform'>";
                    echo "<option value=''>Show All $tax_name</option>";
                    $this->TS_VCSC_Generate_Taxonomy_Options($tax_slug,0,0,(isset($_GET[strtolower($tax_slug)])? $_GET[strtolower($tax_slug)] : null));
                    echo "</select>";
                }
            }
        }
        /**
         * TS_VCSC_Generate_Taxonomy_Options generate dropdown
         * @author Ohad Raz <admin@bainternet.info>
         * @since 0.1
         * @param  string  $tax_slug 
         * @param  string  $parent   
         * @param  integer $level    
         * @param  string  $selected 
         * @return void            
         */
        public function TS_VCSC_Generate_Taxonomy_Options($tax_slug, $parent = '', $level = 0,$selected = null) {
            $args = array('show_empty' => 1);
            if(!is_null($parent)) {
                $args = array('parent' => $parent);
            }
            $terms = get_terms($tax_slug,$args);
            $tab='';
            for($i=0;$i<$level;$i++){
                $tab.='--';
            }
            foreach ($terms as $term) {
                // output each select option line, check against the last $_GET to show the current option selected
                echo '<option value='. $term->slug, $selected == $term->slug ? ' selected="selected"' : '','>' .$tab. $term->name .' (' . $term->count .')</option>';
                $this->TS_VCSC_Generate_Taxonomy_Options($tax_slug, $term->term_id, $level+1,$selected);
            }
        }
    }
}


// Load Library to create Custom Metaboxes
// ---------------------------------------
if (!function_exists('TS_VCSC_CMBMetaBoxes')){
	function TS_VCSC_CMBMetaBoxes() {
		if (!class_exists('cmb_Meta_Box')) {
			require_once('custom-metabox/init.php');
		}
	}
}


// Functions to retrieve Video Thumbnails
// --------------------------------------
if (!function_exists('TS_VCSC_VideoImage_Youtube')){
	function TS_VCSC_VideoImage_Youtube($url){
		// Get image from video URL
		$urls 		= parse_url($url);
		$imgPath 	= '';
		if ((isset($urls['host'])) && ($urls['host'] == 'youtu.be')) {
			//Expect the URL to be http://youtu.be/abcd, where abcd is the video ID
			$imgPath = ltrim($urls['path'],'/');
		} else if ((isset($urls['path'])) && (strpos($urls['path'], 'embed') == 1)) {
			// Expect the URL to be http://www.youtube.com/embed/abcd
			$imgPath = end(explode('/', $urls['path']));
		} else if (strpos($url, '/') === false) { 
			//Expect the URL to be abcd only
			$imgPath = $url;
		} else {
			//Expect the URL to be http://www.youtube.com/watch?v=abcd
			parse_str($urls['query']);
			$imgPath = $v;
		}
		return "http://img.youtube.com/vi/" . $imgPath . "/0.jpg";
	}
}
if (!function_exists('TS_VCSC_VideoID_Youtube')){
	function TS_VCSC_VideoID_Youtube($url){
		// Get image from video URL
		$urls 		= parse_url($url);
		$imgPath 	= '';
		if ((isset($urls['host'])) && ($urls['host'] == 'youtu.be')) {
			//Expect the URL to be http://youtu.be/abcd, where abcd is the video ID
			$imgPath = ltrim($urls['path'],'/');
		} else if ((isset($urls['path'])) && (strpos($urls['path'], 'embed') == 1)) {
			// Expect the URL to be http://www.youtube.com/embed/abcd
			$imgPath = end(explode('/', $urls['path']));
		} else if (strpos($url, '/') === false) { 
			//Expect the URL to be abcd only
			$imgPath = $url;
		} else {
			//Expect the URL to be http://www.youtube.com/watch?v=abcd
			parse_str($urls['query']);
			$imgPath = $v;
		}
		return $imgPath;
	}
}
if (!function_exists('TS_VCSC_VideoImage_Vimeo')){
	function TS_VCSC_VideoImage_Vimeo($url){
		$image_url = parse_url($url);
		if ((isset($image_url['host'])) && ($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com')) {
			$hash = unserialize(TS_VCSC_retrieveExternalData("http://vimeo.com/api/v2/video/" . substr($image_url['path'], 1) . ".php"));
			if ($hash[0]["thumbnail_large"]) {
				return $hash[0]["thumbnail_large"];
			} else {
				return '';
			}
		} else {
			return '';
		}
	}
}
if (!function_exists('TS_VCSC_VideoID_Vimeo')){
	function TS_VCSC_VideoID_Vimeo($url){
		$image_url = parse_url($url);
		if ((isset($image_url['host'])) && ($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com')) {
			return substr($image_url['path'], 1);
		} else {
			return '';
		}
	}
}
if (!function_exists('TS_VCSC_VideoImage_Motion')){
	function TS_VCSC_VideoImage_Motion($url){
		$image_url 	= parse_url($url);
		if ((isset($image_url['host'])) && ($image_url['host'] == 'www.dailymotion.com' || $image_url['host'] == 'dailymotion.com')) {
			$url	= $image_url['path'];
			$parts 	= explode('/', $url);
			$parts 	= explode('_', $parts[2]);
			return "http://www.dailymotion.com/thumbnail/video/" . $parts[0];
		} else if ((isset($urls['host'])) && ($image_url['host'] == 'dai.ly')) {
			$imgPath = ltrim($image_url['path'],'/');
			return "http://www.dailymotion.com/thumbnail/video/" . $imgPath;
		} else {
			return '';
		}
	}
}
if (!function_exists('TS_VCSC_VideoID_Motion')){
	function TS_VCSC_VideoID_Motion($url){
		$image_url 	= parse_url($url);
		$imgPath 	= '';
		if ((isset($image_url['host'])) && ($image_url['host'] == 'www.dailymotion.com' || $image_url['host'] == 'dailymotion.com')) {
			$url	= $image_url['path'];
			$parts 	= explode('/', $url);
			$parts 	= explode('_', $parts[2]);
			return $parts[0];
		} else if ((isset($urls['host'])) && ($image_url['host'] == 'dai.ly')) {
			$imgPath = ltrim($image_url['path'],'/');
			return $imgPath;
		} else {
			return $imgPath;
		}
	}
}


// Get Item Information from Envato
// --------------------------------
if (!function_exists('TS_VCSC_ShowInformation')){
	function TS_VCSC_ShowInformation($item_id, $item_vc = true) {
		if ($item_vc == true) {
			$item_id = '7190695';
		}
		$item = TS_VCSC_GetItemInfo($item_id);
		if ($item === false) {
			return '<p style="text-align: justify;">Oops... Something went wrong. Could not retrieve item information from Envato.</p>';
		}
		$item = $item['item'];
		extract($item);
		$ts_vcsc_extend_envatoItem_Name     = $item;
		$ts_vcsc_extend_envatoItem_User		= $user;
		$ts_vcsc_extend_envatoItem_Rating	= $rating;
		$ts_vcsc_extend_envatoItem_Sales	= $sales;
		$ts_vcsc_extend_envatoItem_Price	= $cost;
		$ts_vcsc_extend_envatoItem_Thumb	= $thumbnail;
		$ts_vcsc_extend_envatoItem_Image	= $live_preview_url;
		$ts_vcsc_extend_envatoItem_Link		= $url;
		$ts_vcsc_extend_envatoItem_Release	= $uploaded_on;
		$ts_vcsc_extend_envatoItem_Update	= $last_update;
		$ts_vcsc_extend_envatoItem_HTML 	= '';
		$ts_vcsc_extend_envatoItem_HTML .= '
		<div class="ts_vcsc_envato_item">
			<div class="ts_vcsc_title">'.$ts_vcsc_extend_envatoItem_Name.'</div>
			<div class="ts_vcsc_wrap">
				<div class="ts_vcsc_top">
					<div class="ts_vcsc_rating"><span class="ts_vcsc_desc">Rating</span>' . TS_VCSC_GetEnvatoStars($ts_vcsc_extend_envatoItem_Rating) . '</div>
				</div>
				<div class="ts_vcsc_middle">
					<div class="ts_vcsc_sales">
						<span class="ts_vcsc_img_sales"></span>
						<div class="ts_vcsc_text">
							<span class="ts_vcsc_num">'.$ts_vcsc_extend_envatoItem_Sales.'</span>
							<span class="ts_vcsc_desc">Sales</span>
						</div>
					</div>
					<div class="ts_vcsc_thumb">
						<img src="'.$ts_vcsc_extend_envatoItem_Thumb.'" alt="'.$ts_vcsc_extend_envatoItem_Name.'" width="80" height="80"/>
					</div>
					<div class="ts_vcsc_price">
						<span class="ts_vcsc_img_price"></span>
						<div class="ts_vcsc_text">
							<span class="ts_vcsc_num"><span>$</span>'.round($ts_vcsc_extend_envatoItem_Price).'</span>
							<span class="ts_vcsc_desc">only</span>
						</div>
					</div>
				</div>
				<div class="ts_vcsc_bottom">
					<a href="'.$ts_vcsc_extend_envatoItem_Link.'" target="_blank"></a>
				</div>
			</div>
		</div>';
		if ($item_vc == true) {
			update_option('ts_vcsc_extend_settings_envatoInfo', 	$ts_vcsc_extend_envatoItem_HTML);
			update_option('ts_vcsc_extend_settings_envatoLink', 	$ts_vcsc_extend_envatoItem_Link);
			update_option('ts_vcsc_extend_settings_envatoPrice', 	$ts_vcsc_extend_envatoItem_Price);
			update_option('ts_vcsc_extend_settings_envatoRating', 	TS_VCSC_GetEnvatoStars($ts_vcsc_extend_envatoItem_Rating));
			update_option('ts_vcsc_extend_settings_envatoSales', 	$ts_vcsc_extend_envatoItem_Sales);
		} else {
			echo $ts_vcsc_extend_envatoItem_HTML;
		}
	}
}
if (!function_exists('TS_VCSC_GetItemInfo')){
	function TS_VCSC_GetItemInfo($item_id) {
		/* Data cache timeout in seconds - It sends a new request each hour instead of each page refresh */
		$CACHE_EXPIRATION = 3600;
		/* Set the transient ID for caching */
		$transient_id = 'TS_VCSC_Extend_Envato_Item_Data';
		/* Get the cached data */
		$cached_item = get_transient($transient_id);
		/* Check if the function has to send a new API request */
		if (!$cached_item || ($cached_item->item_id != $item_id)) {
			/* Set the API URL, %s will be replaced with the item ID  */
			$api_url = "http://marketplace.envato.com/api/edge/item:%s.json"; 
			/* Fetch data using the WordPress function wp_remote_get() */
			if ((function_exists('wp_remote_get')) && (strlen($item_id) != 0)) {
				$response = wp_remote_get(sprintf($api_url, $item_id));
			} else if ((function_exists('wp_remote_post')) && (strlen($item_id) != 0)) {
				$response = wp_remote_post(sprintf($api_url, $item_id));
			}
			/* Check for errors, if there are some errors return false */
			if (is_wp_error($response) or (wp_remote_retrieve_response_code($response) != 200)) {
				return false;
			}
			/* Transform the JSON string into a PHP array */
			$item_data = json_decode(wp_remote_retrieve_body($response), true);
			/* Check for incorrect data */
			if (!is_array($item_data)) {
				return false;
			}
			/* Prepare data for caching */
			$data_to_cache = new stdClass();
			$data_to_cache->item_id 		= $item_id;
			$data_to_cache->item_info 		= $item_data;
			/* Set the transient - cache item data*/
			set_transient($transient_id, $data_to_cache, $CACHE_EXPIRATION);
			/* Return item info array */
			return $item_data;
		}
		/* If the item is already cached return the cached info */
		return $cached_item->item_info;
	}
}
if (!function_exists('TS_VCSC_GetEnvatoStars')){
	function TS_VCSC_GetEnvatoStars($rating) {
		if ((int) $rating == 0) {
			return '<div class="ts_vcsc_not_rating">Not rated yet.</div>';
		}
		$return = '<ul class="ts_vcsc_stars">';
		$i=1;
		while ((--$rating) >= 0) {
			$return .= '<li class="ts_vcsc_full_star"></li>';
			$i++;
		}
		if ($rating == -0.5) {
			$return .= '<li class="ts_vcsc_full_star"></li>';
			$i++;
		}
		while ($i <= 5) {
			$return .= '<li class="ts_vcsc_empty_star"></li>';
			$i++;
		}
		$return .= '</ul>';
		return $return;
	}
}


// Other Utilized Functions
// ------------------------
if (!function_exists('TS_VCSC_CleanNumberData')){
	function TS_VCSC_CleanNumberData($a) {
		if(is_numeric($a)) {
			$a = preg_replace('/[^0-9,]/s', '', $a);
		}
		return $a;
	}
}
if (!function_exists('TS_VCSC_IsBase64Encoded')){
	function TS_VCSC_IsBase64Encoded($s){
		// Check if there are valid base64 characters
		if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s)) return false;
		// Decode the string in strict mode and check the results
		$decoded = base64_decode($s, true);
		if (false === $decoded) return false;
		// Encode the string again
		if (base64_encode($decoded) != $s) return false;
		return true;
	}
}
if (!function_exists('TS_VCSC_FormatSizeUnits')){
	function TS_VCSC_FormatSizeUnits($bytes) {
		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} elseif ($bytes > 1) {
			$bytes = $bytes . ' Bytes';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' Byte';
		} else {
			$bytes = '0 Bytes';
		}
		return $bytes;
	}
}
if (!function_exists('TS_VCSC_TruncateHTML')){
	/**
	* Truncates text.
	*
	* Cuts a string to the length of $length and replaces the last characters
	* with the ending if the text is longer than length.
	*
	* @param string  $text String to truncate.
	* @param integer $length Length of returned string, including ellipsis.
	* @param string  $ending Ending to be appended to the trimmed string.
	* @param boolean $exact If false, $text will not be cut mid-word
	* @param boolean $considerHtml If true, HTML tags would be handled correctly
	* @return string Trimmed string.
	*/
	function TS_VCSC_TruncateHTML($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false) {
		if ($considerHtml) {
			// if the plain text is shorter than the maximum length, return the whole text
			if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
				return $text;
			}
			// splits all html-tags to scanable lines
			preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
			$total_length 	= 0;
			$open_tags 		= array();
			$truncate 		= '';
			foreach ($lines as $line_matchings) {
				// if there is any html-tag in this line, handle it and add it (uncounted) to the output
				if (!empty($line_matchings[1])) {
					// if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
					if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
						// do nothing
					// if tag is a closing tag (f.e. </b>)
					} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
						// delete tag from $open_tags list
						$pos = array_search($tag_matchings[1], $open_tags);
						if ($pos !== false) {
							unset($open_tags[$pos]);
						}
					// if tag is an opening tag (f.e. <b>)
					} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
						// add tag to the beginning of $open_tags list
						array_unshift($open_tags, strtolower($tag_matchings[1]));
					}
					// add html-tag to $truncate'd text
					$truncate .= $line_matchings[1];
				}
				// calculate the length of the plain text part of the line; handle entities as one character
				$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
				if (($total_length + $content_length) > $length) {
					// the number of characters which are left
					$left 				= $length - $total_length;
					$entities_length 	= 0;
					// search for html entities
					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
						// calculate the real length of all entities in the legal range
						foreach ($entities[0] as $entity) {
							if ($entity[1] + 1 - $entities_length <= $left) {
								$left--;
								$entities_length += strlen($entity[0]);
							} else {
								// no more characters left
								break;
							}
						}
					}
					$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
					// maximum lenght is reached, so get off the loop
					break;
				} else {
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}
				// if the maximum length is reached, get off the loop
				if ($total_length >= $length) {
					break;
				}
			}
		} else {
			if (strlen($text) <= $length) {
				return $text;
			} else {
				$truncate = substr($text, 0, $length);
			}
		}
		// if the words shouldn't be cut in the middle...
		if (!$exact) {
			// ...search the last occurance of a space...
			$spacepos = strrpos($truncate, ' ');
			if (isset($spacepos)) {
				// ...and cut the text in this position
				$truncate = substr($truncate, 0, $spacepos);
			}
		}
		// add the defined ending to the text
		$truncate .= ' ' . $ending;
		if ($considerHtml) {
			// close all unclosed html-tags
			foreach ($open_tags as $tag) {
				$truncate .= '</' . $tag . '>';
			}
		}
		return $truncate;
	}
}
if (!function_exists('TS_VCSC_CurrentPageURL')){
	function TS_VCSC_CurrentPageURL() {
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
}
if (!function_exists('TS_VCSC_CurrentPageName')){
	function TS_VCSC_CurrentPageName() {
		return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}
}
if (!function_exists('TS_VCSC_CustomFontImportMessages')){
	function TS_VCSC_CustomFontImportMessages($type, $message) {
		echo '<script>
			jQuery(document).ready(function() {
				jQuery("#ts_vcsc_icons_upload_custom_pack_form").trigger("reset");
				MessiTitle		= "Visual Composer Extensions";
				MessiContent 	= "' . $message . '";
				MessiCode 		= "anim ' . $type . '";
				new Messi(MessiContent, {
					title:                      MessiTitle,
					titleClass:                 MessiCode,
					modal: 		                true,
					modalOpacity:               0.70,
					viewport:                   {top: "50%", left: "50%"},
					buttons:                    [{id: 0, label: "Close", val: "X"}],
					callback: function(val) {
						jQuery("#ts_vcsc_custom_pack_field").val("");
						//location.reload();
						window.location.href = window.location.href;
					},
					onclose: function(val) {
						jQuery("#ts_vcsc_custom_pack_field").val("");
						//location.reload();
						window.location.href = window.location.href;
					}
				});
			});
		</script>';
	}
}
if (!function_exists('TS_VCSC_ResetCustomFont')){
	function TS_VCSC_ResetCustomFont() {
		update_option('ts_vcsc_extend_settings_tinymceCustom', 			0);
		update_option('ts_vcsc_extend_settings_tinymceCustomJSON', 		'');
		update_option('ts_vcsc_extend_settings_tinymceCustomPath', 		'');
		update_option('ts_vcsc_extend_settings_tinymceCustomArray', 	'');
		update_option('ts_vcsc_extend_settings_tinymceCustomName', 		'Custom User Font');
		update_option('ts_vcsc_extend_settings_tinymceCustomAuthor', 	'Custom User');
		update_option('ts_vcsc_extend_settings_tinymceCustomCount', 	0);
		update_option('ts_vcsc_extend_settings_tinymceCustomDate',		'');
	}
}
if (!function_exists('TS_VCSC_IsEditPagePost')){
	function TS_VCSC_IsEditPagePost($new_edit = null){
		global $pagenow, $typenow;
		if (function_exists('vc_is_inline')){
			$vc_is_inline = vc_is_inline();
			if ((!vc_is_inline()) && (!is_admin())) return false;
		} else {
			$vc_is_inline = false;
			if (!is_admin()) return false;
		}
		if ($new_edit == "edit") {
			return in_array($pagenow, array('post.php'));
		} else if ($new_edit == "new") {
			return in_array($pagenow, array('post-new.php'));
		} else if ($vc_is_inline == true) {
			return true;
		} else {
			return in_array($pagenow, array('post.php', 'post-new.php'));
		}
	}
}
if (!function_exists('TS_VCSC_GetPostOptions')){
	function TS_VCSC_GetPostOptions($query_args) {
		$args = wp_parse_args( $query_args, array(
			'post_type' 		=> 'post',
			'posts_per_page'	=> -1,
			'orderby' 			=> 'title',
			'order' 			=> 'ASC',
		) );
		$posts = get_posts( $args );
		$post_options = array();
		if ($posts) {
			foreach ($posts as $post) {
				$post_options[] = array(
					'name' 		=> $post->post_title,
					'value' 	=> $post->ID
				);
			}
		}
		//TS_VCSC_SortMultiArray($post_options, 'name');
		return $post_options;
	}
}
if (!function_exists('TS_VCSC_GetTheCategoryByTax')){
	function TS_VCSC_GetTheCategoryByTax($id = false, $tcat = 'category') {
		$categories = get_the_terms($id, $tcat);
		if ((!$categories) || is_wp_error($categories)) {
			$categories = array();
		}
		$categories = array_values($categories);
		foreach (array_keys($categories) as $key) {
			_make_cat_compat($categories[$key]);
		}
		return apply_filters('get_the_categories', $categories);
	}
}
if (!function_exists('TS_VCSC_GetPluginVersion')){
	function TS_VCSC_GetPluginVersion() {
		$plugin_data 		= get_plugin_data( __FILE__ );
		$plugin_version 	= $plugin_data['Version'];
		return $plugin_version;
	}
}
if (!function_exists('TS_VCSC_VersionCompare')){
	function TS_VCSC_VersionCompare($a, $b) {
		//Compare two sets of versions, where major/minor/etc. releases are separated by dots. 
		//Returns 0 if both are equal, 1 if A > B, and -1 if B < A. 
		$a = explode(".", rtrim($a, ".0")); //Split version into pieces and remove trailing .0 
		$b = explode(".", rtrim($b, ".0")); //Split version into pieces and remove trailing .0 
		//Iterate over each piece of A 
		foreach ($a as $depth => $aVal) {
			if (isset($b[$depth])) {
			//If B matches A to this depth, compare the values 
				if ($aVal > $b[$depth]) {
			return 1; //Return A > B
			//break;
			} else if ($aVal < $b[$depth]) {
			return -1; //Return B > A
			//break;
			}
			//An equal result is inconclusive at this point 
			} else  {
			//If B does not match A to this depth, then A comes after B in sort order 
				return 1; //so return A > B
			//break;
			} 
		} 
		//At this point, we know that to the depth that A and B extend to, they are equivalent. 
		//Either the loop ended because A is shorter than B, or both are equal. 
		return (count($a) < count($b)) ? -1 : 0; 
	}
}
if (!function_exists('TS_VCSC_PluginIsActive')){
	function TS_VCSC_PluginIsActive($plugin_path) {
		$return_var = in_array($plugin_path, apply_filters('active_plugins', get_option('active_plugins')));
		return $return_var;
	}
}
if (!function_exists('TS_VCSC_CheckShortcode')){
	function TS_VCSC_CheckShortcode($shortcode = '') {
		$post_to_check = get_post(get_the_ID());
		// false because we have to search through the post content first
		$found = false;
		// if no short code was provided, return false
		if (!$shortcode) {
			return $found;
		}
		// check the post content for the short code
		if (stripos($post_to_check->post_content, '[' . $shortcode) !== false) {
			// we have found the short code
			$found = true;
		}
		// return our final results
		return $found;
	}
}
if (!function_exists('TS_VCSC_CheckString')){
	function TS_VCSC_CheckString($string = '') {
		$post_to_check = get_post(get_the_ID());
		// false because we have to search through the post content first
		$found = false;
		// if no string was provided, return false
		if (!$string) {
			return $found;
		}
		// check the post content for the short code
		if (stripos($post_to_check->post_content, '' . $string) !== false) {
			// we have found the string
			$found = true;
		}
		// return our final results
		return $found;
	}
}
if (!function_exists('TS_VCSC_GetExtraClass')){
	function TS_VCSC_GetExtraClass($el_class) {
		$output = '';
		if ( $el_class != '' ) {
			$output = " " . str_replace(".", "", $el_class);
		}
		return $output;
	}
}
if (!function_exists('TS_VCSC_endBlockComment')){
	function TS_VCSC_endBlockComment($string) {
		return (!empty($_GET['wpb_debug']) && $_GET['wpb_debug']=='true' ? '<!-- END '.$string.' -->' : '');
	}
}
if (!function_exists('TS_VCSC_GetCSSAnimation')){
	function TS_VCSC_GetCSSAnimation($css_animation) {
		$output = '';
		if ((get_option('ts_vcsc_extend_settings_loadHeader', 0) == 0)) {
			$FOOTER = true;
		} else {
			$FOOTER = false;
		}
		if ($css_animation != '') {
			if (get_option('ts_vcsc_extend_settings_loadWaypoints', 1) == 1) {
				if (wp_script_is('waypoints', $list = 'registered')) {
					wp_enqueue_script('waypoints');
				} else {
					wp_enqueue_script('ts-extend-waypoints',					TS_VCSC_GetResourceURL('js/jquery.vcsc.waypoints.min.js'), array('jquery'), false, $FOOTER);
				}
			}
			$output = ' wpb_animate_when_almost_visible wpb_'.$css_animation;
		}
		return $output;
	}
}
if (!function_exists('TS_VCSC_GetFontFamily')){
	function TS_VCSC_GetFontFamily($id, $font_family, $font_type) {
		$url            = plugin_dir_url( __FILE__ );
		$output         = '';
		if ($font_type == 'google') {
			if (!function_exists("my_strstr")) {
				function my_strstr( $haystack, $needle, $before_needle = false ) {
					if ( !$before_needle ) return strstr( $haystack, $needle );
					else return substr( $haystack, 0, strpos( $haystack, $needle ) );
				}
			}
			wp_enqueue_style($font_family, 'http://fonts.googleapis.com/css?family=' .$font_family , null, false, 'all');
			$format_name = strpos($font_family, ':');
			if ($format_name !== false) {
				$google_font =  my_strstr(str_replace( '+', ' ', $font_family), ':', true);
			} else {
				$google_font = str_replace('+', ' ', $font_family);
			}
			//$output .= '<style>#' . $id . ' .ts-icon-title-text {font-family: "' . $google_font . '" !important;}</style>';
			$output .= '<style>#' . $id . ' {font-family: "' . $google_font . '" !important;}</style>';
		} else if ($font_type == 'fontface') {
				$stylesheet = $url . 'assets/fontface/fontface_stylesheet.css';
				$font_dir = FONTFACE_URI;
				if (file_exists( $stylesheet)) {
					$file_content = file_get_contents($stylesheet);
					if (preg_match("/@font-face\s*{[^}]*?font-family\s*:\s*('|\")$font_family\\1.*?}/is", $file_content, $match)) {
						$fontface_style = preg_replace("/url\s*\(\s*['|\"]\s*/is", "\\0$font_dir/", $match[0])."\n";
					}
					$output = "\n<style>" . $fontface_style ."\n";
					$output .= '#' . $id . ' {font-family: "'.$font_family.'" !important;}</style>';
				}
			} else if ($font_type == 'safefont') {
				$output .= '<style>#'.$id.' {font-family: '.$font_family.' !important;}</style>';
			}
		return $output;
	}
}
if (!function_exists('TS_VCSC_GetResourceURL')){
	function TS_VCSC_GetResourceURL($relativePath){
		return plugins_url($relativePath, plugin_basename(__FILE__));
	}
}
if (!function_exists('TS_VCSC_DeleteOptionsPrefixed')){
	function TS_VCSC_DeleteOptionsPrefixed($prefix) {
		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'" );
	}
}
if (!function_exists('TS_VCSC_SortMultiArray')){
	function TS_VCSC_SortMultiArray(&$array, $key) {
		foreach($array as &$value) {
			$value['__________'] = $value[$key];
		}
		/* Note, if your functions are inside of a class, use: 
			usort($array, array("My_Class", 'TS_VCSC_SortByDummyKey'));
		*/
		usort($array, 'TS_VCSC_SortByDummyKey');
		foreach($array as &$value) {   // removes the dummy key from your array
			unset($value['__________']);
		}
		return $array;
	}
}
if (!function_exists('TS_VCSC_SortByDummyKey')){
	function TS_VCSC_SortByDummyKey($a, $b) {
		if($a['__________'] == $b['__________']) return 0;
		if($a['__________'] < $b['__________']) return -1;
		return 1;
	}
}
if (!function_exists('TS_VCSC_CaseInsensitiveSort')){
	function TS_VCSC_CaseInsensitiveSort($a,$b) { 
		return strtolower($b) < strtolower($a); 
	}
}
if (!function_exists('TS_VCSC_getRemoteFile')){
	function TS_VCSC_getRemoteFile($url) {
		// get the host name and url path
		$parsedUrl = parse_url($url);
		$host = $parsedUrl['host'];
		if (isset($parsedUrl['path'])) {
			$path = $parsedUrl['path'];
		} else {
			// the url is pointing to the host like http://www.mysite.com
			$path = '/';
		}
		if (isset($parsedUrl['query'])) {
			$path .= '?' . $parsedUrl['query'];
		}
		if (isset($parsedUrl['port'])) {
			$port = $parsedUrl['port'];
		} else {
			// most sites use port 80
			$port = '80';
		}
		$timeout = 10;
		$response = '';
		// connect to the remote server
		$fp = @fsockopen($host, '80', $errno, $errstr, $timeout );
		if( !$fp ) {
			echo "Cannot retrieve $url";
		} else {
			// send the necessary headers to get the file
			fputs($fp, "GET $path HTTP/1.0\r\n" .
			"Host: $host\r\n" .
			"User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n" .
			"Accept: */*\r\n" .
			"Accept-Language: en-us,en;q=0.5\r\n" .
			"Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" .
			"Keep-Alive: 300\r\n" .
			"Connection: keep-alive\r\n" .
			"Referer: http://$host\r\n\r\n");
			// retrieve the response from the remote server
			while ( $line = fread( $fp, 4096 ) ) {
				$response .= $line;
			}
			fclose( $fp );
			// strip the headers
			$pos = strpos($response, "\r\n\r\n");
			$response = substr($response, $pos + 4);
		}
		// return the file content
		return $response;
	}
}
if (!function_exists('TS_VCSC_retrieveExternalData')){
	function TS_VCSC_retrieveExternalData($url){
		if (ini_get('allow_url_fopen') == '1') {
			//echo 'Using file_get_contents';
			$content = file_get_contents($url);
			if ($content !== false) {}
		} else if (function_exists('curl_init')) {
			//echo 'Using CURL';
			// initialize a new curl resource
			$ch = curl_init();
			$timeout = 5;
			// set the url to fetch
			curl_setopt($ch, CURLOPT_URL, $url);
			// don't give me the headers just the content
			curl_setopt($ch, CURLOPT_HEADER, 0);
			// return the value instead of printing the response to browser
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// set error timeout
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			// use a user agent to mimic a browser
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
			$content = curl_exec($ch);
			// remember to always close the session and free all resources
			curl_close($ch);
		} else {
			//echo 'Using Others';
			$content = TS_VCSC_getRemoteFile($url);
		}
		return $content;
	}
}
if (!function_exists('TS_VCSC_cURLcheckBasicFunctions')){
	function TS_VCSC_cURLcheckBasicFunctions() {
		if( !function_exists("curl_init") &&
			!function_exists("curl_setopt") &&
			!function_exists("curl_exec") &&
			!function_exists("curl_close") ) return false;
		else return true;
	}
}
if (!function_exists('TS_VCSC_checkValidURL')){
	function TS_VCSC_checkValidURL($url) {
		if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
			return true;
		} else {
			return false;
		}
	}
}
if (!function_exists('TS_VCSC_makeValidURL')){
	function TS_VCSC_makeValidURL($url) {
		if (preg_match("~^(?:f|ht)tps?://~i", $url)) {
			return $url;
		} else {
			return 'http://' . $url;
		}
	}
}
if (!function_exists('TS_VCSC_numberOfDecimals')){
	function TS_VCSC_numberOfDecimals($value) {
		if ((int)$value == $value) {
			return 0;
		} else if (!is_numeric($value)) {
			// throw new Exception('numberOfDecimals: ' . $value . ' is not a number!');
			return false;
		}
		return strlen($value) - strrpos($value, '.') - 1;
	}
}
if (!function_exists('TS_VCSC_RemoveDirectory')){
	function TS_VCSC_RemoveDirectory($directory, $empty = false) { 
		if (substr($directory, -1) == "/") { 
			$directory = substr($directory, 0, -1); 
		} 
		if (!file_exists($directory) || !is_dir($directory)) { 
			return false;
		} elseif (!is_readable($directory)) { 
			return false; 
		} else { 
			$directoryHandle = opendir($directory); 
			while ($contents = readdir($directoryHandle)) { 
				if ($contents != '.' && $contents != '..') { 
					$path = $directory . "/" . $contents;
					if (is_dir($path)) { 
						TS_VCSC_RemoveDirectory($path); 
					} else { 
						unlink($path); 
					} 
				} 
			} 
			closedir($directoryHandle); 
			if ($empty == false) { 
				if (!rmdir($directory)) { 
					return false; 
				} 
			}
			return true; 
		} 
	}
}
?>