<?php
	if (function_exists('vc_map')) {
		vc_map( array(
			"name"                      => __( "TS Icon Box (Deprecated)", "ts_visual_composer_extend" ),
			"base"                      => "TS-VCSC-Icon-Box",
			"icon" 	                    => "icon-wpb-ts_vcsc_icon_box",
			"class"                     => "",
			"category"                  => __('VC Extensions (Deprecated)', "ts_visual_composer_extend"),
			"description" 		    	=> __("Place an icon or image box", "ts_visual_composer_extend"),
			//"admin_enqueue_js"		=> array(ts_fb_get_resource_url('/js/...')),
			//"admin_enqueue_css"		=> array(ts_fb_get_resource_url('/css/...')),
			"params"                    => array(
				// Icon Box Design
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_1",
					"value"             => "Box Design",
					"description"       => __( "", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Icon Box Style", "ts_visual_composer_extend" ),
					"param_name"        => "style",
					"width"             => 300,
					"value"             => array(
						__( 'Icon Inside - Left', "ts_visual_composer_extend" )       => "icon_left",
						__( 'Icon Inside - Top', "ts_visual_composer_extend" )        => "icon_top",
						__( 'Icon Outside - Left', "ts_visual_composer_extend" )      => "boxed_left",
						__( 'Icon Outside - Top', "ts_visual_composer_extend" )       => "boxed_top",
					),
					"description"       => __( "Select the general layout of your icon box.", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Box Background Style", "ts_visual_composer_extend" ),
					"param_name"        => "box_background_type",
					"width"             => 300,
					"value"             => array(
						__( "Solid Color", "ts_visual_composer_extend" )                   => "color",
						__( "Background Pattern", "ts_visual_composer_extend" )            => "pattern",
					),
					"description"       => __( "Select the background type for your icon box.", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Box Background Color", "ts_visual_composer_extend" ),
					"param_name"        => "box_background_color",
					"value"             => "#ffffff",
					"description"       => __( "Select the background color for your icon box.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "box_background_type", 'value' => 'color' )
				),
				array(
					"type"              => "background",
					"heading"           => __( "Box Background Pattern", "ts_visual_composer_extend" ),
					"param_name"        => "box_background_pattern",
					"width"             => 200,
					"value"             => $this->TS_VCSC_Background_List,
					"encoding"          => "false",
					"description"       => __( "Select the background pattern for your icon box.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "box_background_type", 'value' => 'pattern' )
				),
				// Box Title Settings
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_2",
					"value"             => "Box Title",
					"description"       => __( "", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "textfield",
					"heading"           => __( "Title", "ts_visual_composer_extend" ),
					"param_name"        => "title",
					"value"             => "",
					"admin_label"       => true,
					"description"       => __( "Enter the title for your icon box.", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "Title Font Size", "ts_visual_composer_extend" ),
					"param_name"        => "title_size",
					"value"             => "24",
					"min"               => "10",
					"max"               => "50",
					"step"              => "1",
					"unit"              => 'px',
					"description"       => __( "Select the title size for your icon box.", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Title Font Weight", "ts_visual_composer_extend" ),
					"param_name"        => "title_weight",
					"width"             => 200,
					"value"             => array(
						__( 'Default', "ts_visual_composer_extend" )      => "inhert",
						__( 'Bold', "ts_visual_composer_extend" )         => "bold",
						__( 'Bolder', "ts_visual_composer_extend" )       => "bolder",
						__( 'Normal', "ts_visual_composer_extend" )       => "normal",
						__( 'Light', "ts_visual_composer_extend" )        => "300",
					),
					"description"       => __( "Select the title weight for your icon box.", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Title Color", "ts_visual_composer_extend" ),
					"param_name"        => "title_color",
					"value"             => "#000000",
					"description"       => __( "Select the title color for your icon box.", "ts_visual_composer_extend" ),
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Title Text Align", "ts_visual_composer_extend" ),
					"param_name"        => "title_align",
					"width"             => 150,
					"value"             => array(
						__( "Center", "ts_visual_composer_extend" )                        => "center",
						__( "Left", "ts_visual_composer_extend" )                          => "left",
						__( "Right", "ts_visual_composer_extend" )                         => "right",
					),
					"description"       => __( "Select the title alignment for your icon box.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "style", 'value' => array( 'boxed_top', 'boxed_left' ) )
				),
				/*array(
					"type"              => "nouislider",
					"heading"           => __( "Title Margin", "ts_visual_composer_extend" ),
					"param_name"        => "title_margin",
					"value"             => "0",
					"min"               => "-100",
					"max"               => "100",
					"step"              => "1",
					"unit"              => 'px',
					"description"       => __( "Select the title top margin for your icon box.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "style", 'value' => array( 'boxed_top', 'boxed_left' ) )
				),*/
				// Box Content Settings
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_3",
					"value"             => "Box Content",
					"description"       => __( "", "ts_visual_composer_extend" )
				),
				array(
					"type"				=> "switch",
                    "heading"           => __( "Allow HTML Code", "ts_visual_composer_extend" ),
                    "param_name"        => "content_html",
                    "value"             => "false",
					"on"				=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"				=> __( 'No', "ts_visual_composer_extend" ),
					"style"				=> "select",
					"design"			=> "toggle-light",
					"description"       => __( "Switch the toggle to allow for HTML code to create the icon box content.", "ts_visual_composer_extend" ),
                    "dependency"		=> ""
				),
                array(
                    "type"              => "textarea",
                    "class"             => "",
                    "heading"           => __( "Content", "ts_visual_composer_extend" ),
                    "param_name"        => "content_text",
                    "value"             => "",
                    "description"       => __( "Enter the main icon box content; HTML code can NOT be used.", "ts_visual_composer_extend" ),
                    "dependency"        => array( 'element' => "content_html", 'value' => 'false' )
                ),
				array(
					"type"              => "textarea_raw_html",
					"heading"           => __( "Content", "ts_visual_composer_extend" ),
					"param_name"        => "content_text_html",
					"value"             => base64_encode(""),
					"description"       => __( "Enter the main icon box content; HTML code can be used.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "content_html", 'value' => 'true' )
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Content Color", "ts_visual_composer_extend" ),
					"param_name"        => "content_color",
					"value"             => "#000000",
					"description"       => __( "Select the font color for your icon box content.", "ts_visual_composer_extend" ),
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Content Text Align", "ts_visual_composer_extend" ),
					"param_name"        => "content_align",
					"width"             => 150,
					"value"             => array(
						__( "Center", "ts_visual_composer_extend" )                        => "center",
						__( "Left", "ts_visual_composer_extend" )                          => "left",
						__( "Right", "ts_visual_composer_extend" )                         => "right",
						__( "Justify", "ts_visual_composer_extend" )                       => "justify"
					),
					"description"       => __( "Select the title alignment for your icon box.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "style", 'value' => array( 'boxed_top', 'boxed_left' ) )
				),
				// Box Icon Settings
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_5",
					"value"             => "Box Icon / Image",
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
					"description"       => __( "Switch the toggle to either use and icon or a normal image.", "ts_visual_composer_extend" ),
                    "dependency"		=> ""
				),
				array(
					"type"              => "icons_panel",
					"heading"           => __( "Icon", "ts_visual_composer_extend" ),
					"param_name"        => "icon",
					"value"             => $this->TS_VCSC_List_Icons_Full,
					"admin_label"       => true,
					"description"       => __( "Select the icon for your icon box.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "icon_replace", 'value' => 'false' )
				),
				array(
					"type"              => "attach_image",
					"heading"           => __( "Select Image", "ts_visual_composer_extend" ),
					"param_name"        => "icon_image",
					"value"             => "false",
					"description"       => __( "Image must have equal dimensions for scaling purposes (i.e. 100x100).", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "icon_replace", 'value' => 'true' )
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Icon Color", "ts_visual_composer_extend" ),
					"param_name"        => "icon_color",
					"value"             => "#000000",
					"description"       => __( "Select the color of the icon for your icon box.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "icon_replace", 'value' => 'false' )
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Icon / Image Background Color", "ts_visual_composer_extend" ),
					"param_name"        => "icon_background",
					"value"             => "",
					"description"       => __( "Select the background color of the icon or transparent image.", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "Icon / Image Size", "ts_visual_composer_extend" ),
					"param_name"        => "icon_size_slide",
					"value"             => "36",
					"min"               => "16",
					"max"               => "512",
					"step"              => "1",
					"unit"              => 'px',
					"description"       => __( "Select the size of the icon.", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "Icon / Image to Text Spacing", "ts_visual_composer_extend" ),
					"param_name"        => "icon_margin",
					"value"             => "10",
					"min"               => "0",
					"max"               => "50",
					"step"              => "1",
					"unit"              => 'px',
					"description"       => __( "Define the space between the icon / image and the icon box content.", "ts_visual_composer_extend" ),
					"dependency"        => ""
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
						__( "Outset Border", "ts_visual_composer_extend" )                 => "outset"
					),
					"description"       => __( "Select the border type for the icon or image.", "ts_visual_composer_extend" )
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
					"description"       => __( "Select the thickness for the icon / image border.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "icon_frame_type", 'value' => $this->TS_VCSC_Border_Type_Values )
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Icon / Image Frame Border Radius", "ts_visual_composer_extend" ),
					"param_name"        => "icon_frame_radius",
					"value"             => array(
						__( "None", "ts_visual_composer_extend" )                          => "",
						__( "Small Radius", "ts_visual_composer_extend" )                  => "ts-radius-small",
						__( "Medium Radius", "ts_visual_composer_extend" )                 => "ts-radius-medium",
						__( "Large Radius", "ts_visual_composer_extend" )                  => "ts-radius-large",
						__( "Full Circle", "ts_visual_composer_extend" )                   => "ts-radius-full"
					),
					"description"       => __( "Select the radius for your icon / image border.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "icon_frame_type", 'value' => $this->TS_VCSC_Border_Type_Values )
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Icon / Image Frame Border Color", "ts_visual_composer_extend" ),
					"param_name"        => "icon_frame_color",
					"value"             => "#000000",
					"description"       => __( "Select the color for your icon border.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "icon_frame_type", 'value' => $this->TS_VCSC_Border_Type_Values )
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "Icon / Image Padding", "ts_visual_composer_extend" ),
					"param_name"        => "icon_padding",
					"value"             => "5",
					"min"               => "0",
					"max"               => "50",
					"step"              => "1",
					"unit"              => 'px',
					"description"       => __( "Define a padding for your icon / image.", "ts_visual_composer_extend" )
				),
				// Box Border Settings
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_6",
					"value"             => "Box Border",
					"description"       => __( "", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Box Border Style", "ts_visual_composer_extend" ),
					"param_name"        => "box_border_type",
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
					"description"       => __( "Select the border type for the icon box.", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Box Border Color", "ts_visual_composer_extend" ),
					"param_name"        => "box_border_color",
					"value"             => "#000000",
					"description"       => __( "Select the color for the icon box border.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "box_border_type", 'value' => $this->TS_VCSC_Border_Type_Values )
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "Box Border Thickness", "ts_visual_composer_extend" ),
					"param_name"        => "box_border_thick",
					"value"             => "1",
					"min"               => "1",
					"max"               => "10",
					"step"              => "1",
					"unit"              => 'px',
					"description"       => __( "Select the thickness for the icon box border.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "box_border_type", 'value' => $this->TS_VCSC_Border_Type_Values )
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Box Frame Border Radius", "ts_visual_composer_extend" ),
					"param_name"        => "box_border_radius",
					"value"             => array(
						__( "None", "ts_visual_composer_extend" )                          => "",
						__( "Small Radius", "ts_visual_composer_extend" )                  => "ts-radius-small",
						__( "Medium Radius", "ts_visual_composer_extend" )                 => "ts-radius-medium",
						__( "Large Radius", "ts_visual_composer_extend" )                  => "ts-radius-large",
					),
					"description"       => __( "Select the radius for the icon box border.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "box_border_type", 'value' => $this->TS_VCSC_Border_Type_Values )
				),
				// Read More Button Settings
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_4",
					"value"             => "Read More Link",
					"description"       => __( "", "ts_visual_composer_extend" )
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Add Link", "ts_visual_composer_extend" ),
					"param_name"        => "read_more_link",
					"width"             => 300,
					"value"             => array(
						__( 'None', "ts_visual_composer_extend" )             => "false",
						__( 'Link Button', "ts_visual_composer_extend" )      => "button",
						__( 'Link Entire Box', "ts_visual_composer_extend" )  => "box",
					),
					"description"       => __( "Select the type of link to be applied to the icon box.", "ts_visual_composer_extend" ),
				),
				array(
					"type"              => "textfield",
					"heading"           => __( "Read More Button Text", "ts_visual_composer_extend" ),
					"param_name"        => "read_more_txt",
					"value"             => "",
					"description"       => __( "Enter the text to be shown in the link button.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "read_more_link", 'value' => 'button' )
				),
				array(
					"type"              => "textfield",
					"heading"           => __( "Read More URL", "ts_visual_composer_extend" ),
					"param_name"        => "read_more_url",
					"value"             => "",
					"description"       => __( "Enter the URL for the link (starting with http://).", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "read_more_link", 'value' => array('button', 'box') )
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Button Design", "ts_visual_composer_extend" ),
					"param_name"        => "read_more_style",
					"width"             => 300,
					"value"             => array(
						__( 'Style 1', "ts_visual_composer_extend" )          => "1",
						__( 'Style 2', "ts_visual_composer_extend" )          => "2",
						__( 'Style 3', "ts_visual_composer_extend" )          => "3",
						__( 'Style 4', "ts_visual_composer_extend" )          => "4",
						__( 'Style 5', "ts_visual_composer_extend" )          => "5"
					),
					"description"       => __( "Select the button style for the link.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "read_more_link", 'value' => 'button' )
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Link Target", "ts_visual_composer_extend" ),
					"param_name"        => "read_more_target",
					"value"             => array(
						__( "Same Window", "ts_visual_composer_extend" )                    => "_parent",
						__( "New Window", "ts_visual_composer_extend" )                     => "_blank"
					),
					"description"       => __( "Define how the link should be opened.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "read_more_url", 'not_empty' => true )
				),
				// Animation Settings
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_7",
					"value"             => "Animations",
					"description"       => __( "", "ts_visual_composer_extend" )
				),
				array(
					"type"				=> "switch",
					"heading"           => __( "Add Animations / Shadow", "ts_visual_composer_extend" ),
					"param_name"        => "animations",
					"value"             => "false",
					"on"				=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"				=> __( 'No', "ts_visual_composer_extend" ),
					"style"				=> "select",
					"design"			=> "toggle-light",
					"description"       => __( "Switch the toggle if you want to apply animations or a shadow to the icon box.", "ts_visual_composer_extend" ),
                    "dependency"		=> ""
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Icon / Image Animation Style", "ts_visual_composer_extend" ),
					"param_name"        => "animation_effect",
					"width"             => 150,
					"value"             => array(
						__( "One Time Effect while Hover", "ts_visual_composer_extend" )    			=> "ts-hover-css-",
						__( "Infinite (Looping) Effect", "ts_visual_composer_extend" )                	=> "ts-infinite-css-",
					),
					"description"       => __( "Select the animation style for the icon / image.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "animations", 'value' => 'true' )
				),
				array(
					"type"				=> "css3animations",
					"class"				=> "",
					"heading"			=> __("Icon / Image Animation", "ts_visual_composer_extend"),
					"param_name"		=> "animation_class",
					"standard"			=> "false",
					"prefix"			=> "",
					"connector"			=> "css3animations_in",
					"noneselect"		=> "true",
					"default"			=> "",
					"value"				=> "",
					"admin_label"		=> false,
					"description"		=> __("Select the animation for the icon / image.", "ts_visual_composer_extend"),
					"dependency"        => array( 'element' => "animations", 'value' => 'true' ),
				),
				array(
					"type"				=> "hidden_input",
					"heading"			=> __( "Icon / Image Animation", "ts_visual_composer_extend" ),
					"param_name"		=> "css3animations_in",
					"value"				=> "",
					"admin_label"		=> true,
					"description"		=> __( "", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "animations", 'value' => 'true' ),
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Box Hover Effect", "ts_visual_composer_extend" ),
					"param_name"        => "animation_box",
					"width"             => 300,
					"value"             => array(
						__( "None", "ts_visual_composer_extend" )                          => "",
						__( "Tilt Up", "ts_visual_composer_extend" )                       => "ts-css-effect1",
						__( "Tilt Left", "ts_visual_composer_extend" )                     => "ts-css-effect2",
						__( "Tilt Right", "ts_visual_composer_extend" )                    => "ts-css-effect3",
						__( "Tilt Left (Up)", "ts_visual_composer_extend" )                => "ts-css-effect4",
						__( "Tilt Right (Down)", "ts_visual_composer_extend" )             => "ts-css-effect5",
						__( "Grow Out", "ts_visual_composer_extend" )                      => "ts-css-effect6",
					),
					"description"       => __( "Select the hover animation for the icon box.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "animations", 'value' => 'true' )
				),
				array(
					"type"              => "dropdown",
					"heading"           => __( "Box Shadow Effect", "ts_visual_composer_extend" ),
					"param_name"        => "animation_shadow",
					"width"             => 300,
					"value"             => array(
						__( "None", "ts_visual_composer_extend" )                          => "",
						__( "Lifted", "ts_visual_composer_extend" )                        => "lifted",
						__( "Raised", "ts_visual_composer_extend" )                        => "raised",
						__( "Perspective - Right", "ts_visual_composer_extend" )           => "perspective-right",
						__( "Perspective - Left", "ts_visual_composer_extend" )            => "perspective-left",
						__( "Curved - Horizontal", "ts_visual_composer_extend" )           => "curved",
						__( "Curved - Horizontal (Top)", "ts_visual_composer_extend" )     => "curved-top",
						__( "Curved - Horizontal (Bottom)", "ts_visual_composer_extend" )  => "curved-bottom",
						__( "Curved - Vertical", "ts_visual_composer_extend" )             => "curved-vertical",
						__( "Curved - Vertical (Left)", "ts_visual_composer_extend" )      => "curved-vertical-left",
						__( "Curved - Vertical (Right)", "ts_visual_composer_extend" )     => "curved-vertical-right",
					),
					"description"       => __( "Select the shadow effect for the icon box.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "animations", 'value' => 'true' )
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
					"description"       => __( "Select the viewport animation for the icon box.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "animations", 'value' => 'true' )
				),
				// Other Icon Box Settings
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_8",
					"value"             => "Other Box Settings",
					"description"       => __( "", "ts_visual_composer_extend" ),
					"group" 			=> "Other Settings",
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "Margin: Top", "ts_visual_composer_extend" ),
					"param_name"        => "margin_top",
					"value"             => "0",
					"min"               => "-50",
					"max"               => "200",
					"step"              => "1",
					"unit"              => 'px',
					"description"       => __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
					"group" 			=> "Other Settings",
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "Margin: Bottom", "ts_visual_composer_extend" ),
					"param_name"        => "margin_bottom",
					"value"             => "0",
					"min"               => "-50",
					"max"               => "200",
					"step"              => "1",
					"unit"              => 'px',
					"description"       => __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
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
			))
		);
	}
?>