<?php
if (!class_exists('TS_Logos')){
	class TS_Logos {
		function __construct() {
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
                    add_action('init',                              	array($this, 'TS_VCSC_Add_Logo_Elements'), 9999999);
                } else {
                    add_action('admin_init',		                	array($this, 'TS_VCSC_Add_Logo_Elements'), 9999999);
                }
            } else {
                add_action('admin_init',								array($this, 'TS_VCSC_Add_Logo_Elements'), 9999999);
            }
            add_shortcode('TS_VCSC_Logo_Standalone', 					array($this, 'TS_VCSC_Logo_Standalone'));
            add_shortcode('TS_VCSC_Logo_Single',                		array($this, 'TS_VCSC_Logo_Single'));
            add_shortcode('TS_VCSC_Logo_Slider_Custom',         		array($this, 'TS_VCSC_Logo_Slider_Custom'));
            add_shortcode('TS_VCSC_Logo_Slider_Category',        		array($this, 'TS_VCSC_Logo_Slider_Category'));
		}
        
        // Standalone Logo
        function TS_VCSC_Logo_Standalone ($atts, $content = null) {
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();
    
            wp_enqueue_script('ts-extend-hammer');
            wp_enqueue_script('ts-extend-nacho');
            wp_enqueue_style('ts-extend-nacho');
			wp_enqueue_style('ts-font-ecommerce');
            wp_enqueue_style('ts-font-teammates');
			if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
                wp_enqueue_style('ts-extend-simptip');
                wp_enqueue_style('ts-extend-animations');
				wp_enqueue_script('ts-extend-badonkatrunc');
                wp_enqueue_style('ts-visual-composer-extend-front');
                wp_enqueue_script('ts-visual-composer-extend-front');
            }
        
            extract( shortcode_atts( array(
                'logo'								=> '',
				'hover_type'           				=> 'ts-imagehover-style1',
				'hover_active'						=> 'false',
				'show_title'						=> 'false',
				'show_button'						=> 'true',

				'overlay_trigger'					=> 'ts-trigger-hover',
				'overlay_handle_show'				=> 'true',
				'overlay_handle_color'				=> '#0094FF',
				
                'icon_style' 			        	=> 'square',
                'icon_background'		        	=> '#f5f5f5',
				
				'lightbox_group'					=> 'true',
				'lightbox_group_name'				=> '',
				'lightbox_effect'					=> 'random',
				'lightbox_backlight'				=> 'hideit',
				'lightbox_backlight_color'			=> '#ffffff',
				
				'image_fixed'						=> 'false',
				'image_width'						=> 300,
				'image_height'						=> 200,
				'image_position'					=> 'ts-imagefloat-center',
				
				'title_truncate'					=> 'true',

				'button_url'						=> '',
				'button_target'						=> '_parent',
				
				'margin_top'						=> 0,
				'margin_bottom'						=> 0,
				'el_id' 							=> '',
				'el_class'                  		=> '',
				'css'								=> '',
            ), $atts ));
			
            $output									= '';

            if ((empty($icon_background)) || ($icon_style == 'simple')) {
                $icon_background_style				= '';
            } else {
                $icon_background_style				= 'background: ' . $icon_background . ';';
            }
            
            $icon_top_adjust						= 'top: 5px;';
	
			if ($lightbox_backlight == "auto") {
				$nacho_color						= '';
			} else if ($lightbox_backlight == "custom") {
				$nacho_color						= 'data-color="' . $lightbox_backlight_color . '"';
			} else if ($lightbox_backlight == "hideit") {
				$nacho_color						= 'data-color="#000000"';
			}
			
			// Handle Padding
			if ($overlay_handle_show == "true") {
				$overlay_padding					= "padding-bottom: 25px;";
				$switch_handle_adjust  				= "";
			} else {
				$overlay_padding					= "";
				$switch_handle_adjust  				= "";
			}
			
			// Handle Icon
			if ($overlay_trigger == "ts-trigger-click") {
				$switch_handle_icon					= 'handle_click';
			} else if ($overlay_trigger == "ts-trigger-hover") {
				$switch_handle_icon					= 'handle_hover';
			}
			
            // Check for Logo and End Shortcode if Empty
            if (empty($logo)) {
                $output .= '<div style="text-align: justify; font-weight: bold; font-size: 14px; color: red;">Please select a logo in the element settings!</div>';
                echo $output;
                $myvariable = ob_get_clean();
                return $myvariable;
            }
	
            if (!empty($el_id)) {
                $logo_block_id						= $el_id;
            } else {
                $logo_block_id						= 'ts-vcsc-logo-' . mt_rand(999999, 9999999);
            }
			

            // Retrieve Logo Post Main Content
            $logo_array								= array();
            $args = array(
                'no_found_rows' 					=> 1,
                'ignore_sticky_posts' 				=> 1,
                'posts_per_page' 					=> -1,
                'post_type' 						=> 'ts_logos',
                'post_status' 						=> 'publish',
                'orderby' 							=> 'title',
                'order' 							=> 'ASC',
            );
            $logo_query = new WP_Query($args);
            if ($logo_query->have_posts()) {
                foreach($logo_query->posts as $p) {
                    if ($p->ID == $logo) {
                        $logo_data = array(
                            'author'				=> $p->post_author,
                            'name'					=> $p->post_name,
                            'title'					=> $p->post_title,
                            'id'					=> $p->ID,
                            'content'				=> $p->post_content,
                        );
                        $logo_array[] = $logo_data;
                    }
                }
            }
            wp_reset_postdata();
            
            // Build Logo Post Main Content
            foreach ($logo_array as $index => $array) {
                $Logo_Title 						= $logo_array[$index]['title'];
                $Logo_ID 							= $logo_array[$index]['id'];
                $Logo_Content 						= $logo_array[$index]['content'];
                $Logo_Image							= wp_get_attachment_image_src(get_post_thumbnail_id($Logo_ID), 'full');
                if ($Logo_Image == false) {
                    $Logo_Image         			= TS_VCSC_GetResourceURL('images/defaults/default_person.jpg');
                } else {
                    $Logo_Image          			= $Logo_Image[0];
                }
    
                // Retrieve Logo Post Meta Content
                $custom_fields 						= get_post_custom($Logo_ID);
                $custom_fields_array				= array();
                foreach ($custom_fields as $field_key => $field_values) {
                    if (!isset($field_values[0])) continue;
                    if (in_array($field_key, array("_edit_lock", "_edit_last"))) continue;
                    if (strpos($field_key, 'ts_vcsc_logo_') !== false) {
                        $field_key_split 			= explode("_", $field_key);
                        $field_key_length 			= count($field_key_split) - 1;
                        $custom_data = array(
                            'group'					=> $field_key_split[$field_key_length - 1],
                            'name'					=> 'Logo_' . ucfirst($field_key_split[$field_key_length]),
                            'value'					=> $field_values[0],
                        );
                        $custom_fields_array[] 		= $custom_data;
                    }
                }
                foreach ($custom_fields_array as $index => $array) {
                    ${$custom_fields_array[$index]['name']} = $custom_fields_array[$index]['value'];
                }
				
				if (isset($Logo_Name)) {
					$Logo_Title 					= $Logo_Name;
				} else {
					$Logo_Title 					= $Logo_Title;
				}

                // Build Logo Links
				$hover_links							= '';
				$hover_links_count						= 0;
				$hover_links 		.= '<ul class="ts-logo-icons ' . $icon_style . ' clearFixMe" style="margin: 0;">';
					if (isset($Logo_Email)) {
						$hover_links_count++;
						$hover_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link email" href="mailto:' . $Logo_Email . '"><i class="ts-teamicon-email2 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Link)) {
						$hover_links_count++;
						$hover_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link link" href="' . TS_VCSC_makeValidURL($Logo_Link) . '"><i class="ts-teamicon-link ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if ($show_button == "true") {
						$hover_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a href="#' . $logo_block_id . '-modal" class="nch-lightbox-trigger nch-lightbox-modal" target="' . $button_target . '" data-title="" data-type="html" rel="' . ($lightbox_group == "true" ? "logogroup" : $lightbox_group_name) . '" data-effect="' . $lightbox_effect . '" data-share="false" ' . $nacho_color . '><i class="ts-teamicon-info3 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
				$hover_links 		.= '</ul>';
				
                $logo_links 		                = '';
                $logo_links_count	                = 0;
				$logo_links 		.= '<ul class="ts-logo-icons ' . $icon_style . ' clearFixMe" style="margin: 0;">';
					if (isset($Logo_Email)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link email" href="mailto:' . $Logo_Email . '"><i class="ts-teamicon-email2 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Link)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link link" href="' . TS_VCSC_makeValidURL($Logo_Link) . '"><i class="ts-teamicon-link ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Facebook)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link facebook" href="' . TS_VCSC_makeValidURL($Logo_Facebook) . '"><i class="ts-teamicon-facebook1 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Google)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link gplus" href="' . TS_VCSC_makeValidURL($Logo_Google) . '"><i class="ts-teamicon-googleplus1 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Twitter)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link twitter" href="' . TS_VCSC_makeValidURL($Logo_Twitter) . '"><i class="ts-teamicon-twitter1 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Linkedin)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link linkedin" href="' . TS_VCSC_makeValidURL($Logo_Linkedin) . '"><i class="ts-teamicon-linkedin ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Xing)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link xing" href="' . TS_VCSC_makeValidURL($Logo_Xing) . '"><i class="ts-teamicon-xing3 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Envato)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link envato" href="' . TS_VCSC_makeValidURL($Logo_Envato) . '"><i class="ts-teamicon-envato ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Rss)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link rss" href="' . TS_VCSC_makeValidURL($Logo_Rss) . '"><i class="ts-teamicon-rss1 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Forrst)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link forrst" href="' . TS_VCSC_makeValidURL($Logo_Forrst) . '"><i class="ts-teamicon-forrst1 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Flickr)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link flickr" href="' . TS_VCSC_makeValidURL($Logo_Flickr) . '"><i class="ts-teamicon-flickr3 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Instagram)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link instagram" href="' . TS_VCSC_makeValidURL($Logo_Instagram) . '"><i class="ts-teamicon-instagram ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Picasa)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link picasa" href="' . TS_VCSC_makeValidURL($Logo_Picasa) . '"><i class="ts-teamicon-picasa1 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Pinterest)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link pinterest" href="' . TS_VCSC_makeValidURL($Logo_Pinterest) . '"><i class="ts-teamicon-pinterest1 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Vimeo)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link vimeo" href="' . TS_VCSC_makeValidURL($Logo_Vimeo) . '"><i class="ts-teamicon-vimeo1 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
					if (isset($Logo_Youtube)) {
						$logo_links_count++;
						$logo_links .= '<li class="ts-logo-icon center" style="' . $icon_background_style . '"><a style="" target="_blank" class="ts-teammate-link youtube" href="' . TS_VCSC_makeValidURL($Logo_Youtube) . '"><i class="ts-teamicon-youtube1 ts-font-icon" style="' . $icon_top_adjust . '"></i></a></li>';
					}
                $logo_links 		.= '</ul>';
				
				$logo_margin 						= 'margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;';
				if ($show_title == "true") {
					$inner_position					= 'margin: 20px 10px;';
				} else {
					$inner_position					= 'margin: -17px auto 0 auto; padding: 0; top: 50%;';
				}
				$image_extension 					= pathinfo($Logo_Image, PATHINFO_EXTENSION);
				$alt_attribute						= basename($Logo_Image, "." . $image_extension);
				
				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-image-hover-frame ' . $image_position . ' ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Logo_Standalone', $atts);
				} else {
					$css_class 	= 'ts-image-hover-frame ' . $image_position . ' ' . $el_class;
				}
				
				$output .= '<div id="' . $logo_block_id . '-container" class="ts-image-hover-container" style="width: 100%; height: 100%; margin: 0; padding: 0;">';
				
					if ($image_fixed == "true") {
						$output .= '<div id="' . $logo_block_id . '" class="' . $css_class . '" style="width: ' . $image_width . 'px; ' . $logo_margin . '">';
							$output .= '<div id="' . $logo_block_id . '-counter" class="ts-fluid-wrapper " style="width: ' . $image_width . 'px; height: auto;">';
								if ($overlay_handle_show == "true") {
									$output .= '<div style="' . $overlay_padding . '">';
								}
									$output .= '<div id="' . $logo_block_id . '-mask" class="ts-imagehover ' . ((($title_truncate == "true") && ($show_title == "true")) ? "ts-logohover-truncate" : "") . ' ' . $hover_type . ' ' . $overlay_trigger . ' ' . ($show_title == "true" ? "" : "notitle") . ' ' . ((($hover_active == "true") && ($overlay_trigger == "ts-trigger-click")) == "true" ? "active" : "") . '" data-trigger="' . $overlay_trigger . '" data-closer="' . $logo_block_id . '-closer" style="width: ' . $image_width . 'px; height: ' . $image_height . 'px;">';
										if ($overlay_trigger == "ts-trigger-click") {
											$output .= '<div id="' . $logo_block_id . '-closer" class="ts-imagecloser" data-mask="' . $logo_block_id . '-mask"></div>';
										}
										$output .= '<img src="' . $Logo_Image . '" alt="' . $alt_attribute . '" style="width: ' . $image_width . 'px; height: ' . $image_height . 'px;"/>';
										$output .= '<div class="mask" style="width: ' . $image_width . 'px; height: ' . $image_height . 'px; display: ' . ((($hover_active == "false") && ($overlay_trigger == "ts-trigger-click")) ? "none;" : "block;") . '">';
											$output .= '<div class="logocontent" style="width: 100%; height: 100%;">';
												if ($show_title == "true") {
													$output .= '<h2 class="logotitle" style="padding:10px 20px;">' . $Logo_Title . '</h2>';
												}
												$output .= '<div class="maskcontent" style="' . $inner_position . '">' . $hover_links . '</div>';
											$output .= '</div>';
										$output .= '</div>';
									$output .= '</div>';
									if ($overlay_handle_show == "true") {
										$output .= '<div class="ts-image-hover-handle" style="' . $switch_handle_adjust . '"><span class="frame_' . $switch_handle_icon . '" style="background-color: ' . $overlay_handle_color . '"><i class="' . $switch_handle_icon . '"></i></span></div>';
									}
								if ($overlay_handle_show == "true") {
									$output .= '</div>';
								}
							$output .= '</div>';
						$output .= '</div>';
					} else {
						$output .= '<div id="' . $logo_block_id . '" class="' . $css_class . '" style="width: 100%; ' . $logo_margin . '">';
							$output .= '<div id="' . $logo_block_id . '-counter" class="ts-fluid-wrapper " style="width: 100%; height: auto;">';
								if ($overlay_handle_show == "true") {
									$output .= '<div style="' . $overlay_padding . '">';
								}
									$output .= '<div id="' . $logo_block_id . '-mask" class="ts-imagehover ' . ((($title_truncate == "true") && ($show_title == "true")) ? "ts-logohover-truncate" : "") . ' ' . $hover_type . ' ' . $overlay_trigger . ' ' . ($show_title == "true" ? "" : "notitle") . ' ' . ((($hover_active == "true") && ($overlay_trigger == "ts-trigger-click")) ? "active" : "") . '" data-trigger="' . $overlay_trigger . '" data-closer="' . $logo_block_id . '-closer" style="width: 100%; height: auto;">';
										if ($overlay_trigger == "ts-trigger-click") {
											$output .= '<div id="' . $logo_block_id . '-closer" class="ts-imagecloser" data-mask="' . $logo_block_id . '-mask"></div>';
										}
										$output .= '<img src="' . $Logo_Image . '" alt="' . $alt_attribute . '" style="width: 100%; height: auto;"/>';
										$output .= '<div class="mask" style="width: 100%; height: 100%; display: ' . ((($hover_active == "false") && ($overlay_trigger == "ts-trigger-click")) ? "none;" : "block;") . '">';
											$output .= '<div class="logocontent" style="width: 100%; height: 100%;">';
												if ($show_title == "true") {
													$output .= '<h2 class="logotitle" style="padding:10px 20px;">' . $Logo_Title . '</h2>';
												}
												$output .= '<div class="maskcontent" style="' . $inner_position . '">' . $hover_links . '</div>';
											$output .= '</div>';
										$output .= '</div>';
									$output .= '</div>';
									if ($overlay_handle_show == "true") {
										$output .= '<div class="ts-image-hover-handle" style="' . $switch_handle_adjust . '"><span class="frame_' . $switch_handle_icon . '" style="background-color: ' . $overlay_handle_color . '"><i class="' . $switch_handle_icon . '"></i></span></div>';
									}
								if ($overlay_handle_show == "true") {
									$output .= '</div>';
								}
							$output .= '</div>';
						$output .= '</div>';
					}
					
					// Create hidden DIV with Modal Content
					$output .= '<div id="' . $logo_block_id . '-modal" class="ts-modal-content nch-hide-if-javascript" style="padding: 15px;">';
						$output .= '<div class="ts-modal-white-header"></div>';
						$output .= '<div class="ts-modal-white-frame">';
							$output .= '<div class="ts-modal-white-inner">';
								$output .= '<h2 style="border-bottom: 1px solid #eeeeee; padding-bottom: 10px;">' . $Logo_Title . '</h2>';
								$output .= '<div style="width: 100%; height: 100%; display: block; margin-top: 15px;">';
									$output .= '<img src="' . $Logo_Image . '" alt="' . $alt_attribute . '" style="width: 25%; height: auto; float: left;"/>';
										$output .= '<div style="width: 70%; margin: 0 0 20px 30%;">';
										if (function_exists('wpb_js_remove_wpautop')){
											$output .= '<div style="width: 100%; display: block; margin: 0 0 20px 0;">' . wpb_js_remove_wpautop(do_shortcode($Logo_Content), true) . '</div>';
										} else {
											$output .= '<div style="width: 100%; display: block; margin: 0 0 20px 0;">' . do_shortcode($Logo_Content) . '</div>';
										}
										$output .= $logo_links;
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				
				$output .= '</div>';
            
                break;
            }
            
            echo $output;
            
            $myvariable = ob_get_clean();
            return $myvariable;
        }
    
        // Single Logo for Custom Slider
        function TS_VCSC_Logo_Single($atts, $content = null) {
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();
        
			if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
				wp_enqueue_script('ts-extend-badonkatrunc');
                wp_enqueue_style('ts-visual-composer-extend-front');
                wp_enqueue_script('ts-visual-composer-extend-front');
            }
        
            extract( shortcode_atts( array(
                'logo'					=> '',
                'style'							=> 'style1',
            ), $atts ));
            
            $output								= '';
			
			// Check for Front End Editor
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
					$frontend_edit				= 'true';
                } else {
					$frontend_edit				= 'false';
                }
            } else {
				$frontend_edit					= 'false';
            }

            // Check for Logo and End Shortcode if Empty
            if (empty($logo)) {
                $output .= '<div style="text-align: justify; font-weight: bold; font-size: 14px; color: red;">Please select a logo in the element settings!</div>';
                echo $output;
                $myvariable = ob_get_clean();
                return $myvariable;
            }
			
            $logo_item_id				= 'ts-vcsc-logo-item-' . mt_rand(999999, 9999999);
            
            // Retrieve Logo Post Main Content
            $logo_array					= array();
            $category_fields 	                = array();
            $args = array(
                'no_found_rows' 				=> 1,
                'ignore_sticky_posts' 			=> 1,
                'posts_per_page' 				=> -1,
                'post_type' 					=> 'ts_logos',
                'post_status' 					=> 'publish',
                'orderby' 						=> 'title',
                'order' 						=> 'ASC',
            );
            $logo_query = new WP_Query($args);
            if ($logo_query->have_posts()) {
                foreach($logo_query->posts as $p) {
                    if ($p->ID == $logo) {
                        $logo_data = array(
                            'author'			=> $p->post_author,
                            'name'				=> $p->post_name,
                            'title'				=> $p->post_title,
                            'id'				=> $p->ID,
                            'content'			=> $p->post_content,
                        );
                        $logo_array[] = $logo_data;
                    }
                }
            }
            wp_reset_postdata();
			
            // Build Logo Post Main Content
            foreach ($logo_array as $index => $array) {
                $Logo_Author				= $logo_array[$index]['author'];
                $Logo_Name 				= $logo_array[$index]['name'];
                $Logo_Title 				= $logo_array[$index]['title'];
                $Logo_ID 				= $logo_array[$index]['id'];
                $Logo_Content 			= $logo_array[$index]['content'];
                $Logo_Image				= wp_get_attachment_image_src(get_post_thumbnail_id($Logo_ID), 'full');
                if ($Logo_Image == false) {
                    $Logo_Image          = TS_VCSC_GetResourceURL('images/defaults/default_person.jpg');
                } else {
                    $Logo_Image          = $Logo_Image[0];
                }
            }
			
            // Retrieve Logo Post Meta Content
            $custom_fields 						= get_post_custom($Logo_ID);
            $custom_fields_array				= array();
            foreach ($custom_fields as $field_key => $field_values) {
                if (!isset($field_values[0])) continue;
                if (in_array($field_key, array("_edit_lock", "_edit_last"))) continue;
                if (strpos($field_key, 'ts_vcsc_logo_') !== false) {
                    $field_key_split 			= explode("_", $field_key);
                    $field_key_length 			= count($field_key_split) - 1;
                    $custom_data = array(
                        'group'					=> $field_key_split[$field_key_length - 1],
                        'name'					=> 'Logo_' . ucfirst($field_key_split[$field_key_length]),
                        'value'					=> $field_values[0],
                    );
                    $custom_fields_array[] = $custom_data;
                }
            }
            foreach ($custom_fields_array as $index => $array) {
                ${$custom_fields_array[$index]['name']} = $custom_fields_array[$index]['value'];
            }
			if (isset($Logo_Position)) {
				$Logo_Position 			= $Logo_Position;
			} else {
				$Logo_Position 			= '';
			}
			if (isset($Logo_Author)) {
				$Logo_Author 			= $Logo_Author;
			} else {
				$Logo_Author 			= '';
			}
            
			// Create Output
			if ($style == "style1") {
				$output .= '<div id="' . $logo_item_id . '" class="ts-logo-main style1 clearFixMe" style="width: 98%; margin: 0 auto;">';
					$output .= '<div class="ts-logo-content">';
						$output .= '<span class="ts-logo-arrow"></span>';
						if (function_exists('wpb_js_remove_wpautop')){
							$output .= '' . wpb_js_remove_wpautop(do_shortcode($Logo_Content), true) . '';
						} else {
							$output .= '' . do_shortcode($Logo_Content) . '';
						}
					$output .= '</div>';
					$output .= '<div class="ts-logo-user">';
						$output .= '<div class="ts-logo-user-thumb"><img src="' . $Logo_Image . '" alt=""></div>';
						$output .= '<div class="ts-logo-user-name">' . $Logo_Author . '</div>';
						$output .= '<div class="ts-logo-user-meta">' . $Logo_Position . '</div>';
					$output .= '</div>';
				$output .= '</div>';
			} else if ($style == "style2") {
				$output .= '<div id="' . $logo_item_id . '" class="ts-logo-main style2 clearFixMe" style="width: 98%; margin: 0 auto;">';
					$output .= '<div class="blockquote">';
						$output .= '<span class="leftq quotes"></span>';
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= '' . wpb_js_remove_wpautop(do_shortcode($Logo_Content), true) . '';
							} else {
								$output .= '' . do_shortcode($Logo_Content) . '';
							}
						$output .= '<span class="rightq quotes"></span>';
					$output .= '</div>';
					$output .= '<div class="information">';
						$output .= '<img src="' . $Logo_Image . '" width="170" height="auto" />';
						$output .= '<div class="author">' . $Logo_Author . '</div>';
						$output .= '<div class="metadata">' . $Logo_Position . '</div>';
					$output .= '</div>';
				$output .= '</div>';
			} else if ($style == "style3") {
				$output .= '<div id="' . $logo_item_id . '" class="ts-logo-main style3 clearFixMe" style="width: 98%; margin: 0 auto;">';
					$output .= '<div class="photo">';
						$output .= '<img src="' . $Logo_Image . '" alt="" />';
					$output .= '</div>';
					$output .= '<div class="content">';
						$output .= '<span class="laquo"></span>';
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= '' . wpb_js_remove_wpautop(do_shortcode($Logo_Content), true) . '';
							} else {
								$output .= '' . do_shortcode($Logo_Content) . '';
							}
						$output .= '<span class="raquo"></span>';
					$output .= '</div>';
					$output .= '<div class="sign">';
						$output .= '<span class="author">' . $Logo_Author . '</span>';
						$output .= '<span class="metadata">' . $Logo_Position . '</span>';
					$output .= '</div>';
				$output .= '</div>';
			}
                

            echo $output;
            
            $myvariable = ob_get_clean();
            return $myvariable;
        }
            
        // Custom Logo Slider
        function TS_VCSC_Logo_Slider_Custom($atts, $content = null){
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();
            
            wp_enqueue_script('ts-extend-hammer');
            wp_enqueue_script('ts-extend-nacho');
            wp_enqueue_style('ts-extend-nacho');
            wp_enqueue_style('ts-extend-owlcarousel2');
            wp_enqueue_script('ts-extend-owlcarouse2');
			wp_enqueue_style('ts-font-ecommerce');
            wp_enqueue_style('ts-font-teammates');
			if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
                wp_enqueue_style('ts-extend-simptip');
                wp_enqueue_style('ts-extend-animations');
				wp_enqueue_script('ts-extend-badonkatrunc');
                wp_enqueue_style('ts-visual-composer-extend-front');
                wp_enqueue_script('ts-visual-composer-extend-front');
            }
            
            extract( shortcode_atts( array(
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
                'transitions'                   => 'backSlide',
                'margin_top'                    => 0,
                'margin_bottom'                 => 0,
                'el_id'                         => '',
                'el_class'                      => '',
            ), $atts ));
            
            $logo_random                 = mt_rand(999999, 9999999);
            
            if (!empty($el_id)) {
                $logo_slider_id			= $el_id;
            } else {
                $logo_slider_id			= 'ts-vcsc-logo-slider-' . $logo_random;
            }
            
            $output = '';
            
            $output .= '<div id="' . $logo_slider_id . '" class="ts-logos-slider owl-carousel" data-id="' . $logo_random . '" data-navigation="' . $show_navigation . '" data-dots="' . $show_dots . '" data-transitions="' . $transitions . '" data-rtl="' . $page_rtl . '" data-height="' . $auto_height . '" data-play="' . $auto_play . '" data-bar="' . $show_bar . '" data-color="' . $bar_color . '" data-speed="' . $show_speed . '" data-hover="' . $stop_hover . '" data-numbers="' . $page_numbers . '">';
                $output .= do_shortcode($content);
            $output .= '</div>';
            
            echo $output;
            
            $myvariable = ob_get_clean();
            return $myvariable;
        }
        // Category Logo Slider
        function TS_VCSC_Logo_Slider_Category($atts, $content = null){
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();
            
            wp_enqueue_script('ts-extend-hammer');
            wp_enqueue_script('ts-extend-nacho');
            wp_enqueue_style('ts-extend-nacho');
            wp_enqueue_style('ts-extend-owlcarousel2');
            wp_enqueue_script('ts-extend-owlcarouse2');
			wp_enqueue_style('ts-font-ecommerce');
            wp_enqueue_style('ts-font-teammates');
			if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
                wp_enqueue_style('ts-extend-simptip');
                wp_enqueue_style('ts-extend-animations');
				wp_enqueue_script('ts-extend-badonkatrunc');
                wp_enqueue_style('ts-visual-composer-extend-front');
                wp_enqueue_script('ts-visual-composer-extend-front');
            }
            
            extract( shortcode_atts( array(
                'logocat'                		=> '',
                'style'							=> 'style1',
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
                'transitions'                   => 'backSlide',
                'margin_top'                    => 0,
                'margin_bottom'                 => 0,
                'el_id'                         => '',
                'el_class'                      => '',
            ), $atts ));
            
            $logo_random                 = mt_rand(999999, 9999999);
            
            if (!empty($el_id)) {
                $logo_slider_id			= $el_id;
            } else {
                $logo_slider_id			= 'ts-vcsc-logo-slider-' . $logo_random;
            }
            
            if (!is_array($logocat)) {
                $logocat 				= array_map('trim', explode(',', $logocat));
            }
            
            $output = '';
            
            // Retrieve Logo Post Main Content
            $logo_array					= array();
            $category_fields 	                = array();
            $args = array(
                'no_found_rows' 				=> 1,
                'ignore_sticky_posts' 			=> 1,
                'posts_per_page' 				=> -1,
                'post_type' 					=> 'ts_logos',
                'post_status' 					=> 'publish',
                'orderby' 						=> 'title',
                'order' 						=> 'ASC',
            );
            $logo_query = new WP_Query($args);
            if ($logo_query->have_posts()) {
                foreach($logo_query->posts as $p) {
                    $categories = TS_VCSC_GetTheCategoryByTax($p->ID, 'ts_logos_category');
                    if ($categories && !is_wp_error($categories)) {
                        $category_slugs_arr     = array();
                        $arrayMatch             = 0;
                        foreach ($categories as $category) {
                            if (in_array($category->slug, $logocat)) {
                                $arrayMatch++;
                            }
                            $category_slugs_arr[] = $category->slug;
                            $category_data = array(
                                'slug'			=> $category->slug,
                                'name'			=> $category->cat_name,
                                'number'    	=> $category->term_id,
                            );
                            $category_fields[] 	= $category_data;
                        }
                        $categories_slug_str 	= join(",", $category_slugs_arr);
                    } else {
                        $category_slugs_arr     = array();
                        $arrayMatch             = 0;
                        if (in_array("ts-logo-none-applied", $logocat)) {
                            $arrayMatch++;
                        }
                        $category_slugs_arr[]   = '';
                        $categories_slug_str    = join(",", $category_slugs_arr);
                    }
                    if ($arrayMatch > 0) {
                        $logo_data = array(
                            'author'			=> $p->post_author,
                            'name'				=> $p->post_name,
                            'title'				=> $p->post_title,
                            'id'				=> $p->ID,
                            'content'			=> $p->post_content,
                            'categories'        => $categories_slug_str,
                        );
                        $logo_array[] 	= $logo_data;
                    }
                }
            }
            wp_reset_postdata();
            
            $output .= '<div id="' . $logo_slider_id . '" class="ts-logos-slider owl-carousel" data-id="' . $logo_random . '" data-rtl="' . $page_rtl . '" data-navigation="' . $show_navigation . '" data-dots="' . $show_dots . '" data-transitions="' . $transitions . '" data-height="' . $auto_height . '" data-play="' . $auto_play . '" data-bar="' . $show_bar . '" data-color="' . $bar_color . '" data-speed="' . $show_speed . '" data-hover="' . $stop_hover . '" data-numbers="' . $page_numbers . '">';
            
                // Build Logo Post Main Content
                foreach ($logo_array as $index => $array) {
                    $Logo_Author				= $logo_array[$index]['author'];
                    $Logo_Name 				= $logo_array[$index]['name'];
                    $Logo_Title 				= $logo_array[$index]['title'];
                    $Logo_ID 				= $logo_array[$index]['id'];
                    $Logo_Content 			= $logo_array[$index]['content'];
                    //$Logo_Category 			= $logo_array[$index]['categories'];
                    $Logo_Image				= wp_get_attachment_image_src(get_post_thumbnail_id($Logo_ID), 'full');
                    if ($Logo_Image == false) {
                        $Logo_Image          = TS_VCSC_GetResourceURL('images/defaults/default_person.jpg');
                    } else {
                        $Logo_Image          = $Logo_Image[0];
                    }
                    
                    // Retrieve Logo Post Meta Content
                    $custom_fields 						= get_post_custom($Logo_ID);
                    $custom_fields_array				= array();
                    foreach ($custom_fields as $field_key => $field_values) {
                        if (!isset($field_values[0])) continue;
                        if (in_array($field_key, array("_edit_lock", "_edit_last"))) continue;
                        if (strpos($field_key, 'ts_vcsc_logo_') !== false) {
                            $field_key_split 			= explode("_", $field_key);
                            $field_key_length 			= count($field_key_split) - 1;
                            $custom_data = array(
                                'group'					=> $field_key_split[$field_key_length - 1],
                                'name'					=> 'Logo_' . ucfirst($field_key_split[$field_key_length]),
                                'value'					=> $field_values[0],
                            );
                            $custom_fields_array[] = $custom_data;
                        }
                    }
                    foreach ($custom_fields_array as $index => $array) {
                        ${$custom_fields_array[$index]['name']} = $custom_fields_array[$index]['value'];
                    }
                    if (isset($Logo_Position)) {
                        $Logo_Position 			= $Logo_Position;
                    } else {
                        $Logo_Position 			= '';
                    }
                    if (isset($Logo_Author)) {
                        $Logo_Author 			= $Logo_Author;
                    } else {
                        $Logo_Author 			= '';
                    }
                    
                    if ($style == "style1") {
                        $output .= '<div class="ts-logo-main style1 clearFixMe" style="width: 98%; margin: 0 auto;">';
                            $output .= '<div class="ts-logo-content">';
                                $output .= '<span class="ts-logo-arrow"></span>';
                                if (function_exists('wpb_js_remove_wpautop')){
                                    $output .= '' . wpb_js_remove_wpautop(do_shortcode($Logo_Content), true) . '';
                                } else {
                                    $output .= '' . do_shortcode($Logo_Content) . '';
                                }
                            $output .= '</div>';
                            $output .= '<div class="ts-logo-user">';
                                $output .= '<div class="ts-logo-user-thumb"><img src="' . $Logo_Image . '" alt=""></div>';
                                $output .= '<div class="ts-logo-user-name">' . $Logo_Author . '</div>';
                                $output .= '<div class="ts-logo-user-meta">' . $Logo_Position . '</div>';
                            $output .= '</div>';
                        $output .= '</div>';
                    } else if ($style == "style2") {
                        $output .= '<div class="ts-logo-main style2 clearFixMe" style="width: 98%; margin: 0 auto;">';
                            $output .= '<div class="blockquote">';
                                $output .= '<span class="leftq quotes"></span>';
                                    if (function_exists('wpb_js_remove_wpautop')){
                                        $output .= '' . wpb_js_remove_wpautop(do_shortcode($Logo_Content), true) . '';
                                    } else {
                                        $output .= '' . do_shortcode($Logo_Content) . '';
                                    }
                                $output .= '<span class="rightq quotes"></span>';
                            $output .= '</div>';
                            $output .= '<div class="information">';
                                $output .= '<img src="' . $Logo_Image . '" width="170" height="auto" />';
                                $output .= '<div class="author">' . $Logo_Author . '</div>';
                                $output .= '<div class="metadata">' . $Logo_Position . '</div>';
                            $output .= '</div>';
                        $output .= '</div>';
                    } else if ($style == "style3") {
                        $output .= '<div class="ts-logo-main style3 clearFixMe" style="width: 98%; margin: 0 auto;">';
                            $output .= '<div class="photo">';
                                $output .= '<img src="' . $Logo_Image . '" alt="" />';
                            $output .= '</div>';
                            $output .= '<div class="content">';
                                $output .= '<span class="laquo"></span>';
                                    if (function_exists('wpb_js_remove_wpautop')){
                                        $output .= '' . wpb_js_remove_wpautop(do_shortcode($Logo_Content), true) . '';
                                    } else {
                                        $output .= '' . do_shortcode($Logo_Content) . '';
                                    }
                                $output .= '<span class="raquo"></span>';
                            $output .= '</div>';
                            $output .= '<div class="sign">';
                                $output .= '<span class="author">' . $Logo_Author . '</span>';
                                $output .= '<span class="metadata">' . $Logo_Position . '</span>';
                            $output .= '</div>';
                        $output .= '</div>';
                    }
					
					foreach ($custom_fields_array as $index => $array) {
						unset(${$custom_fields_array[$index]['name']});
					}
                }
            
            $output .= '</div>';
            
            echo $output;
            
            $myvariable = ob_get_clean();
            return $myvariable;
        }
	
        // Add Logo Elements
        function TS_VCSC_Add_Logo_Elements() {
			global $VISUAL_COMPOSER_EXTENSIONS;
            // Add Standalone Logo
            if (function_exists('vc_map')) {
                vc_map( array(
                    "name"                              => __( "TS Single Logo", "ts_visual_composer_extend" ),
                    "base"                              => "TS_VCSC_Logo_Standalone",
                    "icon" 	                            => "icon-wpb-ts_vcsc_logo_standalone",
                    "class"                             => "",
                    "category"                          => __( 'VC Extensions', "ts_visual_composer_extend" ),
                    "description"                       => __("Place a single Logo element", "ts_visual_composer_extend"),
                    //"admin_enqueue_js"                => array(''),
                    //"admin_enqueue_css"               => array(''),
                    "params"                            => array(
                        // Logo Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_1",
                            "value"                     => "Main Content",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "custompost",
                            "heading"                   => __( "Logo", "ts_visual_composer_extend" ),
                            "param_name"                => "logo",
                            "posttype"                  => "ts_logos",
                            "posttaxonomy"              => "ts_logos_category",
							"taxonomy"              	=> "ts_logos_category",
							"postsingle"				=> "Logo",
							"postplural"				=> "Logos",
							"postclass"					=> "logo",
                            "value"                     => "",
                            "admin_label"		        => true,
                            "description"               => __( "Please select the Logo you want to highlight.", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "hidden_input",
                            "heading"                   => __( "Logo Name", "ts_visual_composer_extend" ),
                            "param_name"                => "logo_name",
                            "value"                     => "",
                            "admin_label"		        => true,
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        // Style + Output Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_2",
                            "value"                     => "Style and Output",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Overlay Style", "ts_visual_composer_extend" ),
							"param_name"            	=> "hover_type",
							"width"                 	=> 300,
							"value"                 	=> array(
								__( "Style 1", "ts_visual_composer_extend" )                        => "ts-imagehover-style1",
								__( "Style 2", "ts_visual_composer_extend" )                        => "ts-imagehover-style2",
								__( "Style 3", "ts_visual_composer_extend" )                        => "ts-imagehover-style3",
								__( "Style 4", "ts_visual_composer_extend" )                        => "ts-imagehover-style4",
								__( "Style 5", "ts_visual_composer_extend" )                        => "ts-imagehover-style5",
								__( "Style 6", "ts_visual_composer_extend" )                        => "ts-imagehover-style6",
								__( "Style 7", "ts_visual_composer_extend" )                        => "ts-imagehover-style7",
								__( "Style 8", "ts_visual_composer_extend" )                        => "ts-imagehover-style8",
							),
							"admin_label"           	=> true,
							"description"           	=> __( "Select the overlay effect for the logo image.", "ts_visual_composer_extend" )
						),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Overlay Trigger", "ts_visual_composer_extend" ),
                            "param_name"                => "overlay_trigger",
                            "value"                     => array(
                                __( "Hover", "ts_visual_composer_extend" )                			=> "ts-trigger-hover",
                                __( "Click", "ts_visual_composer_extend" )                			=> "ts-trigger-click",
                            ),
							"description"               => __( "", "ts_visual_composer_extend" ),
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Overlay on Start", "ts_visual_composer_extend" ),
                            "param_name"                => "hover_active",
                            "value"                     => "false",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show the overlay on page load.", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "overlay_trigger", 'value' => array('ts-trigger-click') ),
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
							"dependency"        		=> ""
						),
						array(
							"type"                  	=> "colorpicker",
							"heading"               	=> __( "Handle Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "overlay_handle_color",
							"value"                 	=> "#0094FF",
							"description"           	=> __( "Define the color for the overlay handle button.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "overlay_handle_show", 'value' => 'true' )
						),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Title", "ts_visual_composer_extend" ),
                            "param_name"                => "show_title",
                            "value"                     => "false",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show the logo title in the overlay.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Auto-Truncate Title", "ts_visual_composer_extend" ),
                            "param_name"                => "title_truncate",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to auto-truncate the title if the logo width would otherwise distort the title.", "ts_visual_composer_extend" ),
                            "dependency"            	=> array( 'element' => "show_title", 'value' => 'true' )
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Info Button", "ts_visual_composer_extend" ),
                            "param_name"                => "show_button",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "description"               => __( "Switch the toggle if you want to show a button to show logo description in lightbox.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        // Social Icons Style
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_3",
                            "value"                     => "Social Icon Settings",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Style", "ts_visual_composer_extend" ),
                            "param_name"                => "icon_style",
                            "value"                     => array(
                                __( "Square", "ts_visual_composer_extend" )                => "square",
                                __( "Rounded", "ts_visual_composer_extend" )               => "rounded",
                                __( "Circle", "ts_visual_composer_extend" )                => "circle",
                                __( "Simple", "ts_visual_composer_extend" )                => "simple",
                            ),
							"description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "colorpicker",
                            "heading"                   => __( "Icon Background Color", "ts_visual_composer_extend" ),
                            "param_name"                => "icon_background",
                            "value"                     => "#f5f5f5",
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "dependency"                => array( 'element' => "icon_style", 'value' => array('square', 'rounded', 'circle') )
                        ),
						// Lightbox Settings
						array(
							"type"                  	=> "seperator",
							"heading"               	=> __( "", "ts_visual_composer_extend" ),
							"param_name"            	=> "seperator_4",
							"value"                 	=> "Lightbox Settings",
							"description"           	=> __( "", "ts_visual_composer_extend" ),
							"group" 			        => "Lightbox Settings",
						),
						array(
							"type"              		=> "switch",
							"heading"			    	=> __( "Create AutoGroup", "ts_visual_composer_extend" ),
							"param_name"		    	=> "lightbox_group",
							"value"				    	=> "true",
							"on"						=> __( 'Yes', "ts_visual_composer_extend" ),
							"off"						=> __( 'No', "ts_visual_composer_extend" ),
							"style"						=> "select",
							"design"					=> "toggle-light",
							"description"		    	=> __( "Switch the toggle if you want the plugin to group this image with all other non-gallery images on the page.", "ts_visual_composer_extend" ),
							"dependency"        		=> "",
							"group" 			        => "Lightbox Settings",
						),
						array(
							"type"                  	=> "textfield",
							"heading"               	=> __( "Group Name", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_group_name",
							"value"                 	=> "",
							"admin_label"           	=> true,
							"description"           	=> __( "Enter a custom group name to manually build group with other non-gallery items.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_group", 'value' => 'false' ),
							"group" 			        => "Lightbox Settings",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Transition Effect", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_effect",
							"width"                 	=> 150,
							"value"                 	=> array(
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
							"admin_label"           	=> true,
							"description"           	=> __( "Select the transition effect to be used for the image in the lightbox.", "ts_visual_composer_extend" ),
							"dependency"            	=> "",
							"group" 			        => "Lightbox Settings",
						),
						array(
							"type"                  	=> "dropdown",
							"heading"               	=> __( "Backlight Effect", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_backlight",
							"width"                 	=> 150,
							"value"                 	=> array(
								__( 'No Backlight', "ts_visual_composer_extend" )     => "hideit",
								__( 'Auto Color', "ts_visual_composer_extend" )       => "auto",
								__( 'Custom Color', "ts_visual_composer_extend" )     => "custom",
							),
							"admin_label"           	=> true,
							"description"           	=> __( "Select the backlight effect for the image.", "ts_visual_composer_extend" ),
							"dependency"            	=> "",
							"group" 			        => "Lightbox Settings",
						),
						array(
							"type"                  	=> "colorpicker",
							"heading"               	=> __( "Custom Backlight Color", "ts_visual_composer_extend" ),
							"param_name"            	=> "lightbox_backlight_color",
							"value"                 	=> "#ffffff",
							"description"           	=> __( "Define the backlight color for the lightbox image.", "ts_visual_composer_extend" ),
							"dependency"            	=> array( 'element' => "lightbox_backlight", 'value' => 'custom' ),
							"group" 			        => "Lightbox Settings",
						),
                        // Other Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_5",
                            "value"                     => "Other Settings",
                            "description"               => __( "", "ts_visual_composer_extend" )
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
                            "description"               => __( "Select the top margin for the element.", "ts_visual_composer_extend" )
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
                            "description"               => __( "Select the bottom margin for the element.", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "textfield",
                            "heading"                   => __( "Define ID Name", "ts_visual_composer_extend" ),
                            "param_name"                => "el_id",
                            "value"                     => "",
                            "description"               => __( "Enter an unique ID for the element.", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "textfield",
                            "heading"                   => __( "Extra Class Name", "ts_visual_composer_extend" ),
                            "param_name"                => "el_class",
                            "value"                     => "",
                            "description"               => __( "Enter a class name for the element.", "ts_visual_composer_extend" )
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
            // Add Single Logo (for Custom Slider)
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name"                           	=> __("TS Logo Slide", "ts_visual_composer_extend"),
                    "base"                           	=> "TS_VCSC_Logo_Single",
                    "class"                          	=> "",
                    "icon"                           	=> "icon-wpb-ts_vcsc_logo",
                    "category"                       	=> __("VC Extensions", "ts_visual_composer_extend"),
                    "content_element"                	=> true,
                    "as_child"                       	=> array('only' => 'TS_VCSC_Logo_Slider_Custom'),
                    "description"                    	=> __("Add a logo slide element", "ts_visual_composer_extend"),
                    "params"                         	=> array(
                        // Logo Select
                        array(
                            "type"                  	=> "seperator",
                            "heading"               	=> __( "", "ts_visual_composer_extend" ),
                            "param_name"            	=> "seperator_1",
                            "value"                 	=> "Selections",
                            "description"           	=> __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "custompost",
                            "heading"                   => __( "Logo", "ts_visual_composer_extend" ),
                            "param_name"                => "logo",
                            "posttype"                  => "ts_logos",
                            "posttaxonomy"              => "ts_logos_category",
							"taxonomy"              	=> "ts_logos_category",
							"postsingle"				=> "Logo",
							"postplural"				=> "Logos",
							"postclass"					=> "logo",
                            "value"                     => "",
                            "admin_label"		        => true,
                            "description"               => __( "Please select the Logo you want to highlight.", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                  	=> "hidden_input",
                            "heading"               	=> __( "Logo", "ts_visual_composer_extend" ),
                            "param_name"            	=> "logo_name",
                            "value"                 	=> "",
                            "admin_label"		    	=> true,
                            "description"           	=> __( "", "ts_visual_composer_extend" )
                        ),
                        // Logo Design
                        array(
                            "type"                  	=> "seperator",
                            "heading"               	=> __( "", "ts_visual_composer_extend" ),
                            "param_name"            	=> "seperator_2",
                            "value"                 	=> "Logo Style",
                            "description"           	=> __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                  	=> "dropdown",
                            "heading"               	=> __( "Design", "ts_visual_composer_extend" ),
                            "param_name"            	=> "style",
                            "value"             => array(
                                __( 'Style 1', "ts_visual_composer_extend" )          => "style1",
                                __( 'Style 2', "ts_visual_composer_extend" )          => "style2",
                                __( 'Style 3', "ts_visual_composer_extend" )          => "style3",
                            ),
                            "description"           	=> __( "", "ts_visual_composer_extend" ),
                            "admin_label"           	=> true,
                            "dependency"            	=> ""
                        ),
                        // Load Custom CSS/JS File
                        array(
                            "type"                  	=> "load_file",
                            "heading"               	=> __( "", "ts_visual_composer_extend" ),
                            "param_name"            	=> "el_file",
                            "value"                 	=> "",
                            "file_type"             	=> "js",
                            "file_path"             	=> "js/ts-visual-composer-extend-element.min.js",
                            "description"           	=> __( "", "ts_visual_composer_extend" )
                        ),
                    ))
                );
            }
            // Add Logos Slider 1 (Custom Build)
            if (function_exists('vc_map')) {
                vc_map(array(
                   "name"                               => __("TS Logos Slider 1", "ts_visual_composer_extend"),
                   "base"                               => "TS_VCSC_Logo_Slider_Custom",
                   "class"                              => "",
                   "icon"                               => "icon-wpb-ts_vcsc_logo_slider_custom",
                   "category"                           => __("VC Extensions", "ts_visual_composer_extend"),
                   "as_parent"                          => array('only' => 'TS_VCSC_Logo_Single'),
                   "description"                        => __("Build a custom Logo Slider", "ts_visual_composer_extend"),
                   "content_element"                    => true,
                   "show_settings_on_create"            => false,
                   "params"                             => array(
                        // Slider Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_1",
                            "value"                     => "Slider Settings",
                            "description"               => __( "", "ts_visual_composer_extend" )
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
                            "type"                      => "dropdown",
                            "heading"                   => __( "Transition", "ts_visual_composer_extend" ),
                            "param_name"                => "transitions",
                            "width"                     => 150,
                            "value"                     => array(
                                __( 'Back Slide', "ts_visual_composer_extend" )		    => "backSlide",
                                __( 'Go Down', "ts_visual_composer_extend" )		        => "goDown",
                                __( 'Fade Up', "ts_visual_composer_extend" )		        => "fadeUp",
                                __( 'Simple Fade', "ts_visual_composer_extend" )		    => "fade",
                            ),
                            "description"               => __( "Select how to transition between the individual slides.", "ts_visual_composer_extend" ),
                            "admin_label"		        => true,
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
                            "param_name"                => "el_file",
                            "value"                     => "",
                            "file_type"                 => "js",
                            "file_path"                 => "js/ts-visual-composer-extend-element.min.js",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                    ),
                    "js_view"                           => 'VcColumnView'
                ));
            }
            // Add Logos Slider 2 (by Categroies)
            if (function_exists('vc_map')) {
                vc_map( array(
                   "name"                               => __("TS Logos Slider 2", "ts_visual_composer_extend"),
                   "base"                               => "TS_VCSC_Logo_Slider_Category",
                   "class"                              => "",
                   "icon"                               => "icon-wpb-ts_vcsc_logo_slider_category",
                   "category"                           => __("VC Extensions", "ts_visual_composer_extend"),
                   "description"                        => __("Place a Logo Slider (by Category)", "ts_visual_composer_extend"),
                   "params"                             => array(
                        // Slider Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_1",
                            "value"                     => "Slider Settings",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "custompostcat",
                            "heading"                   => __( "Logo Categories", "ts_visual_composer_extend" ),
                            "param_name"                => "logocat",
                            "posttype"                  => "ts_logos",
                            "posttaxonomy"              => "ts_logos_category",
							"taxonomy"              	=> "ts_logos_category",
							"postsingle"				=> "Logo",
							"postplural"				=> "Logos",
							"postclass"					=> "logo",
                            "value"                     => "",
                            "description"               => __( "Please select the logo categories you want to use for the slider.", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Design", "ts_visual_composer_extend" ),
                            "param_name"                => "style",
                            "value"                     => array(
                                __( 'Style 1', "ts_visual_composer_extend" )          => "style1",
                                __( 'Style 2', "ts_visual_composer_extend" )          => "style2",
                                __( 'Style 3', "ts_visual_composer_extend" )          => "style3",
                            ),
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "admin_label"               => true,
                            "dependency"                => ""
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
                            "type"                      => "dropdown",
                            "heading"                   => __( "Transition", "ts_visual_composer_extend" ),
                            "param_name"                => "transitions",
                            "width"                     => 150,
                            "value"                     => array(
                                __( 'Back Slide', "ts_visual_composer_extend" )		    => "backSlide",
                                __( 'Go Down', "ts_visual_composer_extend" )		        => "goDown",
                                __( 'Fade Up', "ts_visual_composer_extend" )		        => "fadeUp",
                                __( 'Simple Fade', "ts_visual_composer_extend" )		    => "fade",
                            ),
                            "description"               => __( "Select how to transition between the individual slides.", "ts_visual_composer_extend" ),
                            "admin_label"		        => true,
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
                            "param_name"                => "el_file",
                            "value"                     => "",
                            "file_type"                 => "js",
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
    class WPBakeryShortCode_TS_VCSC_Logo_Slider_Custom extends WPBakeryShortCodesContainer {};
}
if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_TS_VCSC_Logo_Standalone extends WPBakeryShortCode {};
	class WPBakeryShortCode_TS_VCSC_Logo_Single extends WPBakeryShortCode {};
	class WPBakeryShortCode_TS_VCSC_Logo_Slider_Category extends WPBakeryShortCode {};
}
// Initialize "TS Logos" Class
if (class_exists('TS_Logos')) {
	$TS_Logos = new TS_Logos;
}