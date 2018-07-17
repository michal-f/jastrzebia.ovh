<?php
if (!class_exists('TS_Anything_Slider')){
	class TS_Anything_Slider {
		function __construct() {
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
                    add_action('init',                              	array($this, 'TS_VCSC_Anything_Slider_Elements'), 9999999);
                } else {
                    add_action('admin_init',		                	array($this, 'TS_VCSC_Anything_Slider_Elements'), 9999999);
                }
            } else {
                add_action('admin_init',								array($this, 'TS_VCSC_Anything_Slider_Elements'), 9999999);
            }
			add_shortcode('TS_VCSC_Anything_Slider',       				array($this, 'TS_VCSC_Anything_Slider'));
		}
        
		// Custom Teaser Block Slider
		function TS_VCSC_Anything_Slider ($atts, $content = null){
			global $VISUAL_COMPOSER_EXTENSIONS;
			ob_start();
	
            wp_enqueue_style('ts-extend-owlcarousel2');
            wp_enqueue_script('ts-extend-owlcarousel2');
			wp_enqueue_style('ts-font-ecommerce');
	
			if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
				wp_enqueue_style('ts-extend-animations');
				wp_enqueue_style('ts-extend-simptip');
				wp_enqueue_style('ts-extend-buttons');
				wp_enqueue_style('ts-visual-composer-extend-front');
				wp_enqueue_script('ts-visual-composer-extend-front');
			}
			
			extract( shortcode_atts( array(
				'number_teasers'				=> 1,
				'auto_height'                   => 'true',
				'page_rtl'						=> 'false',
				'auto_play'                     => 'false',
				'show_bar'                      => 'true',
				'bar_color'                     => '#dd3333',
				'show_speed'                    => 5000,
				'stop_hover'                    => 'true',
				'show_navigation'               => 'true',
				'show_dots'						=> 'true',
				'page_numbers'                  => 'false',
				'items_loop'					=> 'true',				
				'animation_in'					=> 'ts-viewport-css-flipInX',
				'animation_out'					=> 'ts-viewport-css-slideOutDown',
				'animation_mobile'				=> 'false',
				'margin_top'                    => 0,
				'margin_bottom'                 => 0,
				'el_id' 						=> '',
				'el_class'                  	=> '',
				'css'							=> '',
			), $atts ));
			
			$teaser_random                    	= mt_rand(999999, 9999999);
			
			// Check for Front End Editor
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
					$slider_class				= 'owl-carousel-edit';
					$slider_message				= '<div class="ts-composer-frontedit-message">' . __( 'The slider is currently viewed in front-end edit mode; slider features are disabled for performance and compatibility reasons.', "ts_visual_composer_extend" ) . '</div>';
					$product_style				= 'width: ' . (100 / $teammates_slide) . '%; height: 100%; float: left; margin: 0; padding: 0;';
					$frontend_edit				= 'true';
                } else {
					$slider_class				= 'ts-owlslider-parent owl-carousel';
					$slider_message				= '';
					$product_style				= '';
					$frontend_edit				= 'false';
                }
            } else {
				$slider_class					= 'ts-owlslider-parent owl-carousel';
				$slider_message					= '';
				$product_style					= '';
				$frontend_edit					= 'false';
            }
			
			if (!empty($el_id)) {
				$any_slider_id			    	= $el_id;
			} else {
				$any_slider_id			    	= 'ts-vcsc-anyslider-' . $teaser_random;
			}
			
			$output = '';
			
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-vcsc-anyslider ' . $slider_class . ' ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Anything_Slider', $atts);
			} else {
				$css_class	= 'ts-vcsc-anyslider ' . $slider_class . ' ' . $el_class;
			}
			
			$output .= '<div id="' . $any_slider_id . '-container" class="ts-vcsc-anyslider-container">';
				// Front-Edit Message
				if ($frontend_edit == "true") {
					$output .= $slider_message;
				}
				// Add Progressbar
				if (($auto_play == "true") && ($show_bar == "true") && ($frontend_edit == "false")) {
					$output .= '<div id="ts-owlslider-progressbar-' . $teaser_random . '" class="ts-owlslider-progressbar-holder" style=""><div class="ts-owlslider-progressbar" style="background: ' . $bar_color . '; height: 100%; width: 0%;"></div></div>';
				}
				// Add Navigation Controls
				if ($frontend_edit == "false") {
					$output .= '<div id="ts-owlslider-controls-' . $teaser_random . '" class="ts-owlslider-controls" style="' . ((($auto_play == "true") || ($show_navigation == "true")) ? "display: block;" : "display: none;") . '">';
						$output .= '<div id="ts-owlslider-controls-next-' . $teaser_random . '" style="' . (($show_navigation == "true") ? "display: block;" : "display: none;") . '" class="ts-owlslider-controls-next"><span class="ts-ecommerce-arrowright5"></span></div>';
						$output .= '<div id="ts-owlslider-controls-prev-' . $teaser_random . '" style="' . (($show_navigation == "true") ? "display: block;" : "display: none;") . '" class="ts-owlslider-controls-prev"><span class="ts-ecommerce-arrowleft5"></span></div>';
						if ($auto_play == "true") {
							$output .= '<div id="ts-owlslider-controls-play-' . $teaser_random . '" class="ts-owlslider-controls-play active"><span class="ts-ecommerce-pause"></span></div>';
						}
					$output .= '</div>';
				}
				// Add Slider
				$output .= '<div id="' . $any_slider_id . '" class="' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;" data-id="' . $teaser_random . '" data-items="' . $number_teasers . '" data-rtl="' . $page_rtl . '" data-loop="' . $items_loop . '" data-navigation="' . $show_navigation . '" data-dots="' . $show_dots . '" data-mobile="' . $animation_mobile . '" data-animationin="' . $animation_in . '" data-animationout="' . $animation_out . '" data-height="' . $auto_height . '" data-play="' . $auto_play . '" data-bar="' . $show_bar . '" data-color="' . $bar_color . '" data-speed="' . $show_speed . '" data-hover="' . $stop_hover . '">';
					$output .= do_shortcode($content);
				$output .= '</div>';
			$output .= '</div>';
			
			echo $output;
			
			$myvariable = ob_get_clean();
			return $myvariable;
		}
	
		// Add Anything Slider Elements
        function TS_VCSC_Anything_Slider_Elements() {
			global $VISUAL_COMPOSER_EXTENSIONS;
			// Add Anything Slider (Custom Build)
			if (function_exists('vc_map')) {
				vc_map(array(
					"name"                              => __("TS Anything Slider", "ts_visual_composer_extend"),
					"base"                              => "TS_VCSC_Anything_Slider",
					"class"                             => "",
					"icon"                              => "icon-wpb-ts_vcsc_anything_slider",
					"category"                          => __("VC Extensions", "ts_visual_composer_extend"),
					"as_parent"                       	=> array('except' => implode(",", $VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_AnySlider_Excluded)),
					"description"                       => __("Build a custom Slider with any content", "ts_visual_composer_extend"),
					"content_element" 					=> true,
					//"container_not_allowed" 			=> false,
					"is_container" 						=> false,
					"container_not_allowed" 			=> false,
					"show_settings_on_create"           => true,
					"params"							=> array(
						// Slider Settings
						array(
							"type"                      => "seperator",
							"heading"                   => __( "", "ts_visual_composer_extend" ),
							"param_name"                => "seperator_1",
							"value"                     => "Slider Settings",
							"description"               => __( "", "ts_visual_composer_extend" )
						),
						array(
							"type" 						=> "css3animations",
							"class" 					=> "",
							"heading" 					=> __("In-Animation Type", "ts_visual_composer_extend"),
							"param_name" 				=> "animation_in",
							"standard"					=> "false",
							"prefix"					=> "ts-viewport-css-",
							"connector"					=> "css3animations_in",
							"default"					=> "flipInX",
							"value" 					=> "",
							"admin_label"				=> false,
							"description" 				=> __("Select the CSS3 in-animation you want to apply to the slider.", "ts_visual_composer_extend"),
							"dependency"            	=> "",
						),
						array(
							"type"                      => "hidden_input",
							"heading"                   => __( "In-Animation Type", "ts_visual_composer_extend" ),
							"param_name"                => "css3animations_in",
							"value"                     => "",
							"admin_label"		        => true,
							"description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"            	=> "",
						),						
						array(
							"type" 						=> "css3animations",
							"class" 					=> "",
							"heading" 					=> __("Out-Animation Type", "ts_visual_composer_extend"),
							"param_name" 				=> "animation_out",
							"standard"					=> "false",
							"prefix"					=> "ts-viewport-css-",
							"connector"					=> "css3animations_out",
							"default"					=> "slideOutDown",
							"value" 					=> "",
							"admin_label"				=> false,
							"description" 				=> __("Select the CSS3 out-animation you want to apply to the slider.", "ts_visual_composer_extend"),
							"dependency"            	=> "",
						),
						array(
							"type"                      => "hidden_input",
							"heading"                   => __( "Out-Animation Type", "ts_visual_composer_extend" ),
							"param_name"                => "css3animations_out",
							"value"                     => "",
							"admin_label"		        => true,
							"description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"            	=> "",
						),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Animate on Mobile", "ts_visual_composer_extend" ),
                            "param_name"                => "animation_mobile",
                            "value"                     => "false",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show the CSS3 animations on mobile devices.", "ts_visual_composer_extend" ),
                            "dependency"                => "",
                        ),
						array(
							"type"              	    => "switch",
							"heading"                   => __( "Auto-Height", "ts_visual_composer_extend" ),
							"param_name"                => "auto_height",
							"value"                     => "true",
							"on"					    => __( 'Yes', "ts_visual_composer_extend" ),
							"off"					    => __( 'No', "ts_visual_composer_extend" ),
							"style"					    => "select",
							"design"				    => "toggle-light",
							"admin_label"		        => true,
							"description"               => __( "Switch the toggle if you want the slider to auto-adjust its height.", "ts_visual_composer_extend" ),
							"dependency"                => ""
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Max. Number of Elements", "ts_visual_composer_extend" ),
							"param_name"                => "number_teasers",
							"value"                     => "1",
							"min"                       => "1",
							"max"                       => "10",
							"step"                      => "1",
							"unit"                      => '',
							"description"               => __( "Define the maximum number of elements per slide.", "ts_visual_composer_extend" ),
							"dependency" 				=> ""
						),
						array(
							"type"              	    => "switch",
							"heading"                   => __( "RTL Page", "ts_visual_composer_extend" ),
							"param_name"                => "page_rtl",
							"value"                     => "false",
							"on"					    => __( 'Yes', "ts_visual_composer_extend" ),
							"off"					    => __( 'No', "ts_visual_composer_extend" ),
							"style"					    => "select",
							"design"				    => "toggle-light",
							"description"               => __( "Switch the toggle if the slider is used on a page with RTL (Right-To-Left) alignment.", "ts_visual_composer_extend" ),
							"dependency"                => ""
						),
						array(
							"type"              	    => "switch",
							"heading"                   => __( "Auto-Play", "ts_visual_composer_extend" ),
							"param_name"                => "auto_play",
							"value"                     => "false",
							"on"					    => __( 'Yes', "ts_visual_composer_extend" ),
							"off"					    => __( 'No', "ts_visual_composer_extend" ),
							"style"					    => "select",
							"design"				    => "toggle-light",
							"admin_label"		        => true,
							"description"               => __( "Switch the toggle if you want the auto-play the slider on page load.", "ts_visual_composer_extend" ),
							"dependency"                => ""
						),
						array(
							"type"              	    => "switch",
							"heading"                   => __( "Show Progressbar", "ts_visual_composer_extend" ),
							"param_name"                => "show_bar",
							"value"                     => "true",
							"on"					    => __( 'Yes', "ts_visual_composer_extend" ),
							"off"					    => __( 'No', "ts_visual_composer_extend" ),
							"style"					    => "select",
							"design"				    => "toggle-light",
							"description"               => __( "Switch the toggle if you want to show a progressbar during auto-play.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "auto_play", "value" 	=> "true"),
						),
						array(
							"type"                      => "colorpicker",
							"heading"                   => __( "Progressbar Color", "ts_visual_composer_extend" ),
							"param_name"                => "bar_color",
							"value"                     => "#dd3333",
							"description"               => __( "Define the color of the animated progressbar.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "auto_play", "value" 	=> "true"),
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Auto-Play Speed", "ts_visual_composer_extend" ),
							"param_name"                => "show_speed",
							"value"                     => "5000",
							"min"                       => "1000",
							"max"                       => "20000",
							"step"                      => "100",
							"unit"                      => 'ms',
							"description"               => __( "Define the speed used to auto-play the slider.", "ts_visual_composer_extend" ),
							"dependency" 				=> array("element" 	=> "auto_play","value" 	=> "true"),
						),
						array(
							"type"              	    => "switch",
							"heading"                   => __( "Stop on Hover", "ts_visual_composer_extend" ),
							"param_name"                => "stop_hover",
							"value"                     => "true",
							"on"					    => __( 'Yes', "ts_visual_composer_extend" ),
							"off"					    => __( 'No', "ts_visual_composer_extend" ),
							"style"					    => "select",
							"design"				    => "toggle-light",
							"description"               => __( "Switch the toggle if you want the stop the auto-play while hovering over the slider.", "ts_visual_composer_extend" ),
							"dependency"                => array( 'element' => "auto_play", 'value' => 'true' )
						),
						array(
							"type"              	    => "switch",
							"heading"                   => __( "Show Navigation", "ts_visual_composer_extend" ),
							"param_name"                => "show_navigation",
							"value"                     => "true",
							"on"					    => __( 'Yes', "ts_visual_composer_extend" ),
							"off"					    => __( 'No', "ts_visual_composer_extend" ),
							"style"					    => "select",
							"design"				    => "toggle-light",
							"description"               => __( "Switch the toggle if you want to show left/right navigation buttons for the slider.", "ts_visual_composer_extend" ),
							"dependency"                => ""
						),
						array(
							"type"              	    => "switch",
							"heading"                   => __( "Show Dots", "ts_visual_composer_extend" ),
							"param_name"                => "show_dots",
							"value"                     => "true",
							"on"					    => __( 'Yes', "ts_visual_composer_extend" ),
							"off"					    => __( 'No', "ts_visual_composer_extend" ),
							"style"					    => "select",
							"design"				    => "toggle-light",
							"description"               => __( "Switch the toggle if you want to show the dot navigation buttons below the slider.", "ts_visual_composer_extend" ),
							"dependency"                => ""
						),
						// Other Settings
						array(
							"type"                      => "seperator",
							"heading"                   => __( "", "ts_visual_composer_extend" ),
							"param_name"                => "seperator_2",
							"value"                     => "Other Settings",
							"description"               => __( "", "ts_visual_composer_extend" ),
							"group" 			        => "Other Settings",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Margin: Top", "ts_visual_composer_extend" ),
							"param_name"                => "margin_top",
							"value"                     => "0",
							"min"                       => "0",
							"max"                       => "200",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
							"group" 			        => "Other Settings",
						),
						array(
							"type"                      => "nouislider",
							"heading"                   => __( "Margin: Bottom", "ts_visual_composer_extend" ),
							"param_name"                => "margin_bottom",
							"value"                     => "0",
							"min"                       => "0",
							"max"                       => "200",
							"step"                      => "1",
							"unit"                      => 'px',
							"description"               => __( "Select the bottom margin for the element.", "ts_visual_composer_extend" ),
							"group" 			        => "Other Settings",
						),
						array(
							"type"                      => "textfield",
							"heading"                   => __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"                => "el_id",
							"value"                     => "",
							"description"               => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
							"group" 			        => "Other Settings",
						),
						array(
							"type"                      => "textfield",
							"heading"                   => __( "Extra Class Name", "ts_visual_composer_extend" ),
							"param_name"                => "el_class",
							"value"                     => "",
							"description"               => __( "Enter a class name for the element.", "ts_visual_composer_extend" ),
							"group" 			        => "Other Settings",
						),
						// Load Custom CSS/JS File
                        array(
                            "type"                      => "load_file",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "el_file1",
                            "value"                     => "",
                            "file_type"                 => "js",
							"file_id"         			=> "ts-extend-element",
                            "file_path"                 => "js/ts-visual-composer-extend-element.min.js",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
						array(
							"type"              		=> "load_file",
							"heading"           		=> __( "", "ts_visual_composer_extend" ),
							"param_name"        		=> "el_file2",
							"value"             		=> "",
							"file_type"         		=> "css",
							"file_id"         			=> "ts-extend-animations",
							"file_path"         		=> "css/ts-visual-composer-extend-animations.min.css",
							"description"       		=> __( "", "ts_visual_composer_extend" )
						),
					),
					"js_view"                           => 'VcColumnView'
				));
			}
		}
	}
}
// Register Container and Child Shortcode with Visual Composer
if (class_exists('WPBakeryShortCodesContainer')) {
	class WPBakeryShortCode_TS_VCSC_Anything_Slider extends WPBakeryShortCodesContainer {};
}
// Initialize "TS Anything Slider" Class
if (class_exists('TS_Anything_Slider')) {
	$TS_Anything_Slider = new TS_Anything_Slider;
}