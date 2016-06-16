<?php
    if (function_exists('vc_map')) {
        vc_map( array(
            "name"                      => __( "TS Spacer / Clear", "ts_visual_composer_extend" ),
            "base"                      => "TS-VCSC-Spacer",
            "icon" 	                    => "icon-wpb-ts_vcsc_spacer",
            "class"                     => "",
            "category"                  => __( "VC Extensions", "ts_visual_composer_extend" ),
            "description"               => __("Place a spacer / clear element", "ts_visual_composer_extend"),
            //"admin_enqueue_js"        => array(ts_fb_get_resource_url('/Core/JS/jquery.js-composer.fb-album.js')),
            //"admin_enqueue_css"       => array(ts_fb_get_resource_url('/Core/CSS/jquery.js-composer.fb-album.css')),
            "params"                    => array(
                // Spacer Settings
                array(
                    "type"              => "seperator",
                    "heading"           => __( "", "ts_visual_composer_extend" ),
                    "param_name"        => "seperator_1",
                    "value"             => "Spacer Dimensions",
                    "description"       => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"              => "nouislider",
                    "heading"           => __( "Height in px", "ts_visual_composer_extend" ),
                    "param_name"        => "height",
                    "value"             => "10",
                    "min"               => "0",
                    "max"               => "500",
                    "step"              => "1",
                    "unit"              => 'px',
                    "admin_label"           => true,
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