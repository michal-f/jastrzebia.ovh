<?php
	$social_array = array();
	$social_count = 0;
	foreach ($this->TS_VCSC_Social_Networks_Array as $Social_Network => $social) {
		$social_lines = array(
			'network' 					=> $Social_Network,
			'class'						=> $social['class'],
			'link'						=> get_option('ts_vcsc_social_link_' . $Social_Network, 	''),
			'order' 					=> get_option('ts_vcsc_social_order_' . $Social_Network, 	$social['order']),
			'original'					=> $social['order']
		);
		$social_array[] = $social_lines;
		$social_count = $social_count + 1;
	}
?>

<div id="ts-settings-social" class="tab-content">
    <h2>Social Network Links</h2>
    <p>These settings will be used as global settings for all social network buttons used in the element "TS Social Networks". You can drag and drop each network to change the order in which the network buttons will be shown on your website.</p>

    <p>
        <h4>Social Network Links:</h4>
		
		<div id="ts-vcsc-social-network-links-restore" class="button-secondary" style="width: 120px; margin-top: 20px; text-align: center;"><img src="<?php echo TS_VCSC_GetResourceURL('images/other/ts_vcsc_sortalpha_icon_16x16.png'); ?>" style="width: 16px; height: 16px; margin-right: 10px;">Restore</div>
	</p>
	
	<?php
		TS_VCSC_SortMultiArray($social_array, 'order');

		// Output Array Input Fields
		$social_networks = '';
		$social_networks .= '<ul id="ts-vcsc-social-network-links" class="ts-social-icons">';
		foreach ($social_array as $index => $array) {
			$Social_Network 	= $social_array[$index]['network'];
			$Social_Class 		= $social_array[$index]['class'];
			$Social_Order		= $social_array[$index]['order'];
			$Social_Link		= $social_array[$index]['link'];
			$Social_Original	= $social_array[$index]['original'];
			$social_networks .= '<li style="display: inline-block; width: 100%; margin: 5px 0px;" data-order="' . $Social_Order . '" data-network="' . $Social_Network . '" data-original="' . $Social_Original . '">';
				$social_networks .= '<div style="width: 150px; float: left;"><span style="width: 20px;"><i class="' . $Social_Class . '"></i></span><label style="margin-left: 10px;" class="Uniform" for="ts_vcsc_social_link_' . $Social_Network . '">' . ucwords($Social_Network) . ':</label></div>';
				if ($Social_Network == "Email") {
					$social_networks .= '<input class="validate[custom[email]]" data-error="Social Network Links - ' . ucwords($Social_Network) . '" data-order="1" type="text" style="width: 20%;" id="ts_vcsc_social_link_' . $Social_Network . '" name="ts_vcsc_social_link_' . $Social_Network . '" value="'.  $Social_Link . '" size="100">';
				} else if (($Social_Network == "Phone") || ($Social_Network == "Cell")) {
					$social_networks .= '<input class="validate[custom[phone]]" data-error="Social Network Links - ' . ucwords($Social_Network) . '" data-order="1" type="text" style="width: 20%;" id="ts_vcsc_social_link_' . $Social_Network . '" name="ts_vcsc_social_link_' . $Social_Network . '" value="'.  $Social_Link . '" size="100">';
				} else if ($Social_Network == "Skype") {
					$social_networks .= '<input class="validate[custom[onlyLetterNumber]]" data-error="Social Network Links - ' . ucwords($Social_Network) . '" data-order="1" type="text" style="width: 20%;" id="ts_vcsc_social_link_' . $Social_Network . '" name="ts_vcsc_social_link_' . $Social_Network . '" value="'.  $Social_Link . '" size="100">';
				
				} else {
					$social_networks .= '<input class="validate[custom[url]]" data-error="Social Network Links - ' . ucwords($Social_Network) . '" data-order="1" type="text" style="width: 20%;" id="ts_vcsc_social_link_' . $Social_Network . '" name="ts_vcsc_social_link_' . $Social_Network . '" value="'.  $Social_Link . '" size="100">';
				}
				$social_networks .= '<input type="hidden" id="ts_vcsc_social_order_' . $Social_Network . '" name="ts_vcsc_social_order_' . $Social_Network . '" value="'.  $Social_Order . '" size="100">';
			$social_networks .= '</li>';
		}
		
		$social_networks .= '</ul>';
		echo $social_networks;
	?>
</div>
