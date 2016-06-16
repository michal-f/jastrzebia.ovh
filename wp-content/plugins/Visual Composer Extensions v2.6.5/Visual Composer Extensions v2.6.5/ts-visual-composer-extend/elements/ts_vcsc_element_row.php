<?php
	if (function_exists('vc_add_param')) {
		// Row Setting Parameters
		vc_add_param("vc_row", array(
			"type"              			=> "seperator",
			"heading"           			=> __( "", "ts_visual_composer_extend" ),
			"param_name"        			=> "seperator_1",
			"value"             			=> "",
			"seperator"             		=> "Background Settings",
			"description"       			=> __( "", "ts_visual_composer_extend" ),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type" 							=> "dropdown",
			"class" 						=> "",
			"heading" 						=> __( "Background Effects", "ts_visual_composer_extend"),
			"param_name" 					=> "ts_row_bg_effects",
			"value" 						=> array(
				__( "None", "ts_visual_composer_extend")					=> "",
				__( "Simple Image", "ts_visual_composer_extend")			=> "image",
				__( "Fixed Image", "ts_visual_composer_extend")				=> "fixed",
				__( "Parallax Image", "ts_visual_composer_extend")			=> "parallax",
				__( "Automove Image", "ts_visual_composer_extend")			=> "automove",
				__( "Single Color", "ts_visual_composer_extend")			=> "single",
				__( "Gradient Color", "ts_visual_composer_extend")			=> "gradient",
				__( "YouTube Video", "ts_visual_composer_extend")			=> "youtube",
				//__( "Selfhosted Video", "ts_visual_composer_extend")		=> "video",
			),
			"admin_label" 					=> true,
			"description" 					=> __("Select the effect you want to apply to the row background.", "ts_visual_composer_extend"),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"							=> "attach_image",
			"heading"						=> __( "Background Image", "ts_visual_composer_extend" ),
			"param_name"					=> "ts_row_bg_image",
			"value"							=> "",
			"description"					=> __( "Select the background image for your row.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("image", "fixed", "parallax", "automove")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"                  		=> "dropdown",
			"heading"               		=> __( "Background Image Source", "ts_visual_composer_extend" ),
			"param_name"            		=> "ts_row_bg_source",
			"width"                 		=> 150,
			"value"                 		=> array(
				__( 'Full Size Image', "ts_visual_composer_extend" )			=> "full",
				__( 'Large Size Image', "ts_visual_composer_extend" )			=> "large",
				__( 'Medium Size Image', "ts_visual_composer_extend" )			=> "medium",
			),
			"description"           		=> __( "Select which image size based on WordPress settings should be used for the lightbox image.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("image", "fixed", "parallax", "automove")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		// Full Width Settings
		vc_add_param("vc_row", array(
			"type"                  		=> "nouislider",
			"heading"               		=> __( "Full Width Breakout", "ts_visual_composer_extend" ),
			"param_name"            		=> "ts_row_break_parents",
			"value"                 		=> "4",
			"min"                   		=> "0",
			"max"                   		=> "99",
			"step"                  		=> "1",
			"unit"                  		=> '',
			"description"           		=> __( "Define the number of Parent Containers the Background should attempt to break away from.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("image", "fixed", "parallax", "gradient", "youtube", "single", "automove")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		// Z-Index
		vc_add_param("vc_row", array(
			"type"                  		=> "nouislider",
			"heading"               		=> __( "Z-Index for Background", "ts_visual_composer_extend" ),
			"param_name"            		=> "ts_row_zindex",
			"value"                 		=> "0",
			"min"                   		=> "-100",
			"max"                   		=> "100",
			"step"                  		=> "1",
			"unit"                  		=> '',
			"description"           		=> __( "Define the z-Index for the background; use only if theme requires an adjustment!", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("image", "fixed", "parallax", "gradient", "youtube", "single", "automove")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		// Min Height Settings
		vc_add_param("vc_row", array(
			"type"                  		=> "nouislider",
			"heading"               		=> __( "Minimum Height", "ts_visual_composer_extend" ),
			"param_name"            		=> "ts_row_min_height",
			"value"                 		=> "100",
			"min"                   		=> "0",
			"max"                   		=> "2048",
			"step"                  		=> "1",
			"unit"                  		=> 'px',
			"description"           		=> __( "Define the minimum height for the row; use only if your theme doesn't provide a similar option and if there is no row content.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("image", "fixed", "parallax", "gradient", "youtube", "single", "automove")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		
		vc_add_param("vc_row", array(
			"type" 							=> "dropdown",
			"class" 						=> "",
			"heading" 						=> __( "Background Position", "ts_visual_composer_extend" ),
			"param_name" 					=> "ts_row_bg_position",
			"value" 						=> array(
				__( "Center Center", "ts_visual_composer_extend" ) 	=> "center",
				__( "Center Top", "ts_visual_composer_extend" )		=> "top",
				__( "Center Bottom", "ts_visual_composer_extend" ) 	=> "bottom",
				__( "Left Top", "ts_visual_composer_extend" ) 		=> "left top",
				__( "Left Center", "ts_visual_composer_extend" ) 	=> "left center",
				__( "Left Bottom", "ts_visual_composer_extend" ) 	=> "left bottom",
				__( "Right Top", "ts_visual_composer_extend" ) 		=> "right top",
				__( "Right Center", "ts_visual_composer_extend" ) 	=> "right center",
				__( "Right Bottom", "ts_visual_composer_extend" ) 	=> "right bottom",
				__( "Custom Value", "ts_visual_composer_extend" ) 	=> "custom",
			),
			"description" 					=> __("Select the position of the background image; will have most effect on smaller screens."),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("image", "fixed")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend" ),
		));		
        vc_add_param("vc_row", array(
			"type"              			=> "textfield",
			"heading"           			=> __( "Custom Image Position", "ts_visual_composer_extend" ),
			"param_name"        			=> "ts_row_bg_position_custom",
			"value"             			=> "",
			"description"       			=> __( "Enter the custom position of the image, using either px or % (i.e. '25% 15%').", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_position",
				"value" 	=> array("custom")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend" ),
		));
		vc_add_param("vc_row", array(
			"type" 							=> "dropdown",
			"class" 						=> "",
			"heading" 						=> __( "Background Size", "ts_visual_composer_extend" ),
			"param_name" 					=> "ts_row_bg_size_standard",
			"value" 						=> array(
				__( "Cover", "ts_visual_composer_extend" ) 			=> "cover",
				__( "Contain", "ts_visual_composer_extend" ) 		=> "contain",
				__( "Initial", "ts_visual_composer_extend" ) 		=> "initial",
				__( "Auto", "ts_visual_composer_extend" ) 			=> "auto",
			),
			"description" 					=> __(""),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("image", "fixed")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type" 							=> "dropdown",
			"class" 						=> "",
			"heading" 						=> __( "Background Size", "ts_visual_composer_extend" ),
			"param_name" 					=> "ts_row_bg_size_parallax",
			"value" 						=> array(
				__( "Cover", "ts_visual_composer_extend" ) 			=> "cover",
				__( "150%", "ts_visual_composer_extend" )			=> "150%",
				__( "200%", "ts_visual_composer_extend" )			=> "200%",
				__( "Contain", "ts_visual_composer_extend" ) 		=> "contain",
				__( "Initial", "ts_visual_composer_extend" ) 		=> "initial",
				__( "Auto", "ts_visual_composer_extend" ) 			=> "auto",
			),
			"description" 					=> __(""),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("parallax", "automove")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type" 							=> "dropdown",
			"class" 						=> "",
			"heading" 						=> __( "Background Repeat", "ts_visual_composer_extend" ),
			"param_name" 					=> "ts_row_bg_repeat",
			"value" 						=> array(
				__( "No Repeat", "ts_visual_composer_extend" )		=> "no-repeat",
				__( "Repeat X + Y", "ts_visual_composer_extend" )	=> "repeat",
				__( "Repeat X", "ts_visual_composer_extend" )		=> "repeat-x",
				__( "Repeat Y", "ts_visual_composer_extend" )		=> "repeat-y"
			),
			"description" 					=> __(""),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("image", "fixed", "parallax")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		
		// Parallax Settings
		vc_add_param("vc_row", array(
			"type"              			=> "seperator",
			"heading"           			=> __( "", "ts_visual_composer_extend" ),
			"param_name"        			=> "seperator_2",
			"value"             			=> "",
			"seperator"             		=> "Parallax Settings",
			"description"       			=> __( "", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "parallax"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type" 							=> "dropdown",
			"class" 						=> "",
			"heading" 						=> __( "Background Parallax", "ts_visual_composer_extend"),
			"param_name" 					=> "ts_row_parallax_type",
			"value" 						=> array(
				"Up"			=> "up",
				"Down"			=> "down",
				"Left"			=> "left",
				"Right"			=> "right",
			),
			"description" 					=> __("Select the parallax effect for your background image. You must have a background image to use this.", "ts_visual_composer_extend"),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "parallax"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type" 							=> "dropdown",
			"class" 						=> "",
			"heading" 						=> __( "Background Position", "ts_visual_composer_extend" ),
			"param_name" 					=> "ts_row_bg_alignment_v",
			"value" 						=> array(
				__( "Center", "ts_visual_composer_extend" )			=> "center",
				__( "Left", "ts_visual_composer_extend" ) 			=> "left",
				__( "Right", "ts_visual_composer_extend" ) 			=> "right"
			),
			"description" 					=> __(""),
			"dependency" 					=> array(
				"element" 	=> "ts_row_parallax_type",
				"value" 	=> array("up", "down")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend" ),
		));
		vc_add_param("vc_row", array(
			"type" 							=> "dropdown",
			"class" 						=> "",
			"heading" 						=> __( "Background Position", "ts_visual_composer_extend" ),
			"param_name" 					=> "ts_row_bg_alignment_h",
			"value" 						=> array(
				__( "Center", "ts_visual_composer_extend" )			=> "center",
				__( "Top", "ts_visual_composer_extend" ) 			=> "top",
				__( "Bottom", "ts_visual_composer_extend" ) 		=> "bottom"
			),
			"description" 					=> __(""),
			"dependency" 					=> array(
				"element" 	=> "ts_row_parallax_type",
				"value" 	=> array("left", "right")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend" ),
		));
		vc_add_param("vc_row", array(
			"type"                  		=> "nouislider",
			"heading"               		=> __( "Parallax Speed", "ts_visual_composer_extend" ),
			"param_name"            		=> "ts_row_parallax_speed",
			"value"                 		=> "20",
			"min"                   		=> "0",
			"max"                   		=> "100",
			"step"                  		=> "1",
			"unit"                  		=> '',
			"description"           		=> __( "", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "parallax"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		// Auto Move Settings
		vc_add_param("vc_row", array(
			"type"              			=> "seperator",
			"heading"           			=> __( "", "ts_visual_composer_extend" ),
			"param_name"        			=> "seperator_3",
			"value"             			=> "",
			"seperator"             		=> "AutoMove Settings",
			"description"       			=> __( "", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "automove"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"                  		=> "nouislider",
			"heading"               		=> __( "Automove Speed", "ts_visual_composer_extend" ),
			"param_name"            		=> "ts_row_automove_speed",
			"value"                 		=> "75",
			"min"                   		=> "0",
			"max"                   		=> "1000",
			"step"                  		=> "1",
			"unit"                  		=> '',
			"description"           		=> __( "Define the AutoMove Speed; the higher the value, the slower the movement.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "automove"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"							=> "switch",
			"heading"           			=> __( "Automove Scroll", "ts_visual_composer_extend" ),
			"param_name"        			=> "ts_row_automove_scroll",
			"value"             			=> "true",
			"on"							=> __( 'Yes', "ts_visual_composer_extend" ),
			"off"							=> __( 'No', "ts_visual_composer_extend" ),
			"style"							=> "select",
			"design"						=> "toggle-light",
			"description"       			=> __( "Switch the toggle if the auto-moving image should scroll with the page or be fixed.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "automove"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type" 							=> "dropdown",
			"class" 						=> "",
			"heading" 						=> __( "Automove Path", "ts_visual_composer_extend"),
			"param_name" 					=> "ts_row_automove_align",
			"value" 						=> array(
				"Horizontal"		=> "horizontal",
				"Vertical"			=> "vertical",
			),
			"description" 					=> __("Select the path the auto-moving image should be using.", "ts_visual_composer_extend"),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "automove"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));	
		vc_add_param("vc_row", array(
			"type" 							=> "dropdown",
			"class" 						=> "",
			"heading" 						=> __( "Moving Direction", "ts_visual_composer_extend"),
			"param_name" 					=> "ts_row_automove_path_h",
			"value" 						=> array(
				"Left to Right"		=> "leftright",
				"Right to Left"		=> "rightleft",
			),
			"description" 					=> __("Select the path the auto-moving image should be using.", "ts_visual_composer_extend"),
			"dependency" 					=> array(
				"element" 	=> "ts_row_automove_align",
				"value" 	=> "horizontal"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type" 							=> "dropdown",
			"class" 						=> "",
			"heading" 						=> __( "Moving Direction", "ts_visual_composer_extend"),
			"param_name" 					=> "ts_row_automove_path_v",
			"value" 						=> array(
				"Top to Bottom"		=> "topbottom",
				"Bottom to Top"		=> "bottomtop",
			),
			"description" 					=> __("Select the path the auto-moving image should be using.", "ts_visual_composer_extend"),
			"dependency" 					=> array(
				"element" 	=> "ts_row_automove_align",
				"value" 	=> "vertical"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));		
		// Global Settings
		vc_add_param("vc_row", array(
			"type"              			=> "seperator",
			"heading"           			=> __( "", "ts_visual_composer_extend" ),
			"param_name"        			=> "seperator_4",
			"value"             			=> "",
			"seperator"             		=> "Global Settings",
			"description"       			=> __( "", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("image", "fixed", "parallax", "automove")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"                  		=> "nouislider",
			"heading"               		=> __( "Padding: Top", "ts_visual_composer_extend" ),
			"param_name"            		=> "padding_top",
			"value"                 		=> "30",
			"min"                   		=> "0",
			"max"                   		=> "250",
			"step"                  		=> "1",
			"unit"                  		=> 'px',
			"description"           		=> __( "", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("image", "fixed", "parallax", "automove")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"                  		=> "nouislider",
			"heading"               		=> __( "Padding: Bottom", "ts_visual_composer_extend" ),
			"param_name"            		=> "padding_bottom",
			"value"                 		=> "30",
			"min"                   		=> "0",
			"max"                   		=> "250",
			"step"                  		=> "1",
			"unit"                  		=> 'px',
			"description"           		=> __( "", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("image", "fixed", "parallax", "automove")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"                  		=> "nouislider",
			"heading"               		=> __( "Margin: Left", "ts_visual_composer_extend" ),
			"param_name"            		=> "margin_left",
			"value"                 		=> "0",
			"min"                   		=> "-50",
			"max"                   		=> "100",
			"step"                  		=> "1",
			"unit"                  		=> 'px',
			"description"           		=> __( "", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("image", "fixed", "parallax", "automove")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"                  		=> "nouislider",
			"heading"               		=> __( "Margin: Right", "ts_visual_composer_extend" ),
			"param_name"            		=> "margin_right",
			"value"                 		=> "0",
			"min"                   		=> "-50",
			"max"                   		=> "100",
			"step"                  		=> "1",
			"unit"                  		=> 'px',
			"description"           		=> __( "", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("image", "fixed", "parallax", "automove")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"              			=> "colorpicker",
			"heading"           			=> __( "Background Color", "ts_visual_composer_extend" ),
			"param_name"        			=> "single_color",
			"value"            	 			=> "#ffffff",
			"description"       			=> __( "Define the background color for the row.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "single"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		// Gradient Color Background
		vc_add_param("vc_row", array(
			"type"              			=> "seperator",
			"heading"           			=> __( "", "ts_visual_composer_extend" ),
			"param_name"        			=> "seperator_5",
			"value"             			=> "",
			"seperator"             		=> "Gradient Settings",
			"description"       			=> __( "", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "gradient"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"                  		=> "nouislider",
			"heading"               		=> __( "Gradient Angle", "ts_visual_composer_extend" ),
			"param_name"            		=> "gradient_angle",
			"value"                 		=> "0",
			"min"                   		=> "0",
			"max"                   		=> "360",
			"step"                  		=> "1",
			"unit"                  		=> 'deg',
			"description"           		=> __( "Define the angle at which the gradient should spread.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "gradient"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"              			=> "colorpicker",
			"heading"           			=> __( "Start Color", "ts_visual_composer_extend" ),
			"param_name"        			=> "gradient_color_start",
			"value"            	 			=> "#cccccc",
			"description"       			=> __( "Define the start color for the gradient.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "gradient"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"                  		=> "nouislider",
			"heading"               		=> __( "Gradient Start", "ts_visual_composer_extend" ),
			"param_name"            		=> "gradient_start_offset",
			"value"                 		=> "0",
			"min"                   		=> "0",
			"max"                   		=> "100",
			"step"                  		=> "1",
			"unit"                  		=> '%',
			"description"           		=> __( "Define the beginning section of the gradient.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "gradient"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"              			=> "colorpicker",
			"heading"           			=> __( "End Color", "ts_visual_composer_extend" ),
			"param_name"        			=> "gradient_color_end",
			"value"            	 			=> "#cccccc",
			"description"       			=> __( "Define the end color for the gradient.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "gradient"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"                  		=> "nouislider",
			"heading"               		=> __( "Gradient End", "ts_visual_composer_extend" ),
			"param_name"            		=> "gradient_end_offset",
			"value"                 		=> "100",
			"min"                   		=> "0",
			"max"                   		=> "100",
			"step"                  		=> "1",
			"unit"                  		=> '%',
			"description"           		=> __( "Define the end section of the gradient.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "gradient"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		// YouTube Video Background
		vc_add_param("vc_row", array(
			"type"              			=> "seperator",
			"heading"           			=> __( "", "ts_visual_composer_extend" ),
			"param_name"        			=> "seperator_6",
			"value"             			=> "",
			"seperator"             		=> "YouTube Settings",
			"description"       			=> __( "", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "youtube"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"              			=> "textfield",
			"heading"           			=> __( "YouTube Video ID", "ts_visual_composer_extend" ),
			"param_name"        			=> "video_youtube",
			"value"             			=> "",
			"description"       			=> __( "Enter the YouTube video ID.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "youtube"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"							=> "attach_image",
			"heading"						=> __( "Background Image", "ts_visual_composer_extend" ),
			"param_name"					=> "video_background",
			"value"							=> "",
			"description"					=> __( "Select an alternative background image for the video on mobile devices; otherwise YouTube cover image will be used.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "youtube"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"							=> "switch",
			"heading"           			=> __( "Mute Video", "ts_visual_composer_extend" ),
			"param_name"        			=> "video_mute",
			"value"             			=> "true",
			"on"							=> __( 'Yes', "ts_visual_composer_extend" ),
			"off"							=> __( 'No', "ts_visual_composer_extend" ),
			"style"							=> "select",
			"design"						=> "toggle-light",
			"description"       			=> __( "Switch the toggle to mute the video while playing.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "youtube"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"							=> "switch",
			"heading"           			=> __( "Loop Video", "ts_visual_composer_extend" ),
			"param_name"        			=> "video_loop",
			"value"             			=> "false",
			"on"							=> __( 'Yes', "ts_visual_composer_extend" ),
			"off"							=> __( 'No', "ts_visual_composer_extend" ),
			"style"							=> "select",
			"design"						=> "toggle-light",
			"description"       			=> __( "Switch the toggle to loop the video after it has finished.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "youtube"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"							=> "switch",
			"heading"           			=> __( "Start Video on Pageload", "ts_visual_composer_extend" ),
			"param_name"        			=> "video_start",
			"value"             			=> "false",
			"on"							=> __( 'Yes', "ts_visual_composer_extend" ),
			"off"							=> __( 'No', "ts_visual_composer_extend" ),
			"style"							=> "select",
			"design"						=> "toggle-light",
			"description"       			=> __( "Switch the toggle to if you want to start the video once the page has loaded.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "youtube"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"							=> "switch",
			"heading"           			=> __( "Stop Video once out of View", "ts_visual_composer_extend" ),
			"param_name"        			=> "video_stop",
			"value"             			=> "true",
			"on"							=> __( 'Yes', "ts_visual_composer_extend" ),
			"off"							=> __( 'No', "ts_visual_composer_extend" ),
			"style"							=> "select",
			"design"						=> "toggle-light",
			"description"       			=> __( "Switch the toggle to if you want to stop the video once it is out of view and restart when it comes back into view.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "youtube"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"							=> "switch",
			"heading"           			=> __( "Show Video Controls", "ts_visual_composer_extend" ),
			"param_name"        			=> "video_controls",
			"value"             			=> "true",
			"on"							=> __( 'Yes', "ts_visual_composer_extend" ),
			"off"							=> __( 'No', "ts_visual_composer_extend" ),
			"style"							=> "select",
			"design"						=> "toggle-light",
			"description"       			=> __( "Switch the toggle to if you want to show basic video controls.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "youtube"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"							=> "switch",
			"heading"           			=> __( "Show Raster over Video", "ts_visual_composer_extend" ),
			"param_name"        			=> "video_raster",
			"value"             			=> "false",
			"on"							=> __( 'Yes', "ts_visual_composer_extend" ),
			"off"							=> __( 'No', "ts_visual_composer_extend" ),
			"style"							=> "select",
			"design"						=> "toggle-light",
			"description"       			=> __( "Switch the toggle to if you want to show a raster over the video.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "youtube"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		// Video Background
		/*vc_add_param("vc_row", array(
			"type"              			=> "textfield",
			"heading"           			=> __( "MP4 Video Path", "ts_visual_composer_extend" ),
			"param_name"        			=> "video_mp4",
			"value"             			=> "",
			"description"       			=> __( "Enter the path to the MP4 video version.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "video"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"              			=> "textfield",
			"heading"           			=> __( "OGV Video Path", "ts_visual_composer_extend" ),
			"param_name"        			=> "video_ogv",
			"value"             			=> "",
			"description"       			=> __( "Enter the path to the OGV video version.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "video"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"              			=> "textfield",
			"heading"           			=> __( "WEBM Video Path", "ts_visual_composer_extend" ),
			"param_name"        			=> "video_webm",
			"value"             			=> "",
			"description"       			=> __( "Enter the path to the WEBM video version.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "video"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"							=> "attach_image",
			"heading"						=> __( "Video Screenshot Image", "ts_visual_composer_extend" ),
			"param_name"					=> "video_image",
			"value"							=> "",
			"description"					=> __( "Select the a screenshot image for the video.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> "video"
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));*/
		// Viewport Animation
		vc_add_param("vc_row", array(
			"type"              			=> "seperator",
			"heading"           			=> __( "", "ts_visual_composer_extend" ),
			"param_name"        			=> "seperator_6",
			"value"             			=> "",
			"seperator"             		=> "Animation Settings",
			"description"       			=> __( "", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("", "image", "gradient", "single")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type" 							=> "css3animations",
			"class" 						=> "",
			"heading" 						=> __("Viewport Animation", "ts_visual_composer_extend"),
			"param_name" 					=> "animation_view",
			"standard"						=> "false",
			"prefix"						=> "",
			"connector"						=> "css3animations_in",
			"noneselect"					=> "true",
			"default"						=> "",
			"value" 						=> "",
			"admin_label"					=> false,
			"description" 					=> __("Select a Viewport Animation for this Row; it is advised not to use with Parallax.", "ts_visual_composer_extend"),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("", "image", "gradient", "single")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"                      	=> "hidden_input",
			"heading"                   	=> __( "Animation Type", "ts_visual_composer_extend" ),
			"param_name"                	=> "css3animations_in",
			"value"                     	=> "",
			"admin_label"		        	=> true,
			"description"               	=> __( "", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("", "image", "gradient", "single")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"							=> "switch",
			"heading"           			=> __( "Repeat Effect", "ts_visual_composer_extend" ),
			"param_name"        			=> "animation_scroll",
			"value"             			=> "false",
			"on"							=> __( 'Yes', "ts_visual_composer_extend" ),
			"off"							=> __( 'No', "ts_visual_composer_extend" ),
			"style"							=> "select",
			"design"						=> "toggle-light",
			"description"       			=> __( "Switch the toggle to repeat the viewport effect when element has come out of view and comes back into viewport.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("", "image", "gradient", "single")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"                  		=> "nouislider",
			"heading"               		=> __( "Animation Speed", "ts_visual_composer_extend" ),
			"param_name"            		=> "animation_speed",
			"value"                 		=> "2000",
			"min"                   		=> "1000",
			"max"                   		=> "5000",
			"step"                  		=> "100",
			"unit"                  		=> 'ms',
			"description"           		=> __( "Define the Length of the Viewport Animation in ms.", "ts_visual_composer_extend" ),
			"dependency" 					=> array(
				"element" 	=> "ts_row_bg_effects",
				"value" 	=> array("", "image", "gradient", "single")
			),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"                  		=> "load_file",
			"class" 						=> "",
			"heading"               		=> __( "", "ts_visual_composer_extend" ),
			"param_name"            		=> "el_file1",
			"value"                 		=> "",
			"file_type"             		=> "js",
			"file_path"             		=> "js/ts-visual-composer-extend-element.min.js",
			"description"           		=> __( "", "ts_visual_composer_extend" ),
			"group" 						=> __( "VC Extensions", "ts_visual_composer_extend"),
		));
		vc_add_param("vc_row", array(
			"type"              			=> "load_file",
			"class" 						=> "",
			"heading"           			=> __( "", "ts_visual_composer_extend" ),
			"param_name"        			=> "el_file2",
			"value"             			=> "",
			"file_type"         			=> "css",
			"file_id"         				=> "ts-extend-animations",
			"file_path"         			=> "css/ts-visual-composer-extend-animations.min.css",
			"description"       			=> __( "", "ts_visual_composer_extend" )
		));
		
		// Add Custom BackEnd View
		$setting = array (
			"js_view" => 'TS_VCSC_VcRowViewCustom'
		);
		vc_map_update('vc_row', $setting);
	}
	
	add_filter('TS_VCSC_ComposerRowAdditions_Filter',		'TS_VCSC_ComposerRowAdditions', 		10, 2);
	
	function TS_VCSC_ComposerRowAdditions($output, $atts, $content='') {
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

		extract(shortcode_atts( array(
			'ts_row_bg_image'			=> '',
			'ts_row_bg_source'			=> 'full',
			'ts_row_bg_effects'			=> '',
			'ts_row_break_parents'		=> 4,
			'ts_row_zindex'				=> 0,
			'ts_row_min_height'			=> 100,
			
			'ts_row_bg_position'		=> 'center',
			'ts_row_bg_position_custom'	=> '',
			'ts_row_bg_alignment_h'		=> 'center',
			'ts_row_bg_alignment_v'		=> 'center',
			'ts_row_bg_repeat'			=> 'no-repeat',
			'ts_row_bg_size_parallax'	=> 'cover',
			'ts_row_bg_size_standard'	=> 'cover',
			'ts_row_parallax_type'		=> '',
			'ts_row_parallax_speed'		=> 20,
			
			'ts_row_automove_scroll'	=> 'true',
			'ts_row_automove_align'		=> 'horizontal',
			'ts_row_automove_path_v'	=> 'topbottom',
			'ts_row_automove_path_h'	=> 'rightleft',
			'ts_row_automove_speed'		=> 75,
			
			'margin_left'				=> 0,
			'margin_right'				=> 0,
			'padding_top'				=> 20,
			'padding_bottom'			=> 20,
			'enable_mobile'				=> 'false',
			
			'single_color'				=> '#ffffff',
			
			'gradient_angle'			=> 0,
			'gradient_color_start'		=> '#cccccc',
			'gradient_start_offset'		=> 0,
			'gradient_color_end'		=> '#cccccc',
			'gradient_end_offset'		=> 100,
			
			'video_youtube'				=> '',
			'video_background'			=> '',
			'video_mute'				=> 'true',
			'video_loop'				=> 'false',
			'video_start'				=> 'false',
			'video_stop'				=> 'true',
			'video_controls'			=> 'true',
			'video_raster'				=> 'false',
			
			'video_mp4'					=> '',
			'video_ogv'					=> '',
			'video_webm'				=> '',
			'video_image'				=> '',

			'animation_factor'			=> '0.33',
			'animation_scroll'			=> 'false',
			'animation_view'			=> '',
			'animation_speed'			=> 2000,
		), $atts));
		
		$output 					= "";

		// Viewport Animations
		if (!empty($animation_view)) {
			$animation_css				= "ts-viewport-css-" . $animation_view;
			$output						.= '<div class="ts-viewport-row ts-viewport-animation" data-inline="' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode . '" data-scrollup = "' . $animation_scroll . '" data-factor="' . $animation_factor . '" data-viewport="' . $animation_css . '" data-speed="' . $animation_speed . '"></div>';
		} else {
			$animation_css				= '';
		}

		// Simple Background Image
		if ($ts_row_bg_effects == "image") {
			$ts_row_bg_image_url		= wp_get_attachment_image_src($ts_row_bg_image, $ts_row_bg_source);
			if ($ts_row_bg_position == "custom") {
				$ts_row_bg_position		= $ts_row_bg_position_custom;
			}
			$output						.= "<div class='ts-background-image ts-background' data-inline='" . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode . "' data-height='" . $ts_row_min_height . "' data-index='" . $ts_row_zindex . "' data-marginleft='" . $margin_left . "' data-marginright='" . $margin_right . "' data-paddingtop='" . $padding_top . "' data-paddingbottom='" . $padding_bottom . "' data-image='" . $ts_row_bg_image_url[0] . "' data-size='". $ts_row_bg_size_standard . "' data-position='" . esc_attr($ts_row_bg_position) . "' data-repeat='" . $ts_row_bg_repeat . "' data-break-parents='" . esc_attr( $ts_row_break_parents ) . "'></div>";
		}
		
		// Fixed Background Image
		if ($ts_row_bg_effects == "fixed") {
			$ts_row_bg_image_url		= wp_get_attachment_image_src($ts_row_bg_image, $ts_row_bg_source);
			if ($ts_row_bg_position == "custom") {
				$ts_row_bg_position		= $ts_row_bg_position_custom;
			}
			$output						.= "<div class='ts-background-fixed ts-background' data-inline='" . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode . "' data-height='" . $ts_row_min_height . "' data-index='" . $ts_row_zindex . "' data-marginleft='" . $margin_left . "' data-marginright='" . $margin_right . "' data-paddingtop='" . $padding_top . "' data-paddingbottom='" . $padding_bottom . "' data-image='" . $ts_row_bg_image_url[0] . "' data-size='". $ts_row_bg_size_standard . "' data-position='" . esc_attr($ts_row_bg_position) . "' data-repeat='" . $ts_row_bg_repeat . "' data-break-parents='" . esc_attr( $ts_row_break_parents ) . "'></div>";
		}

		// Parallax Background
		if ($ts_row_bg_effects == "parallax") {
			$parallaxClass				= ( $ts_row_parallax_type == "none" ) ? "" : "ts-background-parallax";
			$parallaxClass				= in_array( $ts_row_parallax_type, array( "none", "fixed", "up", "down", "left", "right", "ts-background-parallax" ) ) ? $parallaxClass : "";			
			if (($ts_row_parallax_type == "up") || ($ts_row_parallax_type == "down")) {
				$ts_row_bg_alignment	= $ts_row_bg_alignment_v;
			} else if (($ts_row_parallax_type == "left") || ($ts_row_parallax_type == "right")) {
				$ts_row_bg_alignment	= $ts_row_bg_alignment_h;
			}			
			if (!empty($parallaxClass)) {
				$ts_row_bg_image_url	= wp_get_attachment_image_src($ts_row_bg_image, $ts_row_bg_source);
				$ts_row_parallax_speed	= round(($ts_row_parallax_speed / 100), 2);
				$output					.= "<div class='" . esc_attr($parallaxClass) . " ts-background' data-inline='" . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode . "' data-disabled='false' data-height='" . $ts_row_min_height . "' data-index='" . $ts_row_zindex . "' data-marginleft='" . $margin_left . "' data-marginright='" . $margin_right . "' data-paddingtop='" . $padding_top . "' data-paddingbottom='" . $padding_bottom . "' data-image='" . $ts_row_bg_image_url[0] . "' data-size='". $ts_row_bg_size_parallax . "' data-position='" . esc_attr($ts_row_bg_position) . "' data-alignment='" . $ts_row_bg_alignment . "' data-repeat='" . $ts_row_bg_repeat . "' data-direction='" . esc_attr($ts_row_parallax_type) . "' data-momentum='" . esc_attr((float)$ts_row_parallax_speed * -1) . "' data-mobile-enabled='" . esc_attr($enable_mobile) . "' data-break-parents='" . esc_attr($ts_row_break_parents) . "'></div>";
			}
		}
		
		// AutoMove Background
		if ($ts_row_bg_effects == "automove") {
			$ts_row_bg_image_url		= wp_get_attachment_image_src($ts_row_bg_image, $ts_row_bg_source);
			if ($ts_row_automove_align == "horizontal") {
				$ts_row_automove_path	= $ts_row_automove_path_h;
			} else if ($ts_row_automove_align == "vertical") {
				$ts_row_automove_path	= $ts_row_automove_path_v;
			}			
			$output						.= "<div class='ts-background-automove ts-background' data-inline='" . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode . "' data-height='" . $ts_row_min_height . "' data-index='" . $ts_row_zindex . "' data-marginleft='" . $margin_left . "' data-marginright='" . $margin_right . "' data-paddingtop='" . $padding_top . "' data-paddingbottom='" . $padding_bottom . "' data-image='" . $ts_row_bg_image_url[0] . "' data-size='". $ts_row_bg_size_standard . "' data-position='" . esc_attr($ts_row_bg_position) . "' data-repeat='repeat 0 0' data-scroll='" . $ts_row_automove_scroll . "' data-alignment='" . $ts_row_automove_align . "' data-direction='" . $ts_row_automove_path . "' data-speed='" . $ts_row_automove_speed . "' data-break-parents='" . esc_attr( $ts_row_break_parents ) . "'></div>";
		}

		// Selfhosted Video Background
		if ($ts_row_bg_effects == "video") {
			if (!empty($video_image)) {
				$video_image_url		= wp_get_attachment_image_src($video_image, 'full');
				$video_image_url		= $video_image_url[0];
			} else {
				$video_image_url		= "";
			}
			$output						.= '<div class="ts-background-video ts-background" data-inline="' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode . '" data-height="' . $ts_row_min_height . '" data-index="' . $ts_row_zindex . '" data-marginleft="' . $margin_left . '" data-marginright="' . $margin_right . '" data-paddingtop="' . $padding_top . '" data-paddingbottom="' . $padding_bottom . '" data-mp4="' . $video_mp4 . '" data-ogv="' . $video_ogv . '" data-webm="' . $video_webm . '" data-image="' . $video_image_url . '" data-break-parents="' . esc_attr( $ts_row_break_parents ) . '"></div>';
		}
		
		// Youtube Video Background
		if ($ts_row_bg_effects == "youtube") {
			if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $video_youtube)) {
				$video_youtube			= $video_youtube;
			} else {
				$video_youtube			= 'https://www.youtube.com/watch?v=' . $video_youtube;
			}
			if (!empty($video_background)) {
				$video_background		= wp_get_attachment_image_src($video_background, 'full');
				$video_background		= $video_background[0];
			} else {
				$video_background		= TS_VCSC_VideoImage_Youtube($video_youtube);
			}
			wp_enqueue_script('ts-extend-ytplayer',	TS_VCSC_GetResourceURL('js/jquery.mb.ytplayer.min.js'), array('jquery'), false, $FOOTER);
			wp_enqueue_style('ts-extend-ytplayer',	TS_VCSC_GetResourceURL('css/jquery.mb.ytplayer.css'), null, false, 'all');
			$output						.= '<div id="ts-background-youtube-' . mt_rand(999999, 9999999) . '" class="ts-background-youtube ts-background" data-inline="' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode . '" data-height="' . $ts_row_min_height . '" data-index="' . $ts_row_zindex . '" data-marginleft="' . $margin_left . '" data-marginright="' . $margin_right . '" data-paddingtop="' . $padding_top . '" data-paddingbottom="' . $padding_bottom . '" data-image="' . $video_background . '" data-video="' . $video_youtube . '" data-controls="' . $video_controls . '" data-start="' . $video_start . '" data-stop="' . $video_stop . '" data-raster="' . $video_raster . '" data-mute="' . $video_mute . '" data-loop="' . $video_loop . '" data-break-parents="' . esc_attr( $ts_row_break_parents ) . '">';
			$output						.= '</div>';
		}
		
		// Vimeo Video Background
		if ($ts_row_bg_effects == "vimeo") {

		}
		
		// Single Color Background
		if ($ts_row_bg_effects == "single") {
			$output						.= '<div class="ts-background-single ts-background" style="display: none; background: ' . $single_color . ';" data-inline="' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode . '" data-color="' . $single_color . '" data-height="' . $ts_row_min_height . '" data-index="' . $ts_row_zindex . '" data-marginleft="' . $margin_left . '" data-marginright="' . $margin_right . '" data-paddingtop="' . $padding_top . '" data-paddingbottom="' . $padding_bottom . '" data-break-parents="' . esc_attr( $ts_row_break_parents ) . '"></div>';
		}
		
		// Gradient Background
		if ($ts_row_bg_effects == "gradient") {
			$gradient_css_attr[] 		= 'background: ' . $gradient_color_start . '';
			$gradient_css_attr[] 		= 'background: -moz-linear-gradient(' . $gradient_angle . 'deg, ' . $gradient_color_start . ' ' . $gradient_start_offset . '%, ' . $gradient_color_end . ' ' . $gradient_end_offset . '%)';
			$gradient_css_attr[] 		= 'background: -webkit-linear-gradient(' . $gradient_angle . 'deg, ' . $gradient_color_start . ' ' . $gradient_start_offset . '%, ' . $gradient_color_end . ' ' . $gradient_end_offset . '%)';
			$gradient_css_attr[] 		= 'background: -o-linear-gradient(' . $gradient_angle . 'deg, ' . $gradient_color_start . ' ' . $gradient_start_offset . '%, ' . $gradient_color_end . ' ' . $gradient_end_offset . '%)';
			$gradient_css_attr[] 		= 'background: -ms-linear-gradient(' . $gradient_angle . 'deg, ' . $gradient_color_start . ' ' . $gradient_start_offset . '%, ' . $gradient_color_end . ' ' . $gradient_end_offset . '%)';
			$gradient_css_attr[] 		= 'background: linear-gradient(' . ($gradient_angle + 90) . 'deg, ' . $gradient_color_start . ' ' . $gradient_start_offset . '%, ' . $gradient_color_end . ' ' . $gradient_end_offset . '%)';
			$gradient_css_attr = 		implode('; ', $gradient_css_attr);
			$output						.= '<div class="ts-background-gradient ts-background" style="display: none; ' . $gradient_css_attr . '" data-inline="' . $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_VCFrontEditMode . '" data-height="' . $ts_row_min_height . '" data-index="' . $ts_row_zindex . '" data-marginleft="' . $margin_left . '" data-marginright="' . $margin_right . '" data-paddingtop="' . $padding_top . '" data-paddingbottom="' . $padding_bottom . '" data-break-parents="' . esc_attr( $ts_row_break_parents ) . '"></div>';
		}
		
		echo $output;
		
		$myvariable = ob_get_clean();
		return $myvariable;
	}
	
	if (!function_exists('vc_theme_before_vc_row')){
		function vc_theme_before_vc_row($atts, $content = null) {
			return apply_filters( 'TS_VCSC_ComposerRowAdditions_Filter', '', $atts, $content );
		}
	}
	if (!function_exists('vc_theme_before_vc_row_inner')){
		function vc_theme_before_vc_row_inner($atts, $content = null){
			return apply_filters( 'TS_VCSC_ComposerRowAdditions_Filter', '', $atts, $content );
		}
	}
?>