<?php
    if (function_exists('vc_map')) {
        vc_map( array(
            "name"                      => __( "TS QR-Code", "ts_visual_composer_extend" ),
            "base"                      => "TS-VCSC-QRCode",
            "icon" 	                    => "icon-wpb-ts_vcsc_qrcode",
            "class"                     => "",
            "category"                  => __( "VC Extensions", "ts_visual_composer_extend" ),
            "description"               => __("Place a QR-Code block element", "ts_visual_composer_extend"),
            //"admin_enqueue_js"        => array(ts_fb_get_resource_url('/Core/JS/jquery.js-composer.fb-album.js')),
            //"admin_enqueue_css"       => array(ts_fb_get_resource_url('/Core/CSS/jquery.js-composer.fb-album.css')),
            "params"                    => array(
                // QR-Code Settings
                array(
                    "type"              => "seperator",
                    "heading"           => __( "", "ts_visual_composer_extend" ),
                    "param_name"        => "seperator_1",
                    "value"             => "QR-Code Settings",
                    "description"       => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"              => "dropdown",
                    "heading"           => __( "Render Element", "ts_visual_composer_extend" ),
                    "param_name"        => "render",
                    "width"             => 150,
                    "value"             => array(
						__( 'Canvas', "ts_visual_composer_extend" )		=> "canvas",
                        __( 'Image', "ts_visual_composer_extend" )		=> "image",
                        __( 'DIV', "ts_visual_composer_extend" )			=> "div",
                    ),
                    "description"       => __( "Select as what kind of element the QR-Block should be rendered.", "ts_visual_composer_extend" )
                ),
				
				array(
					"type"              => "colorpicker",
					"heading"           => __( "Code Color", "ts_visual_composer_extend" ),
					"param_name"        => "color",
					"value"             => "#000000",
					"description"       => __( "Define the color of the QR-Code block.", "ts_visual_composer_extend" ),
					"dependency"        => ""
				),
				array(
					"type"				=> "switch",
                    "heading"           => __( "Responsive QR-Code", "ts_visual_composer_extend" ),
                    "param_name"        => "responsive",
                    "value"             => "false",
					"on"				=> __( 'Yes', "ts_visual_composer_extend" ),
					"off"				=> __( 'No', "ts_visual_composer_extend" ),
					"style"				=> "select",
					"design"			=> "toggle-light",
                    "description"       => __( "Switch the toggle if you want the QR-Block element to be responsive.", "ts_visual_composer_extend" ),
                    "dependency"        => ""
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "QR-Code Size", "ts_visual_composer_extend" ),
					"param_name"        => "size_r",
					"value"             => "100",
					"min"               => "10",
					"max"               => "100",
					"step"              => "1",
					"unit"              => "%",
					"description"       => __( "Define the responsive size of the QR-Code block.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "responsive", 'value' => 'true' )
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "QR-Code Min-Size", "ts_visual_composer_extend" ),
					"param_name"        => "size_min",
					"value"             => "100",
					"min"               => "50",
					"max"               => "1024",
					"step"              => "1",
					"unit"              => "px",
					"description"       => __( "Define the minimum size of the QR-Code block.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "responsive", 'value' => 'true' )
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "QR-Code Max-Size", "ts_visual_composer_extend" ),
					"param_name"        => "size_max",
					"value"             => "400",
					"min"               => "50",
					"max"               => "1024",
					"step"              => "1",
					"unit"              => "px",
					"description"       => __( "Define the maximum size of the QR-Code block.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "responsive", 'value' => 'true' )
				),
				array(
					"type"              => "nouislider",
					"heading"           => __( "QR-Code Size", "ts_visual_composer_extend" ),
					"param_name"        => "size_f",
					"value"             => "100",
					"min"               => "50",
					"max"               => "1024",
					"step"              => "1",
					"unit"              => "px",
					"description"       => __( "Define the fixed size of the QR-Code block.", "ts_visual_composer_extend" ),
					"dependency"        => array( 'element' => "responsive", 'value' => 'false' )
				),
                array(
                    "type"              => "textfield",
                    "heading"           => __( "Encoded Text", "ts_visual_composer_extend" ),
                    "param_name"        => "value",
                    "value"             => "",
					"admin_label"       => true,
                    "description"       => __( "Enter the text (i.e. URL, Email Address) that should be encoded as QR-Block.", "ts_visual_composer_extend" )
                ),
				// Other Settings
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