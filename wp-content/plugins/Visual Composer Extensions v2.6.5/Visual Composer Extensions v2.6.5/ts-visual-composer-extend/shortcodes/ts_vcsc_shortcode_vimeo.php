<?php
	add_shortcode('TS-VCSC-Vimeo', 'TS_VCSC_Vimeo_Function');
	function TS_VCSC_Vimeo_Function ($atts, $content = null) {
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

			'lightbox_group_name'			=> 'nachogroup',
			'lightbox_size'					=> 'full',
			'lightbox_effect'				=> 'random',
			'lightbox_speed'				=> 5000,
			'lightbox_social'				=> 'true',
			'lightbox_play'					=> 'false',
			'lightbox_backlight_auto'		=> 'true',
			'lightbox_backlight_color'		=> '#ffffff',
			
			'content_lightbox'				=> 'true',
			'content_vimeo'					=> '',
			'content_vimeo_trigger'			=> 'preview',
			'content_vimeo_title'			=> '',
			'content_vimeo_subtitle'		=> '',
			'content_vimeo_image'			=> '',
			'content_vimeo_image_simple'	=> 'false',
			'content_vimeo_icon'			=> '',
			'content_vimeo_iconsize'		=> 30,
			'content_vimeo_iconcolor' 		=> '#cccccc',
			'content_vimeo_button'			=> '',
			'content_vimeo_buttonstyle'		=> 'style1',
			'content_vimeo_buttontext'		=> '',
			'content_vimeo_text'			=> '',
			'content_raw'					=> '',
			
			'content_tooltip_css'			=> 'false',
			'content_tooltip_content'		=> '',
			'content_tooltip_position'		=> 'ts-simptip-position-top',
			'content_tooltip_style'			=> '',
			
			'margin_top'					=> 0,
			'margin_bottom'					=> 0,
			'el_id'							=> '',
			'el_class'						=> '',
			'css'							=> '',
		), $atts ));
	
		if (!empty($el_id)) {
			$modal_id						= $el_id;
		} else {
			$modal_id						= 'ts-vcsc-vimeo-' . mt_rand(999999, 9999999);
		}

		// Tooltip
		if ($content_tooltip_css == "true") {
			if (strlen($content_tooltip_content) != 0) {
				$vimeo_tooltipclasses		= " ts-simptip-multiline " . $content_tooltip_style . " " . $content_tooltip_position;
				$vimeo_tooltipcontent		= ' data-tstooltip="' . $content_tooltip_content . '"';
			} else {
				$vimeo_tooltipclasses		= "";
				$vimeo_tooltipcontent		= "";
			}
		} else {
			$vimeo_tooltipclasses			= "";
			if (strlen($content_tooltip_content) != 0) {
				$vimeo_tooltipcontent		= ' title="' . $content_tooltip_content . '"';
			} else {
				$vimeo_tooltipcontent		= "";
			}
		}
		
		if ($lightbox_backlight_auto == "false") {
			$nacho_color			= 'data-backlight="' . $lightbox_backlight_color . '"';
		} else {
			$nacho_color			= '';
		}
		
		if ($content_image_responsive == "true") {
			$image_dimensions		= 'width: 100%; height: auto;';
			$parent_dimensions		= 'width: ' . $content_image_width_r . '%; ' . $content_image_height . '';
		} else {
			$image_dimensions		= 'width: 100%; height: auto;';
			$parent_dimensions		= 'width: ' . $content_image_width_f . 'px; ' . $content_image_height . '';
		}
		
		if (preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~', $content_vimeo)) {
			$content_vimeo			= $content_vimeo;
		} else {
			$content_vimeo			= $content_vimeo;
		}
		
		if ($lightbox_play == "true") {
			$video_autoplay			= '?autoplay=1';
		} else {
			$video_autoplay			= '?autoplay=0';
		}
		
		if (function_exists('vc_shortcode_custom_css_class')) {
			$css_class 	= apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . vc_shortcode_custom_css_class($css, ' '), 'TS-VCSC-Vimeo', $atts);
		} else {
			$css_class	= '';
		}
		
		$output						= '';

		if ($content_lightbox == "true") {
			if ($content_vimeo_trigger == "preview") {
				$modal_image = TS_VCSC_VideoImage_Vimeo($content_vimeo);
				if ($modal_image == '') {
					$modal_image = TS_VCSC_GetResourceURL('images/defaults/default_vimeo.jpg');
				}
				if ($vimeo_tooltipcontent != '') {
					$output .= '<div class="' . $modal_id . '-parent nch-holder ' . $vimeo_tooltipclasses . '" ' . $vimeo_tooltipcontent . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
						$output .= '<div id="' . $modal_id . '" class="' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-vimeo ' . $css_class . '" style="width: 100%; height: 100%;">';
				} else {
						$output .= '<div id="' . $modal_id . '" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-vimeo ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
				}
						$output .= '<a href="' . $content_vimeo . '" class="nch-lightbox-media" data-title="' . $content_vimeo_title . '" data-videoplay="' . $lightbox_play . '" data-type="vimeo" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							$output .= '<img src="' . $modal_image . '" title="" style="display: block; ' . $image_dimensions . '">';
							$output .= '<div class="nchgrid-caption"></div>';
							if (!empty($content_vimeo_title)) {
								$output .= '<div class="nchgrid-caption-text">' . $content_vimeo_title . '</div>';
							}
						$output .= '</a>';
					$output .= '</div>';
				if ($vimeo_tooltipcontent != '') {
					$output .= '</div>';
				}
			} else if ($content_vimeo_trigger == "default") {
				$modal_image = TS_VCSC_GetResourceURL('images/defaults/default_vimeo.jpg');
				if ($vimeo_tooltipcontent != '') {
					$output .= '<div class="' . $modal_id . '-parent nch-holder ' . $vimeo_tooltipclasses . '" ' . $vimeo_tooltipcontent . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
						$output .= '<div id="' . $modal_id . '" class="' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-vimeo ' . $css_class . '" style="width: 100%; height: 100%;">';
				} else {
						$output .= '<div id="' . $modal_id . '" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-vimeo ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
				}
						$output .= '<a href="' . $content_vimeo . '" class="nch-lightbox-media" data-title="' . $content_vimeo_title . '" data-videoplay="' . $lightbox_play . '" data-type="vimeo" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
							$output .= '<img src="' . $modal_image . '" title="" style="display: block; ' . $image_dimensions . '">';
							$output .= '<div class="nchgrid-caption"></div>';
							if (!empty($content_vimeo_title)) {
								$output .= '<div class="nchgrid-caption-text">' . $content_vimeo_title . '</div>';
							}
						$output .= '</a>';
					$output .= '</div>';
				if ($vimeo_tooltipcontent != '') {
					$output .= '</div>';
				}
			} else if ($content_vimeo_trigger == "image") {
				$modal_image = wp_get_attachment_image_src($content_vimeo_image, 'large');
				$modal_image = $modal_image[0];
				if ($content_vimeo_image_simple == "false") {
					if ($vimeo_tooltipcontent != '') {
						$output .= '<div class="' . $modal_id . '-parent nch-holder ' . $vimeo_tooltipclasses . '" ' . $vimeo_tooltipcontent . ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
							$output .= '<div id="' . $modal_id . '" class="' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-vimeo ' . $css_class . '" style="width: 100%; height: 100%;">';
					} else {
							$output .= '<div id="' . $modal_id . '" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' nchgrid-item nchgrid-tile nch-lightbox-vimeo ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px; ' . $parent_dimensions . '">';
					}
							$output .= '<a href="' . $content_vimeo . '" class="nch-lightbox-media" data-title="' . $content_vimeo_title . '" data-videoplay="' . $lightbox_play . '" data-type="vimeo" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
								$output .= '<img src="' . $modal_image . '" title="" style="display: block; ' . $image_dimensions . '">';
								$output .= '<div class="nchgrid-caption"></div>';
								if (!empty($content_vimeo_title)) {
									$output .= '<div class="nchgrid-caption-text">' . $content_vimeo_title . '</div>';
								}
							$output .= '</a>';
						$output .= '</div>';
					if ($vimeo_tooltipcontent != '') {
						$output .= '</div>';
					}
				} else {
					$output .= '<a href="' . $content_vimeo . '" class="' . $modal_id . '-parent nch-holder nch-lightbox-media ' . $vimeo_tooltipclasses . '" ' . $vimeo_tooltipcontent . ' style="' . $parent_dimensions . '" data-title="' . $content_vimeo_title . '" data-videoplay="' . $lightbox_play . '" data-type="vimeo" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
						$output .= '<img class="" src="' . $modal_image . '" style="display: block; ' . $image_dimensions . '">';
					$output .= '</a>';
				}
			} else if ($content_vimeo_trigger == "icon") {
				$icon_style = 'color: ' . $content_vimeo_iconcolor . '; width:' . $content_vimeo_iconsize . 'px; height:' . $content_vimeo_iconsize . 'px; font-size:' . $content_vimeo_iconsize . 'px; line-height:' . $content_vimeo_iconsize . 'px;';
				$output .= '<div id="' . $modal_id . '" style="" class="' . $modal_id . '-parent nch-holder ts-vcsc-font-icon ts-font-icons ts-shortcode ts-icon-align-center ' . $el_class . ' ' . $css_class . '" style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a class="ts-font-icons-link nch-lightbox-media" href="' . $content_vimeo . '" target="_blank" data-title="' . $content_vimeo_title . '" data-videoplay="' . $lightbox_play . '" data-type="vimeo" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '>';
						$output .= '<span class="' . $vimeo_tooltipclasses . '" ' . $vimeo_tooltipcontent . '>';
							$output .= '<i class="ts-font-icon ' . $content_vimeo_icon . '" style="' . $icon_style . '"></i>';
						$output .= '</span>';
					$output .= '</a>';
				$output .= '</div>';
			} else if ($content_vimeo_trigger == "winged") {
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' ' . $css_class . '" style="display: block; width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<div class="ts-lightbox-button-1 clearFixMe">';
						$output .= '<div class="top">' . $content_vimeo_title . '</div>';
						$output .= '<div class="bottom">' . $content_vimeo_subtitle . '</div>';
						$output .= '<a href="' . $content_vimeo . '" class="nch-lightbox-media icon" data-title="' . $content_vimeo_title . '" data-videoplay="' . $lightbox_play . '" data-type="vimeo" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '><span class="vimeo">' . $content_vimeo_buttontext . '</span></a>';
					$output .= '</div>';
				$output .= '</div>';
			} else if ($content_vimeo_trigger == "simple") {
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' ' . $vimeo_tooltipclasses . ' ' . $css_class . '" ' . $vimeo_tooltipcontent . ' style="display: block; width: 100%; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="' . $content_vimeo . '" class="ts-lightbox-button-2 icon nch-lightbox-media" data-title="' . $content_vimeo_title . '" data-videoplay="' . $lightbox_play . '" data-type="vimeo" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . '><span class="vimeo">' . $content_vimeo_buttontext . '</span></a>';
				$output .= '</div>';
			} else if ($content_vimeo_trigger == "text") {
				$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' ' . $css_class . '" style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
					$output .= '<a href="' . $content_vimeo . '" class="nch-lightbox-media ' . $vimeo_tooltipclasses . '" ' . $vimeo_tooltipcontent . ' data-title="' . $content_vimeo_title . '" data-videoplay="' . $lightbox_play . '" data-type="vimeo" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . ' target="_blank">' . $content_vimeo_text . '</a>';
				$output .= '</div>';
			} else if ($content_vimeo_trigger == "custom") {
				if ($content_raw != "") {
					$content_raw =  rawurldecode(base64_decode(strip_tags($content_raw)));
					$output .= '<div id="' . $modal_id . '-trigger" class="' . $modal_id . '-parent nch-holder ' . $el_class . ' ' . $css_class . '" style="text-align: center; margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;">';
						$output .= '<a href="' . $content_vimeo . '" class="nch-lightbox-media ' . $vimeo_tooltipclasses . '" ' . $vimeo_tooltipcontent . ' data-title="' . $content_vimeo_title . '" data-videoplay="' . $lightbox_play . '" data-type="vimeo" rel="' . $lightbox_group_name . '" data-effect="' . $lightbox_effect . '" data-share="' . ($lightbox_social == "true" ? 1 : 0) . '" data-duration="' . $lightbox_speed . '" ' . $nacho_color . 'style="" target="_blank">';
							$output .= $content_raw;
						$output .= '</a>';
					$output .= '</div>';
				}
			}
		} else {
			$modal_image = TS_VCSC_VideoID_Vimeo($content_vimeo);
			$output .= '<div id="' . $modal_id . '" class="ts-video-container">';
				$output .= '<iframe src="//player.vimeo.com/video/' . $modal_image . $video_autoplay . '" width="100%" height="auto" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			$output .= '</div>';
		}

		echo $output;
	
		$myvariable = ob_get_clean();
		return $myvariable;
	}
?>