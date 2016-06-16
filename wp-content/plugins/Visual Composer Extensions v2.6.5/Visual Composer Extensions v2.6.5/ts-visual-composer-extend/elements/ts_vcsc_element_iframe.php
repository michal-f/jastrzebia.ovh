<?php
    if (function_exists('vc_map')) {
        vc_map( array(
            "name"                          => __( "TS iFrame Embed", "ts_visual_composer_extend" ),
            "base"                          => "TS-VCSC-IFrame",
            "icon" 	                        => "icon-wpb-ts_vcsc_iframe",
            "class"                         => "",
            "category"                      => __( "VC Extensions", "ts_visual_composer_extend" ),
            "description"                   => __("Place an iFrame element", "ts_visual_composer_extend"),
            "admin_enqueue_js"              => "",
            "admin_enqueue_css"             => "",
            "params"                        => array(
                // Embed iFrame
                array(
                    "type"                  => "seperator",
                    "heading"               => __( "", "ts_visual_composer_extend" ),
                    "param_name"            => "seperator_1",
                    "value"                 => "iFrame Settings",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "iFrame URL", "ts_visual_composer_extend" ),
                    "param_name"            => "content_iframe",
                    "value"                 => "",
                    "admin_label"           => true,
                    "description"           => __( "Enter the URL for the iFrame.", "ts_visual_composer_extend" ),
                    "dependency"            => ""
                ),
				array(
					"type"              	=> "messenger",
					"heading"           	=> __( "", "ts_visual_composer_extend" ),
					"param_name"        	=> "messenger",
					"color"					=> "#FF0000",
					"weight"				=> "normal",
					"size"					=> "14",
					"value"					=> "",
					"message"            	=> __( "Please ensure that the page you are attempting to embed via iFrame is not protected by restrictive X-Frame-Options.", "ts_visual_composer_extend" ),
					"description"       	=> __( "", "ts_visual_composer_extend" )
				),
				array(
					"type"             	 	=> "switch",
					"heading"               => __( "Open in Lightbox", "ts_visual_composer_extend" ),
					"param_name"            => "content_lightbox",
					"value"                 => "false",
                    "admin_label"           => true,
					"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"					=> __( 'No', "ts_visual_composer_extend" ),
					"style"					=> "select",
					"design"				=> "toggle-light",
                    "description"       	=> __( "Switch the toggle to show the iFrame in a lightbox.", "ts_visual_composer_extend" ),
                    "dependency"        	=> ""
				),
                // iFrame Dimensions
                array(
                    "type"                  => "dropdown",
                    "heading"               => __( "iFrame Width", "ts_visual_composer_extend" ),
                    "param_name"            => "iframe_width",
                    "width"                 => 150,
                    "value"                 => array(
                        __( 'Auto', "ts_visual_composer_extend" )                 => "auto",
                        __( 'Set Width (%)', "ts_visual_composer_extend" )        => "widthpercent",
                        __( 'Set Width (px)', "ts_visual_composer_extend" )       => "widthpixel",
                    ),
                    "description"           => __( "Select the how the iFrame Width should be determined.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_lightbox", 'value' => 'false' )
                ),
				array(
					"type"                  => "nouislider",
					"heading"               => __( "iFrame Width", "ts_visual_composer_extend" ),
					"param_name"            => "iframe_width_percent",
					"value"                 => "100",
					"min"                   => "1",
					"max"                   => "100",
					"step"                  => "1",
					"unit"                  => '%',
					"description"           => __( "Select iFrame width in percent.", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "iframe_width", 'value' => 'widthpercent' )
				),
				array(
					"type"                  => "nouislider",
					"heading"               => __( "iFrame Width", "ts_visual_composer_extend" ),
					"param_name"            => "iframe_width_pixel",
					"value"                 => "1024",
					"min"                   => "1",
					"max"                   => "2048",
					"step"                  => "1",
					"unit"                  => 'px',
					"description"           => __( "Select iFrame width in px.", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "iframe_width", 'value' => 'widthpixel' )
				),
                array(
                    "type"                  => "dropdown",
                    "heading"               => __( "iFrame Height", "ts_visual_composer_extend" ),
                    "param_name"            => "iframe_height",
                    "width"                 => 150,
                    "value"                 => array(
                        __( 'Auto', "ts_visual_composer_extend" )                 => "auto",
                        __( 'Set Height (px)', "ts_visual_composer_extend" )      => "heightpixel",
                    ),
                    "description"           => __( "Select the how the iFrame Height should be determined.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_lightbox", 'value' => 'false' )
                ),
				array(
					"type"                  => "nouislider",
					"heading"               => __( "iFrame Height", "ts_visual_composer_extend" ),
					"param_name"            => "iframe_height_pixel",
					"value"                 => "400",
					"min"                   => "100",
					"max"                   => "4096",
					"step"                  => "1",
					"unit"                  => 'px',
					"description"           => __( "Select iFrame height in px.", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "iframe_height", 'value' => 'heightpixel' )
				),
				array(
					"type"             	 	=> "switch",
					"heading"			    => __( "Make iFrame Transparent", "ts_visual_composer_extend" ),
					"param_name"		    => "iframe_transparency",
					"value"				    => "true",
					"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"					=> __( 'No', "ts_visual_composer_extend" ),
					"style"					=> "select",
					"design"				=> "toggle-light",
					"description"		    => __( "Switch the toggle if you want the iFrame to allow for transparency.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_lightbox", 'value' => 'false' )
				),
				array(
					"type"              	=> "dropdown",
					"heading"           	=> __( "iFrame Border Type", "ts_visual_composer_extend" ),
					"param_name"        	=> "border_type",
					"width"             	=> 300,
					"value"             	=> array(
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
					"description"       	=> __( "Select the border type for the iFrame.", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "content_lightbox", 'value' => 'false' )
				),
				array(
					"type"              	=> "nouislider",
					"heading"           	=> __( "iFrame Border Thickness", "ts_visual_composer_extend" ),
					"param_name"        	=> "border_thick",
					"value"             	=> "1",
					"min"               	=> "1",
					"max"               	=> "10",
					"step"              	=> "1",
					"unit"              	=> 'px',
					"description"       	=> __( "Select the thickness for the iFrame border.", "ts_visual_composer_extend" ),
					"dependency"        	=> array( 'element' => "border_type", 'value' => $this->TS_VCSC_Border_Type_Values )
				),
				array(
					"type"              	=> "colorpicker",
					"heading"           	=> __( "iFrame Frame Border Color", "ts_visual_composer_extend" ),
					"param_name"        	=> "border_color",
					"value"             	=> "#000000",
					"description"       	=> __( "Select the color for the iFrame border.", "ts_visual_composer_extend" ),
					"dependency"        	=> array( 'element' => "border_type", 'value' => $this->TS_VCSC_Border_Type_Values )
				),				
				array(
					"type"              	=> "switch",
                    "heading"               => __( "Make iFrame Full-Width", "ts_visual_composer_extend" ),
                    "param_name"            => "iframefullwidth",
                    "value"                 => "false",
					"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"					=> __( 'No', "ts_visual_composer_extend" ),
					"style"					=> "select",
					"design"				=> "toggle-light",
					"description"           => __( "Switch the toggle if you want attempt showing the iFrame in full width (will not work with all themes).", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_lightbox", 'value' => 'false' )
				),
                array(
                    "type"                  => "nouislider",
                    "heading"               => __( "Full iFrame Breakouts", "ts_visual_composer_extend" ),
                    "param_name"            => "breakouts",
                    "value"                 => "6",
                    "min"                   => "0",
                    "max"                   => "99",
                    "step"                  => "1",
                    "unit"                  => '',
                    "description"           => __( "Define the number of parent containers the iFrame should attempt to break away from.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "iframefullwidth", 'value' => 'true' )
                ),
                // Triggger Type
				array(
					"type"                  => "dropdown",
					"heading"               => __( "Trigger Type", "ts_visual_composer_extend" ),
					"param_name"            => "content_iframe_trigger",
                    "value"                 => array(
                        __("Default Image", "ts_visual_composer_extend")          => "default",
                        __("Custom Image", "ts_visual_composer_extend")           => "image",
                        __("Font Icon", "ts_visual_composer_extend")              => "icon",
                        __("Winged Button", "ts_visual_composer_extend")          => "winged",
                        __("Simple Button", "ts_visual_composer_extend")          => "simple",
                        __("Text", "ts_visual_composer_extend")                   => "text",
                        __("Custom HTML", "ts_visual_composer_extend")            => "custom",
                    ),
					"description"           => __( "Select the type of trigger to click on in order to show the lightbox.", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "content_lightbox", 'value' => 'true' )
				),
                // Custom Image
				array(
					"type"                  => "attach_image",
					"heading"               => __( "Select Image", "ts_visual_composer_extend" ),
					"param_name"            => "content_iframe_image",
					"value"                 => "",
					"description"           => __( "Select the preview image for the lightbox content.", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "content_iframe_trigger", 'value' => 'image' )
				),
				array(
					"type"             	 	=> "switch",
					"heading"			    => __( "Simple Image Only", "ts_visual_composer_extend" ),
					"param_name"		    => "content_iframe_image_simple",
					"value"				    => "false",
					"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"					=> __( 'No', "ts_visual_composer_extend" ),
					"style"					=> "select",
					"design"				=> "toggle-light",
					"description"		    => __( "Switch the toggle if you want to display just the image without any styling.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_iframe_trigger", 'value' => 'image' )
				),
                // Font Icon
				array(
					"type"                  => "icons_panel",
					"heading"               => __( "Select Icon", "ts_visual_composer_extend" ),
					"param_name"            => "content_iframe_icon",
					"value"                 => $this->TS_VCSC_List_Icons_Full,
					"description"           => __( "Select the icon you want to display.", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "content_iframe_trigger", 'value' => 'icon' )
				),
				array(
					"type"                  => "nouislider",
					"heading"               => __( "Icon Size", "ts_visual_composer_extend" ),
					"param_name"            => "content_iframe_iconsize",
					"value"                 => "30",
					"min"                   => "16",
					"max"                   => "512",
					"step"                  => "1",
					"unit"                  => 'px',
					"description"           => __( "Select the icon / image size", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "content_iframe_trigger", 'value' => 'icon' )
				),
				array(
					"type"                  => "colorpicker",
					"heading"               => __( "Icon Color", "ts_visual_composer_extend" ),
					"param_name"            => "content_iframe_iconcolor",
					"value"                 => "#cccccc",
					"description"           => __( "Define the color of the icon.", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "content_iframe_trigger", 'value' => 'icon' )
				),
                // Button
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Button Text", "ts_visual_composer_extend" ),
                    "param_name"            => "content_iframe_buttontext",
                    "value"                 => "View Video",
                    "description"           => __( "Enter the text for the button.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_iframe_trigger", 'value' => array('winged', 'simple') )
                ),
                // Text Link
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Trigger Text", "ts_visual_composer_extend" ),
                    "param_name"            => "content_iframe_text",
                    "value"                 => "",
                    "description"           => __( "Enter the trigger text for the video.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_iframe_trigger", 'value' => 'text' )
                ),
                // Custom Code
                array(
                    "type"                  => "textarea_raw_html",
                    "heading"               => __("Raw HTML", "ts_visual_composer_extend"),
                    "param_name"            => "content_raw",
                    "value"                 => base64_encode(""),
                    "description"           => __("Enter your custom HTML code; code will be wrapped in appropriate link automatically.", "ts_visual_composer_extend"),
                    "dependency"            => array( 'element' => "content_iframe_trigger", 'value' => 'custom' )
                ),
                // Title + Subtitle
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Title", "ts_visual_composer_extend" ),
                    "param_name"            => "content_iframe_title",
                    "value"                 => "",
                    "description"           => __( "Enter a title for the lightbox content.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_iframe_trigger", 'value' => array('image', 'default', 'preview', 'winged') )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Subtitle", "ts_visual_composer_extend" ),
                    "param_name"            => "content_iframe_subtitle",
                    "value"                 => "",
                    "description"           => __( "Enter a subtitle for the lightbox content.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_iframe_trigger", 'value' => array('winged') )
                ),
                // Lightbox Settings
				array(
					"type"				    => "seperator",
					"heading"			    => __( "", "ts_visual_composer_extend" ),
					"param_name"		    => "seperator_2",
					"value"				    => "Lightbox Settings",
					"description"		    => __( "", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_iframe_trigger", 'value' => array('image', 'default', 'preview', 'simple', 'icon', 'text', 'custom', 'winged') )
				),
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Group Name", "ts_visual_composer_extend" ),
                    "param_name"            => "lightbox_group_name",
                    "value"                 => "",
                    "description"           => __( "Enter a custom group name to manually build group with other non-gallery items; leave empty for non-grouping", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_iframe_trigger", 'value' => array('image', 'default', 'preview', 'simple', 'icon', 'text', 'custom', 'winged') )
                ),
                array(
                    "type"                  => "dropdown",
                    "heading"               => __( "Transition Effect", "ts_visual_composer_extend" ),
                    "param_name"            => "lightbox_effect",
                    "width"                 => 150,
                    "value"                 => array(
                        __( 'Random', "ts_visual_composer_extend" )       	=> "random",
                        __( 'Swipe', "ts_visual_composer_extend" )        	=> "swipe",
                        __( 'Fade', "ts_visual_composer_extend" )         	=> "fade",
                        __( 'Scale', "ts_visual_composer_extend" )        	=> "scale",
                        __( 'Slide Up', "ts_visual_composer_extend" )     	=> "slideUp",
                        __( 'Slide Down', "ts_visual_composer_extend" )   	=> "slideDown",
                        __( 'Flip', "ts_visual_composer_extend" )         	=> "flip",
                        __( 'Skew', "ts_visual_composer_extend" )         	=> "skew",
                        __( 'Bounce Up', "ts_visual_composer_extend" )    	=> "bounceUp",
                        __( 'Bounce Down', "ts_visual_composer_extend" )  	=> "bounceDown",
                        __( 'Break In', "ts_visual_composer_extend" )     	=> "breakIn",
                        __( 'Rotate In', "ts_visual_composer_extend" )    	=> "rotateIn",
                        __( 'Rotate Out', "ts_visual_composer_extend" )   	=> "rotateOut",
                        __( 'Hang Left', "ts_visual_composer_extend" )    	=> "hangLeft",
                        __( 'Hang Right', "ts_visual_composer_extend" )   	=> "hangRight",
                        __( 'Cycle Up', "ts_visual_composer_extend" )     	=> "cicleUp",
                        __( 'Cycle Down', "ts_visual_composer_extend" )   	=> "cicleDown",
                        __( 'Zoom In', "ts_visual_composer_extend" )      	=> "zoomIn",
                        __( 'Throw In', "ts_visual_composer_extend" )     	=> "throwIn",
                        __( 'Fall', "ts_visual_composer_extend" )         	=> "fall",
                        __( 'Jump', "ts_visual_composer_extend" )         	=> "jump",
                    ),
                    "admin_label"           => true,
                    "description"           => __( "Select the transition effect to be used for the iframe in the lightbox.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_iframe_trigger", 'value' => array('image', 'default', 'preview', 'simple', 'icon', 'text', 'custom', 'winged') )
                ),
                // Tooltip Settings
				array(
					"type"				    => "seperator",
					"heading"			    => __( "", "ts_visual_composer_extend" ),
					"param_name"		    => "seperator_3",
					"value"					=> "",
					"seperator"				=> "Tooltip",
					"description"		    => __( "", "ts_visual_composer_extend" ),
					"group" 				=> "Tooltip Settings",
				),
				array(
					"type"              	=> "messenger",
					"heading"           	=> __( "", "ts_visual_composer_extend" ),
					"param_name"        	=> "messenger",
					"color"					=> "#FF0000",
					"weight"				=> "normal",
					"size"					=> "14",
					"value"					=> "",
					"message"            	=> __( "The tooltip settings will only be applied to the trigger element when the iFrame is shown via lightbox.", "ts_visual_composer_extend" ),
					"description"       	=> __( "", "ts_visual_composer_extend" ),
					"group" 				=> "Tooltip Settings",
				),
				array(
					"type"             	 	=> "switch",
					"heading"			    => __( "Use CSS3 Tooltip", "ts_visual_composer_extend" ),
					"param_name"		    => "content_tooltip_css",
					"value"				    => "false",
					"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"					=> __( 'No', "ts_visual_composer_extend" ),
					"style"					=> "select",
					"design"				=> "toggle-light",
					"description"		    => __( "Switch the toggle if you want to apply a CSS3 tooltip to the image.", "ts_visual_composer_extend" ),
					"group" 				=> "Tooltip Settings",
				),
				array(
					"type"				    => "textarea",
					"class"				    => "",
					"heading"			    => __( "Tooltip Content", "ts_visual_composer_extend" ),
					"param_name"		    => "content_tooltip_content",
					"value"				    => "",
					"description"		    => __( "Enter the tooltip content here (do not use quotation marks).", "ts_visual_composer_extend" ),
					"group" 				=> "Tooltip Settings",
				),
				array(
					"type"				    => "dropdown",
					"class"				    => "",
					"heading"			    => __( "Tooltip Position", "ts_visual_composer_extend" ),
					"param_name"		    => "content_tooltip_position",
                    "value"                 => array(
                        __("Top", "ts_visual_composer_extend")                    => "ts-simptip-position-top",
                        __("Bottom", "ts_visual_composer_extend")                 => "ts-simptip-position-bottom",
                    ),
					"description"		    => __( "Select the tooltip position in relation to the trigger.", "ts_visual_composer_extend" ),
					"dependency"		    => array( 'element' => "content_tooltip_css", 'value' => 'true' ),
					"group" 				=> "Tooltip Settings",
				),
				array(
					"type"				    => "dropdown",
					"class"				    => "",
					"heading"			    => __( "Tooltip Style", "ts_visual_composer_extend" ),
					"param_name"		    => "content_tooltip_style",
                    "value"                 => array(
                        __("Black", "ts_visual_composer_extend")                  => "",
                        __("Gray", "ts_visual_composer_extend")                   => "ts-simptip-style-gray",
                        __("Green", "ts_visual_composer_extend")                  => "ts-simptip-style-green",
                        __("Blue", "ts_visual_composer_extend")                   => "ts-simptip-style-blue",
                        __("Red", "ts_visual_composer_extend")                    => "ts-simptip-style-red",
                        __("Orange", "ts_visual_composer_extend")                 => "ts-simptip-style-orange",
                        __("Yellow", "ts_visual_composer_extend")                 => "ts-simptip-style-yellow",
                        __("Purple", "ts_visual_composer_extend")                 => "ts-simptip-style-purple",
                        __("Pink", "ts_visual_composer_extend")                   => "ts-simptip-style-pink",
                        __("White", "ts_visual_composer_extend")                  => "ts-simptip-style-white"
                    ),
					"description"		    => __( "Select the tooltip style.", "ts_visual_composer_extend" ),
					"dependency"		    => array( 'element' => "content_tooltip_css", 'value' => 'true' ),
					"group" 				=> "Tooltip Settings",
				),
				// Other Settings
				array(
					"type"				    => "seperator",
					"heading"			    => __( "", "ts_visual_composer_extend" ),
					"param_name"		    => "seperator_4",
					"value"				    => "Other Settings",
					"description"		    => __( "", "ts_visual_composer_extend" ),
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
				array(
					"type"                  => "load_file",
					"heading"               => __( "", "ts_visual_composer_extend" ),
                    "param_name"            => "el_file",
					"value"                 => "",
					"file_type"             => "js",
					"file_path"             => "js/ts-visual-composer-extend-element.min.js",
					"description"           => __( "", "ts_visual_composer_extend" )
				),
            ))
        );
    }
?>