<?php
	add_shortcode('TS-VCSC-Modal-Popup', 'TS_VCSC_Modal_Function');
	function TS_VCSC_Modal_Function ($atts, $content = null) {
		global $VISUAL_COMPOSER_EXTENSIONS;
		ob_start();

		wp_enqueue_script('ts-extend-hammer');
		wp_enqueue_script('ts-extend-nacho');
		wp_enqueue_style('ts-extend-nacho');
		if ($VISUAL_COMPOSER_EXTENSIONS->TS_VCSC_LoadFrontEndForcable == "false") {
			wp_enqueue_style('ts-extend-simptip');
			wp_enqueue_style('ts-extend-animations');
			wp_enqueue_style('ts-visual-composer-extend-front');
			wp_enqueue_script('ts-visual-composer-extend-front');
		}
		
		extract( shortcode_atts( array(
			'content_image_responsive'		=> 'true',
			'content_image_height'			=> 'height: 100%;',
			'content_image_width_r'			=> 100,
			'content_image_width_f'			=> 300,
			'content_image_size'			=> 'large',

			'lightbox_group'				=> 'false',
			'lightbox_group_name'			=> 'nachogroup',
			'lightbox_size'					=> 'full',
			'lightbox_effect'				=> 'random',
			'lightbox_speed'				=> 5000,
			'lightbox_social'				=> 'true',
			'lightbox_backlight_choice'		=> 'predefined',
			'lightbox_backlight_color'		=> '#0084E2',
			'lightbox_backlight_custom'		=> '#000000',
			
			'lightbox_custom_width'			=> 'false',
			'lightbox_width'				=> 960,
			'lightbox_custom_height'		=> 'false',
			'lightbox_height'				=> 540,
			
			'lightbox_custom_padding'		=> 15,
			'lightbox_custom_background'	=> 'none',
			'lightbox_background_image'		=> '',
			'lightbox_background_size'		=> 'cover',
			'lightbox_background_repeat'	=> 'no-repeat',
			'lightbox_background_color'		=> '#ffffff',
			
			'height'						=> 500,
			'width'							=> 300,
			'content_style'					=> '',
			
			'content_open'					=> 'false',
			'content_open_hide'				=> 'true',
			'content_open_delay'			=> 0,
			
			'content_trigger'				=> '',
			'content_title'					=> '',
			'content_subtitle'				=> '',
			'content_image'					=> '',
			'content_image_simple'			=> 'false',
			'content_icon'					=> '',
			'content_iconsize'				=> 30,
			'content_iconcolor' 			=> '#cccccc',
			'content_button'				=> '',
			'content_buttonstyle'			=> 'style1',
			'content_buttontext'			=> '',
			'content_text'					=> '',
			'content_raw'					=> '',
			
			'content_tooltip_css'			=> 'false',
			'content_tooltip_content'		=> '',
			'content_tooltip_position'		=> 'ts-simptip-position-top',
			'content_tooltip_style'			=> '',
			
			'content_show_title'			=> 'true',
			'title'							=> '',
			'margin_top'					=> 0,
			'margin_bottom'					=> 0,
			'el_id'							=> '',
			'el_class'						=> '',
			'css'							=> '',
		), $atts ));
	
		if (!empty($el_id)) {
			$modal_id						= $el_id;
		} else {
			$modal_id						= 'ts-vcsc-modal-' . mt_rand(999999, 9999999);
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
		
		// Auto-Open Class
		if ($content_open == "true") {
			$modal_openclass				= "nch-lightbox-open";
			if ($content_open_hide == "true") {
				$modal_hideclass			= "nch-lightbox-hide";
			} else {
				$modal_hideclass			= "";
			}
		} else {
			$modal_openclass				= "nch-lightbox-modal";
			$modal_hideclass				= "";
		}
		
		// Backlight Color
		if ($lightbox_backlight_choice == "predefined") {
			$lightbox_backlight_selection	= $lightbox_backlight_color;
		} else {
			$lightbox_backlight_selection	= $lightbox_backlight_custom;
		}

		// Custom Width / Height
		$lightbox_dimensions				= '';
		if ($lightbox_custom_width == "true") {
			//$lightbox_dimensions			.= ' data-width="' . $lightbox_width . '"';
		}
		if ($lightbox_custom_height == "true") {
			//$lightbox_dimensions			.= ' data-height="' . $lightbox_height . '"';
		}
		
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
		
		$output 							= '';
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS-VCSC-Modal-Popup', $atts);
		} else {
			$css_class	= '';
		}
		
		$output .= '<div id="' . $modal_id . '-container" class="ts-vcsc-modal-container">';
			if ($content_trigger == "default") {
				$modal_image = TS_VCSC_GetResourceURL('images/defaults/default_modal.jpg');
				if ($popup_tooltipcontent != '') {
					$output .= '<div class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $popup_tooltipclasses . '" ' . $popup_tooltipcontent . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
						$output .= '<div id="' . $modal_id . '-trigger" class="' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-modal ' . $css_class . '" style="width: 100%; height: 100%;">';
				} else {
						$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-modal ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
				}
						$output .= '<a href="#' . $modal_id . '" class="nch-lightbox-trigger ' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-title="" data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-type="html" rel="" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
							$output .= '<img src="' . $modal_image . '" title="" style="display: block; ' . $image_dimensions . '">';
							$output .= '<div class="nchgrid-caption"></div>';
							if (!empty($content_title)) {
								$output .= '<div class="nchgrid-caption-text">' . $content_title . '</div>';
							}
						$output .= '</a>';
					$output .= '</div>';
				if ($popup_tooltipcontent != '') {
					$output .= '</div>';
				}
			}
			if ($content_trigger == "image") {
				$modal_image = wp_get_attachment_image_src($content_image, 'large');
				$modal_image = $modal_image[0];
				if ($content_image_simple == "false") {
					if ($popup_tooltipcontent != '') {
						$output .= '<div class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $popup_tooltipclasses . '" ' . $popup_tooltipcontent . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
							$output .= '<div id="' . $modal_id . '-trigger" class="' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-modal ' . $css_class . '" style="width: 100%; height: 100%;">';
					} else {
							$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-modal ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
					}
							$output .= '<a href="#' . $modal_id . '" class="nch-lightbox-trigger ' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-title="" data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-type="html" rel="" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
								$output .= '<img src="' . $modal_image . '" title="" style="display: block; ' . $image_dimensions . '">';
								$output .= '<div class="nchgrid-caption"></div>';
								if (!empty($content_title)) {
									$output .= '<div class="nchgrid-caption-text">' . $content_title . '</div>';
								}
							$output .= '</a>';
						$output .= '</div>';
					if ($popup_tooltipcontent != '') {
						$output .= '</div>';
					}
				} else {
					$output .= '<a href="#' . $modal_id . '" class="' . $modal_id . '-parent nch-holder nch-lightbox-modal ' . $popup_tooltipclasses . '" ' . $popup_tooltipcontent . ' style="' . $parent_dimensions . '" ' . $lightbox_dimensions . ' data-title="' . $content_title . '" data-type="html" rel="' . ($lightbox_group == "true" ? "nachogroup" : $lightbox_group) . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						$output .= '<img class="" src="' . $modal_image . '" style="display: block; ' . $image_dimensions . '">';
					$output .= '</a>';
				}
			}
			if ($content_trigger == "icon") {
				$icon_style = 'color: ' . $content_iconcolor . '; width:' . $content_iconsize . 'px; height:' . $content_iconsize . 'px; font-size:' . $content_iconsize . 'px; line-height:' . $content_iconsize . 'px;';
				$output .= '<div id="' . $modal_id . '-trigger" style="" class="' . $modal_id . '-parent nch-holder ts-vcsc-font-icon ts-font-icons ts-shortcode ts-icon-align-center ' . $el_class . ' ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="#' . $modal_id . '" class="' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-title="" data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-type="html" rel="" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
						$output .= '<span class="' . $popup_tooltipclasses . '" ' . $popup_tooltipcontent . '>';
							$output .= '<i class="ts-font-icon ' . $content_icon . '" style="' . $icon_style . '"></i>';
						$output .= '</span>';
					$output .= '</a>';
				$output .= '</div>';
			}
			if ($content_trigger == "winged") {
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $popup_tooltipclasses . ' ' . $css_class . '" ' . $popup_tooltipcontent . ' style="display: block; width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<div class="ts-lightbox-button-1 clearFixMe">';
						$output .= '<div class="top">' . $content_title . '</div>';
						$output .= '<div class="bottom">' . $content_subtitle . '</div>';
						$output .= '<a href="#' . $modal_id . '" class="icon ' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-type="html" rel="" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '"><span class="popup">' . $content_buttontext . '</span></a>';
					$output .= '</div>';
				$output .= '</div>';
			}
			if ($content_trigger == "simple") {
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $popup_tooltipclasses . ' ' . $css_class . '" ' . $popup_tooltipcontent . ' style="display: block; width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="#' . $modal_id . '" class="ts-lightbox-button-2 icon ' . $modal_openclass . '" ' . $lightbox_dimensions . ' data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-type="html" rel="" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '"><span class="popup">' . $content_buttontext . '</span></a>';
				$output .= '</div>';
			}
			if ($content_trigger == "text") {
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $css_class . '" style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="#' . $modal_id . '" class="' . $popup_tooltipclasses . ' ' . $modal_openclass . '" ' . $popup_tooltipcontent . ' ' . $lightbox_dimensions . ' data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-type="html" rel="" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">' . $content_text . '</a>';
				$output .= '</div>';
			}
			if ($content_trigger == "custom") {
				if ($content_raw != "") {
					$content_raw =  rawurldecode(base64_decode(strip_tags($content_raw)));
					$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $modal_hideclass . ' ' . $el_class . ' ' . $css_class . '" style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
						$output .= '<a href="#' . $modal_id . '" class="' . $popup_tooltipclasses . ' ' . $modal_openclass . '" ' . $popup_tooltipcontent . ' ' . $lightbox_dimensions . ' data-open="' . $content_open . '" data-delay="' . $content_open_delay . '" data-type="html" rel="" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" data-color="' . $lightbox_backlight_selection . '">';
							$output .= $content_raw;
						$output .= '</a>';
					$output .= '</div>';
				}
			}
			
			// Create hidden DIV with Modal Content
			$output .= '<div id="' . $modal_id . '" class="ts-modal-content nch-hide-if-javascript ' . $el_class . '" style="display: none; padding: ' . $lightbox_custom_padding . 'px; ' . $lightbox_background . '">';
				$output .= '<div class="ts-modal-white-header"></div>';
				$output .= '<div class="ts-modal-white-frame" style="">';
					$output .= '<div class="ts-modal-white-inner">';
						if (($content_show_title == "true") && ($title != "")) {
							$output .= '<h2 style="border-bottom: 1px solid #eeeeee; padding-bottom: 10px; margin-bottom: 10px;">' . $title . '</h2>';
						}
						if (function_exists('wpb_js_remove_wpautop')){
							$output .= wpb_js_remove_wpautop(do_shortcode($content), true);
						} else {
							$output .= do_shortcode($content);
						}
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
		
		$output .= '</div>';
		
		echo $output;
	
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>