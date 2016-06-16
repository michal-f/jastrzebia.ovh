<?php
if (!class_exists('TS_Image_Hotspot')){
	class TS_Image_Hotspot {
		function __construct() {
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
                    add_action('init',                              	array($this, 'TS_VCSC_Add_Image_Hotspot_Elements'), 9999999);
                } else {
                    add_action('admin_init',		                	array($this, 'TS_VCSC_Add_Image_Hotspot_Elements'), 9999999);
                }
            } else {
                add_action('admin_init',								array($this, 'TS_VCSC_Add_Image_Hotspot_Elements'), 9999999);
            }
			add_shortcode('TS_VCSC_Image_Hotspot_Single',              	array($this, 'TS_VCSC_Image_Hotspot_Single'));
			add_shortcode('TS_VCSC_Image_Hotspot_Container',       		array($this, 'TS_VCSC_Image_Hotspot_Container'));
		}

		// Single Hotspot
		function TS_VCSC_Image_Hotspot_Single ($atts, $content = null) {
			global $VISUAL_COMPOSER_EXTENSIONS;
			ob_start();
		
			extract( shortcode_atts( array(
				'hotspot_positions'				=> '0,0',
				'hotspot_show_title'			=> 'true',
				'hotspot_title'					=> '',
				'hotspot_event'					=> 'none',
				'hotspot_pulse'					=> 'true',
				
				'hotspot_color_dot'				=> '#45453f',
				'hotspot_color_circle'			=> '#f7f14c',
				'hotspot_color_pulse'			=> '#fff601',
				
				'hotspot_text'					=> '',
				'hotspot_picture'				=> '',
				'hotspot_link'					=> '',				
				'hotspot_video_link'			=> '',
				'hotspot_video_auto'			=> 'true',
				'hotspot_video_related'			=> 'false',
				
				'height'						=> 500,
				'width'							=> 300,
				'content_style'					=> '',
				
				'content_image_responsive'		=> 'true',
				'content_image_height'			=> 'height: 100%;',
				'content_image_width_r'			=> 100,
				'content_image_width_f'			=> 300,
				'content_image_size'			=> 'large',
				'content_tooltip_css'			=> 'true',
				'content_tooltip_content'		=> '',
				'content_tooltip_position'		=> 'ts-simptip-position-top',
				'content_tooltip_style'			=> '',
				
				'lightbox_group'				=> 'false',
				'lightbox_group_name'			=> '',
				'lightbox_size'					=> 'full',
				'lightbox_effect'				=> 'random',
				'lightbox_speed'				=> 5000,
				'lightbox_social'				=> 'true',
				'lightbox_backlight_choice'		=> 'predefined',
				'lightbox_backlight_color'		=> '#0084E2',
				'lightbox_backlight_custom'		=> '#000000',
				
				'lightbox_custom_padding'		=> 15,
				'lightbox_custom_background'	=> 'none',
				'lightbox_background_image'		=> '',
				'lightbox_background_size'		=> 'cover',
				'lightbox_background_repeat'	=> 'no-repeat',
				'lightbox_background_color'		=> '#ffffff',
				
				'el_id' 						=> '',
				'el_class'                  	=> '',
				'css'							=> '',
			), $atts ));
			
			$hotspot_random                    	= mt_rand(999999, 9999999);
			
			if (!empty($el_id)) {
				$modal_id						= $el_id;
			} else {
				$modal_id						= 'ts-vcsc-modal-' . mt_rand(999999, 9999999);
			}
			
			// Check for Front End Editor
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
					$hotspot_frontend			= "true";
                } else {
					$hotspot_frontend			= "false";
                }
            } else {
				$hotspot_frontend				= "false";
            }
			
			// iFrame / Link
			if (($hotspot_event == "link") || ($hotspot_event == "iframe")) {
				$link 							= ($hotspot_link == '||') ? '' : $hotspot_link;
				$link 							= vc_build_link($link);
				$a_href							= $link['url'];
				$a_title 						= $link['title'];
				$a_target 						= $link['target'];
			}
			// Image
			if ($hotspot_event == "image") {
				if (!empty($hotspot_picture)) {
					$modal_image				= wp_get_attachment_image_src($hotspot_picture, 'full');
				}
			}			
			// YouTube Video
			if ($hotspot_event == "youtube") {
				if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $hotspot_video_link)) {
					$hotspot_video_link		= $hotspot_video_link;
				} else {
					$hotspot_video_link		= 'https://www.youtube.com/watch?v=' . $hotspot_video_link;
				}
			}
			// DailyMotion Video
			if ($hotspot_event == "dailymotion") {
				if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $hotspot_video_link)) {
					$hotspot_video_link	= $hotspot_video_link;
				} else {			
					$hotspot_video_link	= $hotspot_video_link;
				}
			}
			
			// Tooltip
			if ($content_tooltip_css == "true") {
				if (strlen($content_tooltip_content) != 0) {
					$popup_tooltipclasses		= " ts-simptip-multiline " . $content_tooltip_style . " " . $content_tooltip_position;
					$popup_tooltipcontent		= ' data-tstooltip="' . $content_tooltip_content . '"';
				} else {
					$popup_tooltipclasses		= "";
					$popup_tooltipcontent		= "";
				}
			} else {
				$popup_tooltipclasses			= "";
				if (strlen($content_tooltip_content) != 0) {
					$popup_tooltipcontent		= ' title="' . $content_tooltip_content . '"';
				} else {
					$popup_tooltipcontent		= "";
				}
			}
			
			if ($content_image_responsive == "true") {
				$image_dimensions				= 'width: 100%; height: auto;';
				$parent_dimensions				= 'width: ' . $content_image_width_r . '%; ' . $content_image_height . '';
			} else {
				$image_dimensions				= 'width: 100%; height: auto;';
				$parent_dimensions				= 'width: ' . $content_image_width_f . 'px; ' . $content_image_height . '';
			}
			

			// Backlight Color
			if ($lightbox_backlight_choice == "predefined") {
				$lightbox_backlight_selection	= $lightbox_backlight_color;
			} else {
				$lightbox_backlight_selection	= $lightbox_backlight_custom;
			}
	
			// Custom Width / Height
			$lightbox_dimensions				= '';
			
			// Background Settings
			if ($lightbox_custom_background == "image") {
				$background_image 				= wp_get_attachment_image_src($lightbox_background_image, 'full');
				$background_image 				= $background_image[0];
				$lightbox_background			= 'background: url(' . $background_image . ') ' . $lightbox_background_repeat . ' 0 0; background-size: ' . $lightbox_background_size . ';';
			} else if ($lightbox_custom_background == "color") {
				$lightbox_background			= 'background: ' . $lightbox_background_color . ';';
			} else {
				$lightbox_background			= '';
			}
			
			$hotspot_positions					= explode(",", $hotspot_positions);
			
			$style_hotspot_dot					= 'background: ' . $hotspot_color_dot . '; border-color: ' . $hotspot_color_circle . ';';
			$style_hotspot_pulse				= 'border-color: ' . $hotspot_color_pulse . ';';

			$output = '';
			
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-image-hotspot-single ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Image_Hotspot_Single', $atts);
			} else {
				$css_class	= 'ts-image-hotspot-single ' . $el_class;
			}
			
			$output .= '<div class="ts-image-hotspot-single-container">';
				if ($hotspot_frontend == "false") {
					$output .= '<div class="' . $css_class . '" style="left: ' . $hotspot_positions[0] . '%; top: ' . $hotspot_positions[1] . '%;">';
						if (($hotspot_event != "none") && ($hotspot_event == "popup")) {
							// Modal Popup
							$output .= '<a id="' . $modal_id . '-trigger" href="#' . $modal_id . '" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-modal" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-type="html" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						} else if (($hotspot_event != "none") && ($hotspot_event == "iframe")) {
							// iFrame Popup
							$output .= '<a id="' . $modal_id . '-trigger" href="' . $a_href . '" target="' . $a_target . '" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-type="iframe" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						} else if (($hotspot_event != "none") && ($hotspot_event == "image")) {
							// Image Popup
							$output .= '<a id="' . $modal_id . '-trigger" href="' . $modal_image[0] . '" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						} else if (($hotspot_event != "none") && ($hotspot_event == "youtube")) {
							// YouTube Popup
							$output .= '<a id="' . $modal_id . '-trigger" href="' . $hotspot_video_link .'" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-related="' . $hotspot_video_related .'" data-videoplay="' . $hotspot_video_auto .'" data-type="youtube" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						} else if (($hotspot_event != "none") && ($hotspot_event == "vimeo")) {
							// Vimeo Popup
							$output .= '<a id="' . $modal_id . '-trigger" href="' . $hotspot_video_link . '" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-videoplay="' . $hotspot_video_auto . '" data-type="vimeo" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						} else if (($hotspot_event != "none") && ($hotspot_event == "dailymotion")) {
							// DailyMotion Popup
							$output .= '<a id="' . $modal_id . '-trigger" href="' . $hotspot_video_link .'" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-videoplay="' . $hotspot_video_auto . '" data-type="dailymotion" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						} else if (($hotspot_event != "none") && ($hotspot_event == "html5")) {
							// HTML5 Video Popup
							$output .= '<a id="' . $modal_id . '-trigger" href="#' . $modal_id . '" class="ts-image-hotspot-trigger ' . $modal_id . '-parent nch-holder nch-lightbox-media" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '" data-type="html" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						} else if (($hotspot_event != "none") && ($hotspot_event == "link")) {
							// Link Event
							$output .= '<a id="' . $modal_id . '-trigger" href="' . $a_href . '" target="' . $a_target . '" class="ts-image-hotspot-trigger ' . $modal_id . '-parent" style="" data-title="' . $hotspot_title . '">';
						} else {
							// No Hotspot Event
							$output .= '<div id="' . $modal_id . '-trigger" class="ts-image-hotspot-trigger ' . $modal_id . '-parent" ' . $lightbox_dimensions . ' style="" data-title="' . $hotspot_title . '">';
						}
							$output 		.= '<div class="ts-image-hotspot-trigger-pulse" style="' . $style_hotspot_pulse . '"></div>';
							$output 		.= '<div class="ts-image-hotspot-trigger-dot ' . $popup_tooltipclasses . '" ' . $popup_tooltipcontent . ' style="' . $style_hotspot_dot . '"></div>';
						if ($hotspot_event != "none") {
							$output .= '</a>';
						} else {
							$output .= '</div>';
						}
					$output .= '</div>';
				} else {
					$output .= '<div class="ts-image-hotspot-single-edit" style="">';					
						if ($hotspot_title) {
							$output .= '<div style="font-weight: bold;">Hotspot - ' . $hotspot_title . ':</div>';
						} else {
							$output .= '<div style="font-weight: bold;">Hotspot Data:</div>';
						}
						$output .= '<div>Top: ' . $hotspot_positions[1] . '% / Left: ' . $hotspot_positions[0] . '% / Event: ' . ucfirst($hotspot_event) . '</div>';
					$output .= '</div>';
				}
				// Create hidden DIV with Modal Popup Hotspot Content
				if (($hotspot_frontend == "false") && ($hotspot_event == "popup")) {
					$output .= '<div id="' . $modal_id . '" class="ts-modal-content nch-hide-if-javascript ' . $el_class . '" style="display: none; padding: ' . $lightbox_custom_padding . 'px; ' . $lightbox_background . '">';
						$output .= '<div class="ts-modal-white-header"></div>';
						$output .= '<div class="ts-modal-white-frame" style="">';
							$output .= '<div class="ts-modal-white-inner">';
								if (($hotspot_show_title == "true") && ($hotspot_title != "")) {
									$output .= '<h2 style="border-bottom: 1px solid #eeeeee; padding-bottom: 10px; margin-bottom: 10px;">' . $hotspot_title . '</h2>';
								}
								$output .= rawurldecode(base64_decode(strip_tags($hotspot_text)));
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				}
			$output .= '</div>';
			
			echo $output;
			
			$myvariable = ob_get_clean();
			return $myvariable;
		}
		
		// Hotspot Container
		function TS_VCSC_Image_Hotspot_Container ($atts, $content = null){
			global $VISUAL_COMPOSER_EXTENSIONS;
			ob_start();
	
			// Check for Front End Editor
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
					$hotspot_frontend			= "true";
                } else {
					$hotspot_frontend			= "false";
                }
            } else {
				$hotspot_frontend				= "false";
            }
			
			if ($hotspot_frontend == "false") {
				wp_enqueue_script('ts-extend-hammer');
				wp_enqueue_script('ts-extend-nacho');
				wp_enqueue_style('ts-extend-nacho');
			}
			if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
				wp_enqueue_style('ts-extend-simptip');
				wp_enqueue_style('ts-extend-animations');
				wp_enqueue_style('ts-visual-composer-extend-front');
				wp_enqueue_script('ts-visual-composer-extend-front');
			}
			
			extract( shortcode_atts( array(
				'hotspot_image'					=> '',				
				'hotspot_break'					=> 'false',
				'hotspot_break_parents'			=> 5,
				'hotspot_break_zindex'			=> 0,
				'margin_left'					=> 0,
				'margin_right'					=> 0,
				'margin_top'                    => 0,
				'margin_bottom'                 => 0,
				'el_id' 						=> '',
				'el_class'                  	=> '',
				'css'							=> '',
			), $atts ));
			
			$hotspot_random                    	= mt_rand(999999, 9999999);
			
			if (!empty($el_id)) {
				$image_hotspot_id			    = $el_id;
			} else {
				$image_hotspot_id			    = 'ts-vcsc-image-hotspot-' . $hotspot_random;
			}			
			
			if (!empty($hotspot_image)) {
				$modal_image 					= wp_get_attachment_image_src($hotspot_image, 'full');
			}
			
			$breakouts_data						= 'data-inline="' . $hotspot_frontend . '" data-index="' . $hotspot_break_zindex . '" data-marginleft="' . $margin_left . '" data-marginright="' . $margin_right . '" data-image="' . $modal_image[0] . '" data-break-parents="' . esc_attr($hotspot_break_parents) . '"';

			$output = '';
			
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-image-hotspot-container ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Image_Hotspot_Container', $atts);
			} else {
				$css_class	= 'ts-image-hotspot-container ' . $el_class;
			}
			
			if ($hotspot_frontend == "false") {
				if ($hotspot_break == "true") {
					$output .= '<div id="' . $image_hotspot_id . '-fullwidth" class="ts-image-full-frame ' . $css_class . '" style="width: 100%; height: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;" ' . $breakouts_data . '>';
				}
					$output .= '<div id="' . $image_hotspot_id . '" class="' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
						$output .= '<img class="ts-image-hotspot-image ' . ($hotspot_break == "true" ? "ts-imagefull" : "") . '" src="' . $modal_image[0] . '">';
						$output .= '<div class="ts-image-hotspot-holder">';
							$output .= do_shortcode($content);
						$output .= '</div>';
					$output .= '</div>';
				if ($hotspot_break == "true") {
					$output .= '</div>';
				}
			} else {
				$output .= '<div id="' . $image_hotspot_id . '" class="ts-image-hotspot-container-edit" style="margin-top: 40px; margin-bottom: 40px;">';
					$output .= '<img class="ts-image-hotspot-image-edit" src="' . $modal_image[0] . '">';
					$output .= '<div class="ts-image-hotspot-holder-edit">';
						$output .= do_shortcode($content);
					$output .= '</div>';
				$output .= '</div>';
			}
			
			echo $output;
			
			$myvariable = ob_get_clean();
			return $myvariable;
		}
	
		// Add Hotspot Elements
        function TS_VCSC_Add_Image_Hotspot_Elements() {
			global $VISUAL_COMPOSER_EXTENSIONS;
			// Add Single Hotspot
			if (function_exists('vc_map')) {
				vc_map( array(
					"name"                      	=> __( "TS Single Hotspot", "ts_visual_composer_extend" ),
					"base"                      	=> "TS_VCSC_Image_Hotspot_Single",
					"icon" 	                    	=> "icon-wpb-ts_vcsc_image_hotspot_single",
					"class"                     	=> "ts_vcsc_main_image_hotspot",
					"content_element"                => true,
					"as_child"                       => array('only' => 'TS_VCSC_Image_Hotspot_Container'),
					"category"                  	=> __( 'VC Extensions', "ts_visual_composer_extend" ),
					"js_view"						=> "TS_VCSC_HotspotSingleViewCustom",
					//"admin_enqueue_js"        	=> preg_replace( '/\s/', '%20', TS_VCSC_GetResourceURL('/js/ts-visual-composer-extend-backbone-other.js')),
					"admin_enqueue_css"       		=> array(''),
					"front_enqueue_js"				=> preg_replace( '/\s/', '%20', TS_VCSC_GetResourceURL('/js/frontend/ts-vcsc-frontend-hotspot-single.min.js')),
					"front_enqueue_css"				=> array(''),
					"params"                    	=> array(
						// Hotspot Settings
						array(
							"type"              	=> "seperator",
							"heading"           	=> __( "", "ts_visual_composer_extend" ),
							"param_name"        	=> "seperator_1",
							"value"             	=> "Hotspot Title",
							"description"       	=> __( "", "ts_visual_composer_extend" )
						),
						array(
							"type"					=> "textfield",
							"heading"				=> __( "Hotspot Title", "ts_visual_composer_extend" ),
							"param_name"			=> "hotspot_title",
							"value"					=> "",
							"admin_label"       	=> true,
							"description"			=> __( "Enter a hotspot title to be used for easier identification when in edit mode.", "ts_visual_composer_extend" ),
						),
						array(
							"type"              	=> "seperator",
							"heading"           	=> __( "", "ts_visual_composer_extend" ),
							"param_name"        	=> "seperator_2",
							"value"             	=> "Hotspot Position",
							"description"       	=> __( "", "ts_visual_composer_extend" )
						),
						array(
							"type"                  => "imagehotspot",
							"heading"               => __( "Hotspot Positions", "ts_visual_composer_extend" ),
							"param_name"            => "hotspot_positions",
							"value"                 => "0,0",
							"min"                   => "0",
							"max"                   => "100",
							"step"                  => "1",
							"unit"                  => '%',
							"admin_label"       	=> true,
							"description"       	=> "",
							"dependency"			=> "",
						),
						array(
							"type"              	=> "seperator",
							"heading"           	=> __( "", "ts_visual_composer_extend" ),
							"param_name"        	=> "seperator_3",
							"value"             	=> "Hotspot Style",
							"description"       	=> __( "", "ts_visual_composer_extend" )
						),
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Inner Dot Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "hotspot_color_dot",
							"value"             	=> "#45453f",
							"description"       	=> __( "Define the color for the inner dot of the hotspot.", "ts_visual_composer_extend" ),
							"dependency"            => "",
						),
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Outer Circle Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "hotspot_color_circle",
							"value"             	=> "#f7f14c",
							"description"       	=> __( "Define the color for the outer dot border of the hotspot.", "ts_visual_composer_extend" ),
							"dependency"            => "",
						),
						array(
							"type"                  => "switch",
							"heading"			    => __( "Use Pulse Effect", "ts_visual_composer_extend" ),
							"param_name"		    => "hotspot_pulse",
							"value"                 => "true",
							"on"				    => __( 'Yes', "ts_visual_composer_extend" ),
							"off"				    => __( 'No', "ts_visual_composer_extend" ),
							"style"				    => "select",
							"design"			    => "toggle-light",
							"description"		    => __( "Switch the toggle if you want have a pulse effect for the hotspot.", "ts_visual_composer_extend" ),
							"dependency"            => "",
						),
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Pulse Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "hotspot_color_pulse",
							"value"             	=> "#fff601",
							"description"       	=> __( "Define the color for the pulsating ring of the hotspot.", "ts_visual_composer_extend" ),
							"dependency"		    => array( 'element' => "hotspot_pulse", 'value' => 'true' ),
						),						
						// Tooltip Settings
						array(
							"type"				    => "seperator",
							"heading"			    => __( "", "ts_visual_composer_extend" ),
							"param_name"		    => "seperator_4",
							"value"					=> "",
							"seperator"				=> "Hotspot Tooltip",
							"description"		    => __( "", "ts_visual_composer_extend" ),
							"dependency"            => "",
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"                  => "switch",
							"heading"			    => __( "Use CSS3 Tooltip", "ts_visual_composer_extend" ),
							"param_name"		    => "content_tooltip_css",
							"value"                 => "true",
							"on"				    => __( 'Yes', "ts_visual_composer_extend" ),
							"off"				    => __( 'No', "ts_visual_composer_extend" ),
							"style"				    => "select",
							"design"			    => "toggle-light",
							"description"		    => __( "Switch the toggle if you want to apply a CSS3 tooltip to the hotspot.", "ts_visual_composer_extend" ),
							"dependency"            => "",
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"				    => "textarea",
							"class"				    => "",
							"heading"			    => __( "Tooltip Content", "ts_visual_composer_extend" ),
							"param_name"		    => "content_tooltip_content",
							"value"				    => "",
							"description"		    => __( "Enter the tooltip content here (do not use quotation marks).", "ts_visual_composer_extend" ),
							"dependency"            => "",
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"				    => "dropdown",
							"class"				    => "",
							"heading"			    => __( "Tooltip Position", "ts_visual_composer_extend" ),
							"param_name"		    => "content_tooltip_position",
							"value"                 => array(
								__("Top", "ts_visual_composer_extend")                    	=> "ts-simptip-position-top",
								__("Bottom", "ts_visual_composer_extend")                 	=> "ts-simptip-position-bottom",
								__("Left", "ts_visual_composer_extend")						=> "ts-simptip-position-left",
								__("Right", "ts_visual_composer_extend")                 	=> "ts-simptip-position-right",
							),
							"description"		    => __( "Select the tooltip position in relation to the hotspot.", "ts_visual_composer_extend" ),
							"dependency"		    => array( 'element' => "content_tooltip_css", 'value' => 'true' ),
							"group" 				=> "Hotspot Tooltip",
						),
						array(
							"type"				    => "dropdown",
							"class"				    => "",
							"heading"			    => __( "Tooltip Style", "ts_visual_composer_extend" ),
							"param_name"		    => "content_tooltip_style",
							"value"                 => array(
								__("Black", "ts_visual_composer_extend")                  	=> "",
								__("Gray", "ts_visual_composer_extend")                   	=> "ts-simptip-style-gray",
								__("Green", "ts_visual_composer_extend")                  	=> "ts-simptip-style-green",
								__("Blue", "ts_visual_composer_extend")                   	=> "ts-simptip-style-blue",
								__("Red", "ts_visual_composer_extend")                    	=> "ts-simptip-style-red",
								__("Orange", "ts_visual_composer_extend")                 	=> "ts-simptip-style-orange",
								__("Yellow", "ts_visual_composer_extend")                 	=> "ts-simptip-style-yellow",
								__("Purple", "ts_visual_composer_extend")                 	=> "ts-simptip-style-purple",
								__("Pink", "ts_visual_composer_extend")                   	=> "ts-simptip-style-pink",
								__("White", "ts_visual_composer_extend")                  	=> "ts-simptip-style-white"
							),
							"description"		    => __( "Select the tooltip style.", "ts_visual_composer_extend" ),
							"dependency"		    => array( 'element' => "content_tooltip_css", 'value' => 'true' ),
							"group" 				=> "Hotspot Tooltip",
						),
						// Hotspot Content
						array(
							"type"				    => "seperator",
							"heading"			    => __( "", "ts_visual_composer_extend" ),
							"param_name"		    => "seperator_5",
							"value"					=> "",
							"seperator"				=> "Hotspot Event",
							"description"		    => __( "", "ts_visual_composer_extend" ),
							"dependency"            => "",
							"group" 				=> "Hotspot Event",
						),						
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Hotspot Event", "ts_visual_composer_extend" ),
							"param_name"            => "hotspot_event",
							"width"                 => 150,
							"value" 				=> array(
								__( "None", "ts_visual_composer_extend" )									=> "none",
								__( "Open Popup in Lightbox", "ts_visual_composer_extend" )					=> "popup",
								__( "Open Image in Lightbox", "ts_visual_composer_extend" )					=> "image",
								__( "Open YouTube Video in Lightbox", "ts_visual_composer_extend" )			=> "youtube",
								__( "Open Vimeo Video in Lightbox", "ts_visual_composer_extend" )			=> "vimeo",
								__( "Open DailyMotion Video in Lightbox", "ts_visual_composer_extend" )		=> "dailymotion",
								__( "Open Page in iFrame", "ts_visual_composer_extend" )					=> "iframe",
								__( "Simple Link to Page", "ts_visual_composer_extend" )					=> "link",
							),
							"description"           => __( "Select if the hotspot should trigger any other action, aside from the tooltip.", "ts_visual_composer_extend" ),
							"admin_label"       	=> true,
							"dependency"            => "",
							"group" 				=> "Hotspot Event",
						),
						// Modal Popup
						array(
							"type"                  => "switch",
							"heading"			    => __( "Show Hotspot Title", "ts_visual_composer_extend" ),
							"param_name"		    => "hotspot_show_title",
							"value"                 => "true",
							"on"				    => __( 'Yes', "ts_visual_composer_extend" ),
							"off"				    => __( 'No', "ts_visual_composer_extend" ),
							"style"				    => "select",
							"design"			    => "toggle-light",
							"description"		    => __( "Switch the toggle if you want to show the title in the modal popup.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'popup' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"              	=> "textarea_raw_html",
							"heading"           	=> __( "Hotspot Description", "ts_visual_composer_extend" ),
							"param_name"        	=> "hotspot_text",
							"value"             	=> base64_encode(""),
							"description"       	=> __( "Enter the more detailed description for the modal popup; HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "hotspot_event", 'value' => 'popup' ),
							"group" 				=> "Hotspot Event",
						),
						// Lightbox Image
						array(
							"type"					=> "attach_image",
							"heading"				=> __( "Image", "ts_visual_composer_extend" ),
							"param_name"			=> "hotspot_picture",
							"class"					=> "",
							"value"					=> "",
							"description"			=> __( "Select the image you want to open when clicked on the hotspot.", "ts_visual_composer_extend" ),
							"dependency"        	=> array( 'element' => "hotspot_event", 'value' => 'image' ),
							"group" 				=> "Hotspot Event",
						),
						// YouTube / DailyMotion / Vimeo
						array(
							"type"                  => "textfield",
							"heading"               => __( "Video URL", "ts_visual_composer_extend" ),
							"param_name"            => "hotspot_video_link",
							"value"                 => "",
							"description"           => __( "Enter the URL for the video to be shown in a lightbox.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => array('youtube','dailymotion','vimeo') ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"              	=> "switch",
							"heading"			    => __( "Show Related Videos", "ts_visual_composer_extend" ),
							"param_name"		    => "hotspot_video_related",
							"value"             	=> "false",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"description"		    => __( "Switch the toggle if you want to show related videos once the video has finished playing.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => 'youtube' ),
							"group" 				=> "Hotspot Event",
						),
						array(
							"type"              	=> "switch",
							"heading"			    => __( "Autoplay Video", "ts_visual_composer_extend" ),
							"param_name"		    => "hotspot_video_auto",
							"value"             	=> "true",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"description"		    => __( "Switch the toggle if you want to auto-play the video once opened in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => array('youtube','dailymotion','vimeo') ),
							"group" 				=> "Hotspot Event",
						),
						// Link / iFrame
						array(
							"type" 					=> "vc_link",
							"heading" 				=> __("Link + Title", "ts_visual_composer_extend"),
							"param_name" 			=> "hotspot_link",
							"description" 			=> __("Provide a link to another site/page to be used for the hotspot event.", "ts_visual_composer_extend"),
							"dependency"            => array( 'element' => "hotspot_event", 'value' => array('iframe','link') ),
							"group" 				=> "Hotspot Event",
						),
						// Lightbox Settings
						array(
							"type"                  => "seperator",
							"heading"               => __( "", "ts_visual_composer_extend" ),
							"param_name"            => "seperator_6",
							"value"					=> "",
							"seperator"             => "Hotspot Popup Settings",
							"description"           => __( "", "ts_visual_composer_extend" ),
							"group" 				=> "Lightbox Settings",
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
							"description"           => __( "Select the transition effect to be used for the hotspot content in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            => "",
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"              	=> "switch",
							"heading"			    => __( "Create AutoGroup", "ts_visual_composer_extend" ),
							"param_name"		    => "lightbox_group",
							"value"				    => "false",
							"on"					=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"					=> __( 'No', "ts_visual_composer_extend" ),
							"style"					=> "select",
							"design"				=> "toggle-light",
							"description"		    => __( "Switch the toggle if you want the plugin to group this hotspot event with all other lightbox items on the page.", "ts_visual_composer_extend" ),
							"dependency"        	=> "",
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"                  => "textfield",
							"heading"               => __( "Group Name", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_group_name",
							"value"                 => "",
							"description"           => __( "Enter a custom group name to manually build a group with other lightbox items.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_group", 'value' => 'false' ),
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Backlight Color", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_backlight_choice",
							"width"                 => 150,
							"value"                 => array(
								__( 'Predefined Color', "ts_visual_composer_extend" )	=> "predefined",
								__( 'Custom Color', "ts_visual_composer_extend" )		=> "customized",
							),
							"description"           => __( "Select the (backlight) color style for the hotspot content.", "ts_visual_composer_extend" ),
							"dependency"            => "",
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"                  => "dropdown",
							"heading"               => __( "Select Backlight Color", "ts_visual_composer_extend" ),
							"param_name"            => "lightbox_backlight_color",
							"width"                 => 150,
							"value"                 => array(
								__( 'Default', "ts_visual_composer_extend" )      		=> "#0084E2",
								__( 'Neutral', "ts_visual_composer_extend" )      		=> "#FFFFFF",
								__( 'Success', "ts_visual_composer_extend" )      		=> "#4CFF00",
								__( 'Warning', "ts_visual_composer_extend" )      		=> "#EA5D00",
								__( 'Error', "ts_visual_composer_extend" )        		=> "#CC0000",
								__( 'None', "ts_visual_composer_extend" )         		=> "#000000",
							),
							"description"           => __( "Select the predefined backlight color for the hotspot popup.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_backlight_choice", 'value' => 'predefined' ),
							"group" 				=> "Lightbox Settings",
						),
						array(
							"type"              	=> "colorpicker",
							"heading"           	=> __( "Select Backlight Color", "ts_visual_composer_extend" ),
							"param_name"        	=> "lightbox_backlight_custom",
							"value"             	=> "#000000",
							"description"       	=> __( "Define a custom backlight color for the hotspot popup.", "ts_visual_composer_extend" ),
							"dependency"            => array( 'element' => "lightbox_backlight_choice", 'value' => 'customized' ),
							"group" 				=> "Lightbox Settings",
						),
						// Load Custom CSS/JS File
						array(
							"type"              	=> "load_file",
							"heading"           	=> __( "", "ts_visual_composer_extend" ),
							"param_name"        	=> "el_file",
							"value"             	=> "",
							"file_type"         	=> "js",
							"file_path"         	=> "js/ts-visual-composer-extend-element.min.js",
							"description"       	=> __( "", "ts_visual_composer_extend" )
						),
					))
				);
			}
			// Add Hotspot Container
			if (function_exists('vc_map')) {
				vc_map(array(
					"name"                              => __("TS Image Hotspot", "ts_visual_composer_extend"),
					"base"                              => "TS_VCSC_Image_Hotspot_Container",
					"class"                             => "",
					"icon"                              => "icon-wpb-ts_vcsc_image_hotspot_container",
					"category"                          => __("VC Extensions", "ts_visual_composer_extend"),
					"as_parent"                         => array('only' => 'TS_VCSC_Image_Hotspot_Single'),
					"description"                       => __("Create an image with hotspots", "ts_visual_composer_extend"),
					"content_element"                   => true,
					"show_settings_on_create"           => true,
					"js_view"                           => "TS_VCSC_HotspotContainerViewCustom",
					//"admin_enqueue_js"        		=> preg_replace( '/\s/', '%20', TS_VCSC_GetResourceURL('/js/ts-visual-composer-extend-backbone-other.js')),
					"admin_enqueue_css"       			=> '',
					"front_enqueue_js"					=> preg_replace( '/\s/', '%20', TS_VCSC_GetResourceURL('/js/frontend/ts-vcsc-frontend-hotspot-container.min.js')),
					"front_enqueue_css"					=> '',
					"params"                            => array(
						// Image Settings
						array(
							"type"                      => "seperator",
							"heading"                   => __( "", "ts_visual_composer_extend" ),
							"param_name"                => "seperator_1",
							"value"                     => "Hotspot Image",
							"description"               => __( "", "ts_visual_composer_extend" ),
						),
						array(
							"type"                  	=> "attach_image",
							"holder" 					=> "img",
							"heading"               	=> __( "Image", "ts_visual_composer_extend" ),
							"param_name"            	=> "hotspot_image",
							"class"						=> "ts_vcsc_holder_image",
							"value"                 	=> "",
							"description"           	=> __( "Select the image you want to use with the hotspots.", "ts_visual_composer_extend" )
						),
						array(
							"type"                  	=> "switch",
							"heading"			    	=> __( "Full Width Image", "ts_visual_composer_extend" ),
							"param_name"		    	=> "hotspot_break",
							"value"                 	=> "false",
							"on"				    	=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"				    	=> __( 'No', "ts_visual_composer_extend" ),
							"style"				    	=> "select",
							"design"			    	=> "toggle-light",
							"admin_label"				=> true,
							"description"		    	=> __( "Switch the toggle if you want to attempt to make the image full width.", "ts_visual_composer_extend" ),
							"dependency"            	=> "",
						),
						array(
							"type"						=> "nouislider",
							"heading"					=> __( "Full Width Breakouts", "ts_visual_composer_extend" ),
							"param_name"				=> "hotspot_break_parents",
							"value"						=> "5",
							"min"						=> "0",
							"max"						=> "99",
							"step"						=> "1",
							"unit"						=> '',
							"description"				=> __( "Define the number of parent containers the image should attempt to break away from.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "hotspot_break", 'value' => 'true' ),
						),
						// Other Settings
						array(
							"type"                      => "seperator",
							"heading"                   => __( "", "ts_visual_composer_extend" ),
							"param_name"                => "seperator_2",
							"value"                     => "Other Settings",
							"description"               => __( "", "ts_visual_composer_extend" ),
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
						),
						array(
							"type"                      => "textfield",
							"heading"                   => __( "Define ID Name", "ts_visual_composer_extend" ),
							"param_name"                => "el_id",
							"value"                     => "",
							"description"               => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" ),
						),
						array(
							"type"                      => "textfield",
							"heading"                   => __( "Extra Class Name", "ts_visual_composer_extend" ),
							"param_name"                => "el_class",
							"value"                     => "",
							"description"               => __( "Enter a class name for the element.", "ts_visual_composer_extend" ),
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
					),
				));
			}
		}
	}
}
// Register Container and Child Shortcode with Visual Composer
if (class_exists('WPBakeryShortCodesContainer')) {
	class WPBakeryShortCode_TS_VCSC_Image_Hotspot_Container extends WPBakeryShortCodesContainer {};
}
if (class_exists('WPBakeryShortCode')) {
	class WPBakeryShortCode_TS_VCSC_Image_Hotspot_Single extends WPBakeryShortCode {};
}
// Initialize "TS Image Hotspots" Class
if (class_exists('TS_Image_Hotspot')) {
	$TS_Image_Hotspot = new TS_Image_Hotspot;
}