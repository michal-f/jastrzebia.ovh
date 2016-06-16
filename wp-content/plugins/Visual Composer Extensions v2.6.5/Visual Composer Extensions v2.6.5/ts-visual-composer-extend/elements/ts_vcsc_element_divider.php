<?php
    if (function_exists('vc_map')) {
        vc_map( array(
            "name"                      => __( "TS Divider", "ts_visual_composer_extend" ),
            "base"                      => "TS-VCSC-Divider",
            "icon" 	                    => "icon-wpb-ts_vcsc_divider",
            "class"                     => "",
            "category"                  => __( "VC Extensions", "ts_visual_composer_extend" ),
            "description"               => __("Place a divider line element", "ts_visual_composer_extend"),
            //"admin_enqueue_js"        => array(''),
            //"admin_enqueue_css"       => array(''),
            "params"                    => array(
                // Divider Settings
                array(
                    "type"              => "seperator",
                    "heading"           => __( "", "ts_visual_composer_extend" ),
                    "param_name"        => "seperator_1",
                    "value"             => "Divider Settings",
                    "description"       => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"              => "dropdown",
                    "heading"           => __( "Divider Type", "ts_visual_composer_extend" ),
                    "param_name"        => "divider_type",
                    "width"             => 150,
                    "value"             => array(
                        __( 'Basic Line Divider', "ts_visual_composer_extend" )        		=> "ts-divider-border",
                        __( 'Divider with Text', "ts_visual_composer_extend" )    			=> "ts-divider-lines",
                        __( 'Divider with Image', "ts_visual_composer_extend" )   			=> "ts-divider-images",
                        __( 'Divider with Icon', "ts_visual_composer_extend" )    			=> "ts-divider-icons",
                        __( 'Divider To Top', "ts_visual_composer_extend" )       			=> "ts-divider-top",
                        __( 'Simple Style 1', "ts_visual_composer_extend" )					=> "ts-divider-one",
                        __( 'Simple Style 2', "ts_visual_composer_extend" )					=> "ts-divider-two",
                        __( 'Simple Style 3', "ts_visual_composer_extend" )					=> "ts-divider-three",
                        __( 'Simple Style 4', "ts_visual_composer_extend" )					=> "ts-divider-four",
                        __( 'Simple Style 5', "ts_visual_composer_extend" )					=> "ts-divider-five",
                        __( 'Simple Style 6', "ts_visual_composer_extend" )					=> "ts-divider-six",
                        __( 'Simple Style 7', "ts_visual_composer_extend" )					=> "ts-divider-seven",
                    ),
                    "admin_label"       => true,
                    "description"       => __( "Select the type of divider you want to use.", "ts_visual_composer_extend" )
                ),
                // Text Divider Settings
                array(
                    "type"              => "dropdown",
                    "heading"           => __( "Divider Text Position", "ts_visual_composer_extend" ),
                    "param_name"        => "divider_text_position",
                    "width"             => 300,
                    "value"             => array(
						__( "Center", "ts_visual_composer_extend" )                         => "center",
						__( "Left", "ts_visual_composer_extend" )                           => "left",
						__( "Right", "ts_visual_composer_extend" )                          => "right",
                    ),
                    "description"       => __( "Select the position of the text in the divider.", "ts_visual_composer_extend" ),
                    "dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-lines' )
                ),
                array(
                    "type"              => "textfield",
                    "heading"           => __( "Divider Text", "ts_visual_composer_extend" ),
                    "param_name"        => "divider_text_content",
                    "value"             => "",
                    "description"       => __( "Enter the text within the divider.", "ts_visual_composer_extend" ),
                    "dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-lines' )
                ),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Divider Color", "ts_visual_composer_extend" ),
					"param_name"        => "divider_text_border",
					"value"             => "#eeeeee",
					"description"       => __( "Define the color of the divider line.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-lines' )
				),
                // Image Divider Settings
                array(
                    "type"              => "dropdown",
                    "heading"           => __( "Divider Icon / Image Position", "ts_visual_composer_extend" ),
                    "param_name"        => "divider_image_position",
                    "width"             => 300,
                    "value"             => array(
						__( "Center", "ts_visual_composer_extend" )                         => "center",
						__( "Left", "ts_visual_composer_extend" )                           => "left",
						__( "Right", "ts_visual_composer_extend" )                          => "right",
                    ),
                    "description"       => __( "Select the position of the icon / image in the divider.", "ts_visual_composer_extend" ),
                    "dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-images' )
                ),
				array(
					"type"              => "attach_image",
					"heading"           => __( "Select Image", "ts_visual_composer_extend" ),
					"param_name"        => "divider_image_content",
					"value"             => "",
					"description"       => __( "Image must have equal dimensions for scaling purposes (i.e. 100x100).", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-images' )
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Divider Color", "ts_visual_composer_extend" ),
					"param_name"        => "divider_image_border",
					"value"             => "#eeeeee",
					"description"       => __( "Define the color of the divider line.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-images' )
				),
                // Icon Devider Settings
                array(
                    "type"              => "dropdown",
                    "heading"           => __( "Divider Icon Position", "ts_visual_composer_extend" ),
                    "param_name"        => "divider_icon_position",
                    "width"             => 300,
                    "value"             => array(
						__( "Center", "ts_visual_composer_extend" )                         => "center",
						__( "Left", "ts_visual_composer_extend" )                           => "left",
						__( "Right", "ts_visual_composer_extend" )                          => "right",
                    ),
                    "description"       => __( "Select the position of the icon in the divider.", "ts_visual_composer_extend" ),
                    "dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-icons' )
                ),
				array(
					"type"              => "icons_panel",
					"heading"           => __( "Select Icon", "ts_visual_composer_extend" ),
					"param_name"        => "divider_icon_content",
					"value"             => $this->TS_VCSC_List_Icons_Full,
					"description"       => __( "Select the icon you want to display.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-icons' )
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Icon Color", "ts_visual_composer_extend" ),
					"param_name"        => "divider_icon_color",
					"value"             => "#cccccc",
					"description"       => __( "Define the color of the icon.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-icons' )
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Divider Color", "ts_visual_composer_extend" ),
					"param_name"        => "divider_icon_border",
					"value"             => "#eeeeee",
					"description"       => __( "Define the color of the divider line.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-icons' )
				),
                // Simple Border Settings
                array(
                    "type"              => "dropdown",
                    "heading"           => __( "Divider Border Type", "ts_visual_composer_extend" ),
                    "param_name"        => "divider_border_type",
                    "width"             => 300,
                    "value"             => array(
						__( "Solid Border", "ts_visual_composer_extend" )                  => "solid",
						__( "Dotted Border", "ts_visual_composer_extend" )                 => "dotted",
						__( "Dashed Border", "ts_visual_composer_extend" )                 => "dashed",
						__( "Double Border", "ts_visual_composer_extend" )                 => "double",
						__( "Grouve Border", "ts_visual_composer_extend" )                 => "groove",
						__( "Ridge Border", "ts_visual_composer_extend" )                  => "ridge",
						__( "Inset Border", "ts_visual_composer_extend" )                  => "inset",
						__( "Outset Border", "ts_visual_composer_extend" )                 => "outset"
                    ),
                    "description"       => __( "Select the type of divider border.", "ts_visual_composer_extend" ),
                    "dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-border' )
                ),
                array(
                    "type"              => "nouislider",
                    "heading"           => __( "Divider Border Thickness", "ts_visual_composer_extend" ),
                    "param_name"        => "divider_border_thick",
                    "value"             => "1",
                    "min"               => "1",
                    "max"               => "10",
                    "step"              => "1",
                    "unit"              => 'px',
                    "description"       => __( "Define the thickness of the divider border.", "ts_visual_composer_extend" ),
                    "dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-border' )
                ),
                array(
                    "type"              => "colorpicker",
                    "heading"           => __( "Divider Border Color", "ts_visual_composer_extend" ),
                    "param_name"        => "divider_border_color",
                    "value"             => "#eeeeee",
                    "description"       => __( "Define the color of the divider border.", "ts_visual_composer_extend" ),
                    "dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-border' )
                ),
                // To Top Divider Settings
                array(
                    "type"              => "textfield",
                    "heading"           => __( "To Top Text", "ts_visual_composer_extend" ),
                    "param_name"        => "divider_top_content",
                    "value"             => "",
                    "description"       => __( "Enter the text for the divider.", "ts_visual_composer_extend" ),
                    "dependency"        => array( 'element' => "divider_type", 'value' => 'ts-divider-top' )
                ),
                // Other Divider Settings
                array(
                    "type"              => "seperator",
                    "heading"           => __( "", "ts_visual_composer_extend" ),
                    "param_name"        => "seperator_2",
                    "value"             => "Other Settings",
                    "description"       => __( "", "ts_visual_composer_extend" ),
					"group" 			=> "Other Settings",
                ),
                array(
                    "type"              => "nouislider",
                    "heading"           => __( "Margin: Top", "ts_visual_composer_extend" ),
                    "param_name"        => "margin_top",
                    "value"             => "20",
                    "min"               => "-50",
                    "max"               => "500",
                    "step"              => "1",
                    "unit"              => 'px',
                    "description"       => __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
					"group" 			=> "Other Settings",
                ),
                array(
                    "type"              => "nouislider",
                    "heading"           => __( "Margin: Bottom", "ts_visual_composer_extend" ),
                    "param_name"        => "margin_bottom",
                    "value"             => "20",
                    "min"               => "-50",
                    "max"               => "500",
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
					"type"              => "load_file",
					"heading"           => __( "", "ts_visual_composer_extend" ),
                    "param_name"        => "el_file",
					"value"             => "",
					"file_type"         => "js",
					"file_path"         => "js/ts-visual-composer-extend-element.min.js",
					"description"       => __( "", "ts_visual_composer_extend" )
				),
            ))
        );
    }
?>