<?php
    if (function_exists('vc_map')) {
        vc_map( array(
            "name"                      => __( "Quform", "ts_visual_composer_extend" ),
            "base"                      => "iphorm",
            "icon" 	                    => "icon-wpb-ts_vcsc_quform",
            "class"                     => "",
            "category"                  => __( '3rd Party Plugins', "ts_visual_composer_extend" ),
            "description"               => __("Place a Quform form element", "ts_visual_composer_extend"),
            //"admin_enqueue_js"        => array(''),
            //"admin_enqueue_css"       => array(''),
            "params"                    => array(
                // Spacer Settings
                array(
                    "type"              => "seperator",
                    "heading"           => __( "", "ts_visual_composer_extend" ),
                    "param_name"        => "seperator_1",
                    "value"             => "Quform Form",
                    "description"       => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"				=> "quform",
                    "class"				=> "",
                    "heading"			=> __( "Select Form", "ts_visual_composer_extend" ),
                    "param_name"		=> "id",
                    "value"				=> "",
                    "description"		=> __( "Select the Quform Form you want to insert.", "ts_visual_composer_extend" )
                ),
                array(
                    "type"              => "hidden_input",
                    "heading"           => __( "Form Name", "ts_visual_composer_extend" ),
                    "param_name"        => "name",
                    "value"             => "",
					"admin_label"		=> true,
                    "description"       => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"              => "messenger",
                    "heading"           => __( "", "ts_visual_composer_extend" ),
                    "param_name"        => "messenger",
					"color"				=> "#FF0000",
					"weight"			=> "bold",
					"value"				=> "",
                    "message"           => __( "Please make sure that the QuForm Plugin is installed and activated.", "ts_visual_composer_extend" ),
                    "description"       => __( "", "ts_visual_composer_extend" )
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