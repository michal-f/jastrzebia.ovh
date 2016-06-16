<?php
function wpgmp_validate_extension($file_name) {
	
    $ext_array = array(".xml",".csv",".xls",".json");
    $extension = strtolower(strrchr($file_name,"."));
    $ext_count = count($ext_array);
    if ( !$file_name ) {
        return false;
    } else {
        if (!$ext_array) {
            return true;
        } else {
            foreach ($ext_array as $value) {
                $first_char = substr($value,0,1);
                    if ($first_char <> ".") {
                        $extensions[] = ".".strtolower($value);
                    } else {
                        $extensions[] = strtolower($value);
                    }
            }
            foreach ($extensions as $value) {
                if ( $value == $extension ) {
                    $valid_extension = "TRUE";
                }
            }
            if ( $valid_extension ) {
                return true;
            } else {
                return false;
            }
        }
    }
}
/**
 * This function used to imort and export the locations and maps in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_xml2array($xml)
{
    $arr = array();
 
    foreach ($xml->children() as $r)
    {
        $t = array();
        if(count($r->children()) == 0)
        {
            $arr[$r->getName()] = strval($r);
        }
        else
        {
            $arr[$r->getName()][] = wpgmp_xml2array($r);
        }
    }
    return $arr;
}
function wpgmp_import_locations()
{
global $wpdb; 
if( isset($_POST['import_loc']) && $_POST['import_loc']=="Import Locations" )
{
if( $_FILES['import_file']['tmp_name']=='' )
{
	$error[] = __( 'Please select file to be imported.', 'wpgmp_google_map' );
}
elseif( !wpgmp_validate_extension($_FILES['import_file']['name']) )
{
	$error[] = __( 'Please upload a valid file', 'wpgmp_google_map' );
}
else
{
	 $file_extension = explode('.',$_FILES['import_file']['name']);
	 
	 $locations_table = $wpdb->prefix."map_locations";
	 
	 if( $file_extension[1]=='xml' )
	 {
		$xml_locations = simplexml_load_file($_FILES['import_file']['tmp_name']);
		$multi_array = wpgmp_xml2array ($xml_locations);
 
		foreach($multi_array['location'] as $all_locations)
		{
		   $query = "SELECT * FROM ".$wpdb->prefix."map_locations WHERE location_latitude='".$all_locations['location_latitude']."' AND location_longitude='".$all_locations['location_longitude']."'";
		     
		   $xml_import = $wpdb->get_row($wpdb->prepare($query,NULL));
		  
		   $cat_id = $wpdb->get_row('SELECT group_map_id FROM '.$wpdb->prefix.'group_map WHERE group_map_title="'.$all_locations['marker_category_title'].'"');
		   
		   if($cat_id->group_map_id=='' and $all_locations['marker_category_title']!='')
		   {
			 $wpdb->insert($wpdb->prefix.'group_map',array('group_map_title'=>$all_locations['marker_category_title'])); 			
				$group_id=$wpdb->insert_id;
		   }
		   elseif($cat_id->group_map_id!='')
		   $group_id=$cat_id->group_map_id;
		   else
		   $group_id=0;	 
		   
		   if( empty($xml_import) )
		   {
		 
			   $save_locations = array(
			  'title' => $all_locations['title'],
			  'address' => $all_locations['address'],
			  'draggable' => $all_locations['draggable'],
			  'disable_infowindow' =>$all_locations['disable_infowindow'],
			  'latitude' => $all_locations['latitude'],
			  'longitude' => $all_locations['longitude'],
			  'message' => $all_locations['message'],
			  'marker_category_id' => $group_id
			  );
			  wpgmp_import_save_locations($save_locations);
			  
			  
		   }
	    }
	 }
	 elseif( $file_extension[1]=='csv' )
	 {
		 ini_set('auto_detect_line_endings', true);
		$row = 1;
		$csv_data = array();
		if (($handle = fopen($_FILES['import_file']['tmp_name'], "r")) !== FALSE) {
			while (($data = fgetcsv($handle)) !== FALSE) {
				$num = count($data);
				$row++;
				for ($c=0; $c < $num; $c++) {
					$data[$c] . "<br />\n";
				}
				$csv_data[] = $data;
			}
			fclose($handle);
		}
		for($i=1; $i<count($csv_data); $i++)
		{
		   $query = "SELECT * FROM ".$wpdb->prefix."map_locations WHERE location_latitude='".$csv_data[$i][4]."' AND location_longitude='".$csv_data[$i][5]."'";
		   $csv_import = $wpdb->get_row($wpdb->prepare($query,NULL));
		   
		    $cat_id = $wpdb->get_row('SELECT group_map_id FROM '.$wpdb->prefix.'group_map WHERE group_map_title="'.$csv_data[$i][7].'"');
		    
		     if($cat_id->group_map_id=='' and $csv_data[$i][7]!='')
		   {
			  
			 $wpdb->insert($wpdb->prefix.'group_map',array('group_map_title'=>$csv_data[$i][7])); 			
				$group_id=$wpdb->insert_id;
		   }
		  elseif($cat_id->group_map_id!='')
		   $group_id=$cat_id->group_map_id;
		   else
		   $group_id=0;	 
		   
		   if( empty($csv_import) )
		   {
			  
			   $save_locations = array(
			  'title' => $csv_data[$i][0],
			  'address' => $csv_data[$i][1],
			  'draggable' => $csv_data[$i][2],
			  'disable_infowindow' => $csv_data[$i][3],
			  'latitude' => $csv_data[$i][4],
			  'longitude' => $csv_data[$i][5],
			  'message' => $csv_data[$i][6],
			  'marker_category_id' => $group_id
			  );
			  
			  wpgmp_import_save_locations($save_locations);
			   
		   }
		}
	 }
	 elseif( $file_extension[1]=='xls' )
	 {
		$xls_data = array();
		$handle = fopen($_FILES['import_file']['tmp_name'], "r");
		if ( $handle )
		{
			$array = explode("\n", fread($handle, filesize($_FILES['import_file']['tmp_name'])));
			for($i=1; $i<count($array); $i++){
				if($array[$i]!='') {
				 $exe_array = explode("\t", $array[$i]);
				 $xls_data[] = $exe_array;
				}
			}
		}
		
		foreach($xls_data as $xls) 
		{
		    
		   $query = "SELECT * FROM ".$wpdb->prefix."map_locations WHERE location_latitude='".$xls[4]."' AND location_longitude='".$xls[5]."'";
		   $csv_import = $wpdb->get_row($wpdb->prepare($query,NULL));
		   $infowindow['googlemap_infowindow_message_one'] = $xls[6];
		   $messages = base64_encode(serialize($infowindow));
 
		  
		   $cat_id = $wpdb->get_row('SELECT group_map_id FROM '.$wpdb->prefix.'group_map WHERE group_map_title="'.$xls[7].'"');
		   
		   if($cat_id->group_map_id=='' and $xls[7]!='')
		   {
			 $wpdb->insert($wpdb->prefix.'group_map',array('group_map_title'=>$xls[7])); 			
			 $group_id=$wpdb->insert_id;
		   }
		  elseif($cat_id->group_map_id!='')
		   $group_id=$cat_id->group_map_id;
		   else
		   $group_id=0;	 
		   	
		   if( empty($csv_import) )
		   {
			  
			  $save_locations = array(
			  'title' => $xls[0],
			  'address' => $xls[1],
			  'draggable' => $xls[2],
			  'disable_infowindow' => $xls[3],
			  'latitude' => $xls[4],
			  'longitude' => $xls[5],
			  'message' => $xls[6],
			  'marker_category_id' => $group_id
			  );
			  
			  wpgmp_import_save_locations($save_locations);
			  
		   }
		}
	 }
	 elseif( $file_extension[1]=='json' )
	 {
		$get_content = file_get_contents($_FILES['import_file']['tmp_name']);
		$json_data = json_decode($get_content);
		foreach($json_data as $key=>$data) {
		  
		   $query = "SELECT * FROM ".$wpdb->prefix."map_locations WHERE location_latitude='".$data->location_latitude."' AND location_longitude='".$data->location_longitude."'";
		   $json_import = $wpdb->get_row($wpdb->prepare($query,NULL));
				
		   $cat_id = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'group_map WHERE group_map_title="'.$data->marker_category_title.'"');	 
		
		  if($cat_id->group_map_id=='' and $data->marker_category_title!='')
		   {
			 $wpdb->insert($wpdb->prefix.'group_map',array('group_map_title'=>$data->marker_category_title)); 			
			 $group_id=$wpdb->insert_id;
		   }
		   elseif($cat_id->group_map_id!='')
		   $group_id=$cat_id->group_map_id;
		   else
		   $group_id=0;	 
				
		   if( empty($json_import) )
		   {
				
			  $save_locations = array(
			  'title' => $data->title,
			  'address' => $data->address,
			  'draggable' => $data->draggable,
			  'disable_infowindow' => $data->disable_infowindow,
			  'latitude' => $data->latitude,
			  'longitude' => $data->longitude,
			  'message' => $data->message,
			  'marker_category_id' => $group_id
			  );
			
			
			 wpgmp_import_save_locations($save_locations);
		   }
		} 
	 }
	$success = __( 'Locations imported successfully. You can manage your locations <a href="'.admin_url('admin.php?page=wpgmp_manage_location').'">here</a>.', 'wpgmp_google_map' );
}
}
?>
<div class="wpgmp-wrap">  
<div class="col-md-11">  
<div id="icon-options-general" class="icon32"><br></div>
<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('Import Locations', 'wpgmp_google_map') ?></h3>
<div class="wpgmp-overview">
<?php
if( !empty($error) )
{
	$error_msg = implode('<br>',$error);
	
	wpgmp_showMessage($error_msg,true);
}
if( !empty($success) )
{
    
    wpgmp_showMessage($success);
}
?>
<p><?php _e('You can use xml,csv,excel or json file to be imported here. Best approach is to export a file, do changed and import it back. You can export it <a href="'.admin_url('admin.php?page=wpgmp_manage_location').'">here</a>. ', 'wpgmp_google_map')?> </p>
<br />
<div class="row">
<form id="import_form" method="post" enctype="multipart/form-data" >
<div class="form-horizontal col-md-8">
    
<input type="hidden" name="import" id="import" value="location_import"  />
<div class="col-md-8">
<input type="file" name="import_file" style="background:#1e8cbe; " />
</div><div class="col-md-2">
<input type="submit" name="import_loc" id="import_loc" value="Import Locations"  class="btn btn-sm btn-primary"/>
</div></div>
</form>
</div>
</div>
</div></div>
<?php	
}
?>
