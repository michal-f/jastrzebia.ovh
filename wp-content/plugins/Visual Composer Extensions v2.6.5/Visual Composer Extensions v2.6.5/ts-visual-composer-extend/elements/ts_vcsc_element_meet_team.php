<?php
    if (function_exists('vc_map')) {
        vc_map( array(
            "name"                          => __( "TS Meet The Team (Deprecated)", "ts_visual_composer_extend" ),
            "base"                          => "TS-VCSC-Meet-Team",
            "icon" 	                        => "icon-wpb-ts_vcsc_meet_team",
            "class"                         => "",
            "category"                      => __( 'VC Extensions (Deprecated)', "ts_visual_composer_extend" ),
            "description"                   => __("Place a Meet The Team element", "ts_visual_composer_extend"),
            //"admin_enqueue_js"            => array(''),
            //"admin_enqueue_css"           => array(''),
            "params"                        => array(
                // Meet The Team Content
                array(
                    "type"                  => "seperator",
                    "heading"               => __( "", "ts_visual_composer_extend" ),
                    "param_name"            => "seperator_1",
                    "value"                 => "Main Content",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "dropdown",
                    "heading"               => __( "Design", "ts_visual_composer_extend" ),
                    "param_name"            => "style",
                    "value"             => array(
                        __( "Style 1", "ts_visual_composer_extend" )                        => "style1",
                        __( "Style 2", "ts_visual_composer_extend" )                        => "style2",
                        __( "Style 3", "ts_visual_composer_extend" )                        => "style3",
                    ),
                    "description"           => __( "", "ts_visual_composer_extend" ),
                    "admin_label"           => true,
                    "dependency"            => ""
                ),
                array(
                    "type"                  => "attach_image",
                    "heading"               => __( "Image", "ts_visual_composer_extend" ),
                    "param_name"            => "image",
                    "value"                 => "false",
                    "admin_label"           => true,
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "seperator",
                    "heading"               => __( "", "ts_visual_composer_extend" ),
                    "param_name"            => "seperator_1",
                    "value"                 => "Team Member Content",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Name", "ts_visual_composer_extend" ),
                    "param_name"            => "name",
                    "value"                 => "",
                    "admin_label"           => true,
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => __( "Title", "ts_visual_composer_extend" ),
                    "param_name"            => "title",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textarea",
                    "class"                 => "",
                    "heading"               => __( "Description", "ts_visual_composer_extend" ),
                    "param_name"            => "description",
                    "value"                 => "",
                    "dependency"            => ""
                ),
                // Social Icon Style
                array(
                    "type"                  => "seperator",
                    "heading"               => __( "", "ts_visual_composer_extend" ),
                    "param_name"            => "seperator_2",
                    "value"                 => "Icon Settings",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "dropdown",
                    "heading"               => __( "Style", "ts_visual_composer_extend" ),
                    "param_name"            => "icon_style",
                    "admin_label"           => true,
                    "value"                 => array(
                        __( "Simple", "ts_visual_composer_extend" )                         => "simple",
                        __( "Square", "ts_visual_composer_extend" )                         => "square",
                        __( "Rounded", "ts_visual_composer_extend" )                        => "rounded",
                        __( "Circle", "ts_visual_composer_extend" )                         => "circle",
                    ),
                ),
                array(
                    "type"                  => "colorpicker",
                    "heading"               => __( "Icon Background Color", "ts_visual_composer_extend" ),
                    "param_name"            => "icon_background",
                    "value"                 => "#f5f5f5",
                    "description"           => __( "", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "icon_style", 'value' => array('square', 'rounded', 'circle') )
                ),
                array(
                    "type"                  => "colorpicker",
                    "heading"               => __( "Icon Border Color", "ts_visual_composer_extend" ),
                    "param_name"            => "icon_frame_color",
                    "value"                 => "#f5f5f5",
                    "description"           => __( "", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "icon_style", 'value' => array('square', 'rounded', 'circle') )
                ),
                array(
                    "type"                  => "nouislider",
                    "heading"               => __( "Icon Frame Border Thickness", "ts_visual_composer_extend" ),
                    "param_name"            => "icon_frame_thick",
                    "value"                 => "1",
                    "min"                   => "1",
                    "max"                   => "10",
                    "step"                  => "1",
                    "unit"                  => 'px',
                    "description"           => __( "", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "icon_style", 'value' => array('square', 'rounded', 'circle') )
                ),
                array(
                    "type"                  => "nouislider",
                    "heading"               => __( "Icon Margin", "ts_visual_composer_extend" ),
                    "param_name"            => "icon_margin",
                    "value"                 => "5",
                    "min"                   => "0",
                    "max"                   => "50",
                    "step"                  => "1",
                    "unit"                  => 'px',
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "dropdown",
                    "heading"               => __( "Icons Align", "ts_visual_composer_extend" ),
                    "param_name"            => "icon_align",
                    "width"                 => 150,
                    "value"                 => array(
                        __( 'Left', "ts_visual_composer_extend" )                           => "left",
                        __( 'Right', "ts_visual_composer_extend" )                          => "right",
                        __( 'Center', "ts_visual_composer_extend" )                         => "center" ),
                    "admin_label"           => true,
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),         
				array(
					"type"				    => "css3animations",
					"class"				    => "",
					"heading"			    => __("Icons Hover Animation", "ts_visual_composer_extend"),
					"param_name"		    => "icon_hover",
					"standard"			    => "false",
					"prefix"			    => "ts-hover-css-",
					"connector"			    => "css3animations_in",
					"noneselect"		    => "true",
					"default"			    => "",
					"value"				    => "",
					"admin_label"		    => false,
					"description"		    => __("Select the hover animation for the social icons.", "ts_visual_composer_extend"),
				),
				array(
					"type"				    => "hidden_input",
					"heading"			    => __( "Icons Hover Animation", "ts_visual_composer_extend" ),
					"param_name"		    => "css3animations_in",
					"value"				    => "",
					"admin_label"		    => true,
					"description"		    => __( "", "ts_visual_composer_extend" ),
				),          
                // Social Icon Links
                array(
                    "type"                  => "seperator",
                    "heading"               => __( "", "ts_visual_composer_extend" ),
                    "param_name"            => "seperator_3",
                    "value"                 => "Social Links",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-email'></i> " . __( "Email Address", "ts_visual_composer_extend" ),
                    "param_name"            => "email",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-phone'></i> " . __( "Phone Number", "ts_visual_composer_extend" ),
                    "param_name"            => "phone",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-cell'></i> " . __( "Cell Number", "ts_visual_composer_extend" ),
                    "param_name"            => "cell",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-portfolio'></i> " . __( "Portfolio URL", "ts_visual_composer_extend" ),
                    "param_name"            => "portfolio",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-link'></i> " . __( "Other Link URL", "ts_visual_composer_extend" ),
                    "param_name"            => "link",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-facebook'></i> " . __( "Facebook URL", "ts_visual_composer_extend" ),
                    "param_name"            => "facebook",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-gplus'></i> " . __( "Google+ URL", "ts_visual_composer_extend" ),
                    "param_name"            => "gplus",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-twitter'></i> " . __( "Twitter URL", "ts_visual_composer_extend" ),
                    "param_name"            => "twitter",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-linkedin'></i> " . __( "Linkedin URL", "ts_visual_composer_extend" ),
                    "param_name"            => "linkedin",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-xing'></i> " . __( "Xing URL", "ts_visual_composer_extend" ),
                    "param_name"            => "xing",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-skype'></i> " . __( "Skype URL", "ts_visual_composer_extend" ),
                    "param_name"            => "skype",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-flickr'></i> " . __( "Flickr URL", "ts_visual_composer_extend" ),
                    "param_name"            => "flickr",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-instagram'></i> " . __( "Instagram URL", "ts_visual_composer_extend" ),
                    "param_name"            => "instagram",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-picasa'></i> " . __( "Picasa URL", "ts_visual_composer_extend" ),
                    "param_name"            => "picasa",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-vimeo'></i> " . __( "Vimeo URL", "ts_visual_composer_extend" ),
                    "param_name"            => "vimeo",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"                  => "textfield",
                    "heading"               => "<i class='ts-social-icon ts-social-youtube'></i> " . __( "Youtube URL", "ts_visual_composer_extend" ),
                    "param_name"            => "youtube",
                    "value"                 => "",
                    "description"           => __( "", "ts_visual_composer_extend" )
                ),
                // Other Meet the Team Settings
                array(
                    "type"                  => "seperator",
                    "heading"               => __( "", "ts_visual_composer_extend" ),
                    "param_name"            => "seperator_4",
                    "value"                 => "Other Settings",
                    "description"           => __( "", "ts_visual_composer_extend" ),
					"group" 				=> "Other Settings",
                ),
                array(
                    "type"                  => "dropdown",
                    "heading"               => __( "Viewport Animation", "ts_visual_composer_extend" ),
                    "param_name"            => "animation_view",
					"value"             =>  array(
						__( "None", "ts_visual_composer_extend" )                          => "",
						__( "Top to Bottom", "ts_visual_composer_extend" )                 => "top-to-bottom",
						__( "Bottom to Top", "ts_visual_composer_extend" )                 => "bottom-to-top",
						__( "Left to Right", "ts_visual_composer_extend" )                 => "left-to-right",
						__( "Right to Left", "ts_visual_composer_extend" )                 => "right-to-left",
						__( "Appear from Center", "ts_visual_composer_extend" )            => "appear",
					),
                    "description"           => __( "Select the viewport animation for the element.", "ts_visual_composer_extend" ),
                    "dependency"            => array( 'element' => "animations", 'value' => 'true' ),
					"group" 				=> "Other Settings",
                ),
                array(
                    "type"                  => "nouislider",
                    "heading"               => __( "Margin: Top", "ts_visual_composer_extend" ),
                    "param_name"            => "margin_top",
                    "value"                 => "0",
                    "min"                   => "-50",
                    "max"                   => "500",
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
                    "min"                   => "-50",
                    "max"                   => "500",
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
					"type"				    => "load_file",
					"heading"			    => __( "", "ts_visual_composer_extend" ),
					"value"				    => "",
					"param_name"		    => "el_file1",
					"file_type"			    => "js",
					"file_path"			    => "js/ts-visual-composer-extend-element.min.js",
					"description"		    => __( "", "ts_visual_composer_extend" )
				),
				array(
					"type"				    => "load_file",
					"heading"			    => __( "", "ts_visual_composer_extend" ),
					"value"				    => "",
					"param_name"		    => "el_file2",
					"file_type"			    => "css",
					"file_id"			    => "ts-extend-animations",
					"file_path"			    => "css/ts-visual-composer-extend-animations.min.css",
					"description"		    => __( "", "ts_visual_composer_extend" )
				),
            ))
        );
    }
?>