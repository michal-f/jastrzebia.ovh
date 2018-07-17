<?php
    if (function_exists('vc_map')) {
        vc_map( array(
            "name"                      => __( "GoPricing Table", "ts_visual_composer_extend" ),
            "base"                      => "go_pricing",
            "icon" 	                    => "icon-wpb-ts_vcsc_go_pricing",
            "class"                     => "",
            "category"                  => __( '3rd Party Plugins', "ts_visual_composer_extend" ),
            "description"               => __("Place a GoPricing element", "ts_visual_composer_extend"),
            //"admin_enqueue_js"        => array(ts_fb_get_resource_url('/Core/JS/jquery.js-composer.fb-album.js')),
            //"admin_enqueue_css"       => array(ts_fb_get_resource_url('/Core/CSS/jquery.js-composer.fb-album.css')),
            "params"                    => array(
                // Spacer Settings
                array(
                    "type"              => "seperator",
                    "heading"           => __( "", "ts_visual_composer_extend" ),
                    "param_name"        => "seperator_1",
                    "value"             => "GoPricing Tables",
                    "description"       => __( "", "ts_visual_composer_extend" )
                ),
                array(
                    "type"				=> "gopricing",
                    "class"				=> "",
                    "heading"			=> __( "Pricing Table", "ts_visual_composer_extend" ),
                    "param_name"		=> "id",
                    "value"				=> "",
					"admin_label"		=> true,
                    "description"		=> __( "Select the GoPricing Table you want to insert.", "ts_visual_composer_extend" )
                ),
                array(
                    "type"              => "nouislider",
                    "heading"           => __( "Bottom Margin", "ts_visual_composer_extend" ),
                    "param_name"        => "margin_bottom",
                    "value"             => "20",
                    "min"               => "0",
                    "max"               => "500",
                    "step"              => "1",
                    "unit"              => 'px',
                    "description"       => __( "Define a bottom margin for the GoPricing Table.", "ts_visual_composer_extend" )
                ),
                array(
                    "type"              => "messenger",
                    "heading"           => __( "", "ts_visual_composer_extend" ),
                    "param_name"        => "messenger",
					"color"				=> "#FF0000",
					"weight"			=> "bold",
					"value"				=> "",
                    "message"           => __( "Please make sure that the GoPricing Tables Plugin is installed and activated.", "ts_visual_composer_extend" ),
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