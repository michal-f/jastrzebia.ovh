jQuery(document).ready(function($) {

	
jQuery(".wpgmp_search_input").keyup(function(){

   map_id=$(this).attr("rel");
			
   jQuery(".wpgmp_locations_listing[rel='"+map_id+"']").addClass("wpgmp_loading");
    wpgmp_filter_locations(map_id,1);
	
	});
		
		
		jQuery(".wpgmp_toggle_container").click(function() {
			jQuery(".wpgmp_toggle_main_container").toggle( "slow" );
			if(jQuery(this).text() == "Hide"){
			   jQuery(this).text("Show");
		   } else {
			   jQuery(this).text("Hide");
		   }
		});
			
		 
		 
		 jQuery(".wpgmp-tab-2").click(function() {
			
			 map_id=$(this).attr("rel");
			 jQuery(".wpgmp-tab-1[rel='"+map_id+"']").removeClass("active");
			 jQuery(".wpgmp-tab-3[rel='"+map_id+"']").removeClass("active");
			 jQuery(this).addClass("active");
			 jQuery("#wpgmp_cat_tab"+map_id+"").hide();
			 jQuery("#wpgmp_nearby_tab"+map_id+"").hide();
			 jQuery("#wpgmp_dir_tab"+map_id+"").show();
		 });
		 
		 jQuery(".wpgmp-tab-1").click(function() {
			
			 map_id=$(this).attr("rel");
			 jQuery(".wpgmp-tab-2[rel='"+map_id+"']").removeClass("active");
			 jQuery(".wpgmp-tab-3[rel='"+map_id+"']").removeClass("active");
			 jQuery(this).addClass("active");
			 jQuery("#wpgmp_dir_tab"+map_id+"").hide();
			 jQuery("#wpgmp_nearby_tab"+map_id+"").hide();
			 jQuery("#wpgmp_cat_tab"+map_id+"").show();
		 });
		 
		 jQuery(".wpgmp-tab-3").click(function() {
			
			 map_id=$(this).attr("rel");
			 jQuery(".wpgmp-tab-1[rel='"+map_id+"']").removeClass("active");
			 jQuery(".wpgmp-tab-2[rel='"+map_id+"']").removeClass("active");
			 jQuery(this).addClass("active");
			 jQuery("#wpgmp_cat_tab"+map_id+"").hide();
			 jQuery("#wpgmp_dir_tab"+map_id+"").hide();
			 jQuery("#wpgmp_nearby_tab"+map_id+"").show();
			 
		 });
		 
		 
		 
		 jQuery(".wpgmp_mcurrent_loction").click(function() {
				wpgmp_get_current_location();
		 });
		 
		 
		
	});

 	
	function wpgmp_get_current_location()
	{
		if (navigator.geolocation) {
		  navigator.geolocation.getCurrentPosition(wpgmp_success, wpgmp_error);
		} else {
		alert("Geolocation not supported. Please check your location permissions");
		}
	}
	
	function wpgmp_success(position) {
		myLat = position.coords.latitude;
		myLng = position.coords.longitude;
		var mylatlng = new google.maps.LatLng(myLat, myLng);
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({"latLng": mylatlng}, function(results, status) {
			if(status == google.maps.GeocoderStatus.OK) {
				current_address = results[0]["formatted_address"];
				jQuery(".wpgmp_set_current_location").html(current_address);
				jQuery(".fromloc").val(current_address);
			
			};
			
		});
	}
	
	function wpgmp_error(msg) {
		 alert("Geolocation not supported. Please check your location permissions");
	}

function wpgmp_filter_nearby_locations(map_id,location_ids)
{

if(location_ids=="")
location_ids=0;

var data = {
		action: "wpgmp_get_map_location",
		map_id: map_id,
		location_ids: location_ids
	};

	jQuery.post(wpgmp_ajaxurl, data, function(response) {
	jQuery("#wpgmp_locations_listing"+map_id).html(response);
	jQuery("#wpgmp_locations_listing"+map_id).removeClass("wpgmp_loading");
	});
	

}

function wpgmp_filter_locations(map_id,page)
{

var filter_category = "";
search_term=jQuery(".wpgmp_search_input[rel='"+map_id+"']").val();	

map_id=jQuery("#wpgmp_locations_listing"+map_id).attr('rel');

//get checked categories here

all_checked=jQuery(".wpgmp_specific_category_location:checkbox:checked");
	
	if(all_checked.length!=0)
	{
	var selected_categories=new Array();
	
	jQuery(all_checked).each(function(){
	
	selected_categories.push(this.value);
	
	});

	filter_category=selected_categories.join(",");
}



var data = {
		action: "wpgmp_get_map_location",
		map_id: map_id,
		search_text: search_term,
		filter_category:filter_category,
		 page : page
	};

	jQuery.post(wpgmp_ajaxurl, data, function(response) {
	jQuery("#wpgmp_locations_listing"+map_id).html(response);
	jQuery("#wpgmp_locations_listing"+map_id).removeClass("wpgmp_loading");
	});


}


function wpgmp_sort_distance(obj) {
    var arr = [];
    for (var prop in obj) {
        if (obj.hasOwnProperty(prop)) {
            arr.push({
                "key": prop,
                "value": obj[prop]
            });
        }
    }
    arr.sort(function(a, b) { return a.value - b.value; });
    //arr.sort(function(a, b) { a.value.toLowerCase().localeCompare(b.value.toLowerCase()); }); //use this to sort as strings
    return arr; // returns array
}

function wpgmp_nearby_locations(markers,map_id,map_div_id)
{
	radius=jQuery("#wpgmp_radius"+map_id).val();
	
	navigator.geolocation.getCurrentPosition(function(position) {
			
				var lat1 =  position.coords.latitude;
				var lon1 =  position.coords.longitude;
			    var location_ids=new Array();
				
				
				var near_locations="<div class=\"scroll-pane\" style=\"height: 97px; width:100%;\">"+
				"<ul class=\"wpgmp_location_container\">";
				
				
				marker_distances= wpgmp_get_nearby_locations(markers,lat1,lon1,radius,map_div_id); 
			
				if(marker_distances.length!=0)
				{
					
				
				for(i=0;i<marker_distances.length;i++)
				{
					marker_key=marker_distances[i].key;
					marker_id=marker_key.match(/[0-9]+/);
					
					if(typeof marker_id != "undefined") 
				  	location_ids.push(marker_id);
				  	
				  	near_locations+="<li class=\"wpgmp_nearby_all_locations\"><span><a href=\"javascript:open_current_location("+marker_distances[i].key+");\">"+eval(marker_distances[i].key).getTitle()+"</a></span><span class=\"wpgmp_nearby_distance\">"+Math.round(marker_distances[i].value/1.6)+" Miles</span></li>";
				}
				
				}
				else
				{
					
					near_locations+="<li class=\"wpgmp_nearby_all_locations\"><span>No Locations Found within "+radius+" miles.</a></span></li>";
					
				}
				near_locations+="</ul>";
                 jQuery("#locations-panel"+map_id).html(near_locations).show();
			
			location_ids=location_ids.join(",");
			wpgmp_filter_nearby_locations(map_id,location_ids);
					
		});
}

function wpgmp_get_nearby_locations(markers,lat1,lon1,radius,div_id)
				{
			
				var radius_km = 6371; 
				
				max_radius_km=radius*1.61;
				
				var distances = [];
				var closest = -1;
				var pi = Math.PI;
				var marker_info = {};
					
				for( i=0;i<markers.length; i++ ) {
				
					var lat2 = markers[i].lat;
					var lon2 = markers[i].lng;

					var chLat = lat2-lat1;
					var chLon = lon2-lon1;

					var dLat = chLat*(pi/180);
					var dLon = chLon*(pi/180);

					var rLat1 = lat1*(pi/180);
					var rLat2 = lat2*(pi/180);

					var a = Math.sin(dLat/2) * Math.sin(dLat/2) + 
					Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(rLat1) * Math.cos(rLat2); 
					var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
					var d = radius_km * c;
					
					if( max_radius_km!=0 )
					{
						if(d<=max_radius_km)
						{
						distances[d] = markers[i];
					    distances.sort(function(a,b){return a-b});
					    marker_info["marker"+markers[i].location_id+div_id] = d;
					    }
					}
					else
					{
						distances[d] = markers[i];
					    distances.sort(function(a,b){return a-b});
					    marker_info["marker"+markers[i].location_id+div_id] = d;
						
					}
				}
				
				sortest_markers=wpgmp_sort_distance(marker_info);
				
				
				return sortest_markers;
				
				}
	
				
function open_current_location(newmarker)
{
	google.maps.event.trigger(newmarker, "click");
}

function wpgmp_get_selected_travel_mode() {
		wpgmp_travel_mode_input = document.getElementById("tmode");
		var value = wpgmp_travel_mode_input.options[wpgmp_travel_mode_input.selectedIndex].value;
		value = value.toUpperCase();
		if (value == "DRIVING") {
		  value = google.maps.DirectionsTravelMode.DRIVING;
		} else if (value == "BICYCLING") {
		  value = google.maps.DirectionsTravelMode.BICYCLING;
		} else if (value == "WALKING") {
		  value = google.maps.DirectionsTravelMode.WALKING;
		} else if (value == "TRANSIT") {
		  value = google.maps.DirectionsTravelMode.TRANSIT;
		} else {
		  alert("Unsupported travel mode.");
		  value = google.maps.DirectionsTravelMode.DRIVING;
		}
		return value;
    }
