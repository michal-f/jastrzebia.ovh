<?php
/**
 * This function used to create locations quickly.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
add_action('wp_ajax_save_map_meta', 'save_map_meta') ;
function save_map_meta(){
global $wpdb;
  if(empty($_POST['data'])) {
  	echo 'false';
  } 
  if(!empty($_POST['data'])) {
	$data = $_POST['data'];
	$location_table=$wpdb->prefix."map_locations";
	
	for($i=0; $i<count($data); $i++)
 	{
		$infowindow['googlemap_infowindow_message_one'] = $data[$i]['description'];
		$messages = base64_encode(serialize($infowindow));
		
		$in_loc_data = array(
			'location_title' => htmlspecialchars(stripslashes($data[$i]['title'])),
			'location_address' => htmlspecialchars(stripslashes($data[$i]['address'])),
			'location_draggable' =>$data[$i]['is_drag'],
			'location_settings' => serialize(array("hide_infowindow"=>$data[$i]['is_click'])),
			'location_latitude' => $data[$i]['lat'],
			'location_longitude'=> $data[$i]['lng'],
			'location_messages'=> $messages,
			'location_group_map' => $data[$i]['marker_group']
		);
	
		$rec=$wpdb->get_results("select * from $location_table where location_latitude='".$data[$i]['lat']."' and location_longitude='".$data[$i]['lng']."' ");
	
		if($rec[0]->location_id!='')
		$return=$wpdb->update($location_table,$in_loc_data,array('location_id'=>$rec[0]->location_id));
		else
		$return=$wpdb->insert($location_table,$in_loc_data);
	
	}
	
  }
echo $return;  
exit;  
}
 
function wpgmp_quick_locations()
{
global $wpdb;	
$group_results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."group_map");
$json = json_encode($group_results);	
?>
<script src="http://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=true"></script>
<script type="text/javascript">
var markers = new Array();
var groupmarker = new Array();
var mCurrZoom;
var mCurrMapType;
var custom_address;
jQuery(document).ready(function() {
	var mapCenter = new google.maps.LatLng(42.345573, -71.098326); //Google map Coordinates
	var map;
	var quickmapgeocoder = new google.maps.Geocoder();
	
	map_initialize();
	
	groupmarker = <?php echo $json; ?>;
	
	jQuery('.geocodeaddress').click(function()
	{
	  var latlng = new google.maps.LatLng(42.345573, -71.098326);
	  var mapOptions = {
		zoom: 8,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	  }
	  
	  map = new google.maps.Map(document.getElementById('quick_map'), mapOptions);
	  
	  google.maps.event.addListener(map, 'click', function(event) {
	
		
				var EditForm = '<div class="marker-edit">'+
				'<form action="" method="POST" name="SaveMarker" id="SaveMarker">'+
				'<table>'+
				'<tr><td>Enter Title</td></tr><tr><td><input type="text" size="40" name="pName" class="save-name" placeholder="Enter Title" /><br />'+
				'<input type="hidden" name="mkey" class="mark-key-url" value="'+num+'"/>'+'</td></tr>'+
				'<tr><td>Enter Message (<i>html tags allowed</i>)</td></tr><tr><td><textarea cols="40" rows="4" name="pDesc" class="save-desc" placeholder="Enter Message"></textarea>'+'</td></tr>'+
				'<tr><td><table><tr><td>Draggable : <input class="is_draggable" type="checkbox" name="is_draggable" id="is_draggable" value="true" ></td><td>Disable Infowindow : <input class="is_clickable" type="checkbox" name="is_clickable" id="is_clickable" value="false" ></td></tr></table>'+'</td></tr>'+
				'<tr><td style="padding-top:10px;">Choose Marker Group</td></tr><tr><td>'+
				'<select name="location_group_map" class="choose_marker">'+
				'<option value="">Select Marker Group</option>'+
				option+
				'</select>'+
				'</td></tr></table>'+
				'<br />'+
				'</form>'+
				'<button name="save-marker" class="save-marker button button-primary">Add Marker</button>&nbsp;&nbsp;'+
				'<br><br></div>';
	
			create_marker(event.latLng, '', EditForm, true, true, true, "");
		});
		
	  var address = document.getElementById('quick_googlemap_address').value;
	
	  geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK)
		{
		  map.setCenter(results[0].geometry.location);
		}
	  });
	});
	function map_initialize()
	{
			var googleMapOptions = 
			{ 
				center: mapCenter, // map center
				zoom: 10, //zoom level, 0 = earth view to higher value
				maxZoom: 18,
				zoomControlOptions: {
				style: google.maps.ZoomControlStyle.SMALL //zoom control size
			},
				scaleControl: true, // enable scale control
				mapTypeId: google.maps.MapTypeId.ROADMAP // google map type
			};
			
			var num = 0;
		
		   	map = new google.maps.Map(document.getElementById("quick_map"), googleMapOptions);			
			
			var option;
			google.maps.event.addListener(map, 'click', function(event) {
            num++; 
			option='';
			for (var n = 0; n<groupmarker.length; n++){
				option += '<option value='+groupmarker[n].group_map_id+'>'+groupmarker[n].group_map_title+'</option>';
			}
				var EditForm = '<div class="marker-edit">'+
				'<form action="" method="POST" name="SaveMarker" id="SaveMarker">'+
				'<table>'+
				'<tr><td>Enter Title</td></tr><tr><td><input type="text" size="40" name="pName" class="save-name" placeholder="Enter Title" /><br />'+
				'<input type="hidden" name="mkey" class="mark-key-url" value="'+num+'"/>'+'</td></tr>'+
				'<tr><td>Enter Message (<i>html tags allowed</i>)</td></tr><tr><td><textarea cols="40" rows="4" name="pDesc" class="save-desc" placeholder="Enter Message"></textarea>'+'</td></tr>'+
				'<tr><td><table><tr><td>Draggable : <input class="is_draggable" type="checkbox" name="is_draggable" id="is_draggable" value="true" ></td><td>Disable Infowindow : <input class="is_clickable" type="checkbox" name="is_clickable" id="is_clickable" value="false" ></td></tr></table>'+'</td></tr>'+
				'<tr><td style="padding-top:10px;">Choose Marker Group</td></tr><tr><td>'+
				'<select name="location_group_map" class="choose_marker">'+
				'<option value="">Select Marker Group</option>'+
				option+
				'</select>'+
				'</td></tr></table>'+
				'<br />'+
				'</form>'+
				'<button name="save-marker" class="save-marker button button-primary">Add Marker</button>&nbsp;&nbsp;'+
				'<br><br></div>';
				create_marker(event.latLng, '', EditForm, true, true, true, "");
			});
			
			
		  var quickmapinput = document.getElementById('quick_googlemap_address');
		  
		  var quickmapautocomplete = new google.maps.places.Autocomplete(quickmapinput, {
			  types: ["geocode"]
		  });
		  
		  quickmapautocomplete.bindTo('bounds', map);
		  
		  google.maps.event.addListener(quickmapautocomplete, 'place_changed', function (event) {
		
					 var quickmapplace = quickmapautocomplete.getPlace();
					 
					 if (quickmapplace.geometry.viewport) {
		
						 map.fitBounds(quickmapplace.geometry.viewport);
		
					 } else {
		
						 map.setCenter(quickmapplace.geometry.location);
		
						 map.setZoom(17);
		
					 }
					
					 quickmapmoveMarker(quickmapplace.name, quickmapplace.geometry.location);
		
		  });
		  
		  function quickmapmoveMarker(placeName, latlng)
		  {
			 marker.setPosition(latlng);
		  }
}
function create_marker(MapPos, MapTitle, MapDesc,  InfoOpenDefault, DragAble, Removable, iconPath)
{
	var marker = new google.maps.Marker({
		position: MapPos,
		map: map,
		draggable:DragAble,
		animation: google.maps.Animation.DROP,
		title:"Drag me for your exact location.",
		icon: iconPath
	});
		
	var contentString = jQuery('<div class="marker-info-win">'+
	'<div class="marker-inner-win"><span class="info-content">'+
	MapDesc+
	'</span>'+
	'</div></div>');
	
	var infowindow = new google.maps.InfoWindow();
	
	infowindow.setContent(contentString[0]);
	
	var saveBtn = contentString.find('button.save-marker')[0];
	
	if(typeof saveBtn !== 'undefined')
	{
		//add click listner to save marker button
		google.maps.event.addDomListener(saveBtn, "click", function(event) {
			//var mReplace = contentString.find('span.info-content'); //html to be replaced after success
			
			var mName = contentString.find('input.save-name')[0].value; //name input field value
			var mDesc  = contentString.find('textarea.save-desc')[0].value; //description input field value
			var mGroup  = contentString.find('select.choose_marker')[0].value;
			var markey  = contentString.find('input.mark-key-url')[0].value;
			var is_drag=contentString.find('input[name="is_draggable"]:checked');
			var is_click=contentString.find('input[name="is_clickable"]:checked');
			
			if(is_drag.is(':checked'))
			is_drag=true;
			else
			is_drag=false;
			
			if(is_click.is(':checked'))
			is_click=false;
			else
			is_click=true;
			
			if(mName =='')
			{
				alert("Please enter Title!");
				
			} else {
	
						save_marker(marker, mName, mDesc, mGroup,markey,is_drag,is_click); //call save marker function
			}
		});
	}
	
	
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
	
	if(InfoOpenDefault)
	{
	  InfoOpenDefault = false;	
	  infowindow.open(map,marker);
	}
}
function save_marker(Marker,mName, mDescription, mGroup, mkey,is_drag,is_click)
{
	var mLat = Marker.getPosition().lat();
	var mLng = Marker.getPosition().lng();
	quickmapgeocoder.geocode({ 'latLng': new google.maps.LatLng(mLat,mLng) }, function(results, status){
		var mAdrs = results[0].formatted_address;	
		markers[0] = {lat : mLat, lng : mLng, title : mName, address:mAdrs, description : mDescription, marker_group : mGroup,is_drag:is_drag,is_click:is_click}; //post variables
		showobj();
	});
}
	
});
function showobj(){
	
jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', { action : 'save_map_meta', data: markers }, function(data,status){
		if(data=='false') {
			alert('Please click on map to create your location.');
		}else if(data>0) {
			alert('Locations save successfully!');
		}
	 })
}
</script>
<div class="wpgmp-wrap"> 
<div class="col-md-11">  
<div id="icon-options-general" class="icon32"><br/>
</div>
<h3><span class="glyphicon glyphicon-asterisk"></span>  <?php _e('Quicks Locations', 'wpgmp_google_map')?></h3>
<div class="wpgmp-overview">
<div class="form-horizontal row">
<div class="col-md-7"><div class="col-md-7 col-sm-7"> <input type="text" name="quick_googlemap_address" id="quick_googlemap_address" class="form-control"  value="" /></div>
<div class="col-md-2 col-sm-2"><input type="button" value="<?php _e('Geocode', 'wpgmp_google_map')?>" class="btn btn-sm btn-primary geocodeaddress" /></div></div>
</div> 
 
<div class="col-md-11"><br /><p><?php _e("Click on map to create your locations.", "wpgmp_google_map"); ?></p></div>
<?php
if(!empty($error))
{
	$error_msg=implode('<br>',$error);
	wpgmp_showMessage($error_msg,true);
}
if(!empty($success))
{
    wpgmp_showMessage($success);
}
?>
<div id="quick_map" style="width: 100%; height: 500px; border:2px solid #000000;"></div>
</div></div>
</div>
<?php
}
?>
