<?php
    if (function_exists('vc_map')) {
        vc_map( array(
            "name"                      => __( "TS Shortcode", "ts_visual_composer_extend" ),
            "base"                      => "TS-VCSC-Shortcode",
            "icon" 	                    => "icon-wpb-ts_vcsc_shortcode",
            "class"                     => "",
            "category"                  => __( "VC Extensions", "ts_visual_composer_extend" ),
            "description"               => __("Place any shortcode in your page", "ts_visual_composer_extend"),
            //"admin_enqueue_js"        => array(''),
            //"admin_enqueue_css"       => array(''),
            "params"                    => array(
                // Shortcode Settings
                array(
                    "type"              => "seperator",
                    "heading"           => __( "", "ts_visual_composer_extend" ),
                    "param_name"        => "seperator_1",
                    "value"             => "Shortcode Input",
                    "description"       => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"              => "textarea_raw_html",
                    "class"             => "",
                    "heading"           => __( "Shortcode", "ts_visual_composer_extend" ),
                    "param_name"        => "tscode",
                    "value"             => base64_encode(""),
					"description"       => __( "Enter the shortcode with its full syntax here.", "ts_visual_composer_extend" ),
                    "dependency"		=> ""
                ),
                array(
                    "type"				=> "hidden_textarea",
                    "class"				=> "",
                    "heading"			=> __( "Shortcode Key", "ts_visual_composer_extend" ),
                    "param_name"		=> "tscodenormal",
                    "value"				=> "",
					"description"       => __( "", "ts_visual_composer_extend" ),
					"admin_label"		=> true,
                    "dependency"		=> ""
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