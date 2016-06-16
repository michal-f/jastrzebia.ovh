<?php
	if (function_exists('vc_map')) {
		vc_map(array(
			"name"                      => __( "TS Icon Fonts", "ts_visual_composer_extend" ),
			"base"                      => "TS-VCSC-Font-Icons",
			"icon" 	                    => "icon-wpb-ts_vcsc_icon_font",
			"class"                     => "",
			"category"                  => __("VC Extensions", "ts_visual_composer_extend"),
			"description" 		    	=> __("Place a font (vector) icon or image", "ts_visual_composer_extend"),
			//"admin_enqueue_js" 	    => array(TS_VCSC_GetResourceURL('/js/ts_vcsc_icon_font.js')),
			//"admin_enqueue_css"	    => array(TS_VCSC_GetResourceURL('/css/jquery.js-composer.fb-album.css')),
			"params"                    => array(
				// Main Icon Settings
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_1",
					"value"             => "Icon / Image Selection Settings",
					"description"       => __( "", "ts_visual_composer_extend" )
				),
				array(
					"type"				=> "switch",
					"heading"           => __( "Use Normal Image", "ts_visual_composer_extend" ),
					"param_name"        => "icon_replace",
					"value"             => "false",
					"on"				=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"				=> __( 'No', "ts_visual_composer_extend" ),
					"style"				=> "select",
					"design"			=> "toggle-light",
					"description"       => __( "Switch the toggle to either use an icon or a normal image.", "ts_visual_composer_extend" ),
                    "dependency"        => ""
				),
				array(
					"type"              => "icons_panel",
					"heading"           => __( "Icon", "ts_visual_composer_extend" ),
					"param_name"        => "icon",
					"value"             => $this->TS_VCSC_List_Icons_Full,
					"admin_label"       => true,
					"description"       => __( "Select the icon you want to display.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "icon_replace", 'value' => 'false' )
				),
				array(
					"type"              => "attach_image",
					"heading"           => __( "Select Image", "ts_visual_composer_extend" ),
					"param_name"        => "icon_image",
					"value"             => "",
					"admin_label"       => true,
					"description"       => __( "Image must have equal dimensions for scaling purposes (i.e. 100x100).", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "icon_replace", 'value' => 'true' )
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "Icon / Image Size", "ts_visual_composer_extend" ),
					"param_name"        => "icon_size_slide",
					"value"             => "30",
					"min"               => "16",
					"max"               => "512",
					"step"              => "1",
					"unit"              => 'px',
					"description"       => __( "Select the icon / image size", "ts_visual_composer_extend" ),
					"dependency"        => ""
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Icon Color", "ts_visual_composer_extend" ),
					"param_name"        => "icon_color",
					"value"             => "#cccccc",
					"description"       => __( "Define the color of the icon.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "icon_replace", 'value' => 'false' )
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Icon / Image Background Color", "ts_visual_composer_extend" ),
					"param_name"        => "icon_background",
					"value"             => "",
					"description"       => __( "Define the background color for the icon / transparent image.", "ts_visual_composer_extend" ),
					"dependency"        => ""
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Icon / Image Align", "ts_visual_composer_extend" ),
					"param_name"        => "icon_align",
					"width"             => 150,
					"value"             => array(
						__( "No Align", "ts_visual_composer_extend" )                      => "none",
						__( "Float Left", "ts_visual_composer_extend" )                    => "left",
						__( "Float Right", "ts_visual_composer_extend" )                   => "right",
						__( "Center", "ts_visual_composer_extend" )                        => "center",
					),
					"description"       => __( "Select how to position the icon in the column.", "ts_visual_composer_extend" )
				),
				// Icon Border Settings
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_2",
					"value"             => "Icon / Image Border Settings",
					"description"       => __( "", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Icon / Image Border Type", "ts_visual_composer_extend" ),
					"param_name"        => "icon_frame_type",
					"width"             => 300,
					"value"             => array(
						__( "None", "ts_visual_composer_extend" )                          => "",
						__( "Solid Border", "ts_visual_composer_extend" )                  => "solid",
						__( "Dotted Border", "ts_visual_composer_extend" )                 => "dotted",
						__( "Dashed Border", "ts_visual_composer_extend" )                 => "dashed",
						__( "Double Border", "ts_visual_composer_extend" )                 => "double",
						__( "Grouve Border", "ts_visual_composer_extend" )                 => "groove",
						__( "Ridge Border", "ts_visual_composer_extend" )                  => "ridge",
						__( "Inset Border", "ts_visual_composer_extend" )                  => "inset",
						__( "Outset Border", "ts_visual_composer_extend" )                 => "outset",
					),
					"description"       => __( "Select the type of border around the icon / image.", "ts_visual_composer_extend" ),
					"dependency"        => ""
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "Icon / Image Border Thickness", "ts_visual_composer_extend" ),
					"param_name"        => "icon_frame_thick",
					"value"             => "1",
					"min"               => "1",
					"max"               => "10",
					"step"              => "1",
					"unit"              => 'px',
					"description"       => __( "Define the thickness of the icon / image border.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "icon_frame_type", 'value' => $this->TS_VCSC_Border_Type_Values )
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Icon / Image Border Radius", "ts_visual_composer_extend" ),
					"param_name"        => "icon_frame_radius",
					"value"             => array(
						__( "None", "ts_visual_composer_extend" )                          => "",
						__( "Small Radius", "ts_visual_composer_extend" )                  => "ts-radius-small",
						__( "Medium Radius", "ts_visual_composer_extend" )                 => "ts-radius-medium",
						__( "Large Radius", "ts_visual_composer_extend" )                  => "ts-radius-large",
						__( "Full Circle", "ts_visual_composer_extend" )                   => "ts-radius-full"
					),
					"description"       => __( "Define the radius of the icon / image border.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "icon_frame_type", 'value' => $this->TS_VCSC_Border_Type_Values )
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Icon / Image Frame Border Color", "ts_visual_composer_extend" ),
					"param_name"        => "icon_frame_color",
					"value"             => "#000000",
					"description"       => __( "Define the color of the icon / image border.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "icon_frame_type", 'value' => $this->TS_VCSC_Border_Type_Values )
				),
				array(
					"type"				=> "switch",
					"heading"           => __( "Apply Padding to Icon / Image", "ts_visual_composer_extend" ),
					"param_name"        => "padding",
					"value"             => "false",
					"on"				=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"				=> __( 'No', "ts_visual_composer_extend" ),
					"style"				=> "select",
					"design"			=> "toggle-light",
					"description"       => __( "Switch the toggle if you want to apply a padding to the icon / image.", "ts_visual_composer_extend" ),
                    "dependency"        => ""
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "Icon / Image Padding", "ts_visual_composer_extend" ),
					"param_name"        => "icon_padding",
					"value"             => "0",
					"min"               => "0",
					"max"               => "50",
					"step"              => "1",
					"unit"              => 'px',
					"description"       => __( "If image instead of icon, increase the image size by padding value.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "padding", 'value' => 'true' )
				),
				// Icon Link Settings
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_3",
					"value"             => "Icon Link Settings",
					"description"       => __( "", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "textfield",
					"heading"           => __( "Link", "ts_visual_composer_extend" ),
					"param_name"        => "link",
					"value"             => "",
					"description"       => __( "Enter the link to the page or file here (starting with http://).", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Link Target", "ts_visual_composer_extend" ),
					"param_name"        => "link_target",
					"value"             => array(
						__( "Same Window", "ts_visual_composer_extend" )                    => "_parent",
						__( "New Window", "ts_visual_composer_extend" )                     => "_blank"
					),
					"description"       => __( "Select how the link should be opened.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "link", 'not_empty' => true )
				),
				// Icon Tooltip
				array(
					"type"				=> "seperator",
					"heading"			=> __( "", "ts_visual_composer_extend" ),
					"param_name"		=> "seperator_4",
					"value"				=> "Icon Tooltip",
					"description"		=> __( "", "ts_visual_composer_extend" )
				),
				array(
					"type"				=> "switch",
					"heading"			=> __( "Use CSS3 Tooltip", "ts_visual_composer_extend" ),
					"param_name"		=> "tooltip_css",
					"value"				=> "false",
					"on"				=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"				=> __( 'No', "ts_visual_composer_extend" ),
					"style"				=> "select",
					"design"			=> "toggle-light",
					"description"       => __( "Switch the toggle if you want to apply a CSS3 tooltip to the image.", "ts_visual_composer_extend" ),
                    "dependency"        => ""
				),
				array(
					"type"				=> "textarea",
					"class"				=> "",
					"heading"			=> __( "Tooltip Content", "ts_visual_composer_extend" ),
					"param_name"		=> "tooltip_content",
					"value"				=> "",
					"description"		=> __( "Enter the tooltip content here (do not use quotation marks).", "ts_visual_composer_extend" ),
					"dependency"		=> ""
				),
				array(
					"type"				=> "dropdown",
					"class"				=> "",
					"heading"			=> __( "Tooltip Position", "ts_visual_composer_extend" ),
					"param_name"		=> "tooltip_position",
					"value"					=> array(
						__( "Top", "ts_visual_composer_extend" )                            => "ts-simptip-position-top",
						__( "Bottom", "ts_visual_composer_extend" )                         => "ts-simptip-position-bottom",
					),
					"description"		=> __( "Select the tooltip position in relation to the image.", "ts_visual_composer_extend" ),
					"dependency"		=> array( 'element' => "tooltip_css", 'value' => 'true' )
				),
				array(
					"type"				=> "dropdown",
					"class"				=> "",
					"heading"			=> __( "Tooltip Style", "ts_visual_composer_extend" ),
					"param_name"		=> "tooltip_style",
					"value"             => array(
						__( "Black", "ts_visual_composer_extend" )                          => "",
						__( "Gray", "ts_visual_composer_extend" )                           => "ts-simptip-style-gray",
						__( "Green", "ts_visual_composer_extend" )                          => "ts-simptip-style-green",
						__( "Blue", "ts_visual_composer_extend" )                           => "ts-simptip-style-blue",
						__( "Red", "ts_visual_composer_extend" )                            => "ts-simptip-style-red",
						__( "Orange", "ts_visual_composer_extend" )                         => "ts-simptip-style-orange",
						__( "Yellow", "ts_visual_composer_extend" )                         => "ts-simptip-style-yellow",
						__( "Purple", "ts_visual_composer_extend" )                         => "ts-simptip-style-purple",
						__( "Pink", "ts_visual_composer_extend" )                           => "ts-simptip-style-pink",
						__( "White", "ts_visual_composer_extend" )                          => "ts-simptip-style-white"
					),
					"description"		=> __( "Select the tooltip style.", "ts_visual_composer_extend" ),
					"dependency"		=> array( 'element' => "tooltip_css", 'value' => 'true' )
				),
				// Animation Settings
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_5",
					"value"             => "Animations",
					"description"       => __( "", "ts_visual_composer_extend" ),
					"group" 			=> "Other Settings",
				),
				array(
					"type"				=> "switch",
					"heading"           => __( "Add Animations", "ts_visual_composer_extend" ),
					"param_name"        => "animations",
					"value"             => "false",
					"on"				=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"				=> __( 'No', "ts_visual_composer_extend" ),
					"style"				=> "select",
					"design"			=> "toggle-light",
					"description"       => __( "Switch the toggle if you want to apply animations or a shadow to the icon box.", "ts_visual_composer_extend" ),
                    "dependency"        => "",
					"group" 			=> "Other Settings",
				),	
				array(
					"type"				=> "css3animations",
					"class"				=> "",
					"heading"			=> __("Icon / Image Hover Animation", "ts_visual_composer_extend"),
					"param_name"		=> "animation_icon",
					"standard"			=> "false",
					"prefix"			=> "ts-hover-css-",
					"connector"			=> "css3animations_in",
					"noneselect"		=> "true",
					"default"			=> "",
					"value"				=> "",
					"admin_label"		=> false,
					"description"		=> __("Select the hover animation for the icon / image.", "ts_visual_composer_extend"),
					"dependency"        => array( 'element' => "animations", 'value' => 'true' ),
					"group" 			=> "Other Settings",
				),
				array(
					"type"				=> "hidden_input",
					"heading"			=> __( "Icon / Image Hover Animation", "ts_visual_composer_extend" ),
					"param_name"		=> "css3animations_in",
					"value"				=> "",
					"admin_label"		=> true,
					"description"		=> __( "", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "animations", 'value' => 'true' ),
					"group" 			=> "Other Settings",
				),	
				array(
					"type"              => "dropdown",
					"heading"           => __( "Viewport Animation", "ts_visual_composer_extend" ),
					"param_name"        => "animation_view",
					"value"             =>  array(
						__( "None", "ts_visual_composer_extend" )                          => "",
						__( "Top to Bottom", "ts_visual_composer_extend" )                 => "top-to-bottom",
						__( "Bottom to Top", "ts_visual_composer_extend" )                 => "bottom-to-top",
						__( "Left to Right", "ts_visual_composer_extend" )                 => "left-to-right",
						__( "Right to Left", "ts_visual_composer_extend" )                 => "right-to-left",
						__( "Appear from Center", "ts_visual_composer_extend" )            => "appear",
					),
					"description"       => __( "Select the viewport animation for the icon title.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "animations", 'value' => 'true' ),
					"group" 			=> "Other Settings",
				),
				// Other Icon Settings
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_6",
					"value"             => "Other Icon Settings",
					"description"       => __( "", "ts_visual_composer_extend" ),
					"group" 			=> "Other Settings",
				),
				array(
					"type"              => "textfield",
					"heading"           => __( "Define ID Name", "ts_visual_composer_extend" ),
					"param_name"        => "el_id",
					"value"             => "",
					"description"       => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
					"group" 			=> "Other Settings",
				),
				array(
					"type"              => "textfield",
					"heading"           => __( "Extra Class Name", "ts_visual_composer_extend" ),
					"param_name"        => "el_class",
					"value"             => "",
					"description"       => __( "Enter a class name for the element.", "ts_visual_composer_extend" ),
					"group" 			=> "Other Settings",
				),
				// Load Custom CSS/JS File
				array(
					"type"				=> "load_file",
					"heading"			=> __( "", "ts_visual_composer_extend" ),
					"value"				=> "",
					"param_name"		=> "el_file1",
					"file_type"			=> "js",
					"file_path"			=> "js/ts-visual-composer-extend-element.min.js",
					"description"		=> __( "", "ts_visual_composer_extend" )
				),
				array(
					"type"				=> "load_file",
					"heading"			=> __( "", "ts_visual_composer_extend" ),
					"value"				=> "",
					"param_name"		=> "el_file2",
					"file_type"			=> "css",
					"file_id"			=> "ts-extend-animations",
					"file_path"			=> "css/ts-visual-composer-extend-animations.min.css",
					"description"		=> __( "", "ts_visual_composer_extend" )
				),
			)
		));
	}
?>