<?php
    if (function_exists('vc_map')) {
        vc_map( array(
            "name"                      => __( "TS Google Docs", "ts_visual_composer_extend" ),
            "base"                      => "TS-VCSC-Google-Docs",
            "icon" 	                    => "icon-wpb-ts_vcsc_google_docs",
            "class"                     => "",
            "category"                  => __( "VC Extensions", "ts_visual_composer_extend" ),
            "description"               => __("Place a Google Doc element", "ts_visual_composer_extend"),
            //"admin_enqueue_js"        => array(''),
            //"admin_enqueue_css"       => array(''),
            "params"                    => array(
                // Google Doc Settings
                array(
                    "type"              => "seperator",
                    "heading"           => __( "", "ts_visual_composer_extend" ),
                    "param_name"        => "seperator_1",
                    "value"             => "Google Document",
                    "description"       => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"              => "dropdown",
                    "heading"           => __( "Doc Type", "ts_visual_composer_extend" ),
                    "param_name"        => "doc_type",
                    "width"             => 150,
                    "value"             => array(
                        __( 'Document', "ts_visual_composer_extend" )         	=> "document",
                        __( 'Presentation', "ts_visual_composer_extend" )     	=> "presentation",
                        __( 'Spreadsheet', "ts_visual_composer_extend" )      	=> "spreadsheet",
                        __( 'Drawing', "ts_visual_composer_extend" )          	=> "drawing",
                        __( 'Form', "ts_visual_composer_extend" )             	=> "form",
                    ),
                    "admin_label"       => true,
                    "description"       => __( "Select the type of Google Doc file you want to embed.", "ts_visual_composer_extend" )
                ),
				array(
					"type"              => "textfield",
					"heading"           => __( "Doc Key", "ts_visual_composer_extend" ),
					"param_name"        => "doc_key",
					"value"             => "",
                    "admin_label"       => true,
					"description"       => __( "Enter the key number for the Google Doc file.", "ts_visual_composer_extend" )
				),
                array(
                    "type"              => "nouislider",
                    "heading"           => __( "Height in px", "ts_visual_composer_extend" ),
                    "param_name"        => "doc_height",
                    "value"             => "500",
                    "min"               => "100",
                    "max"               => "2048",
                    "step"              => "1",
                    "unit"              => 'px',
                    "description"       => __( "Define the height for the Google Doc file.", "ts_visual_composer_extend" )
                ),
				array(
					"type"              => "switch",
					"heading"           => __( "Editable Share Document", "ts_visual_composer_extend" ),
					"param_name"        => "doc_share",
					"value"             => "false",
					"on"				=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"				=> __( 'No', "ts_visual_composer_extend" ),
					"style"				=> "select",
					"design"			=> "toggle-light",
					"description"       => __( "Switch the toggle if the document will be shared as editable.", "ts_visual_composer_extend" ),
                    "dependency"        => ""
				),
				array(
					"type"              => "switch",
					"heading"           => __( "Slide Auto Start", "ts_visual_composer_extend" ),
					"param_name"        => "doc_presentation_auto",
					"value"             => "false",
					"on"				=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"				=> __( 'No', "ts_visual_composer_extend" ),
					"style"				=> "select",
					"design"			=> "toggle-light",
					"description"       => __( "Switch the toggle if the slides should auto start.", "ts_visual_composer_extend" ),
                    "dependency"        => array( 'element' => "doc_type", 'value' => 'presentation' )
				),
                array(
                    "type"              => "nouislider",
                    "heading"           => __( "Slide Speed", "ts_visual_composer_extend" ),
                    "param_name"        => "doc_presentation_speed",
                    "value"             => "10000",
                    "min"               => "1000",
                    "max"               => "50000",
                    "step"              => "100",
                    "unit"              => 'ms',
                    "description"       => __( "Define the speed in which slides should auto rotate.", "ts_visual_composer_extend" ),
                    "dependency"        => array( 'element' => "doc_type", 'value' => 'presentation' )
                ),
				array(
					"type"              => "switch",
					"heading"           => __( "Slide Auto Loop", "ts_visual_composer_extend" ),
					"param_name"        => "doc_presentation_loop",
					"value"             => "false",
					"on"				=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"				=> __( 'No', "ts_visual_composer_extend" ),
					"style"				=> "select",
					"design"			=> "toggle-light",
					"description"       => __( "Switch the toggle if the slides loop and automatically start a new cycle.", "ts_visual_composer_extend" ),
                    "dependency"        => array( 'element' => "doc_type", 'value' => 'presentation' )
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
					"heading"           => __( "Google Document Border Type", "ts_visual_composer_extend" ),
					"param_name"        => "doc_frame_type",
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
					"description"       => __( "Select the type of border around the Google Document.", "ts_visual_composer_extend" ),
					"dependency"        => ""
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "Google Document Border Thickness", "ts_visual_composer_extend" ),
					"param_name"        => "doc_frame_thick",
					"value"             => "1",
					"min"               => "1",
					"max"               => "10",
					"step"              => "1",
					"unit"              => 'px',
					"description"       => __( "Define the thickness of the border around the Google Document.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "doc_frame_type", 'value' => $this->TS_VCSC_Border_Type_Values )
				),
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Google Document Frame Border Color", "ts_visual_composer_extend" ),
					"param_name"        => "doc_frame_color",
					"value"             => "#dddddd",
					"description"       => __( "Define the color the border around the Google Document.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "doc_frame_type", 'value' => $this->TS_VCSC_Border_Type_Values )
				),
				// Other Google Docs Settings
				array(
					"type"              => "seperator",
					"heading"           => __( "", "ts_visual_composer_extend" ),
					"param_name"        => "seperator_3",
					"value"             => "Other Google Doc Settings",
					"description"       => __( "", "ts_visual_composer_extend" ),
					"group" 			=> "Other Settings",
				),
                array(
                    "type"              => "nouislider",
                    "heading"           => __( "Margin: Top", "ts_visual_composer_extend" ),
                    "param_name"        => "margin_top",
                    "value"             => "0",
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
                    "value"             => "0",
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