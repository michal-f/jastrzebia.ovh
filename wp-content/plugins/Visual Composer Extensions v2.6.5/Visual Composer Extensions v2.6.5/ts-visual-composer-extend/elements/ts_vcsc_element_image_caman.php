<?php
    if (function_exists('vc_map')) {
        vc_map( array(
            "name"                          => __( "TS Image Caman", "ts_visual_composer_extend" ),
            "base"                          => "TS-VCSC-Image-Caman",
            "icon"                          => "icon-wpb-ts_vcsc_image_caman",
            "class"                         => "ts_vcsc_main_image_caman",
            "category"                      => __( "VC Extensions", "ts_visual_composer_extend" ),
            "description" 		            => __("Place an image with Caman effects", "ts_visual_composer_extend"),
            //"admin_enqueue_js"            => array(''),
            //"admin_enqueue_css"           => array(''),
            "params"                        => array(
                // Image Selection and Dimensions
                array(
                    "type"                  => "seperator",
                    "heading"               => __( "", "ts_visual_composer_extend" ),
                    "param_name"            => "seperator_1",
                    "value"                 => "Image Selection / Dimensions",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "attach_image",
					"holder" 				=> "img",
                    "heading"               => __( "Image", "ts_visual_composer_extend" ),
                    "param_name"            => "image",
					"class"					=> "ts_vcsc_holder_image",
                    "value"                 => "",
                    "admin_label"           => false,
                    "description"           => __( "Select the image you want to use.", "ts_visual_composer_extend" )
                ),
				array(
					"type"             	 	=> "switch",
					"heading"			    => __( "Add Custom ALT Attribute", "ts_visual_composer_extend" ),
					"param_name"		    => "attribute_alt",
					"value"				    => "false",
					"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"					=> __( 'No', "ts_visual_composer_extend" ),
					"style"					=> "select",
					"design"				=> "toggle-light",
                    "description"       	=> __( "Switch the toggle if you want add a custom ALT attribute value, otherwise file name will be set.", "ts_visual_composer_extend" ),
                    "dependency"        	=> ""
				),
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Enter ALT Value", "ts_visual_composer_extend" ),
                    "param_name"            => "attribute_alt_value",
                    "value"                 => "",
                    "description"           => __( "Enter a custom value for the ALT attribute for this image.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "attribute_alt", 'value' => 'true' )
                ),
                // Image Styles
                array(
                    "type"                  => "seperator",
                    "heading"               => __( "", "ts_visual_composer_extend" ),
                    "param_name"            => "seperator_2",
                    "value"                 => "Image Effect",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "dropdown",
                    "heading"               => __( "Effect", "ts_visual_composer_extend" ),
                    "param_name"            => "caman_effect",
                    "width"                 => 150,
                    "admin_label"           => true,
                    "value"                 => array(
                        __( 'Vintage', "ts_visual_composer_extend" )          				=> "vintage",
                        __( 'Lomo', "ts_visual_composer_extend" )             				=> "lomo",
                        __( 'Clarity', "ts_visual_composer_extend" )          				=> "clarity",
                        __( 'Sin City', "ts_visual_composer_extend" )         				=> "sinCity",
                        __( 'Sunrise', "ts_visual_composer_extend" )          				=> "sunrise",
                        __( 'Cross Process', "ts_visual_composer_extend" )    				=> "crossProcess",
                        __( 'Orange Peel', "ts_visual_composer_extend" )      				=> "orangePeel",
                        __( 'Love', "ts_visual_composer_extend" )             				=> "love",
                        __( 'Grungy', "ts_visual_composer_extend" )           				=> "grungy",
                        __( 'Jarques', "ts_visual_composer_extend" )          				=> "jarques",
                        __( 'Pin Hole', "ts_visual_composer_extend" )         				=> "pinhole",
                        __( 'Old Boot', "ts_visual_composer_extend" )         				=> "oldBoot",
                        __( 'Glowing Sun', "ts_visual_composer_extend" )      				=> "glowingSun",
                        __( 'Hazy Days', "ts_visual_composer_extend" )        				=> "hazyDays",
                        __( 'Her Majesty', "ts_visual_composer_extend" )      				=> "herMajesty",
                        __( 'Nostalgia', "ts_visual_composer_extend" )        				=> "nostalgia",
                        __( 'Hemingway', "ts_visual_composer_extend" )        				=> "hemingway",
                        __( 'Concentrate', "ts_visual_composer_extend" )      				=> "concentrate",
                        __( 'Emboss', "ts_visual_composer_extend" )           				=> "emboss",
                        __( 'Grayscale', "ts_visual_composer_extend" )        				=> "greyscale",
                        __( 'Invert', "ts_visual_composer_extend" )           				=> "invert",
                    ),
                    "description"           => __( "Select the effect you want to apply to the image.", "ts_visual_composer_extend" )
                ),
				array(
					"type"             	 	=> "switch",
					"heading"			    => __( "Allow for Switch Effekt", "ts_visual_composer_extend" ),
					"param_name"		    => "caman_switch_allow",
					"value"				    => "true",
					"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"					=> __( 'No', "ts_visual_composer_extend" ),
					"style"					=> "select",
					"design"				=> "toggle-light",
					"admin_label"           => true,
                    "description"       	=> __( "Switch the toggle if you want to provide a switch option between the Caman and the Original image.", "ts_visual_composer_extend" ),
                    "dependency"        	=> ""
				),
                array(
                    "type"                  => "dropdown",
                    "heading"               => __( "Switch Style", "ts_visual_composer_extend" ),
                    "param_name"            => "caman_switch_type",
                    "width"                 => 300,
                    "value"                 => array(
                        __( 'Flip', "ts_visual_composer_extend" )             				=> "ts-imageswitch-flip",
                        __( 'Fade', "ts_visual_composer_extend" )             				=> "ts-imageswitch-fade",
                    ),
                    "description"           => __( "Define how the two images should be switched out.", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "caman_switch_allow", 'value' => 'true' )
                ),
                array(
                    "type"                  => "dropdown",
                    "heading"               => __( "Trigger Type", "ts_visual_composer_extend" ),
                    "param_name"            => "caman_trigger_flip",
                    "width"                 => 300,
                    "value"                 => array(
						__( "Click", "ts_visual_composer_extend" )							=> "ts-trigger-click",
						__( "Hover", "ts_visual_composer_extend" )							=> "ts-trigger-hover",
                    ),
                    "description"           => __( "Define how to trigger the image switch.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "caman_switch_type", 'value' => 'ts-imageswitch-flip' )
                ),
                array(
                    "type"                  => "dropdown",
                    "heading"               => __( "Trigger Type", "ts_visual_composer_extend" ),
                    "param_name"            => "caman_trigger_fade",
                    "width"                 => 300,
                    "value"                 => array(
						__( "Click", "ts_visual_composer_extend" )							=> "ts-trigger-click",
						__( "Hover", "ts_visual_composer_extend" )							=> "ts-trigger-hover",
                    ),
                    "description"           => __( "Define how to trigger the image switch.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "caman_switch_type", 'value' => 'ts-imageswitch-fade' )
                ),
				array(
					"type"             	 	=> "switch",
                    "heading"               => __( "Show Caman Handle", "ts_visual_composer_extend" ),
                    "param_name"            => "caman_handle_show",
                    "value"                 => "true",
					"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"					=> __( 'No', "ts_visual_composer_extend" ),
					"style"					=> "select",
					"design"				=> "toggle-light",
                    "description"       	=> __( "Use the toggle to show or hide a handle button below the image.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "caman_switch_allow", 'value' => 'true' )
				),
                array(
                    "type"                  => "colorpicker",
                    "heading"               => __( "Handle Color", "ts_visual_composer_extend" ),
                    "param_name"            => "caman_handle_color",
                    "value"                 => "#0094FF",
                    "description"           => __( "Define the color for the Caman handle button.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "caman_handle_show", 'value' => 'true' )
                ),
                // Image Tooltip
                array(
                    "type"                  => "seperator",
                    "heading"               => __( "", "ts_visual_composer_extend" ),
                    "param_name"            => "seperator_5",
                    "value"                 => "Image Tooltip",
                    "description"           => __( "", "ts_visual_composer_extend" ),
					"group" 				=> "Tooltip Settings",
                ),
				array(
					"type"             	 	=> "switch",
                    "heading"               => __( "Use CSS3 Tooltip", "ts_visual_composer_extend" ),
                    "param_name"            => "tooltip_css",
                    "value"                 => "false",
					"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"					=> __( 'No', "ts_visual_composer_extend" ),
					"style"					=> "select",
					"design"				=> "toggle-light",
                    "description"       	=> __( "Switch the toggle if you want to apply a CSS3 tooltip to the image.", "ts_visual_composer_extend" ),
                    "dependency"        	=> "",
					"group" 				=> "Tooltip Settings",
				),
                array(
                    "type"                  => "textarea",
                    "class"                 => "",
                    "heading"               => __( "Tooltip Content", "ts_visual_composer_extend" ),
                    "param_name"            => "tooltip_content",
                    "value"                 => "",
                    "description"           => __( "Enter the tooltip content here (do not use quotation marks).", "ts_visual_composer_extend" ),
                    "dependency"            => "",
					"group" 				=> "Tooltip Settings",
                ),
                array(
                    "type"			        => "dropdown",
                    "class"			        => "",
                    "heading"               => __( "Tooltip Style", "ts_visual_composer_extend" ),
                    "param_name"	        => "tooltip_style",
                    "value"                 => array(
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
                    "description"           => __( "Select the tooltip style.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "tooltip_css", 'value' => 'true' ),
					"group" 				=> "Tooltip Settings",
                ),
                // Other Settings
                array(
                    "type"                  => "seperator",
                    "heading"               => __( "", "ts_visual_composer_extend" ),
                    "param_name"            => "seperator_6",
                    "value"                 => "Other Settings",
                    "description"           => __( "", "ts_visual_composer_extend" ),
					"group" 				=> "Other Settings",
                ),
                array(
                    "type"                  => "nouislider",
                    "heading"               => __( "Margin: Top", "ts_visual_composer_extend" ),
                    "param_name"            => "margin_top",
                    "value"                 => "0",
                    "min"                   => "0",
                    "max"                   => "200",
                    "step"                  => "1",
                    "unit"                  => 'px',
                    "description"           => __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
					"group" 				=> "Other Settings",
                ),
                array(
                    "type"                  => "nouislider",
                    "heading"               => __( "Margin: Bottom", "ts_visual_composer_extend" ),
                    "param_name"            => "margin_bottom",
                    "value"                 => "0",
                    "min"                   => "0",
                    "max"                   => "200",
                    "step"                  => "1",
                    "unit"                  => 'px',
                    "description"           => __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
					"group" 				=> "Other Settings",
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Define ID Name", "ts_visual_composer_extend" ),
                    "param_name"            => "el_id",
                    "value"                 => "",
                    "description"           => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
					"group" 				=> "Other Settings",
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Extra Class Name", "ts_visual_composer_extend" ),
                    "param_name"            => "el_class",
                    "value"                 => "",
                    "description"           => __( "Enter a class name for the element.", "ts_visual_composer_extend" ),
					"group" 				=> "Other Settings",
                ),
                // Load Custom CSS/JS File
                array(
                    "type"                  => "load_file",
                    "heading"               => __( "", "ts_visual_composer_extend" ),
                    "param_name"            => "el_file",
                    "value"                 => "",
                    "file_type"             => "js",
                    "file_path"             => "js/ts-visual-composer-extend-element.min.js",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
            )
        ));
    }
?>