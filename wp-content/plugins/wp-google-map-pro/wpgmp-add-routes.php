<?php
/**
 * This function used to add multiple routes.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_add_routes()
{ 

global $wpdb;

if( isset($_POST['submit']) && $_POST['submit']=="Save Route" )
{

	if( empty($_POST['route_title']) )
	{
		$error[]= __( 'Please enter route title.', 'wpgmp_google_map' );
	}

	if( empty($_POST['route_marker_draggable']) ) 
	{
		$_POST['route_marker_draggable'] = 'false';
	}
	else
	{
		$_POST['route_marker_draggable'] = $_POST['route_marker_draggable'];
	}

	if( empty($_POST['route_custom_marker']) ) 
	{
		$_POST['route_custom_marker'] = 'false';
	}
	else
	{
		$_POST['route_custom_marker'] = $_POST['route_custom_marker'];
	}

	if( empty($_POST['route_optimize_waypoints']) ) 
	{
		$_POST['route_optimize_waypoints'] = 'false';
	}
	else
	{
		$_POST['route_optimize_waypoints'] = $_POST['route_optimize_waypoints'];
	}

	if( empty($error) ) 
	{		
		$map_routes_table=$wpdb->prefix."map_routes";
		$map_routes_data = array(
		'route_title' 				=> htmlspecialchars(stripslashes($_POST['route_title'])),
		'route_stroke_color' 		=> htmlspecialchars(stripslashes($_POST['route_stroke_color'])),
		'route_stroke_opacity' 		=> htmlspecialchars(stripslashes($_POST['route_stroke_opacity'])),
		'route_stroke_weight' 		=> htmlspecialchars(stripslashes($_POST['route_stroke_weight'])),
		'route_travel_mode' 		=> htmlspecialchars(stripslashes($_POST['route_travel_mode'])),
		'route_unit_system'			=> htmlspecialchars(stripslashes($_POST['route_unit_system'])),
		'route_marker_draggable'	=> htmlspecialchars(stripslashes($_POST['route_marker_draggable'])),
		'route_custom_marker'		=> htmlspecialchars(stripslashes($_POST['route_custom_marker'])),
		'route_optimize_waypoints'	=> htmlspecialchars(stripslashes($_POST['route_optimize_waypoints'])),
		'route_start_location' 		=> htmlspecialchars(stripslashes($_POST['route_start_location'])),
		'route_end_location' 		=> htmlspecialchars(stripslashes($_POST['route_end_location'])),
		'route_way_points'			=> serialize($_POST['route_way_points'])
		);

		$wpdb->insert($map_routes_table,$map_routes_data);
		$success = __( 'Route added successfully.', 'wpgmp_google_map' );
		$_POST = array();
	}	
}	

$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."map_locations");
$group_results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."group_map");
?>

<script>   
jQuery(document).ready(function($) {
    $('.filter_location').change(function () {
		location_value=$(this).val();
	    if(location_value>0)		
	    {
			$('li[class^="group"]').hide();
	        $('li[class="group'+$(this).val()+'"]').show();
	    }
	    else
	    {
	    	$('li[class^="group"]').show();
	    }
	}); 
});	   
</script>

<div class="wpgmp-wrap"> 
	<div class="col-md-11">  
		<div id="icon-options-general" class="icon32"><br/></div>
			<h3>
				<span class="glyphicon glyphicon-asterisk"></span><?php _e('Add Route', 'wpgmp_google_map')?>
			</h3> 
			<div class="wpgmp-overview">
				<?php
				if( !empty($error) )
				{
					$error_msg=implode('<br>',$error);
					
					wpgmp_showMessage($error_msg,true);
				}
				if( !empty($success) )
				{
				    wpgmp_showMessage($success);
				}
				?>
				<form method="post">
					<div class="form-horizontal">

						<div class="row">
			                <div class="col-md-3">
			                  	<label for="title">Route Title <span style="color:red;">*<span></label>
			                </div>
			                <div class="col-md-7">
			                   	<input type="text" name="route_title" value="<?php if(!empty($_POST['route_title'])) { echo $_POST['route_title']; } ?>" />
			                    <p class="description">Please enter route title.</p>
			                </div>
			            </div>

			            <div class="row">
			                <div class="col-md-3">
			                  	<label for="title">Stroke Color</label>
			                </div>
			                <div class="col-md-7">
			                	<?php if(empty($_POST['route_stroke_color'])) { ?>
			                   		<input type="text" value="8CAEF2" name="route_stroke_color" class="color {pickerClosable:true}" />
			                    <?php } else { ?>
			                    	<input type="text" value="<?php echo $_POST['route_stroke_color']; ?>" name="route_stroke_color" class="color {pickerClosable:true}" />
			                    <?php } ?>
			                    <p class="description">Choose route direction stroke color.(Default is Blue)</p>
			                </div>
			            </div>

			            <div class="row">
			                <div class="col-md-3">
			                  <label for="title">Stroke Opacity</label>
			                </div>
			                <div class="col-md-7">
			                  <select name="route_stroke_opacity">	
			                    <option value="1"<?php if(!empty($_POST['route_stroke_opacity'])) { echo selected($_POST['route_stroke_opacity'],1); } ?>>1</option>
			                    <option value="0.9"<?php if(!empty($_POST['route_stroke_opacity'])) { echo selected($_POST['route_stroke_opacity'],0.9); } ?>>0.9</option>
			                    <option value="0.8"<?php if(!empty($_POST['route_stroke_opacity'])) { echo selected($_POST['route_stroke_opacity'],0.8); } ?>>0.8</option>
			                    <option value="0.7"<?php if(!empty($_POST['route_stroke_opacity'])) { echo selected($_POST['route_stroke_opacity'],0.7); } ?>>0.7</option>
			                    <option value="0.6"<?php if(!empty($_POST['route_stroke_opacity'])) { echo selected($_POST['route_stroke_opacity'],0.6); } ?>>0.6</option>
			                    <option value="0.5"<?php if(!empty($_POST['route_stroke_opacity'])) { echo selected($_POST['route_stroke_opacity'],0.5); } ?>>0.5</option>
			                    <option value="0.4"<?php if(!empty($_POST['route_stroke_opacity'])) { echo selected($_POST['route_stroke_opacity'],0.4); } ?>>0.4</option>
			                    <option value="0.3"<?php if(!empty($_POST['route_stroke_opacity'])) { echo selected($_POST['route_stroke_opacity'],0.3); } ?>>0.3</option>
			                    <option value="0.2"<?php if(!empty($_POST['route_stroke_opacity'])) { echo selected($_POST['route_stroke_opacity'],0.2); } ?>>0.2</option>
			                    <option value="0.1"<?php if(!empty($_POST['route_stroke_opacity'])) { echo selected($_POST['route_stroke_opacity'],0.1); } ?>>0.1</option>
			                  </select>
			                  <p class="description">Please select route direction stroke opacity.</p>
			                </div>
			            </div>

			            <div class="row">
			                <div class="col-md-3">
			                  <label for="title">Stroke Weight</label>
			                </div>
			                <div class="col-md-7">
			                	
			                  <select name="route_stroke_weight">
			                  	<?php 
			                  	for ($sw=10; $sw>=1; $sw--)
			                  	{ 
			                  		?>
			                  		<option value="<?php echo $sw; ?>"<?php if(!empty($_POST['route_stroke_weight'])) { echo selected($_POST['route_stroke_weight'],$sw); } ?>><?php echo $sw; ?></option>
									<?php
								} 
								?>
			                   </select>
			                  <p class="description">Please select route direction stroke weight.</p>
			                </div>
			            </div>

			            <div class="row">
			                <div class="col-md-3">
			                  <label for="title">Travel Modes</label>
			                </div>
			                <div class="col-md-7">
			                  <select name="route_travel_mode">
			                    <option value="DRIVING"<?php if(!empty($_POST['route_travel_mode'])) { echo selected($_POST['route_travel_mode'],'DRIVING'); } ?>>DRIVING</option>
			                    <option value="WALKING"<?php if(!empty($_POST['route_travel_mode'])) { echo selected($_POST['route_travel_mode'],'WALKING'); } ?>>WALKING</option>
			                    <option value="BICYCLING"<?php if(!empty($_POST['route_travel_mode'])) { echo selected($_POST['route_travel_mode'],'BICYCLING'); } ?>>BICYCLING</option>
			                    <option value="TRANSIT"<?php if(!empty($_POST['route_travel_mode'])) { echo selected($_POST['route_travel_mode'],'TRANSIT'); } ?>>TRANSIT</option>
			                  </select>
			                  <p class="description">Please select travel mode.</p>
			                </div>
			            </div>

			            <div class="row">
			                <div class="col-md-3">
			                  <label for="title">Unit Systems</label>
			                </div>
			                <div class="col-md-7">
			                  <select name="route_unit_system">
			                    <option value="METRIC"<?php if(!empty($_POST['route_unit_system'])) { echo selected($_POST['route_unit_system'],'METRIC'); } ?>>METRIC</option>
			                    <option value="IMPERIAL"<?php if(!empty($_POST['route_unit_system'])) { echo selected($_POST['route_unit_system'],'IMPERIAL'); } ?>>IMPERIAL</option>
			                  </select>
			                  <p class="description">Please select travel mode.</p>
			                </div>
			            </div>

			            <div class="row">
			              <div class="col-md-3">
			                <label for="title">Draggable</label>
			              </div>
			              <div class="col-md-7">
			              	<p class="description">
			              	<input type="checkbox" name="route_marker_draggable" value="true"<?php if(!empty($_POST['route_marker_draggable'])) { checked($_POST['route_marker_draggable'],'true'); } ?> />
			                Please check to enable marker draggable.
			            	</p>
			              </div>
			            </div>

			            <div class="row">
			              <div class="col-md-3">
			                <label for="title">Display Custom Marker</label>
			              </div>
			              <div class="col-md-7">
			              	<p class="description">
			              	<input type="checkbox" name="route_custom_marker" value="true" checked="checked" />
			                Please check to display custom markers.
			            	</p>
			              </div>
			            </div>

			            <div class="row">
			              <div class="col-md-3">
			                <label for="title">Optimize Waypoints</label>
			              </div>
			              <div class="col-md-7">
			              	<p class="description">
			              	<input type="checkbox" name="route_optimize_waypoints" value="true"<?php if(!empty($_POST['route_optimize_waypoints'])) { checked($_POST['route_optimize_waypoints'],'true'); } ?> />
			                Please check to enable optimize waypoints.
			            	</p>
			              </div>
			            </div>

					    <div class="row">
				            <div class="col-md-3">
			                  <label for="title">Start Location</label>
			                </div>  
          					<div class="col-md-7">
          						<?php
								echo '<select name="route_start_location">';

								if( !empty($results) )
								{
							        for($i = 0; $i < count($results); $i++)
									{
										?>
										
			                    		<option value="<?php echo $results[$i]->location_id; ?>"<?php if(!empty($_POST['route_start_location'])) { echo selected($_POST['route_start_location'],$results[$i]->location_id); } ?>><?php echo $results[$i]->location_title; ?></option>
			                  	
							        	<?php   
             						}
					            }
					            else
					            {
        						?>
					              Seems you don\'t have any location right now &nbsp; <a href="<?php echo admin_url('admin.php?page=wpgmp_add_location') ?>"> Click here </a> &nbsp; to add a location now.
					            <?php  
								}

								echo '</select>';
								?>
								<p class="description">Please select start location.</p>
          					</div>	
           				</div>

           				<div class="row">
				            <div class="col-md-3">
			                  <label for="title">End Location</label>
			                </div>  
          					<div class="col-md-7">
          						<?php
								echo '<select name="route_end_location">';

								if( !empty($results) )
								{
							        for($i = 0; $i < count($results); $i++)
									{
										?>
										
			                    		<option value="<?php echo $results[$i]->location_id; ?>"<?php if(!empty($_POST['route_end_location'])) { echo selected($_POST['route_end_location'],$results[$i]->location_id); } ?>><?php echo $results[$i]->location_title; ?></option>
			                  	
							        	<?php   
             						}
					            }
					            else
					            {
        						?>
					              Seems you don\'t have any location right now &nbsp; <a href="<?php echo admin_url('admin.php?page=wpgmp_add_location') ?>"> Click here </a> &nbsp; to add a location now.
					            <?php  
								}

								echo '</select>';
								?>
								<p class="description">Please select end location.</p>
          					</div>	
           				</div>


						<fieldset>
						    <legend>
						    	<?php _e('Way Points', 'wpgmp_google_map')?>
						    	<span style="float:right;">Filter : 
						    	<select name="filter_location" class="filter_location">  
						        	<option value="">Select group</option>
								    <?php
								    if( !empty($group_results) )
							    	{
							        	for($i = 0; $i < count($group_results); $i++)
							        	{
								    ?>
						        		<option value="<?php echo $group_results[$i]->group_map_id; ?>"<?php if(!empty($_POST['location_group_map'])) { selected($group_results[$i]->group_map_id,$_POST['location_group_map']); } ?>><?php echo $group_results[$i]->group_map_title; ?></option>
								    <?php
								    	}
								    }	
								    ?>
								</select>
							</legend>

	           				<div class="row">
	          					<div class="col-md-12">
	          						<?php
									echo '<ul>';
									if( !empty($results) )
									{
								        for($i = 0; $i < count($results); $i++)
										{
											echo '<li class="group'.$results[$i]->location_group_map.'">';
											if(empty($_POST['route_way_points'])) 
											{	
											?>
													<input type="checkbox" name="route_way_points[]" value="<?php echo $results[$i]->location_id; ?>" />&nbsp;<?php echo $results[$i]->location_title; ?>
								        	<?php
								        	}
								        	else
								        	{
								        		if(in_array($results[$i]->location_id, $_POST['route_way_points']))
								        		{
								        			?>
								        				<input type="checkbox" checked="checked" name="route_way_points[]" value="<?php echo $results[$i]->location_id; ?>" />&nbsp;<?php echo $results[$i]->location_title; ?>
								        			<?php	
								        		}
								        		else
								        		{
								        			?>
								        				<input type="checkbox" name="route_way_points[]" value="<?php echo $results[$i]->location_id; ?>" />&nbsp;<?php echo $results[$i]->location_title; ?>
								        			<?php
								        		}	
								        	} 
								        	echo '</li>';  
	             						}
						            }
						            else
						            {
	        						?>
						              Seems you don\'t have any location right now &nbsp; <a href="<?php echo admin_url('admin.php?page=wpgmp_add_location') ?>"> Click here </a> &nbsp; to add a location now.
						            <?php  
									}
									echo '</ul>';
									?>
	          					</div>	
	           				</div>
						</fieldset>
           				<div class="row">
						    <div class="col-md-3">
						    	<input type="submit" name="submit" id="submit" class="btn btn-lg btn-primary" value="<?php _e('Save Route', 'wpgmp_google_map')?>"/>
						  	</div>
					    </div> 

					</div>
				</form>
		</div>
	</div>
</div>
<?php
}
