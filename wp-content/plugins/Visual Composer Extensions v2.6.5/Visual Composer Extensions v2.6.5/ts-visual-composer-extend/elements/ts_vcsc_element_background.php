<?php
    if (function_exists('vc_map')) {
        vc_map( array(
            "name"                              => __( "TS YouTube Background", "ts_visual_composer_extend" ),
            "base"                              => "TS-VCSC-YouTube-Background",
            "icon" 	                            => "icon-wpb-ts_vcsc_background_youtube",
            "class"                             => "",
            "category"                          => __( "VC Extensions", "ts_visual_composer_extend" ),
            "description"                       => __("Place a YouTube Video as page background.", "ts_visual_composer_extend"),
            //"admin_enqueue_js"                => array(''),
            //"admin_enqueue_css"               => array(''),
            "params"                            => array(
                // Divider Settings
                array(
                    "type"                      => "seperator",
                    "heading"                   => __( "", "ts_visual_composer_extend" ),
                    "param_name"                => "seperator_1",
                    "value"                     => "YouTube Background Settings",
                    "description"               => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"              		=> "textfield",
                    "heading"           		=> __( "YouTube Video ID", "ts_visual_composer_extend" ),
                    "param_name"        		=> "video_youtube",
                    "value"             		=> "",
                    "admin_label" 				=> true,
                    "description"       		=> __( "Enter the YouTube video ID.", "ts_visual_composer_extend" )
                ),
				array(
					"type"              		=> "switch",
                    "heading"           		=> __( "Mute Video", "ts_visual_composer_extend" ),
                    "param_name"        		=> "video_mute",
                    "value"             		=> "true",
					"on"						=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"						=> __( 'No', "ts_visual_composer_extend" ),
					"style"						=> "select",
					"design"					=> "toggle-light",
                    "description"           	=> __( "Switch the toggle to mute the video while playing.", "ts_visual_composer_extend" ),
                    "dependency"            	=> ""
				),
				array(
					"type"              		=> "switch",
                    "heading"           		=> __( "Loop Video", "ts_visual_composer_extend" ),
                    "param_name"        		=> "video_loop",
                    "value"             		=> "false",
					"on"						=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"						=> __( 'No', "ts_visual_composer_extend" ),
					"style"						=> "select",
					"design"					=> "toggle-light",
                    "description"           	=> __( "Switch the toggle to loop the video after it has finished.", "ts_visual_composer_extend" ),
                    "dependency"            	=> ""
				),
				array(
					"type"              		=> "switch",
                    "heading"           		=> __( "Start Video on Pageload", "ts_visual_composer_extend" ),
                    "param_name"        		=> "video_start",
                    "value"             		=> "false",
					"on"						=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"						=> __( 'No', "ts_visual_composer_extend" ),
					"style"						=> "select",
					"design"					=> "toggle-light",
                    "description"           	=> __( "Switch the toggle to if you want to start the video once the page has loaded.", "ts_visual_composer_extend" ),
                    "dependency"            	=> ""
				),
				array(
					"type"              		=> "switch",
                    "heading"           		=> __( "Show Video Controls", "ts_visual_composer_extend" ),
                    "param_name"        		=> "video_controls",
                    "value"             		=> "true",
					"on"						=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"						=> __( 'No', "ts_visual_composer_extend" ),
					"style"						=> "select",
					"design"					=> "toggle-light",
                    "description"           	=> __( "Switch the toggle to if you want to show basic video controls.", "ts_visual_composer_extend" ),
                    "dependency"            	=> ""
				),
				array(
					"type"              		=> "switch",
                    "heading"           		=> __( "Show Raster over Video", "ts_visual_composer_extend" ),
                    "param_name"        		=> "video_raster",
                    "value"             		=> "false",
					"on"						=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"						=> __( 'No', "ts_visual_composer_extend" ),
					"style"						=> "select",
					"design"					=> "toggle-light",
                    "description"           	=> __( "Switch the toggle to if you want to show a raster over the video.", "ts_visual_composer_extend" ),
                    "dependency"            	=> ""
				),
				// Load Custom CSS/JS File
				array(
					"type"                      => "load_file",
					"heading"                   => __( "", "ts_visual_composer_extend" ),
                    "param_name"                => "el_file",
					"value"                     => "",
					"file_type"                 => "js",
					"file_path"                 => "js/ts-visual-composer-extend-element.min.js",
					"description"               => __( "", "ts_visual_composer_extend" )
				),
            ))
        );
    }
?>