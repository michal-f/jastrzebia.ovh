<?php
function wpgmp_js_head()
{
global $wpdb,$post;
if( !empty($_GET['location']) )
{
	$user_record = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."map_locations where location_id=%d",$_GET['location']));
	$group_marker = $wpdb->get_row($wpdb->prepare("SELECT group_marker FROM ".$wpdb->prefix."group_map where group_map_id=%d",$user_record->location_group_map));
	if(!empty($group_marker)) 
	{
		$image_src = $group_marker->group_marker;	
	}
}
?>
<script type="text/javascript"> 
var geocoder;
var map;
function initialize() {		
	
geocoder = new google.maps.Geocoder();
  
var latlng = new google.maps.LatLng(-34.397, 150.644);
 
var imgurl= "<?php echo $image_src; ?>";
if(imgurl=="")
{
   var image = '<?php echo plugins_url('images/blue-dot.png', __FILE__ ); ?>';
}
else
{
	var image= imgurl;
}
	
  var mapOptions = {
    zoom: 8,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  map = new google.maps.Map(document.getElementById('map'), mapOptions);
  
  marker = new google.maps.Marker({
                 position: latlng,
                 map: map,
				 
				 draggable:true,
                 icon: image,
    			 animation: google.maps.Animation.DROP,
             });
			 
		google.maps.event.addListener(marker, "dragend", function(event) {
			geocodePosition(marker.getPosition());
			
			map.panTo(marker.getPosition());
			
			jQuery('.google_latitude').val(event.latLng.lat());
            jQuery('.google_longitude').val(event.latLng.lng());
        });	 
        
        
		var input = document.getElementById('googlemap_address');
        
		 var autocomplete = new google.maps.places.Autocomplete(input, {
             types: ["geocode"]
         });
		 
         autocomplete.bindTo('bounds', map);
         var infowindow = new google.maps.InfoWindow();
         google.maps.event.addListener(autocomplete, 'place_changed', function (event) {
             infowindow.close();
             var place = autocomplete.getPlace();
             if (place.geometry.viewport) {
                 map.fitBounds(place.geometry.viewport);
             } else {
                 map.setCenter(place.geometry.location);
                 map.setZoom(17);
             }
			 moveMarker(place.name, place.geometry.location);
             jQuery('.google_latitude').val(place.geometry.location.lat());
             jQuery('.google_longitude').val(place.geometry.location.lng());
         });
         function moveMarker(placeName, latlng)
		 {
             marker.setIcon(image);
             marker.setPosition(latlng);
         }
geocodeaddress();
}

function geocodePosition(pos) {
	  
  	geocoder.geocode({
    	latLng: pos
  	},
	
	function(responses)
	{
    	if (responses && responses.length > 0)
		{
      		jQuery('#googlemap_address').val(responses[0].formatted_address);
    	}
		else
		{
      		alert('Cannot determine address at this location.');
    	}
  	});
}

function geocodeaddress() {
	
var imgurl= '<?php echo $image_src; ?>';
if(imgurl=='')
{
   var image = 'http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';
}
else
{
	var image= imgurl;
}
	
  var address = document.getElementById('googlemap_address').value;
  geocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
      var marker = new google.maps.Marker({
          map: map,
		  
		  icon:image,
		  
		  draggable:true,
          position: results[0].geometry.location
      });
	  
	  google.maps.event.addListener(marker, "dragend", function(event) {
			geocodePosition(marker.getPosition());
			
			map.panTo(marker.getPosition());
			
			jQuery('.google_latitude').val(event.latLng.lat());
            jQuery('.google_longitude').val(event.latLng.lng());
        });	
	  var latitude = results[0].geometry.location.lat();
     var longitude = results[0].geometry.location.lng();
		
	document.getElementById("googlemap_latitude").value = latitude;
	document.getElementById("googlemap_longitude").value = longitude;
    }
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
<?php
}
