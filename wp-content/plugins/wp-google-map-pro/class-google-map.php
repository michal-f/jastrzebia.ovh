<?php
class Wpgmp_Google_Map {
	
var $code								="";
var $instance							=1;
var $groupID 							='groupmap';
var $center_lat 						='37.09024';
var $center_lng 						='-95.712891';
var $set_nearest_location				="";
var $center_address 					="";
var $divID								='map';
var $marker								=array();
var $width								="";
var $height								="";
var $zoom								=14;
var $title 								='WP Google Map Pro';
var $polygon 							=array();
var $polyline 							=array();
var $routedirections 					=array();
var $map_draw_polygon 					="";
var $kml_layer							="";
var $kml_layers_links					="";
var $fusion_select						="";
var $fusion_from						="";
var $heat_map							="";
var $temperature_unit					="";
var $wind_speed_unit					="";
var $map_width 							="";
var $map_height 						="";
var $map_start_point					="";
var $map_end_point						="";
var $map_multiple_point					="";
var $map_scrolling_wheel				="true";
var $map_draggable						="true";
var $map_pan_control					="true";
var $map_zoom_control					="true";
var $map_type_control					="true"; 
var $map_scale_control					="true";
var $map_street_view_control			="true";
var $map_overview_control				="true";
var $map_enable_info_window_setting 	="";
var $map_info_window_width				="";
var $map_info_window_height				="";
var $map_info_window_shadow_style		="";
var $map_info_window_border_radius		="";
var $map_info_window_border_width		=""; 
var $map_info_window_border_color		="";
var $map_info_window_background_color	="";
var $map_info_window_arrow_size			="";
var $map_info_window_arrow_position		="";
var $map_info_window_arrow_style		="";
var $map_style_google_map				="";
var $map_language						="en";
var $polygon_border_color				="#f22800";
var $polygon_background_color			="#f22800";
var $map_draw_polyline					="";
var $map_polyline_stroke_color			="";
var $map_polyline_stroke_opacity		="";
var $map_polyline_stroke_weight			="";
var $map_type							="ROADMAP";
var $map_45								="";
var $marker_cluster						="";
var $grid								="";
var $max_zoom							="14";
var $style								="default";
var $map_overlay 						="";
var $map_overlay_border_color			="#F22800";
var $map_overlay_width					="200";
var $map_overlay_height					="200";
var $map_overlay_fontsize				="16";
var $map_overlay_border_width			="200";
var $map_overlay_border_style			="";
var $polygontriangle					="polygontriangle"; 
var $visualrefresh 						="false";
var $directionsDisplay 					="directionsDisplay";
var $directionsService 					="directionsService";
var $route_direction 					="";
var $wpgmp_multiple_routes				="";
var $route_start_point 					="";
var $route_end_point 					="";
var $map_way_point 						="";
var $route_direction_stroke_color 		="";
var $route_direction_stroke_opacity 	="";
var $route_direction_stroke_weight 		="";
var $street_control 					="";
var $street_view_close_button 			="";
var $links_control 						="";
var $street_view_pan_control 			="";
var $enable_group_map 					="";
var $group_data 						="";
var $groups_markers 					=array();
var $infowindow 						= "infowindow";
var $bountpanning						="bountpanning";
var $map_panning_true;
var $map_panning_from_latitude; 
var $map_panning_from_longitude;
var $map_panning_to_latitude;
var $map_panning_to_longitude;
var $map_panning_zoom_level;
var $map_drawing;
var $map_shapes;
var $editable							=false;
var $display_marker_category			=false;
var $tabs_font_color					='ffffff';
var $tabs_font_size						='15';
var $category_font_color				='632E9B';
var $category_font_size					='15';
var $location_font_color				='632E9B';
var $location_font_size					='14';
var $map_controls 						='';
var $map_id 							='';
var $map_infowindow_open 				='';
var $route_polyline						='route_polyline';
var $renderer_options					='renderer_options';
var $filters                            ='';
var $filter_options						='';		
function __construct()
{
	global $wpgmp_containers;
	$this->divID="map".(count($wpgmp_containers)+1);
	$wpgmp_containers[]=$this->divID;
}

private function start()
{
	if( $this->center_address )
	{ 
		$output = $this->getData($this->center_address);	
		if( $output->status == 'OK' )
		{
			$this->center_lat = $output->results[0]->geometry->location->lat;
			$this->center_lng = $output->results[0]->geometry->location->lng;
		}
	}
	if( $this->map_width!='' && $this->map_height!='' )
	{
		  $width = $this->map_width."px";
		  $height = $this->map_height."px";
	}
	elseif( $this->map_width=='' && $this->map_height!='' )
	{
		  $width = "100%";
		  $height = $this->map_height."px";
	}
	elseif( $this->map_width=='' && $this->map_height=='' )
	{
		  $width = "100%";
		  $height = "300px";
	}
	else
	{
		  $width =  $this->map_width."px";
		  $height = "300px";
	}

if( !empty($this->display_type) && $this->display_type=='horizontal')
{
	if(empty($this->container_width))
	{
		$main_container_width = '100%';
	}
	else
	{
		$main_container_width = $this->container_width.'px';
	}
}

if( !empty($this->display_type) && $this->display_type=='vertical')
{
	if(empty($this->container_width))
	{
		$main_container_width = '175px';
	}
	else
	{
		$main_container_width = $this->container_width.'px';
	}
}

$this->code='
<style>
#'.$this->divID.' img {
max-width:none !important;
padding:0px !important;
}';

if( $this->display_marker_category=='true' )
{
	$this->code .='
	
	.wpgmp_category_tabs a
	{
		color:#'.$this->tabs_font_color.' !important;
	}

	.wpgmp_category_container .wpgmp_categoriese .wpgmp_cat_title
	{
		color:#'.$this->category_font_color.' !important;
		font-size:'.$this->category_font_size.'px !important;
	}
	.wpgmp_categoriese .wpgmp_location_container li.wpgmp_all_locations
	{
		color:#'.$this->location_font_color.' !important;
		font-size:'.$this->location_font_size.'px !important;
	}
	';
}

$this->code.="</style>";

$this->code.='<div id="map-selected"></div>';

if( !empty($this->display_marker_category) && $this->display_marker_category=='true' )
{
	$this->code.='<div class="wpgmp_category_main_container">';
}

	$main_map_div='<div id='.$this->divID.' style="width:'.$width.'; height:'.$height.';"></div>';
	
	if(isset($this->map_controls['display_listing']))
	{
	if( !empty($this->map_controls['display_listing']) && $this->map_controls['display_listing']==true)
	{
		$main_listing_div = '<div class="wpgmp_listing_container">'.do_shortcode("[put_wpgm_locations id=".$this->map_id."  filters='".$this->filters."' ]").'</div>';
    }
}

$final_ouput = $main_map_div.$main_listing_div;

$this->code.= apply_filters("wpgmp_map_output",$final_ouput,$main_map_div,$main_listing_div,$this->map_id);	
			
if( $this->display_marker_category=='true' )
{		
		$this->code.='<div class="wpgmp_category_container">
				<ul class="wpgmp_category_tabs clearfix">';
				
		if($this->map_controls['wpgmp_category_tab']==true)
		$this->code.='<li class="wpgmp-tab-1" rel="'.$this->map_id.'"><a href="javascript:void(0);">'.__("Categories",'wpgmp_google_map').'</a></li>';	
		
		if($this->map_controls['wpgmp_direction_tab']==true)
		$this->code.='<li class="wpgmp-tab-2" rel="'.$this->map_id.'"><a href="javascript:void(0);">'.__("Directions",'wpgmp_google_map').'</a></li>';	
		
		if($this->map_controls['wpgmp_nearby_tab']==true)
		$this->code.='<li class="wpgmp-tab-3" rel="'.$this->map_id.'"><a href="javascript:void(0);">'.__("Nearby",'wpgmp_google_map').'</a></li>';	
	
		if($this->map_controls['wpgmp_route_tab']==true)
		{
		$this->code.='<li class="wpgmp-tab-4" rel="'.$this->map_id.'"><a href="javascript:void(0);">'.__("Route Directions",'wpgmp_google_map').'</a></li>';	
	}
		
		
		$this->code.='</ul>';
				
				
		$this->code.='<div class="wpgmp_toggle_main_container">';
		
		$default_styles='';
		
		$default_styles['hide_direction_tab']="";
		$default_styles['hide_nearby_tab']="";
		$default_styles['hide_route_tab']="";
		
		if($this->map_controls['wpgmp_category_tab']==true)
		{
			$default_styles['hide_direction_tab']="display:none";
			$default_styles['hide_nearby_tab']="display:none";
			$default_styles['hide_route_tab']="display:none";
					$this->code.='<div id="wpgmp_cat_tab'.$this->map_id.'">';
					
					global $wpdb;
					
					for($i=0; $i< count($this->marker); $i++)
					{
						if($this->marker[$i]['cat_id'])
						$all_marker[$this->marker[$i]['cat_id']][] = $this->marker[$i];
					}
					
					if(is_array($all_marker))
					{
					foreach($all_marker as $key => $markers)
					{ 
						$results = array_unique($markers);
    
						$this->code.='
						<div class="wpgmp_categoriese">';
						
						foreach($results as $marker) 
						{
							$this->code.='<input type="checkbox" class="wpgmp_specific_category_location" value="'.$marker['cat_id'].'" id="hospital-chk">
							<a href="javascript:void(0);" class="wpgmp_cat_title accordion accordion-close">'.$marker['cat_title'].'<span class="arrow"><img src="'.$marker['icon'].'" /></span></a>';
						}
							
								$this->code.='
											  <div class="scroll-pane" style="height: 97px; width:100%;">
												  <ul class="wpgmp_location_container">';
												   
												   foreach($markers as $marker) 
												   {  
													 $this->code.='
														 <li class="wpgmp_all_locations" onclick=open_current_location(marker'.$marker["location_id"].$this->divID.');>
															<span>'.$marker['title'].'</span>
														 </li>';
												   }
													

								  $this->code.='			
												</ul>
											</div>
							</div>';
					}
				
				    }
					$this->code.='
					</div>';
		}			
					
				    if($this->map_controls['wpgmp_direction_tab']==true)
		{
				    
				    $default_styles['hide_nearby_tab']="display:none";
					$default_styles['hide_route_tab']="display:none";
				    $this->code.='<div id="wpgmp_dir_tab'.$this->map_id.'" style="'.$default_styles['hide_direction_tab'].'">
							<div class="wpgmp_direction_container">
								<form class="wpgmp-direction-form" action="" method="post" id="calculate-route">
								<input id="fromloc'.$this->map_id.'" placeholder="Start Point" type="text" class="input fromloc"><span class="wpgmp_mcurrent_loction" title="Take Current Location" style="margin-right: 0px; position: absolute;"></span>
								<input id="toloc'.$this->map_id.'" placeholder="End Point" type="text" class="input toloc">
								</form>
								<select id="tmode"> 
								  <option value="DRIVING" selected="selected">Driving</option> 
								  <option value="BICYCLING">Bicycling</option> 
								  <option value="WALKING">Walking</option> 
								  <option value="TRANSIT">Transit</option> 
								</select> 
								<input type="submit" value="Find Direction" class="wpgmp_find_dir_button" onclick="wpgmp_calculate_route('.$this->map_id.');">
							</div>
							<div id="directions-panel'.$this->map_id.'" style="overflow-y:scroll; height:200px; display:none;"></div>
					</div>';
					
		}				
		
		
		if($this->map_controls['wpgmp_nearby_tab']==true)
		{
		
		$default_styles['hide_route_tab']="display:none";
		$this->code.='
		
		<script>var markers'.$this->map_id.' = '.json_encode($this->marker).';</script>
		
		<div id="wpgmp_nearby_tab'.$this->map_id.'" style="'.$default_styles['hide_nearby_tab'].'">
							<div class="wpgmp_direction_container">
								<form class="wpgmp-nearby-form" action="" method="post" id="find-nearby">
								 <div><span>Current Location </span> :<span class="wpgmp_mcurrent_loction" title="Take Current Location" id="nearby_current_location" style="width:150px; margin:0px;" </span></div>
								 <div><span class="wpgmp_set_current_location"></span></div>
								<input id="wpgmp_radius'.$this->map_id.'" placeholder="Radius (miles)" type="text" class="input" value="25" size=8> Miles
								</form>
								<input type="submit" value="Find Locations" class="wpgmp_find_nearby_button" onclick="wpgmp_nearby_locations(markers'.$this->map_id.','.$this->map_id.',\''.$this->divID.'\');">
							</div>
							<div id="locations-panel'.$this->map_id.'" style="overflow-y:scroll; height:200px; display:none;"></div>
					</div>';
				} 	
				
				
		if($this->map_controls['wpgmp_route_tab']==true)
		{
			
			
					$this->code.='<div id="wpgmp_route_tab'.$this->map_id.'" style="'.$default_styles['hide_route_tab'].'">';
					
					global $wpdb;
					
					
					if(is_array($this->wpgmp_multiple_routes))
					{
						
					
					foreach($this->wpgmp_multiple_routes as $key => $route)
					{ 
	 
						$this->code.='
						<div class="wpgmp_categoriese">';
						
							$this->code.='
							<a href="javascript:void(0);" class="wpgmp_cat_title accordion accordion-close">'.$route['route_title'].'</a>';
							
								$this->code.='  ';
												   
												    $this->code.='
															<div id="directions-panel-route'.$key.'" style="overflow-y:scroll; height:200px;"></div>
														';
												   
													

								  $this->code.='			
												
							</div>';
					}
				
					}
					$this->code.='
					</div>';
				
		}
	
				
				$this->code.='	</div>
					<div class="wpgmp_toggle_container">Hide</div>
</div></div>';
}

$this->code.='
<script type="text/javascript">
google.load("maps", "3.7", {"other_params" : "sensor=false&libraries=places,weather,panoramio,drawing&language='.get_option('wpgmp_language').'"});
google.setOnLoadCallback(initialize);';

if( $this->display_marker_category=='true' )
{
	$this->code.='	
	 jQuery(document).ready(function($) {
		 
		jQuery(".scroll-pane").jScrollPane();
		jQuery(".wpgmp-tab-1").addClass("active");
		jQuery(".accordion").accordion({
            speed: "slow"
         });	 
		 
		 
	 var fron_loc = document.getElementById("fromloc'.$this->map_id.'");
		 var to_loc = document.getElementById("toloc'.$this->map_id.'");
        
        if(fron_loc || to_loc) {
			new google.maps.places.Autocomplete(fron_loc, { types: ["geocode"] });
			new google.maps.places.Autocomplete(to_loc, { types: ["geocode"] });	
		}
	 });
	';
}
	
	
	$this->code.='
	var groups = [];
	var allmarkers = [];';


if( !empty($this->visualrefresh) && $this->visualrefresh=='true' )
{
	$this->code.=' google.maps.visualRefresh = '.$this->visualrefresh.'; ';
}

if( $this->marker_cluster=='true' )
{
$this->code.='var styles = [[{
        url: "'.plugins_url('/images/people35.png', __FILE__ ).'",
        height: 35,
        width: 35,
        anchor: [16, 0],
        textColor: "#ff00ff",
        textSize: 10
      }, {
        url: "'.plugins_url('images/people45.png', __FILE__ ).'",
        height: 45,
        width: 45,
        anchor: [24, 0],
        textColor: "#ff0000",
        textSize: 11
      }, {
        url: "'.plugins_url('/images/people55.png', __FILE__ ).'",
        height: 55,
        width: 55,
        anchor: [32, 0],
        textColor: "#ffffff",
        textSize: 12
      }], [{
        url: "'.plugins_url('/images/conv30.png', __FILE__ ).'",
        height: 27,
        width: 30,
        anchor: [3, 0],
        textColor: "#ff00ff",
        textSize: 10
      }, {
        url: "'.plugins_url('/images/conv40.png', __FILE__ ).'",
        height: 36,
        width: 40,
        anchor: [6, 0],
        textColor: "#ff0000",
        textSize: 11
      }, {
        url: "'.plugins_url('/images/conv50.png', __FILE__ ).'",
        width: 50,
        height: 45,
        anchor: [8, 0],
        textSize: 12
      }], [{
        url: "'.plugins_url('/images/heart30.png', __FILE__ ).'",
        height: 26,
        width: 30,
        anchor: [4, 0],
        textColor: "#ff00ff",
        textSize: 10
      }, {
        url: "'.plugins_url('/images/heart40.png', __FILE__ ).'",
        height: 35,
        width: 40,
        anchor: [8, 0],
        textColor: "#ff0000",
        textSize: 11
      }, {
        url: "'.plugins_url('/images/heart50.png', __FILE__ ).'",
        width: 50,
        height: 44,
        anchor: [12, 0],
        textSize: 12
      }]];
	  var markerClusterer = null;
      var imageUrl = "http://chart.apis.google.com/chart?cht=mm&chs=24x32&chco=FFFFFF,008CFF,000000&ext=.png";
	  var markclus = [];';
}


$this->code.='
var wgmp_circles 	= [];
var wgmp_polygons 	= [];
var wgmp_polylines 	= [];
var wgmp_rectangles = [];
';


if( $this->route_direction == 'true' )
{

$this->code.='
function wpgmp_calculate_route_directions() {';

	$multiple_routes = $this->wpgmp_multiple_routes;

	foreach ($multiple_routes as $key => $multiple_route) 
	{
		$this->code.='
		'.$this->directionsService.$key.' = new google.maps.DirectionsService();
		'.$this->route_polyline.$key.' = {
									strokeColor: "#'.$multiple_route['stroke_color'].'",
									strokeOpacity: '.$multiple_route['stroke_opacity'].',
									strokeWeight: '.$multiple_route['stroke_weight'].'
		};
	
	    '.$this->renderer_options.$key.' = {
									draggable: '.$multiple_route['draggable'].',
									suppressMarkers: '.$multiple_route['custom_marker'].', 
									suppressInfoWindows: false, 
									preserveViewport: true, 
									polylineOptions: '.$this->route_polyline.$key.'
		};';
	
		$this->code.='
		var start'.$key.' = "'.$multiple_route['start_point'].'";
		var end'.$key.' = "'.$multiple_route['end_point'].'";';
		
		if( !empty($multiple_route['way_points']) )
		{
			$json_multiple_route = json_encode($multiple_route['way_points']);
		    			
			$this->code.='
			var waypts'.$key.' = [];
			way_points_array'.$key.' = '.$json_multiple_route.';
			
			for(var wp=0; wp<way_points_array'.$key.'.length; wp++){
				waypts'.$key.'.push({
					location:way_points_array'.$key.'[wp],
					stopover:true
				});
			}';
		}

		$this->code.='
		var request = {
			origin: start'.$key.',
			destination: end'.$key.',
		';
		
		if( !empty($multiple_route['way_points']) )
		{	
			$this->code.='
			waypoints: waypts'.$key.',
			optimizeWaypoints: '.$multiple_route['optimize_waypoints'].',
			';	
		}

		$this->code.='
		travelMode: google.maps.TravelMode.'.$multiple_route['travel_mode'].',
		unitSystem: google.maps.UnitSystem.'.$multiple_route['unit_system'].'
		};
			
		'.$this->directionsService.$key.'.route(request, function(response'.$key.', status) {
			if (status == google.maps.DirectionsStatus.OK)
			{
				'.$this->directionsDisplay.$key.' = new google.maps.DirectionsRenderer('.$this->renderer_options.$key.');
				'.$this->directionsDisplay.$key.'.setMap('.$this->divID.');
				'.$this->directionsDisplay.$key.'.setDirections(response'.$key.');
				'.$this->directionsDisplay.$key.'.setPanel(document.getElementById("directions-panel-route'.$key.'"));		
			}
	  	});';
	}
	
$this->code.='
}';
}

$this->code.='
function initialize() {
var infoWindow = null;';

if($this->map_infowindow_open!='true') {

$this->code .=''.$this->infowindow.' =  new google.maps.InfoWindow();';

}

if($this->set_nearest_location=="true")
{
	$this->code.='if (navigator.geolocation) {
	
		navigator.geolocation.getCurrentPosition(function(position) {
			
				var lat1 =  position.coords.latitude;
				var lon1 =  position.coords.longitude;
				var markers = '.json_encode($this->marker).';
				marker_distances= wpgmp_get_nearby_locations(markers,lat1,lon1,0,"'.$this->divID.'"); 
				new_marker=marker_distances[0].key;
				marker_object=eval(new_marker);
				'.$this->divID.'.setCenter(marker_object.getPosition());
						
		});
	}';
}	

if( $this->display_marker_category=='true' )
{
	
$this->directionsDisplay ="directionsDisplay".$this->map_id;
$this->directionsService ="directionsService".$this->map_id;

$this->code.='
	var rendererOptions = {
		draggable: true
	};
	'.$this->directionsService.' = new google.maps.DirectionsService();
	'.$this->directionsDisplay.' = new google.maps.DirectionsRenderer(rendererOptions);';
}

if(isset($this->map_style_google_map['mapfeaturetype']))
{
	$total_rows=count($this->map_style_google_map['mapfeaturetype']);
	for($i=0;$i<$total_rows;$i++)
	{
		if( empty($this->map_style_google_map['mapfeaturetype'][$i]) or empty($this->map_style_google_map['mapelementtype'][$i]) )
		continue;
		$map_stylers[]="{   featureType: '".$this->map_style_google_map['mapfeaturetype'][$i]."',  elementType: '".$this->map_style_google_map['mapelementtype'][$i]."',  stylers: [  { color: '#".$this->map_style_google_map['color'][$i]."' } ,{ visibility: '".$this->map_style_google_map['visibility'][$i]."' } ]  }";
	}
}

if( !empty($map_stylers) )
{	
	if( is_array($map_stylers) )
	{
		$map_styles="var map_styles = [ ".implode(',',$map_stylers)." ];  ";
	}
}

if( !empty($map_styles) )
{
	$this->code.=$map_styles;
}

$this->center_lat = apply_filters("wpgmp_center_latitude",$this->center_lat,$this->map_id);

$this->center_lng = apply_filters("wpgmp_center_longitude",$this->center_lng,$this->map_id);

$this->code.='var latlng = new google.maps.LatLng('.$this->center_lat.','.$this->center_lng.');';

if( $this->street_control!='true' )
{		
	$this->code.='var mapOptions = {';
	if( empty($this->map_45) )
	{
		$this->code.='zoom: '.$this->zoom.',';
	}
	else
	{
		$this->code.='zoom: 18,';	
	}
	
	$map_options = array(
	
	'scrollwheel'=>$this->map_scrolling_wheel,
	'panControl'=>$this->map_pan_control,
	'zoomControl'=>$this->map_zoom_control,
	'mapTypeControl'=>$this->map_type_control,
	'scaleControl'=>$this->map_scale_control,
	'streetViewControl'=>$this->map_street_view_control,
	'overviewMapControl'=>$this->map_overview_control,
	'overviewMapControlOptions' =>$this->map_overview_control,
	'MapTypeId' =>$this->map_type
	
	);
	
	$map_options = apply_filters("wpgmp_map_options",$map_options,$this->map_id);
		
	$this->code.='
	scrollwheel: '.$map_options['scrollwheel'].',
	panControl: '.$map_options['panControl'].',
	zoomControl: '.$map_options['zoomControl'].',
	mapTypeControl: '.$map_options['mapTypeControl'].',
	scaleControl: '.$map_options['scaleControl'].',
	streetViewControl: '.$map_options['streetViewControl'].',
	overviewMapControl: '.$map_options['overviewMapControl'].',
	overviewMapControlOptions: {
            opened: '.$map_options['overviewMapControlOptions'].'
    },
	center: latlng,';
		if($this->map_draggable=='false') {
		$this->code.='draggable: '.$this->map_draggable.',';
		} else {
		$this->code.='draggable: true,';	
		}
	$this->code.='mapTypeId: google.maps.MapTypeId.'.$map_options['MapTypeId'].'
	};
	'.$this->divID.' = new google.maps.Map(document.getElementById("'.$this->divID.'"), mapOptions);';
}
else
{		
	$this->code.='var panoOptions ={
	position: latlng,
	addressControlOptions: {
		position: google.maps.ControlPosition.BOTTOM_CENTER
	},
	linksControl: '.$this->links_control.',
	panControl: '.$this->street_view_pan_control.',
	zoomControlOptions: {
		style: google.maps.ZoomControlStyle.SMALL
	},
	enableCloseButton: '.$this->street_view_close_button.'
	};
	var panorama = new google.maps.StreetViewPanorama(document.getElementById("'.$this->divID.'"), panoOptions);';
}

if( $this->route_direction == 'true' )
{
	$this->code.="
	wpgmp_calculate_route_directions();
	";
}	

$this->code.="
google.maps.event.addDomListener(window, 'resize', function() {
    google.maps.event.trigger(".$this->divID.", 'resize');
    ".$this->divID.".setCenter(latlng);
});
";
		 		
if( !empty($this->map_45) )
{
	$this->code.=''.$this->divID.'.setTilt('.$this->map_45.');';
}

if( !empty($map_styles) )
{
	$this->code.=''.$this->divID.'.setOptions({styles: map_styles});';
}

if( $this->kml_layer=="KmlLayer" )
{
	if (!is_array($this->kml_layers_links))
	{
		$this->kml_layers_links = array($this->kml_layers_links);
	}
	
	if (count($this->kml_layers_links))
	{
		$i = 0;
		foreach ($this->kml_layers_links as $kmlLayerURL)
		{
			$this->code.='
			var kmlLayerOptions = {
			map: '.$this->divID.'
			};
			var kmlLayer_'.$i.' = new google.maps.KmlLayer("'.$kmlLayerURL.'", kmlLayerOptions);
			';
			++$i;
		}
	}
}

if( !empty($this->fusion_layer) && $this->fusion_layer=="FusionTablesLayer" )
{
	$this->code.='
	fusionlayer = new google.maps.'.$this->fusion_layer.'({
	query: {
			select: "'.$this->fusion_select.'",
			from: "'.$this->fusion_from.'"
	},
	heatmap: {
	  		enabled: '.$this->heat_map.'
	}	
  	});
	fusionlayer.setMap('.$this->divID.');';
}

if( !empty($this->traffic_layer) && $this->traffic_layer=="TrafficLayer" )
{
	$this->code.='
	var trafficLayer = new google.maps.'.$this->traffic_layer.'();
	trafficLayer.setMap('.$this->divID.');';
}

if( !empty($this->transit_layer) && $this->transit_layer=="TransitLayer" )
{
	$this->code.='
	var transitLayer = new google.maps.'.$this->transit_layer.'();
	transitLayer.setMap('.$this->divID.');';
}

if( !empty($this->weather_layer) && $this->weather_layer=="WeatherLayer" )
{
	$this->code.='
	var weatherLayer = new google.maps.weather.'.$this->weather_layer.'({
	windSpeedUnit: google.maps.weather.WindSpeedUnit.'.$this->wind_speed_unit.',
	temperatureUnits: google.maps.weather.TemperatureUnit.'.$this->temperature_unit.'
	});
	weatherLayer.setMap('.$this->divID.');
	var cloudLayer = new google.maps.weather.CloudLayer();
	cloudLayer.setMap('.$this->divID.');';
}

if( !empty($this->bicycling_layer) && $this->bicycling_layer=="BicyclingLayer" )
{
	$this->code.='
	var bikeLayer = new google.maps.'.$this->bicycling_layer.'();
	bikeLayer.setMap('.$this->divID.');';
}

if( !empty($this->panoramio_layer) && $this->panoramio_layer=="PanoramioLayer" )
{
	$this->code.='
	var panoramioLayer = new google.maps.panoramio.'.$this->panoramio_layer.'();
	panoramioLayer.setMap('.$this->divID.');';
}

if( !empty($this->map_panning_true) && $this->map_panning_true=='true')
{
    $this->code.=''.$this->bountpanning.' = new google.maps.LatLngBounds(
                   new google.maps.LatLng('.$this->map_panning_from_latitude.', '.$this->map_panning_from_longitude.'), 
                   new google.maps.LatLng('.$this->map_panning_to_latitude.','.$this->map_panning_to_longitude.'));
    
    google.maps.event.addListener('.$this->divID.', "center_changed", function() {
     if ('.$this->bountpanning.'.contains('.$this->divID.'.getCenter())) return;
     var c = '.$this->divID.'.getCenter(),
         x = c.lng(),
         y = c.lat(),
         maxX = '.$this->bountpanning.'.getNorthEast().lng(),
         maxY = '.$this->bountpanning.'.getNorthEast().lat(),
         minX = '.$this->bountpanning.'.getSouthWest().lng(),
         minY = '.$this->bountpanning.'.getSouthWest().lat();
     if (x < minX) x = minX;
     if (x > maxX) x = maxX;
     if (y < minY) y = minY;
     if (y > maxY) y = maxY;
     '.$this->divID.'.setCenter(new google.maps.LatLng(y, x));
   });
   google.maps.event.addListener('.$this->divID.', "zoom_changed", function() {
     if ('.$this->divID.'.getZoom() < '.$this->map_panning_zoom_level.') '.$this->divID.'.setZoom('.$this->map_panning_zoom_level.');
   });';
}

if( $this->map_overlay=="true" )
{
	$this->code.='
	function CoordMapType(tileSize)
	{
		this.tileSize = tileSize;
	}
	CoordMapType.prototype.getTile = function(coord, zoom, ownerDocument)
	{
		var div = ownerDocument.createElement("div");
		div.innerHTML 			= coord;
		div.style.width 		= "200px";
		div.style.height 		= "300px";
		div.style.fontSize 		= "'.$this->map_overlay_fontsize.'px";
		div.style.borderStyle 	= "'.$this->map_overlay_border_style.'";
		div.style.borderWidth 	= "'.$this->map_overlay_border_width.'px";
		div.style.borderColor 	= "#'.$this->map_overlay_border_color.'";
		return div;
	};
	'.$this->divID.'.overlayMapTypes.insertAt(0, new CoordMapType(new google.maps.Size('.$this->map_overlay_width.', '.$this->map_overlay_height.')));';
}

if(is_array($this->map_shapes['polylines']))
{
	$all_saved_shape=$this->map_shapes['polylines'];
	$all_shapes=explode('|',$all_saved_shape[0]);
	if(is_array($all_shapes))
	{
		foreach($all_shapes as $key=>$shapes)
		{
				$find_shape=explode("=",$shapes);
				
				if($find_shape[0]=='polylines')
				$polylines_shape[0]=$find_shape[1];
				else if($find_shape[0]=='polygons')
				$polygons_shape[0]=$find_shape[1];
				else if($find_shape[0]=='circles')
				$circles_shape[0]=$find_shape[1];
				else if($find_shape[0]=='rectangles')
				$rectangles_shape[0]=$find_shape[1];
		}
	}
	
	if($polygons_shape[0]!='')
	{
		$all_polylines=explode('::',$polygons_shape[0]);
		for($p=0;$p<count($all_polylines);$p++)
		{
			$all_settings=explode('...',$all_polylines[$p]);
			$cordinates=explode('----',$all_settings[0]);
			
			$all_settings_val=explode(',',$all_settings[1]);

			if(empty($all_settings_val[3]))
			$all_settings_val[3]="#ff0000";
			
			if(empty($all_settings_val[4]))
			$all_settings_val[4]=1;
			
			if(empty($all_settings_val[2]))
			$all_settings_val[2]="#ff0000";
			
			if(empty($all_settings_val[1]))
			$all_settings_val[1]=1;
			
			if(empty($all_settings_val[0]))
			$all_settings_val[0]=5;
	
			$this->code.='var path = [';
			$shape_cordinates= '';
			
			for($i=0; $i < count($cordinates); $i++)
			{
				  $latlng=explode(",",$cordinates[$i]);	
				  $shape_cordinates[]='new google.maps.LatLng('.$latlng[0].','.$latlng[1].')';
			}
			
			$this->code.=implode(',',$shape_cordinates);
			$this->code.='];
		
			var gon'.$p.' = new google.maps.Polygon({
			paths: path,
			strokeColor: "'.$all_settings_val[2].'",
			strokeOpacity: '.$all_settings_val[1].',
			strokeWeight: '.$all_settings_val[0].',
			fillColor: "'.$all_settings_val[3].'",
			fillOpacity: '.$all_settings_val[4].'
			});
			gon'.$p.'.setMap('.$this->divID.');';
			$this->code.='wgmp_polygons.push(gon'.$p.');';
	
			if($this->editable==true)
			{
				$this->code.='google.maps.event.addListener(gon'.$p.', "click", function(){
				gon'.$p.'.setEditable(true);
				get_shapes_options(gon'.$p.');
				});'; 
				
				$this->code.='google.maps.event.addListener(gon'.$p.', "rightclick", function(){
				gon'.$p.'.setEditable(true);
				set_shapes_options(gon'.$p.');
				});'; 	
			}
		}
	}
	
	if($polylines_shape[0]!='')
	{
		$all_polylines=explode('::',$polylines_shape[0]);
		for($p=0;$p<count($all_polylines);$p++)
		{
			$all_settings=explode('...',$all_polylines[$p]);
			$cordinates=explode('----',$all_settings[0]);
			
			$all_settings_val=explode(',',$all_settings[1]);
			
			if(empty($all_settings_val[2]))
			$all_settings_val[2]="#ff0000";
			
			if(empty($all_settings_val[1]))
			$all_settings_val[1]=1;
			
			if(empty($all_settings_val[0]))
			$all_settings_val[0]=5;
			
			
			$this->code.='var path = [';
			$shape_cordinates= '';
		
			for($i=0; $i < count($cordinates); $i++)
			{
				  $latlng=explode(",",$cordinates[$i]);	
				  $shape_cordinates[]='new google.maps.LatLng('.$latlng[0].','.$latlng[1].')';
			}
			
			$this->code.=implode(',',$shape_cordinates);
			$this->code.='];
		
			var line'.$p.' = new google.maps.Polyline({
			path: path,
			strokeColor: "'.$all_settings_val[2].'",
			strokeOpacity: '.$all_settings_val[1].',
			strokeWeight: '.$all_settings_val[0].' 
			});
			line'.$p.'.setMap('.$this->divID.');';
	
			$this->code.='wgmp_polylines.push(line'.$p.');';
			
			if($this->editable==true)
			{
				$this->code.='google.maps.event.addListener(line'.$p.', "click", function(){
				line'.$p.'.setEditable(true);
				get_shapes_options(line'.$p.');
				});'; 
				
				$this->code.='google.maps.event.addListener(line'.$p.', "rightclick", function(){
				line'.$p.'.setEditable(true);
				set_shapes_options(line'.$p.');
				});'; 	
			}
		}
	}
	
	if($circles_shape[0]!='')
	{
	
	$all_circles=explode('::',$circles_shape[0]);
	for($p=0;$p<count($all_circles);$p++)
	{
	
	$all_settings=explode('...',$all_circles[$p]);
	$cordinates=explode('----',$all_settings[0]);
	
	$all_settings_val=explode(',',$all_settings[1]);
	
	if(empty($all_settings_val[5]))
	$all_settings_val[5]=1;
	
	
	if(empty($all_settings_val[3]))
	$all_settings_val[3]="#ff0000";
	
	if(empty($all_settings_val[4]))
	$all_settings_val[4]=1;
	
	if(empty($all_settings_val[2]))
	$all_settings_val[2]="#ff0000";
	
	if(empty($all_settings_val[1]))
	$all_settings_val[1]=1;
	
	if(empty($all_settings_val[0]))
	$all_settings_val[0]=5;
	
	
	$this->code.='var center = ';
	$shape_cordinates= '';
	
	for($i=0; $i < count($cordinates); $i++)
	{
		  $latlng=explode(",",$cordinates[$i]);	
		  $shape_cordinates[]='new google.maps.LatLng('.$latlng[0].','.$latlng[1].')';
	}
	
	$this->code.=implode(',',$shape_cordinates);
	
	$this->code.='
		
	var circle'.$p.' = new google.maps.Circle({
	  
	  fillColor: "'.$all_settings_val[3].'",
	  fillOpacity: '.$all_settings_val[4].',
	  strokeColor: "'.$all_settings_val[2].'",
	  strokeOpacity: '.$all_settings_val[1].',
	  strokeWeight: '.$all_settings_val[0].',
	  center: center,
      radius: '.$all_settings_val[5].' 
	});
	circle'.$p.'.setMap('.$this->divID.');';
	
	$this->code.='wgmp_circles.push(circle'.$p.');';
	
	if($this->editable==true)
	{
	$this->code.='google.maps.event.addListener(circle'.$p.', "click", function(){
		
		circle'.$p.'.setEditable(true);
		
		get_shapes_options(circle'.$p.');
		
	
		});'; 
		
	$this->code.='google.maps.event.addListener(circle'.$p.', "rightclick", function(){
		
		circle'.$p.'.setEditable(true);
	
		set_shapes_options(circle'.$p.');
		
		});'; 	
	}
	}
		
	} // Circles Ended Here
	
	if($rectangles_shape[0]!='')
	{
		
	$all_polylines=explode('::',$rectangles_shape[0]);
	
	for($p=0;$p<count($all_polylines);$p++)
	{
	
	$all_settings=explode('...',$all_polylines[$p]);
	$cordinates=explode('----',$all_settings[0]);
	
	$all_settings_val=explode(',',$all_settings[1]);
	
	
	if(empty($all_settings_val[3]))
	$all_settings_val[3]="ff0000";
	
	if(empty($all_settings_val[4]))
	$all_settings_val[4]=1;
	
	if(empty($all_settings_val[2]))
	$all_settings_val[2]="ff0000";
	
	if(empty($all_settings_val[1]))
	$all_settings_val[1]=1;
	
	if(empty($all_settings_val[0]))
	$all_settings_val[0]=5;
	
	
	$this->code.='var path = new google.maps.LatLngBounds ( ';
	$shape_cordinates= '';
	
	for($i=0; $i < count($cordinates); $i++)
	{
		  $latlng=explode(",",$cordinates[$i]);	
		  $shape_cordinates[]='new google.maps.LatLng('.$latlng[0].','.$latlng[1].')';
	}
	$this->code.=implode(',',$shape_cordinates);
	$this->code.=');
		
	var rectangle'.$p.' = new google.maps.Rectangle({
	  bounds: path,
	  strokeColor: "'.$all_settings_val[2].'",
	  strokeOpacity: '.$all_settings_val[1].',
	  strokeWeight: '.$all_settings_val[0].',
	  fillColor: "'.$all_settings_val[3].'",
	  fillOpacity: '.$all_settings_val[4].'
	});
	
	rectangle'.$p.'.setMap('.$this->divID.');';
	
	$this->code.='wgmp_rectangles.push(rectangle'.$p.');';
	
	if($this->editable==true)
	{
	$this->code.='google.maps.event.addListener(rectangle'.$p.', "click", function(){
		
		rectangle'.$p.'.setEditable(true);
		
		get_shapes_options(rectangle'.$p.');
		
	
		});'; 
		
	$this->code.='google.maps.event.addListener(rectangle'.$p.', "rightclick", function(){
		
		rectangle'.$p.'.setEditable(true);
	
		set_shapes_options(rectangle'.$p.');
		
		});'; 	
	}
	}
	}
}


for($i=0; $i< count($this->marker); $i++)
{
	
	if($this->map_infowindow_open=='true') {
		$this->code .=''.$this->infowindow.$i.' =  new google.maps.InfoWindow();';
	}

  if( empty($this->marker[$i]['draggable']) )
	 $this->marker[$i]['draggable']='false';
  if( $this->marker[$i]['click']=='true' )
	 $this->marker[$i]['click']='true';
  else
	 $this->marker[$i]['click']='false';
	 
	 $info_title =$this->marker[$i]['title'];
	 
	 $this->code.='marker'.$this->marker[$i]['location_id'].$this->divID.'=new google.maps.Marker({
		map: '.$this->divID.',
		draggable:'.$this->marker[$i]['draggable'].',';
		$this->code.='position: new google.maps.LatLng('.$this->marker[$i]['lat'].', '.$this->marker[$i]['lng'].'), 
		title: "'. $info_title.'",
		clickable: '.$this->marker[$i]['click'].',
		icon: "'.$this->marker[$i]['icon'].'",
	  });';
	  
	  if( $this->display_marker_category=='true' )
	  {
		  if($this->marker[$i]['cat_id'])
		  {
		   $group_id = $this->marker[$i]['cat_id'];
		  
		   $this->code .= "\n".'if(typeof groups.group'.$group_id.' == "undefined")
						  groups.group'.$group_id.' = [];';	  
			  
		   $this->code .= "\n".'groups.group'.$group_id.'.push(marker'.$this->marker[$i]['location_id'].$this->divID.');';	  
		 }
	  }
	  
	 
 
if( $this->marker[$i]['info']!='' )
{
	
	$infos = $this->marker[$i]['info'];
	
	if( is_array($infos) )
	{
		$message = nl2br($infos['first']['message']);
		$info_message = stripslashes($message);
		$infos_mess = str_replace(array("\r\n"),'',$info_message);
	}
	elseif( $infos!='' )
	{
		$message = nl2br($infos);
		$info_message = stripslashes($message);
		$infos_mess = str_replace(array("\r\n"),'',$info_message);
	}	
	
	$infos_mess = apply_filters("wgmp_infowindow_content",$infos_mess,$this->map_id);
	
	
	if($this->map_infowindow_open=='true') {
		
		 $this->code.='
		 
		  var infowindow_content='.json_encode($infos_mess).';
		 '.$this->infowindow.$i.'.setContent(infowindow_content);	
		 '.$this->infowindow.$i.'.open('.$this->divID.',marker'.$this->marker[$i]['location_id'].$this->divID.');
		 
			google.maps.event.addListener(marker'.$this->marker[$i]['location_id'].$this->divID.', "click", function() {
			 
			 '.$this->infowindow.$i.'.open('.$this->divID.',marker'.$this->marker[$i]['location_id'].$this->divID.');
			 
				google.maps.event.addListener('.$this->divID.', "click", function() {
					
					'.$this->infowindow.$i.'.close();
				
				});
			
			});'; 
		
	} else {
	
		$this->code.='google.maps.event.addListener(marker'.$this->marker[$i]['location_id'].$this->divID.', "click", function() {
		
		 var infowindow_content='.json_encode($infos_mess).';
		 '.$this->infowindow.'.setContent(infowindow_content);	
		 
		 '.$this->infowindow.'.open('.$this->divID.',marker'.$this->marker[$i]['location_id'].$this->divID.');
		 
			google.maps.event.addListener('.$this->divID.', "click", function() {
				
				'.$this->infowindow.'.close();
			
			});
			
		});'; 
	}
	
if( $this->route_direction == 'true' )
{

$this->code .= '
google.maps.event.addListener(marker'.$this->marker[$i]['location_id'].$this->divID.', "dragend", function() {';

	if(count($this->marker)<3)
	{
	    $this->code.='	
	    var request = { 
				        origin: marker'.$this->marker[0]['location_id'].$this->divID.'.getPosition(), 
				        destination: marker'.$this->marker[1]['location_id'].$this->divID.'.getPosition(), 
				        travelMode: google.maps.DirectionsTravelMode.WALKING 
	    }; 
	    
	    '.$this->directionsDisplay.'.setMap(null);

	    '.$this->directionsService.'.route(request, function(response, status) {
			
			if (status == google.maps.DirectionsStatus.OK)
			{
				'.$this->directionsDisplay.' = new google.maps.DirectionsRenderer('.$this->renderer_options.');
				'.$this->directionsDisplay.'.setMap('.$this->divID.');
				'.$this->directionsDisplay.'.setDirections(response);		
			}
	  	});';
	}
	elseif( count($this->marker)>2 )
	{
		$start_point = current($this->marker);
		$end_point = end($this->marker);
		$newarray = array_slice($this->marker, 1, -1);
		foreach($newarray as $newarr)
		{
		 	$new_array_value[] = $newarr['location_address'];
		}

		$js_array = json_encode($new_array_value);
	    $this->code.='
		var start = marker'.$start_point['location_id'].$this->divID.'.getPosition();
		var end = marker'.$end_point['location_id'].$this->divID.'.getPosition();
		var waypts = [];
		checkboxArray = '.$js_array.';
		
		for(var mp=0; mp<checkboxArray.length; mp++) {
			waypts.push({
				location:checkboxArray[mp],
				stopover:true});
		}
		
		var request = {
			origin: start,
			destination: end,
			waypoints: waypts,
			optimizeWaypoints: true,
			travelMode: google.maps.TravelMode.DRIVING
		};
		
		'.$this->directionsDisplay.'.setMap(null);

		'.$this->directionsService.'.route(request, function(response, status) {
			if (status == google.maps.DirectionsStatus.OK)
			{	
				'.$this->directionsDisplay.' = new google.maps.DirectionsRenderer('.$this->renderer_options.');
				'.$this->directionsDisplay.'.setMap('.$this->divID.');
				'.$this->directionsDisplay.'.setDirections(response);	
			}
			else
			{
			console.info("could not get route");
			console.info(response);
			}
	  });';
	}

     $this->code.='});';
}
$this->code.='allmarkers.push(marker'.$this->marker[$i]['location_id'].$this->divID.');';	  
}
}


if( $this->marker_cluster=='true' )
{
	$this->code.='markerClusterer = new MarkerClusterer('.$this->divID.', allmarkers , {
	
	gridSize:'.$this->grid.',
	
	maxZoom:'.$this->max_zoom.',
	
	styles: styles['.$this->style.']
	
	});';
}

if($this->editable==true)
{
	
	$objects=array('circle','polygon','polyline','rectangle');
	for($i=0;$i<count($objects);$i++)
	{
		$object_name=$objects[$i];
	
		$drawingModes[]="google.maps.drawing.OverlayType.".strtoupper($object_name);
		
		if($this->map_drawing[$object_name]['fill_color']!='')
		$circle_options[$object_name][]= "fillColor: '#".$this->map_drawing[$object_name]['fill_color']."'";
		else
		$circle_options[$object_name][]= "fillColor: '#BAC1FF'";
		
		if($this->map_drawing[$object_name]['border_color']!='')
		$circle_options[$object_name][]= "strokeColor: '#".$this->map_drawing[$object_name]['border_color']."'";
		else
		$circle_options[$object_name][]= "strokeColor: '#8C75FF'";
		
		if($this->map_drawing[$object_name]['border_thickness']!='')
		$circle_options[$object_name][]= "strokeWeight: '".$this->map_drawing[$object_name]['border_thickness']."'";
		else
		$circle_options[$object_name][]= "strokeWeight: '2'";
		
		if($this->map_drawing[$object_name]['stroke_opacity']!='')
		$circle_options[$object_name][]= "strokeOpacity: '".$this->map_drawing[$object_name]['stroke_opacity']."'";
		else
		$circle_options[$object_name][]= "strokeOpacity: '1'";
		
		if($this->map_drawing[$object_name]['zindex']!='')
		$circle_options[$object_name][]= "zindex: ".$this->map_drawing[$object_name]['zindex'];
		else
		$circle_options[$object_name][]= "zindex: 1";
		
		
		if($this->map_drawing[$object_name]['fill_opacity']!='')
		$circle_options[$object_name][]= "fillOpacity: '".$this->map_drawing[$object_name]['fill_opacity']."'";
		else
		$circle_options[$object_name][]= "fillOpacity: '0.5'";
		
		if($this->map_drawing[$object_name]['editable']=='on')
		$circle_options[$object_name][]= "editable: true";
		else
		$circle_options[$object_name][]= "editable: false";
		
		if($this->map_drawing[$object_name]['draggable']=='on')
		$circle_options[$object_name][]= "draggable: true";
		else
		$circle_options[$object_name][]= "draggable: false";
	}
	
	
	if(is_array($drawingModes))
	$display_modes=implode(",",$drawingModes);
	
	if(is_array($circle_options[circle]))
	$display_circle_options=implode(",",$circle_options['circle']);
	
	if(is_array($circle_options[polygon]))
	$display_polygon_options=implode(",",$circle_options['polygon']);
	
	if(is_array($circle_options[polyline]))
	$display_polyline_options=implode(",",$circle_options['polyline']);
	
	if(is_array($circle_options[rectangle]))
	$display_rectangle_options=implode(",",$circle_options['rectangle']);
	
if($this->editable==true)
{
		
$this->code.="  var drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: null,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [
       ".$display_modes."
      ]
    },
   
    circleOptions: {
      ".$display_circle_options."
    },
     polygonOptions: {
      ".$display_polygon_options."
    },
    polylineOptions: {
      ".$display_polyline_options."
    },
    rectangleOptions: {
      ".$display_rectangle_options."
    }
  });
  drawingManager.setMap(".$this->divID.");";
  
  for($i=0;$i<count($objects);$i++)
	{
		$object_name=$objects[$i];
		
  $this->code.="
  
  google.maps.event.addDomListener(drawingManager, '".$object_name."complete', function(".$object_name.") {
       
       wgmp_".$object_name."s.push(".$object_name.");
       
       wpgmp_shape_complete(".$object_name.");
       
        
      }); ";
  } 
      
  $this->code.="
      google.maps.event.addDomListener(document.getElementById('reset_drawing'), 'click', function() { ";
  
   for($i=0;$i<count($objects);$i++)
	{
		$object_name=$objects[$i];     
  
  $this->code.="
  
		for (var i = 0; i < wgmp_".$object_name."s.length; i++) {
                            wgmp_".$object_name."s[i].setMap(null);
        }
        
        wgmp_".$object_name."s = [];";
   }
        
  $this->code.="
      });
      
      google.maps.event.addDomListener(document.getElementById('save_drawing'), 'click', function() {
        
        var all_shapes_cordinate = [];
        
        all_shapes_cordinate.push('polylines='+wpgmp_save_polylines().join('::'));
        all_shapes_cordinate.push('polygons='+wpgmp_save_polygons().join('::'));
        all_shapes_cordinate.push('circles='+wpgmp_save_circles().join('::'));
        all_shapes_cordinate.push('rectangles='+wpgmp_save_rectangles().join('::'));
        
        
      wpgmp_save_shapes(all_shapes_cordinate);
      
      });
      
      google.maps.event.addDomListener(document.getElementById('set_pan_tools'), 'click', function() {
	   drawingManager.circleOptions.fillColor='#'+jQuery('#options_fillcolor').val();
	   drawingManager.rectangleOptions.fillColor='#'+jQuery('#options_fillcolor').val();
	   drawingManager.polygonOptions.fillColor='#'+jQuery('#options_fillcolor').val();
	   
	   drawingManager.circleOptions.fillOpacity=jQuery('#options_fillopacity').val();
	   drawingManager.rectangleOptions.fillOpacity=jQuery('#options_fillopacity').val();
	   drawingManager.polygonOptions.fillOpacity=jQuery('#options_fillopacity').val();
	   drawingManager.polylineOptions.fillOpacity=jQuery('#options_fillopacity').val();
	  
	   drawingManager.circleOptions.strokeColor='#'+jQuery('#options_color').val();
	   drawingManager.rectangleOptions.strokeColor='#'+jQuery('#options_color').val();
	   drawingManager.polygonOptions.strokeColor='#'+jQuery('#options_color').val();
	   drawingManager.polylineOptions.strokeColor='#'+jQuery('#options_color').val();
	   
	   drawingManager.circleOptions.strokeWeight=jQuery('#options_stroke_thickness').val();
	   drawingManager.rectangleOptions.strokeWeight=jQuery('#options_stroke_thickness').val();
	   drawingManager.polygonOptions.strokeWeight=jQuery('#options_stroke_thickness').val();
	   drawingManager.polylineOptions.strokeWeight=jQuery('#options_stroke_thickness').val();	  
     });";
}
}	
$this->code.="}";

if( $this->display_marker_category=='true' )
{
$this->code.='



jQuery(document).ready(function($) {
$(".wpgmp_specific_category_location").click(function() {
	
	for( var n in groups)
	{ 
		for(i = 0; i <groups[n].length; i++)
		{
			if( typeof groups[n][i].getMap() == "null");
			groups[n][i].setMap(null);
		}	 
	}					
	
	all_checked=$(".wpgmp_specific_category_location:checkbox:checked");
	
	if(all_checked.length!=0)
	{
	$(all_checked).each(function(){
		
	
		group_id = this.value;
		
		var bounds = new google.maps.LatLngBounds();
		if(groups)
		{	
			for( var n in groups)
			{ 
				if(n.indexOf("group") != "-1")
				{
					if(n == "group"+group_id)
					{
						for(i = 0; i <groups[n].length; i++)
						{
							if( typeof groups[n][i].getMap() == "null");
							groups[n][i].setMap('.$this->divID.');
							bounds.extend(groups[n][i].getPosition());
						}	 
					}
				}
			}
		}	
	
		
	});	
	wpgmp_filter_locations('.$this->map_id.',1);
}
else
{
	
	for( var n in groups)
	{ 
		for(i = 0; i <groups[n].length; i++)
						{
							if( typeof groups[n][i].getMap() == "null");
							groups[n][i].setMap('.$this->divID.');
						}	 
	}		
	wpgmp_filter_locations('.$this->map_id.',1);
}
	
	
});
});


	function wpgmp_calculate_route(map_id) {
	    
		'.$this->directionsDisplay.'.setMap('.$this->divID.');
		'.$this->directionsDisplay.'.setPanel(document.getElementById("directions-panel"+map_id));

		 var start = document.getElementById("fromloc"+map_id).value;
		 var end =  document.getElementById("toloc"+map_id).value;
		 
		 if(start=="")
		 {
			 alert("Please enter start point.");
			 return false;
		 }
		 if(end=="")
		 {
			 alert("Please enter end point.");
			 return false;
		 }
		
		jQuery("#directions-panel"+map_id).show();
	
		var request = {
			origin: start,
			destination: end,
			travelMode: wpgmp_get_selected_travel_mode()
		  };
		  
		  '.$this->directionsService.'.route(request, function(response, status) {
			    	if (status == google.maps.DirectionsStatus.OK) {
						'.$this->directionsDisplay.'.setDirections(response);
					}
         });
      }
';	
}

$this->code.='</script>';

/* remove tabs, spaces, newlines, etc. */
$this->code = str_replace(array("\r\n","\r","\t","\n",'  ','    ','     '), '', $this->code);
/* remove other spaces before/after ) */
$this->code = preg_replace(array('(( )+\))','(\)( )+)'), ')', $this->code);
/* remove some more spaces. */
$this->code = str_replace(array('), ','", ',' = ',': {',", '",': "',', function',': true',': false'), array('),','",','=',':{',",'",':"',',function',':true',':false'), $this->code);


}

// Add markers to google map.

public function addMarker($loc_id,$loc_address,$lat,$lng,$click='false',$title='My WorkPlace',$info='Hello World',$icon='',$map='map',$draggable='',$animation='',$cat_id='',$cat_title='')
{
	$count=count($this->marker);	
	
	$this->marker[$count]['location_id']=$loc_id;

	$this->marker[$count]['location_address']=$loc_address;
	
	$this->marker[$count]['lat']=$lat;
	
	$this->marker[$count]['lng']=$lng;
	
	$this->marker[$count]['click']=$click;
	
	$this->marker[$count]['title']=$title;
	
	$this->marker[$count]['info']=$info;
	
	$this->marker[$count]['icon']=$icon;
	
	$this->marker[$count]['map']=$map;
	
	$this->marker[$count]['draggable']=$draggable;
	
	$this->marker[$count]['animation']=$animation;
	
	$this->marker[$count]['cat_id']=$cat_id;

	$this->marker[$count]['cat_title']=$cat_title;
}
public function addMarkerByAddress($address,$click='false',$title='My WorkPlace',$info='Hello World',$icon='',$map='map')
{
	$status = false;
	$output = $this->getData($address);
	if( $output->status == 'OK' )
	{
	   $lat = $output->results[0]->geometry->location->lat;
	   $lng = $output->results[0]->geometry->location->lng;
	   $status = true;
	}
	if( $status )
	{
		$count=count($this->marker);	
		$this->marker[$count]['lat']=$lat;
		$this->marker[$count]['lng']=$lng;
		$this->marker[$count]['map']=$map;
		$this->marker[$count]['title']=$title;
		$this->marker[$count]['click']=$click;
		$this->marker[$count]['icon']=$icon;
		$this->marker[$count]['info']=$info;
    }		
}
public function addroutedirections($lat,$lng)
{
	$count=count($this->routedirections);	
	
	$this->routedirections[$count]['lat']=$lat;
	
	$this->routedirections[$count]['lng']=$lng;
}
public function addpolyline($lat,$lng)
{
	$count=count($this->polyline);	
	
	$this->polyline[$count]['lat']=$lat;
	
	$this->polyline[$count]['lng']=$lng;
}
public function addPolygon($lat,$lng)
{
	$count=count($this->polygon);
	
	$this->polygon[$count]['lat']=$lat;
	
	$this->polygon[$count]['lng']=$lng;
}
// Call this function to create a google map.
public function showmap()
{
	$this->marker = apply_filters("wpgmp_markers",$this->marker,$this->map_id);
	$this->start();
	$this->instance++;
	return $this->code;
}
public function getData($address)
{
  $url = 'http://maps.google.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false';
  if( ini_get('allow_url_fopen') )
  {
		$geocode2 = wp_remote_get($url);
		$geocode=$geocode2['body'];
  }
  elseif( !ini_get('allow_url_fopen') )
  {
		$geocode2 = wp_remote_get($url);
		$geocode=$geocode2['body'];
  }
  else
  {
	echo "Configure your php.ini settings. allow_url_fopen may be disabled";
	exit;
  }	
  return json_decode($geocode);
}
}
