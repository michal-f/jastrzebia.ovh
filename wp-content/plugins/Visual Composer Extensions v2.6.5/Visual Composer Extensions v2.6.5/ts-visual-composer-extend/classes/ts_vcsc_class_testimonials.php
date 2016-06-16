<?php
if (!class_exists('TS_Testimonials')){
	class TS_Testimonials {
		function __construct() {
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
                    add_action('init',                              	array($this, 'TS_VCSC_Add_Testimonial_Elements'), 9999999);
                } else {
                    add_action('admin_init',		                	array($this, 'TS_VCSC_Add_Testimonial_Elements'), 9999999);
                }
            } else {
                add_action('admin_init',								array($this, 'TS_VCSC_Add_Testimonial_Elements'), 9999999);
            }
            add_shortcode('TS_VCSC_Testimonial_Standalone', 			array($this, 'TS_VCSC_Testimonial_Standalone'));
            add_shortcode('TS_VCSC_Testimonial_Single',                	array($this, 'TS_VCSC_Testimonial_Single'));
            add_shortcode('TS_VCSC_Testimonial_Slider_Custom',         	array($this, 'TS_VCSC_Testimonial_Slider_Custom'));
            add_shortcode('TS_VCSC_Testimonial_Slider_Category',        array($this, 'TS_VCSC_Testimonial_Slider_Category'));
		}
        
        // Standalone Testimonial
        function TS_VCSC_Testimonial_Standalone ($atts, $content = null) {
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();
    
            if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
                wp_enqueue_style('ts-visual-composer-extend-front');
            }
        
            extract( shortcode_atts( array(
                'testimonial'					=> '',
                'style'							=> 'style1',
				'show_author'					=> 'true',
				'show_avatar'					=> 'true',
                'margin_top'                    => 0,
                'margin_bottom'                 => 0,
                'el_id'                         => '',
                'el_class'                      => '',
				'css'							=> '',
            ), $atts ));
            
            $output								= '';
    
            // Check for Testimonial and End Shortcode if Empty
            if (empty($testimonial)) {
                $output .= '<div style="text-align: justify; font-weight: bold; font-size: 14px; color: red;">Please select a testimonial in the element settings!</div>';
                echo $output;
                $myvariable = ob_get_clean();
                return $myvariable;
            }
	
            if (!empty($el_id)) {
                $testimonial_block_id			= $el_id;
            } else {
                $testimonial_block_id			= 'ts-vcsc-testimonial-' . mt_rand(999999, 9999999);
            }
            
            // Retrieve Testimonial Post Main Content
            $testimonial_array					= array();
            $args = array(
                'no_found_rows' 				=> 1,
                'ignore_sticky_posts' 			=> 1,
                'posts_per_page' 				=> -1,
                'post_type' 					=> 'ts_testimonials',
                'post_status' 					=> 'publish',
                'orderby' 						=> 'title',
                'order' 						=> 'ASC',
            );
            $testimonial_query = new WP_Query($args);
            if ($testimonial_query->have_posts()) {
                foreach($testimonial_query->posts as $p) {
                    if ($p->ID == $testimonial) {
                        $testimonial_data = array(
                            'author'			=> $p->post_author,
                            'name'				=> $p->post_name,
                            'title'				=> $p->post_title,
                            'id'				=> $p->ID,
                            'content'			=> $p->post_content,
                        );
                        $testimonial_array[] = $testimonial_data;
                    }
                }
            }
            wp_reset_postdata();
            
            // Build Testimonial Post Main Content
            foreach ($testimonial_array as $index => $array) {
                $Testimonial_Author				= $testimonial_array[$index]['author'];
                $Testimonial_Name 				= $testimonial_array[$index]['name'];
                $Testimonial_Title 				= $testimonial_array[$index]['title'];
                $Testimonial_ID 				= $testimonial_array[$index]['id'];
                $Testimonial_Content 			= $testimonial_array[$index]['content'];
                $Testimonial_Image				= wp_get_attachment_image_src(get_post_thumbnail_id($Testimonial_ID), 'full');
                if ($Testimonial_Image == false) {
                    $Testimonial_Image          = TS_VCSC_GetResourceURL('images/defaults/default_person.jpg');
                } else {
                    $Testimonial_Image          = $Testimonial_Image[0];
                }
    
                // Retrieve Testimonial Post Meta Content
                $custom_fields 						= get_post_custom($Testimonial_ID);
                $custom_fields_array				= array();
                foreach ($custom_fields as $field_key => $field_values) {
                    if (!isset($field_values[0])) continue;
                    if (in_array($field_key, array("_edit_lock", "_edit_last"))) continue;
                    if (strpos($field_key, 'ts_vcsc_testimonial_') !== false) {
                        $field_key_split 			= explode("_", $field_key);
                        $field_key_length 			= count($field_key_split) - 1;
                        $custom_data = array(
                            'group'					=> $field_key_split[$field_key_length - 1],
                            'name'					=> 'Testimonial_' . ucfirst($field_key_split[$field_key_length]),
                            'value'					=> $field_values[0],
                        );
                        $custom_fields_array[] = $custom_data;
                    }
                }
                foreach ($custom_fields_array as $index => $array) {
                    ${$custom_fields_array[$index]['name']} = $custom_fields_array[$index]['value'];
                }
                if (isset($Testimonial_Position)) {
                    $Testimonial_Position 			= $Testimonial_Position;
                } else {
                    $Testimonial_Position 			= '';
                }
                if (isset($Testimonial_Author)) {
                    $Testimonial_Author 			= $Testimonial_Author;
                } else {
                    $Testimonial_Author 			= '';
                }

				if (function_exists('vc_shortcode_custom_css_class')) {
					$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-testimonial-main clearFixMe ' . $style . ' ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Testimonial_Standalone', $atts);
				} else {
					$css_class	= 'ts-testimonial-main clearFixMe ' . $style . ' ' . $el_class;
				}
				
                // Create Output
                if ($style == "style1") {
                    $output .= '<div id="' . $testimonial_block_id . '" class="' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
                        $output .= '<div class="ts-testimonial-content">';
							if (($show_avatar == "true") || ($show_author == "true")) {
								$output .= '<span class="ts-testimonial-arrow"></span>';
							}
                            if (function_exists('wpb_js_remove_wpautop')){
                                $output .= '' . wpb_js_remove_wpautop(do_shortcode($Testimonial_Content), true) . '';
                            } else {
                                $output .= '' . do_shortcode($Testimonial_Content) . '';
                            }
                        $output .= '</div>';
						if (($show_avatar == "true") || ($show_author == "true")) {
							$output .= '<div class="ts-testimonial-user">';
								if ($show_avatar == "true") {
									$output .= '<div class="ts-testimonial-user-thumb"><img src="' . $Testimonial_Image . '" alt=""></div>';
								}
								if ($show_author == "true") {
									$output .= '<div class="ts-testimonial-user-name">' . $Testimonial_Author . '</div>';
									$output .= '<div class="ts-testimonial-user-meta">' . $Testimonial_Position . '</div>';
								}
							$output .= '</div>';
						}
                    $output .= '</div>';
                }
				if ($style == "style2") {
                    $output .= '<div id="' . $testimonial_block_id . '" class="' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
                        $output .= '<div class="blockquote">';
                            $output .= '<span class="leftq quotes"></span>';
                                if (function_exists('wpb_js_remove_wpautop')){
                                    $output .= '' . wpb_js_remove_wpautop(do_shortcode($Testimonial_Content), true) . '';
                                } else {
                                    $output .= '' . do_shortcode($Testimonial_Content) . '';
                                }
                            $output .= '<span class="rightq quotes"></span>';
                        $output .= '</div>';
						if (($show_avatar == "true") || ($show_author == "true")) {
							$output .= '<div class="information">';
								if ($show_avatar == "true") {
									$output .= '<img src="' . $Testimonial_Image . '" style="width: 150px; height: auto; " width="150" height="auto" />';
								}
								if ($show_author == "true") {
									$output .= '<div class="author" style="' . ($show_avatar == "false" ? "margin-left: 15px;" : "") . '">' . $Testimonial_Author . '</div>';
									$output .= '<div class="metadata">' . $Testimonial_Position . '</div>';
								}
							$output .= '</div>';
						}
                    $output .= '</div>';
                }
				if ($style == "style3") {
                    $output .= '<div id="' . $testimonial_block_id . '" class="' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
						if ($show_avatar == "true") {
							$output .= '<div class="photo">';
								$output .= '<img src="' . $Testimonial_Image . '" alt=""/>';
							$output .= '</div>';
						}
                        $output .= '<div class="content" style="' . ($show_avatar == "false" ? "margin-left: 0;" : "") . '">';
                            $output .= '<span class="laquo"></span>';
                                if (function_exists('wpb_js_remove_wpautop')){
                                    $output .= '' . wpb_js_remove_wpautop(do_shortcode($Testimonial_Content), true) . '';
                                } else {
                                    $output .= '' . do_shortcode($Testimonial_Content) . '';
                                }
                            $output .= '<span class="raquo"></span>';
                        $output .= '</div>';
						if ($show_author == "true") {
							$output .= '<div class="sign">';
								$output .= '<span class="author">' . $Testimonial_Author . '</span>';
								$output .= '<span class="metadata">' . $Testimonial_Position . '</span>';
							$output .= '</div>';
						}
                    $output .= '</div>';
                }
				if ($style == "style4") {
					$output .= '<div id="' . $testimonial_block_id . '" class="' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . ($margin_bottom + 32) . 'px;">';
						if (($show_avatar == "true") || ($show_author == "true")) {
							$output .= '<div class="ts-testimonial-author-info clearfix">';
								if ($show_avatar == "true") {
									$output .= '<div class="ts-testimonial-author-image">';
										$output .= '<img src="' . $Testimonial_Image . '" alt="">';
										$output .= '<span class="ts-testimonial-author-overlay"></span>';
									$output .= '</div>';
								}
								if ($show_author == "true") {
									$output .= '<span class="ts-testimonial-author-name">' . $Testimonial_Author . '</span>';
									$output .= '<span class="ts-testimonial-author-position">' . $Testimonial_Position . '</span>';
								}
							$output .= '</div>';
						}
						$output .= '<div class="ts-testimonial-statement clearfix">';
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= '' . wpb_js_remove_wpautop(do_shortcode($Testimonial_Content), true) . '';
							} else {
								$output .= '' . do_shortcode($Testimonial_Content) . '';
							}
						$output .= '</div>';			
						$output .= '<div class="ts-testimonial-bottom-arrow"></div>';
					$output .= '</div>';
				}
            
                break;
            }
            
            echo $output;
            
            $myvariable = ob_get_clean();
            return $myvariable;
        }
    
        // Single Testimonial for Custom Slider
        function TS_VCSC_Testimonial_Single($atts, $content = null) {
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();
    
            if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
                wp_enqueue_style('ts-visual-composer-extend-front');
            }
        
            extract( shortcode_atts( array(
                'testimonial'					=> '',
                'style'							=> 'style1',
				'show_author'					=> 'true',
				'show_avatar'					=> 'true',
                'el_id'                         => '',
                'el_class'                      => '',
				'css'							=> '',
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

            // Check for Testimonial and End Shortcode if Empty
            if (empty($testimonial)) {
                $output .= '<div style="text-align: justify; font-weight: bold; font-size: 14px; color: red;">Please select a testimonial in the element settings!</div>';
                echo $output;
                $myvariable = ob_get_clean();
                return $myvariable;
            }
			
            $testimonial_item_id				= 'ts-vcsc-testimonial-item-' . mt_rand(999999, 9999999);
            
            // Retrieve Testimonial Post Main Content
            $testimonial_array					= array();
            $category_fields 	                = array();
            $args = array(
                'no_found_rows' 				=> 1,
                'ignore_sticky_posts' 			=> 1,
                'posts_per_page' 				=> -1,
                'post_type' 					=> 'ts_testimonials',
                'post_status' 					=> 'publish',
                'orderby' 						=> 'title',
                'order' 						=> 'ASC',
            );
            $testimonial_query = new WP_Query($args);
            if ($testimonial_query->have_posts()) {
                foreach($testimonial_query->posts as $p) {
                    if ($p->ID == $testimonial) {
                        $testimonial_data = array(
                            'author'			=> $p->post_author,
                            'name'				=> $p->post_name,
                            'title'				=> $p->post_title,
                            'id'				=> $p->ID,
                            'content'			=> $p->post_content,
                        );
                        $testimonial_array[] = $testimonial_data;
                    }
                }
            }
            wp_reset_postdata();
			
            // Build Testimonial Post Main Content
            foreach ($testimonial_array as $index => $array) {
                $Testimonial_Author				= $testimonial_array[$index]['author'];
                $Testimonial_Name 				= $testimonial_array[$index]['name'];
                $Testimonial_Title 				= $testimonial_array[$index]['title'];
                $Testimonial_ID 				= $testimonial_array[$index]['id'];
                $Testimonial_Content 			= $testimonial_array[$index]['content'];
                $Testimonial_Image				= wp_get_attachment_image_src(get_post_thumbnail_id($Testimonial_ID), 'full');
                if ($Testimonial_Image == false) {
                    $Testimonial_Image          = TS_VCSC_GetResourceURL('images/defaults/default_person.jpg');
                } else {
                    $Testimonial_Image          = $Testimonial_Image[0];
                }
            }
			
            // Retrieve Testimonial Post Meta Content
            $custom_fields 						= get_post_custom($Testimonial_ID);
            $custom_fields_array				= array();
            foreach ($custom_fields as $field_key => $field_values) {
                if (!isset($field_values[0])) continue;
                if (in_array($field_key, array("_edit_lock", "_edit_last"))) continue;
                if (strpos($field_key, 'ts_vcsc_testimonial_') !== false) {
                    $field_key_split 			= explode("_", $field_key);
                    $field_key_length 			= count($field_key_split) - 1;
                    $custom_data = array(
                        'group'					=> $field_key_split[$field_key_length - 1],
                        'name'					=> 'Testimonial_' . ucfirst($field_key_split[$field_key_length]),
                        'value'					=> $field_values[0],
                    );
                    $custom_fields_array[] = $custom_data;
                }
            }
            foreach ($custom_fields_array as $index => $array) {
                ${$custom_fields_array[$index]['name']} = $custom_fields_array[$index]['value'];
            }
			if (isset($Testimonial_Position)) {
				$Testimonial_Position 			= $Testimonial_Position;
			} else {
				$Testimonial_Position 			= '';
			}
			if (isset($Testimonial_Author)) {
				$Testimonial_Author 			= $Testimonial_Author;
			} else {
				$Testimonial_Author 			= '';
			}
			
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-testimonial-main clearFixMe ' . $style . ' ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Testimonial_Single', $atts);
            } else {
				$css_class	= 'ts-testimonial-main clearFixMe ' . $style . ' ' . $el_class;
			}
			
			// Create Output
			if ($style == "style1") {
				$output .= '<div id="' . $testimonial_item_id . '" class="' . $css_class . '" style="width: 99%; margin: 0 auto;">';
					$output .= '<div class="ts-testimonial-content">';
						if (($show_avatar == "true") || ($show_author == "true")) {
							$output .= '<span class="ts-testimonial-arrow"></span>';
						}
						if (function_exists('wpb_js_remove_wpautop')){
							$output .= '' . wpb_js_remove_wpautop(do_shortcode($Testimonial_Content), true) . '';
						} else {
							$output .= '' . do_shortcode($Testimonial_Content) . '';
						}
					$output .= '</div>';
					if (($show_avatar == "true") || ($show_author == "true")) {
						$output .= '<div class="ts-testimonial-user">';
							if ($show_avatar == "true") {
								$output .= '<div class="ts-testimonial-user-thumb"><img src="' . $Testimonial_Image . '" alt=""></div>';
							}
							if ($show_author == "true") {
								$output .= '<div class="ts-testimonial-user-name">' . $Testimonial_Author . '</div>';
								$output .= '<div class="ts-testimonial-user-meta">' . $Testimonial_Position . '</div>';
							}
						$output .= '</div>';
					}
				$output .= '</div>';
			}
			if ($style == "style2") {
				$output .= '<div id="' . $testimonial_item_id . '" class="' . $css_class . '" style="width: 99%; margin: 0 auto;">';
					$output .= '<div class="blockquote">';
						$output .= '<span class="leftq quotes"></span>';
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= '' . wpb_js_remove_wpautop(do_shortcode($Testimonial_Content), true) . '';
							} else {
								$output .= '' . do_shortcode($Testimonial_Content) . '';
							}
						$output .= '<span class="rightq quotes"></span>';
					$output .= '</div>';
					if (($show_avatar == "true") || ($show_author == "true")) {
						$output .= '<div class="information">';
							if ($show_avatar == "true") {
								$output .= '<img src="' . $Testimonial_Image . '" style="width: 150px; height: auto;" width="150" height="auto" />';
							}
							if ($show_author == "true") {
								$output .= '<div class="author" style="' . ($show_avatar == "false" ? "margin-left: 15px;" : "") . '">' . $Testimonial_Author . '</div>';
								$output .= '<div class="metadata">' . $Testimonial_Position . '</div>';
							}
						$output .= '</div>';
					}
				$output .= '</div>';				
			}
			if ($style == "style3") {
				$output .= '<div id="' . $testimonial_item_id . '" class="' . $css_class . '" style="width: 99%; margin: 0 auto;">';
					if ($show_avatar == "true") {
						$output .= '<div class="photo">';
							$output .= '<img src="' . $Testimonial_Image . '" alt=""/>';
						$output .= '</div>';
					}
					$output .= '<div class="content" style="' . ($show_avatar == "false" ? "margin-left: 0;" : "") . '">';
						$output .= '<span class="laquo"></span>';
							if (function_exists('wpb_js_remove_wpautop')){
								$output .= '' . wpb_js_remove_wpautop(do_shortcode($Testimonial_Content), true) . '';
							} else {
								$output .= '' . do_shortcode($Testimonial_Content) . '';
							}
						$output .= '<span class="raquo"></span>';
					$output .= '</div>';
					if ($show_author == "true") {
						$output .= '<div class="sign">';
							$output .= '<span class="author">' . $Testimonial_Author . '</span>';
							$output .= '<span class="metadata">' . $Testimonial_Position . '</span>';
						$output .= '</div>';
					}
				$output .= '</div>';
			}
			if ($style == "style4") {
				$output .= '<div id="' . $testimonial_block_id . '" class="' . $css_class . '" style="width: 99%; margin: 0 auto 32px auto;">';
					if (($show_avatar == "true") || ($show_author == "true")) {
						$output .= '<div class="ts-testimonial-author-info clearfix">';
							if ($show_avatar == "true") {
								$output .= '<div class="ts-testimonial-author-image">';
									$output .= '<img src="' . $Testimonial_Image . '" alt="">';
									$output .= '<span class="ts-testimonial-author-overlay"></span>';
								$output .= '</div>';
							}
							if ($show_author == "true") {
								$output .= '<span class="ts-testimonial-author-name">' . $Testimonial_Author . '</span>';
								$output .= '<span class="ts-testimonial-author-position">' . $Testimonial_Position . '</span>';
							}
						$output .= '</div>';
					}
					$output .= '<div class="ts-testimonial-statement clearfix">';
						if (function_exists('wpb_js_remove_wpautop')){
							$output .= '' . wpb_js_remove_wpautop(do_shortcode($Testimonial_Content), true) . '';
						} else {
							$output .= '' . do_shortcode($Testimonial_Content) . '';
						}
					$output .= '</div>';			
					$output .= '<div class="ts-testimonial-bottom-arrow"></div>';
				$output .= '</div>';
			}
                
            echo $output;
            
            $myvariable = ob_get_clean();
            return $myvariable;
        }

        // Custom Testimonial Slider
        function TS_VCSC_Testimonial_Slider_Custom($atts, $content = null){
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();
    
            wp_enqueue_style('ts-extend-owlcarousel2');
            wp_enqueue_script('ts-extend-owlcarousel2');
			wp_enqueue_style('ts-font-ecommerce');
	
            if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
				wp_enqueue_style('ts-extend-animations');
                wp_enqueue_style('ts-visual-composer-extend-front');
                wp_enqueue_script('ts-visual-composer-extend-front');
            }
            
            extract( shortcode_atts( array(
				'testimonials_slide'			=> 1,
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
                'el_id'                         => '',
                'el_class'                      => '',
				'css'							=> '',
            ), $atts ));
            
            $testimonial_random                 = mt_rand(999999, 9999999);
            
            if (!empty($el_id)) {
                $testimonial_slider_id			= $el_id;
            } else {
                $testimonial_slider_id			= 'ts-vcsc-testimonial-slider-' . $testimonial_random;
            }
			
			// Check for Front End Editor
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
					$slider_class				= 'owl-carousel-edit';
					$slider_message				= '<div class="ts-composer-frontedit-message">' . __( 'The slider is currently viewed in front-end edit mode; slider features are disabled for performance and compatibility reasons.', "ts_visual_composer_extend" ) . '</div>';
					$testimonial_style			= 'width: ' . (100 / $testimonials_slide) . '%; height: 100%; float: left; margin: 0; padding: 0;';
					$frontend_edit				= 'true';
                } else {
					$slider_class				= 'ts-owlslider-parent owl-carousel';
					$slider_message				= '';
					$testimonial_style			= '';
					$frontend_edit				= 'false';
                }
            } else {
				$slider_class					= 'ts-owlslider-parent owl-carousel';
				$slider_message					= '';
				$testimonial_style				= '';
				$frontend_edit					= 'false';
            }
            
            $output = '';
            
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-testimonials-slider ' . $slider_class . ' ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Testimonial_Slider_Custom', $atts);
			} else {
				$css_class	= 'ts-testimonials-slider ' . $slider_class . ' ' . $el_class;
			}
			
			$output .= '<div id="' . $testimonial_slider_id . '-container" class="ts-testimonials-slider-container">';
				// Front-Edit Message
				if ($frontend_edit == "true") {
					$output .= $slider_message;
				}
				// Add Progressbar
				if (($auto_play == "true") && ($show_bar == "true") && ($frontend_edit == "false")) {
					$output .= '<div id="ts-owlslider-progressbar-' . $testimonial_random . '" class="ts-owlslider-progressbar-holder" style=""><div class="ts-owlslider-progressbar" style="background: ' . $bar_color . '; height: 100%; width: 0%;"></div></div>';
				}
				// Add Navigation Controls
				if ($frontend_edit == "false") {
					$output .= '<div id="ts-owlslider-controls-' . $testimonial_random . '" class="ts-owlslider-controls" style="' . ((($auto_play == "true") || ($show_navigation == "true")) ? "display: block;" : "display: none;") . '">';
						$output .= '<div id="ts-owlslider-controls-next-' . $testimonial_random . '" style="' . (($show_navigation == "true") ? "display: block;" : "display: none;") . '" class="ts-owlslider-controls-next"><span class="ts-ecommerce-arrowright5"></span></div>';
						$output .= '<div id="ts-owlslider-controls-prev-' . $testimonial_random . '" style="' . (($show_navigation == "true") ? "display: block;" : "display: none;") . '" class="ts-owlslider-controls-prev"><span class="ts-ecommerce-arrowleft5"></span></div>';
						if ($auto_play == "true") {
							$output .= '<div id="ts-owlslider-controls-play-' . $testimonial_random . '" class="ts-owlslider-controls-play active"><span class="ts-ecommerce-pause"></span></div>';
						}
					$output .= '</div>';
				}
				// Add Slider
				$output .= '<div id="' . $testimonial_slider_id . '" class="' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;" data-id="' . $testimonial_random . '" data-items="' . $testimonials_slide . '" data-rtl="' . $page_rtl . '" data-loop="' . $items_loop . '" data-navigation="' . $show_navigation . '" data-dots="' . $show_dots . '" data-mobile="' . $animation_mobile . '" data-animationin="' . $animation_in . '" data-animationout="' . $animation_out . '" data-height="' . $auto_height . '" data-play="' . $auto_play . '" data-bar="' . $show_bar . '" data-color="' . $bar_color . '" data-speed="' . $show_speed . '" data-hover="' . $stop_hover . '">';
					$output .= do_shortcode($content);
				$output .= '</div>';
            $output .= '</div>';
			
            echo $output;
            
            $myvariable = ob_get_clean();
            return $myvariable;
        }
        // Category Testimonial Slider
        function TS_VCSC_Testimonial_Slider_Category($atts, $content = null){
            global $VISUAL_COMPOSER_EXTENSIONS;
            ob_start();

            wp_enqueue_style('ts-extend-owlcarousel2');
            wp_enqueue_script('ts-extend-owlcarousel2');
			wp_enqueue_style('ts-font-ecommerce');
    
            if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
				wp_enqueue_style('ts-extend-animations');
                wp_enqueue_style('ts-visual-composer-extend-front');
                wp_enqueue_script('ts-visual-composer-extend-front');
            }
            
            extract( shortcode_atts( array(
                'testimonialcat'                => '',
                'style'							=> 'style1',
				'show_author'					=> 'true',
				'show_avatar'					=> 'true',
				'testimonials_slide'			=> 1,
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
                'el_id'                         => '',
                'el_class'                      => '',
				'css'							=> '',
            ), $atts ));
            
            $testimonial_random                 = mt_rand(999999, 9999999);
            
            if (!empty($el_id)) {
                $testimonial_slider_id			= $el_id;
            } else {
                $testimonial_slider_id			= 'ts-vcsc-testimonial-slider-' . $testimonial_random;
            }
            
            if (!is_array($testimonialcat)) {
                $testimonialcat 				= array_map('trim', explode(',', $testimonialcat));
            }
			
			// Check for Front End Editor
            if (function_exists('vc_is_inline')){
                if (vc_is_inline()) {
					$slider_class				= 'owl-carousel-edit';
					$slider_message				= '<div class="ts-composer-frontedit-message">' . __( 'The slider is currently viewed in front-end edit mode; slider features are disabled for performance and compatibility reasons.', "ts_visual_composer_extend" ) . '</div>';
					$testimonial_style			= 'width: ' . (100 / $testimonials_slide) . '%; height: 100%; float: left; margin: 0; padding: 0;';
					$frontend_edit				= 'true';
                } else {
					$slider_class				= 'ts-owlslider-parent owl-carousel';
					$slider_message				= '';
					$testimonial_style			= '';
					$frontend_edit				= 'false';
                }
            } else {
				$slider_class					= 'ts-owlslider-parent owl-carousel';
				$slider_message					= '';
				$testimonial_style				= '';
				$frontend_edit					= 'false';
            }
            
            $output = '';
            
            // Retrieve Testimonial Post Main Content
            $testimonial_array					= array();
            $category_fields 	                = array();
            $args = array(
                'no_found_rows' 				=> 1,
                'ignore_sticky_posts' 			=> 1,
                'posts_per_page' 				=> -1,
                'post_type' 					=> 'ts_testimonials',
                'post_status' 					=> 'publish',
                'orderby' 						=> 'title',
                'order' 						=> 'ASC',
            );
            $testimonial_query = new WP_Query($args);
            if ($testimonial_query->have_posts()) {
                foreach($testimonial_query->posts as $p) {
                    $categories = TS_VCSC_GetTheCategoryByTax($p->ID, 'ts_testimonials_category');
                    if ($categories && !is_wp_error($categories)) {
                        $category_slugs_arr     = array();
                        $arrayMatch             = 0;
                        foreach ($categories as $category) {
                            if (in_array($category->slug, $testimonialcat)) {
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
                        if (in_array("ts-testimonial-none-applied", $testimonialcat)) {
                            $arrayMatch++;
                        }
                        $category_slugs_arr[]   = '';
                        $categories_slug_str    = join(",", $category_slugs_arr);
                    }
                    if ($arrayMatch > 0) {
                        $testimonial_data = array(
                            'author'			=> $p->post_author,
                            'name'				=> $p->post_name,
                            'title'				=> $p->post_title,
                            'id'				=> $p->ID,
                            'content'			=> $p->post_content,
                            'categories'        => $categories_slug_str,
                        );
                        $testimonial_array[] 	= $testimonial_data;
                    }
                }
            }
            wp_reset_postdata();
            
			if (function_exists('vc_shortcode_custom_css_class')) {
				$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'ts-testimonials-slider ' . $slider_class . ' ' . $el_class . ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS_VCSC_Testimonial_Slider_Category', $atts);
			} else {
				$css_class	= 'ts-testimonials-slider ' . $slider_class . ' ' . $el_class;
			}
			
			$output .= '<div id="' . $testimonial_slider_id . '-container" class="ts-testimonials-slider-container">';
				// Front-Edit Message
				if ($frontend_edit == "true") {
					$output .= $slider_message;
				}
				// Add Progressbar
				if (($auto_play == "true") && ($show_bar == "true") && ($frontend_edit == "false")) {
					$output .= '<div id="ts-owlslider-progressbar-' . $testimonial_random . '" class="ts-owlslider-progressbar-holder" style=""><div class="ts-owlslider-progressbar" style="background: ' . $bar_color . '; height: 100%; width: 0%;"></div></div>';
				}
				// Add Navigation Controls
				if ($frontend_edit == "false") {
					$output .= '<div id="ts-owlslider-controls-' . $testimonial_random . '" class="ts-owlslider-controls" style="' . ((($auto_play == "true") || ($show_navigation == "true")) ? "display: block;" : "display: none;") . '">';
						$output .= '<div id="ts-owlslider-controls-next-' . $testimonial_random . '" style="' . (($show_navigation == "true") ? "display: block;" : "display: none;") . '" class="ts-owlslider-controls-next"><span class="ts-ecommerce-arrowright5"></span></div>';
						$output .= '<div id="ts-owlslider-controls-prev-' . $testimonial_random . '" style="' . (($show_navigation == "true") ? "display: block;" : "display: none;") . '" class="ts-owlslider-controls-prev"><span class="ts-ecommerce-arrowleft5"></span></div>';
						if ($auto_play == "true") {
							$output .= '<div id="ts-owlslider-controls-play-' . $testimonial_random . '" class="ts-owlslider-controls-play active"><span class="ts-ecommerce-pause"></span></div>';
						}
					$output .= '</div>';
				}
				// Add Slider
				$output .= '<div id="' . $testimonial_slider_id . '" class="' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;" data-id="' . $testimonial_random . '" data-items="' . $testimonials_slide . '" data-rtl="' . $page_rtl . '" data-loop="' . $items_loop . '" data-navigation="' . $show_navigation . '" data-dots="' . $show_dots . '" data-mobile="' . $animation_mobile . '" data-animationin="' . $animation_in . '" data-animationout="' . $animation_out . '" data-height="' . $auto_height . '" data-play="' . $auto_play . '" data-bar="' . $show_bar . '" data-color="' . $bar_color . '" data-speed="' . $show_speed . '" data-hover="' . $stop_hover . '">';
					// Build Testimonial Post Main Content
					foreach ($testimonial_array as $index => $array) {
						$Testimonial_Author				= $testimonial_array[$index]['author'];
						$Testimonial_Name 				= $testimonial_array[$index]['name'];
						$Testimonial_Title 				= $testimonial_array[$index]['title'];
						$Testimonial_ID 				= $testimonial_array[$index]['id'];
						$Testimonial_Content 			= $testimonial_array[$index]['content'];
						//$Testimonial_Category 		= $testimonial_array[$index]['categories'];
						$Testimonial_Image				= wp_get_attachment_image_src(get_post_thumbnail_id($Testimonial_ID), 'full');
						if ($Testimonial_Image == false) {
							$Testimonial_Image          = TS_VCSC_GetResourceURL('images/defaults/default_person.jpg');
						} else {
							$Testimonial_Image          = $Testimonial_Image[0];
						}
						
						// Retrieve Testimonial Post Meta Content
						$custom_fields 						= get_post_custom($Testimonial_ID);
						$custom_fields_array				= array();
						foreach ($custom_fields as $field_key => $field_values) {
							if (!isset($field_values[0])) continue;
							if (in_array($field_key, array("_edit_lock", "_edit_last"))) continue;
							if (strpos($field_key, 'ts_vcsc_testimonial_') !== false) {
								$field_key_split 			= explode("_", $field_key);
								$field_key_length 			= count($field_key_split) - 1;
								$custom_data = array(
									'group'					=> $field_key_split[$field_key_length - 1],
									'name'					=> 'Testimonial_' . ucfirst($field_key_split[$field_key_length]),
									'value'					=> $field_values[0],
								);
								$custom_fields_array[] = $custom_data;
							}
						}
						foreach ($custom_fields_array as $index => $array) {
							${$custom_fields_array[$index]['name']} = $custom_fields_array[$index]['value'];
						}
						if (isset($Testimonial_Position)) {
							$Testimonial_Position 			= $Testimonial_Position;
						} else {
							$Testimonial_Position 			= '';
						}
						if (isset($Testimonial_Author)) {
							$Testimonial_Author 			= $Testimonial_Author;
						} else {
							$Testimonial_Author 			= '';
						}
	
						if ($style == "style1") {
							$output .= '<div class="ts-testimonial-main style1 clearFixMe" style="width: 99%; margin: 0 auto;">';
								$output .= '<div class="ts-testimonial-content">';
									if (($show_avatar == "true") || ($show_author == "true")) {
										$output .= '<span class="ts-testimonial-arrow"></span>';
									}
									if (function_exists('wpb_js_remove_wpautop')){
										$output .= '' . wpb_js_remove_wpautop(do_shortcode($Testimonial_Content), true) . '';
									} else {
										$output .= '' . do_shortcode($Testimonial_Content) . '';
									}
								$output .= '</div>';
								if (($show_avatar == "true") || ($show_author == "true")) {
									$output .= '<div class="ts-testimonial-user">';
										if ($show_avatar == "true") {
											$output .= '<div class="ts-testimonial-user-thumb"><img src="' . $Testimonial_Image . '" alt=""></div>';
										}
										if ($show_author == "true") {
											$output .= '<div class="ts-testimonial-user-name">' . $Testimonial_Author . '</div>';
											$output .= '<div class="ts-testimonial-user-meta">' . $Testimonial_Position . '</div>';
										}
									$output .= '</div>';
								}
							$output .= '</div>';
						}
						if ($style == "style2") {
							$output .= '<div class="ts-testimonial-main style2 clearFixMe" style="width: 99%; margin: 0 auto;">';
								$output .= '<div class="blockquote">';
									$output .= '<span class="leftq quotes"></span>';
										if (function_exists('wpb_js_remove_wpautop')){
											$output .= '' . wpb_js_remove_wpautop(do_shortcode($Testimonial_Content), true) . '';
										} else {
											$output .= '' . do_shortcode($Testimonial_Content) . '';
										}
									$output .= '<span class="rightq quotes"></span>';
								$output .= '</div>';
								if (($show_avatar == "true") || ($show_author == "true")) {
									$output .= '<div class="information">';
										if ($show_avatar == "true") {
											$output .= '<img src="' . $Testimonial_Image . '" style="width: 150px; height: auto; " width="150" height="auto" />';
										}
										if ($show_author == "true") {
											$output .= '<div class="author" style="' . ($show_avatar == "false" ? "margin-left: 15px;" : "") . '">' . $Testimonial_Author . '</div>';
											$output .= '<div class="metadata">' . $Testimonial_Position . '</div>';
										}
									$output .= '</div>';
								}
							$output .= '</div>';
						}
						if ($style == "style3") {
							$output .= '<div class="ts-testimonial-main style3 clearFixMe" style="width: 99%; margin: 0 auto;">';
								if ($show_avatar == "true") {
									$output .= '<div class="photo">';
										$output .= '<img src="' . $Testimonial_Image . '" alt=""/>';
									$output .= '</div>';
								}
								$output .= '<div class="content" style="' . ($show_avatar == "false" ? "margin-left: 0;" : "") . '">';
									$output .= '<span class="laquo"></span>';
										if (function_exists('wpb_js_remove_wpautop')){
											$output .= '' . wpb_js_remove_wpautop(do_shortcode($Testimonial_Content), true) . '';
										} else {
											$output .= '' . do_shortcode($Testimonial_Content) . '';
										}
									$output .= '<span class="raquo"></span>';
								$output .= '</div>';
								if ($show_author == "true") {
									$output .= '<div class="sign">';
										$output .= '<span class="author">' . $Testimonial_Author . '</span>';
										$output .= '<span class="metadata">' . $Testimonial_Position . '</span>';
									$output .= '</div>';
								}
							$output .= '</div>';
						}
						if ($style == "style4") {
							$output .= '<div class="ts-testimonial-main style4 clearFixMe" style="width: 99%; margin: 0 auto 32px auto;">';
								if (($show_avatar == "true") || ($show_author == "true")) {
									$output .= '<div class="ts-testimonial-author-info clearfix">';
										if ($show_avatar == "true") {
											$output .= '<div class="ts-testimonial-author-image">';
												$output .= '<img src="' . $Testimonial_Image . '" alt="">';
												$output .= '<span class="ts-testimonial-author-overlay"></span>';
											$output .= '</div>';
										}
										if ($show_author == "true") {
											$output .= '<span class="ts-testimonial-author-name">' . $Testimonial_Author . '</span>';
											$output .= '<span class="ts-testimonial-author-position">' . $Testimonial_Position . '</span>';
										}
									$output .= '</div>';
								}
								$output .= '<div class="ts-testimonial-statement clearfix">';
									if (function_exists('wpb_js_remove_wpautop')){
										$output .= '' . wpb_js_remove_wpautop(do_shortcode($Testimonial_Content), true) . '';
									} else {
										$output .= '' . do_shortcode($Testimonial_Content) . '';
									}
								$output .= '</div>';			
								$output .= '<div class="ts-testimonial-bottom-arrow"></div>';
							$output .= '</div>';
						}
						
						foreach ($custom_fields_array as $index => $array) {
							unset(${$custom_fields_array[$index]['name']});
						}
						if ($frontend_edit == 'true') {
							break;
						}
					}
				
				$output .= '</div>';
            $output .= '</div>';
			
            echo $output;
            
            $myvariable = ob_get_clean();
            return $myvariable;
        }
	
        // Add Testimonial Elements
        function TS_VCSC_Add_Testimonial_Elements() {
			global $VISUAL_COMPOSER_EXTENSIONS;
            // Add Standalone Testimonial
            if (function_exists('vc_map')) {
                vc_map( array(
                    "name"                              => __( "TS Single Testimonial", "ts_visual_composer_extend" ),
                    "base"                              => "TS_VCSC_Testimonial_Standalone",
                    "icon" 	                            => "icon-wpb-ts_vcsc_testimonial_standalone",
                    "class"                             => "",
                    "category"                          => __( 'VC Extensions', "ts_visual_composer_extend" ),
                    "description"                       => __("Place a single testimonial element", "ts_visual_composer_extend"),
                    //"admin_enqueue_js"                => array(ts_fb_get_resource_url('/js/...')),
                    //"admin_enqueue_css"               => array(ts_fb_get_resource_url('/css/...')),
                    "params"                            => array(
                        // Testimonial Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_1",
                            "value"                     => "Main Content",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "custompost",
                            "heading"                   => __( "Testimonial", "ts_visual_composer_extend" ),
                            "param_name"                => "testimonial",
                            "posttype"                  => "ts_testimonials",
                            "posttaxonomy"              => "ts_testimonials_category",
							"taxonomy"              	=> "ts_testimonials_category",
							"postsingle"				=> "Testimonial",
							"postplural"				=> "Testimonials",
							"postclass"					=> "testimonial",
                            "value"                     => "",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "hidden_input",
                            "heading"                   => __( "Testimonial Name", "ts_visual_composer_extend" ),
                            "param_name"                => "custompost_name",
                            "value"                     => "",
                            "admin_label"		        => true,
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        // Testimonial Design
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_2",
                            "value"                     => "Testimonial Style",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Design", "ts_visual_composer_extend" ),
                            "param_name"                => "style",
                            "value"                     => array(
                                __( 'Style 1', "ts_visual_composer_extend" )          => "style1",
                                __( 'Style 2', "ts_visual_composer_extend" )          => "style2",
                                __( 'Style 3', "ts_visual_composer_extend" )          => "style3",
								__( 'Style 4', "ts_visual_composer_extend" )          => "style4",
                            ),
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "admin_label"               => true,
                            "dependency"                => ""
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Autor Name", "ts_visual_composer_extend" ),
                            "param_name"                => "show_author",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show the author name for the testimonial.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Avatar", "ts_visual_composer_extend" ),
                            "param_name"                => "show_avatar",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show the user avatar for the testimonial.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        // Other Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_3",
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
                    ))
                );
            }
            // Add Single Testimonial (for Custom Slider)
            if (function_exists('vc_map')) {
                vc_map(array(
                    "name"                           	=> __("TS Testimonial Slide", "ts_visual_composer_extend"),
                    "base"                           	=> "TS_VCSC_Testimonial_Single",
                    "class"                          	=> "",
                    "icon"                           	=> "icon-wpb-ts_vcsc_testimonial",
                    "category"                       	=> __("VC Extensions", "ts_visual_composer_extend"),
                    "content_element"                	=> true,
                    "as_child"                       	=> array('only' => 'TS_VCSC_Testimonial_Slider_Custom'),
                    "description"                    	=> __("Add a testimonial slide element", "ts_visual_composer_extend"),
                    "params"                         	=> array(
                        // Testimonial Select
                        array(
                            "type"                  	=> "seperator",
                            "heading"               	=> __( "", "ts_visual_composer_extend" ),
                            "param_name"            	=> "seperator_1",
                            "value"                 	=> "Selections",
                            "description"           	=> __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "custompost",
                            "heading"                   => __( "Testimonial", "ts_visual_composer_extend" ),
                            "param_name"                => "testimonial",
                            "posttype"                  => "ts_testimonials",
                            "posttaxonomy"              => "ts_testimonials_category",
							"taxonomy"              	=> "ts_testimonials_category",
							"postsingle"				=> "Testimonial",
							"postplural"				=> "Testimonials",
							"postclass"					=> "testimonial",
                            "value"                     => "",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                  	=> "hidden_input",
                            "heading"               	=> __( "Testimonial", "ts_visual_composer_extend" ),
                            "param_name"            	=> "custompost_name",
                            "value"                 	=> "",
                            "admin_label"		    	=> true,
                            "description"           	=> __( "", "ts_visual_composer_extend" )
                        ),
                        // Testimonial Design
                        array(
                            "type"                  	=> "seperator",
                            "heading"               	=> __( "", "ts_visual_composer_extend" ),
                            "param_name"            	=> "seperator_2",
                            "value"                 	=> "Testimonial Style",
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
								__( 'Style 4', "ts_visual_composer_extend" )          => "style4",
                            ),
                            "description"           	=> __( "", "ts_visual_composer_extend" ),
                            "admin_label"           	=> true,
                            "dependency"            	=> ""
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Autor Name", "ts_visual_composer_extend" ),
                            "param_name"                => "show_author",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show the author name for the testimonial.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Avatar", "ts_visual_composer_extend" ),
                            "param_name"                => "show_avatar",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show the user avatar for the testimonial.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
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
            // Add Testimonials Slider 1 (Custom Build)
            if (function_exists('vc_map')) {
                vc_map(array(
                   "name"                               => __("TS Testimonials Slider 1", "ts_visual_composer_extend"),
                   "base"                               => "TS_VCSC_Testimonial_Slider_Custom",
                   "class"                              => "",
                   "icon"                               => "icon-wpb-ts_vcsc_testimonial_slider_custom",
                   "category"                           => __("VC Extensions", "ts_visual_composer_extend"),
                   "as_parent"                          => array('only' => 'TS_VCSC_Testimonial_Single'),
                   "description"                        => __("Build a custom Testimonial Slider", "ts_visual_composer_extend"),
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
            // Add Testimonials Slider 2 (by Categories)
            if (function_exists('vc_map')) {
                vc_map( array(
                   "name"                               => __("TS Testimonials Slider 2", "ts_visual_composer_extend"),
                   "base"                               => "TS_VCSC_Testimonial_Slider_Category",
                   "class"                              => "",
                   "icon"                               => "icon-wpb-ts_vcsc_testimonial_slider_category",
                   "category"                           => __("VC Extensions", "ts_visual_composer_extend"),
                   "description"                        => __("Place a Testimonial Slider (by Category)", "ts_visual_composer_extend"),
                   "params"                             => array(
                        // Content Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_1",
                            "value"                     => "Content Settings",
                            "description"               => __( "", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "custompostcat",
                            "heading"                   => __( "Testimonial Categories", "ts_visual_composer_extend" ),
                            "param_name"                => "testimonialcat",
                            "posttype"                  => "ts_testimonials",
                            "posttaxonomy"              => "ts_testimonials_category",
							"taxonomy"              	=> "ts_testimonials_category",
							"postsingle"				=> "Testimonial",
							"postplural"				=> "Testimonials",
							"postclass"					=> "testimonial",
                            "value"                     => "",
                            "description"               => __( "Please select the testimonial categories you want to use for the slider.", "ts_visual_composer_extend" )
                        ),
                        array(
                            "type"                      => "dropdown",
                            "heading"                   => __( "Design", "ts_visual_composer_extend" ),
                            "param_name"                => "style",
                            "value"                     => array(
                                __( 'Style 1', "ts_visual_composer_extend" )          => "style1",
                                __( 'Style 2', "ts_visual_composer_extend" )          => "style2",
                                __( 'Style 3', "ts_visual_composer_extend" )          => "style3",
								__( 'Style 4', "ts_visual_composer_extend" )          => "style4",
                            ),
                            "description"               => __( "", "ts_visual_composer_extend" ),
                            "admin_label"               => true,
                            "dependency"                => ""
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Autor Name", "ts_visual_composer_extend" ),
                            "param_name"                => "show_author",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show the author name for the testimonial.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
                        array(
                            "type"              	    => "switch",
                            "heading"                   => __( "Show Avatar", "ts_visual_composer_extend" ),
                            "param_name"                => "show_avatar",
                            "value"                     => "true",
                            "on"					    => __( 'Yes', "ts_visual_composer_extend" ),
                            "off"					    => __( 'No', "ts_visual_composer_extend" ),
                            "style"					    => "select",
                            "design"				    => "toggle-light",
                            "admin_label"		        => true,
                            "description"               => __( "Switch the toggle if you want to show the user avatar for the testimonial.", "ts_visual_composer_extend" ),
                            "dependency"                => ""
                        ),
						// Slider Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_2",
                            "value"                     => "Slider Settings",
                            "description"               => __( "", "ts_visual_composer_extend" ),
							"group" 			        => "Slider Settings",
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
							"group" 			        => "Slider Settings",
						),
						array(
							"type"                      => "hidden_input",
							"heading"                   => __( "In-Animation Type", "ts_visual_composer_extend" ),
							"param_name"                => "css3animations_in",
							"value"                     => "",
							"admin_label"		        => true,
							"description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"            	=> "",
							"group" 			        => "Slider Settings",
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
							"group" 			        => "Slider Settings",
						),
						array(
							"type"                      => "hidden_input",
							"heading"                   => __( "Out-Animation Type", "ts_visual_composer_extend" ),
							"param_name"                => "css3animations_out",
							"value"                     => "",
							"admin_label"		        => true,
							"description"               => __( "", "ts_visual_composer_extend" ),
							"dependency"            	=> "",
							"group" 			        => "Slider Settings",
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
							"group" 			        => "Slider Settings",
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
                            "dependency"                => "",
							"group" 			        => "Slider Settings",
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
							"dependency"                => "Slider Settings"
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
                            "dependency"                => "",
							"group" 			        => "Slider Settings",
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
							"group" 			        => "Slider Settings",
                        ),
                        array(
                            "type"                      => "colorpicker",
                            "heading"                   => __( "Progressbar Color", "ts_visual_composer_extend" ),
                            "param_name"                => "bar_color",
                            "value"                     => "#dd3333",
                            "description"               => __( "Define the color of the animated progressbar.", "ts_visual_composer_extend" ),
                            "dependency" 				=> array("element" 	=> "auto_play", "value" 	=> "true"),
							"group" 			        => "Slider Settings",
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
							"group" 			        => "Slider Settings",
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
                            "dependency"                => array( 'element' => "auto_play", 'value' => 'true' ),
							"group" 			        => "Slider Settings",
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
                            "dependency"                => "",
							"group" 			        => "Slider Settings",
                        ),
                        // Other Settings
                        array(
                            "type"                      => "seperator",
                            "heading"                   => __( "", "ts_visual_composer_extend" ),
                            "param_name"                => "seperator_3",
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
                ));
            }

		}
	}
}
// Register Container and Child Shortcode with Visual Composer
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_TS_VCSC_Testimonial_Slider_Custom extends WPBakeryShortCodesContainer {};
}
if (class_exists('WPBakeryShortCode')) {
	class WPBakeryShortCode_TS_VCSC_Testimonial_Standalone extends WPBakeryShortCode {};
    class WPBakeryShortCode_TS_VCSC_Testimonial_Single extends WPBakeryShortCode {};
	class WPBakeryShortCode_TS_VCSC_Testimonial_Slider_Category extends WPBakeryShortCode {};
}
// Initialize "TS Testimonials" Class
if (class_exists('TS_Testimonials')) {
	$TS_Testimonials = new TS_Testimonials;
}