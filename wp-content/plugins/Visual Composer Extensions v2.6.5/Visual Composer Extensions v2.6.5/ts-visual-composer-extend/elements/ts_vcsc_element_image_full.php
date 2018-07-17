<?php
    if (function_exists('vc_map')) {
        vc_map( array(
            "name"                          		=> __( "TS Image Full", "ts_visual_composer_extend" ),
            "base"                          		=> "TS_VCSC_Image_Full",
            "icon"                          		=> "icon-wpb-ts_vcsc_image_full",
            "class"                         		=> "ts_vcsc_main_image_full",
            "category"                      		=> __( "VC Extensions", "ts_visual_composer_extend" ),
            "description" 		            		=> __("Place a full width image", "ts_visual_composer_extend"),
            //"admin_enqueue_js"            		=> array(''),
            //"admin_enqueue_css"           		=> array(''),
            "params"                        		=> array(
                // Image Selection and Breakout
                array(
                    "type"                  		=> "seperator",
                    "heading"               		=> __( "", "ts_visual_composer_extend" ),
                    "param_name"            		=> "seperator_1",
                    "value"                 		=> "Image Selection",
                    "description"           		=> __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  		=> "attach_image",
					"holder" 						=> "img",
                    "heading"               		=> __( "Image", "ts_visual_composer_extend" ),
                    "param_name"            		=> "image",
					"class"							=> "ts_vcsc_holder_image",
                    "value"                 		=> "",
                    "admin_label"           		=> false,
                    "description"           		=> __( "Select the image you want to use.", "ts_visual_composer_extend" )
                ),
				array(
					"type"             	 				=> "switch",
					"heading"			    		=> __( "Add Custom ALT Attribute", "ts_visual_composer_extend" ),
					"param_name"		    		=> "attribute_alt",
					"value"				    		=> "false",
					"on"							=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"							=> __( 'No', "ts_visual_composer_extend" ),
					"style"							=> "select",
					"design"						=> "toggle-light",
                    "description"       			=> __( "Switch the toggle if you want to add a custom ALT attribute value, otherwise file name will be set.", "ts_visual_composer_extend" ),
                    "dependency"        			=> ""
				),
                array(
                    "type"                  		=> "textfield",
                    "heading"               		=> __( "Enter ALT Value", "ts_visual_composer_extend" ),
                    "param_name"            		=> "attribute_alt_value",
                    "value"                 		=> "",
                    "description"           		=> __( "Enter a custom value for the ALT attribute for this image.", "ts_visual_composer_extend" ),
                    "dependency"            		=> array( 'element' => "attribute_alt", 'value' => 'true' )
                ),
				array(
					"type"                  		=> "nouislider",
					"heading"               		=> __( "Full Width Breakouts", "ts_visual_composer_extend" ),
					"param_name"            		=> "break_parents",
					"value"                 		=> "5",
					"min"                   		=> "0",
					"max"                   		=> "99",
					"step"                  		=> "1",
					"unit"                  		=> '',
					"admin_label"           		=> true,
					"description"           		=> __( "Define the number of parent containers the image should attempt to break away from.", "ts_visual_composer_extend" ),
				),
				// Margin Settings
                array(
                    "type"                  		=> "seperator",
                    "heading"               		=> __( "", "ts_visual_composer_extend" ),
                    "param_name"            		=> "seperator_2",
                    "value"                 		=> "Other Settings",
                    "description"           		=> __( "", "ts_visual_composer_extend" ),
					"group" 						=> "Image Margins",
                ),
				array(
					"type"                  		=> "nouislider",
					"heading"               		=> __( "Margin: Left", "ts_visual_composer_extend" ),
					"param_name"            		=> "margin_left",
					"value"                 		=> "0",
					"min"                   		=> "-100",
					"max"                   		=> "100",
					"step"                  		=> "1",
					"unit"                  		=> 'px',
					"description"           		=> __( "If the image isn't exactly aligned, use left/right margins to adjust position.", "ts_visual_composer_extend" ),
					"group" 						=> "Image Margins",
				),
				array(
					"type"                  		=> "nouislider",
					"heading"               		=> __( "Margin: Right", "ts_visual_composer_extend" ),
					"param_name"            		=> "margin_right",
					"value"                 		=> "0",
					"min"                   		=> "-100",
					"max"                   		=> "100",
					"step"                  		=> "1",
					"unit"                  		=> 'px',
					"description"           		=> __( "If the image isn't exactly aligned, use left/right margins to adjust position.", "ts_visual_composer_extend" ),
					"group" 						=> "Image Margins",
				),
                array(
                    "type"                  		=> "nouislider",
                    "heading"               		=> __( "Margin: Top", "ts_visual_composer_extend" ),
                    "param_name"            		=> "margin_top",
                    "value"                 		=> "0",
                    "min"                   		=> "0",
                    "max"                   		=> "200",
                    "step"                  		=> "1",
                    "unit"                  		=> 'px',
                    "description"           		=> __( "Select the top margin for your image.", "ts_visual_composer_extend" ),
					"group" 						=> "Image Margins",
                ),
                array(
                    "type"                  		=> "nouislider",
                    "heading"               		=> __( "Margin: Bottom", "ts_visual_composer_extend" ),
                    "param_name"            		=> "margin_bottom",
                    "value"                 		=> "0",
                    "min"                   		=> "0",
                    "max"                   		=> "200",
                    "step"                  		=> "1",
                    "unit"                  		=> 'px',
                    "description"           		=> __( "Select the bottom margin for your image.", "ts_visual_composer_extend" ),
					"group" 						=> "Image Margins",
                ),
                // Other Settings
                array(
                    "type"                  		=> "seperator",
                    "heading"               		=> __( "", "ts_visual_composer_extend" ),
                    "param_name"            		=> "seperator_3",
                    "value"                 		=> "Other Settings",
                    "description"           		=> __( "", "ts_visual_composer_extend" ),
					"group" 						=> "Other Settings",
                ),
                array(
                    "type"                  		=> "textfield",
                    "heading"               		=> __( "Define ID Name", "ts_visual_composer_extend" ),
                    "param_name"            		=> "el_id",
                    "value"                 		=> "",
                    "description"           		=> __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
					"group" 						=> "Other Settings",
                ),
                array(
                    "type"                  		=> "textfield",
                    "heading"               		=> __( "Extra Class Name", "ts_visual_composer_extend" ),
                    "param_name"            		=> "el_class",
                    "value"                 		=> "",
                    "description"           		=> __( "Enter a class name for the element.", "ts_visual_composer_extend" ),
					"group" 						=> "Other Settings",
                ),
                // Load Custom CSS/JS File
                array(
                    "type"                  		=> "load_file",
                    "heading"               		=> __( "", "ts_visual_composer_extend" ),
                    "param_name"            		=> "el_file",
                    "value"                 		=> "",
                    "file_type"             		=> "js",
                    "file_path"             		=> "js/ts-visual-composer-extend-element.min.js",
                    "description"           		=> __( "", "ts_visual_composer_extend" )
                ),
            )
        ));
    }
?>