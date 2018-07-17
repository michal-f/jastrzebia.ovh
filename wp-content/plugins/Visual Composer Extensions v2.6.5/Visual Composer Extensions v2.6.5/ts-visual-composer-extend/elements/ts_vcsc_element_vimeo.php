<?php
    if (function_exists('vc_map')) {
        vc_map( array(
            "name"                          => __( "TS Video Vimeo", "ts_visual_composer_extend" ),
            "base"                          => "TS-VCSC-Vimeo",
            "icon" 	                        => "icon-wpb-ts_vcsc_vimeo",
            "class"                         => "",
            "category"                      => __( "VC Extensions", "ts_visual_composer_extend" ),
            "description"                   => __("Place a Vimeo Video", "ts_visual_composer_extend"),
            "admin_enqueue_js"              => "",
            "admin_enqueue_css"             => "",
            "params"                        => array(
                // Vimeo Video
                array(
                    "type"                  => "seperator",
                    "heading"               => __( "", "ts_visual_composer_extend" ),
                    "param_name"            => "seperator_1",
                    "value"                 => "Vimeo Video",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Vimeo Video URL", "ts_visual_composer_extend" ),
                    "param_name"            => "content_vimeo",
                    "value"                 => "",
                    "admin_label"           => true,
                    "description"           => __( "Enter the URL for the Vimeo video.", "ts_visual_composer_extend" ),
                    "dependency"            => ""
                ),
				array(
					"type"              	=> "switch",
					"heading"			    => __( "Autoplay Video", "ts_visual_composer_extend" ),
					"param_name"		    => "lightbox_play",
					"value"             	=> "false",
					"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"					=> __( 'No', "ts_visual_composer_extend" ),
					"style"					=> "select",
					"design"				=> "toggle-light",
					"description"		    => __( "Switch the toggle if you want to auto-play the video once opened in the lightbox or on pageload (iFrame).", "ts_visual_composer_extend" ),
                    "dependency"            => ""
				),
				array(
					"type"              	=> "switch",
					"heading"           	=> __( "Open in Lightbox", "ts_visual_composer_extend" ),
					"param_name"        	=> "content_lightbox",
					"value"             	=> "true",
					"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"					=> __( 'No', "ts_visual_composer_extend" ),
					"style"					=> "select",
					"design"				=> "toggle-light",
					"description"       	=> __( "Switch the toggle to show the video in a lightbox.", "ts_visual_composer_extend" ),
					"dependency"        	=> ""
				),
                // Triggger Type
				array(
					"type"                  => "dropdown",
					"heading"               => __( "Trigger Type", "ts_visual_composer_extend" ),
					"param_name"            => "content_vimeo_trigger",
                    "value"                 => array(
                        __("Vimeo Cover", "ts_visual_composer_extend")            => "preview",
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
					"param_name"            => "content_vimeo_image",
					"value"                 => "",
					"description"           => __( "Select the preview image for the lightbox content.", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "content_vimeo_trigger", 'value' => 'image' )
				),
				array(
					"type"              	=> "switch",
					"heading"			    => __( "Simple Image Only", "ts_visual_composer_extend" ),
					"param_name"		    => "content_vimeo_image_simple",
					"value"             	=> "false",
					"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"					=> __( 'No', "ts_visual_composer_extend" ),
					"style"					=> "select",
					"design"				=> "toggle-light",
					"description"		    => __( "Switch the toggle if you want display just the image without any styling.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_vimeo_trigger", 'value' => 'image' )
				),
                array(
                    "type"                  => "dropdown",
                    "heading"               => __( "Auto Height Setting", "ts_visual_composer_extend" ),
                    "param_name"            => "content_image_height",
                    "width"                 => 150,
                    "value"                 => array(
                        __( '100% Height Setting', "ts_visual_composer_extend" )		=> "height: 100%;",
                        __( 'Auto Height Setting', "ts_visual_composer_extend" )     	=> "height: auto;",
                    ),
                    "description"           => __( "Select what CSS height setting should be applied to the image (change only if image height does not display correctly).", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_vimeo_trigger", 'value' => array('preview', 'default', 'image') )
                ),
                // Font Icon
				array(
					"type"                  => "icons_panel",
					"heading"               => __( "Select Icon", "ts_visual_composer_extend" ),
					"param_name"            => "content_vimeo_icon",
					"value"                 => $this->TS_VCSC_List_Icons_Full,
					"description"           => __( "Select the icon you want to display.", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "content_vimeo_trigger", 'value' => 'icon' )
				),
				array(
					"type"                  => "nouislider",
					"heading"               => __( "Icon Size", "ts_visual_composer_extend" ),
					"param_name"            => "content_vimeo_iconsize",
					"value"                 => "30",
					"min"                   => "16",
					"max"                   => "512",
					"step"                  => "1",
					"unit"                  => 'px',
					"description"           => __( "Select the icon / image size", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "content_vimeo_trigger", 'value' => 'icon' )
				),
				array(
					"type"                  => "colorpicker",
					"heading"               => __( "Icon Color", "ts_visual_composer_extend" ),
					"param_name"            => "content_vimeo_iconcolor",
					"value"                 => "#cccccc",
					"description"           => __( "Define the color of the icon.", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "content_vimeo_trigger", 'value' => 'icon' )
				),
                // Button
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Button Text", "ts_visual_composer_extend" ),
                    "param_name"            => "content_vimeo_buttontext",
                    "value"                 => "View Video",
                    "description"           => __( "Enter the text for the button.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_vimeo_trigger", 'value' => array('winged', 'simple') )
                ),
                // Text Link
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Trigger Text", "ts_visual_composer_extend" ),
                    "param_name"            => "content_vimeo_text",
                    "value"                 => "",
                    "description"           => __( "Enter the trigger text for the video.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_vimeo_trigger", 'value' => 'text' )
                ),
                // Custom Code
                array(
                    "type"                  => "textarea_raw_html",
                    "heading"               => __("Raw HTML", "ts_visual_composer_extend"),
                    "param_name"            => "content_raw",
                    "value"                 => base64_encode(""),
                    "description"           => __("Enter your custom HTML code; code will be wrapped in appropriate link automatically.", "ts_visual_composer_extend"),
                    "dependency"            => array( 'element' => "content_vimeo_trigger", 'value' => 'custom' )
                ),
                // Title + Subtitle
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Title", "ts_visual_composer_extend" ),
                    "param_name"            => "content_vimeo_title",
                    "value"                 => "",
                    "description"           => __( "Enter a title for the lightbox content.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_vimeo_trigger", 'value' => array('image', 'default', 'preview', 'winged') )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Subtitle", "ts_visual_composer_extend" ),
                    "param_name"            => "content_vimeo_subtitle",
                    "value"                 => "",
                    "description"           => __( "Enter a subtitle for the lightbox content.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_vimeo_trigger", 'value' => array('winged') )
                ),
                // Lightbox Settings
				array(
					"type"				    => "seperator",
					"heading"			    => __( "", "ts_visual_composer_extend" ),
					"param_name"		    => "seperator_2",
					"value"				    => "Lightbox Settings",
					"description"		    => __( "", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "content_lightbox", 'value' => "true" )
				),
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Group Name", "ts_visual_composer_extend" ),
                    "param_name"            => "lightbox_group_name",
                    "value"                 => "nachogroup",
                    "description"           => __( "Enter a custom group name to manually build group with other non-gallery items; leave empty for non-grouping", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_lightbox", 'value' => "true" )
                ),
                array(
                    "type"                  => "dropdown",
                    "heading"               => __( "Transition Effect", "ts_visual_composer_extend" ),
                    "param_name"            => "lightbox_effect",
                    "width"                 => 150,
                    "value"                 => array(
                        __( 'Random', "ts_visual_composer_extend" )       => "random",
                        __( 'Swipe', "ts_visual_composer_extend" )        => "swipe",
                        __( 'Fade', "ts_visual_composer_extend" )         => "fade",
                        __( 'Scale', "ts_visual_composer_extend" )        => "scale",
                        __( 'Slide Up', "ts_visual_composer_extend" )     => "slideUp",
                        __( 'Slide Down', "ts_visual_composer_extend" )   => "slideDown",
                        __( 'Flip', "ts_visual_composer_extend" )         => "flip",
                        __( 'Skew', "ts_visual_composer_extend" )         => "skew",
                        __( 'Bounce Up', "ts_visual_composer_extend" )    => "bounceUp",
                        __( 'Bounce Down', "ts_visual_composer_extend" )  => "bounceDown",
                        __( 'Break In', "ts_visual_composer_extend" )     => "breakIn",
                        __( 'Rotate In', "ts_visual_composer_extend" )    => "rotateIn",
                        __( 'Rotate Out', "ts_visual_composer_extend" )   => "rotateOut",
                        __( 'Hang Left', "ts_visual_composer_extend" )    => "hangLeft",
                        __( 'Hang Right', "ts_visual_composer_extend" )   => "hangRight",
                        __( 'Cycle Up', "ts_visual_composer_extend" )     => "cicleUp",
                        __( 'Cycle Down', "ts_visual_composer_extend" )   => "cicleDown",
                        __( 'Zoom In', "ts_visual_composer_extend" )      => "zoomIn",
                        __( 'Throw In', "ts_visual_composer_extend" )     => "throwIn",
                        __( 'Fall', "ts_visual_composer_extend" )         => "fall",
                        __( 'Jump', "ts_visual_composer_extend" )         => "jump",
                    ),
                    "admin_label"           => true,
                    "description"           => __( "Select the transition effect to be used for the image in the lightbox.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_lightbox", 'value' => "true" )
                ),
                // Tooltip Settings
				array(
					"type"				    => "seperator",
					"heading"			    => __( "", "ts_visual_composer_extend" ),
					"param_name"		    => "seperator_3",
					"value"				    => "Tooltip",
					"description"		    => __( "", "ts_visual_composer_extend" ),
					"dependency"            => array( 'element' => "content_lightbox", 'value' => "true" )
				),
				array(
					"type"              	=> "switch",
					"heading"           	=> __( "Use CSS3 Tooltip", "ts_visual_composer_extend" ),
					"param_name"        	=> "content_tooltip_css",
					"value"             	=> "false",
					"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"					=> __( 'No', "ts_visual_composer_extend" ),
					"style"					=> "select",
					"design"				=> "toggle-light",
					"description"		    => __( "Switch the toggle if you want to apply a CSS3 tooltip to the video trigger.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_lightbox", 'value' => "true" )
				),
				array(
					"type"				    => "textarea",
					"class"				    => "",
					"heading"			    => __( "Tooltip Content", "ts_visual_composer_extend" ),
					"param_name"		    => "content_tooltip_content",
					"value"				    => "",
					"description"		    => __( "Enter the tooltip content here (do not use quotation marks).", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "content_lightbox", 'value' => "true" )
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
					"description"		    => __( "Select the tooltip position in relation to the image.", "ts_visual_composer_extend" ),
					"dependency"		    => array( 'element' => "content_tooltip_css", 'value' => 'true' )
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
					"dependency"		    => array( 'element' => "content_tooltip_css", 'value' => 'true' )
				),
				// Other Settings
				array(
					"type"				    => "seperator",
					"heading"			    => __( "", "ts_visual_composer_extend" ),
					"param_name"		    => "seperator_4",
					"value"				    => "Other Settings",
					"description"		    => __( "", "ts_visual_composer_extend" ),
					"group"					=> "Other Settings",
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
					"group"					=> "Other Settings",
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
					"group"					=> "Other Settings",
                ),
				array(
					"type"                  => "textfield",
					"heading"               => __( "Define ID Name", "ts_visual_composer_extend" ),
					"param_name"            => "el_id",
					"value"                 => "",
					"description"           => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
					"group"					=> "Other Settings",
				),
				array(
					"type"                  => "textfield",
					"heading"               => __( "Extra Class Name", "ts_visual_composer_extend" ),
					"param_name"            => "el_class",
					"value"                 => "",
					"description"           => __( "Enter a class name for the element.", "ts_visual_composer_extend" ),
					"group"					=> "Other Settings",
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
            ))
        );
    }
?>