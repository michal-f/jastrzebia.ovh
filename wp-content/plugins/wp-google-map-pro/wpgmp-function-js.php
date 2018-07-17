<?php
function wpgmp_function_js()
{
?>
<script type="text/javascript"> 
jQuery(document).ready(function ($) {
	window.send_to_editor_default = window.send_to_editor;
	$('#set-book-image').click(function(){
		// replace the default send_to_editor handler function with our own
		window.send_to_editor = window.attach_image;
		tb_show('', 'media-upload.php?post_id=<?php echo $post->ID ?>&amp;type=image&amp;TB_iframe=true');
		return false;
	});
	
	$('#remove-book-image').click(function() {
		$('#upload_image_id').val('');
		$('img').attr('src', '');
		
		$('#upload_image_url').val('');
		
		$(this).hide();
		return false;
	});
	
	$(".wpgmp-select-all").click(function () {
        if ($(".wpgmp-select-all").is(':checked')) {
            $(".wpgmp-location-checkbox").each(function () {
                $(this).attr("checked", true);
            });

        } else {
            $(".wpgmp-location-checkbox").each(function () {
                $(this).attr("checked", false);
            });
        }
    });
	
	window.attach_image = function(html) {
		$('body').append('<div id="temp_image">' + html + '</div>');
		var img = $('#temp_image').find('img');
		imgurl   = img.attr('src');
		imgclass = img.attr('class');
		imgid    = parseInt(imgclass.replace(/\D/g, ''), 10);
		$('#remove-book-image').show();
		$('img#book_image').attr('src', imgurl);
		$('#upload_image_url').val(imgurl);
		try{tb_remove();}catch(e){};
		$('#temp_image').remove();
		window.send_to_editor = window.send_to_editor_default;
	}
	$('.street_view_toggle').click(function () {                
		$('#disply_street_view').toggle();
	});
	
	$('.route_direction_toggle').click(function () {                
		$('#disply_route_direction').toggle();
	});
	
	$('.info_window_toggle').click(function () {                
		$('#disply_info_window').toggle();
	});
	
	$('.group_map_toggle').click(function () {                
		$('#disply_group_map').toggle();
	});
	
	$('.marker_cluster_toggle').click(function () {                
		$('#disply_marker_cluster').toggle();
	});
	
	$('.overlays_toggle').click(function () {                
		$('#disply_overlays').toggle();
	});
	
	$('.limit_panning_toggle').click(function () {                
		$('#disply_limit_panning').toggle();
	});
	
	$('.marker_category_toggle').click(function () {                
		$('#disply_marker_category_toggle').toggle();
	});
	
	$('.marker_listing_toggle').click(function () {                
		$('#disply_listing_toggle').toggle();
	});
	
	
	$('.drawing_toggle').click(function () {   
		$('#disply_drawing').toggle();
	});
	
	$(".read_icons").click(function (){
	$(".read_icons").removeClass('active');
	$(this).addClass('active');
	});
	
	
});

function send_icon_to_map(imagesrc){
	jQuery('#upload_image_url').val(imagesrc);
	jQuery('img#book_image').attr('src', imagesrc);
	jQuery('#remove-book-image').show();
	jQuery('#temp_image').remove();
	tb_remove();
}

function mylayer(val)
{
	if(document.getElementById('kml_layer').checked)
	{
		document.getElementById('kmldisplay').style.display = '';
	}
	else
	{
		document.getElementById('kmldisplay').style.display = 'none';
	}
	
	if(document.getElementById('fusion_layer').checked)
	{
		document.getElementById('fusiondisplay').style.display = '';
	}
	else
	{
		document.getElementById('fusiondisplay').style.display = 'none';
	}
	
	if(document.getElementById('weather_layer').checked)
	{
		document.getElementById('weatherlayer').style.display = '';
	}
	else
	{
		document.getElementById('weatherlayer').style.display = 'none';
	}
}
</script>
<?php
}
