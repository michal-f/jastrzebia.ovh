<?php
if (!class_exists('TS_Image_IHover')){
	class TS_Image_IHover {
		function __construct() {
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
                    add_action('init',                                  array($this, 'TS_VCSC_Image_IHover_Elements'), 9999999);
                } else {
                    add_action('admin_init',		                    array($this, 'TS_VCSC_Image_IHover_Elements'), 9999999);
                }
            } else {
                add_action('admin_init',                                array($this, 'TS_VCSC_Image_IHover_Elements'), 9999999);
            }
            add_shortcode('TS_VCSC_Image_IHover',              			array($this, 'TS_VCSC_Image_IHover_Function'));
		}
        
        // Image IHover
        function TS_VCSC_Image_IHover_Function ($atts, $content = null) {
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();
			
			// Check for Front End Editor
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
					$ihover_frontent			= "true";
                } else {
					$ihover_frontent			= "false";
                }
            } else {
				$ihover_frontent				= "false";
            }

			if ($ihover_frontent == "false") {
				wp_enqueue_script('ts-extend-hammer');
				wp_enqueue_script('ts-extend-nacho');
				wp_enqueue_style('ts-extend-nacho');
			}	
            if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
                wp_enqueue_style('ts-extend-ihover');
				wp_enqueue_script('ts-extend-ihover');
				wp_enqueue_script('ts-extend-badonkatrunc');
                wp_enqueue_style('ts-visual-composer-extend-front');
                wp_enqueue_script('ts-visual-composer-extend-front');
            }
            
            extract( shortcode_atts( array(
                'ihover_image'					=> '',
				'ihover_responsive'				=> 'true',
				'ihover_truncate'				=> 'false',
				'ihover_style'					=> 'circle',
				'ihover_colored'				=> 'false',
				
				'ihover_circle_effect'			=> 'effect1',
				'ihover_circle_border'			=> 10,
				'ihover_circle_color1'			=> '#ecab18',
				'ihover_circle_color2'			=> '#1ad280',
				'ihover_circle_direction'		=> 'left_to_right',
				'ihover_circle_direction2'		=> 'top_to_bottom',
				'ihover_circle_direction3'		=> 'from_left_and_right',
				'ihover_circle_direction4'		=> 'left_to_right',
				'ihover_circle_direction5'		=> '',
				'ihover_circle_scale'			=> 'scale_up',
				
				'ihover_square_effect'			=> '',
				'ihover_square_border'			=> 8,
				'ihover_square_color'			=> '#ffffff',
				'ihover_square_direction'		=> 'from_left_and_right',
				'ihover_square_direction2'		=> 'left_and_right',
				'ihover_square_direction3'		=> 'top_to_bottom',
				'ihover_square_direction4'		=> 'left_to_right',
				'ihover_square_direction5'		=> 'left_to_right',
				'ihover_square_scale'			=> 'scale_up',
				'ihover_title'					=> 'Heading here',
				'ihover_content'				=> 'Description goes here.',

				'ihover_event'					=> 'none',
				'ihover_show_title'				=> 'true',
				'ihover_link'					=> '',
				'ihover_text'					=> '',
				'ihover_image'					=> '',
				'ihover_link'					=> '',				
				'ihover_video_link'				=> '',
				'ihover_video_auto'				=> 'true',
				'ihover_video_related'			=> 'false',				
				
				'overlay_trigger'				=> 'ts-trigger-hover',
				'overlay_handle_show'			=> 'true',
				'overlay_handle_color'			=> '#0094FF',
				
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
				
                'margin_top'					=> 20,
                'margin_bottom'					=> 20,
                'el_id' 						=> '',
                'el_class'              		=> '',
				'css'							=> '',
            ), $atts ));
            
            $output                             = '';
			$linkstring							= '';
        
            if (!empty($el_id)) {
                $ihover_image_id				= $el_id;
            } else {
                $ihover_image_id				= 'ts-vcsc-ihover-image-' . mt_rand(999999, 9999999);
            }
			
			// Image
			if (!empty($ihover_image)) {
				$ihover_image					= wp_get_attachment_image_src($ihover_image, 'full');
			}	
			// iFrame / Link
			if (($ihover_event == "link") || ($ihover_event == "iframe")) {
				$link 							= ($ihover_link == '||') ? '' : $ihover_link;
				$link 							= vc_build_link($link);
				$a_href							= $link['url'];
				$a_title 						= ''; //$link['title'];
				$a_target 						= $link['target'];
			} else {
				$a_href							= 'javascript:void(0);';
				$a_title						= '';
				$a_target						= '_blank';
			}
			if ($a_href == '') {
				$a_href							= 'javascript:void(0);';
			}		
			// YouTube Video
			if ($ihover_event == "youtube") {
				if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $ihover_video_link)) {
					$ihover_video_link		= $ihover_video_link;
				} else {
					$ihover_video_link		= 'https://www.youtube.com/watch?v=' . $ihover_video_link;
				}
			}
			// DailyMotion Video
			if ($ihover_event == "dailymotion") {
				if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $ihover_video_link)) {
					$ihover_video_link	= $ihover_video_link;
				} else {			
					$ihover_video_link	= $ihover_video_link;
				}
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
			
			// Handle Padding
			if ($overlay_handle_show == "true") {
				if (($ihover_style == "circle") && ($ihover_circle_effect == "effect1")) {
					$overlay_padding			= "padding-bottom: " . (10 + $ihover_circle_border) . "px;";
				} else {
					$overlay_padding			= "padding-bottom: 10px;";
				}
				$switch_handle_adjust  			= "";
			} else {
				$overlay_padding				= "";
				$switch_handle_adjust  			= "";
			}
			
			// Handle Icon
			if ($ihover_event != "none") {
				$switch_handle_icon				= 'handle_click';
			} else {
				$switch_handle_icon				= 'handle_hover';
			}
			
			if ($ihover_style == "circle") {
				$ihovereffect					= $ihover_circle_effect;
			} else {
				$ihovereffect					= $ihover_square_effect;
			}
			
			// Size Adjust Class
			if ($ihover_style == "circle") {
				$sizeadjust1 					= array ("effect1");
				$sizeadjust2 					= array ("effect2","effect3","effect4","effect5","effect6","effect7","effect8","effect9","effect10","effect11","effect12","effect13","effect14","effect15","effect16","effect17","effect18","effect19","effect20");			
				$sizeadjust3					= array();
				$sizeadjust4					= array();
			} else {
				$sizeadjust1					= array();
				$sizeadjust2					= array();
				$sizeadjust3					= array("effect1","effect2","effect3","effect5","effect6","effect7","effect8","effect9","effect10","effect11","effect12","effect13","effect14","effect15");
				$sizeadjust4					= array("effect4");
			}
			if (($ihover_responsive == "true") && (in_array($ihovereffect, $sizeadjust1))) {
				$sizeadjustclass				= 'ts-ihover-image-sizeadjust1';
			} else if (($ihover_responsive == "true") && (in_array($ihovereffect, $sizeadjust2))) {
				$sizeadjustclass				= 'ts-ihover-image-sizeadjust2';
			} else if (($ihover_responsive == "true") && (in_array($ihovereffect, $sizeadjust3))) {
				$sizeadjustclass				= 'ts-ihover-image-sizeadjust3';
			} else if (($ihover_responsive == "true") && (in_array($ihovereffect, $sizeadjust4))) {
				$sizeadjustclass				= 'ts-ihover-image-sizeadjust4';
			} else {
				$sizeadjustclass				= '';
			}
			
			// Direction Version
			if ($ihover_style == "circle") {
				$ihoverdirection1				= array("effect1","effect5","effect17","effect19");
				$ihoverdirection2				= array("effect2","effect3","effect4","effect7","effect8","effect9","effect11","effect12","effect14","effect18");
				$ihoverdirection3				= array("effect6");
				$ihoverdirection4				= array("effect10","effect20");
				$ihoverdirection5				= array("effect13");
				$ihoverdirection6				= array("effect15","effect16");
			} else {
				$ihoverdirection1				= array("effect6");
				$ihoverdirection2				= array("effect1");
				$ihoverdirection3				= array("effect3");
				$ihoverdirection4				= array("effect5");
				$ihoverdirection5				= array("effect8");
				$ihoverdirection6				= array("effect9","effect10","effect11","effect12","effect13","effect14","effect15");
			}
			if ($ihover_style == "circle") {
				if (in_array($ihover_circle_effect, $ihoverdirection1)) {
					$ihover_direction			= '';
				} else if (in_array($ihover_circle_effect, $ihoverdirection2)) {
					$ihover_direction			= $ihover_circle_direction;
				} else if (in_array($ihover_circle_effect, $ihoverdirection3)) {
					$ihover_direction			= $ihover_circle_scale;
				} else if (in_array($ihover_circle_effect, $ihoverdirection4)) {
					$ihover_direction			= $ihover_circle_direction2;
				} else if (in_array($ihover_circle_effect, $ihoverdirection5)) {
					$ihover_direction			= $ihover_circle_direction3;
				} else if (in_array($ihover_circle_effect, $ihoverdirection6)) {
					$ihover_direction			= $ihover_circle_direction4;
				} else {
					$ihover_direction			= '';
				}
			} else {
				if (in_array($ihover_circle_effect, $ihoverdirection1)) {
					$ihover_direction			= '';
				} else if (in_array($ihover_square_effect, $ihoverdirection1)) {
					$ihover_direction			= $ihover_square_direction;
				} else if (in_array($ihover_square_effect, $ihoverdirection2)) {
					$ihover_direction			= $ihover_square_direction2;
				} else if (in_array($ihover_square_effect, $ihoverdirection3)) {
					$ihover_direction			= $ihover_square_direction3;
				} else if (in_array($ihover_square_effect, $ihoverdirection4)) {
					$ihover_direction			= $ihover_square_direction4;
				} else if (in_array($ihover_square_effect, $ihoverdirection5)) {
					$ihover_direction			= $ihover_square_scale;
				} else if (in_array($ihover_square_effect, $ihoverdirection6)) {
					$ihover_direction			= $ihover_square_direction5;
				} else {
					$ihover_direction			= '';
				}
			}
			
			if ($ihover_style == "circle") {
				$ihoverborder_width				= '';
				$ihoverborder_data				= '';
			} else {
				$ihoverborder_width				= 'border: ' . $ihover_square_border . 'px solid ' . $ihover_square_color . ';';
				$ihoverborder_data				= 'data-border="' . $ihover_square_border . '"';
			}
			
			// Output Version
			if ($ihover_style == "circle") {
				$ihoveroutput1					= array("effect1","effect5","effect18","effect20");
				$ihoveroutput2					= array("effect2","effect3","effect4","effect6","effect7","effect9","effect10","effect11","effect12","effect13","effect14","effect15","effect16","effect17","effect19");
				$ihoveroutput3					= array("effect8");
			} else {
				$ihoveroutput1					= array("effect9");
				$ihoveroutput2					= array("effect1","effect2","effect3","effect4","effect5","effect6","effect7","effect8","effect10","effect11","effect12","effect13","effect14","effect15");
				$ihoveroutput3					= array();
			}

			
			// Link Output
			if ($ihover_frontent == "false") {
				if (($ihover_event != "none") && ($ihover_event == "popup")) {
					// Modal Popup
					$linkstring .= '<a id="' . $ihover_image_id . '-trigger" href="#' . $ihover_image_id . '" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-modal" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" data-type="html" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
				} else if (($ihover_event != "none") && ($ihover_event == "iframe")) {
					// iFrame Popup
					$linkstring .= '<a id="' . $ihover_image_id . '-trigger" href="' . $a_href . '" target="' . $a_target . '" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-media" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" data-type="iframe" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
				} else if (($ihover_event != "none") && ($ihover_event == "image")) {
					// Image Popup
					$linkstring .= '<a id="' . $ihover_image_id . '-trigger" href="' . $ihover_image[0] . '" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-media" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
				} else if (($ihover_event != "none") && ($ihover_event == "youtube")) {
					// YouTube Popup
					$linkstring .= '<a id="' . $ihover_image_id . '-trigger" href="' . $ihover_video_link .'" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-media" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" data-related="' . $ihover_video_related .'" data-videoplay="' . $ihover_video_auto .'" data-type="youtube" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
				} else if (($ihover_event != "none") && ($ihover_event == "vimeo")) {
					// Vimeo Popup
					$linkstring .= '<a id="' . $ihover_image_id . '-trigger" href="' . $ihover_video_link . '" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-media" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" data-videoplay="' . $ihover_video_auto . '" data-type="vimeo" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
				} else if (($ihover_event != "none") && ($ihover_event == "dailymotion")) {
					// DailyMotion Popup
					$linkstring .= '<a id="' . $ihover_image_id . '-trigger" href="' . $ihover_video_link .'" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-media" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" data-videoplay="' . $ihover_video_auto . '" data-type="dailymotion" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
				} else if (($ihover_event != "none") && ($ihover_event == "html5")) {
					// HTML5 Video Popup
					$linkstring .= '<a id="' . $ihover_image_id . '-trigger" href="#' . $modal_id . '" class="ts-ihover-image-link ' . $ihover_image_id . '-parent nch-holder nch-lightbox-media" ' . $lightbox_dimensions . ' style="" data-title="' . $ihover_title . '" data-type="html" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
				} else if (($ihover_event != "none") && ($ihover_event == "link")) {
					// Link Event
					$linkstring .= '<a id="' . $ihover_image_id . '-trigger" class="ts-ihover-image-link" href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '">';
				} else {
					// No Hotspot Event
					$linkstring .= '<a id="' . $ihover_image_id . '-trigger" class="ts-ihover-image-link" href="' . $a_href . '" target="' . $a_target . '" title="' . $a_title . '">';
				}
			} else {
				$linkstring .= '<a id="' . $ihover_image_id . '-trigger" class="ts-ihover-image-link" href="javascript:void(0);" target="_blank" title="">';
			}
			
			$output .= '<div class="ts-ihover-image-container ts-image-hover-frame">';
				if ($overlay_handle_show == "true") {
					$output .= '<div style="' . $overlay_padding . '">';
				}
					// Create Output Version 1
					if (in_array($ihovereffect, $ihoveroutput1)) {
						$output .= '<div class="ts-ihover-image-main ts-ihover-image-item ' . $ihover_style . ' ' . $ihovereffect . ' ' . $sizeadjustclass . ' ' . ($ihover_colored == "true" ? "colored" : "") . ' ' . $ihover_direction . ' ' . ($ihover_truncate == "true" ? "ts-ihover-image-truncated" : "") . '" ' . $ihoverborder_data . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $ihoverborder_width . '">';
							$output .= $linkstring;
								if (($ihover_style == "circle") && ($ihovereffect == "effect1")) {
									$output .= '<div class="spinner" data-border="' . $ihover_circle_border . '" style="border: ' . $ihover_circle_border . 'px solid ' . $ihover_circle_color1 . '; border-right-color: ' . $ihover_circle_color2 . '; border-bottom-color: ' . $ihover_circle_color2 . ';"></div>';
									$position = 'top: ' . $ihover_circle_border . 'px; left: ' . $ihover_circle_border . 'px;';
								} else {
									$position = '';
								}
								$output .= '<div class="ts-ihover-image-picture" style="' . (($ihover_circle_effect == "effect5" || $ihover_circle_effect == "effect18" || $ihover_circle_effect == "effect20") ? "width: 100%; height: 100%;" : "") . ' ' . $position . '"><img src="' . $ihover_image[0] . '" alt="" style=""></div>';
								$output .= '<div class="ts-ihover-image-info" style="' . $position . '">';
									$output .= '<div class="ts-ihover-image-info-back">';
										$output .= '<div class="ts-ihover-image-title" data-title="' . $ihover_title . '">' . $ihover_title . '</div>';
										$output .= '<div class="ts-ihover-image-content" data-content="' . $ihover_content . '">' . $ihover_content . '</div>';
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</a>';
						$output .= '</div>';
					}
					// Create Output Version 2
					if (in_array($ihovereffect, $ihoveroutput2)) {
						$output .= '<div class="ts-ihover-image-main ts-ihover-image-item ' . $ihover_style . ' ' . $ihovereffect . ' ' . $sizeadjustclass . ' ' . ($ihover_colored == "true" ? "colored" : "") . ' ' . $ihover_direction . ' ' . ($ihover_truncate == "true" ? "ts-ihover-image-truncated" : "") . '" ' . $ihoverborder_data . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $ihoverborder_width . '">';
							$output .= $linkstring;
								$output .= '<div class="ts-ihover-image-picture" style="width: 100%; height: 100%;"><img src="' . $ihover_image[0] . '" alt="" style=""></div>';
								if (($ihover_style == "square") && ($ihovereffect == "effect4")) {
									$output .= '<div class="mask1"></div>';
									$output .= '<div class="mask2"></div>';
								}
								$output .= '<div class="ts-ihover-image-info">';
									$output .= '<div class="ts-ihover-image-title" data-title="' . $ihover_title . '">' . $ihover_title . '</div>';
									$output .= '<div class="ts-ihover-image-content" data-content="' . $ihover_content . '">' . $ihover_content . '</div>';
								$output .= '</div>';
							$output .= '</a>';
						$output .= '</div>';
					}
					// Create Output Version 3
					if (in_array($ihovereffect, $ihoveroutput3)) {
						$output .= '<div class="ts-ihover-image-main ts-ihover-image-item ' . $ihover_style . ' ' . $ihovereffect . ' ' . $sizeadjustclass . ' ' . ($ihover_colored == "true" ? "colored" : "") . ' ' . $ihover_direction . ' ' . ($ihover_truncate == "true" ? "ts-ihover-image-truncated" : "") . '" ' . $ihoverborder_data . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $ihoverborder_width . '">';
							$output .= $linkstring;
								$output .= '<div class="img-container" style="width: 100%; height: 100%;"><div class="ts-ihover-image-picture" style="width: 100%; height: 100%;"><img src="' . $ihover_image[0] . '" alt="" style=""></div></div>';
								$output .= '<div class="ts-ihover-image-info-container">';
									$output .= '<div class="ts-ihover-image-info">';
										$output .= '<div class="ts-ihover-image-title" data-title="' . $ihover_title . '">' . $ihover_title . '</div>';
										$output .= '<div class="ts-ihover-image-content" data-content="' . $ihover_content . '">' . $ihover_content . '</div>';
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</a>';
						$output .= '</div>';
					}
					// Overlay Handle
					if ($overlay_handle_show == "true") {
						$output .= '<div class="ts-image-hover-handle" style="' . $switch_handle_adjust . '"><span class="frame_' . $switch_handle_icon . '" style="background-color: ' . $overlay_handle_color . '"><i class="' . $switch_handle_icon . '"></i></span></div>';
					}
				if ($overlay_handle_show == "true") {
					$output .= '</div>';
				}
				// Create hidden DIV with Modal Popup iHover Content
				if (($ihover_frontent == "false") && ($ihover_event == "popup")) {
					$output .= '<div id="' . $ihover_image_id . '" class="ts-modal-content nch-hide-if-javascript ' . $el_class . '" style="display: none; padding: ' . $lightbox_custom_padding . 'px; ' . $lightbox_background . '">';
						$output .= '<div class="ts-modal-white-header"></div>';
						$output .= '<div class="ts-modal-white-frame" style="">';
							$output .= '<div class="ts-modal-white-inner">';
								if (($ihover_show_title == "true") && ($ihover_title != "")) {
									$output .= '<h2 style="border-bottom: 1px solid #eeeeee; padding-bottom: 10px; margin-bottom: 10px;">' . $ihover_title . '</h2>';
								}
								$output .= rawurldecode(base64_decode(strip_tags($ihover_text)));
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				}
			$output .= '</div>';
			
            echo $output;
			
			unset($sizeadjust1);
			unset($sizeadjust2);
			unset($sizeadjust3);
			unset($sizeadjust4);
			unset($ihoverdirection1);
			unset($ihoverdirection2);
			unset($ihoverdirection3);
			unset($ihoverdirection4);
			unset($ihoverdirection5);
			unset($ihoverdirection6);
			unset($ihoveroutput1);
			unset($ihoveroutput2);
			unset($ihoveroutput3);
            
            $myvariable = ob_get_clean();
            return $myvariable;
        }
    
        // Add Image IHover Elements
        function TS_VCSC_Image_IHover_Elements() {
            global $VISUAL_COMPOSER_EXTENSIONS;
            // Add Standalone Skillset
            if (function_exists('vc_map')) {
                vc_map( array(
                    "name"                              => __( "TS Image iHover", "ts_visual_composer_extend" ),
                    "base"                              => "TS_VCSC_Image_IHover",
                    "icon" 	                            => "icon-wpb-ts_vcsc_image_ihover",
                    "class"                             => "",
                    "category"                          => __( 'VC Extensions', "ts_visual_composer_extend" ),
                    "description"                       => __("Place an image with iHover effect", "ts_visual_composer_extend"),
                    //"admin_enqueue_js"                => array(''),
                    //"admin_enqueue_css"               => array(''),
                    "params"                            => array(
                        // Style Selection
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_1",
							"value"						=> "",
                            "seperator"					=> "iHover Selection",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
						array(
							"type"                  	=> "attach_image",
							"holder" 					=> "img",
							"heading"               	=> __( "Image", "ts_visual_composer_extend" ),
							"param_name"            	=> "ihover_image",
							"class"						=> "ts_vcsc_holder_image",
							"value"                 	=> "",
							"description"           	=> __( "Select the image you want to use with the iHover effect.", "ts_visual_composer_extend" )
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Style", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_style",
							"value"                 	=> array(
								__("Circle", "ts_visual_composer_extend")				=> "circle",
								__("Square", "ts_visual_composer_extend")				=> "square",
							),
							"description"		    	=> __( "Select the general style for the iHover effect.", "ts_visual_composer_extend" ),
							"admin_label"       		=> true,
							"dependency"		    	=> "",
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Type", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_circle_effect",
							"value"                 	=> array(
								__("Effect 1", "ts_visual_composer_extend")				=> "effect1",
								__("Effect 2", "ts_visual_composer_extend")				=> "effect2",
								__("Effect 3", "ts_visual_composer_extend")				=> "effect3",
								__("Effect 4", "ts_visual_composer_extend")				=> "effect4",
								__("Effect 5", "ts_visual_composer_extend")				=> "effect5",
								__("Effect 6", "ts_visual_composer_extend")				=> "effect6",
								__("Effect 7", "ts_visual_composer_extend")				=> "effect7",
								__("Effect 8", "ts_visual_composer_extend")				=> "effect8",
								__("Effect 9", "ts_visual_composer_extend")				=> "effect9",
								__("Effect 10", "ts_visual_composer_extend")			=> "effect10",
								__("Effect 11", "ts_visual_composer_extend")			=> "effect11",
								__("Effect 12", "ts_visual_composer_extend")			=> "effect12",
								__("Effect 13", "ts_visual_composer_extend")			=> "effect13",
								__("Effect 14", "ts_visual_composer_extend")			=> "effect14",
								__("Effect 15", "ts_visual_composer_extend")			=> "effect15",
								__("Effect 16", "ts_visual_composer_extend")			=> "effect16",
								__("Effect 17", "ts_visual_composer_extend")			=> "effect17",
								__("Effect 18", "ts_visual_composer_extend")			=> "effect18",
								__("Effect 19", "ts_visual_composer_extend")			=> "effect19",
								__("Effect 20", "ts_visual_composer_extend")			=> "effect20",
							),
							"description"		    	=> __( "Select the iHover effect you want to apply to the image.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_style", 'value' => 'circle' ),
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_circle_direction",
							"value"                 	=> array(
								__("Left To Right", "ts_visual_composer_extend")		=> "left_to_right",
								__("Right To Left", "ts_visual_composer_extend")		=> "right_to_left",
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_circle_effect", 'value' => array('effect2','effect3','effect4','effect7','effect8','effect9','effect11','effect12','effect14','effect18') ),
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Scaling", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_circle_scale",
							"value"                 	=> array(
								__("Scale Up", "ts_visual_composer_extend")				=> "scale_up",
								__("Scale Down", "ts_visual_composer_extend")			=> "scale_down",
								__("Scale Down + Up", "ts_visual_composer_extend")		=> "scale_down_up",
							),
							"description"		    	=> __( "Select which scale type the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_circle_effect", 'value' => 'effect6' ),
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_circle_direction2",
							"value"                 	=> array(
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_circle_effect", 'value' => array('effect10','effect20') ),
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_circle_direction3",
							"value"                 	=> array(
								__("From Left And Right", "ts_visual_composer_extend")	=> "from_left_and_right",
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_circle_effect", 'value' => array('effect13') ),
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_circle_direction4",
							"value"                 	=> array(
								__("Left To Right", "ts_visual_composer_extend")		=> "left_to_right",
								__("Right To Left", "ts_visual_composer_extend")		=> "right_to_left",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_circle_effect", 'value' => array('effect15','effect16') ),
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Type", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_effect",
							"value"                 	=> array(
								__("Effect 1", "ts_visual_composer_extend")				=> "effect1",
								__("Effect 2", "ts_visual_composer_extend")				=> "effect2",
								__("Effect 3", "ts_visual_composer_extend")				=> "effect3",
								__("Effect 4", "ts_visual_composer_extend")				=> "effect4",
								__("Effect 5", "ts_visual_composer_extend")				=> "effect5",
								__("Effect 6", "ts_visual_composer_extend")				=> "effect6",
								__("Effect 7", "ts_visual_composer_extend")				=> "effect7",
								__("Effect 8", "ts_visual_composer_extend")				=> "effect8",
								__("Effect 9", "ts_visual_composer_extend")				=> "effect9",
								__("Effect 10", "ts_visual_composer_extend")			=> "effect10",
								__("Effect 11", "ts_visual_composer_extend")			=> "effect11",
								__("Effect 12", "ts_visual_composer_extend")			=> "effect12",
								__("Effect 13", "ts_visual_composer_extend")			=> "effect13",
								__("Effect 14", "ts_visual_composer_extend")			=> "effect14",
								__("Effect 15", "ts_visual_composer_extend")			=> "effect15",
							),
							"description"		    	=> __( "Select the iHover effect you want to apply to the image.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_style", 'value' => 'square' ),
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_direction",
							"value"                 	=> array(
								__("From Left And Right", "ts_visual_composer_extend")	=> "from_left_and_right",
								__("From Top And Bottom", "ts_visual_composer_extend")	=> "from_top_and_bottom",
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_square_effect", 'value' => array('effect6') ),
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_direction2",
							"value"                 	=> array(
								__("Left And Right", "ts_visual_composer_extend")		=> "left_and_right",
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_square_effect", 'value' => array('effect1') ),
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_direction3",
							"value"                 	=> array(
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_square_effect", 'value' => array('effect3') ),
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_direction4",
							"value"                 	=> array(
								__("Left To Right", "ts_visual_composer_extend")		=> "left_to_right",
								__("Right To Left", "ts_visual_composer_extend")		=> "right_to_left",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_square_effect", 'value' => array('effect5') ),
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Direction", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_direction5",
							"value"                 	=> array(
								__("Left To Right", "ts_visual_composer_extend")		=> "left_to_right",
								__("Right To Left", "ts_visual_composer_extend")		=> "right_to_left",
								__("Top To Bottom", "ts_visual_composer_extend")		=> "top_to_bottom",
								__("Bottom To Top", "ts_visual_composer_extend")		=> "bottom_to_top",
							),
							"description"		    	=> __( "Select which direction the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_square_effect", 'value' => array('effect9','effect10','effect11','effect12','effect13','effect14','effect15') ),
						),
						array(
							"type"				    	=> "dropdown",
							"class"				    	=> "",
							"heading"			    	=> __( "Effect Scaling", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_square_scale",
							"value"                 	=> array(
								__("Scale Up", "ts_visual_composer_extend")				=> "scale_up",
								__("Scale Down", "ts_visual_composer_extend")			=> "scale_down",
							),
							"description"		    	=> __( "Select which scale type the iHover effect should be using.", "ts_visual_composer_extend" ),
							"dependency"		    	=> array( 'element' => "ihover_square_effect", 'value' => 'effect8' ),
						),						
						array(
							"type"                  	=> "switch",
							"heading"			    	=> __( "Use Colored Effect", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_colored",
							"value"                	 	=> "false",
							"on"				    	=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"				    	=> __( 'No', "ts_visual_composer_extend" ),
							"style"				    	=> "select",
							"design"			    	=> "toggle-light",
							"description"		    	=> __( "Switch the toggle if you want to apply a colored background to the effect.", "ts_visual_composer_extend" ),
							"admin_label"       		=> true,
							"dependency"		    	=> array( 'element' => "ihover_style", 'value' => array('circle','square') ),
						),
						// Border Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_2",
							"value"						=> "",
                            "seperator"					=> "Border Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"group" 			        => "Border",
                        ),
						array(
							"type"              		=> "messenger",
							"heading"           		=> __( "", "ts_visual_composer_extend" ),
							"param_name"        		=> "messenger",
							"color"						=> "#FF0000",
							"weight"					=> "normal",
							"size"						=> "14",
							"value"						=> "",
							"message"            		=> __( "The following border settings apply to all effects using the  'Square' style.", "ts_visual_composer_extend" ),
							"description"       		=> __( "", "ts_visual_composer_extend" ),
							"group" 			        => "Border",
						),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Border Thickness", "ts_visual_composer_extend" ),
                            "param_name"                => "ihover_square_border",
                            "value"                     => "8",
                            "min"                       => "0",
                            "max"                       => "50",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Select the width of the border around the iHover image.", "ts_visual_composer_extend" ),
                            "dependency"		    	=> "",
							"group" 			        => "Border",
                        ),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Border Color", "ts_visual_composer_extend" ),
							"param_name"        		=> "ihover_square_color",
							"value"             		=> "#ffffff",
							"description"       		=> __( "Define the color for border around the iHover image.", "ts_visual_composer_extend" ),
                            "dependency"		    	=> "",
							"group" 			        => "Border",
						),
						array(
							"type"              		=> "messenger",
							"heading"           		=> __( "", "ts_visual_composer_extend" ),
							"param_name"        		=> "messenger",
							"color"						=> "#FF0000",
							"weight"					=> "normal",
							"size"						=> "14",
							"value"						=> "",
							"message"            		=> __( "The following border settings apply only to 'Circle' style with 'Effect 1'.", "ts_visual_composer_extend" ),
							"description"       		=> __( "", "ts_visual_composer_extend" ),
							"group" 			        => "Border",
						),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Border Thickness", "ts_visual_composer_extend" ),
                            "param_name"                => "ihover_circle_border",
                            "value"                     => "10",
                            "min"                       => "0",
                            "max"                       => "50",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Select the width of the border around the iHover image.", "ts_visual_composer_extend" ),
                            "dependency"		    	=> "",
							"group" 			        => "Border",
                        ),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Border Color 1", "ts_visual_composer_extend" ),
							"param_name"        		=> "ihover_circle_color1",
							"value"             		=> "#ecab18",
							"description"       		=> __( "Define the color for border around the iHover image.", "ts_visual_composer_extend" ),
							"dependency"		    	=> "",
							"group" 			        => "Border",
						),
						array(
							"type"              		=> "colorpicker",
							"heading"           		=> __( "Border Color 2", "ts_visual_composer_extend" ),
							"param_name"        		=> "ihover_circle_color2",
							"value"             		=> "#1ad280",
							"description"       		=> __( "Define the color for border around the iHover image.", "ts_visual_composer_extend" ),
							"dependency"		    	=> "",
							"group" 			        => "Border",
						),
						// Content Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_3",
							"value"						=> "",
                            "seperator"					=> "iHover Content",
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"group" 			        => "Content",
                        ),
						array(
							"type"             	 		=> "switch",
							"heading"               	=> __( "Show Overlay Handle", "ts_visual_composer_extend" ),
							"param_name"            	=> "overlay_handle_show",
							"value"                 	=> "true",
							"on"						=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"						=> __( 'No', "ts_visual_composer_extend" ),
							"style"						=> "select",
							"design"					=> "toggle-light",
							"description"       		=> __( "Use the toggle to show or hide a handle button below the image.", "ts_visual_composer_extend" ),
							"dependency"        		=> "",
							"group" 			        => "Content",
						),
						array(
							"type"                  	=> "colorpicker",
							"heading"              	 	=> __( "Handle Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "overlay_handle_color",
							"value"                 	=> "#0094FF",
							"description"           	=> __( "Define the color for the overlay handle button.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "overlay_handle_show", 'value' => 'true' ),
							"group" 			        => "Content",
						),
						array(
							"type"						=> "textfield",
							"heading"					=> __( "Title", "ts_visual_composer_extend" ),
							"param_name"				=> "ihover_title",
							"value"						=> "Heading Here",
							"admin_label"       		=> true,
							"description"				=> __( "Enter a title to be used for the iHover effect.", "ts_visual_composer_extend" ),
							"group" 			        => "Content",
						),
						array(
							"type"                  	=> "textarea",
							"class"                 	=> "",
							"heading"               	=> __( "Message", "ts_visual_composer_extend" ),
							"param_name"            	=> "ihover_content",
							"value"                 	=> "Description goes here",
							"description"	        	=> __( "Enter a short message to be used for the iHover effect; HTML code can NOT be used.", "ts_visual_composer_extend" ),
							"dependency"        		=> "",
							"group" 					=> "Content",
						),
						// iHover Event
						array(
							"type"				    	=> "seperator",
							"heading"			    	=> __( "", "ts_visual_composer_extend" ),
							"param_name"		    	=> "seperator_4",
							"value"						=> "",
							"seperator"					=> "iHover Event",
							"description"		    	=> __( "", "ts_visual_composer_extend" ),
							"dependency"            	=> "",
							"group" 					=> "Event",
						),						
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "iHover Event", "ts_visual_composer_extend" ),
							"param_name"            	=> "ihover_event",
							"width"                 	=> 150,
							"value" 					=> array(
								__( "None", "ts_visual_composer_extend" )									=> "none",
								__( "Open Image in Lightbox", "ts_visual_composer_extend" )					=> "image",
								__( "Open Popup in Lightbox", "ts_visual_composer_extend" )					=> "popup",
								__( "Open YouTube Video in Lightbox", "ts_visual_composer_extend" )			=> "youtube",
								__( "Open Vimeo Video in Lightbox", "ts_visual_composer_extend" )			=> "vimeo",
								__( "Open DailyMotion Video in Lightbox", "ts_visual_composer_extend" )		=> "dailymotion",
								__( "Open Page in iFrame", "ts_visual_composer_extend" )					=> "iframe",
								__( "Simple Link to Page", "ts_visual_composer_extend" )					=> "link",
							),
							"description"           	=> __( "Select if the iHover image should trigger any other action.", "ts_visual_composer_extend" ),
							"admin_label"       		=> true,
							"dependency"            	=> "",
							"group" 					=> "Event",
						),
						// Modal Popup
						array(
							"type"                  	=> "switch",
							"heading"			    	=> __( "Show iHover Title", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_show_title",
							"value"                 	=> "true",
							"on"				    	=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"				    	=> __( 'No', "ts_visual_composer_extend" ),
							"style"				    	=> "select",
							"design"			    	=> "toggle-light",
							"description"		    	=> __( "Switch the toggle if you want to show the title in the modal popup.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => 'popup' ),
							"group" 					=> "Event",
						),
						array(
							"type"              		=> "textarea_raw_html",
							"heading"           		=> __( "iHover Description", "ts_visual_composer_extend" ),
							"param_name"        		=> "ihover_text",
							"value"             		=> base64_encode(""),
							"description"       		=> __( "Enter the more detailed description for the modal popup; HTML code can be used.", "ts_visual_composer_extend" ),
							"dependency"        		=> array( 'element' => "ihover_event", 'value' => 'popup' ),
							"group" 					=> "Event",
						),
						// YouTube / DailyMotion / Vimeo
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Video URL", "ts_visual_composer_extend" ),
							"param_name"            	=> "ihover_video_link",
							"value"                 	=> "",
							"description"           	=> __( "Enter the URL for the video to be shown in a lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => array('youtube','dailymotion','vimeo') ),
							"group" 					=> "Event",
						),
						array(
							"type"              		=> "switch",
							"heading"			    	=> __( "Show Related Videos", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_video_related",
							"value"             		=> "false",
							"on"						=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"						=> __( 'No', "ts_visual_composer_extend" ),
							"style"						=> "select",
							"design"					=> "toggle-light",
							"description"		    	=> __( "Switch the toggle if you want to show related videos once the video has finished playing.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => 'youtube' ),
							"group" 					=> "Event",
						),
						array(
							"type"              		=> "switch",
							"heading"			    	=> __( "Autoplay Video", "ts_visual_composer_extend" ),
							"param_name"		    	=> "ihover_video_auto",
							"value"             		=> "true",
							"on"						=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"						=> __( 'No', "ts_visual_composer_extend" ),
							"style"						=> "select",
							"design"					=> "toggle-light",
							"description"		    	=> __( "Switch the toggle if you want to auto-play the video once opened in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => array('youtube','dailymotion','vimeo') ),
							"group" 					=> "Event",
						),
						// Link / iFrame
						array(
							"type" 						=> "vc_link",
							"heading" 					=> __("Link + Title", "ts_visual_composer_extend"),
							"param_name" 				=> "ihover_link",
							"description" 				=> __("Provide a link to another site/page to be used for the iHover event.", "ts_visual_composer_extend"),
							"dependency"            	=> array( 'element' => "ihover_event", 'value' => array('iframe','link') ),
							"group" 					=> "Event",
						),						
                        // Other Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_5",
							"value"						=> "",
                            "seperator"					=> "Other Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "group" 			        => "Other Settings",
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Margin: Top", "ts_visual_composer_extend" ),
                            "param_name"                => "margin_top",
                            "value"                     => "20",
                            "min"                       => "-50",
                            "max"                       => "500",
                            "step"                      => "1",
                            "unit"                      => 'px',
                            "description"               => __( "Select the top margin for the element.", "ts_visual_composer_extend" ),
                            "group" 			        => "Other Settings",
                        ),
                        array(
                            "type"                      => "nouislider",
                            "heading"                   => __( "Margin: Bottom", "ts_visual_composer_extend" ),
                            "param_name"                => "margin_bottom",
                            "value"                     => "20",
                            "min"                       => "-50",
                            "max"                       => "500",
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
                            "param_name"                => "el_file",
                            "value"                     => "",
                            "file_type"                 => "js",
                            "file_path"                 => "js/ts-visual-composer-extend-element.min.js",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                    ))
                );
            }
        }
	}
}
if (class_exists('WPBakeryShortCode')) {
	class WPBakeryShortCode_TS_VCSC_Image_IHover extends WPBakeryShortCode {};
}
// Initialize "TS Image IHover" Class
if (class_exists('TS_Image_IHover')) {
	$TS_Image_IHover = new TS_Image_IHover;
}