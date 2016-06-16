<?php
	if (function_exists('vc_add_param')) {
		// Column Setting Parameters
		vc_add_param("vc_column", array(
			"type"              		=> "seperator",
			"heading"           		=> __( "", "ts_visual_composer_extend" ),
			"param_name"        		=> "seperator_1",
			"value"             		=> "Viewport Animation",
			"description"       		=> __( "", "ts_visual_composer_extend" ),
			"group" 					=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_column", array(
			"type" 						=> "css3animations",
			"class" 					=> "",
			"heading" 					=> __("Viewport Animation", "ts_visual_composer_extend"),
			"param_name" 				=> "animation_view",
			"standard"					=> "false",
			"prefix"					=> "",
			"connector"					=> "css3animations_in",
			"noneselect"				=> "true",
			"default"					=> "",
			"value" 					=> "",
			"admin_label"				=> false,
			"description" 				=> __("Select a Viewport Animation for this Column.", "ts_visual_composer_extend"),
			"group" 					=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_column", array(
			"type"                      => "hidden_input",
			"heading"                   => __( "Animation Type", "ts_visual_composer_extend" ),
			"param_name"                => "css3animations_in",
			"value"                     => "",
			"admin_label"		        => true,
			"description"               => __( "", "ts_visual_composer_extend" ),
			"group" 					=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_column", array(
			"type"						=> "switch",
			"heading"           		=> __( "Repeat Effect", "ts_visual_composer_extend" ),
			"param_name"        		=> "animation_scroll",
			"value"             		=> "false",
			"on"						=> __( 'Yes', "ts_visual_composer_extend" ),
			"off"						=> __( 'No', "ts_visual_composer_extend" ),
			"style"						=> "select",
			"design"					=> "toggle-light",
			"description"       		=> __( "Switch the toggle to repeat the viewport effect when element has come out of view and comes back into viewport.", "ts_visual_composer_extend" ),
			"dependency" 				=> array("element" => "animation_view", "not_empty" => true),
			"group" 					=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_column", array(
			"type"                  	=> "nouislider",
			"heading"               	=> __( "Animation Speed", "ts_visual_composer_extend" ),
			"param_name"            	=> "animation_speed",
			"value"                 	=> "2000",
			"min"                   	=> "1000",
			"max"                   	=> "5000",
			"step"                  	=> "100",
			"unit"                  	=> 'ms',
			"description"           	=> __( "Define the Length of the Viewport Animation in ms.", "ts_visual_composer_extend" ),
			"dependency" 				=> array("element" => "animation_view", "not_empty" => true),
			"group" 					=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_column", array(
			"type"                  	=> "load_file",
			"class" 					=> "",
			"heading"               	=> __( "", "ts_visual_composer_extend" ),
			"param_name"            	=> "el_file1",
			"value"                 	=> "",
			"file_type"             	=> "js",
			"file_path"             	=> "js/ts-visual-composer-extend-element.min.js",
			"description"           	=> __( "", "ts_visual_composer_extend" ),
			"group" 					=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_column", array(
			"type"              		=> "load_file",
			"class" 					=> "",
			"heading"           		=> __( "", "ts_visual_composer_extend" ),
			"param_name"        		=> "el_file2",
			"value"             		=> "",
			"file_type"         		=> "css",
			"file_id"         			=> "ts-extend-animations",
			"file_path"         		=> "css/ts-visual-composer-extend-animations.min.css",
			"description"       		=> __( "", "ts_visual_composer_extend" )
		));
	}
	
	add_filter('TS_VCSC_ComposerColumnAdditions_Filter',	'TS_VCSC_ComposerColumnAdditions', 		10, 2);

	function TS_VCSC_ComposerColumnAdditions($output, $atts, $content='') {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();
		
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndWaypoints == "true") {
			if (wp_script_is('waypoints', $list = 'registered')) {
				wp_enqueue_script('waypoints');
			} else {
				wp_enqueue_script('ts-extend-waypoints');
			}
		}
		
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
			wp_enqueue_style('ts-extend-animations');
			wp_enqueue_style('ts-visual-composer-extend-front');
			wp_enqueue_script('ts-visual-composer-extend-front');
		}
		
		extract( shortcode_atts( array(
			'animation_factor'			=> '0.33',
			'animation_scroll'			=> 'false',
			'animation_view'			=> '',
			'animation_speed'			=> 2000,
		), $atts ) );
		
		if ($animation_view != '') {
			$animation_css			= "ts-viewport-css-" . $animation_view;
			echo '<div class="ts-viewport-column ts-viewport-animation" data-scrollup = "' . $animation_scroll . '" data-factor="' . $animation_factor . '" data-viewport="' . $animation_css . '" data-speed="' . $animation_speed . '"></div>';
		} else {
			echo '';
		}
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}

	
	if (!function_exists('vc_theme_before_vc_column')){
		function vc_theme_before_vc_column($atts, $content = null){
			return apply_filters( 'TS_VCSC_ComposerColumnAdditions_Filter', '', $atts, $content );
		}
	}
?>