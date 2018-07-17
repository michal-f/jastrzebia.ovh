<?php
/**
 * This function used to create locations quickly.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_manage_drawing()
{
global $wpdb;
if(isset($_POST['save_shapes']) && $_POST['save_shapes']=='save_shapes')
{
	$map_id=$_POST['map_id'];
	$data['polylines'] = $_POST['shapes_values'];
	
	$map_table=$wpdb->prefix."create_map";
	$infowindow['map_polyline_setting']['shapes'] = serialize($data);
	
	$in_loc_data = array(
	'map_polyline_setting' => $infowindow['map_polyline_setting']['shapes']
	);
	$wpdb->update($map_table,$in_loc_data,array( 'map_id' =>$map_id ));
}

if( !empty($_GET['map_id']) )
{ 
  $map_id = $_GET['map_id'];
  $selected_map = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."create_map where map_id=".$map_id."");
}
?>

<div class="wpgmp-wrap">
  <div class="col-md-11">
    
    <h3><span class="glyphicon glyphicon-asterisk"></span>
      <?php _e('Drawing on Maps', 'wpgmp_google_map' ) ?>
    </h3>
    
    <div class="wpgmp-overview">
    <?php
	$map_records = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."create_map");
	?>
    <form id="load_map" name="load_map">
      <p>
        <label  style="font-weight:bold;">
          <?php _e('Select Your Map:' , 'wpgmp_google_map');?>
        </label>
        <input type="hidden" name="page" id="page" value="wpgmp_google_wpgmp_manage_drawing" />
        <select id="map_id" name="map_id" onchange="document.load_map.submit();";>
          <option value="">
          <?php _e( 'Select Map', 'wpgmp_google_map' ) ?>
          </option>
          <?php foreach($map_records as $key => $map_record){  ?>
          <option value="<?php echo $map_record->map_id; ?>"<?php if(!empty($_GET["map_id"]))  { selected($map_record->map_id,$_GET['map_id']); } ?>><?php echo $map_record->map_title; ?></option>
          <?php } ?>
        </select>
      </p>
    </form>
    <?php if(!empty($map_id) && $map_id>0)
  	{
  	?>
    
    <fieldset>
      <div class="row">
          <form id="save_map" class="save_map" method="post">
            <div class="col-md-4">
              <input type="button" name="save_drawing" id="save_drawing" class="btn btn-primary" value="<?php _e('Save Drawing', 'wpgmp_google_map' ); ?>"/>
              &nbsp;&nbsp;
              <input type="button" class="btn btn-primary" name="reset_drawing" id="reset_drawing" value="<?php _e( 'Reset Drawing', 'wpgmp_google_map' ); ?>"/>
            </div>
            <div class="col-md-8 ">
              <table>
                <tr>
                  <td><?php _e( 'Fill Color:', 'wpgmp_google_map' ); ?></td>
                  <td><input type="text" size="7" value="BAC1FF" id="options_fillcolor" name="options_fillcolor" class="color {pickerClosable:true} " /></td>
                  <td><?php _e( 'Opacity:', 'wpgmp_google_map' ); ?></td>
                  <td><input type="text" size="3" value="1"  id="options_fillopacity"  name="options_fillopacity"   /></td>
                  <td><?php _e( 'Stroke Color:', 'wpgmp_google_map' ); ?></td>
                  <td><input type="text" size="7" value="8C75FF"  id="options_color" name="options_color" class="color {pickerClosable:true} " /></td>
                  <td><?php _e( 'Thickness:', 'wpgmp_google_map' ); ?></td>
                  <td><input type="text" size="2" value="1" id="options_stroke_thickness"  name="options_stroke_thickness"  /></td>
                  <td><input type='button' id='set_pan_tools' class='set_pan_tools' value='Apply'></td>
                </tr>
              </table>
            </div>
            <input type="hidden" name="map_id" id="map_id" value="<?php echo $map_id; ?>" />
            <input type="hidden" name="shapes_values[]" id="shapes_values" value="" />
            <input type="hidden" name="save_shapes" id="save_shapes" value="save_shapes" />
          </form>
      </div>
    </fieldset>
  <?php
  }
  ?>
    <?php
		if(!empty($map_id) && $map_id>0)
		echo do_shortcode("[put_wpgm id=".$map_id."]");
		?>
  </div>
  
  </div>
</div>
<?php
}
?>
